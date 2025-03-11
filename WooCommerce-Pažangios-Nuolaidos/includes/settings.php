<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Settings {
    /**
     * Konstruktorius - registruoja nustatymų puslapį
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Pridedame nustatymų puslapį
     */
    public function add_settings_page() {
        add_options_page(
            __('Nuolaidų nustatymai', 'wc-advanced-discounts'),
            __('Nuolaidų nustatymai', 'wc-advanced-discounts'),
            'manage_options',
            'wcad_settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Užregistruojame nustatymus
     */
    public function register_settings() {
        register_setting('wcad_settings_group', 'wcad_allow_discount_stacking');
        register_setting('wcad_settings_group', 'wcad_default_expiration_days');
    }

    /**
     * Atvaizduojame nustatymų puslapį
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Nuolaidų nustatymai', 'wc-advanced-discounts'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('wcad_settings_group'); ?>
                <?php do_settings_sections('wcad_settings_page'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="wcad_allow_discount_stacking">Leisti Nuolaidų Krovimą</label></th>
                        <td><input type="checkbox" name="wcad_allow_discount_stacking" id="wcad_allow_discount_stacking" value="1" <?php checked(1, get_option('wcad_allow_discount_stacking'), true); ?>></td>
                    </tr>
                    <tr>
                        <th><label for="wcad_default_expiration_days">Numatomas Galiojimo Laikas (dienomis)</label></th>
                        <td><input type="number" name="wcad_default_expiration_days" id="wcad_default_expiration_days" value="<?php echo esc_attr(get_option('wcad_default_expiration_days', 30)); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="save_settings" class="button-primary" value="Išsaugoti Nustatymus">
                </p>
            </form>
        </div>
        <?php
    }
}

// Inicializuojame klasę tik jei ji dar neegzistuoja
if (!class_exists('WCAD_Settings')) {
    new WCAD_Settings();
}
?>