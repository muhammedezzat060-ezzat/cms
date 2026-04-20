/**
 * AUSR CMS - Visual Editor JavaScript
 * نظام المحرر البصري التفاعلي
 */

(function () {
    'use strict';

    // ============================================================
    // Config
    // ============================================================
    const API = ausrCMS.apiUrl;
    const NONCE = ausrCMS.nonce;
    
    let currentSection = 'home-hero';
    let sectionData = null;
    let currentOverlay = null;
    let pendingChanges = {};

    // ============================================================
    // Initialize Visual Editor
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        initVisualEditor();
    });

    function initVisualEditor() {
        loadSectionData();
        initSectionTabs();
        initQuickActions();
        loadSection('home-hero');
    }

    // ============================================================
    // Load Section Data
    // ============================================================
    function loadSectionData() {
        const dataElement = document.getElementById('ausr-section-data');
        if (dataElement) {
            try {
                sectionData = JSON.parse(dataElement.textContent);
            } catch (e) {
                console.error('Failed to parse section data:', e);
            }
        }
    }

    // ============================================================
    // Section Tabs
    // ============================================================
    function initSectionTabs() {
        const tabs = document.querySelectorAll('.ausr-section-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const section = tab.dataset.section;
                if (section !== currentSection) {
                    switchSection(section);
                }
            });
        });
    }

    function switchSection(section) {
        // Update active tab
        document.querySelectorAll('.ausr-section-tab').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.section === section);
        });

        currentSection = section;
        loadSection(section);
    }

    // ============================================================
    // Load Section
    // ============================================================
    async function loadSection(sectionKey) {
        const container = document.getElementById('ausr-canvas-container');
        if (!container || !sectionData || !sectionData[sectionKey]) return;

        container.innerHTML = '<div class="ausr-loading">جاري تحميل القسم...</div>';

        const section = sectionData[sectionKey];
        
        // Create section image
        const img = document.createElement('img');
        img.src = section.image;
        img.className = 'ausr-section-image';
        img.alt = sectionKey;
        img.onload = () => {
            container.innerHTML = '';
            container.appendChild(img);
            
            // Add overlays
            section.overlays.forEach(overlay => {
                createOverlay(overlay);
            });
        };

        img.onerror = () => {
            container.innerHTML = '<div class="ausr-loading" style="color: #ef4444;">فشل في تحميل صورة القسم</div>';
        };
    }

    // ============================================================
    // Create Interactive Overlay
    // ============================================================
    function createOverlay(overlayData) {
        const overlay = document.createElement('div');
        overlay.className = 'ausr-overlay';
        overlay.dataset.overlayId = overlayData.id;
        overlay.dataset.fieldKey = overlayData.key;
        overlay.dataset.fieldType = overlayData.type;
        
        // Position overlay
        overlay.style.left = overlayData.x;
        overlay.style.top = overlayData.y;
        overlay.style.width = overlayData.width;
        overlay.style.height = overlayData.height;

        // Add preview content
        const preview = document.createElement('div');
        preview.className = 'ausr-overlay-preview';
        preview.textContent = 'اضغط للتعديل';
        overlay.appendChild(preview);

        // Add click handler
        overlay.addEventListener('click', () => {
            openEditModal(overlayData);
        });

        // Add hover effects
        overlay.addEventListener('mouseenter', () => {
            overlay.classList.add('active');
        });

        overlay.addEventListener('mouseleave', () => {
            overlay.classList.remove('active');
        });

        document.getElementById('ausr-canvas-container').appendChild(overlay);
    }

    // ============================================================
    // Edit Modal
    // ============================================================
    function openEditModal(overlayData) {
        currentOverlay = overlayData;
        const modal = document.getElementById('ausr-edit-modal');
        const modalTitle = document.getElementById('ausr-modal-title');
        const modalBody = document.getElementById('ausr-modal-body');

        modalTitle.textContent = `تعديل: ${overlayData.id}`;

        // Create form based on field type
        let formHTML = '';
        
        if (overlayData.type === 'text') {
            formHTML = `
                <div class="ausr-field">
                    <label for="field-value">النص</label>
                    <textarea id="field-value" placeholder="أدخل النص هنا...">${overlayData.value || ''}</textarea>
                </div>
            `;
        } else if (overlayData.type === 'image') {
            formHTML = `
                <div class="ausr-field">
                    <label for="field-value">رابط الصورة</label>
                    <input type="url" id="field-value" placeholder="أدخل رابط الصورة..." value="${overlayData.value || ''}">
                </div>
            `;
        }

        modalBody.innerHTML = formHTML;
        modal.style.display = 'flex';

        // Focus first input
        setTimeout(() => {
            const firstInput = modalBody.querySelector('input, textarea');
            if (firstInput) firstInput.focus();
        }, 100);
    }

    function closeEditModal() {
        const modal = document.getElementById('ausr-edit-modal');
        modal.style.display = 'none';
        currentOverlay = null;
    }

    // ============================================================
    // Save Field from Modal
    // ============================================================
    async function saveFieldFromModal() {
        if (!currentOverlay) return;

        const fieldInput = document.getElementById('field-value');
        const newValue = fieldInput.value.trim();

        if (newValue === currentOverlay.value) {
            closeEditModal();
            return;
        }

        try {
            // Show loading state
            const saveBtn = document.querySelector('.ausr-modal-footer .ausr-btn-primary');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'جاري الحفظ...';
            saveBtn.disabled = true;

            // Save via API
            const response = await apiCall('content/bulk', 'POST', {
                items: [{
                    page_key: currentSection,
                    element_key: currentOverlay.key,
                    value: newValue,
                    type: currentOverlay.type || 'text'
                }]
            });

            if (response.success) {
                // Update overlay data
                currentOverlay.value = newValue;
                pendingChanges[currentOverlay.key] = newValue;

                // Update preview
                const overlay = document.querySelector(`[data-overlay-id="${currentOverlay.id}"]`);
                if (overlay) {
                    const preview = overlay.querySelector('.ausr-overlay-preview');
                    if (preview) {
                        preview.textContent = newValue.substring(0, 50) + (newValue.length > 50 ? '...' : '');
                    }
                }

                showToast('تم حفظ التغيير بنجاح ✅', 'success');
                closeEditModal();
            } else {
                throw new Error(response.message || 'فشل في الحفظ');
            }
        } catch (error) {
            showToast('فشل في الحفظ: ' + error.message, 'error');
        } finally {
            // Reset button
            const saveBtn = document.querySelector('.ausr-modal-footer .ausr-btn-primary');
            saveBtn.textContent = 'حفظ التغييرات';
            saveBtn.disabled = false;
        }
    }

    // ============================================================
    // Quick Actions
    // ============================================================
    function initQuickActions() {
        // Add keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 's':
                        e.preventDefault();
                        saveAllChanges();
                        break;
                    case 'p':
                        e.preventDefault();
                        togglePreview();
                        break;
                }
            }
        });
    }

    function togglePreview() {
        // Toggle overlay visibility
        const overlays = document.querySelectorAll('.ausr-overlay');
        overlays.forEach(overlay => {
            const isHidden = overlay.style.display === 'none';
            overlay.style.display = isHidden ? 'flex' : 'none';
        });

        showToast(isHidden ? 'تم إظهار التراكبات' : 'تم إخفاء التراكبات', 'success');
    }

    function resetSection() {
        if (confirm('هل أنت متأكد من إعادة تعيين جميع التغييرات في هذا القسم؟')) {
            pendingChanges = {};
            loadSection(currentSection);
            showToast('تم إعادة تعيين القسم', 'success');
        }
    }

    async function saveAllChanges() {
        if (Object.keys(pendingChanges).length === 0) {
            showToast('لا توجد تغييرات لحفظها', 'warning');
            return;
        }

        try {
            const items = Object.keys(pendingChanges).map(key => ({
                page_key: currentSection,
                element_key: key,
                value: pendingChanges[key],
                type: 'text'
            }));

            const response = await apiCall('content/bulk', 'POST', { items });

            if (response.success) {
                pendingChanges = {};
                showToast('تم حفظ جميع التغييرات بنجاح ✅', 'success');
            } else {
                throw new Error(response.message || 'فشل في الحفظ');
            }
        } catch (error) {
            showToast('فشل في الحفظ: ' + error.message, 'error');
        }
    }

    // ============================================================
    // API Helper with Token Support
    // ============================================================
    async function apiCall(endpoint, method = 'GET', data = null) {
        const opts = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': NONCE,
            },
            credentials: 'include',
        };
        
        // Add Authorization header for token-based requests
        const token = localStorage.getItem('ausr_auth_token');
        if (token) {
            opts.headers['Authorization'] = `Bearer ${token}`;
        }
        
        if (data && method !== 'GET') opts.body = JSON.stringify(data);

        const res = await fetch(`${API}/${endpoint}`, opts);
        const json = await res.json();

        if (!res.ok) throw new Error(json.message || 'خطأ في الاتصال');
        return json;
    }

    // ============================================================
    // Toast Notifications
    // ============================================================
    function showToast(msg, type = 'success') {
        // Remove existing toast
        const existing = document.querySelector('.ausr-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `ausr-toast ${type}`;
        toast.textContent = msg;
        document.body.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => toast.classList.add('show'));

        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // ============================================================
    // Global Functions
    // ============================================================
    window.closeEditModal = closeEditModal;
    window.saveFieldFromModal = saveFieldFromModal;
    window.togglePreview = togglePreview;
    window.resetSection = resetSection;
    window.saveAllChanges = saveAllChanges;

})();
