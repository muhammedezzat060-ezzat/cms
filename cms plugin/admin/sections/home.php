<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ===== الرئيسية - البطل ===== -->
<div id="sec-home-hero" class="ausr-section active">
    <div class="ausr-card">
        <div class="ausr-card-title">🏠 قسم البطل — الصفحة الرئيسية</div>
        <div class="ausr-card-sub">النصوص الرئيسية التي تظهر في أعلى الصفحة الرئيسية</div>
        <div class="ausr-fields-grid">

            <?php ausr_field('home', 'home-hero-badge', 'الشارة العلوية (Badge)', 'text', 'مؤسسة أكاديمية معتمدة رسمياً'); ?>
            <?php ausr_field('home', 'home-hero-title-1', 'سطر العنوان الأول', 'text', 'نصنع قادةَ'); ?>
            <?php ausr_field('home', 'home-hero-title-2', 'سطر العنوان الثاني', 'text', 'ونقود الابتكار'); ?>
            <?php ausr_field('home', 'home-hero-title-3', 'سطر العنوان الثالث', 'text', 'والبحث العلمي'); ?>
            <?php ausr_field('home', 'home-hero-sub', 'الوصف الرئيسي', 'textarea', 'الجامعة الأمريكية للدراسات والبحوث...'); ?>
            <?php ausr_field('home', 'hero-btn1', 'نص زر البرامج', 'text', '🎓 استكشف البرامج'); ?>
            <?php ausr_field('home', 'hero-btn2', 'نص زر من نحن', 'text', 'اعرف أكثر عنا ←'); ?>
            <?php ausr_field('home', 'home-card-label', 'عنوان بطاقة الإحصائيات', 'text', 'إحصائيات الجامعة'); ?>

        </div>
    </div>
</div>

<!-- ===== الرئيسية - الإحصائيات ===== -->
<div id="sec-home-stats" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">📊 الإحصائيات — الصفحة الرئيسية</div>
        <div class="ausr-card-sub">الأرقام في البطاقة وشريط الإحصائيات</div>
        <div class="ausr-fields-grid">

            <?php ausr_field('home', 'home-stat-programs', 'البرامج — رقم البطاقة', 'text', '٢٠+'); ?>
            <?php ausr_field('home', 'home-stat-programs-lbl', 'البرامج — تسمية البطاقة', 'text', 'برنامج أكاديمي'); ?>
            <?php ausr_field('home', 'home-sbar-programs', 'البرامج — رقم الشريط', 'text', '٢٠+'); ?>
            <?php ausr_field('home', 'home-sbar-programs-lbl', 'البرامج — تسمية الشريط', 'text', 'برنامج معتمد'); ?>

            <?php ausr_field('home', 'home-stat-partnerships', 'الشراكات — رقم البطاقة', 'text', '١٢+'); ?>
            <?php ausr_field('home', 'home-stat-partnerships-lbl', 'الشراكات — تسمية البطاقة', 'text', 'شراكة دولية'); ?>
            <?php ausr_field('home', 'home-sbar-partnerships', 'الشراكات — رقم الشريط', 'text', '١٢+'); ?>
            <?php ausr_field('home', 'home-sbar-partnerships-lbl', 'الشراكات — تسمية الشريط', 'text', 'شراكة دولية'); ?>

            <?php ausr_field('home', 'home-stat-graduates', 'الخريجون — رقم البطاقة', 'text', '٥٠٠+'); ?>
            <?php ausr_field('home', 'home-stat-graduates-lbl', 'الخريجون — تسمية البطاقة', 'text', 'خريج متميز'); ?>
            <?php ausr_field('home', 'home-sbar-graduates', 'الخريجون — رقم الشريط', 'text', '٥٠٠+'); ?>
            <?php ausr_field('home', 'home-sbar-graduates-lbl', 'الخريجون — تسمية الشريط', 'text', 'خريج متميز'); ?>

            <?php ausr_field('home', 'home-stat-accreditation', 'الاعتماد — رقم البطاقة', 'text', '١٠٠٪'); ?>
            <?php ausr_field('home', 'home-stat-accreditation-lbl', 'الاعتماد — تسمية البطاقة', 'text', 'اعتماد رسمي'); ?>
            <?php ausr_field('home', 'home-sbar-accreditation', 'الاعتماد — رقم الشريط', 'text', '١٠٠٪'); ?>
            <?php ausr_field('home', 'home-sbar-accreditation-lbl', 'الاعتماد — تسمية الشريط', 'text', 'اعتماد رسمي'); ?>

        </div>
    </div>
</div>

<!-- ===== الرئيسية - من نحن ===== -->
<div id="sec-home-about" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">🏛️ قسم من نحن — الصفحة الرئيسية</div>
        <div class="ausr-fields-grid">

            <?php ausr_field('home', 'home-about-tag', 'التاق', 'text', 'من نحن'); ?>
            <?php ausr_field('home', 'home-about-heading', 'العنوان', 'text', 'مؤسسة أكاديمية تبني المستقبل بعلم وبحث'); ?>
            <?php ausr_field('home', 'home-about-body', 'النص', 'textarea', 'نؤمن بأن التعليم الحقيقي يبدأ بالقيم...'); ?>
            <?php ausr_field('home', 'home-prog-heading', 'عنوان قسم البرامج', 'text', 'البرامج الأكاديمية'); ?>

        </div>
    </div>
</div>

<!-- ===== الرئيسية - الرؤية ===== -->
<div id="sec-home-vision" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">💡 قسم الرؤية — الصفحة الرئيسية</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('home', 'home-vision-quote', 'نص الاقتباس', 'textarea', 'أن نكون جامعةً ذكيةً رائدةً...'); ?>
        </div>
    </div>
</div>

<!-- ===== الرئيسية - التذييل ===== -->
<div id="sec-home-footer" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">📋 التذييل — الصفحة الرئيسية</div>
        <div class="ausr-fields-grid">

            <?php ausr_field('home', 'home-footer-desc', 'النص التعريفي', 'textarea', 'علمٌ يبحث.. ومستقبلٌ يبني.'); ?>
            <?php ausr_field('home', 'home-footer-copy', 'حقوق الملكية', 'text', '© 2026 الجامعة الأمريكية...'); ?>
            <?php ausr_field('home', 'footer-email', 'رابط البريد الإلكتروني', 'url', 'mailto:info@ausruniversity.com'); ?>
            <?php ausr_field('home', 'footer-whatsapp', 'رابط واتساب', 'url', 'https://wa.me/20xxxxxxxxx'); ?>
            <?php ausr_field('home', 'uni-logo', 'رابط الشعار', 'url', 'https://ausruniversity.com/wp-content/uploads/...'); ?>

            <!-- معاينة الشعار -->
            <div class="ausr-field-wrap ausr-field-full">
                <div class="ausr-logo-preview">
                    <img id="ausr-logo-preview-img"
                         src="https://ausruniversity.com/wp-content/uploads/2026/03/cropped-cropped-logo-1-scaled-1.png"
                         alt="Logo Preview" />
                    <span>معاينة الشعار</span>
                </div>
            </div>

        </div>
    </div>
</div>
