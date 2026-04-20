<?php
/**
 * AUSR CMS - User Management Class
 * إدارة مستخدمي البلجن المستقلين
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_User_Management {

    // ============================================================
    // إضافة مستخدم جديد
    // ============================================================
    public static function add_user( $username, $password ) {
        global $wpdb;

        // التحقق من الصلاحيات
        if ( ! current_user_can( 'manage_options' ) ) {
            return [
                'success' => false,
                'message' => 'ليس لديك صلاحية لإضافة مستخدمين',
                'code'    => 403,
            ];
        }

        // تنظيف المدخلات
        $username = sanitize_user( $username );
        $password = trim( $password );

        // التحقق من البيانات
        if ( empty( $username ) || strlen( $username ) < 3 ) {
            return [
                'success' => false,
                'message' => 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل',
                'code'    => 400,
            ];
        }

        if ( ! preg_match( '/^[a-zA-Z0-9_-]+$/', $username ) ) {
            return [
                'success' => false,
                'message' => 'اسم المستخدم يجب أن يحتوي على حروف إنجليزية، أرقام، شرطة، أو تسطير فقط',
                'code'    => 400,
            ];
        }

        // التحقق من قوة كلمة المرور
        $password_errors = AUSR_Security::validate_password_strength( $password );
        if ( ! empty( $password_errors ) ) {
            return [
                'success' => false,
                'message' => implode( ' — ', $password_errors ),
                'code'    => 400,
            ];
        }

        // التحقق من عدم وجود المستخدم
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                'SELECT COUNT(*) FROM ' . AUSR_Database::table_auth() . ' WHERE username = %s',
                $username
            )
        );

        if ( $exists ) {
            return [
                'success' => false,
                'message' => 'اسم المستخدم موجود بالفعل',
                'code'    => 409,
            ];
        }

        // إضافة المستخدم
        $hash = password_hash( $password, PASSWORD_BCRYPT, [ 'cost' => 12 ] );

        $result = $wpdb->insert(
            AUSR_Database::table_auth(),
            [
                'username'   => $username,
                'password'   => $hash,
                'created_at' => current_time( 'mysql' ),
            ],
            [ '%s', '%s', '%s' ]
        );

        if ( ! $result ) {
            return [
                'success' => false,
                'message' => 'فشل في إضافة المستخدم',
                'code'    => 500,
            ];
        }

        // تسجيل العملية
        AUSR_Database::log( 'user_added', $username, null, $username, wp_get_current_user()->user_login );

        return [
            'success' => true,
            'message' => 'تم إضافة المستخدم بنجاح ✅',
        ];
    }

    // ============================================================
    // حذف مستخدم
    // ============================================================
    public static function delete_user( $username ) {
        global $wpdb;

        // التحقق من الصلاحيات
        if ( ! current_user_can( 'manage_options' ) ) {
            return [
                'success' => false,
                'message' => 'ليس لديك صلاحية لحذف مستخدمين',
                'code'    => 403,
            ];
        }

        // منع حذف المستخدم الافتراضي
        if ( $username === 'ausr_admin' ) {
            return [
                'success' => false,
                'message' => 'لا يمكن حذف المستخدم الافتراضي',
                'code'    => 400,
            ];
        }

        // التحقق من وجود المستخدم
        $user = $wpdb->get_row(
            $wpdb->prepare(
                'SELECT * FROM ' . AUSR_Database::table_auth() . ' WHERE username = %s',
                sanitize_user( $username )
            )
        );

        if ( ! $user ) {
            return [
                'success' => false,
                'message' => 'المستخدم غير موجود',
                'code'    => 404,
            ];
        }

        // حذف التوكنات الخاصة بالمستخدم
        $wpdb->delete(
            $wpdb->prefix . 'ausr_tokens',
            [ 'username' => $username ],
            [ '%s' ]
        );

        // حذف المستخدم
        $result = $wpdb->delete(
            AUSR_Database::table_auth(),
            [ 'username' => $username ],
            [ '%s' ]
        );

        if ( ! $result ) {
            return [
                'success' => false,
                'message' => 'فشل في حذف المستخدم',
                'code'    => 500,
            ];
        }

        // تسجيل العملية
        AUSR_Database::log( 'user_deleted', $username, null, $username, wp_get_current_user()->user_login );

        return [
            'success' => true,
            'message' => 'تم حذف المستخدم بنجاح ✅',
        ];
    }

    // ============================================================
    // جلب قائمة المستخدمين
    // ============================================================
    public static function get_users() {
        global $wpdb;

        // التحقق من الصلاحيات
        if ( ! current_user_can( 'manage_options' ) ) {
            return [
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض المستخدمين',
                'code'    => 403,
            ];
        }

        $users = $wpdb->get_results(
            'SELECT username, last_login, created_at, login_attempts, locked_until 
             FROM ' . AUSR_Database::table_auth() . ' 
             ORDER BY created_at DESC',
            ARRAY_A
        );

        return [
            'success' => true,
            'users'   => $users,
        ];
    }

    // ============================================================
    // تغيير كلمة مرور مستخدم
    // ============================================================
    public static function change_user_password( $username, $new_password ) {
        global $wpdb;

        // التحقق من الصلاحيات
        if ( ! current_user_can( 'manage_options' ) ) {
            return [
                'success' => false,
                'message' => 'ليس لديك صلاحية لتغيير كلمات المرور',
                'code'    => 403,
            ];
        }

        // التحقق من قوة كلمة المرور
        $password_errors = AUSR_Security::validate_password_strength( $new_password );
        if ( ! empty( $password_errors ) ) {
            return [
                'success' => false,
                'message' => implode( ' — ', $password_errors ),
                'code'    => 400,
            ];
        }

        // التحقق من وجود المستخدم
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                'SELECT COUNT(*) FROM ' . AUSR_Database::table_auth() . ' WHERE username = %s',
                sanitize_user( $username )
            )
        );

        if ( ! $exists ) {
            return [
                'success' => false,
                'message' => 'المستخدم غير موجود',
                'code'    => 404,
            ];
        }

        // تحديث كلمة المرور
        $hash = password_hash( $new_password, PASSWORD_BCRYPT, [ 'cost' => 12 ] );

        $result = $wpdb->update(
            AUSR_Database::table_auth(),
            [ 
                'password'      => $hash,
                'login_attempts' => 0,
                'locked_until'   => null,
            ],
            [ 'username' => $username ],
            [ '%s', '%d', '%s' ],
            [ '%s' ]
        );

        if ( ! $result ) {
            return [
                'success' => false,
                'message' => 'فشل في تحديث كلمة المرور',
                'code'    => 500,
            ];
        }

        // حذف التوكنات القديمة للمستخدم
        $wpdb->delete(
            $wpdb->prefix . 'ausr_tokens',
            [ 'username' => $username ],
            [ '%s' ]
        );

        // تسجيل العملية
        AUSR_Database::log( 'password_changed_admin', $username, null, $username, wp_get_current_user()->user_login );

        return [
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح ✅',
        ];
    }
}
