<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

class WCAD_Deactivator {
    /**
     * Papildinio išjungimo funkcija
     */
    public static function deactivate() {
        // Išjungimo metu galime išvalyti cache, nustatyti tam tikrus statusus ir pan.
        flush_rewrite_rules();
    }
}

// Priskiriame išjungimo funkciją
register_deactivation_hook(__FILE__, array('WCAD_Deactivator', 'deactivate'));
?>