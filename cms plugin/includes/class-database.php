<?php
/**
 * AUSR CMS - Database Class
 * مسؤولة عن إنشاء وإدارة جداول قاعدة البيانات
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_Database {

    // ============================================================
    // أسماء الجداول
    // ============================================================
    public static function table_content() {
        global $wpdb;
        return $wpdb->prefix . 'ausr_content';
    }

    public static function table_auth() {
        global $wpdb;
        return $wpdb->prefix . 'ausr_auth';
    }

    public static function table_logs() {
        global $wpdb;
        return $wpdb->prefix . 'ausr_logs';
    }

    // ============================================================
    // تثبيت البلجن - إنشاء الجداول
    // ============================================================
    public static function install() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // ---- جدول المحتوى ----
        $sql_content = "CREATE TABLE IF NOT EXISTS " . self::table_content() . " (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            page_key    VARCHAR(100)        NOT NULL,
            element_key VARCHAR(200)        NOT NULL,
            element_type ENUM('text','html','image','url','number') DEFAULT 'text',
            element_value LONGTEXT,
            font_size   TINYINT(3) UNSIGNED DEFAULT NULL,
            created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY  unique_element (page_key, element_key),
            KEY         idx_page_key (page_key)
        ) $charset;";

        // ---- جدول المصادقة ----
        $sql_auth = "CREATE TABLE IF NOT EXISTS " . self::table_auth() . " (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            username    VARCHAR(100)        NOT NULL UNIQUE,
            password    VARCHAR(255)        NOT NULL,
            last_login  DATETIME            DEFAULT NULL,
            login_attempts TINYINT(3) UNSIGNED DEFAULT 0,
            locked_until DATETIME           DEFAULT NULL,
            created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";

        // ---- جدول التوكنات ----
        $sql_tokens = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ausr_tokens" . " (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            username    VARCHAR(100)        NOT NULL,
            token       VARCHAR(500)        NOT NULL,
            created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
            expires_at  DATETIME            NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY  unique_token (token),
            KEY         idx_username (username),
            KEY         idx_expires (expires_at)
        ) $charset;";

        // ---- جدول السجلات (User Activity): action = نوع العملية، username = من نفّذها ----
        $sql_logs = "CREATE TABLE IF NOT EXISTS " . self::table_logs() . " (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            action      VARCHAR(100)        NOT NULL,
            username    VARCHAR(100)        DEFAULT NULL,
            element_key VARCHAR(200)        DEFAULT NULL,
            old_value   TEXT                DEFAULT NULL,
            new_value   TEXT                DEFAULT NULL,
            ip_address  VARCHAR(45)         DEFAULT NULL,
            user_agent  VARCHAR(500)        DEFAULT NULL,
            created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY         idx_action (action),
            KEY         idx_username (username),
            KEY         idx_created (created_at)
        ) $charset;";

        dbDelta( $sql_content );
        dbDelta( $sql_auth );
        dbDelta( $sql_tokens );
        dbDelta( $sql_logs );

        // حفظ إصدار قاعدة البيانات
        update_option( 'ausr_cms_db_version', AUSR_CMS_DB_VERSION );

        // إنشاء المستخدم الافتراضي
        self::create_default_user();
    }

    // ============================================================
    // ترقية الجداول (إصدارات أقدم من 1.1)
    // ============================================================
    public static function maybe_upgrade() {
        $current = get_option( 'ausr_cms_db_version', '1.0' );
        if ( version_compare( (string) $current, '1.1', '>=' ) ) {
            return;
        }

        global $wpdb;
        $table = self::table_logs();

        // إضافة عمود username لتتبع من نفّذ كل عملية (User Activity Logs)
        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- اسم الجدول من prefix داخلي فقط
        $has_col = $wpdb->get_var(
            $wpdb->prepare(
                "SHOW COLUMNS FROM `{$table}` LIKE %s",
                'username'
            )
        );
        if ( ! $has_col ) {
            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $wpdb->query(
                $wpdb->prepare(
                    "ALTER TABLE `{$table}` ADD COLUMN %s VARCHAR(100) NULL DEFAULT NULL AFTER %s, ADD KEY %s (%s)",
                    'username',
                    'action',
                    'idx_username',
                    'username'
                )
            );
        }

        update_option( 'ausr_cms_db_version', '1.1' );
    }

    // ============================================================
    // إنشاء المستخدم الافتراضي
    // ============================================================
    private static function create_default_user() {
        global $wpdb;

        $default_user = 'ausr_admin';

        $exists = $wpdb->get_var(
            $wpdb->prepare(
                'SELECT COUNT(*) FROM ' . self::table_auth() . ' WHERE username = %s',
                $default_user
            )
        );

        if ( $exists ) return;

        // توليد كلمة مرور عشوائية قوية
        $password = self::generate_strong_password();
        $hash     = password_hash( $password, PASSWORD_BCRYPT, [ 'cost' => 12 ] );

        $wpdb->insert(
            self::table_auth(),
            [
                'username'   => 'ausr_admin',
                'password'   => $hash,
                'created_at' => current_time( 'mysql' ),
            ],
            [ '%s', '%s', '%s' ]
        );

        // حفظ كلمة المرور مؤقتاً للعرض في الـ Admin
        update_option( 'ausr_cms_initial_password', $password );
    }

    // ============================================================
    // توليد كلمة مرور قوية
    // ============================================================
    public static function generate_strong_password( $length = 14 ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$!';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $password .= $chars[ random_int( 0, strlen( $chars ) - 1 ) ];
        }
        return $password;
    }

    // ============================================================
    // إلغاء التفعيل (لا نحذف البيانات)
    // ============================================================
    public static function deactivate() {
        // لا نحذف الجداول عند إلغاء التفعيل
        // نحذفها فقط عند حذف البلجن نهائياً
        wp_clear_scheduled_hook( 'ausr_cms_cleanup_logs' );
    }

    // ============================================================
    // جلب محتوى صفحة كاملة
    // ============================================================
    public static function get_page_content( $page_key ) {
        global $wpdb;

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT element_key, element_type, element_value, font_size
                 FROM " . self::table_content() . "
                 WHERE page_key = %s",
                sanitize_key( $page_key )
            ),
            ARRAY_A
        );

        $result = [];
        foreach ( $rows as $row ) {
            $result[ $row['element_key'] ] = [
                'type'      => $row['element_type'],
                'value'     => $row['element_value'],
                'font_size' => $row['font_size'],
            ];
        }
        return $result;
    }

    // ============================================================
    // حفظ عنصر
    // ============================================================
    public static function save_element( $page_key, $element_key, $value, $type = 'text', $font_size = null ) {
        global $wpdb;

        // تنظيف المدخلات
        $page_key    = sanitize_key( $page_key );
        $element_key = sanitize_text_field( $element_key );
        $type        = in_array( $type, ['text','html','image','url','number'] ) ? $type : 'text';
        $font_size   = $font_size ? absint( $font_size ) : null;

        // تنظيف القيمة حسب النوع
        if ( $type === 'html' ) {
            $value = wp_kses_post( $value );
        } elseif ( $type === 'image' || $type === 'url' ) {
            $value = esc_url_raw( $value );
        } else {
            $value = sanitize_textarea_field( $value );
        }

        $result = $wpdb->replace(
            self::table_content(),
            [
                'page_key'      => $page_key,
                'element_key'   => $element_key,
                'element_type'  => $type,
                'element_value' => $value,
                'font_size'     => $font_size,
            ],
            [ '%s', '%s', '%s', '%s', $font_size ? '%d' : 'NULL' ]
        );

        return $result !== false;
    }

    // ============================================================
    // حفظ متعدد (Bulk Save)
    // ============================================================
    public static function save_bulk( $items ) {
        $success = 0;
        $failed  = 0;

        foreach ( $items as $item ) {
            $saved = self::save_element(
                $item['page_key']    ?? '',
                $item['element_key'] ?? '',
                $item['value']       ?? '',
                $item['type']        ?? 'text',
                $item['font_size']   ?? null
            );
            $saved ? $success++ : $failed++;
        }

        return [ 'success' => $success, 'failed' => $failed ];
    }

    // ============================================================
    // جلب كل المحتوى (للصفحة الأمامية)
    // ============================================================
    public static function get_all_content() {
        global $wpdb;

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT page_key, element_key, element_type, element_value, font_size
                 FROM " . self::table_content() . "
                 ORDER BY page_key, element_key"
            ),
            ARRAY_A
        );

        $result = [];
        foreach ( $rows as $row ) {
            $result[ $row['page_key'] ][ $row['element_key'] ] = [
                'type'      => $row['element_type'],
                'value'     => $row['element_value'],
                'font_size' => $row['font_size'],
            ];
        }
        return $result;
    }

    // ============================================================
    // هل المرفق مذكور في محتوى AUSR؟ (للتحقق قبل السماح بالحذف)
    // ============================================================
    public static function is_attachment_referenced_in_content( $attachment_id ) {
        global $wpdb;

        $attachment_id = absint( $attachment_id );
        if ( ! $attachment_id ) {
            return false;
        }

        $url = wp_get_attachment_url( $attachment_id );
        if ( ! $url ) {
            return false;
        }

        $table = self::table_content();
        $path  = wp_parse_url( $url, PHP_URL_PATH );
        $path  = is_string( $path ) ? $path : '';

        $needles = array_unique(
            array_filter(
                [
                    $url,
                    (string) $attachment_id,
                    str_replace( [ 'http://', 'https://' ], '', $url ),
                    $path,
                ]
            )
        );

        foreach ( $needles as $needle ) {
            $needle = trim( (string) $needle );
            if ( $needle === '' ) {
                continue;
            }
            $like = '%' . $wpdb->esc_like( $needle ) . '%';
            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- اسم الجدول من prefix داخلي
            $count = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$table} WHERE %1s = %2s OR %1s LIKE %3s",
                    'element_value',
                    $needle,
                    'element_value',
                    $like
                )
            );
            if ( $count > 0 ) {
                return true;
            }
        }

        return false;
    }

    // ============================================================
    // تسجيل عملية في السجل (User Activity)
    // action = نوع العملية؛ username = المستخدم (جلسة AUSR أو تمرير صريح مثل فشل الدخول)
    // ============================================================
    public static function log( $action, $element_key = null, $old_value = null, $new_value = null, $actor_username = null ) {
        global $wpdb;

        $username = null;
        if ( $actor_username !== null && $actor_username !== '' ) {
            $username = sanitize_user( wp_unslash( (string) $actor_username ), true );
        } else {
            // محاولة جلب اسم المستخدم من Token
            $token = AUSR_Security::get_bearer_token();
            if ( $token && AUSR_Security::validate_api_token( $token ) ) {
                $username = AUSR_Security::get_username_from_token( $token );
            }
            
            // إذا لم يتم العثور على Token، جلب من WordPress user
            if ( ! $username && is_user_logged_in() ) {
                $current_user = wp_get_current_user();
                $username = $current_user->user_login;
            }
        }

        // تسجيل نوع المستخدم (Admin أو Plugin User)
        $user_type = 'unknown';
        if ( current_user_can( 'manage_options' ) ) {
            $user_type = 'wp_admin';
        } elseif ( $token && AUSR_Security::validate_api_token( $token ) ) {
            $user_type = 'plugin_user';
        }

        $wpdb->insert(
            self::table_logs(),
            [
                'action'      => sanitize_text_field( $action ),
                'username'    => $username ? $username : null,
                'element_key' => $element_key ? sanitize_text_field( $element_key ) : null,
                'old_value'   => $old_value,
                'new_value'   => $new_value . ($user_type ? " [{$user_type}]" : ''),
                'ip_address'  => self::get_client_ip(),
                'user_agent'  => isset( $_SERVER['HTTP_USER_AGENT'] )
                                 ? sanitize_text_field( substr( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ), 0, 500 ) )
                                 : null,
                'created_at'  => current_time( 'mysql' ),
            ],
            [ '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ]
        );
    }

    // ============================================================
    // جلب الـ IP الحقيقي
    // ============================================================
    private static function get_client_ip() {
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
}
