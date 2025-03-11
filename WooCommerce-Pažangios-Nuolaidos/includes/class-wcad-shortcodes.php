<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Shortcodes {
    /**
     * Konstruktorius - registruoja šortkodą
     */
    public function __construct() {
        add_shortcode('wcad_active_discounts', array($this, 'display_active_discounts'));
    }

    /**
     * Rodo aktyvias nuolaidas naudojant šortkodą [wcad_active_discounts]
     */
    public function display_active_discounts() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discounts = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");

        if (empty($discounts)) {
            return '<p>' . __('Šiuo metu nėra aktyvių nuolaidų.', 'wc-advanced-discounts') . '</p>';
        }

        $output = '<ul class="wcad-discount-list">';
        foreach ($discounts as $discount) {
            $output .= '<li>' . esc_html($discount->discount_name) . ': ';
            $output .= esc_html($discount->discount_value);
            $output .= ($discount->discount_type == 'percent') ? '%' : '€';
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Shortcodes')) {
    new WCAD_Shortcodes();
}
?>