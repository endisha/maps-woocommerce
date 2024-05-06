<?php

/**
 * Map fields template
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;
?>

<div id="checkout_latitude_longitude_fields" style="display: none">
    <?php
    woocommerce_form_field($latitude_key, array('type' => 'hidden'));
    woocommerce_form_field($longitude_key, array('type' => 'hidden'));
    ?>
</div>
<?php
