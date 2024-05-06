<?php

/**
 * Visibility settings fields template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<h2><?php esc_html_e('Map Visibility', 'maps-woocommerce'); ?></h2>

<p><?php esc_html_e('This section lets you control where the map appears and displays.', 'maps-woocommerce'); ?></p>

<table class="form-table maps_woocommerce_visibility_settings_fields" aria-describedby="visiblity-fields">

    <?php foreach ($visiblity_fields as $key => $option) :
        $option_key = sprintf("mwplg_maps_woocommerce_visibility_key[%s]", $key);
    ?>
        <tr>
            <th>
                <label for="mwplg_maps_woocommerce_visibility_key_option">
                    <?php echo esc_html($option['name']); ?>
                </label>
            </th>
            <td>
                <?php
                woocommerce_form_field(
                    $option_key,
                    array(
                        'type' => 'checkbox',
                        'class' => sanitize_key($option_key),
                        'label' => sanitize_text_field($option['desc'])
                    ),
                    MWPLG_Map_Visibility_Helper::isVisible($key)
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
