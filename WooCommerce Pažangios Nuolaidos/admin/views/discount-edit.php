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

// Sukurti arba redaguoti nuolaidą
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-edit.php', "<?php\n
global $wpdb;\n$table_name = $wpdb->prefix . 'wcad_discounts';\n$discount_id = isset(\$_GET['id']) ? intval(\$_GET['id']) : 0;\n$discount = \$discount_id ? \$wpdb->get_row(\"SELECT * FROM $table_name WHERE id = \$discount_id\") : null;\n\nif (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['wcad_save_discount'])) {\n    check_admin_referer('wcad_save_discount_action', 'wcad_save_discount_nonce');\n\n    \$data = [\n        'discount_name' => sanitize_text_field(\$_POST['discount_name']),\n        'discount_type' => sanitize_text_field(\$_POST['discount_type']),\n        'discount_value' => floatval(\$_POST['discount_value']),\n        'status' => intval(\$_POST['status']),\n        'conditions' => sanitize_text_field(\$_POST['conditions'])\n    ];\n\n    if (\$discount_id) {\n        \$wpdb->update(\$table_name, \$data, ['id' => \$discount_id]);\n    } else {\n        \$wpdb->insert(\$table_name, \$data);\n    }\n\n    wp_redirect(admin_url('admin.php?page=wcad_discount_list'));\n    exit;\n}\n\n?>\n<div class=\"wrap\">\n<h1><?php echo \$discount ? 'Redaguoti Nuolaidą' : 'Sukurti Naują Nuolaidą'; ?></h1>\n<form method=\"post\">\n    <?php wp_nonce_field('wcad_save_discount_action', 'wcad_save_discount_nonce'); ?>\n    <table class=\"form-table\">\n        <tr><th><label for=\"discount_name\">Pavadinimas</label></th>\n            <td><input type=\"text\" name=\"discount_name\" value=\"<?php echo esc_attr(\$discount->discount_name ?? ''); ?>\" required></td></tr>\n        <tr><th><label for=\"discount_type\">Tipas</label></th>\n            <td><select name=\"discount_type\">\n                <option value=\"fixed\" <?php selected(\$discount->discount_type ?? '', 'fixed'); ?>>Fiksuota suma</option>\n                <option value=\"percentage\" <?php selected(\$discount->discount_type ?? '', 'percentage'); ?>>Procentai</option>\n            </select></td></tr>\n        <tr><th><label for=\"discount_value\">Reikšmė</label></th>\n            <td><input type=\"number\" step=\"0.01\" name=\"discount_value\" value=\"<?php echo esc_attr(\$discount->discount_value ?? ''); ?>\" required></td></tr>\n        <tr><th><label for=\"status\">Būsena</label></th>\n            <td><select name=\"status\">\n                <option value=\"1\" <?php selected(\$discount->status ?? 1, 1); ?>>Aktyvi</option>\n                <option value=\"0\" <?php selected(\$discount->status ?? 1, 0); ?>>Neaktyvi</option>\n            </select></td></tr>\n        <tr><th><label for=\"conditions\">Sąlygos (JSON)</label></th>\n            <td><textarea name=\"conditions\" rows=\"5\"><?php echo esc_textarea(\$discount->conditions ?? ''); ?></textarea></td></tr>\n    </table>\n    <p class=\"submit\"><input type=\"submit\" name=\"wcad_save_discount\" class=\"button-primary\" value=\"Išsaugoti\"></p>\n</form>\n</div>");

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
