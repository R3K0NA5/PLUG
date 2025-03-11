<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Discounts {
    /**
     * Konstruktorius - registruoja nuolaidų funkcijas
     */
    public function __construct() {
        add_action('init', array($this, 'register_discount_post_type'));
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
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Discounts')) {
    new WCAD_Discounts();
}
?>