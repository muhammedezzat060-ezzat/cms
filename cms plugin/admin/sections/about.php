<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ===== من نحن - البطل ===== -->
<div id="sec-about-hero" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">🏛️ قسم البطل — صفحة من نحن</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('about', 'about-hero-eyebrow', 'التاق العلوي', 'text',     'من نحن'); ?>
            <?php ausr_field('about', 'about-hero-title',   'العنوان',      'text',     'مؤسسة أكاديمية تبني المستقبل'); ?>
            <?php ausr_field('about', 'about-hero-desc',    'الوصف',        'textarea', 'الجامعة الأمريكية للدراسات...'); ?>
        </div>
    </div>
</div>

<!-- ===== من نحن - القصة ===== -->
<div id="sec-about-story" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">📖 القصة والرؤية</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('about', 'about-story-heading', 'عنوان القسم',  'text',     'قصتنا ورسالتنا'); ?>
            <?php ausr_field('about', 'about-story-p1',      'الفقرة الأولى','textarea', 'أُسست الجامعة...'); ?>
            <?php ausr_field('about', 'about-story-p2',      'الفقرة الثانية','textarea','نؤمن بأن التعليم...'); ?>
            <?php ausr_field('about', 'about-vision-label',  'عنوان الرؤية', 'text',     'رؤيتنا'); ?>
            <?php ausr_field('about', 'about-vision-text',   'نص الرؤية',   'textarea', 'أن نكون جامعةً...'); ?>
        </div>
    </div>
</div>

<!-- ===== من نحن - الإحصائيات ===== -->
<div id="sec-about-stats" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">📈 الإحصائيات — صفحة من نحن</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('about', 'about-stat-programs',         'البرامج — الرقم',         'text', '٢٠+'); ?>
            <?php ausr_field('about', 'about-stat-programs-lbl',     'البرامج — التسمية',       'text', 'برنامج أكاديمي معتمد'); ?>
            <?php ausr_field('about', 'about-stat-partnerships',     'الشراكات — الرقم',        'text', '١٢+'); ?>
            <?php ausr_field('about', 'about-stat-partnerships-lbl', 'الشراكات — التسمية',      'text', 'شراكة دولية'); ?>
            <?php ausr_field('about', 'about-stat-graduates',        'الخريجون — الرقم',        'text', '٥٠٠+'); ?>
            <?php ausr_field('about', 'about-stat-graduates-lbl',    'الخريجون — التسمية',      'text', 'خريج متميز'); ?>
            <?php ausr_field('about', 'about-stat-accreditation',    'الاعتماد — الرقم',        'text', '١٠٠٪'); ?>
            <?php ausr_field('about', 'about-stat-accreditation-lbl','الاعتماد — التسمية',      'text', 'اعتماد رسمي'); ?>
        </div>
    </div>
</div>

<!-- ===== من نحن - الفريق ===== -->
<div id="sec-about-team" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">👥 فريق القيادة</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('about', 'about-team-heading', 'عنوان القسم', 'text', 'قيادة أكاديمية متميزة'); ?>
        </div>

        <?php
        $team = [
            ['01', 'أ.د. محمد الراشد',  'رئيس الجامعة'],
            ['02', 'أ.د. سارة العمري',  'عميد الدراسات العليا'],
            ['03', 'د. خالد المنصور',   'مدير الشراكات الدولية'],
            ['04', 'د. نورة الزهراني',  'مديرة ضمان الجودة'],
        ];
        foreach ( $team as $member ) :
            [$num, $name, $role] = $member;
        ?>
        <div class="ausr-prog-block">
            <div class="ausr-prog-block-header">عضو <?php echo $num; ?> — <?php echo $name; ?></div>
            <div class="ausr-fields-grid">
                <?php ausr_field('about', "about-team-{$num}-name", 'الاسم',   'text', $name); ?>
                <?php ausr_field('about', "about-team-{$num}-role", 'المنصب',  'text', $role); ?>
                <?php ausr_field('about', "about-team-{$num}-bio",  'النبذة',  'text', 'خبرة أكاديمية...'); ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="ausr-fields-grid" style="margin-top:20px">
            <?php ausr_field('about', 'about-footer-copy', '©️ حقوق الملكية', 'text', '© 2026 الجامعة الأمريكية...'); ?>
        </div>
    </div>
</div>
