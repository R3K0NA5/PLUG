<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

/**
 * Šablonas rodyti nuolaidas krepšelyje
 */
function wcad_display_cart_discounts() {
    if (!is_cart() && !is_checkout()) {
        return;
    }

    $discount_code = WC()->session->get('wcad_discount');
    if (!$discount_code) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'wcad_discounts';
    $discount = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE discount_name = %s AND status = 1", $discount_code));

    if (!$discount) {
        return;
    }

    echo '<div class="woocommerce-info">';
    echo sprintf(__('Priskirta nuolaida: %s - %s%s', 'wc-advanced-discounts'),
        esc_html($discount->discount_name),
        esc_html($discount->discount_value),
        ($discount->discount_type == 'percent') ? '%' : '€'
    );
    echo '</div>';
}
add_action('woocommerce_before_cart', 'wcad_display_cart_discounts');
add_action('woocommerce_before_checkout_form', 'wcad_display_cart_discounts');
?>