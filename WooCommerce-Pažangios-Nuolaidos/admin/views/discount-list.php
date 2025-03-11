<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

// Nuolaidų sąrašo puslapis
?>
<div class="wrap">
    <h1>Visos Nuolaidos</h1>
    <table class="widefat fixed">
        <thead>
        <tr>
            <th>ID</th>
            <th>Pavadinimas</th>
            <th>Tipas</th>
            <th>Vertė</th>
            <th>Būsena</th>
            <th>Veiksmai</th>
        </tr>
        </thead>
        <tbody>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_discounts';
        $discounts = $wpdb->get_results("SELECT * FROM $table_name");
        foreach ($discounts as $discount) {
            echo '<tr>';
            echo '<td>' . esc_html($discount->id) . '</td>';
            echo '<td>' . esc_html($discount->discount_name) . '</td>';
            echo '<td>' . esc_html($discount->discount_type) . '</td>';
            echo '<td>' . esc_html($discount->discount_value) . '</td>';
            echo '<td>' . ($discount->status ? 'Aktyvus' : 'Neaktyvus') . '</td>';
            echo '<td><a href="admin.php?page=wcad_discount_edit&id=' . $discount->id . '" class="button">Redaguoti</a> | <a href="#" class="button delete-discount" data-id="' . $discount->id . '">Ištrinti</a></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>