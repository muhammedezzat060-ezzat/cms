<?php
/**
 * AUSR CMS - Auth Class
 * مسؤولة عن تسجيل الدخول والخروج وإدارة الجلسات
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_Auth {

    private static $max_attempts = 5;
    private static $lockout_time = 900; // 15 دقيقة
    private static $ip_max_attempts = 20; // حد محاولات IP

    // ============================================================
    // تهيئة
    // ============================================================
    public static function init() {
        // لا يحتاج تهيئة إضافية — الـ API بتتولى الباقي
    }

    // ============================================================
    // تسجيل الدخول
    // ============================================================
    public static function login( $username, $password ) {
        global $wpdb;

        // التحقق من حظر IP
        if ( self::is_ip_banned() ) {
            AUSR_Database::log( 'login_blocked', null, null, 'IP banned: ' . AUSR_Security::get_client_ip(), null );
            return [
                'success' => false,
                'message' => 'تم حجب الوصول من عنوان IP الخاص بك',
                'code'    => 429,
            ];
        }

        // Rate Limiting
        if ( ! AUSR_Security::check_rate_limit( 'login', self::$max_attempts, self::$lockout_time ) ) {
            AUSR_Database::log( 'login_blocked', null, null, sanitize_user( $username ), sanitize_user( $username ) );
            self::ban_ip_if_needed(); // حظر IP إذا تجاوز الحد
            return [
                'success' => false,
                'message' => 'تم حجب الوصول مؤقتاً بسبب كثرة المحاولات. حاول بعد 15 دقيقة.',
                'code'    => 429,
            ];
        }

        // جلب المستخدم
        $user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM " . AUSR_Database::table_auth() . " WHERE username = %s",
                sanitize_user( $username )
            )
        );

        if ( ! $user ) {
            AUSR_Database::log( 'login_failed', null, null, 'invalid_username: ' . $username, sanitize_user( $username ) );
            self::increment_ip_attempts();
            return [
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة',
                'code'    => 401,
            ];
        }

        // التحقق من الإغلاق المؤقت
        if ( $user->locked_until && strtotime( $user->locked_until ) > time() ) {
            $remaining = ceil( ( strtotime( $user->locked_until ) - time() ) / 60 );
            return [
                'success' => false,
                'message' => "الحساب مغلق مؤقتاً. حاول بعد {$remaining} دقيقة.",
                'code'    => 429,
            ];
        }

        // التحقق من كلمة المرور
        if ( ! password_verify( $password, $user->password ) ) {
            // زيادة عداد المحاولات الفاشلة
            $attempts = $user->login_attempts + 1;
            $locked_until = null;

            if ( $attempts >= self::$max_attempts ) {
                $locked_until = date( 'Y-m-d H:i:s', time() + self::$lockout_time );
                $attempts     = 0;
            }

            $wpdb->update(
                AUSR_Database::table_auth(),
                [
                    'login_attempts' => $attempts,
                    'locked_until'   => $locked_until,
                ],
                [ 'id' => $user->id ],
                [ '%d', '%s' ],
                [ '%d' ]
            );

            AUSR_Database::log( 'login_failed', null, null, 'invalid_password for user: ' . $username, sanitize_user( $username ) );
            self::increment_ip_attempts();
            return [
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة',
                'code'    => 401,
            ];
        }

        // تسجيل الدخول بنجاح
        $wpdb->update(
            AUSR_Database::table_auth(),
            [
                'last_login'     => current_time( 'mysql' ),
                'login_attempts' => 0,
                'locked_until'   => null,
            ],
            [ 'id' => $user->id ],
            [ '%s', '%d', '%s' ],
            [ '%d' ]
        );

        // إنشاء Token بدلاً من الجلسة
        $token = AUSR_Security::create_api_token( $username );
        
        // تخزين Token في قاعدة البيانات
        AUSR_Security::store_token( $username, $token );

        // إعادة تعيين Rate Limit
        AUSR_Security::reset_rate_limit( 'login' );
        
        // إعادة تعيين محاولات IP
        self::reset_ip_attempts();

        // تسجيل في السجل
        AUSR_Database::log( 'login_success', null, null, $username, sanitize_user( $username ) );

        return [
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح ✅',
            'token'   => $token,
        ];
    }

    // ============================================================
    // تسجيل الخروج
    // ============================================================
    public static function logout() {
        try {
            $token = AUSR_Security::get_bearer_token();
            if ( $token && AUSR_Security::validate_api_token( $token ) ) {
                // تسجيل الخروج عن طريق إبطال الـ Token
                AUSR_Security::invalidate_token( $token );
            }
            
            AUSR_Database::log( 'logout' );
            return [ 'success' => true, 'message' => 'تم تسجيل الخروج' ];
        } catch (Exception $e) {
            error_log("Logout error: " . $e->getMessage());
            return [ 'success' => false, 'message' => 'حدث خطأ أثناء تسجيل الخروج' ];
        }
    }

    // ============================================================
    // تغيير كلمة المرور
    // ============================================================
    public static function change_password( $new_password ) {
        global $wpdb;

        try {
            // التحقق من قوة كلمة المرور
            $errors = AUSR_Security::validate_password_strength( $new_password );
            if ( ! empty( $errors ) ) {
                return [
                    'success' => false,
                    'message' => implode( ' — ', $errors ),
                    'code'    => 400,
                ];
            }

            $hash = password_hash( $new_password, PASSWORD_BCRYPT, [ 'cost' => 12 ] );

            // التحقق من Token بدلاً من الجلسة
            $token = AUSR_Security::get_bearer_token();
            if ( empty( $token ) || ! AUSR_Security::validate_api_token( $token ) ) {
                return [
                    'success' => false,
                    'message' => 'لا توجد جلسة مستخدم صالحة لتغيير كلمة المرور',
                    'code'    => 401,
                ];
            }
            
            // جلب اسم المستخدم من الـ Token
            $session_user = AUSR_Security::get_username_from_token( $token );
            if ( ! $session_user ) {
                return [
                    'success' => false,
                    'message' => 'الـ Token غير صالح',
                    'code'    => 401,
                ];
            }

            $result = $wpdb->update(
                AUSR_Database::table_auth(),
                [ 'password' => $hash ],
                [ 'username' => $session_user ],
                [ '%s' ],
                [ '%s' ]
            );

            if ( $result === false ) {
                return [
                    'success' => false,
                    'message' => 'فشل في تغيير كلمة المرور',
                    'code'    => 500,
                ];
            }

            AUSR_Database::log( 'password_changed' );
            return [ 'success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح ✅' ];
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير كلمة المرور',
                'code'    => 500,
            ];
        }
    }

    // ============================================================
    // التحقق من الجلسة
    // ============================================================
    public static function check_session() {
        try {
            $token = AUSR_Security::get_bearer_token();
            $is_authenticated = AUSR_Security::is_authenticated();
            $username = null;
            
            if ( $is_authenticated && $token ) {
                $username = AUSR_Security::get_username_from_token( $token );
            }
            
            return [
                'logged_in' => $is_authenticated,
                'user'      => $username,
                'token'     => $token,
            ];
        } catch (Exception $e) {
            error_log("Session check error: " . $e->getMessage());
            return [
                'logged_in' => false,
                'user'      => null,
                'token'     => null,
            ];
        }
    }
    
    // ============================================================
    // التحقق من حظر IP
    // ============================================================
    private static function is_ip_banned() {
        $ip = AUSR_Security::get_client_ip();
        $ban_key = 'ausr_ip_ban_' . md5( $ip );
        return get_transient( $ban_key ) !== false;
    }
    
    // ============================================================
    // حظر IP إذا تجاوز الحد
    // ============================================================
    private static function ban_ip_if_needed() {
        $ip = AUSR_Security::get_client_ip();
        $ip_key = 'ausr_ip_attempts_' . md5( $ip );
        $attempts = (int) get_transient( $ip_key );
        
        if ( $attempts >= self::$ip_max_attempts ) {
            $ban_key = 'ausr_ip_ban_' . md5( $ip );
            set_transient( $ban_key, true, 3600 ); // حظر لمدة ساعة
            delete_transient( $ip_key );
        }
    }
    
    // ============================================================
    // زيادة محاولات IP
    // ============================================================
    private static function increment_ip_attempts() {
        $ip = AUSR_Security::get_client_ip();
        $ip_key = 'ausr_ip_attempts_' . md5( $ip );
        $attempts = (int) get_transient( $ip_key );
        set_transient( $ip_key, $attempts + 1, 3600 ); // احتفظ لمدة ساعة
    }
    
    // ============================================================
    // إعادة تعيين محاولات IP
    // ============================================================
    private static function reset_ip_attempts() {
        $ip = AUSR_Security::get_client_ip();
        $ip_key = 'ausr_ip_attempts_' . md5( $ip );
        delete_transient( $ip_key );
    }
}
