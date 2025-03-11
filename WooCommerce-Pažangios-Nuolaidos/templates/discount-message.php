<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

/**
 * Šablonas rodyti nuolaidos pranešimą produkto puslapyje
 */
function wcad_display_discount_message() {
    global $wpdb, $post;
    $table_name = $wpdb->prefix . 'wcad_discounts';

    // Patikriname, ar šiam produktui yra aktyvių nuolaidų
    $discount = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE status = 1 AND FIND_IN_SET(%d, product_ids)", $post->ID));

    if (!$discount) {
        return;
    }

    echo '<div class="woocommerce-message">';
    echo sprintf(__('Šiam produktui taikoma nuolaida: %s - %s%s', 'wc-advanced-discounts'),
        esc_html($discount->discount_name),
        esc_html($discount->discount_value),
        ($discount->discount_type == 'percent') ? '%' : '€'
    );
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'wcad_display_discount_message', 20);
?>