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
}
add_action('plugins_loaded', 'wcad_initialize_plugin');

// Nuolaidų naudojimo žurnalo klasė
class WCAD_Logs {
    public function __construct() {
        add_action('woocommerce_order_status_completed', array($this, 'log_discount_usage'));
    }

    public function log_discount_usage($order_id) {
        $order = wc_get_order($order_id);
        $discounts = $order->get_used_coupons();

        if (!empty($discounts)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wcad_discount_logs';

            foreach ($discounts as $discount) {
                $wpdb->insert(
                    $table_name,
                    array(
                        'order_id' => $order_id,
                        'discount_code' => $discount,
                        'used_at' => current_time('mysql')
                    ),
                    array('%d', '%s', '%s')
                );
            }
        }
    }
}

// Planuojamų nuolaidų valdymo klasė
class WCAD_Cron {
    public function __construct() {
        add_action('wcad_check_scheduled_discounts', array($this, 'apply_scheduled_discounts'));
        if (!wp_next_scheduled('wcad_check_scheduled_discounts')) {
            wp_schedule_event(time(), 'hourly', 'wcad_check_scheduled_discounts');
        }
    }

    public function apply_scheduled_discounts() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $current_time = current_time('mysql');

        // Aktyvuoti suplanuotas nuolaidas
        $wpdb->query("UPDATE $table_name SET status = 1 WHERE status = 0 AND conditions LIKE '%scheduled%' AND conditions <= '$current_time'");
    }
}
register_activation_hook(__FILE__, function() {
    if (!wp_next_scheduled('wcad_check_scheduled_discounts')) {
        wp_schedule_event(time(), 'hourly', 'wcad_check_scheduled_discounts');
    }
});

register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('wcad_check_scheduled_discounts');
});