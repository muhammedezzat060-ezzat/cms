<?php
/**
 * AUSR CMS - Bootstrap 5 Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Verify auth
if ( ! AUSR_Security::is_authenticated() ) {
    include AUSR_CMS_DIR . 'admin/login-screen.php';
    return;
}

// Get Database content map
$json_content = file_get_contents( AUSR_CMS_DIR . 'content.json' );
$map = json_decode( $json_content, true );

// Dynamic Sections based on reference files
$sections = [
    'home'     => [ 'label' => 'الرئيسية', 'icon' => '🏠' ],
    'programs' => [ 'label' => 'البرامج', 'icon' => '🎓' ],
    'events'   => [ 'label' => 'الفعاليات', 'icon' => '📅' ],
    'about'    => [ 'label' => 'من نحن', 'icon' => '🏛️' ],
    'settings' => [ 'label' => 'الإعدادات', 'icon' => '⚙️' ]
];

// Complete Labels mapping for ALL keys from content.json
$labels_map = [
    'home' => [
        'home-hero-badge' => 'شارة البطل',
        'home-hero-title-1' => 'العنوان الرئيسي - السطر الأول',
        'home-hero-title-2' => 'العنوان الرئيسي - السطر الثاني',
        'home-hero-title-3' => 'العنوان الرئيسي - السطر الثالث',
        'home-hero-sub' => 'النص الوصفي تحت العنوان',
        'home-card-label' => 'تسمية بطاقة الإحصائيات',
        'home-stat-programs' => 'عدد البرامج',
        'home-stat-programs-lbl' => 'تسمية البرامج',
        'home-stat-partnerships' => 'عدد الشراكات',
        'home-stat-partnerships-lbl' => 'تسمية الشراكات',
        'home-stat-graduates' => 'عدد الخريجين',
        'home-stat-graduates-lbl' => 'تسمية الخريجين',
        'home-stat-accreditation' => 'نسبة الاعتماد',
        'home-stat-accreditation-lbl' => 'تسمية الاعتماد',
        'home-sbar-programs' => 'عدد البرامج (الشريط الجانبي)',
        'home-sbar-programs-lbl' => 'تسمية البرامج (الشريط الجانبي)',
        'home-sbar-partnerships' => 'عدد الشراكات (الشريط الجانبي)',
        'home-sbar-partnerships-lbl' => 'تسمية الشراكات (الشريط الجانبي)',
        'home-sbar-graduates' => 'عدد الخريجين (الشريط الجانبي)',
        'home-sbar-graduates-lbl' => 'تسمية الخريجين (الشريط الجانبي)',
        'home-sbar-accreditation' => 'نسبة الاعتماد (الشريط الجانبي)',
        'home-sbar-accreditation-lbl' => 'تسمية الاعتماد (الشريط الجانبي)',
        'home-about-tag' => 'وسم من نحن',
        'home-about-heading' => 'عنوان قسم من نحن',
        'home-about-body' => 'محتوى قسم من نحن',
        'home-prog-heading' => 'عنوان قسم البرامج',
        'home-vision-quote' => 'نص الرؤية',
        'home-footer-copy' => 'حقوق التذييل'
    ],
    'programs' => [
        'prog-hero-eyebrow' => 'الوسم العلوي',
        'prog-hero-title' => 'عنوان صفحة البرامج',
        'prog-hero-desc' => 'وصف البرامج الأكاديمية',
        'prog-01-cat' => 'تصنيف البرنامج 1',
        'prog-01-icon' => 'أيقونة البرنامج 1',
        'prog-01-title' => 'عنوان البرنامج 1',
        'prog-01-desc' => 'وصف البرنامج 1',
        'prog-01-tag-1' => 'الوسم الأول للبرنامج 1',
        'prog-01-tag-2' => 'الوسم الثاني للبرنامج 1',
        'prog-01-duration' => 'مدة البرنامج 1',
        'prog-02-cat' => 'تصنيف البرنامج 2',
        'prog-02-icon' => 'أيقونة البرنامج 2',
        'prog-02-title' => 'عنوان البرنامج 2',
        'prog-02-desc' => 'وصف البرنامج 2',
        'prog-02-tag-1' => 'الوسم الأول للبرنامج 2',
        'prog-02-tag-2' => 'الوسم الثاني للبرنامج 2',
        'prog-02-duration' => 'مدة البرنامج 2',
        'prog-03-cat' => 'تصنيف البرنامج 3',
        'prog-03-icon' => 'أيقونة البرنامج 3',
        'prog-03-title' => 'عنوان البرنامج 3',
        'prog-03-desc' => 'وصف البرنامج 3',
        'prog-03-tag-1' => 'الوسم الأول للبرنامج 3',
        'prog-03-tag-2' => 'الوسم الثاني للبرنامج 3',
        'prog-03-duration' => 'مدة البرنامج 3',
        'prog-04-cat' => 'تصنيف البرنامج 4',
        'prog-04-icon' => 'أيقونة البرنامج 4',
        'prog-04-title' => 'عنوان البرنامج 4',
        'prog-04-desc' => 'وصف البرنامج 4',
        'prog-04-tag-1' => 'الوسم الأول للبرنامج 4',
        'prog-04-tag-2' => 'الوسم الثاني للبرنامج 4',
        'prog-04-duration' => 'مدة البرنامج 4',
        'prog-05-cat' => 'تصنيف البرنامج 5',
        'prog-05-icon' => 'أيقونة البرنامج 5',
        'prog-05-title' => 'عنوان البرنامج 5',
        'prog-05-desc' => 'وصف البرنامج 5',
        'prog-05-tag-1' => 'الوسم الأول للبرنامج 5',
        'prog-05-tag-2' => 'الوسم الثاني للبرنامج 5',
        'prog-05-duration' => 'مدة البرنامج 5',
        'prog-06-cat' => 'تصنيف البرنامج 6',
        'prog-06-icon' => 'أيقونة البرنامج 6',
        'prog-06-title' => 'عنوان البرنامج 6',
        'prog-06-desc' => 'وصف البرنامج 6',
        'prog-06-tag-1' => 'الوسم الأول للبرنامج 6',
        'prog-06-tag-2' => 'الوسم الثاني للبرنامج 6',
        'prog-06-duration' => 'مدة البرنامج 6',
        'prog-footer-copy' => 'حقوق التذييل'
    ],
    'events' => [
        'ev-hero-eyebrow' => 'الوسم العلوي',
        'ev-hero-title' => 'عنوان صفحة الفعاليات',
        'ev-hero-desc' => 'وصف الفعاليات',
        'ev-featured-tag' => 'وسم الفعالية المميزة',
        'ev-featured-title' => 'عنوان الفعالية المميزة',
        'ev-featured-desc' => 'وصف الفعالية المميزة',
        'ev-featured-dates' => 'تواريخ الفعالية المميزة',
        'ev-featured-location' => 'موقع الفعالية المميزة',
        'ev-featured-attendees' => 'عدد الحضور',
        'ev-featured-day' => 'اليوم',
        'ev-featured-month' => 'الشهر',
        'ev-featured-year' => 'السنة',
        'ev-card-01-date' => 'تاريخ البطاقة 1',
        'ev-card-01-title' => 'عنوان البطاقة 1',
        'ev-card-01-desc' => 'وصف البطاقة 1',
        'ev-card-01-loc' => 'موقع البطاقة 1',
        'ev-card-02-date' => 'تاريخ البطاقة 2',
        'ev-card-02-title' => 'عنوان البطاقة 2',
        'ev-card-02-desc' => 'وصف البطاقة 2',
        'ev-card-02-loc' => 'موقع البطاقة 2',
        'ev-card-03-date' => 'تاريخ البطاقة 3',
        'ev-card-03-title' => 'عنوان البطاقة 3',
        'ev-card-03-desc' => 'وصف البطاقة 3',
        'ev-card-03-loc' => 'موقع البطاقة 3',
        'ev-card-04-date' => 'تاريخ البطاقة 4',
        'ev-card-04-title' => 'عنوان البطاقة 4',
        'ev-card-04-desc' => 'وصف البطاقة 4',
        'ev-card-04-loc' => 'موقع البطاقة 4',
        'ev-card-05-date' => 'تاريخ البطاقة 5',
        'ev-card-05-title' => 'عنوان البطاقة 5',
        'ev-card-05-desc' => 'وصف البطاقة 5',
        'ev-card-05-loc' => 'موقع البطاقة 5',
        'ev-card-06-date' => 'تاريخ البطاقة 6',
        'ev-card-06-title' => 'عنوان البطاقة 6',
        'ev-card-06-desc' => 'وصف البطاقة 6',
        'ev-card-06-loc' => 'موقع البطاقة 6',
        'ev-footer-copy' => 'حقوق التذييل'
    ],
    'about' => [
        'about-hero-eyebrow' => 'الوسم العلوي',
        'about-hero-title' => 'عنوان الصفحة الرئيسي',
        'about-hero-desc' => 'وصف البطل',
        'about-story-heading' => 'عنوان قصتنا',
        'about-story-p1' => 'فقرة القصة الأولى',
        'about-story-p2' => 'فقرة القصة الثانية',
        'about-vision-label' => 'تسمية الرؤية',
        'about-vision-text' => 'نص الرؤية',
        'about-stat-programs' => 'عدد البرامج',
        'about-stat-programs-lbl' => 'تسمية البرامج',
        'about-stat-partnerships' => 'عدد الشراكات',
        'about-stat-partnerships-lbl' => 'تسمية الشراكات',
        'about-stat-graduates' => 'عدد الخريجين',
        'about-stat-graduates-lbl' => 'تسمية الخريجين',
        'about-stat-accreditation' => 'نسبة الاعتماد',
        'about-stat-accreditation-lbl' => 'تسمية الاعتماد',
        'about-team-heading' => 'عنوان فريق القيادة',
        'about-team-01-name' => 'اسم عضو الفريق 1',
        'about-team-01-role' => 'دور عضو الفريق 1',
        'about-team-01-bio' => 'نبذة عن عضو الفريق 1',
        'about-team-02-name' => 'اسم عضو الفريق 2',
        'about-team-02-role' => 'دور عضو الفريق 2',
        'about-team-02-bio' => 'نبذة عن عضو الفريق 2',
        'about-team-03-name' => 'اسم عضو الفريق 3',
        'about-team-03-role' => 'دور عضو الفريق 3',
        'about-team-03-bio' => 'نبذة عن عضو الفريق 3',
        'about-team-04-name' => 'اسم عضو الفريق 4',
        'about-team-04-role' => 'دور عضو الفريق 4',
        'about-team-04-bio' => 'نبذة عن عضو الفريق 4',
        'about-footer-copy' => 'حقوق التذييل'
    ]
];
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-dark text-white min-vh-100 p-3" style="background: #1e1e2d !important;">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1">AUSR CMS</h4>
                <small class="text-white-50">v<?php echo AUSR_CMS_VERSION; ?></small>
            </div>
            
            <nav class="nav flex-column mb-4">
                <?php foreach ( $sections as $key => $meta ) : ?>
                    <button type="button" class="nav-link text-white py-2 px-3 mb-1 rounded <?php echo $key === 'home' ? 'active' : ''; ?>" data-section-btn="<?php echo esc_attr( $key ); ?>" style="background: <?php echo $key === 'home' ? 'rgba(54, 153, 255, 0.2)' : 'transparent'; ?>; border-right: <?php echo $key === 'home' ? '4px solid #3699ff' : '4px solid transparent'; ?>">
                        <span class="me-2"><?php echo $meta['icon']; ?></span>
                        <span><?php echo esc_html( $meta['label'] ); ?></span>
                    </button>
                <?php endforeach; ?>
            </nav>
            
            <div class="mt-auto pt-3 border-top border-secondary">
                <a href="<?php echo esc_url( home_url() ); ?>" target="_blank" class="text-white-50 text-decoration-none d-block mb-2 small">🌐 عرض الموقع</a>
                <button type="button" class="btn btn-link text-danger p-0 small" id="ausr-logout-trigger">🚪 خروج</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 bg-light">
            <!-- Header -->
            <div class="bg-white shadow-sm p-3 mb-4 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0" id="ausr-active-section-title">الصفحة الرئيسية</h3>
                <button type="button" class="btn btn-primary" id="ausr-global-save">
                    <span class="me-2">💾</span>
                    <span>حفظ التغييرات</span>
                </button>
            </div>

            <!-- Content -->
            <div class="p-3">
                <?php if ( $map ) : ?>
                    <?php foreach ( $map as $page_key => $elements ) : ?>
                        <div id="section-<?php echo esc_attr( $page_key ); ?>" class="ausr-section <?php echo $page_key === 'home' ? '' : 'd-none'; ?>">
                            <form class="ausr-section-form" data-page="<?php echo esc_attr( $page_key ); ?>">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-white">
                                        <h5 class="fw-bold mb-0">إدارة قسم: <?php echo esc_html( $sections[$page_key]['label'] ?? $page_key ); ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                        $db_content = AUSR_Database::get_page_content( $page_key );
                                        foreach ( $elements as $key => $default_val ) : 
                                            $current_val = isset($db_content[$key]['value']) ? $db_content[$key]['value'] : $default_val;
                                            $label = isset($labels_map[$page_key][$key]) ? $labels_map[$page_key][$key] : $key;
                                            $is_textarea = strlen( $default_val ) > 50 || strpos( $default_val, "\n" ) !== false;
                                        ?>
                                            <div class="mb-3">
                                                <label for="<?php echo esc_attr( $key ); ?>" class="form-label fw-bold text-dark">
                                                    <?php echo esc_html( $label ); ?>
                                                    <small class="text-muted ms-2 font-monospace"><?php echo esc_html( $key ); ?></small>
                                                </label>
                                                <?php if ( $is_textarea ) : ?>
                                                    <textarea name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="form-control" rows="4"><?php echo esc_textarea( $current_val ); ?></textarea>
                                                <?php else : ?>
                                                    <input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $current_val ); ?>" class="form-control">
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-warning text-center">
                        <p class="mb-0">⚠️ لم يتم العثور على ملف content.json أو الملف تالف.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toast -->
<div id="ausr-toast" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100; display: none;">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">إشعار</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message">
        </div>
    </div>
</div>
