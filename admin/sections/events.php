<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ===== الفعاليات - البطل ===== -->
<div id="sec-events-hero" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">📅 قسم البطل — صفحة الفعاليات</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('events', 'ev-hero-eyebrow', 'التاق العلوي', 'text', 'الفعاليات والمؤتمرات'); ?>
            <?php ausr_field('events', 'ev-hero-title',   'العنوان',      'text', 'فعاليات أكاديمية تصنع الفارق'); ?>
            <?php ausr_field('events', 'ev-hero-desc',    'الوصف',        'textarea', 'مؤتمرات وورش عمل...'); ?>
        </div>
    </div>
</div>

<!-- ===== الفعاليات - المميزة ===== -->
<div id="sec-events-featured" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">⭐ الفعالية المميزة</div>
        <div class="ausr-fields-grid">
            <?php ausr_field('events', 'ev-featured-tag',       'التاق',        'text',     'المؤتمر الرئيسي القادم'); ?>
            <?php ausr_field('events', 'ev-featured-title',     'العنوان',      'text',     'المؤتمر الدولي للبحث...'); ?>
            <?php ausr_field('events', 'ev-featured-desc',      'الوصف',        'textarea', 'مؤتمر أكاديمي دولي...'); ?>
            <?php ausr_field('events', 'ev-featured-dates',     'التواريخ',     'text',     '15-17 سبتمبر 2026'); ?>
            <?php ausr_field('events', 'ev-featured-location',  'المكان',       'text',     'مقر الجامعة الرئيسي'); ?>
            <?php ausr_field('events', 'ev-featured-attendees', 'المشاركون',    'text',     'أكثر من 300 مشارك'); ?>
            <?php ausr_field('events', 'ev-featured-day',       'اليوم',        'text',     '١٥'); ?>
            <?php ausr_field('events', 'ev-featured-month',     'الشهر',        'text',     'سبتمبر'); ?>
            <?php ausr_field('events', 'ev-featured-year',      'السنة',        'text',     '2026'); ?>
        </div>
    </div>
</div>

<!-- ===== الفعاليات - البطاقات ===== -->
<div id="sec-events-cards" class="ausr-section">
    <div class="ausr-card">
        <div class="ausr-card-title">🗓️ بطاقات الفعاليات (6 فعاليات)</div>
        <?php
        $events = [
            ['01', 'مؤتمر الذكاء الاصطناعي',        'أبريل 2026'],
            ['02', 'ورشة كتابة الأبحاث العلمية',    'مايو 2026'],
            ['03', 'ندوة التنمية المستدامة',         'يونيو 2026'],
            ['04', 'الملتقى الأكاديمي الإنساني',    'يوليو 2026'],
            ['05', 'ورشة منهجية البحث',              'أغسطس 2026'],
            ['06', 'ندوة التعليم الرقمي',            'سبتمبر 2026'],
        ];
        foreach ( $events as $ev ) :
            [$num, $title, $date] = $ev;
        ?>
        <div class="ausr-prog-block">
            <div class="ausr-prog-block-header">فعالية <?php echo $num; ?> — <?php echo $title; ?></div>
            <div class="ausr-fields-grid">
                <?php ausr_field('events', "ev-card-{$num}-date",  'التاريخ', 'text',     $date); ?>
                <?php ausr_field('events', "ev-card-{$num}-title", 'العنوان', 'text',     $title); ?>
                <?php ausr_field('events', "ev-card-{$num}-desc",  'الوصف',   'textarea', 'وصف الفعالية...'); ?>
                <?php ausr_field('events', "ev-card-{$num}-loc",   'المكان',  'text',     'قاعة المؤتمرات'); ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="ausr-fields-grid" style="margin-top:20px">
            <?php ausr_field('events', 'ev-footer-copy', '©️ حقوق الملكية', 'text', '© 2026 الجامعة الأمريكية...'); ?>
        </div>
    </div>
</div>
