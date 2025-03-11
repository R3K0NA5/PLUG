<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Cron {
    /**
     * Konstruktorius - registruoja planuojamas užduotis
     */
    public function __construct() {
        add_action('wcad_daily_cron_hook', array($this, 'run_scheduled_tasks'));
        if (!wp_next_scheduled('wcad_daily_cron_hook')) {
            wp_schedule_event(time(), 'daily', 'wcad_daily_cron_hook');
        }
    }

    /**
     * Funkcija, vykdoma kasdieniniu cron užduoties metu
     */
    public function run_scheduled_tasks() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $wpdb->query("UPDATE $table_name SET status = 0 WHERE expiration_date < NOW()");
    }

    /**
     * Papildinio išjungimo metu pašaliname cron įvykį
     */
    public static function deactivate() {
        wp_clear_scheduled_hook('wcad_daily_cron_hook');
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Cron')) {
    new WCAD_Cron();
}

// Priskiriame išjungimo funkciją
register_deactivation_hook(__FILE__, array('WCAD_Cron', 'deactivate'));
?>