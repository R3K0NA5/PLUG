<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

// Nuolaidų nustatymų puslapis
?>
<div class="wrap">
    <h1>Nuolaidų Nustatymai</h1>
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