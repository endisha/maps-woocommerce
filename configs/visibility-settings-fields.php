<?php

/**
 * Visibility settings fields config file
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

return array(
    'checkout_page' => array(
        'name' => __('Checkout Page', 'maps-woocommerce'),
        'desc' =>  __('Show during the checkout page in billing or shipping sections based on the [Map Display Location] setting.', 'maps-woocommerce'),
    ),
    'address_account_page' => array(
        'name' => __('Address Account Page', 'maps-woocommerce'),
        'desc' =>  __('Show in the user account - address page under billing or shipping sections based on the [Map Display Location] setting.', 'maps-woocommerce'),
    ),
    'order_details_page' => array(
        'name' => __('Order Details Page', 'maps-woocommerce'),
        'desc' =>  __('Show in the order details page.', 'maps-woocommerce'),
    ),
    'admin_order_details_page' => array(
        'name' => __('Admin Order Details Page', 'maps-woocommerce'),
        'desc' =>  __('Show in the admin order details page.', 'maps-woocommerce'),
    ),
    'admin_user_profile_page' => array(
        'name' => __('Profile Page', 'maps-woocommerce'),
        'desc' => __('Show in the user profile when editing the user in the admin.', 'maps-woocommerce'),
    ),
);
