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
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-init.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'includes/class-wcad-admin.php';

// Inicializavimo klasė
class WCAD_Init {
    public function __construct() {
        add_action('init', array($this, 'register_discount_post_type'));
    }

    public function register_discount_post_type() {
        $labels = array(
            'name' => 'Nuolaidos',
            'singular_name' => 'Nuolaida',
            'menu_name' => 'Nuolaidos',
            'name_admin_bar' => 'Nuolaida',
            'add_new' => 'Pridėti naują',
            'add_new_item' => 'Pridėti naują nuolaidą',
            'edit_item' => 'Redaguoti nuolaidą',
            'new_item' => 'Nauja nuolaida',
            'view_item' => 'Peržiūrėti nuolaidą',
            'search_items' => 'Ieškoti nuolaidų',
            'not_found' => 'Nuolaidų nerasta',
            'not_found_in_trash' => 'Šiukšlinėje nuolaidų nerasta',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'supports' => array('title', 'editor', 'custom-fields'),
            'capability_type' => 'post',
            'rewrite' => array('slug' => 'nuolaida'),
        );

        register_post_type('wcad_discount', $args);
    }
}

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
            'Pažangios Nuolaidos',
            'Nuolaidos',
            'manage_options',
            'wcad_discounts',
            array($this, 'wcad_admin_dashboard'),
            'dashicons-tag',
            55
        );

        add_submenu_page(
            'wcad_discounts',
            'Visos Nuolaidos',
            'Visos Nuolaidos',
            'manage_options',
            'wcad_discount_list',
            array($this, 'wcad_discount_list_page')
        );
    }

    public function wcad_admin_dashboard() {
        echo '<div class="wrap"><h1>Nuolaidų valdymas</h1><p>Čia bus nuolaidų valdymo skydelis.</p></div>';
    }

    public function wcad_discount_list_page() {
        include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-list.php';
    }
}
