<?php
/**
 * Plugin Name: AUSR CMS
 * Plugin URI:  https://ausruniversity.com
 * Description: لوحة تحكم احترافية لإدارة محتوى موقع الجامعة الأمريكية للدراسات والبحوث
 * Version:     1.0.0
 * Author:      AUSR Development Team
 * License:     Proprietary
 * Text Domain: ausr-cms
 */

// ============================================================
// 1. الحماية من الوصول المباشر
// ============================================================
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Access Denied' );
}

// ============================================================
// 2. ثوابت البلجن
// ============================================================
define( 'AUSR_CMS_VERSION',    '1.0.0' );
define( 'AUSR_CMS_DIR',        plugin_dir_path( __FILE__ ) );
define( 'AUSR_CMS_URL',        plugin_dir_url( __FILE__ ) );
define( 'AUSR_CMS_BASENAME',   plugin_basename( __FILE__ ) );
define( 'AUSR_CMS_DB_VERSION', '1.1' );

// Public Read Token for API Access
if ( ! defined( 'AUSR_SECURE_TOKEN' ) ) {
    define( 'AUSR_SECURE_TOKEN', 'AUSR-SECURE-TOKEN-2026-PROD-8X7K9M2N4P5Q6R8S' );
}

// ============================================================
// 3. تحميل الكلاسات
// ============================================================
require_once AUSR_CMS_DIR . 'includes/class-database.php';
require_once AUSR_CMS_DIR . 'includes/class-content-map.php';
require_once AUSR_CMS_DIR . 'includes/class-security.php';
require_once AUSR_CMS_DIR . 'includes/class-auth.php';
require_once AUSR_CMS_DIR . 'includes/class-user-management.php';
require_once AUSR_CMS_DIR . 'includes/class-upload.php';
require_once AUSR_CMS_DIR . 'includes/class-api.php';
require_once AUSR_CMS_DIR . 'includes/functions.php';

// ============================================================
// 4. تفعيل البلجن
// ============================================================
register_activation_hook( __FILE__, [ 'AUSR_Database', 'install' ] );

// ============================================================
// 5. إلغاء تفعيل البلجن
// ============================================================
register_deactivation_hook( __FILE__, [ 'AUSR_Database', 'deactivate' ] );

// ============================================================
// 6. تهيئة البلجن
// ============================================================
add_action( 'plugins_loaded', 'ausr_cms_init' );

function ausr_cms_init() {
    // ترقية جدول السجلات (عمود username) عند الحاجة
    AUSR_Database::maybe_upgrade();

    // تهيئة الـ API
    AUSR_API::init();

    // تهيئة الـ Auth
    AUSR_Auth::init();

    // تسجيل الأنماط والسكربتات
    add_action( 'admin_enqueue_scripts', 'ausr_cms_admin_assets' );
}

/**
 * تحميل الملفات البرمجية والتنسيقية للوحة التحكم
 */
function ausr_cms_admin_assets( $hook ) {
    // تحميل فقط في صفحة البلجن
    if ( strpos( $hook, 'ausr-cms' ) === false && strpos( $hook, 'toplevel_page_ausr-cms' ) === false ) {
        return;
    }

    // Bootstrap 5 CSS (CDN)
    wp_enqueue_style( 'bootstrap5-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css', [], '5.3.2' );
    
    // Custom CSS overrides
    wp_enqueue_style( 'ausr-bootstrap-dashboard-css', AUSR_CMS_URL . 'admin/css/dashboard-bootstrap.css', [ 'bootstrap5-css' ], AUSR_CMS_VERSION );
    
    // Fonts
    wp_enqueue_style( 'tajawal-font', 'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Amiri:wght@400;700&display=swap', [], null );

    // Bootstrap 5 JS (CDN)
    wp_enqueue_script( 'bootstrap5-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], '5.3.2', true );

    // Custom JS
    wp_enqueue_script( 'ausr-bootstrap-dashboard-js', AUSR_CMS_URL . 'admin/js/dashboard-clean.js', [ 'jquery', 'bootstrap5-js' ], AUSR_CMS_VERSION, true );

    // تمرير متغيرات للـ JS
    wp_localize_script( 'ausr-bootstrap-dashboard-js', 'ausrVars', [
        'apiUrl' => esc_url_raw( rest_url( 'ausr/v1/' ) ),
        'nonce'  => wp_create_nonce( 'wp_rest' ),
        'logoutUrl' => wp_logout_url( admin_url('admin.php?page=ausr-cms') )
    ]);
}

// ============================================================
// 7. تسجيل صفحة لوحة التحكم
// ============================================================
function ausr_cms_register_dashboard_page() {
    // إنشاء صفحة لوحة التحكم إذا لم تكن موجودة
    if ( ! get_option( 'ausr_cms_dashboard_page_id' ) ) {
        $page_id = wp_insert_post( [
            'post_title'   => 'لوحة التحكم',
            'post_name'    => 'ausr-dashboard',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '[ausr_cms_dashboard]',
        ] );
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_option( 'ausr_cms_dashboard_page_id', $page_id );
        }
    }
}

// ============================================================
// 8. Shortcode لوحة التحكم
// ============================================================
add_shortcode( 'ausr_cms_dashboard', 'ausr_cms_render_dashboard' );

function ausr_cms_render_dashboard() {
    // منع التخزين المؤقت لصفحة لوحة التحكم
    if ( ! defined( 'DONOTCACHEPAGE' ) ) define( 'DONOTCACHEPAGE', true );
    if ( ! defined( 'DONOTCACHEDB' ) )   define( 'DONOTCACHEDB', true );

    ob_start();
    include AUSR_CMS_DIR . 'admin/dashboard.php';
    return ob_get_clean();
}

// ============================================================
// 9. Assets Loading - Enhanced with Modern Libraries
// ============================================================
function ausr_cms_enqueue_assets() {
    // Modern CSS Framework (Inter Font)
    wp_enqueue_style(
        'ausr-cms-inter-font',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
        [],
        AUSR_CMS_VERSION
    );

    // Material Icons
    wp_enqueue_style(
        'ausr-cms-material-icons',
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        [],
        AUSR_CMS_VERSION
    );

    // Dashboard CSS
    wp_enqueue_style(
        'ausr-cms-dashboard',
        AUSR_CMS_URL . 'admin/css/dashboard.css',
        [ 'ausr-cms-inter-font', 'ausr-cms-material-icons' ],
        AUSR_CMS_VERSION
    );

    // Simple Cards CSS
    wp_enqueue_style(
        'ausr-cms-simple-cards',
        AUSR_CMS_URL . 'admin/css/simple-cards.css',
        [ 'ausr-cms-dashboard' ],
        AUSR_CMS_VERSION
    );

    // JavaScript
    wp_enqueue_script(
        'ausr-cms-dashboard',
        AUSR_CMS_URL . 'admin/js/dashboard.js',
        [ 'jquery' ],
        AUSR_CMS_VERSION,
        true
    );

    // Simple Cards JavaScript
    wp_enqueue_script(
        'ausr-cms-simple-cards',
        AUSR_CMS_URL . 'admin/js/simple-cards.js',
        [ 'ausr-cms-dashboard' ],
        AUSR_CMS_VERSION,
        true
    );

    // Pass data to JavaScript
    wp_localize_script(
        'ausr-cms-dashboard',
        'ausrCMS',
        [
            'apiUrl'   => rest_url( 'ausr/v1' ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
            'siteUrl'  => get_site_url(),
            'version'  => AUSR_CMS_VERSION,
        ]
    );
}

// ============================================================
// 10. إشعار الـ Admin
// ============================================================
function ausr_cms_admin_notice() {
    $plain = get_option( 'ausr_cms_initial_password' );
    if ( ! $plain ) return;

    $dashboard_url = get_permalink( get_option( 'ausr_cms_dashboard_page_id' ) );
    ?>
    <div class="notice notice-success is-dismissible" style="direction:rtl;text-align:right">
        <p><strong>🎉 تم تفعيل AUSR CMS بنجاح!</strong></p>
        <p>
            <strong>اسم المستخدم:</strong> ausr_admin &nbsp;|&nbsp;
            <strong>كلمة المرور:</strong> <code><?php echo esc_html( $plain ); ?></code>
        </p>
        <p>
            <strong>رابط لوحة التحكم:</strong>
            <a href="<?php echo esc_url( $dashboard_url ); ?>" target="_blank">
                <?php echo esc_url( $dashboard_url ); ?>
            </a>
        </p>
        <p style="color:red">
            ⚠️ <strong>احفظ هذه البيانات الآن — لن تظهر مرة أخرى بعد إغلاق هذه الرسالة!</strong>
        </p>
    </div>
    <?php
    // حذف كلمة المرور الأولية بعد العرض
    delete_option( 'ausr_cms_initial_password' );
}
