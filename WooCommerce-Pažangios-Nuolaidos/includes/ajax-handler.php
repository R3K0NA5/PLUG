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
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-helpers.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-logs.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-cron.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/database.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/settings.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/ajax-handler.php';

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
    if (class_exists('WCAD_Helpers')) {
        new WCAD_Helpers();
    }
    if (class_exists('WCAD_Logs')) {
        new WCAD_Logs();
    }
    if (class_exists('WCAD_Cron')) {
        new WCAD_Cron();
    }
    if (class_exists('WCAD_Database')) {
        new WCAD_Database();
    }
    if (class_exists('WCAD_Settings')) {
        new WCAD_Settings();
    }
    if (class_exists('WCAD_Ajax_Handler')) {
        new WCAD_Ajax_Handler();
    }
}
add_action('plugins_loaded', 'wcad_initialize_plugin');

// AJAX tvarkyklė
class WCAD_Ajax_Handler {
    public function __construct() {
        add_action('wp_ajax_wcad_apply_discount', array($this, 'apply_discount'));
        add_action('wp_ajax_nopriv_wcad_apply_discount', array($this, 'apply_discount'));
    }

    public function apply_discount() {
        if (!isset($_POST['discount_code'])) {
            wp_send_json_error(array('message' => 'Trūksta nuolaidos kodo.'));
        }

        $discount_code = sanitize_text_field($_POST['discount_code']);
        $discount = $this->get_discount_by_code($discount_code);

        if (!$discount) {
            wp_send_json_error(array('message' => 'Nuolaidos kodas nerastas.'));
        }

        WC()->cart->add_fee($discount['discount_name'], -$discount['discount_value']);
        wp_send_json_success(array('message' => 'Nuolaida pritaikyta!'));
    }

    private function get_discount_by_code($code) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE discount_name = %s AND status = 1", $code), ARRAY_A);
    }
}