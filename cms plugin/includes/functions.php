<?php
/**
 * AUSR CMS - Helper Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * يولّد حقل تعديل واحد مع دعم حجم الخط
 */
function ausr_field( $page_key, $element_key, $label, $type = 'text', $placeholder = '' ) {
    $is_textarea = ( $type === 'textarea' );
    $is_url      = ( $type === 'url' );
    $full_class  = ( $is_textarea ) ? 'ausr-field-wrap ausr-field-full ausr-field-block' : 'ausr-field-wrap ausr-field-block';
    ?>
    <div class="<?php echo esc_attr( $full_class ); ?>">
        <label class="ausr-field-label"><?php echo esc_html( $label ); ?></label>

        <?php if ( $is_textarea ) : ?>
            <textarea
                class="ausr-input ausr-textarea"
                data-page="<?php echo esc_attr( $page_key ); ?>"
                data-key="<?php echo esc_attr( $element_key ); ?>"
                data-type="<?php echo $is_url ? 'url' : 'text'; ?>"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
                rows="3"
            ></textarea>
        <?php else : ?>
            <input
                type="text"
                class="ausr-input"
                data-page="<?php echo esc_attr( $page_key ); ?>"
                data-key="<?php echo esc_attr( $element_key ); ?>"
                data-type="<?php echo $is_url ? 'url' : 'text'; ?>"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
            />
        <?php endif; ?>

        <!-- حجم الخط -->
        <div class="ausr-font-size-row">
            <label class="ausr-font-size-label">حجم الخط (px)</label>
            <input
                type="number"
                class="ausr-font-size-input"
                data-page="<?php echo esc_attr( $page_key ); ?>"
                data-key="<?php echo esc_attr( $element_key ); ?>"
                data-font="true"
                min="8"
                max="120"
                placeholder="افتراضي"
            />
            <span class="ausr-font-size-hint">اتركه فارغاً للحجم الافتراضي</span>
        </div>
    </div>
    <?php
}
