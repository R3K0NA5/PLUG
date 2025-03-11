<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Helpers {
    /**
     * Formatuoja kainą pagal WooCommerce nustatymus
     */
    public static function format_price($amount) {
        return wc_price($amount);
    }

    /**
     * Tikrina, ar vartotojas turi tam tikrą rolę
     */
    public static function user_has_role($user_id, $role) {
        $user = get_userdata($user_id);
        return in_array($role, (array) $user->roles);
    }

    /**
     * Gauna aktyvias nuolaidas
     */
    public static function get_active_discounts() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        return $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");
    }
}

// Užtikriname, kad klasė nebus dubliuojama
if (!class_exists('WCAD_Helpers')) {
    new WCAD_Helpers();
}
?>