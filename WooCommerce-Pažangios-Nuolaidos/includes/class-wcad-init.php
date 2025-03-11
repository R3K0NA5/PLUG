<?php
/**
 * WooCommerce-Pažangios-Nuolaidos - Inicializacijos failas
 */

// Užtikriname, kad failas negali būti pasiektas tiesiogiai
if (!defined('ABSPATH')) exit;

class WCAD_Init {
    /**
     * Konstruktorius - registruoja pagrindines funkcijas
     */
    public function __construct() {
        add_action('init', array($this, 'register_discount_post_type'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Registruojame nuolaidų CPT
     */
    public function register_discount_post_type() {
        $labels = array(
            'name'               => __('Nuolaidos', 'wc-advanced-discounts'),
            'singular_name'      => __('Nuolaida', 'wc-advanced-discounts'),
            'menu_name'          => __('Nuolaidos', 'wc-advanced-discounts'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-tag',
            'supports'           => array('title', 'editor'),
        );

        register_post_type('wcad_discount', $args);
    }

    /**
     * Pridedame administravimo meniu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Nuolaidos', 'wc-advanced-discounts'),
            __('Nuolaidos', 'wc-advanced-discounts'),
            'manage_options',
            'wcad_discounts',
            array($this, 'render_admin_page'),
            'dashicons-tag',
            26
        );
    }

    /**
     * Įkeliame administratoriaus resursus
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style('wcad-admin-css', plugin_dir_url(__FILE__) . '../assets/css/admin.css');
        wp_enqueue_script('wcad-admin-js', plugin_dir_url(__FILE__) . '../assets/js/admin.js', array('jquery'), false, true);
    }

    /**
     * Įkeliame vartotojo pusės resursus
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style('wcad-frontend-css', plugin_dir_url(__FILE__) . '../assets/css/frontend.css');
        wp_enqueue_script('wcad-frontend-js', plugin_dir_url(__FILE__) . '../assets/js/frontend.js', array('jquery'), false, true);
    }

    /**
     * Atvaizduojame administratoriaus puslapį
     */
    public function render_admin_page() {
        echo '<div class="wrap"><h1>' . __('Nuolaidų valdymas', 'wc-advanced-discounts') . '</h1></div>';
    }
}

// Inicializuojame klasę
new WCAD_Init();
?>