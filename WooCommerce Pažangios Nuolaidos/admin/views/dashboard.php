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
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/dashboard.php', "<?php\n echo '<div class=\"wrap\">';\n echo '<h1>Nuolaidų Valdymas</h1>';\n echo '<p>Sveiki atvykę į WooCommerce pažangios nuolaidos valdymo skydelį.</p>';\n echo '</div>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-list.php', "<?php\n echo '<div class=\"wrap\">';\n echo '<h1>Visos Nuolaidos</h1>';\n echo '<p>Čia bus rodomas visų pridėtų nuolaidų sąrašas.</p>';\n echo '</div>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/discount-edit.php', "<?php\n echo '<div class=\"wrap\">';\n echo '<h1>Redaguoti Nuolaidą</h1>';\n echo '<p>Čia bus forma nuolaidų kūrimui ir redagavimui.</p>';\n echo '</div>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/logs.php', "<?php\n echo '<div class=\"wrap\">';\n echo '<h1>Nuolaidų Žurnalas</h1>';\n echo '<p>Čia bus rodomi visi užregistruoti nuolaidų naudojimo įrašai.</p>';\n echo '</div>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'admin/views/settings.php', "<?php\n echo '<div class=\"wrap\">';\n echo '<h1>Nuolaidų Papildinio Nustatymai</h1>';\n echo '<p>Čia bus konfigūruojami papildinio nustatymai.</p>';\n echo '</div>';\n ?>");

// Šablonai vartotojo pusei
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/discount-cart.php', "<?php\n echo '<p>Rodomos nuolaidos krepšelyje.</p>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/discount-message.php', "<?php\n echo '<p>Specialūs nuolaidų pranešimai.</p>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/discount-widget.php', "<?php\n echo '<p>Nuolaidų valdiklis šoninei juostai.</p>';\n ?>");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'templates/uninstall.php', "<?php\n echo '<p>Šis failas bus naudojamas papildinio pašalinimui.</p>';\n ?>");

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