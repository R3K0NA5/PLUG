<?php
/**
 * Plugin Name: WooCommerce Pažangios Nuolaidos
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
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-init.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-admin.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-discounts.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-cart.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-activator.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-deactivator.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-shortcodes.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-helpers.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-logs.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-cron.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/database.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/settings.php';
require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/ajax-handler.php';

// Užtikriname, kad visi reikalingi failai yra pasiekiami
if (!class_exists('WCAD_Init')) {
    exit('Kritinė klaida: WCAD_Init klasė neįkelta.');
}

// Inicializuoti papildinį
function wcad_initialize_plugin() {
    new WCAD_Init();
    new WCAD_Admin();
    new WCAD_Discounts();
    new WCAD_Cart();
    new WCAD_Shortcodes();
    new WCAD_Helpers();
    new WCAD_Logs();
    new WCAD_Cron();
    new WCAD_Database();
    new WCAD_Settings();
    new WCAD_Ajax_Handler();
}
add_action('plugins_loaded', 'wcad_initialize_plugin');
?>