<?php

/**
 * Custom marker field template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<table class="form-table mwplg_maps_woocommerce_map_marker_custom_image_wrapper" aria-describedby="custom-marker-field" <?php if (!$selected) : ?>style="display: none" <?php endif; ?>>
    <tr>
        <th>
            <label for="mwplg_maps_woocommerce_map_marker_custom_image">
                <?php esc_html_e('Custom Marker', 'maps-woocommerce'); ?>
            </label>
        </th>
        <td>
            <?php
            woocommerce_form_field(
                'mwplg_maps_woocommerce_map_marker_custom_image',
                array(
                    'type' => 'url',
                    'class' => 'mwplg_maps_woocommerce_map_marker_custom_image_field',
                    'description' => __('Enter a custom marker image URL. The recommended dimensions are 40x40 pixels.', 'maps-woocommerce'),
                ),
                esc_url_raw(get_option('mwplg_maps_woocommerce_map_marker_custom_image', null))
            );
            ?>
            <button id="maps_woocommerce_upload_custom_marker" class="button button-image">
                <span class="dashicons dashicons-format-image marker-custom-image-button"></span>
            </button>
            <button id="mwplg_maps_woocommerce_remove_custom_marker" class="button button-image-reset">
                <span class="dashicons dashicons-remove marker-custom-image-button"></span>
            </button>
        </td>
    </tr>
</table>
<?php
