<?php

/**
 * Order map template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<?php if ($exist) : ?>
    <div class="maps-woocommerce-map-wrapper">
        <?php
        $class = apply_filters('maps_woocommerce_order_map_class', 'order-map');
        MWPLG_Include_Map_Helper::include_map(
            apply_filters(
                'maps_woocommerce_order_map_css_properties',
                array_merge(
                    array(
                        'width' => '100%',
                        'height' => '300px',
                        'border' => '1px solid #ccc',
                        'border-radius' => '5px',
                    ),
                    $properties ?? array()
                )
            ),
            $class
        );
        ?>
    </div>
<?php else : ?>
    <p class="maps-woocommerce-description maps-woocommerce-no-data">
        <?php esc_html_e('No map data available for this order.', 'maps-woocommerce'); ?>
    </p>
<?php endif;
