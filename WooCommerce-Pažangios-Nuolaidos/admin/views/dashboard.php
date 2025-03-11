<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Admin {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * Pridedame administratoriaus meniu (tik vieną kartą)
     */
    public function add_admin_menu() {
        remove_menu_page('wcad_main'); // Pašalina dubliuotą meniu jei toks yra

        add_menu_page(
            __('Nuolaidos', 'wc-advanced-discounts'),
            __('Nuolaidos', 'wc-advanced-discounts'),
            'manage_options',
            'wcad_main',
            array($this, 'render_dashboard_page'),
            'dashicons-tag',
            26
        );

        add_submenu_page(
            'wcad_main',
            __('Visos nuolaidos', 'wc-advanced-discounts'),
            __('Visos nuolaidos', 'wc-advanced-discounts'),
            'manage_options',
            'wcad_discounts',
            array($this, 'render_discount_list')
        );

        add_submenu_page(
            'wcad_main',
            __('Pridėti naują nuolaidą', 'wc-advanced-discounts'),
            __('Pridėti naują', 'wc-advanced-discounts'),
            'manage_options',
            'wcad_add_discount',
            array($this, 'render_discount_edit')
        );

        add_submenu_page(
            'wcad_main',
            __('Nuolaidų nustatymai', 'wc-advanced-discounts'),
            __('Nustatymai', 'wc-advanced-discounts'),
            'manage_options',
            'wcad_settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Atvaizduojame pradinį puslapį
     */
    public function render_dashboard_page() {
        echo '<div class="wrap"><h1>' . __('Nuolaidų valdymas', 'wc-advanced-discounts') . '</h1>';
        echo '<p>' . __('Čia galite valdyti visas nuolaidas, kurti naujas ir peržiūrėti ataskaitas.', 'wc-advanced-discounts') . '</p>';
        echo '<a href="admin.php?page=wcad_discounts" class="button button-primary">Tvarkyti nuolaidas</a>';
        echo '</div>';
    }

    /**
     * Atvaizduojame nuolaidų sąrašą
     */
    public function render_discount_list() {
        include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-list.php';
    }

    /**
     * Atvaizduojame nuolaidos redagavimo puslapį
     */
    public function render_discount_edit() {
        include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-edit.php';
    }

    /**
     * Atvaizduojame nustatymų puslapį
     */
    public function render_settings_page() {
        include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/settings.php';
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
add_action('plugins_loaded', function() {
    WCAD_Admin::get_instance();
});
?>