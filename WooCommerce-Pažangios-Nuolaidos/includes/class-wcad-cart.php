<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Cart {
    /**
     * Konstruktorius - registruoja krepšelio nuolaidų taikymo funkcijas
     */
    public function __construct() {
        add_action('woocommerce_cart_calculate_fees', array($this, 'apply_cart_discounts'));
    }

    /**
     * Taikyti nuolaidas krepšelyje
     */
    public function apply_cart_discounts() {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        global $woocommerce;
        $discount_total = 0;

        // Paimame visas aktyvias nuolaidas iš duomenų bazės
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discounts = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");

        foreach ($discounts as $discount) {
            if ($discount->discount_type === 'percent') {
                $discount_total += $woocommerce->cart->subtotal * ($discount->discount_value / 100);
            } elseif ($discount->discount_type === 'fixed') {
                $discount_total += $discount->discount_value;
            }
        }

        if ($discount_total > 0) {
            $woocommerce->cart->add_fee(__('Nuolaida', 'wc-advanced-discounts'), -$discount_total, true);
        }
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Cart')) {
    new WCAD_Cart();
}
?>