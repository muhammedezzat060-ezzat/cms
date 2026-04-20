<?php
/**
 * AUSR CMS - Standard Form Processor
 * Handles saving content via Standard POST to ensure 100% reliability
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( isset( $_POST['ausr_save_content'] ) && check_admin_referer( 'ausr_save_action', 'ausr_nonce' ) ) {
    $page_key = sanitize_text_field( $_POST['page_key'] );
    $data = $_POST['content_data'] ?? [];

    if ( ! empty( $data ) && is_array( $data ) ) {
        $success_count = 0;
        foreach ( $data as $element_key => $value ) {
            // Get existing type if possible or default to text
            $saved = AUSR_Database::save_element( $page_key, sanitize_text_field( $element_key ), wp_kses_post( $value ) );
            if ( $saved ) $success_count++;
        }
        
        // Log the action
        AUSR_Database::log( 'standard_post_save', $page_key, null, "Saved $success_count elements via Standard POST" );
        
        // Redirect with success message
        wp_redirect( add_query_arg( [ 'page' => 'ausr-cms', 'save_status' => 'success', 'count' => $success_count ], admin_url( 'admin.php' ) ) );
        exit;
    }
}
?>

<div class="ausr-render-map-container">
    <?php
    $json_content = file_get_contents( AUSR_CMS_DIR . 'content.json' );
    $map = json_decode( $json_content, true );
    
    // Labels mapping from Reference Files analysis
    $labels_map = [
        'home' => [
            'home-hero-badge'   => 'شارة البطل (Hero Badge)',
            'home-hero-title-1' => 'العنوان الرئيسي - سطر 1',
            'home-hero-title-2' => 'العنوان الرئيسي - سطر 2',
            'home-hero-title-3' => 'العنوان الرئيسي - سطر 3',
            'home-hero-sub'     => 'النص الوصفي تحت العنوان',
            'home-card-label'   => 'تسمية بطاقة الإحصائيات',
            'home-stat-programs' => 'عدد البرامج (رقم)',
            'home-stat-programs-lbl' => 'تسمية البرامج',
            'home-stat-partnerships' => 'عدد الشراكات (رقم)',
            'home-stat-partnerships-lbl' => 'تسمية الشراكات',
            'home-stat-graduates' => 'عدد الخريجين (رقم)',
            'home-stat-graduates-lbl' => 'تسمية الخريجين',
            'home-stat-accreditation' => 'نسبة الاعتماد (رقم)',
            'home-stat-accreditation-lbl' => 'تسمية الاعتماد',
            'home-about-tag'    => 'وسم من نحن (Tag)',
            'home-about-heading' => 'عنوان قسم من نحن',
            'home-about-body'    => 'محتوى قسم من نحن',
            'home-prog-heading'  => 'عنوان قسم البرامج',
            'home-vision-quote'  => 'نص الرؤية (الاقتباس)',
            'home-footer-copy'   => 'حقوق التذييل (Footer Copy)'
        ],
        'about' => [
            'about-hero-eyebrow' => 'الوسم العلوي (Eyebrow)',
            'about-hero-title'   => 'عنوان الصفحة الرئيسي',
            'about-hero-desc'    => 'وصف البطل (Hero Desc)',
            'about-story-heading' => 'عنوان قصتنا',
            'about-story-p1'      => 'فقرة القصة الأولى',
            'about-story-p2'      => 'فقرة القصة الثانية',
            'about-vision-label'  => 'تسمية الرؤية',
            'about-vision-text'   => 'نص الرؤية الرئيسي',
            'about-stat-programs' => 'عدد البرامج',
            'about-stat-programs-lbl' => 'تسمية البرامج',
            'about-team-heading'  => 'عنوان فريق القيادة'
        ],
        'programs' => [
            'prog-hero-eyebrow' => 'الوسم العلوي',
            'prog-hero-title'   => 'عنوان صفحة البرامج',
            'prog-hero-desc'    => 'وصف البرامج الأكاديمية'
        ],
        'events' => [
            'ev-hero-eyebrow'   => 'الوسم العلوي',
            'ev-hero-title'     => 'عنوان صفحة الفعاليات',
            'ev-hero-desc'      => 'وصف الفعاليات',
            'ev-featured-tag'   => 'وسم الفعالية المميزة',
            'ev-featured-title' => 'عنوان الفعالية المميزة',
            'ev-featured-desc'  => 'وصف الفعالية المميزة'
        ]
    ];
    
    if ( ! $map ) :
        echo '<div class="ausr-alert ausr-alert-error">فشل قراءة ملف content.json - تأكد من صحة الملف.</div>';
    else :
        // Display Save Status
        if ( isset( $_GET['save_status'] ) && $_GET['save_status'] === 'success' ) {
            echo '<div class="ausr-alert ausr-alert-success">✅ تم حفظ ' . intval( $_GET['count'] ) . ' عنصر بنجاح عبر Standard POST!</div>';
        }

        foreach ( $map as $page_key => $elements ) :
            // We group by page_key but render all keys as requested
            ?>
            <div id="sec-<?php echo esc_attr( $page_key ); ?>" class="ausr-section <?php echo $page_key === 'home' ? 'active' : ''; ?>">
                <div class="ausr-section-header">
                    <h2>إدارة محتوى: <?php echo esc_html( strtoupper( $page_key ) ); ?></h2>
                    <p>المصدر: content.json (Data Keys)</p>
                </div>

                <form method="post" action="">
                    <?php wp_nonce_field( 'ausr_save_action', 'ausr_nonce' ); ?>
                    <input type="hidden" name="page_key" value="<?php echo esc_attr( $page_key ); ?>">
                    
                    <div class="ausr-fields-grid">
                        <?php 
                        // Get current DB values to show real state
                        $db_content = AUSR_Database::get_page_content( $page_key );

                        foreach ( $elements as $key => $default_val ) : 
                            $current_val = $db_content[$key]['value'] ?? $default_val;
                            $label = $labels_map[$page_key][$key] ?? $key;
                            $is_textarea = strlen( $default_val ) > 50 || strpos( $default_val, "\n" ) !== false;
                        ?>
                            <div class="ausr-field">
                                <label>
                                    <span class="ausr-field-label-text"><?php echo esc_html( $label ); ?></span>
                                    <span class="ausr-key-label"><?php echo esc_html( $key ); ?></span>
                                </label>
                                <?php if ( $is_textarea ) : ?>
                                    <textarea name="content_data[<?php echo esc_attr( $key ); ?>]" class="ausr-input" rows="4"><?php echo esc_textarea( $current_val ); ?></textarea>
                                <?php else : ?>
                                    <input type="text" name="content_data[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $current_val ); ?>" class="ausr-input">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="ausr-form-footer" style="display: none;">
                        <button type="submit" name="ausr_save_content" class="ausr-btn ausr-btn-primary">
                            💾 حفظ تغييرات <?php echo esc_html( $page_key ); ?> (POST)
                        </button>
                    </div>
                </form>
            </div>
            <?php
        endforeach;
    endif;
    ?>
</div>

<style>
.ausr-field-label-text {
    font-weight: 700;
    color: #1e293b;
    font-size: 14px;
    margin-bottom: 4px;
    display: inline-block;
}
.ausr-key-label {
    font-family: monospace;
    background: #f1f5f9;
    color: #64748b;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    margin-right: 8px;
    border: 1px solid #e2e8f0;
}
.ausr-field {
    margin-bottom: 24px;
    padding: 16px;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}
.ausr-field:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.ausr-section { display: none; }
.ausr-section.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
