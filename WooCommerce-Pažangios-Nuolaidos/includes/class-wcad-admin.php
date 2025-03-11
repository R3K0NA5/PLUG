<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Admin {
    /**
     * Konstruktorius - registruoja administratoriaus funkcijas
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
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
     * Atvaizduojame administratoriaus puslapį
     */
    public function render_admin_page() {
        echo '<div class="wrap"><h1>' . __('Nuolaidų valdymas', 'wc-advanced-discounts') . '</h1></div>';
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Admin')) {
    new WCAD_Admin();
}
?>