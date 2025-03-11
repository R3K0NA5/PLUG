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
}
add_action('plugins_loaded', 'wcad_initialize_plugin');

// Aktyvavimo funkcija
class WCAD_Activator {
    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            discount_name varchar(255) NOT NULL,
            discount_type varchar(50) NOT NULL,
            discount_value float NOT NULL,
            conditions text NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, array('WCAD_Activator', 'activate'));

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

// Nuolaidų valdymo klasė
class WCAD_Discounts {
    public function __construct() {
        add_action('woocommerce_cart_calculate_fees', array($this, 'apply_discounts'));
    }

    public function apply_discounts($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        $discount_amount = 5; // Testavimo suma, vėliau bus dinaminė
        $cart->add_fee(__('Nuolaida', 'wc-advanced-discounts'), -$discount_amount);
    }
}

// Krepšelio nuolaidų valdymo klasė
class WCAD_Cart {
    public function __construct() {
        add_action('woocommerce_before_calculate_totals', array($this, 'apply_cart_discounts'));
    }

    public function apply_cart_discounts($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $discount_percentage = get_post_meta($product_id, '_wcad_discount_percentage', true);

            if ($discount_percentage) {
                $original_price = $cart_item['data']->get_regular_price();
                $discounted_price = $original_price * ((100 - $discount_percentage) / 100);
                $cart_item['data']->set_price($discounted_price);
            }
        }
    }
}