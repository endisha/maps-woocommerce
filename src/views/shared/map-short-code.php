<?php

/**
 * Map template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<div id="map-woocommerce-checkout-order" <?php if (!empty($class)) : ?>class="<?php echo esc_attr($class); ?>" <?php endif; ?><?php if (!empty($styles)) : ?> style="
    <?php foreach ($styles as $property => $value) : ?>
        <?php echo esc_attr($property); ?>: <?php echo esc_attr($value); ?>;
    <?php endforeach; ?>
    " <?php endif; ?>>
    <?php if (!empty($html)) : ?>
        <?php echo wp_kses($html, array(
            'div' => array(
                'id' => array(),
                'class' => array(),
                'style' => array(),
            ),
            'span' => array(
                'class' => array(),
                'style' => array(),
            ),
        )); ?>
    <?php endif; ?>
</div>
<?php
