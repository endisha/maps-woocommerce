<?php

/**
 * Preview map template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<table class="form-table" aria-describedby="preview-map">
    <tr>
        <th>
            <label for="mwplg_maps_woocommerce_map_default_latitude">
                <?php esc_html_e('Use Default Coordinates', 'maps-woocommerce'); ?>
            </label>
        </th>
        <td>
            <?php
            woocommerce_form_field(
                'mwplg_maps_woocommerce_map_use_default_coordinates',
                array(
                    'type' => 'checkbox',
                    'label' => __('Use default coordinates for users without saved coordinates', 'maps-woocommerce'),
                ),
                sanitize_text_field(get_option('mwplg_maps_woocommerce_map_use_default_coordinates', 'no')) == 'yes'
            );
            ?>
            <p class="description"><?php esc_html_e('If unchecked, the map will load with zero coordinates.', 'maps-woocommerce'); ?></p>
        </td>

    </tr>
    <tr>
        <th>
            <label for="mwplg_maps_woocommerce_map_default_latitude">
                <?php esc_html_e('Default Coordinates', 'maps-woocommerce'); ?>
            </label>
        </th>
        <td>
            <?php
            woocommerce_form_field(
                'mwplg_maps_woocommerce_map_default_latitude',
                array(
                    'default' => '0',
                    'type' => 'text',
                    'placeholder' => __('Latitude', 'maps-woocommerce'),
                ),
                sanitize_text_field(get_option('mwplg_maps_woocommerce_map_default_latitude', null))
            );
            woocommerce_form_field(
                'mwplg_maps_woocommerce_map_default_longitude',
                array(
                    'default' => '0',
                    'type' => 'text',
                    'placeholder' => __('Longitude', 'maps-woocommerce'),
                ),
                sanitize_text_field(get_option('mwplg_maps_woocommerce_map_default_longitude', null))
            );
            ?>
            <p><?php esc_html_e('Enter the default coordinates to be used during the initial load when the user has not yet saved their coordinates.', 'maps-woocommerce'); ?></p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="maps_woocommerce_map_preview"><?php esc_html_e('Map Preview', 'maps-woocommerce'); ?></label>
        </th>
        <td>

            <?php if ($preview) : ?>
                <?php
                $html = wp_kses_post('
                <div id="map-loader" class="loader">
                    <span class="spinner is-active spin spinner-button" style="display: block;"></span>
                    <span class="spinner-text">' . esc_html__('Loading map...', 'maps-woocommerce') . '</span>
                </div>');
                ?>
                <?php
                MWPLG_Include_Map_Helper::include_map(
                    apply_filters('maps_woocommerce_preview_map_css_properties', array(
                        'width' => '50%',
                        'height' => '300px'
                    )),
                    'map',
                    $html
                );
                ?>
                <p class="map-preview-description">
                    <?php esc_html_e('Drag the marker to define the initial map coordinates.', 'maps-woocommerce'); ?>
                </p>

            <?php else : ?>
                <p class="map-preview-description">
                    <?php esc_html_e('The map will appear when an active Google API key is entered.', 'maps-woocommerce'); ?>
                </p>
            <?php endif; ?>

        </td>
    </tr>
</table>
<?php
