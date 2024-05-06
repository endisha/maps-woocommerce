<?php

/**
 * Checkout map template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<div class="form-row form-row-wide">
    <label for="<?php echo esc_attr($key); ?>" class="maps-woocommerce-label">
        <?php esc_html_e('Location', 'maps-woocommerce'); ?>
        <?php if ($required) : ?>
            <abbr class="required" title="required">*</abbr>
        <?php else : ?>
            <span class="optional"><?php esc_html_e('(optional)', 'maps-woocommerce'); ?></span>
        <?php endif; ?>
    </label>
    <div class="maps-woocommerce-map-wrapper">
        <?php
        $class = apply_filters('maps_woocommerce_checkout_map_class', 'checkout-map');
        MWPLG_Include_Map_Helper::include_map(apply_filters('maps_woocommerce_checkout_map_css_properties', array(
            'height' => '300px',
            'border' => '1px solid #eee',
            'border-radius' => '5px'
        )), $class);
        ?>
    </div>
    <span class="maps-woocommerce-description">
        <?php esc_html_e('Drag the marker to choose your location.', 'maps-woocommerce'); ?>
    </span>
</div>
<p></p>
<?php
