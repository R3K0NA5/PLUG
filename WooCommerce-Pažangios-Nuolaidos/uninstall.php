<?php
/**
 * WooCommerce-Pažangios-Nuolaidos - Pašalinimo failas
 */

// Užtikrinti, kad failas būtų paleistas tik per WordPress pašalinimo mechanizmą
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Gauti WordPress duomenų bazės objektą
global $wpdb;

// Lentelių pavadinimai
$discounts_table = esc_sql($wpdb->prefix . 'wcad_discounts');
$logs_table = esc_sql($wpdb->prefix . 'wcad_logs');

// Pašalinti duomenų bazės lenteles
$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $discounts_table ) );
$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $logs_table ) );

// Pašalinti visus papildinio nustatymus
delete_option('wcad_allow_discount_stacking');
delete_option('wcad_default_discount_status');
delete_option('wcad_default_expiration_days');

// Pašalinti visus transients, jei jie buvo naudojami
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wcad_%'");

// Pašalinti vartotojo meta duomenis, jei buvo naudojami
$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'wcad_%'");

// Pašalinti visus nustatymus iš `wp_options`
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wcad_%'");

// AJAX funkcionalumo patikrinimas
add_action('wp_ajax_wcad_delete_discount', 'wcad_delete_discount_callback');
function wcad_delete_discount_callback() {
    global $wpdb;
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        wp_send_json_error(['message' => 'Neteisingas ID']);
    }
    $discount_id = intval($_POST['id']);
    $table_name = $wpdb->prefix . 'wcad_discounts';
    $wpdb->delete($table_name, ['id' => $discount_id], ['%d']);
    wp_send_json_success(['message' => 'Nuolaida ištrinta']);
}

// AJAX funkcionalumas nuolaidų taikymui krepšelyje
add_action('wp_ajax_wcad_apply_discount', 'wcad_apply_discount_callback');
add_action('wp_ajax_nopriv_wcad_apply_discount', 'wcad_apply_discount_callback');
function wcad_apply_discount_callback() {
    if (!isset($_POST['code']) || empty($_POST['code'])) {
        wp_send_json_error(['message' => 'Neteisingas nuolaidos kodas']);
    }
    $discount_code = sanitize_text_field($_POST['code']);

    // Tikriname ar nuolaida egzistuoja
    global $wpdb;
    $table_name = $wpdb->prefix . 'wcad_discounts';
    $discount = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE discount_code = %s AND status = 1", $discount_code));

    if (!$discount) {
        wp_send_json_error(['message' => 'Nuolaida nerasta arba neaktyvi']);
    }

    // Pridedame nuolaidą į WooCommerce krepšelį
    WC()->cart->add_discount($discount_code);

    wp_send_json_success(['message' => 'Nuolaida pritaikyta!']);
}

// AJAX funkcionalumas nuolaidų peržiūrai be puslapio perkrovimo
add_action('wp_ajax_wcad_get_discounts', 'wcad_get_discounts_callback');
add_action('wp_ajax_nopriv_wcad_get_discounts', 'wcad_get_discounts_callback');
function wcad_get_discounts_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wcad_discounts';
    $discounts = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");

    if (!$discounts) {
        wp_send_json_error(['message' => 'Nėra aktyvių nuolaidų']);
    }

    $response = [];
    foreach ($discounts as $discount) {
        $response[] = [
            'name' => esc_html($discount->discount_name),
            'code' => esc_html($discount->discount_code),
            'value' => esc_html($discount->discount_value),
            'type' => esc_html($discount->discount_type)
        ];
    }

    wp_send_json_success(['discounts' => $response]);
}
?>