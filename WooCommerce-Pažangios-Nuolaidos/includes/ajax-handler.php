<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Ajax_Handler {
    /**
     * Konstruktorius - registruoja AJAX veiksmus
     */
    public function __construct() {
        add_action('wp_ajax_wcad_apply_discount', array($this, 'apply_discount'));
        add_action('wp_ajax_nopriv_wcad_apply_discount', array($this, 'apply_discount'));
    }

    /**
     * Taiko nuolaidą AJAX užklausoje
     */
    public function apply_discount() {
        if (!isset($_POST['discount_code'])) {
            wp_send_json_error(['message' => __('Nuolaidos kodas nerastas.', 'wc-advanced-discounts')]);
        }

        $discount_code = sanitize_text_field($_POST['discount_code']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discount = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE discount_name = %s AND status = 1", $discount_code));

        if (!$discount) {
            wp_send_json_error(['message' => __('Nuolaida nerasta arba ji nebegalioja.', 'wc-advanced-discounts')]);
        }

        WC()->session->set('wcad_discount', $discount_code);
        wp_send_json_success(['message' => __('Nuolaida pritaikyta!', 'wc-advanced-discounts')]);
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Ajax_Handler')) {
    new WCAD_Ajax_Handler();
}
?>
