/**
 * AUSR CMS Dashboard - JavaScript v1.0.0
 */
(function () {
    'use strict';

    // ============================================================
    // Config
    // ============================================================
    const API      = ausrCMS.apiUrl;
    const NONCE    = ausrCMS.nonce;
    const SITE_URL = ausrCMS.siteUrl;

    let pendingChanges = 0;
    let allContent     = {};

    // ============================================================
    // Init
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        initLogin();
        initDashboard();
    });

    // ============================================================
    // API Helper with Automatic Token & Payload Alignment
    // ============================================================
    async function apiCall(endpoint, method = 'GET', data = null) {
        console.log(`[API CALL] ${method} ${endpoint}`, data);
        
        const opts = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce':   NONCE,
            },
            credentials: 'include',
        };
        
        // Auto-extract token from localStorage
        const token = localStorage.getItem('ausr_auth_token');
        if (token) {
            opts.headers['Authorization'] = `Bearer ${token}`;
        }
        
        if (data && method !== 'GET') {
            // Ensure payload matches class-api.php expectations
            // If it's a bulk save, it should be { items: [...] }
            // If it's single, it should be { page_key, element_key, value, ... }
            opts.body = JSON.stringify(data);
        }

        try {
            const res = await fetch(`${API}/${endpoint}`, opts);
            const json = await res.json();

            if (!res.ok) {
                console.error('[API ERROR]', res.status, json);
                throw new Error(json.message || `HTTP ${res.status}`);
            }
            
            return json;
        } catch (error) {
            console.error('[FETCH ERROR]', error);
            throw error;
        }
    }

    // ============================================================
    // Show Alert (Legacy - kept for compatibility)
    // ============================================================
    function showAlert(msg, type = 'success') {
        showToast(msg, type);
    }

    // ============================================================
    // Toast Notifications System
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
    // LOGIN
    // ============================================================
    function initLogin() {
        const loginBtn  = document.getElementById('ausr-login-btn');
        const toggleBtn = document.getElementById('ausr-toggle-pass');

        if (!loginBtn) return;

        // Toggle password visibility
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const passInput = document.getElementById('ausr-password');
                const eyeIcon   = document.getElementById('ausr-eye-icon');
                if (passInput.type === 'password') {
                    passInput.type = 'text';
                    eyeIcon.textContent = '🙈';
                } else {
                    passInput.type = 'password';
                    eyeIcon.textContent = '👁️';
                }
            });
        }

        // Login on button click
        loginBtn.addEventListener('click', doLogin);

        // Login on Enter
        ['ausr-username', 'ausr-password'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('keypress', e => { if (e.key === 'Enter') doLogin(); });
        });
    }

    async function doLogin() {
        const user = document.getElementById('ausr-username').value;
        const pass = document.getElementById('ausr-password').value;
        const btn  = document.querySelector('#ausr-login-screen .ausr-btn-primary');
        const err  = document.getElementById('ausr-login-error');
    
        if (!user || !pass) {
            alert('يرجى إدخال اسم المستخدم وكلمة المرور');
            return;
        }
    
        try {
            btn.disabled = true;
            btn.innerHTML = 'جاري التحقق...';
            err.style.display = 'none';
    
            // إرسال طلب الدخول للـ API
            const res = await apiCall('login', 'POST', { username: user, password: pass });
    
            if (res.success) {
                // Store token for API calls
                if (res.token) {
                    localStorage.setItem('ausr_auth_token', res.token);
                }
                window.location.reload(); // إعادة تحميل الصفحة للدخول للوحة
            } else {
                throw new Error(res.message || 'بيانات الدخول غير صحيحة');
            }
        } catch (e) {
            console.error('Login Error:', e);
            err.textContent = e.message;
            err.style.display = 'block';
            alert('فشل الدخول: ' + e.message); // تنبيه إضافي للتأكد
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'دخول 🔐';
        }
    }

    // ============================================================
    // VISUAL MAPPING EDITOR (The UI Revolution)
    // ============================================================
    function openVisualOverlay(sectionId) {
        console.log(`[VISUAL] Opening Overlay for: ${sectionId}`);
        const card = document.querySelector(`.ausr-visual-card[data-section="${sectionId}"]`);
        if (!card) return;

        const fieldsMap = JSON.parse(card.querySelector('.ausr-fields-map').textContent);
        const imgSrc = card.querySelector('img').src;
        
        const modal = document.getElementById('ausr-overlay-modal');
        const editorArea = document.getElementById('ausr-overlay-editor-area');
        const title = document.getElementById('ausr-overlay-title');

        title.textContent = `Visual Mapping: ${sectionId}`;
        
        // Build Editor HTML
        let html = `
            <div class="ausr-visual-editor-main">
                <img src="${imgSrc}" id="ausr-visual-main-img">
        `;

        fieldsMap.forEach(field => {
            const currentVal = allContent[sectionId]?.[field.key]?.value || '';
            html += `
                <div class="ausr-overlay-point" 
                     style="top: ${field.top}; left: ${field.left};" 
                     onclick="focusVisualField('${field.key}')"
                     title="${field.label}">
                    <div class="ausr-overlay-tooltip">${field.label}</div>
                </div>
            `;
        });

        html += `</div><div class="ausr-visual-fields-list">`;
        
        fieldsMap.forEach(field => {
            const currentVal = allContent[sectionId]?.[field.key]?.value || '';
            html += `
                <div class="ausr-field" id="wrapper-${field.key}">
                    <label>${field.label}</label>
                    <textarea class="ausr-input visual-field-input" 
                              data-section="${sectionId}" 
                              data-key="${field.key}" 
                              id="input-${field.key}">${currentVal}</textarea>
                </div>
            `;
        });

        html += `</div>`;
        editorArea.innerHTML = html;
        modal.style.display = 'flex';
    }

    function closeVisualOverlay() {
        document.getElementById('ausr-overlay-modal').style.display = 'none';
    }

    window.focusVisualField = function(key) {
        const input = document.getElementById(`input-${key}`);
        if (input) {
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Highlight wrapper
            document.querySelectorAll('.ausr-field').forEach(f => f.classList.remove('highlight'));
            document.getElementById(`wrapper-${key}`).classList.add('highlight');
        }
    };

    async function saveVisualChanges() {
        const btn = document.getElementById('ausr-overlay-save-btn');
        const inputs = document.querySelectorAll('.visual-field-input');
        const items = [];

        inputs.forEach(input => {
            items.push({
                page_key: input.dataset.section,
                element_key: input.dataset.key,
                value: input.value,
                type: 'text'
            });
        });

        if (items.length === 0) return;

        btn.disabled = true;
        btn.innerHTML = '⏳ جاري الحفظ...';

        try {
            const res = await apiCall('content/bulk', 'POST', { items });
            if (res.success) {
                showToast('✅ تم الحفظ بنجاح', 'success');
                await loadAllContent(); // Refresh local data
                closeVisualOverlay();
            }
        } catch (e) {
            showToast('❌ فشل الحفظ: ' + e.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '💾 حفظ التغييرات';
        }
    }

    // Bind to window
    window.openVisualOverlay = openVisualOverlay;
    window.closeVisualOverlay = closeVisualOverlay;
    window.saveVisualChanges = saveVisualChanges;
    
    // Register Save Button
    document.addEventListener('DOMContentLoaded', () => {
        const saveBtn = document.getElementById('ausr-overlay-save-btn');
        if (saveBtn) saveBtn.addEventListener('click', saveVisualChanges);
    });

    // ============================================================
    // DASHBOARD INIT
    // ============================================================
    function initDashboard() {
        if (!document.getElementById('ausr-dashboard')) return;

        console.log('[INIT] Dashboard Booting...');
        
        // 1. Core Logic
        initNavigation();
        initSidebarToggle();
        initSaveButton();
        initLogout();
        initUpload();
        initSettings();
        loadAllContent();
        
        // 2. UI Systems
        initMainTabs();
        buildMediaSkeleton();
    }

    function initMainTabs() {
        const tabBtns = document.querySelectorAll('.ausr-top-tab[data-main-tab]');
        const panels = {
            texts: document.getElementById('ausr-panel-texts'),
            media: document.getElementById('ausr-panel-media'),
            users: document.getElementById('ausr-panel-users'),
            logs:  document.getElementById('ausr-panel-logs'),
            visual: document.getElementById('ausr-panel-visual'),
        };

        if (!tabBtns.length) return;

        function activateTab(name) {
            tabBtns.forEach(btn => {
                const on = btn.dataset.mainTab === name;
                btn.classList.toggle('is-active', on);
                btn.setAttribute('aria-selected', on ? 'true' : 'false');
            });

            Object.entries(panels).forEach(([key, el]) => {
                if (!el) return;
                const on = key === name;
                el.classList.toggle('is-active', on);
                el.style.display = on ? 'block' : 'none';
            });

            if (name === 'media') loadMediaLibrary();
            if (name === 'users') loadUsersList();
            if (name === 'logs') renderLogsTable('ausr-tab-logs-container', { limit: 100, mode: 'tab' });
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => activateTab(btn.dataset.mainTab));
        });
    }

    function switchToClassicEditor() {
        document.getElementById('ausr-panel-texts').style.display = 'block';
        document.getElementById('ausr-panel-visual').style.display = 'none';
        document.getElementById('ausr-classic-editor').classList.add('active');
        document.getElementById('ausr-visual-editor').classList.remove('active');
    }

    function switchToVisualEditor() {
        document.getElementById('ausr-panel-texts').style.display = 'none';
        document.getElementById('ausr-panel-visual').style.display = 'block';
        document.getElementById('ausr-classic-editor').classList.remove('active');
        document.getElementById('ausr-visual-editor').classList.add('active');
    }

    window.switchToClassicEditor = switchToClassicEditor;
    window.switchToVisualEditor = switchToVisualEditor;

    function buildMediaSkeleton() {
        const skel = document.getElementById('ausr-media-grid-skeleton');
        if (!skel || skel.dataset.built) return;
        skel.innerHTML = Array.from({ length: 12 }, () => '<div class="ausr-skel-cell"></div>').join('');
        skel.dataset.built = '1';
    }

    // ============================================================
    // NAVIGATION
    // ============================================================
    const sectionTitles = {
        'home-hero':       'قسم البطل — الرئيسية',
        'home-stats':      'الإحصائيات — الرئيسية',
        'home-about':      'من نحن — الرئيسية',
        'home-vision':     'الرؤية — الرئيسية',
        'home-footer':     'التذييل — الرئيسية',
        'programs-hero':   'قسم البطل — البرامج',
        'programs-cards':  'البرامج الأكاديمية',
        'events-hero':     'قسم البطل — الفعاليات',
        'events-featured': 'الفعالية المميزة',
        'events-cards':    'بطاقات الفعاليات',
        'about-hero':      'قسم البطل — من نحن',
        'about-story':     'القصة والرؤية',
        'about-stats':     'الإحصائيات — من نحن',
        'about-team':      'فريق القيادة',
        'settings':        'الإعدادات',
    };

    // ============================================================
    // NAVIGATION & SIDEBAR TOGGLE
    // ============================================================
    function initNavigation() {
        console.log('[NAV] Initializing Navigation');
        
        const headerPlaceholder = document.getElementById('ausr-header-save-placeholder');

        function moveSaveButton(sectionKey) {
            if (!headerPlaceholder) return;
            headerPlaceholder.innerHTML = '';
            const section = document.getElementById(`sec-${sectionKey}`);
            if (section) {
                const saveBtn = section.querySelector('.ausr-btn-primary');
                if (saveBtn) {
                    const clonedBtn = saveBtn.cloneNode(true);
                    // Standard POST needs the original button or at least its name/value
                    // To keep Standard POST working without complex JS, we'll just make the header button
                    // trigger the click on the real button inside the form.
                    clonedBtn.type = "button";
                    clonedBtn.id = "cloned-save-btn";
                    clonedBtn.addEventListener('click', () => {
                        saveBtn.click();
                    });
                    headerPlaceholder.appendChild(clonedBtn);
                }
            }
        }

        // Initial move for the active section (home)
        moveSaveButton('home');

        // Sidebar Section Navigation
        document.querySelectorAll('.ausr-nav-item[data-section]').forEach(btn => {
            btn.addEventListener('click', () => {
                const sectionKey = btn.dataset.section;
                
                // Update UI state
                document.querySelectorAll('.ausr-nav-item').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Show only target section
                document.querySelectorAll('.ausr-section').forEach(sec => {
                    sec.classList.remove('active');
                    sec.style.display = 'none';
                });
                
                const targetSec = document.getElementById(`sec-${sectionKey}`);
                if (targetSec) {
                    targetSec.classList.add('active');
                    targetSec.style.display = 'block';
                    document.querySelector('.ausr-main').scrollTop = 0;
                    
                    // Move the save button to header
                    moveSaveButton(sectionKey);
                }
                
                // Update title
                const titleEl = document.getElementById('ausr-section-title');
                if (titleEl) {
                    const sectionNames = {
                        'home': 'الصفحة الرئيسية',
                        'about': 'من نحن',
                        'programs': 'البرامج الأكاديمية',
                        'events': 'الفعاليات والمؤتمرات',
                        'settings': 'الإعدادات العامة'
                    };
                    titleEl.textContent = sectionNames[sectionKey] || sectionKey;
                }
            });
        });
    }

    function initSidebarToggle() {
        const sidebar = document.getElementById('ausr-sidebar');
        const dashboard = document.getElementById('ausr-dashboard');
        const toggleBtn = document.getElementById('ausr-menu-toggle');
        
        if (!sidebar || !toggleBtn) return;

        toggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            if (dashboard) dashboard.classList.toggle('sidebar-collapsed', isCollapsed);
            localStorage.setItem('ausr_sidebar_collapsed', isCollapsed);
            toggleBtn.textContent = isCollapsed ? '📋' : '☰';
        });

        // Restore state
        if (localStorage.getItem('ausr_sidebar_collapsed') === 'true') {
            sidebar.classList.add('collapsed');
            if (dashboard) dashboard.classList.add('sidebar-collapsed');
            toggleBtn.textContent = '📋';
        }
    }

    // ============================================================
    // LOAD CONTENT
    // ============================================================
    async function loadAllContent() {
        try {
            const data = await apiCall('content', 'GET');
            allContent = data.content || {};
            populateFields();
        } catch (err) {
            showAlert('تعذّر تحميل المحتوى — ' + err.message, 'error');
        }
    }

    function populateFields() {
        // ملء حقول النص
        document.querySelectorAll('[data-page][data-key]:not([data-font])').forEach(el => {
            const page = el.dataset.page;
            const key  = el.dataset.key;
            const val  = allContent[page]?.[key]?.value;
            if (val !== undefined) el.value = val;
        });

        // ملء حقول حجم الخط
        document.querySelectorAll('[data-font="true"]').forEach(el => {
            const page = el.dataset.page;
            const key  = el.dataset.key;
            const size = allContent[page]?.[key]?.font_size;
            if (size) el.value = size;
        });

        // معاينة الشعار
        const logoVal = allContent['home']?.['uni-logo']?.value;
        if (logoVal) {
            const preview = document.getElementById('ausr-logo-preview-img');
            if (preview) preview.src = logoVal;
        }

        // تتبع التغييرات
        trackChanges();
    }

    // ============================================================
    // TRACK CHANGES
    // ============================================================
    function trackChanges() {
        document.querySelectorAll('[data-page][data-key]').forEach(el => {
            el.addEventListener('input', () => {
                el.classList.add('changed');
                pendingChanges++;
                updateUnsavedBadge();

                // معاينة الشعار فورياً
                if (el.dataset.key === 'uni-logo' && el.tagName === 'INPUT') {
                    const preview = document.getElementById('ausr-logo-preview-img');
                    if (preview && el.value) preview.src = el.value;
                }
            });
        });
    }

    function updateUnsavedBadge() {
        const badge   = document.getElementById('ausr-unsaved-badge');
        const countEl = document.getElementById('ausr-unsaved-count');
        if (!badge) return;

        if (pendingChanges > 0) {
            badge.style.display = 'block';
            if (countEl) countEl.textContent = pendingChanges;
        } else {
            badge.style.display = 'none';
        }
    }

    // ============================================================
    // SAVE ALL
    // ============================================================
function initSaveButton() {
        // Main Save Button
        const mainBtn = document.getElementById('ausr-save-all-btn');
        if (mainBtn) {
            mainBtn.addEventListener('click', saveAll);
        }
    }

    async function saveAll() {
        const btn = document.getElementById('ausr-save-all-btn');
        const items = [];

        // Collect from all sections in Classic Editor
        document.querySelectorAll('[data-page][data-key]:not([data-font])').forEach(el => {
            const fontInput = document.querySelector(`[data-font="true"][data-page="${el.dataset.page}"][data-key="${el.dataset.key}"]`);
            items.push({
                page_key: el.dataset.page,
                element_key: el.dataset.key,
                value: el.value,
                type: el.dataset.type || 'text',
                font_size: fontInput ? fontInput.value : null
            });
        });

        if (items.length === 0) {
            showToast('لا توجد تغييرات للحفظ', 'warning');
            return;
        }

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '⏳ جاري الحفظ...';
        }

        try {
            const res = await apiCall('content/bulk', 'POST', { items });
            if (res.success) {
                showToast('✅ تم حفظ التغييرات بنجاح', 'success');
                pendingChanges = 0;
                updateUnsavedBadge();
                document.querySelectorAll('.changed').forEach(el => el.classList.remove('changed'));
            }
        } catch (e) {
            showToast('❌ فشل الحفظ: ' + e.message, 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '💾 حفظ كل التغييرات';
            }
        }
    }

    // ============================================================
    // LOGOUT
    // ============================================================
    function initLogout() {
        const btn = document.getElementById('ausr-logout-btn');
        if (!btn) return;

        btn.addEventListener('click', async () => {
            if (!confirm('هل تريد تسجيل الخروج؟')) return;
            try {
                await apiCall('logout', 'POST');
                // Clear token from localStorage
                localStorage.removeItem('ausr_auth_token');
                location.reload();
            } catch (e) {
                // Clear token even on error
                localStorage.removeItem('ausr_auth_token');
                location.reload();
            }
        });
    }

    // ============================================================
    // UPLOAD
    // ============================================================
    function initUpload() {
        const zone      = document.getElementById('ausr-upload-zone');
        const fileInput = document.getElementById('ausr-file-input');
        if (!zone) return;

        // Drag & Drop
        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.classList.add('dragover');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) uploadFile(e.dataTransfer.files[0]);
        });

        // File input
        if (fileInput) {
            fileInput.addEventListener('change', () => {
                if (fileInput.files[0]) uploadFile(fileInput.files[0]);
            });
        }
    }

    async function uploadFile(file) {
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            showAlert('الملف كبير جداً (الحد الأقصى 5MB)', 'error');
            return;
        }

        const zone     = document.getElementById('ausr-upload-zone');
        const progress = document.getElementById('ausr-upload-progress');
        const result   = document.getElementById('ausr-upload-result');
        const fill     = document.getElementById('ausr-progress-fill');

        zone.style.display     = 'none';
        progress.style.display = 'block';
        result.style.display   = 'none';

        // Animate progress bar
        let prog = 0;
        const interval = setInterval(() => {
            prog = Math.min(prog + 10, 90);
            if (fill) fill.style.width = prog + '%';
        }, 150);

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch(`${API}/upload`, {
                method: 'POST',
                headers: { 'X-WP-Nonce': NONCE },
                credentials: 'include',
                body: formData,
            });
            const data = await res.json();

            clearInterval(interval);
            if (fill) fill.style.width = '100%';

            setTimeout(() => {
                progress.style.display = 'none';

                if (data.url) {
                    const urlInput = document.getElementById('ausr-uploaded-url');
                    if (urlInput) urlInput.value = data.url;

                    const preview = document.getElementById('ausr-upload-preview');
                    if (preview) {
                        preview.innerHTML = '';
                        if (data.type?.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = data.url;
                            img.alt = '';
                            img.style.cssText = 'max-width:180px;border-radius:8px;margin-bottom:12px;display:block';
                            preview.appendChild(img);
                        } else {
                            const d = document.createElement('div');
                            d.style.cssText = 'font-size:40px;text-align:center;margin-bottom:12px';
                            d.textContent = '📄';
                            preview.appendChild(d);
                        }
                    }

                    result.style.display = 'block';
                    showAlert(data.message || 'تم الرفع بنجاح ✅', 'success');

                    if (document.getElementById('ausr-panel-media')?.classList.contains('is-active')) {
                        loadMediaLibrary();
                    }
                } else {
                    zone.style.display = 'block';
                    showAlert(data.message || 'فشل في رفع الملف', 'error');
                }
            }, 400);

        } catch (err) {
            clearInterval(interval);
            progress.style.display = 'none';
            zone.style.display     = 'block';
            showAlert('خطأ في الاتصال — ' + err.message, 'error');
        }
    }

    // ============================================================
    // COPY URL
    // ============================================================
    window.ausrCopyUrl = function () {
        const input = document.getElementById('ausr-uploaded-url');
        if (!input) return;
        input.select();
        document.execCommand('copy');
        showAlert('تم نسخ الرابط ✅', 'success');
    };

    // ============================================================
    // TOGGLE PASSWORD VISIBILITY (للإعدادات)
    // ============================================================
    window.ausrTogglePass = function (inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type      = 'text';
            icon.textContent = '🙈';
        } else {
            input.type      = 'password';
            icon.textContent = '👁️';
        }
    };

    // ============================================================
    // SETTINGS - تغيير كلمة المرور
    // ============================================================
    function initSettings() {
        const btn = document.getElementById('ausr-change-pass-btn');
        if (!btn) return;

        btn.addEventListener('click', async () => {
            const np = document.getElementById('ausr-new-pass')?.value;
            const cp = document.getElementById('ausr-confirm-pass')?.value;

            if (!np || !cp) {
                showToast('يرجى إدخال كلمة المرور وتأكيدها', 'error');
                return;
            }
            if (np !== cp) {
                showToast('كلمتا المرور غير متطابقتين', 'error');
                return;
            }

            btn.textContent = '⏳ جاري التغيير...';
            btn.disabled    = true;

            try {
                const res = await apiCall('change-password', 'POST', { password: np });
                showToast(res.message || 'تم تغيير كلمة المرور ✅', 'success');
                document.getElementById('ausr-new-pass').value     = '';
                document.getElementById('ausr-confirm-pass').value = '';
            } catch (err) {
                showToast(err.message || 'فشل في تغيير كلمة المرور', 'error');
            }

            btn.textContent = '🔐 تغيير كلمة المرور';
            btn.disabled    = false;
        });
    }

    // ============================================================
    // LOGS
    // ============================================================
    function escapeHtml(str) {
        if (str == null) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    const actionLabels = {
        login_success:      '✅ تسجيل دخول ناجح',
        login_failed:       '❌ محاولة دخول فاشلة',
        login_blocked:      '🚫 تم حجب الوصول',
        logout:             '🚪 تسجيل خروج',
        content_saved:      '💾 حفظ محتوى',
        bulk_save:          '💾 حفظ متعدد',
        file_uploaded:      '🖼️ رفع ملف',
        file_deleted:       '🗑️ حذف ملف',
        file_delete_denied: '🚫 رفض حذف ملف',
        password_changed:   '🔐 تغيير كلمة المرور',
    };

    let mediaLoadToken = 0;

    async function loadMediaLibrary() {
        const skel = document.getElementById('ausr-media-grid-skeleton');
        const grid = document.getElementById('ausr-media-grid');
        if (!grid) return;

        const token = ++mediaLoadToken;
        if (skel) skel.style.display = 'grid';
        grid.style.display = 'none';
        grid.innerHTML = '';

        try {
            const data = await apiCall('ausr-media?per_page=60', 'GET');
            if (token !== mediaLoadToken) return;

            const items = data.items || [];
            if (skel) skel.style.display = 'none';
            grid.style.display = 'grid';

            if (!items.length) {
                grid.innerHTML = '<p class="ausr-loading">لا توجد صور بعد. ارفع ملفاً من الأعلى.</p>';
                return;
            }

            grid.innerHTML = items.map((it, i) => {
                const isImg = (it.mime || '').startsWith('image/');
                const thumb = escapeHtml(it.thumb || it.url || '');
                const urlEnc = encodeURIComponent(it.url || '');
                const delay = Math.min(i, 12) * 35;
                const thumbBlock = isImg
                    ? `<img class="ausr-media-card-thumb" src="${thumb}" alt="" loading="lazy" width="400" height="400" />`
                    : '<div class="ausr-media-card-thumb" style="display:flex;align-items:center;justify-content:center;color:var(--ausr-gold);font-size:36px">📄</div>';

                return `
                    <article class="ausr-media-card" style="animation-delay:${delay}ms" data-ausr-url-enc="${urlEnc}">
                        <div class="ausr-media-card-thumb-wrap">${thumbBlock}</div>
                        <div class="ausr-media-card-body">
                            <div class="ausr-media-card-title">${escapeHtml(it.title || 'مرفق')}</div>
                            <div class="ausr-media-card-actions">
                                <button type="button" class="ausr-btn ausr-btn-secondary ausr-btn-copy-media">نسخ الرابط</button>
                                <button type="button" class="ausr-btn ausr-btn-danger ausr-btn-delete-media" data-attachment-id="${Number(it.id)}">حذف</button>
                            </div>
                        </div>
                    </article>`;
            }).join('');
        } catch (err) {
            if (token !== mediaLoadToken) return;
            if (skel) skel.style.display = 'none';
            grid.style.display = 'block';
            grid.innerHTML = `<p class="ausr-loading" style="color:var(--ausr-danger)">تعذّر التحميل: ${escapeHtml(err.message)}</p>`;
        }
    }

    function initMediaPanelActions() {
        const panel = document.getElementById('ausr-panel-media');
        if (!panel || panel.dataset.actionsBound) return;
        panel.dataset.actionsBound = '1';

        panel.addEventListener('click', async (e) => {
            const copyBtn = e.target.closest('.ausr-btn-copy-media');
            if (copyBtn) {
                const card = copyBtn.closest('.ausr-media-card');
                const enc    = card?.dataset?.ausrUrlEnc;
                if (!enc) return;
                const url = decodeURIComponent(enc);
                try {
                    await navigator.clipboard.writeText(url);
                    showAlert('تم نسخ الرابط ✅', 'success');
                } catch {
                    const ta = document.createElement('textarea');
                    ta.value = url;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    showAlert('تم نسخ الرابط ✅', 'success');
                }
                return;
            }

            const delBtn = e.target.closest('.ausr-btn-delete-media');
            if (delBtn) {
                const id = delBtn.getAttribute('data-attachment-id');
                if (!id || !confirm('حذف هذا الملف نهائياً من الموقع؟')) return;
                try {
                    await apiCall('delete-file', 'POST', { attachment_id: Number(id) });
                    showAlert('تم حذف الملف', 'success');
                    loadMediaLibrary();
                } catch (err) {
                    showAlert(err.message || 'تعذّر الحذف', 'error');
                }
            }
        });
    }

    async function renderLogsTable(containerId, opts) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const limit = (opts && opts.limit) || 100;
        container.innerHTML = '<div class="ausr-loading">جاري التحميل...</div>';

        try {
            const data = await apiCall(`logs?limit=${limit}`, 'GET');
            const logs = data.logs || [];

            if (!logs.length) {
                container.innerHTML = '<div class="ausr-loading">لا توجد سجلات بعد</div>';
                return;
            }

            let html = `
                <div class="ausr-card">
                    <table class="ausr-logs-table">
                        <thead>
                            <tr>
                                <th>المستخدم</th>
                                <th>نوع العملية</th>
                                <th>العنصر المعدّل</th>
                                <th>الوقت</th>
                            </tr>
                        </thead>
                        <tbody>`;

            logs.forEach(log => {
                const op = actionLabels[log.action] || log.action;
                html += `
                    <tr>
                        <td>${escapeHtml(log.username || '—')}</td>
                        <td>${escapeHtml(op)}</td>
                        <td>${escapeHtml(log.element_key || '—')}</td>
                        <td>${escapeHtml(log.created_at || '—')}</td>
                    </tr>`;
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
        } catch (err) {
            container.innerHTML = `<div class="ausr-loading" style="color:var(--ausr-danger)">فشل في تحميل السجلات: ${escapeHtml(err.message)}</div>`;
        }
    }

    // ============================================================
    // Users Management Functions
    // ============================================================
    async function loadUsersList() {
        const container = document.getElementById('ausr-users-list');
        if (!container) return;

        container.innerHTML = '<div class="ausr-loading">جاري التحميل...</div>';

        try {
            const data = await apiCall('users', 'GET');
            const users = data.users || [];

            if (!users.length) {
                container.innerHTML = '<div class="ausr-loading">لا يوجد مستخدمين بعد</div>';
                return;
            }

            renderUsersList(users);
        } catch (err) {
            container.innerHTML = `<div class="ausr-loading" style="color:var(--ausr-danger)">فشل في تحميل المستخدمين: ${escapeHtml(err.message)}</div>`;
        }
    }

    function renderUsersList(users) {
        const container = document.getElementById('ausr-users-list');
        if (!container) return;

        const html = users.map(user => {
            const avatar = user.username.charAt(0).toUpperCase();
            const lastLogin = user.last_login ? new Date(user.last_login).toLocaleDateString('ar-SA') : 'لم يسجل دخول';
            const createdAt = user.created_at ? new Date(user.created_at).toLocaleDateString('ar-SA') : '';
            const isLocked = user.locked_until && new Date(user.locked_until) > new Date();

            return `
                <div class="ausr-user-item" data-username="${escapeHtml(user.username)}">
                    <div class="ausr-user-info">
                        <div class="ausr-user-avatar">${avatar}</div>
                        <div class="ausr-user-details">
                            <div class="ausr-user-name">${escapeHtml(user.username)}</div>
                            <div class="ausr-user-meta">
                                ${isLocked ? '🔒 مقفول' : '✅ نشط'} • 
                                آخر دخول: ${escapeHtml(lastLogin)} • 
                                إنشاء: ${escapeHtml(createdAt)}
                            </div>
                        </div>
                    </div>
                    <div class="ausr-user-actions">
                        ${user.username !== 'ausr_admin' ? `
                            <button type="button" class="ausr-btn ausr-btn-secondary ausr-btn-change-pass" data-username="${escapeHtml(user.username)}">
                                🔐 تغيير كلمة المرور
                            </button>
                            <button type="button" class="ausr-btn ausr-btn-danger ausr-btn-delete-user" data-username="${escapeHtml(user.username)}">
                                🗑️ حذف
                            </button>
                        ` : '<span style="color:var(--ausr-muted);font-size:12px">مستخدم افتراضي</span>'}
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;

        // Add event listeners
        container.addEventListener('click', handleUsersActions);
    }

    function handleUsersActions(e) {
        const changePassBtn = e.target.closest('.ausr-btn-change-pass');
        const deleteBtn = e.target.closest('.ausr-btn-delete-user');

        if (changePassBtn) {
            const username = changePassBtn.dataset.username;
            const newPassword = prompt(`أدخل كلمة المرور الجديدة للمستخدم: ${username}`);
            if (newPassword) {
                changeUserPassword(username, newPassword);
            }
        }

        if (deleteBtn) {
            const username = deleteBtn.dataset.username;
            if (confirm(`هل أنت متأكد من حذف المستخدم: ${username}؟`)) {
                deleteUser(username);
            }
        }
    }

    async function changeUserPassword(username, password) {
        try {
            const res = await apiCall(`users/${username}/password`, 'POST', { password });
            showToast(res.message || 'تم تغيير كلمة المرور بنجاح ✅', 'success');
            loadUsersList(); // Refresh list
        } catch (err) {
            showToast(err.message || 'فشل في تغيير كلمة المرور', 'error');
        }
    }

    async function deleteUser(username) {
        try {
            const res = await apiCall(`users/${username}`, 'DELETE');
            showToast(res.message || 'تم حذف المستخدم بنجاح ✅', 'success');
            loadUsersList(); // Refresh list
        } catch (err) {
            showToast(err.message || 'فشل في حذف المستخدم', 'error');
        }
    }

    // Live Search for Users
    function initUsersSearch() {
        const searchInput = document.getElementById('ausr-users-search');
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const userItems = document.querySelectorAll('.ausr-user-item');

            userItems.forEach(item => {
                const username = item.dataset.username.toLowerCase();
                const shouldShow = username.includes(searchTerm);
                item.style.display = shouldShow ? 'flex' : 'none';
            });
        });
    }

    // Add User Form Handler
    function initAddUserForm() {
        const form = document.getElementById('ausr-add-user-form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('ausr-new-username').value;
            const password = document.getElementById('ausr-new-password').value;

            if (!username || !password) {
                showToast('يرجى إدخال جميع الحقول', 'error');
                return;
            }

            try {
                const res = await apiCall('users', 'POST', { username, password });
                showToast(res.message || 'تم إضافة المستخدم بنجاح ✅', 'success');
                
                // Reset form
                form.reset();
                
                // Refresh users list
                loadUsersList();
            } catch (err) {
                showToast(err.message || 'فشل في إضافة المستخدم', 'error');
            }
        });
    }

    // Live Search for Content
    function initContentSearch() {
        const searchInput = document.getElementById('ausr-content-search');
        const resultsContainer = document.getElementById('ausr-search-results');
        const resultsList = document.getElementById('ausr-search-results-list');
        
        if (!searchInput || !resultsContainer || !resultsList) return;

        let searchTimeout;

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.trim().toLowerCase();
            
            clearTimeout(searchTimeout);
            
            if (searchTerm.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                performContentSearch(searchTerm, resultsList, resultsContainer);
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    }

    function performContentSearch(searchTerm, resultsList, resultsContainer) {
        const results = [];
        
        // Search through all content fields
        document.querySelectorAll('[data-page][data-key]').forEach(field => {
            const page = field.dataset.page;
            const key = field.dataset.key;
            const value = field.value || field.textContent || '';
            
            if (value.toLowerCase().includes(searchTerm) || 
                key.toLowerCase().includes(searchTerm) ||
                page.toLowerCase().includes(searchTerm)) {
                
                const sectionName = getSectionName(page, key);
                results.push({
                    element: field,
                    page,
                    key,
                    value: value.substring(0, 100),
                    sectionName
                });
            }
        });

        // Display results
        if (results.length === 0) {
            resultsList.innerHTML = '<div class="ausr-search-result-item">لم يتم العثور على نتائج</div>';
        } else {
            resultsList.innerHTML = results.slice(0, 10).map(result => `
                <div class="ausr-search-result-item" data-page="${result.page}" data-key="${result.key}">
                    <div class="field-name">${escapeHtml(result.key)}</div>
                    <div class="field-value">${escapeHtml(result.value)}</div>
                    <span class="section-name">${escapeHtml(result.sectionName)}</span>
                </div>
            `).join('');

            // Add click handlers
            resultsList.addEventListener('click', (e) => {
                const item = e.target.closest('.ausr-search-result-item');
                if (item) {
                    const page = item.dataset.page;
                    const key = item.dataset.key;
                    const targetField = document.querySelector(`[data-page="${page}"][data-key="${key}"]`);
                    
                    if (targetField) {
                        // Scroll to field
                        targetField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // Highlight field
                        targetField.focus();
                        targetField.style.borderColor = 'var(--ausr-gold)';
                        setTimeout(() => {
                            targetField.style.borderColor = '';
                        }, 2000);
                        
                        // Hide results
                        resultsContainer.style.display = 'none';
                    }
                }
            });
        }

        resultsContainer.style.display = 'block';
    }

    function getSectionName(page, key) {
        const sectionMap = {
            'home': 'الصفحة الرئيسية',
            'programs': 'البرامج الأكاديمية',
            'events': 'الفعاليات',
            'about': 'من نحن',
            'settings': 'الإعدادات'
        };
        return sectionMap[page] || page;
    }

    // Editor Toggle Functions
    function switchToClassicEditor() {
        const classicPanel = document.getElementById('ausr-panel-texts');
        const visualPanel = document.getElementById('ausr-panel-visual');
        const classicBtn = document.getElementById('ausr-classic-editor');
        const visualBtn = document.getElementById('ausr-visual-editor');

        if (classicPanel && visualPanel && classicBtn && visualBtn) {
            classicPanel.style.display = 'block';
            visualPanel.style.display = 'none';
            classicBtn.classList.add('active');
            visualBtn.classList.remove('active');
        }
    }

    function switchToVisualEditor() {
        const classicPanel = document.getElementById('ausr-panel-texts');
        const visualPanel = document.getElementById('ausr-panel-visual');
        const classicBtn = document.getElementById('ausr-classic-editor');
        const visualBtn = document.getElementById('ausr-visual-editor');

        if (classicPanel && visualPanel && classicBtn && visualBtn) {
            classicPanel.style.display = 'none';
            visualPanel.style.display = 'block';
            classicBtn.classList.remove('active');
            visualBtn.classList.add('active');
        }
    }

    // Smart Collapsible Sidebar
    function initSmartSidebar() {
        const sidebar = document.getElementById('ausr-sidebar');
        if (!sidebar) return;

        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'ausr-sidebar-toggle';
        toggleBtn.innerHTML = '☰';
        toggleBtn.title = 'إخفاء/إظهار القائمة';
        toggleBtn.onclick = () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('ausr_sidebar_collapsed', sidebar.classList.contains('collapsed'));
        };

        sidebar.appendChild(toggleBtn);

        // Restore saved state
        const isCollapsed = localStorage.getItem('ausr_sidebar_collapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }
    }

    // Initialize users management when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        initUsersSearch();
        initAddUserForm();
        initContentSearch();
        initSmartSidebar();
    });

    // END OF MODULE
})();
