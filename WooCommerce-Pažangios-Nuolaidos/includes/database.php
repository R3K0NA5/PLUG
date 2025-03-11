<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Database {
    /**
     * Sukuria būtinas duomenų bazės lenteles aktyvuojant papildinį
     */
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $discounts_table = $wpdb->prefix . 'wcad_discounts';
        $logs_table = $wpdb->prefix . 'wcad_logs';

        $sql = "CREATE TABLE IF NOT EXISTS $discounts_table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            discount_name VARCHAR(255) NOT NULL,
            discount_type VARCHAR(50) NOT NULL,
            discount_value FLOAT NOT NULL,
            expiration_date DATETIME NULL,
            status TINYINT(1) NOT NULL DEFAULT 1
        ) $charset_collate;";

        $sql .= "CREATE TABLE IF NOT EXISTS $logs_table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            discount_code VARCHAR(255) NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            status VARCHAR(50) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}

// Priskiriame aktyvavimo funkciją
register_activation_hook(__FILE__, array('WCAD_Database', 'create_tables'));
?>