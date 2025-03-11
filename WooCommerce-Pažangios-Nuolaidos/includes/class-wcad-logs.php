<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Logs {
    /**
     * Įrašo nuolaidos pritaikymo įrašą į duomenų bazę
     */
    public static function log_discount_usage($discount_code, $user_id, $status) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_logs';
        $wpdb->insert(
            $table_name,
            array(
                'discount_code' => $discount_code,
                'user_id'       => $user_id,
                'status'        => $status,
                'created_at'    => current_time('mysql', 1)
            ),
            array('%s', '%d', '%s', '%s')
        );
    }

    /**
     * Gauna visus nuolaidų naudojimo įrašus
     */
    public static function get_logs($limit = 50) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_logs';
        return $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT " . intval($limit));
    }
}

// Užtikriname, kad klasė nebus dubliuojama
if (!class_exists('WCAD_Logs')) {
    new WCAD_Logs();
}
?>