<?php


// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

// Apibrėžti konstantas
define('WC_AD_DISCOUNTS_VERSION', '1.0.0');
define('WC_AD_DISCOUNTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WC_AD_DISCOUNTS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Įtraukti pagrindinius failus
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-init.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-admin.php';

// Inicializuoti papildinį
function wcad_initialize_plugin() {
    if (class_exists('WCAD_Init')) {
        new WCAD_Init();
    }
    if (class_exists('WCAD_Admin')) {
        new WCAD_Admin();
    }
}
add_action('plugins_loaded', 'wcad_initialize_plugin');

// Aktyvavimo funkcija
function wcad_activate_plugin() {
    require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-activator.php';
    WCAD_Activator::activate();
}
register_activation_hook(__FILE__, 'wcad_activate_plugin');

// Deaktyvavimo funkcija
function wcad_deactivate_plugin() {
    require_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-deactivator.php';
    WCAD_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'wcad_deactivate_plugin');

// Sukurti administratoriaus meniu
class WCAD_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'wcad_create_admin_menu'));
    }

    public function wcad_create_admin_menu() {
        add_menu_page(
            'Pažangios Nuolaidos', // Pavadinimas meniu juostoje
            'Nuolaidos', // Meniu pavadinimas
            'manage_options', // Leidimai
            'wcad_discounts', // Slug
            array($this, 'wcad_admin_dashboard'), // Funkcija
            'dashicons-tag', // Ikona
            55 // Pozicija meniu
        );
    }

    public function wcad_admin_dashboard() {
        echo '<div class="wrap"><h1>Nuolaidų valdymas</h1><p>Čia bus nuolaidų valdymo skydelis.</p></div>';
    }
}
