<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

// Nuolaidų žurnalo puslapis
?>
<div class="wrap">
    <h1>Nuolaidų Žurnalas</h1>
    <table class="widefat fixed">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nuolaidos Kodas</th>
            <th>Vartotojas</th>
            <th>Data</th>
            <th>Būsena</th>
        </tr>
        </thead>
        <tbody>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcad_logs';
        $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . esc_html($log->id) . '</td>';
            echo '<td>' . esc_html($log->discount_code) . '</td>';
            echo '<td>' . esc_html(get_userdata($log->user_id)->display_name) . '</td>';
            echo '<td>' . esc_html($log->created_at) . '</td>';
            echo '<td>' . esc_html($log->status) . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>