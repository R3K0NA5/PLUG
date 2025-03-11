<?php
/**
 * Plugin Name: WooCommerce-Pažangios-Nuolaidos
 * Plugin URI: https://yourwebsite.com
 * Aprašymas: Išsamus WooCommerce nuolaidų papildinys, palaikantis kategorijų, vartotojų rolių, BOGO pasiūlymų, rinkinių, sąlyginių nuolaidų ir dar daugiau.
 * Versija: 1.0.0
 * Autorius: Donatas
 * Autoriaus URI: https://yourwebsite.com
 * Licencija: GPL2
 * Teksto Domenas: wc-advanced-discounts
 */

// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

// Apibrėžti konstantas
define('WC_AD_DISCOUNTS_VERSION', '1.0.0');
define('WC_AD_DISCOUNTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WC_AD_DISCOUNTS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Įtraukti pagrindinius failus
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-init.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-admin.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-discounts.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-cart.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-activator.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-deactivator.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-shortcodes.php';

// Inicializuoti papildinį
function wcad_initialize_plugin() {
    if (class_exists('WCAD_Init')) {
        new WCAD_Init();
    }
    if (class_exists('WCAD_Admin')) {
        new WCAD_Admin();
    }
    if (class_exists('WCAD_Discounts')) {
        new WCAD_Discounts();
    }
    if (class_exists('WCAD_Cart')) {
        new WCAD_Cart();
    }
    if (class_exists('WCAD_Shortcodes')) {
        new WCAD_Shortcodes();
    }
}
add_action('plugins_loaded', 'wcad_initialize_plugin');

// Aktyvavimo funkcija
class WCAD_Activator {
    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            discount_name varchar(255) NOT NULL,
            discount_type varchar(50) NOT NULL,
            discount_value float NOT NULL,
            conditions text NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, array('WCAD_Activator', 'activate'));

// Deaktyvavimo funkcija
class WCAD_Deactivator {
    public static function deactivate() {
        // Čia galima pridėti kodą išvalymui, jei reikia
    }
}
register_deactivation_hook(__FILE__, array('WCAD_Deactivator', 'deactivate'));

// Šortkodų klasė
class WCAD_Shortcodes {
    public function __construct() {
        add_shortcode('available_discounts', array($this, 'display_available_discounts'));
    }

    public function display_available_discounts() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discounts = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");

        if (!$discounts) {
            return '<p>Nėra aktyvių nuolaidų.</p>';
        }

        $output = '<ul class="wcad-discount-list">';
        foreach ($discounts as $discount) {
            $output .= '<li><strong>' . esc_html($discount->discount_name) . '</strong>: ' . esc_html($discount->discount_value) . '</li>';
        }
        $output .= '</ul>';

        return $output;
    }
}
