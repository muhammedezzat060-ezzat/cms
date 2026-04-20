<?php
/**
 * AUSR CMS - Visual Cards Layout
 * Cards Layout for Sections with Quick Preview
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- Visual Cards Container -->
<div id="ausr-visual-cards" class="ausr-visual-cards">
    
    <!-- Cards Header -->
    <div class="ausr-cards-header">
        <h2 class="ausr-cards-title">Sections Management</h2>
        <p class="ausr-cards-subtitle">Click on any section to edit its content</p>
    </div>

    <!-- Cards Grid -->
    <div class="ausr-cards-grid">
        
        <!-- Home Section Card -->
        <div class="ausr-section-card" data-section="home">
            <div class="ausr-card-header">
                <div class="ausr-card-icon">home</div>
                <div class="ausr-card-info">
                    <h3 class="ausr-card-title">Home Page</h3>
                    <p class="ausr-card-description">Main landing page content</p>
                </div>
                <button class="ausr-preview-btn" onclick="showSectionPreview('home')">
                    <span class="ausr-preview-icon">eye</span>
                </button>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-section-items">
                    <div class="ausr-item" data-key="hero_title">
                        <span class="ausr-item-label">Hero Title</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                    <div class="ausr-item" data-key="hero_subtitle">
                        <span class="ausr-item-label">Hero Subtitle</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                    <div class="ausr-item" data-key="stats_title">
                        <span class="ausr-item-label">Statistics Title</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('home')">
                    <span class="ausr-btn-icon">edit</span>
                    Edit Section
                </button>
                <button class="ausr-btn ausr-btn-secondary" onclick="previewSection('home')">
                    <span class="ausr-btn-icon">visibility</span>
                    Preview
                </button>
            </div>
        </div>

        <!-- Programs Section Card -->
        <div class="ausr-section-card" data-section="programs">
            <div class="ausr-card-header">
                <div class="ausr-card-icon">school</div>
                <div class="ausr-card-info">
                    <h3 class="ausr-card-title">Programs</h3>
                    <p class="ausr-card-description">Academic programs and courses</p>
                </div>
                <button class="ausr-preview-btn" onclick="showSectionPreview('programs')">
                    <span class="ausr-preview-icon">eye</span>
                </button>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-section-items">
                    <div class="ausr-item" data-key="hero_title">
                        <span class="ausr-item-label">Hero Title</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                    <div class="ausr-item" data-key="programs_list">
                        <span class="ausr-item-label">Programs List</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('programs')">
                    <span class="ausr-btn-icon">edit</span>
                    Edit Section
                </button>
                <button class="ausr-btn ausr-btn-secondary" onclick="previewSection('programs')">
                    <span class="ausr-btn-icon">visibility</span>
                    Preview
                </button>
            </div>
        </div>

        <!-- Events Section Card -->
        <div class="ausr-section-card" data-section="events">
            <div class="ausr-card-header">
                <div class="ausr-card-icon">event</div>
                <div class="ausr-card-info">
                    <h3 class="ausr-card-title">Events</h3>
                    <p class="ausr-card-description">Upcoming events and activities</p>
                </div>
                <button class="ausr-preview-btn" onclick="showSectionPreview('events')">
                    <span class="ausr-preview-icon">eye</span>
                </button>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-section-items">
                    <div class="ausr-item" data-key="hero_title">
                        <span class="ausr-item-label">Hero Title</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                    <div class="ausr-item" data-key="featured_event">
                        <span class="ausr-item-label">Featured Event</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('events')">
                    <span class="ausr-btn-icon">edit</span>
                    Edit Section
                </button>
                <button class="ausr-btn ausr-btn-secondary" onclick="previewSection('events')">
                    <span class="ausr-btn-icon">visibility</span>
                    Preview
                </button>
            </div>
        </div>

        <!-- About Section Card -->
        <div class="ausr-section-card" data-section="about">
            <div class="ausr-card-header">
                <div class="ausr-card-icon">info</div>
                <div class="ausr-card-info">
                    <h3 class="ausr-card-title">About</h3>
                    <p class="ausr-card-description">About us information</p>
                </div>
                <button class="ausr-preview-btn" onclick="showSectionPreview('about')">
                    <span class="ausr-preview-icon">eye</span>
                </button>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-section-items">
                    <div class="ausr-item" data-key="hero_title">
                        <span class="ausr-item-label">Hero Title</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                    <div class="ausr-item" data-key="story_content">
                        <span class="ausr-item-label">Story Content</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('about')">
                    <span class="ausr-btn-icon">edit</span>
                    Edit Section
                </button>
                <button class="ausr-btn ausr-btn-secondary" onclick="previewSection('about')">
                    <span class="ausr-btn-icon">visibility</span>
                    Preview
                </button>
            </div>
        </div>

        <!-- Settings Section Card -->
        <div class="ausr-section-card" data-section="settings">
            <div class="ausr-card-header">
                <div class="ausr-card-icon">settings</div>
                <div class="ausr-card-info">
                    <h3 class="ausr-card-title">Settings</h3>
                    <p class="ausr-card-description">Global settings and configuration</p>
                </div>
                <button class="ausr-preview-btn" onclick="showSectionPreview('settings')">
                    <span class="ausr-preview-icon">eye</span>
                </button>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-section-items">
                    <div class="ausr-item" data-key="site_title">
                        <span class="ausr-item-label">Site Title</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                    <div class="ausr-item" data-key="contact_email">
                        <span class="ausr-item-label">Contact Email</span>
                        <span class="ausr-item-status">ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('settings')">
                    <span class="ausr-btn-icon">edit</span>
                    Edit Section
                </button>
                <button class="ausr-btn ausr-btn-secondary" onclick="previewSection('settings')">
                    <span class="ausr-btn-icon">visibility</span>
                    Preview
                </button>
            </div>
        </div>

    </div>

    <!-- Quick Actions Bar -->
    <div class="ausr-quick-actions-bar">
        <div class="ausr-actions-left">
            <button class="ausr-action-btn" onclick="refreshAllSections()">
                <span class="ausr-action-icon">refresh</span>
                Refresh All
            </button>
            <button class="ausr-action-btn" onclick="exportAllContent()">
                <span class="ausr-action-icon">download</span>
                Export
            </button>
        </div>
        <div class="ausr-actions-right">
            <button class="ausr-action-btn ausr-btn-success" onclick="saveAllChanges()">
                <span class="ausr-action-icon">save</span>
                Save All Changes
            </button>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="ausr-preview-modal" class="ausr-preview-modal" style="display: none;">
    <div class="ausr-modal-backdrop" onclick="closePreviewModal()"></div>
    <div class="ausr-modal-content">
        <div class="ausr-modal-header">
            <h3 class="ausr-modal-title">Section Preview</h3>
            <button class="ausr-modal-close" onclick="closePreviewModal()">close</button>
        </div>
        <div class="ausr-modal-body">
            <div id="ausr-preview-content" class="ausr-preview-content">
                <!-- Preview content will be loaded here -->
            </div>
        </div>
        <div class="ausr-modal-footer">
            <button class="ausr-btn ausr-btn-secondary" onclick="closePreviewModal()">Close</button>
            <button class="ausr-btn ausr-btn-primary" onclick="editFromPreview()">Edit Section</button>
        </div>
    </div>
</div>

<?php
/**
 * AUSR CMS - Visual Mapping Editor
 * Each section as a visual card with overlay mapping
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Sections configuration for visual mapping
$visual_sections = [
    [
        'id' => 'home-hero',
        'title' => 'Home Hero (الرئيسية)',
        'image' => 'home-hero.jpg',
        'fields' => [
            ['key' => 'hero-title', 'label' => 'العنوان الرئيسي', 'top' => '20%', 'left' => '30%'],
            ['key' => 'hero-subtitle', 'label' => 'الوصف القصير', 'top' => '40%', 'left' => '30%'],
            ['key' => 'hero-btn-text', 'label' => 'نص الزر', 'top' => '60%', 'left' => '30%']
        ]
    ],
    [
        'id' => 'home-stats',
        'title' => 'Statistics (الإحصائيات)',
        'image' => 'home-stats.jpg',
        'fields' => [
            ['key' => 'stat-1-num', 'label' => 'الرقم 1', 'top' => '50%', 'left' => '20%'],
            ['key' => 'stat-1-label', 'label' => 'الوصف 1', 'top' => '65%', 'left' => '20%'],
            ['key' => 'stat-2-num', 'label' => 'الرقم 2', 'top' => '50%', 'left' => '40%'],
            ['key' => 'stat-2-label', 'label' => 'الوصف 2', 'top' => '65%', 'left' => '40%']
        ]
    ],
    [
        'id' => 'programs-cards',
        'title' => 'Academic Programs (البرامج)',
        'image' => 'programs.jpg',
        'fields' => [
            ['key' => 'prog-1-title', 'label' => 'البرنامج 1', 'top' => '30%', 'left' => '25%'],
            ['key' => 'prog-2-title', 'label' => 'البرنامج 2', 'top' => '30%', 'left' => '50%'],
            ['key' => 'prog-3-title', 'label' => 'البرنامج 3', 'top' => '30%', 'left' => '75%']
        ]
    ]
];
?>

<div class="ausr-visual-wrapper">
    <div class="ausr-visual-header">
        <h2>Visual-First Editor</h2>
        <p>تعديل المحتوى مباشرة من خلال صور الموقع</p>
    </div>

    <div class="ausr-visual-grid">
        <?php foreach ($visual_sections as $section) : ?>
        <div class="ausr-visual-card" data-section="<?php echo esc_attr($section['id']); ?>">
            <div class="ausr-card-info">
                <h3><?php echo esc_html($section['title']); ?></h3>
                <span class="ausr-badge"><?php echo count($section['fields']); ?> Fields</span>
            </div>
            
            <div class="ausr-card-preview" onclick="openVisualOverlay('<?php echo esc_attr($section['id']); ?>')">
                <?php $img_url = AUSR_CMS_URL . 'admin/images/sections/' . $section['image']; ?>
                <img src="<?php echo esc_url($img_url); ?>" alt="Preview" onerror="this.src='https://via.placeholder.com/500x300?text=No+Preview+Found'">
                
                <div class="ausr-mapping-overlay">
                    <div class="ausr-overlay-btn">👁️ اضغط لبدء التعديل البصري</div>
                </div>
            </div>
            
            <!-- Hidden Fields Map (JSON for JS) -->
            <script type="application/json" class="ausr-fields-map">
                <?php echo json_encode($section['fields']); ?>
            </script>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Overlay Modal for Visual Editing -->
<div id="ausr-overlay-modal" class="ausr-modal" style="display:none">
    <div class="ausr-modal-backdrop" onclick="closeVisualOverlay()"></div>
    <div class="ausr-modal-container">
        <header class="ausr-modal-header">
            <h2 id="ausr-overlay-title">Visual Mapping</h2>
            <button class="ausr-close-btn" onclick="closeVisualOverlay()">×</button>
        </header>
        <div class="ausr-modal-body">
            <div id="ausr-overlay-editor-area" class="ausr-overlay-editor-area">
                <!-- Image + Overlays will be injected here -->
            </div>
        </div>
        <footer class="ausr-modal-footer">
            <button class="ausr-btn ausr-btn-secondary" onclick="closeVisualOverlay()">إلغاء</button>
            <button class="ausr-btn ausr-btn-primary" id="ausr-overlay-save-btn">💾 حفظ التغييرات</button>
        </footer>
    </div>
</div>
