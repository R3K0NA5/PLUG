<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

global $wpdb;
$table_name = $wpdb->prefix . 'wcad_discounts';
$discounts = $wpdb->get_results("SELECT * FROM $table_name");
?>

<div class="wrap">
    <h1><?php _e('Visos Nuolaidos', 'wc-advanced-discounts'); ?></h1>
    <a href="admin.php?page=wcad_add_discount" class="page-title-action">Pridėti naują</a>

    <table class="widefat fixed" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Pavadinimas</th>
            <th>Tipas</th>
            <th>Reikšmė</th>
            <th>Būsena</th>
            <th>Veiksmai</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($discounts as $discount) : ?>
            <tr>
                <td><?php echo esc_html($discount->id); ?></td>
                <td><?php echo esc_html($discount->discount_name); ?></td>
                <td><?php echo esc_html($discount->discount_type); ?></td>
                <td><?php echo esc_html($discount->discount_value); ?></td>
                <td><?php echo ($discount->status) ? 'Aktyvus' : 'Neaktyvus'; ?></td>
                <td>
                    <a href="admin.php?page=wcad_add_discount&id=<?php echo $discount->id; ?>" class="button">Redaguoti</a>
                    <a href="#" class="button delete-discount" data-id="<?php echo $discount->id; ?>">Ištrinti</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-discount').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let discountId = this.getAttribute('data-id');
                if (confirm('Ar tikrai norite ištrinti šią nuolaidą?')) {
                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'action=wcad_delete_discount&id=' + discountId
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Klaida šalinant nuolaidą.');
                        }
                    });
                }
            });
        });
    });
</script>