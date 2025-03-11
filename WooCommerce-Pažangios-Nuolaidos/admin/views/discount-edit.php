<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

global $wpdb;
$table_name = $wpdb->prefix . 'wcad_discounts';
$discount_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$discount = ($discount_id) ? $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $discount_id)) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $discount_name = sanitize_text_field($_POST['discount_name']);
    $discount_type = sanitize_text_field($_POST['discount_type']);
    $discount_value = floatval($_POST['discount_value']);
    $status = isset($_POST['status']) ? 1 : 0;

    if ($discount_id) {
        // Atnaujinti nuolaidą
        $wpdb->update(
            $table_name,
            ['discount_name' => $discount_name, 'discount_type' => $discount_type, 'discount_value' => $discount_value, 'status' => $status],
            ['id' => $discount_id]
        );
    } else {
        // Sukurti naują nuolaidą
        $wpdb->insert(
            $table_name,
            ['discount_name' => $discount_name, 'discount_type' => $discount_type, 'discount_value' => $discount_value, 'status' => $status]
        );
    }
    wp_redirect(admin_url('admin.php?page=wcad_discounts'));
    exit;
}
?>

<div class="wrap">
    <h1><?php echo ($discount_id) ? __('Redaguoti nuolaidą', 'wc-advanced-discounts') : __('Pridėti naują nuolaidą', 'wc-advanced-discounts'); ?></h1>
    <form method="POST">
        <table class="form-table">
            <tr>
                <th><label for="discount_name">Pavadinimas</label></th>
                <td><input type="text" name="discount_name" id="discount_name" value="<?php echo esc_attr($discount->discount_name ?? ''); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="discount_type">Nuolaidos tipas</label></th>
                <td>
                    <select name="discount_type" id="discount_type">
                        <option value="percent" <?php selected($discount->discount_type ?? '', 'percent'); ?>>%</option>
                        <option value="fixed" <?php selected($discount->discount_type ?? '', 'fixed'); ?>>€</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="discount_value">Reikšmė</label></th>
                <td><input type="number" step="0.01" name="discount_value" id="discount_value" value="<?php echo esc_attr($discount->discount_value ?? ''); ?>" required></td>
            </tr>
            <tr>
                <th><label for="status">Aktyvus</label></th>
                <td><input type="checkbox" name="status" id="status" <?php checked($discount->status ?? 0, 1); ?>></td>
            </tr>
        </table>
        <p class="submit">
            <button type="submit" class="button button-primary">Išsaugoti</button>
        </p>
    </form>
</div>
