<?php
/**
 * AUSR CMS - Dynamic Registry Dashboard
 * Generates editing fields directly from class-content-map.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$registry = AUSR_Content_Map::get_registry();
$defaults = AUSR_Content_Map::get_defaults();

foreach ( $registry as $page_key => $page_data ) :
    foreach ( $page_data['sections'] as $sec_key => $sec_data ) :
?>
    <!-- Section: <?php echo esc_html( $sec_data['label'] ); ?> -->
    <div id="sec-<?php echo esc_attr( $page_key . '-' . $sec_key ); ?>" class="ausr-section">
        <div class="ausr-section-header">
            <h2><?php echo esc_html( $sec_data['label'] ); ?></h2>
            <p>Page: <?php echo esc_html( $page_data['label'] ); ?></p>
        </div>

        <div class="ausr-fields-grid">
            <?php foreach ( $sec_data['fields'] as $field_key => $field_config ) : 
                $current_val = $defaults[$page_key][$field_key] ?? '';
            ?>
                <div class="ausr-field">
                    <label for="<?php echo esc_attr( $field_key ); ?>">
                        <?php echo esc_html( $field_config['label'] ); ?>
                        <span class="ausr-key-badge"><?php echo esc_html( $field_key ); ?></span>
                    </label>
                    
                    <?php if ( $field_config['type'] === 'textarea' ) : ?>
                        <textarea 
                            id="<?php echo esc_attr( $field_key ); ?>"
                            data-page="<?php echo esc_attr( $page_key ); ?>"
                            data-key="<?php echo esc_attr( $field_key ); ?>"
                            class="ausr-input"
                            rows="4"
                        ><?php echo esc_textarea( $current_val ); ?></textarea>
                    <?php else : ?>
                        <input 
                            type="text"
                            id="<?php echo esc_attr( $field_key ); ?>"
                            data-page="<?php echo esc_attr( $page_key ); ?>"
                            data-key="<?php echo esc_attr( $field_key ); ?>"
                            value="<?php echo esc_attr( $current_val ); ?>"
                            class="ausr-input"
                        />
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php 
    endforeach;
endforeach; 
?>

<style>
.ausr-key-badge {
    font-size: 10px;
    background: #e2e8f0;
    color: #475569;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 8px;
    font-family: monospace;
}
.ausr-section {
    display: none; /* Hidden by default, shown by JS */
}
.ausr-section.active {
    display: block;
}
</style>
