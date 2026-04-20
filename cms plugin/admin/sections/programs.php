<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ===== البرامج - البطل ===== -->
<div id="sec-programs-hero" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">🎓 قسم البطل — صفحة البرامج</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('programs', 'prog-hero-eyebrow', 'التاق العلوي', 'text', 'البرامج الأكاديمية'); ?>
            <?php ausr_field('programs', 'prog-hero-title', 'العنوان الرئيسي', 'text', 'برامج أكاديمية تصنع الفرق'); ?>
            <?php ausr_field('programs', 'prog-hero-desc', 'الوصف', 'textarea', 'نقدم مجموعة متنوعة من البرامج...'); ?>
        </div>
    </div>
</div>

<!-- ===== البرامج - البطاقات ===== -->
<div id="sec-programs-cards" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">📚 البرامج الأكاديمية (6 برامج)</div>
        <div class="ausr-card-sub">عنوان ووصف ومدة كل برنامج</div>

        <?php
        $programs = [
            ['01', 'ماجستير إدارة الأعمال',       'masters'],
            ['02', 'دكتوراه العلوم التطبيقية',     'phd'],
            ['03', 'ماجستير تقنية المعلومات',      'masters'],
            ['04', 'برنامج الباحثين المتميزين',    'research'],
            ['05', 'البرنامج المشترك الدولي',      'intl'],
            ['06', 'دكتوراه القانون والسياسات',    'phd'],
        ];
        foreach ( $programs as $prog ) :
            [$num, $title, $cat] = $prog;
        ?>
        <div class="ausr-prog-block">
            <div class="ausr-prog-block-header">برنامج <?php echo $num; ?> — <?php echo $title; ?></div>
            <div class="ausr-fields-grid">
                <?php ausr_field('programs', "prog-{$num}-cat",      "التصنيف",  'text',     $cat); ?>
                <?php ausr_field('programs', "prog-{$num}-title",    "العنوان",  'text',     $title); ?>
                <?php ausr_field('programs', "prog-{$num}-desc",     "الوصف",    'textarea', 'وصف البرنامج...'); ?>
                <?php ausr_field('programs', "prog-{$num}-duration", "المدة",    'text',     '⏱ سنتان'); ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="ausr-fields-grid" style="margin-top:20px">
            <?php ausr_field('programs', 'prog-footer-copy', '©️ حقوق الملكية', 'text', '© 2026 الجامعة الأمريكية...'); ?>
        </div>
    </div>
</div>
