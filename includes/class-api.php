<?php
/**
 * AUSR CMS - API Class
 * مسؤولة عن كل الـ REST API endpoints
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_API {

    private static $namespace = 'ausr/v1';

    // ============================================================
    // تهيئة الـ Routes
    // ============================================================
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
    }

    // ============================================================
    // تسجيل الـ Routes
    //
    // صلاحيات الوصول:
    // - login / session: تبقى مفتوحة (__return_true) لأن is_user_logged_in() قبل الدخول مستحيلة منطقياً.
    // - logout: تتطلب جلسة AUSR (check_auth).
    // - جلب المحتوى GET: قراءة عامة (Public Read) — افتراضياً للجميع؛ إن وُجد رمز في wp-config أو الخيارات
    //   يُشترط تطابق ترويسة X-AUSR-Token (وسيلة اختيارية لا تعيق زوار الموقع في الواجهة العادية).
    // ============================================================
    public static function register_routes() {

        // --- تسجيل الدخول ---
        register_rest_route( self::$namespace, '/login', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_login' ],
            'permission_callback' => '__return_true',
            'args'                => [
                'username' => [ 
                    'required' => true, 
                    'sanitize_callback' => 'sanitize_user',
                    'validate_callback' => [ __CLASS__, 'validate_username' ]
                ],
                'password' => [ 
                    'required' => true,
                    'validate_callback' => [ __CLASS__, 'validate_password' ]
                ],
            ],
        ] );

        // --- تسجيل الخروج ---
        register_rest_route( self::$namespace, '/logout', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_logout' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
        ] );

        // --- التحقق من الجلسة ---
        register_rest_route( self::$namespace, '/session', [
            'methods'             => 'GET',
            'callback'            => [ __CLASS__, 'handle_check_session' ],
            'permission_callback' => '__return_true',
        ] );

        // --- جلب كل المحتوى (للصفحة الأمامية) ---
        register_rest_route( self::$namespace, '/content', [
            'methods'             => 'GET',
            'callback'            => [ __CLASS__, 'handle_get_all_content' ],
            'permission_callback' => [ __CLASS__, 'permission_public_read' ],
        ] );

        // --- جلب محتوى صفحة ---
        register_rest_route( self::$namespace, '/content/(?P<page_key>[a-zA-Z0-9_-]+)', [
            'methods'             => 'GET',
            'callback'            => [ __CLASS__, 'handle_get_page_content' ],
            'permission_callback' => [ __CLASS__, 'permission_public_read' ],
            'args'                => [
                'page_key' => [
                    'required' => true,
                    'validate_callback' => [ __CLASS__, 'validate_page_key' ]
                ]
            ]
        ] );

        // --- حفظ عنصر ---
        register_rest_route( self::$namespace, '/content', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_save_element' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
            'args'                => [
                'page_key'    => [ 'required' => true, 'validate_callback' => [ __CLASS__, 'validate_page_key' ] ],
                'element_key' => [ 'required' => true, 'validate_callback' => [ __CLASS__, 'validate_element_key' ] ],
                'value'       => [ 'required' => true ],
                'type'        => [ 'required' => false, 'default' => 'text' ],
                'font_size'   => [ 'required' => false, 'validate_callback' => [ __CLASS__, 'validate_font_size' ] ],
            ],
        ] );

        // --- حفظ متعدد ---
        register_rest_route( self::$namespace, '/content/bulk', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_save_bulk' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
            'args'                => [
                'items' => [ 'required' => true, 'validate_callback' => [ __CLASS__, 'validate_bulk_items' ] ],
            ],
        ] );

        // --- رفع ملف ---
        register_rest_route( self::$namespace, '/upload', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_upload' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
        ] );

        // --- تغيير كلمة المرور ---
        register_rest_route( self::$namespace, '/change-password', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_change_password' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
            'args'                => [
                'password' => [ 
                    'required' => true,
                    'validate_callback' => [ __CLASS__, 'validate_new_password' ]
                ],
                'confirm_password' => [ 'required' => true ],
            ],
        ] );

        // --- جلب سجل العمليات ---
        register_rest_route( self::$namespace, '/logs', [
            'methods'             => 'GET',
            'callback'            => [ __CLASS__, 'handle_get_logs' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
            'args'                => [
                'limit' => [
                    'required' => false,
                    'default' => 50,
                    'validate_callback' => [ __CLASS__, 'validate_limit' ]
                ]
            ]
        ] );

        // --- إدارة المستخدمين (للمسؤولين فقط) ---
        register_rest_route( self::$namespace, '/users', [
            'methods'             => 'GET',
            'callback'            => [ __CLASS__, 'handle_get_users' ],
            'permission_callback' => [ __CLASS__, 'check_admin_auth' ],
        ] );

        register_rest_route( self::$namespace, '/users', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_add_user' ],
            'permission_callback' => [ __CLASS__, 'check_admin_auth' ],
            'args'                => [
                'username' => [ 
                    'required' => true, 
                    'sanitize_callback' => 'sanitize_user',
                    'validate_callback' => [ __CLASS__, 'validate_username' ]
                ],
                'password' => [ 
                    'required' => true,
                    'validate_callback' => [ __CLASS__, 'validate_new_password' ]
                ],
            ],
        ] );

        register_rest_route( self::$namespace, '/users/(?P<username>[a-zA-Z0-9_-]+)', [
            'methods'             => 'DELETE',
            'callback'            => [ __CLASS__, 'handle_delete_user' ],
            'permission_callback' => [ __CLASS__, 'check_admin_auth' ],
        ] );

        register_rest_route( self::$namespace, '/users/(?P<username>[a-zA-Z0-9_-]+)/password', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_change_user_password' ],
            'permission_callback' => [ __CLASS__, 'check_admin_auth' ],
            'args'                => [
                'password' => [ 
                    'required' => true,
                    'validate_callback' => [ __CLASS__, 'validate_new_password' ]
                ],
            ],
        ] );

        // --- مكتبة صور AUSR (مرفقات تحمل الميتا _ausr_cms_upload) — محمية بجلسة المحرر ---
        register_rest_route( self::$namespace, '/ausr-media', [
            'methods'             => 'GET',
            'callback'            => [ __CLASS__, 'handle_get_ausr_media' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
            'args'                => [
                'per_page' => [
                    'required' => false,
                    'default'  => 48,
                    'validate_callback' => [ __CLASS__, 'validate_per_page' ],
                ],
            ],
        ] );
        
        // --- حذف ملف ---
        register_rest_route( self::$namespace, '/delete-file', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'handle_delete_file' ],
            'permission_callback' => [ __CLASS__, 'check_auth' ],
            'args'                => [
                'attachment_id' => [ 'required' => true, 'validate_callback' => [ __CLASS__, 'validate_attachment_id' ] ],
            ],
        ] );
    }

    // ============================================================
    // Validation Callbacks
    // ============================================================
    public static function validate_username( $param, $request, $key ) {
        return !empty( $param ) && strlen( $param ) >= 3;
    }
    
    public static function validate_password( $param, $request, $key ) {
        return !empty( $param );
    }
    
    public static function validate_page_key( $param, $request, $key ) {
        return preg_match( '/^[a-zA-Z0-9_-]+$/', $param );
    }
    
    public static function validate_element_key( $param, $request, $key ) {
        return preg_match( '/^[a-zA-Z0-9_-]+$/', $param );
    }
    
    public static function validate_font_size( $param, $request, $key ) {
        return $param === null || ( is_numeric( $param ) && $param >= 8 && $param <= 120 );
    }
    
    public static function validate_bulk_items( $param, $request, $key ) {
        return is_array( $param ) && count( $param ) > 0 && count( $param ) <= 200;
    }
    
    public static function validate_new_password( $param, $request, $key ) {
        $errors = AUSR_Security::validate_password_strength( $param );
        return empty( $errors );
    }
    
    public static function validate_limit( $param, $request, $key ) {
        return is_numeric( $param ) && $param >= 1 && $param <= 100;
    }
    
    public static function validate_attachment_id( $param, $request, $key ) {
        return is_numeric( $param ) && $param > 0;
    }

    public static function validate_per_page( $param, $request, $key ) {
        return $param === null || ( is_numeric( $param ) && (int) $param >= 1 && (int) $param <= 100 );
    }

    // ============================================================
    // Permission Callback - التحقق من المصادقة
    // ============================================================
    public static function check_auth() {
        if ( ! AUSR_Security::is_authenticated() ) {
            return new WP_Error( 'ausr_unauthorized', 'غير مصرح بالوصول', [ 'status' => 401 ] );
        }
        return true;
    }

    // ============================================================
    // Permission Callback - التحقق من صلاحيات المدير فقط
    // ============================================================
    public static function check_admin_auth() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error( 'ausr_forbidden', 'هذه العملية للمسؤولين فقط', [ 'status' => 403 ] );
        }
        return true;
    }

    /**
     * قراءة عامة للمحتوى (GET): لا تتطلب تسجيل دخول — مناسبة لعرض الموقع للزوار عبر REST.
     * طبقة اختيارية: إذا عُرّف الثابت AUSR_CMS_PUBLIC_READ_TOKEN أو الخيار ausr_cms_public_read_token
     * يجب إرسال نفس القيمة في ترويسة X-AUSR-Token وإلا يُرفض الطلب (حماية إضافية دون إجبار الزائر على حساب WP).
     */
    public static function permission_public_read( WP_REST_Request $request ) {
        $secret = self::get_public_read_secret();
        if ( $secret === '' ) {
            return true;
        }

        $sent = self::extract_x_ausr_token( $request );
        if ( $sent === '' || ! hash_equals( $secret, $sent ) ) {
            return new WP_Error(
                'ausr_read_token',
                'رمز القراءة X-AUSR-Token غير صالح أو مفقود',
                [ 'status' => 403 ]
            );
        }

        return true;
    }

    /**
     * سر اختياري لقفل واجهة جلب المحتوى عن الاستطلاع العشوائي (اختياري بالكامل).
     */
    private static function get_public_read_secret() {
        if ( defined( 'AUSR_CMS_PUBLIC_READ_TOKEN' ) && is_string( AUSR_CMS_PUBLIC_READ_TOKEN ) && AUSR_CMS_PUBLIC_READ_TOKEN !== '' ) {
            return AUSR_CMS_PUBLIC_READ_TOKEN;
        }
        $opt = get_option( 'ausr_cms_public_read_token', '' );
        return is_string( $opt ) && $opt !== '' ? $opt : '';
    }

    /**
     * استخراج ترويسة X-AUSR-Token (مع دعم $_SERVER للبروكسيات).
     */
    private static function extract_x_ausr_token( WP_REST_Request $request ) {
        foreach ( [ 'x_ausr_token', 'X-AUSR-Token', 'X_AUSR_TOKEN' ] as $header_key ) {
            $h = $request->get_header( $header_key );
            if ( is_string( $h ) && $h !== '' ) {
                return trim( $h );
            }
        }
        if ( ! empty( $_SERVER['HTTP_X_AUSR_TOKEN'] ) && is_string( $_SERVER['HTTP_X_AUSR_TOKEN'] ) ) {
            return trim( wp_unslash( $_SERVER['HTTP_X_AUSR_TOKEN'] ) );
        }
        return '';
    }

    // ============================================================
    // تسجيل الدخول
    // ============================================================
    public static function handle_login( WP_REST_Request $request ) {
        AUSR_Security::send_security_headers();

        $username = $request->get_param( 'username' );
        $password = $request->get_param( 'password' );

        if ( empty( $username ) || empty( $password ) ) {
            return self::error_response( 'يرجى إدخال اسم المستخدم وكلمة المرور', 400 );
        }

        try {
            $result = AUSR_Auth::login( $username, $password );
            
            if ( ! $result['success'] ) {
                return self::error_response( $result['message'], $result['code'] ?? 401 );
            }
            
            return rest_ensure_response( $result );
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء تسجيل الدخول', 500 );
        }
    }

    // ============================================================
    // تسجيل الخروج
    // ============================================================
    public static function handle_logout() {
        try {
            $result = AUSR_Auth::logout();
            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Logout error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء تسجيل الخروج', 500 );
        }
    }

    // ============================================================
    // التحقق من الجلسة
    // ============================================================
    public static function handle_check_session() {
        try {
            return rest_ensure_response( AUSR_Auth::check_session() );
        } catch (Exception $e) {
            error_log("Session check error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء التحقق من الجلسة', 500 );
        }
    }

    // ============================================================
    // جلب كل المحتوى
    // ============================================================
    public static function handle_get_all_content() {
        try {
            // التحقق من cache أولاً
            $cache_key = 'ausr_all_content';
            $cached = wp_cache_get( $cache_key );
            
            if ( $cached !== false ) {
                return rest_ensure_response( [ 'success' => true, 'content' => $cached ] );
            }
            
            $content = AUSR_Database::get_all_content();
            
            // تخزين في cache لمدة ساعة
            wp_cache_set( $cache_key, $content, '', 3600 );
            
            return rest_ensure_response( [ 'success' => true, 'content' => $content ] );
        } catch (Exception $e) {
            error_log("Get all content error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء جلب المحتوى', 500 );
        }
    }

    // ============================================================
    // جلب محتوى صفحة
    // ============================================================
    public static function handle_get_page_content( WP_REST_Request $request ) {
        try {
            $page_key = $request->get_param( 'page_key' );
            
            // التحقق من cache
            $cache_key = 'ausr_page_content_' . $page_key;
            $cached = wp_cache_get( $cache_key );
            
            if ( $cached !== false ) {
                return rest_ensure_response( [ 'success' => true, 'content' => $cached ] );
            }
            
            $content = AUSR_Database::get_page_content( $page_key );
            
            // تخزين في cache
            wp_cache_set( $cache_key, $content, '', 1800 );
            
            return rest_ensure_response( [ 'success' => true, 'content' => $content ] );
        } catch (Exception $e) {
            error_log("Get page content error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء جلب محتوى الصفحة', 500 );
        }
    }

    // ============================================================
    // حفظ عنصر
    // ============================================================
    public static function handle_save_element( WP_REST_Request $request ) {
        try {
            $page_key    = $request->get_param( 'page_key' );
            $element_key = $request->get_param( 'element_key' );
            $value       = $request->get_param( 'value' );
            $type        = $request->get_param( 'type' ) ?? 'text';
            $font_size   = $request->get_param( 'font_size' );

            if ( empty( $page_key ) || empty( $element_key ) ) {
                return self::error_response( 'page_key و element_key مطلوبان', 400 );
            }

            // جلب القيمة القديمة للسجل
            $old_content = AUSR_Database::get_page_content( $page_key );
            $old_value   = $old_content[ $element_key ]['value'] ?? null;

            $saved = AUSR_Database::save_element( $page_key, $element_key, $value, $type, $font_size );

            if ( ! $saved ) {
                return self::error_response( 'فشل في حفظ المحتوى', 500 );
            }

            AUSR_Database::log( 'content_saved', $element_key, $old_value, $value );
            
            // مسح cache المحتوى
            wp_cache_delete( 'ausr_all_content' );
            wp_cache_delete( 'ausr_page_content_' . $page_key );
            
            return rest_ensure_response( [ 'success' => true, 'message' => 'تم الحفظ بنجاح ✅' ] );
        } catch (Exception $e) {
            error_log("Save element error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء حفظ المحتوى', 500 );
        }
    }

    // ============================================================
    // حفظ متعدد
    // ============================================================
    public static function handle_save_bulk( WP_REST_Request $request ) {
        try {
            $items = $request->get_param( 'items' );

            if ( empty( $items ) || ! is_array( $items ) ) {
                return self::error_response( 'لا توجد بيانات للحفظ', 400 );
            }

            // حد أقصى 200 عنصر في الطلب
            if ( count( $items ) > 200 ) {
                return self::error_response( 'عدد العناصر كبير جداً (الحد الأقصى 200)', 400 );
            }

            $result = AUSR_Database::save_bulk( $items );
            AUSR_Database::log( 'bulk_save', null, null, "success:{$result['success']}, failed:{$result['failed']}" );
            
            // مسح cache المحتوى
            wp_cache_delete( 'ausr_all_content' );
            
            foreach ( $items as $item ) {
                if ( isset( $item['page_key'] ) ) {
                    wp_cache_delete( 'ausr_page_content_' . $item['page_key'] );
                }
            }

            return rest_ensure_response( [
                'success' => true,
                'message' => "تم حفظ {$result['success']} عنصر بنجاح ✅",
                'details' => $result,
            ] );
        } catch (Exception $e) {
            error_log("Bulk save error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء الحفظ المتعدد', 500 );
        }
    }

    // ============================================================
    // رفع ملف
    // ============================================================
    public static function handle_upload() {
        try {
            if ( empty( $_FILES['file'] ) ) {
                return self::error_response( 'لم يتم اختيار ملف', 400 );
            }

            $result = AUSR_Upload::upload_file( $_FILES['file'] );

            if ( ! $result['success'] ) {
                return self::error_response( $result['message'], $result['code'] ?? 500 );
            }

            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Upload error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء رفع الملف', 500 );
        }
    }

    // ============================================================
    // تغيير كلمة المرور
    // ============================================================
    public static function handle_change_password( WP_REST_Request $request ) {
        try {
            $password = $request->get_param( 'password' );
            $confirm_password = $request->get_param( 'confirm_password' );

            if ( empty( $password ) ) {
                return self::error_response( 'كلمة المرور مطلوبة', 400 );
            }
            
            if ( $password !== $confirm_password ) {
                return self::error_response( 'كلمتا المرور غير متطابقتين', 400 );
            }

            $result = AUSR_Auth::change_password( $password );

            if ( ! $result['success'] ) {
                return self::error_response( $result['message'], $result['code'] ?? 400 );
            }

            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Change password error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء تغيير كلمة المرور', 500 );
        }
    }

    // ============================================================
    // مكتبة الوسائط AUSR (مرفقات بميتا _ausr_cms_upload)
    // ============================================================
    public static function handle_get_ausr_media( WP_REST_Request $request ) {
        try {
            $per_page = min( max( 1, absint( $request->get_param( 'per_page' ) ?: 48 ) ), 100 );

            $query = new WP_Query(
                [
                    'post_type'      => 'attachment',
                    'post_status'    => 'inherit',
                    'posts_per_page' => $per_page,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'meta_query'     => [
                        [
                            'key'     => '_ausr_cms_upload',
                            'compare' => 'EXISTS',
                        ],
                    ],
                    'no_found_rows'  => false,
                ]
            );

            $items = [];
            foreach ( $query->posts as $post ) {
                if ( ! $post instanceof WP_Post ) {
                    continue;
                }
                $id = (int) $post->ID;
                $url = wp_get_attachment_url( $id );
                if ( ! $url ) {
                    continue;
                }
                $items[] = [
                    'id'    => $id,
                    'url'   => $url,
                    'mime'  => (string) get_post_mime_type( $id ),
                    'title' => sanitize_text_field( get_the_title( $id ) ),
                    'thumb' => wp_get_attachment_image_url( $id, 'medium' ) ?: $url,
                ];
            }

            return rest_ensure_response(
                [
                    'success' => true,
                    'items'   => $items,
                    'total'   => (int) $query->found_posts,
                ]
            );
        } catch ( Exception $e ) {
            error_log( 'AUSR media list error: ' . $e->getMessage() );
            return self::error_response( 'تعذّر جلب مكتبة الصور', 500 );
        }
    }

    // ============================================================
    // جلب السجلات
    // ============================================================
    public static function handle_get_logs( WP_REST_Request $request ) {
        try {
            global $wpdb;

            $limit = min( absint( $request->get_param( 'limit' ) ?? 50 ), 100 );

            $logs = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT action, username, element_key, ip_address, created_at
                     FROM " . AUSR_Database::table_logs() . "
                     ORDER BY created_at DESC
                     LIMIT %d",
                    $limit
                ),
                ARRAY_A
            );

            return rest_ensure_response( [ 'success' => true, 'logs' => $logs ] );
        } catch (Exception $e) {
            error_log("Get logs error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء جلب السجلات', 500 );
        }
    }
    
    // ============================================================
    // حذف ملف (تدقيق مزدوج + تسجيل كل محاولة في wp_ausr_logs)
    // ============================================================
    public static function handle_delete_file( WP_REST_Request $request ) {
        $attachment_id = 0;
        try {
            $attachment_id = absint( $request->get_param( 'attachment_id' ) );

            if ( ! $attachment_id ) {
                self::log_file_delete_outcome( 0, 'file_delete_denied', 'رفض: معرف المرفق مفقود أو غير صالح' );
                return self::error_response( 'معرف الملف مطلوب', 400 );
            }

            $attachment = get_post( $attachment_id );
            if ( ! $attachment || $attachment->post_type !== 'attachment' ) {
                self::log_file_delete_outcome( $attachment_id, 'file_delete_denied', 'رفض: المرفق غير موجود أو ليس من نوع attachment' );
                return self::error_response( 'الملف غير موجود', 404 );
            }

            $referenced = AUSR_Database::is_attachment_referenced_in_content( $attachment_id );
            $ausr_meta  = get_post_meta( $attachment_id, '_ausr_cms_upload', true );
            $has_ausr   = ( $ausr_meta !== '' && $ausr_meta !== false && $ausr_meta !== null );

            if ( ! $referenced || ! $has_ausr ) {
                self::log_file_delete_outcome(
                    $attachment_id,
                    'file_delete_denied',
                    'رفض: فشل التدقيق المزدوج (مرتبط_بالمحتوى=' . ( $referenced ? 'نعم' : 'لا' ) . '،ميتا_AUSR=' . ( $has_ausr ? 'نعم' : 'لا' ) . ')'
                );
                return self::error_response(
                    'لا تملك صلاحية حذف هذا الملف أو أنه غير تابع للنظام',
                    403
                );
            }

            $deleted = wp_delete_attachment( $attachment_id, true );

            if ( ! $deleted ) {
                self::log_file_delete_outcome( $attachment_id, 'file_delete_denied', 'فشل: wp_delete_attachment لم يُرجع نجاحاً' );
                return self::error_response( 'فشل في حذف الملف', 500 );
            }

            self::log_file_delete_outcome( $attachment_id, 'file_deleted', 'نجاح: تم حذف المرفق من الوسائط' );

            return rest_ensure_response( [
                'success' => true,
                'message' => 'تم حذف الملف بنجاح ✅',
            ] );
        } catch ( Exception $e ) {
            error_log( 'Delete file error: ' . $e->getMessage() );
            self::log_file_delete_outcome(
                (int) $attachment_id,
                'file_delete_denied',
                'استثناء: ' . sanitize_text_field( substr( $e->getMessage(), 0, 240 ) )
            );
            return self::error_response( 'حدث خطأ أثناء حذف الملف', 500 );
        }
    }

    /**
     * تسجيل محاولة حذف مرفق (نجاح أو رفض أو فشل) مع السبب في new_value — username من الجلسة عبر AUSR_Database::log.
     */
    private static function log_file_delete_outcome( $attachment_id, $action, $reason ) {
        $key = $attachment_id > 0 ? 'attachment_' . $attachment_id : 'attachment_invalid';
        AUSR_Database::log(
            sanitize_text_field( $action ),
            $key,
            null,
            sanitize_text_field( $reason )
        );
    }

    // ============================================================
    // جلب المستخدمين
    // ============================================================
    public static function handle_get_users() {
        try {
            $result = AUSR_User_Management::get_users();
            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Get users error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء جلب المستخدمين', 500 );
        }
    }

    // ============================================================
    // إضافة مستخدم جديد
    // ============================================================
    public static function handle_add_user( WP_REST_Request $request ) {
        try {
            $username = $request->get_param( 'username' );
            $password = $request->get_param( 'password' );

            $result = AUSR_User_Management::add_user( $username, $password );
            
            if ( ! $result['success'] ) {
                return self::error_response( $result['message'], $result['code'] ?? 400 );
            }
            
            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Add user error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء إضافة المستخدم', 500 );
        }
    }

    // ============================================================
    // حذف مستخدم
    // ============================================================
    public static function handle_delete_user( WP_REST_Request $request ) {
        try {
            $username = $request->get_param( 'username' );

            $result = AUSR_User_Management::delete_user( $username );
            
            if ( ! $result['success'] ) {
                return self::error_response( $result['message'], $result['code'] ?? 400 );
            }
            
            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Delete user error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء حذف المستخدم', 500 );
        }
    }

    // ============================================================
    // تغيير كلمة مرور مستخدم
    // ============================================================
    public static function handle_change_user_password( WP_REST_Request $request ) {
        try {
            $username = $request->get_param( 'username' );
            $password = $request->get_param( 'password' );

            $result = AUSR_User_Management::change_user_password( $username, $password );
            
            if ( ! $result['success'] ) {
                return self::error_response( $result['message'], $result['code'] ?? 400 );
            }
            
            return rest_ensure_response( $result );
        } catch (Exception $e) {
            error_log("Change user password error: " . $e->getMessage());
            return self::error_response( 'حدث خطأ أثناء تغيير كلمة المرور', 500 );
        }
    }

    // ============================================================
    // إرجاع خطأ
    // ============================================================
    private static function error_response( $message, $code = 400 ) {
        return new WP_Error( 'ausr_error', $message, [ 'status' => $code ] );
    }
}
