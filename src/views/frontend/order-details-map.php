<?php

/**
 * Order map details template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<?php if ($exist) : ?>
    <section class="woocommerce-map-details">
        <h2 class="woocommerce-order-map-details__title"><?php esc_html_e('Location', 'maps-woocommerce'); ?></h2>
        <div class="maps-woocommerce-map-wrapper">
            <?php
            $class = apply_filters('maps_woocommerce_order_details_map_class', 'order-map');
            MWPLG_Include_Map_Helper::include_map(apply_filters(
                'maps_woocommerce_order_details_map_css_properties',
                array_merge(array(
                    'width' => '100%',
                    'height' => '300px',
                    'border' => '1px solid #ccc',
                    'border-radius' => '5px',
                ), $properties ?? array())
            ), $class);
            ?>
        </div>
    </section>
    <p></p>
<?php endif;
