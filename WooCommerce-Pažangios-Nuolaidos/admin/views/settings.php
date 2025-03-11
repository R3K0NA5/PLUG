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

// Administravimo peržiūros failai
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-list.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-edit.php';
include_once WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/settings.php';

// Sukurti nustatymų puslapį
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/settings.php', "<?php\nif (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['wcad_save_settings'])) {\n    check_admin_referer('wcad_save_settings_action', 'wcad_save_settings_nonce');\n    update_option('wcad_allow_discount_stacking', isset(\$_POST['allow_discount_stacking']) ? 1 : 0);\n    update_option('wcad_default_discount_status', intval(\$_POST['default_discount_status']));\n    update_option('wcad_default_expiration_days', intval(\$_POST['default_expiration_days']));\n    echo '<div class=\"updated notice is-dismissible\"><p>Nustatymai išsaugoti.</p></div>';\n}\n$allow_stacking = get_option('wcad_allow_discount_stacking', 1);\n$default_status = get_option('wcad_default_discount_status', 1);\n$default_expiration = get_option('wcad_default_expiration_days', 30);\n?>\n<div class=\"wrap\">\n<h1>Nuolaidų Papildinio Nustatymai</h1>\n<form method=\"post\">\n    <?php wp_nonce_field('wcad_save_settings_action', 'wcad_save_settings_nonce'); ?>\n    <table class=\"form-table\">\n        <tr><th><label for=\"allow_discount_stacking\">Leisti nuolaidų kombinavimą</label></th>\n            <td><input type=\"checkbox\" name=\"allow_discount_stacking\" value=\"1\" <?php checked($allow_stacking, 1); ?>></td></tr>\n        <tr><th><label for=\"default_discount_status\">Numatytoji būsena</label></th>\n            <td><select name=\"default_discount_status\">\n                <option value=\"1\" <?php selected($default_status, 1); ?>>Aktyvi</option>\n                <option value=\"0\" <?php selected($default_status, 0); ?>>Neaktyvi</option>\n            </select></td></tr>\n        <tr><th><label for=\"default_expiration_days\">Numatyta galiojimo trukmė (dienomis)</label></th>\n            <td><input type=\"number\" name=\"default_expiration_days\" value=\"<?php echo esc_attr($default_expiration); ?>\"></td></tr>\n    </table>\n    <p class=\"submit\"><input type=\"submit\" name=\"wcad_save_settings\" class=\"button-primary\" value=\"Išsaugoti nustatymus\"></p>\n</form>\n</div>");

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
