(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('[AUSR] Bootstrap 5 Dashboard Loaded');

        // --- 1. SECTION SWITCHING ---
        $('.nav-link[data-section-btn]').on('click', function(e) {
            e.preventDefault();
            const sectionKey = $(this).data('section-btn');
            
            // UI State
            $('.nav-link[data-section-btn]').removeClass('active');
            $(this).addClass('active');

            // Show Section using Bootstrap d-none
            $('.ausr-section').addClass('d-none');
            $(`#section-${sectionKey}`).removeClass('d-none');

            // Update Title
            const label = $(this).find('span:last').text();
            $('#ausr-active-section-title').text(label);
        });

        // --- 2. GLOBAL SAVE (API) ---
        $('#ausr-global-save').on('click', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const $activeSection = $('.ausr-section:not(.d-none)');
            const $form = $activeSection.find('.ausr-section-form');
            const pageKey = $form.data('page');
            
            // Gather data for Bulk API - collect ALL form inputs
            const items = [];
            $form.find('input, textarea').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    items.push({
                        page_key: pageKey,
                        element_key: name,
                        value: $(this).val(),
                        type: 'text'
                    });
                }
            });

            if (items.length === 0) {
                showToast('لا توجد بيانات للحفظ', 'error');
                return;
            }

            console.log('[AUSR] Saving', items.length, 'items for page:', pageKey);

            // Loading state
            const originalText = $btn.find('span:last').text();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><span>جاري الحفظ...</span>');

            $.ajax({
                url: ausrVars.apiUrl + 'content/bulk',
                type: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ausrVars.nonce);
                },
                contentType: 'application/json',
                data: JSON.stringify({ items: items }),
                success: function(response) {
                    console.log('[AUSR] Save response:', response);
                    if (response.success) {
                        showToast('✅ تم حفظ التغييرات بنجاح', 'success');
                    } else {
                        showToast('❌ فشل الحفظ: ' + (response.message || 'خطأ غير معروف'), 'error');
                    }
                },
                error: function(xhr) {
                    console.error('[AUSR] Save error:', xhr);
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'خطأ في الاتصال بالخادم';
                    showToast('❌ ' + msg, 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<span class="me-2">💾</span><span>' + originalText + '</span>');
                }
            });
        });

        // --- 3. TOAST NOTIFICATIONS ---
        function showToast(message, type) {
            const $toast = $('#ausr-toast');
            const $toastInner = $toast.find('.toast');
            const $title = $('#toast-title');
            const $body = $('#toast-message');
            
            $toast.removeClass('d-none');
            $toastInner.removeClass('toast-success toast-error').addClass(type === 'success' ? 'toast-success' : 'toast-error');
            $title.text(type === 'success' ? 'نجاح' : 'خطأ');
            $body.text(message);
            
            setTimeout(() => {
                $toast.addClass('d-none');
            }, 3000);
        }

        // --- 4. LOGOUT ---
        $('#ausr-logout-trigger').on('click', function(e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
                window.location.href = ausrVars.logoutUrl || '#';
            }
        });
    });

})(jQuery);
