<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Activator {
    /**
     * Papildinio aktyvavimo funkcija
     */
    public static function activate() {
        // Sukuriame būtinąsias duomenų bazės lenteles
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            discount_name VARCHAR(255) NOT NULL,
            discount_type VARCHAR(50) NOT NULL,
            discount_value FLOAT NOT NULL,
            status TINYINT(1) NOT NULL DEFAULT 1
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}

// Priskiriame aktyvavimo funkciją
register_activation_hook(__FILE__, array('WCAD_Activator', 'activate'));
?>