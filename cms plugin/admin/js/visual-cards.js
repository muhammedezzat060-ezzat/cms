/**
 * AUSR CMS - Visual Cards JavaScript
 * Interactive Cards Layout with Modern UX
 */

(function () {
    'use strict';

    // ============================================================
    // Config
    // ============================================================
    const API = ausrCMS.apiUrl;
    const NONCE = ausrCMS.nonce;
    
    let currentSection = null;
    let allContent = {};
    let pendingChanges = {};

    // ============================================================
    // Initialize Visual Cards
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        initVisualCards();
        loadAllSectionsContent();
    });

    function initVisualCards() {
        console.log('Initializing Visual Cards...');
        updateCardStatuses();
        initCardInteractions();
    }

    // ============================================================
    // Load All Sections Content
    // ============================================================
    async function loadAllSectionsContent() {
        try {
            console.log('Loading all sections content...');
            const data = await apiCall('content', 'GET');
            allContent = data.content || {};
            console.log('Content loaded:', allContent);
            updateCardStatuses();
        } catch (error) {
            console.error('Failed to load content:', error);
            showToast('Failed to load content: ' + error.message, 'error');
        }
    }

    // ============================================================
    // Update Card Statuses
    // ============================================================
    function updateCardStatuses() {
        document.querySelectorAll('.ausr-section-card').forEach(card => {
            const section = card.dataset.section;
            const items = card.querySelectorAll('.ausr-item');
            
            items.forEach(item => {
                const key = item.dataset.key;
                const content = allContent[section]?.[key];
                
                const statusEl = item.querySelector('.ausr-item-status');
                if (statusEl) {
                    if (content && content.value) {
                        statusEl.textContent = 'filled';
                        statusEl.style.background = 'rgba(16, 185, 129, 0.2)';
                        statusEl.style.color = '#10b981';
                    } else {
                        statusEl.textContent = 'empty';
                        statusEl.style.background = 'rgba(239, 68, 68, 0.2)';
                        statusEl.style.color = '#ef4444';
                    }
                }
            });
        });
    }

    // ============================================================
    // Card Interactions
    // ============================================================
    function initCardInteractions() {
        // Add hover effects and click handlers
        document.querySelectorAll('.ausr-section-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // ============================================================
    // Section Actions
    // ============================================================
    function showSectionPreview(section) {
        console.log('Showing preview for section:', section);
        currentSection = section;
        
        const modal = document.getElementById('ausr-preview-modal');
        const content = document.getElementById('ausr-preview-content');
        
        if (!modal || !content) return;
        
        // Load preview screenshot or placeholder
        const imageUrl = `${ausrCMS.siteUrl}/wp-content/plugins/ausr-cms/admin/images/sections/${section}-preview.jpg`;
        const img = document.createElement('img');
        img.src = imageUrl;
        img.alt = `${section} preview`;
        img.style.cssText = 'max-width: 100%; height: auto; border-radius: 8px;';
        
        img.onload = () => {
            content.innerHTML = '';
            content.appendChild(img);
        };
        
        img.onerror = () => {
            content.innerHTML = `
                <div style="text-align: center; color: #94a3b8;">
                    <div style="font-size: 48px; margin-bottom: 16px;">image</div>
                    <div>Preview image not available</div>
                    <div style="font-size: 14px; margin-top: 8px;">Section: ${section}</div>
                </div>
            `;
        };
        
        modal.style.display = 'flex';
    }

    function closePreviewModal() {
        const modal = document.getElementById('ausr-preview-modal');
        if (modal) {
            modal.style.display = 'none';
        }
        currentSection = null;
    }

    function editSection(section) {
        console.log('Editing section:', section);
        
        // Switch to classic editor for this section
        const classicPanel = document.getElementById('ausr-panel-texts');
        const visualPanel = document.getElementById('ausr-panel-visual');
        const classicBtn = document.getElementById('ausr-classic-editor');
        const visualBtn = document.getElementById('ausr-visual-editor');

        if (classicPanel && visualPanel && classicBtn && visualBtn) {
            classicPanel.style.display = 'block';
            visualPanel.style.display = 'none';
            classicBtn.classList.add('active');
            visualBtn.classList.remove('active');
            
            // Navigate to the specific section
            setTimeout(() => {
                const navItem = document.querySelector(`.ausr-nav-item[data-section="${section}"]`);
                if (navItem) {
                    navItem.click();
                }
            }, 100);
        }
        
        showToast(`Switched to edit ${section} section`, 'success');
    }

    function previewSection(section) {
        console.log('Previewing section:', section);
        showSectionPreview(section);
    }

    function editFromPreview() {
        if (currentSection) {
            closePreviewModal();
            editSection(currentSection);
        }
    }

    // ============================================================
    // Quick Actions
    // ============================================================
    function refreshAllSections() {
        console.log('Refreshing all sections...');
        showToast('Refreshing content...', 'info');
        loadAllSectionsContent();
    }

    async function exportAllContent() {
        console.log('Exporting all content...');
        
        try {
            const btn = event.target.closest('.ausr-action-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="ausr-action-icon">hourglass_empty</span> Exporting...';
            btn.disabled = true;
            
            // Create export data
            const exportData = {
                timestamp: new Date().toISOString(),
                content: allContent
            };
            
            // Download as JSON
            const blob = new Blob([JSON.stringify(exportData, null, 2)], {
                type: 'application/json'
            });
            
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `ausr-cms-export-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showToast('Content exported successfully', 'success');
        } catch (error) {
            console.error('Export failed:', error);
            showToast('Export failed: ' + error.message, 'error');
        } finally {
            // Reset button
            const btn = document.querySelector('.ausr-action-btn:disabled');
            if (btn) {
                btn.innerHTML = '<span class="ausr-action-icon">download</span> Export';
                btn.disabled = false;
            }
        }
    }

    async function saveAllChanges() {
        console.log('Saving all changes...');
        
        try {
            const btn = event.target.closest('.ausr-action-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="ausr-action-icon">hourglass_empty</span> Saving...';
            btn.disabled = true;
            btn.classList.add('loading');
            
            // Collect all changes from the classic editor
            const items = [];
            document.querySelectorAll('[data-page][data-key]:not([data-font])').forEach(el => {
                const fontInput = document.querySelector(
                    `[data-font="true"][data-page="${el.dataset.page}"][data-key="${el.dataset.key}"]`
                );
                
                items.push({
                    page_key: el.dataset.page,
                    element_key: el.dataset.key,
                    value: el.value || '',
                    type: el.dataset.type || 'text',
                    font_size: fontInput ? (fontInput.value || null) : null,
                });
            });
            
            console.log('Items to save:', items);
            
            if (items.length === 0) {
                showToast('No changes to save', 'warning');
                return;
            }
            
            const response = await apiCall('content/bulk', 'POST', { items });
            console.log('Save response:', response);
            
            if (response.success) {
                showToast('All changes saved successfully', 'success');
                
                // Refresh content
                await loadAllSectionsContent();
                
                // Reset change indicators
                document.querySelectorAll('.changed').forEach(el => el.classList.remove('changed'));
            } else {
                throw new Error(response.message || 'Save operation failed');
            }
        } catch (error) {
            console.error('Save failed:', error);
            showToast('Save failed: ' + error.message, 'error');
        } finally {
            // Reset button
            const btn = document.querySelector('.ausr-action-btn:disabled');
            if (btn) {
                btn.innerHTML = '<span class="ausr-action-icon">save</span> Save All Changes';
                btn.disabled = false;
                btn.classList.remove('loading');
            }
        }
    }

    // ============================================================
    // API Helper with Debugging
    // ============================================================
    async function apiCall(endpoint, method = 'GET', data = null) {
        console.log('=== API CALL DEBUG ===');
        console.log('Endpoint:', endpoint);
        console.log('Method:', method);
        console.log('Data:', data);
        
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
        console.log('Token from localStorage:', token ? 'exists' : 'missing');
        
        if (token) {
            opts.headers['Authorization'] = `Bearer ${token}`;
            console.log('Authorization header added:', opts.headers['Authorization']);
        } else {
            console.warn('No JWT token found - this might cause authentication issues');
        }
        
        if (data && method !== 'GET') {
            opts.body = JSON.stringify(data);
            console.log('Request body:', opts.body);
        }

        console.log('Full request options:', opts);
        console.log('Full URL:', `${API}/${endpoint}`);

        try {
            const res = await fetch(`${API}/${endpoint}`, opts);
            console.log('Response status:', res.status);
            console.log('Response headers:', [...res.headers.entries()]);
            
            const json = await res.json();
            console.log('Response JSON:', json);

            if (!res.ok) {
                console.error('API call failed:', res.status, json);
                throw new Error(json.message || `HTTP ${res.status}: ${res.statusText}`);
            }
            
            console.log('API call successful');
            return json;
        } catch (error) {
            console.error('API call error:', error);
            throw error;
        }
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
    window.showSectionPreview = showSectionPreview;
    window.closePreviewModal = closePreviewModal;
    window.editSection = editSection;
    window.previewSection = previewSection;
    window.editFromPreview = editFromPreview;
    window.refreshAllSections = refreshAllSections;
    window.exportAllContent = exportAllContent;
    window.saveAllChanges = saveAllChanges;

})();
