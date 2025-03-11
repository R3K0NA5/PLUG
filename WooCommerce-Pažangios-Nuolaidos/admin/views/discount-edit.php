<?php
// Uždrausti tiesioginę prieigą
if (!defined('ABSPATH')) exit;

// Nuolaidų redagavimo puslapis
?>
<div class="wrap">
    <h1>Redaguoti Nuolaidą</h1>
    <form method="post">
        <table class="form-table">
            <tr>
                <th><label for="discount_name">Nuolaidos Pavadinimas</label></th>
                <td><input type="text" name="discount_name" id="discount_name" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="discount_type">Nuolaidos Tipas</label></th>
                <td>
                    <select name="discount_type" id="discount_type">
                        <option value="percent">Procentinė</option>
                        <option value="fixed">Fiksuota suma</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="discount_value">Nuolaidos Vertė</label></th>
                <td><input type="number" name="discount_value" id="discount_value" class="regular-text" step="0.01"></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="save_discount" id="save_discount" class="button-primary" value="Išsaugoti">
        </p>
    </form>
</div>