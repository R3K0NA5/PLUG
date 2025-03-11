<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

/**
 * Nuolaidų valdiklis
 */
class WCAD_Discount_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'wcad_discount_widget',
            __('Aktyvios Nuolaidos', 'wc-advanced-discounts'),
            array('description' => __('Rodo aktyvias nuolaidas jūsų parduotuvėje.', 'wc-advanced-discounts'))
        );
    }

    /**
     * Atvaizduojame valdiklį
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . __('Aktyvios Nuolaidos', 'wc-advanced-discounts') . $args['after_title'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discounts = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");

        if (!empty($discounts)) {
            echo '<ul class="wcad-discount-list">';
            foreach ($discounts as $discount) {
                echo '<li>' . esc_html($discount->discount_name) . ': ';
                echo esc_html($discount->discount_value);
                echo ($discount->discount_type == 'percent') ? '%' : '€';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __('Šiuo metu nėra aktyvių nuolaidų.', 'wc-advanced-discounts') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Valdiklio nustatymų forma
     */
    public function form($instance) {
        echo '<p>' . __('Šis valdiklis neturi papildomų nustatymų.', 'wc-advanced-discounts') . '</p>';
    }
}

/**
 * Užregistruojame valdiklį
 */
function wcad_register_widget() {
    register_widget('WCAD_Discount_Widget');
}
add_action('widgets_init', 'wcad_register_widget');
?>