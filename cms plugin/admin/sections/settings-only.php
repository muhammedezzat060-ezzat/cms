<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ===== الإعدادات ===== -->
<div id="sec-settings" class="ausr-section">

    <div class="ausr-card" style="max-width:500px">
        <div class="ausr-card-title">🔐 تغيير كلمة المرور</div>
        <div class="ausr-card-sub">يجب أن تحتوي على 8 أحرف على الأقل، حرف كبير، ورقم</div>

        <div class="ausr-field">
            <label>كلمة المرور الجديدة</label>
            <div class="ausr-password-wrapper">
                <input type="password" id="ausr-new-pass" class="ausr-input" placeholder="أدخل كلمة المرور الجديدة" />
                <button type="button" class="ausr-toggle-pass" onclick="ausrTogglePass('ausr-new-pass', 'eye-new')">
                    <span id="eye-new">👁️</span>
                </button>
            </div>
        </div>

        <div class="ausr-field">
            <label>تأكيد كلمة المرور</label>
            <div class="ausr-password-wrapper">
                <input type="password" id="ausr-confirm-pass" class="ausr-input" placeholder="أعد كتابة كلمة المرور" />
                <button type="button" class="ausr-toggle-pass" onclick="ausrTogglePass('ausr-confirm-pass', 'eye-confirm')">
                    <span id="eye-confirm">👁️</span>
                </button>
            </div>
        </div>

        <button class="ausr-btn ausr-btn-primary" id="ausr-change-pass-btn">
            🔐 تغيير كلمة المرور
        </button>
    </div>

    <div class="ausr-card" style="max-width:500px">
        <div class="ausr-card-title">ℹ️ معلومات النظام</div>
        <table class="ausr-info-table">
            <tr>
                <td>الإصدار</td>
                <td><strong><?php echo esc_html( AUSR_CMS_VERSION ); ?></strong></td>
            </tr>
            <tr>
                <td>اسم المستخدم</td>
                <td><strong><?php echo isset( $_SESSION['ausr_cms_user'] ) ? esc_html( sanitize_text_field( wp_unslash( $_SESSION['ausr_cms_user'] ) ) ) : '—'; ?></strong></td>
            </tr>
            <tr>
                <td>الموقع</td>
                <td><a href="<?php echo esc_url( get_site_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( get_site_url() ); ?></a></td>
            </tr>
            <tr>
                <td>WordPress</td>
                <td><strong><?php echo esc_html( get_bloginfo( 'version' ) ); ?></strong></td>
            </tr>
            <tr>
                <td>PHP</td>
                <td><strong><?php echo esc_html( PHP_VERSION ); ?></strong></td>
            </tr>
        </table>
    </div>
</div>
