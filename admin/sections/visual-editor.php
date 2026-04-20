<?php
/**
 * AUSR CMS - Visual Editor Section
 * نظام المحرر البصري التفاعلي
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- Visual Editor Container -->
<div id="ausr-visual-editor" class="ausr-visual-editor">
    
    <!-- Section Selector -->
    <div class="ausr-section-selector">
        <h3 class="ausr-section-title">اختر القسم للتعديل</h3>
        <div class="ausr-section-tabs">
            <button type="button" class="ausr-section-tab active" data-section="home-hero">البطل الرئيسي</button>
            <button type="button" class="ausr-section-tab" data-section="home-stats">الإحصائيات</button>
            <button type="button" class="ausr-section-tab" data-section="home-about">من نحن</button>
            <button type="button" class="ausr-section-tab" data-section="programs-hero">برامج - البطل</button>
            <button type="button" class="ausr-section-tab" data-section="events-hero">فعاليات - البطل</button>
            <button type="button" class="ausr-section-tab" data-section="about-hero">عن الموقع - البطل</button>
        </div>
    </div>

    <!-- Visual Editor Canvas -->
    <div class="ausr-visual-canvas">
        <div class="ausr-canvas-container" id="ausr-canvas-container">
            <!-- Section images and overlays will be loaded here dynamically -->
            <div class="ausr-loading">جاري تحميل المحرر البصري...</div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="ausr-edit-modal" class="ausr-edit-modal" style="display: none;">
        <div class="ausr-modal-backdrop" onclick="closeEditModal()"></div>
        <div class="ausr-modal-content">
            <div class="ausr-modal-header">
                <h3 class="ausr-modal-title" id="ausr-modal-title">تعديل المحتوى</h3>
                <button type="button" class="ausr-modal-close" onclick="closeEditModal()">✕</button>
            </div>
            <div class="ausr-modal-body" id="ausr-modal-body">
                <!-- Edit form will be loaded here -->
            </div>
            <div class="ausr-modal-footer">
                <button type="button" class="ausr-btn ausr-btn-secondary" onclick="closeEditModal()">إلغاء</button>
                <button type="button" class="ausr-btn ausr-btn-primary" onclick="saveFieldFromModal()">حفظ التغييرات</button>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="ausr-quick-actions">
        <button type="button" class="ausr-quick-btn" onclick="togglePreview()">
            👁️ معاينة
        </button>
        <button type="button" class="ausr-quick-btn" onclick="resetSection()">
            🔄 إعادة تعيين
        </button>
        <button type="button" class="ausr-quick-btn ausr-btn-success" onclick="saveAllChanges()">
            💾 حفظ كل التغييرات
        </button>
    </div>
</div>

<!-- Section Data (Hidden) -->
<script type="application/json" id="ausr-section-data">
{
    "home-hero": {
        "image": "<?php echo AUSR_CMS_URL; ?>admin/images/sections/home-hero.jpg",
        "overlays": [
            {"id": "hero-title", "x": "10%", "y": "20%", "width": "80%", "height": "15%", "key": "hero_title", "type": "text"},
            {"id": "hero-subtitle", "x": "10%", "y": "40%", "width": "80%", "height": "10%", "key": "hero_subtitle", "type": "text"},
            {"id": "hero-cta", "x": "10%", "y": "60%", "width": "30%", "height": "8%", "key": "hero_cta_text", "type": "text"}
        ]
    },
    "home-stats": {
        "image": "<?php echo AUSR_CMS_URL; ?>admin/images/sections/home-stats.jpg",
        "overlays": [
            {"id": "stats-title", "x": "10%", "y": "15%", "width": "80%", "height": "10%", "key": "stats_title", "type": "text"},
            {"id": "stat-1", "x": "10%", "y": "35%", "width": "25%", "height": "15%", "key": "stat_1_number", "type": "text"},
            {"id": "stat-2", "x": "37%", "y": "35%", "width": "25%", "height": "15%", "key": "stat_2_number", "type": "text"},
            {"id": "stat-3", "x": "65%", "y": "35%", "width": "25%", "height": "15%", "key": "stat_3_number", "type": "text"}
        ]
    },
    "programs-hero": {
        "image": "<?php echo AUSR_CMS_URL; ?>admin/images/sections/programs-hero.jpg",
        "overlays": [
            {"id": "programs-title", "x": "10%", "y": "20%", "width": "80%", "height": "12%", "key": "programs_hero_title", "type": "text"},
            {"id": "programs-subtitle", "x": "10%", "y": "40%", "width": "80%", "height": "8%", "key": "programs_hero_subtitle", "type": "text"}
        ]
    },
    "events-hero": {
        "image": "<?php echo AUSR_CMS_URL; ?>admin/images/sections/events-hero.jpg",
        "overlays": [
            {"id": "events-title", "x": "10%", "y": "20%", "width": "80%", "height": "12%", "key": "events_hero_title", "type": "text"},
            {"id": "events-subtitle", "x": "10%", "y": "40%", "width": "80%", "height": "8%", "key": "events_hero_subtitle", "type": "text"}
        ]
    },
    "about-hero": {
        "image": "<?php echo AUSR_CMS_URL; ?>admin/images/sections/about-hero.jpg",
        "overlays": [
            {"id": "about-title", "x": "10%", "y": "20%", "width": "80%", "height": "12%", "key": "about_hero_title", "type": "text"},
            {"id": "about-subtitle", "x": "10%", "y": "40%", "width": "80%", "height": "8%", "key": "about_hero_subtitle", "type": "text"}
        ]
    }
}
</script>
