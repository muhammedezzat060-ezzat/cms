<?php
/**
 * AUSR CMS - Security Class
 * مسؤولة عن كل جوانب الأمان
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_Security {

    // ============================================================
    // التحقق من الـ Nonce
    // ============================================================
    public static function verify_nonce( $nonce ) {
        return wp_verify_nonce( $nonce, 'ausr_cms_nonce' );
    }

    // ============================================================
    // is_authenticated - Hybrid Auth with Auto Token
    // ============================================================
    public static function is_authenticated() {
        // WordPress Admin check - Auto create token if needed
        if ( current_user_can( 'manage_options' ) ) {
            $current_user = wp_get_current_user();
            $username = $current_user->user_login;
            
            // Check if admin already has a token
            $existing_token = self::get_stored_token( $username );
            if ( ! $existing_token ) {
                // Auto-create token for WordPress admin
                $token = self::create_api_token( $username );
                self::store_token( $username, $token );
                
                // Log auto token creation
                error_log( "AUSR CMS: Auto-created token for WordPress admin: {$username}" );
            }
            
            return true;
        }
        
        // Bearer Token check
        $token = self::get_bearer_token();
        if ( $token && self::validate_api_token( $token ) ) {
            return true;
        }
        
        return false;
    }

    // ============================================================
    // إنشاء API Token
    // ============================================================
    public static function create_api_token( $username ) {
        $header = json_encode( ['typ' => 'JWT', 'alg' => 'HS256'] );
        $payload = json_encode( [
            'user' => $username,
            'iat' => time(),
            'exp' => time() + ( 8 * 3600 ), // 8 ساعات
        ] );
        
        $base64UrlHeader = str_replace( ['+', '/', '='], ['-', '_', ''], base64_encode( $header ) );
        $base64UrlPayload = str_replace( ['+', '/', '='], ['-', '_', ''], base64_encode( $payload ) );
        
        $signature = hash_hmac( 'sha256', $base64UrlHeader . "." . $base64UrlPayload, self::get_jwt_secret(), true );
        $base64UrlSignature = str_replace( ['+', '/', '='], ['-', '_', ''], base64_encode( $signature ) );
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // ============================================================
    // تخزين Token في قاعدة البيانات
    // ============================================================
    public static function store_token( $username, $token ) {
        global $wpdb;
        
        $wpdb->insert(
            $wpdb->prefix . 'ausr_tokens',
            [
                'username'   => $username,
                'token'      => $token,
                'created_at' => current_time( 'mysql' ),
                'expires_at' => date( 'Y-m-d H:i:s', time() + ( 8 * 3600 ) ),
            ],
            [ '%s', '%s', '%s', '%s' ]
        );
    }

    // ============================================================
    // إبطال Token
    // ============================================================
    public static function invalidate_token( $token ) {
        global $wpdb;
        
        $wpdb->delete(
            $wpdb->prefix . 'ausr_tokens',
            [ 'token' => $token ],
            [ '%s' ]
        );
    }

    // ============================================================
    // جلب Bearer Token من Header
    // ============================================================
    public static function get_bearer_token() {
        // محاولة جلب الـ Authorization header بطرق مختلفة
        $auth_header = null;
        
        // الطريقة 1: $_SERVER (الأكثر توافقية)
        if ( ! empty( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
            $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        }
        // الطريقة 2: REDIRECT_HTTP_AUTHORIZATION (لبعض السيرفرات)
        elseif ( ! empty( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) ) {
            $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        // الطريقة 3: getallheaders() (إذا كانت متاحة)
        elseif ( function_exists( 'getallheaders' ) ) {
            $headers = getallheaders();
            $auth_header = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        }
        
        if ( $auth_header && preg_match( '/Bearer\s+(\S+)/', $auth_header, $matches ) ) {
            return $matches[1];
        }
        
        return null;
    }
    
    // ============================================================
    // get_stored_token - Get existing token for user
    // ============================================================
    public static function get_stored_token( $username ) {
        global $wpdb;
        
        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT token FROM " . $wpdb->prefix . "ausr_tokens WHERE username = %s AND expires_at > %s LIMIT 1",
                $username,
                current_time( 'mysql' )
            )
        );
    }
    
    // ============================================================
    // التحقق من API Token
    // ============================================================
    public static function validate_api_token( $token ) {
        global $wpdb;
        
        $result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM " . $wpdb->prefix . "ausr_tokens WHERE token = %s AND expires_at > %s",
                $token,
                current_time( 'mysql' )
            )
        );
        
        return (int) $result > 0;
    }
    
    // ============================================================
    // جلب اسم المستخدم من Token
    // ============================================================
    public static function get_username_from_token( $token ) {
        global $wpdb;
        
        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT username FROM " . $wpdb->prefix . "ausr_tokens WHERE token = %s AND expires_at > %s",
                $token,
                current_time( 'mysql' )
            )
        );
    }
    
    // ============================================================
    // جلب JWT Secret
    // ============================================================
    private static function get_jwt_secret() {
        $secret = get_option( 'ausr_cms_jwt_secret' );
        if ( ! $secret ) {
            $secret = wp_generate_password( 64, false );
            update_option( 'ausr_cms_jwt_secret', $secret );
        }
        return $secret;
    }

    // ============================================================
    // Rate Limiting - الحماية من الهجمات
    // ============================================================
    public static function check_rate_limit( $action, $limit = 5, $window = 300 ) {
        $ip       = self::get_client_ip();
        $key      = 'ausr_rl_' . md5( $action . $ip );
        $attempts = (int) get_transient( $key );

        if ( $attempts >= $limit ) {
            return false; // تجاوز الحد
        }

        set_transient( $key, $attempts + 1, $window );
        return true;
    }

    // ============================================================
    // إعادة تعيين Rate Limit بعد النجاح
    // ============================================================
    public static function reset_rate_limit( $action ) {
        $ip  = self::get_client_ip();
        $key = 'ausr_rl_' . md5( $action . $ip );
        delete_transient( $key );
    }

    // ============================================================
    // التحقق من قوة كلمة المرور
    // ============================================================
    public static function validate_password_strength( $password ) {
        $errors = [];

        if ( strlen( $password ) < 8 ) {
            $errors[] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
        }
        if ( ! preg_match( '/[A-Z]/', $password ) ) {
            $errors[] = 'يجب أن تحتوي على حرف كبير واحد على الأقل';
        }
        if ( ! preg_match( '/[0-9]/', $password ) ) {
            $errors[] = 'يجب أن تحتوي على رقم واحد على الأقل';
        }

        return $errors;
    }

    // ============================================================
    // تنظيف المدخلات
    // ============================================================
    public static function sanitize_input( $data, $type = 'text' ) {
        switch ( $type ) {
            case 'html':
                return wp_kses_post( $data );
            case 'url':
                return esc_url_raw( $data );
            case 'int':
                return absint( $data );
            case 'email':
                return sanitize_email( $data );
            case 'textarea':
                return sanitize_textarea_field( $data );
            default:
                return sanitize_text_field( $data );
        }
    }

    // ============================================================
    // إرسال Headers الأمان
    // ============================================================
    public static function send_security_headers() {
        if ( ! headers_sent() ) {
            header( 'X-Content-Type-Options: nosniff' );
            header( 'X-Frame-Options: SAMEORIGIN' );
            header( 'X-XSS-Protection: 1; mode=block' );
            header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        }
    }

    // ============================================================
    // جلب الـ IP الحقيقي
    // ============================================================
    public static function get_client_ip() {
        $keys = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];
        foreach ( $keys as $key ) {
            if ( ! empty( $_SERVER[ $key ] ) ) {
                $ip = trim( explode( ',', $_SERVER[ $key ] )[0] );
                if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                    return $ip;
                }
            }
        }
        return '0.0.0.0';
    }

    // ============================================================
    // إرجاع خطأ JSON آمن
    // ============================================================
    public static function json_error( $message, $code = 403 ) {
        return new WP_Error(
            'ausr_cms_error',
            $message,
            [ 'status' => $code ]
        );
    }
}