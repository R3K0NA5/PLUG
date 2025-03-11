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

// Sukurti krepšelio nuolaidų atvaizdavimą
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/discount-cart.php', "<?php\nif (!defined('ABSPATH')) exit;\n\nif (!WC()->cart) return;\n\n$applied_coupons = WC()->cart->get_applied_coupons();\n\nif (!empty($applied_coupons)) {\n    echo '<div class=\"woocommerce-info\">';\n    echo '<h3>Jūsų pritaikytos nuolaidos:</h3>';\n    echo '<ul>';\n    foreach ($applied_coupons as $coupon_code) {\n        $coupon = new WC_Coupon($coupon_code);\n        echo '<li>' . esc_html($coupon->get_description()) . ' (' . esc_html($coupon_code) . ')</li>';\n    }\n    echo '</ul>';\n    echo '</div>';\n} else {\n    echo '<div class=\"woocommerce-info\">Nėra pritaikytų nuolaidų.</div>';\n}\n?>");

// Sukurti nuolaidų pranešimus
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/discount-message.php', "<?php\nif (!defined('ABSPATH')) exit;\n\n// Gauti visas aktyvias nuolaidas\nglobal $wpdb;\n$table_name = $wpdb->prefix . 'wcad_discounts';\n$discounts = $wpdb->get_results(\"SELECT * FROM $table_name WHERE status = 1\");\n\nif (!empty($discounts)) {\n    echo '<div class=\"woocommerce-info\">';\n    echo '<h3>Aktyvios Nuolaidos:</h3>';\n    echo '<ul>';\n    foreach ($discounts as $discount) {\n        echo '<li>' . esc_html($discount->discount_name) . ': ' . esc_html($discount->discount_value);\n        echo ($discount->discount_type == 'percentage') ? '%' : '€';\n        echo '</li>';\n    }\n    echo '</ul>';\n    echo '</div>';\n}\n?>");

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
