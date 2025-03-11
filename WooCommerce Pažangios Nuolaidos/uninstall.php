<?php
/**
 * WooCommerce Pažangios Nuolaidos - Pašalinimo failas
 */

// Užtikrinti, kad failas būtų paleistas tik per WordPress pašalinimo mechanizmą
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Gauti WordPress duomenų bazės objektą
global $wpdb;

// Lentelių pavadinimai
$discounts_table = $wpdb->prefix . 'wcad_discounts';
$logs_table = $wpdb->prefix . 'wcad_logs';

// Pašalinti duomenų bazės lenteles
$wpdb->query("DROP TABLE IF EXISTS $discounts_table");
$wpdb->query("DROP TABLE IF EXISTS $logs_table");

// Pašalinti visus papildinio nustatymus
delete_option('wcad_allow_discount_stacking');
delete_option('wcad_default_discount_status');
delete_option('wcad_default_expiration_days');

// Pašalinti visus transients, jei jie buvo naudojami
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wcad_%'");

// Pašalinti vartotojo meta duomenis, jei buvo naudojami
$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'wcad_%'");

// Pašalinti visus nustatymus iš `wp_options`
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wcad_%'");

// Papildomo valymo veiksmai (jei reikia galima pridėti)

// Sukurti CSS failus
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'assets/css/admin.css', "/* WooCommerce Nuolaidų Administratoriaus Stiliai */\nbody { font-family: Arial, sans-serif; }\n.wcad-admin-table { width: 100%; border-collapse: collapse; }\n.wcad-admin-table th, .wcad-admin-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }\n.wcad-admin-table th { background-color: #f4f4f4; }\n");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'assets/css/frontend.css', "/* WooCommerce Nuolaidų Vartotojo Sąsajos Stiliai */\n.woocommerce-info { background-color: #f9f9f9; border-left: 5px solid #007cba; padding: 10px; margin-bottom: 15px; }\n");

// Sukurti JavaScript failus
file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'assets/js/admin.js', "// WooCommerce Nuolaidų Administratoriaus Skriptai\n(function($) {\n    $(document).ready(function() {\n        $('.delete-discount').on('click', function(e) {\n            e.preventDefault();\n            if (confirm('Ar tikrai norite ištrinti šią nuolaidą?')) {\n                let discountId = $(this).data('id');\n                $.post(ajaxurl, { action: 'wcad_delete_discount', id: discountId }, function(response) {\n                    location.reload();\n                });\n            }\n        });\n    });\n})(jQuery);");

file_put_contents(WC_AD_DISCOUNTS_PLUGIN_DIR . 'assets/js/frontend.js', "// WooCommerce Nuolaidų Vartotojo Sąsajos Skriptai\n(function($) {\n    $(document).ready(function() {\n        $('.apply-discount').on('click', function(e) {\n            e.preventDefault();\n            let discountCode = $('#discount-code').val();\n            $.post(wcad_ajax.url, { action: 'wcad_apply_discount', code: discountCode }, function(response) {\n                alert(response.message);\n                location.reload();\n            });\n        });\n    });\n})(jQuery);");

// Sukurti paveikslėlių katalogą, jei reikalinga (tuščias failas)
touch(WC_AD_DISCOUNTS_PLUGIN_DIR . 'assets/images/.placeholder');
?>
