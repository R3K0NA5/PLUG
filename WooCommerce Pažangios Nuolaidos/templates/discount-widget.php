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

// Administravimo peržiūros failai
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-list.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-edit.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/settings.php';

// Įtraukti nuolaidų valdiklį
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/discount-widget.php';

// Sukurti nuolaidų valdiklį
class WCAD_Discount_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'wcad_discount_widget',
            __('Nuolaidų Valdiklis', 'wc-advanced-discounts'),
            ['description' => __('Rodomi aktyvūs nuolaidų pasiūlymai', 'wc-advanced-discounts')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . __('Aktyvios Nuolaidos', 'wc-advanced-discounts') . $args['after_title'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discounts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE status = %d", 1));

        if (!empty($discounts)) {
            echo '<ul>';
            foreach ($discounts as $discount) {
                echo '<li>' . esc_html($discount->discount_name) . ': ';
                echo esc_html($discount->discount_value);
                echo ($discount->discount_type == 'percentage') ? '%' : '€';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __('Nėra aktyvių nuolaidų.', 'wc-advanced-discounts') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>' . __('Šis valdiklis nerodo papildomų nustatymų.', 'wc-advanced-discounts') . '</p>';
    }
}

function wcad_register_widget() {
    register_widget('WCAD_Discount_Widget');
}
add_action('widgets_init', 'wcad_register_widget');

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
