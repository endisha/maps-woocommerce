<?php

/**
 * Admin settings fields config file
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

return array(
    array(
        'name' => __('Maps for WooCommerce Settings', 'maps-woocommerce'),
        'desc' =>  __('Configure Google Maps for your WooCommerce store.', 'maps-woocommerce'),
        'desc_tip' => __('Integrate Google Maps with WooCommerce for easy location selection during checkout and in user account addresses to elevate the shopping experience.', 'maps-woocommerce'),
        'type' => 'title',
        'id' => 'maps_woocommerce_options'
    ),
    array(
        'name' => __('Activate', 'maps-woocommerce'),
        'desc' => __('Enable or disable the maps for WooCommerce.', 'maps-woocommerce'),
        'type' => 'checkbox',
        'id' => 'mwplg_maps_woocommerce_active'
    ),
    array(
        'name' => __('API Key', 'maps-woocommerce'),
        'desc' => __('Google Maps API Key.', 'maps-woocommerce'),
        'desc_tip' => __('The plugin will not be activated until a valid key is entered.', 'maps-woocommerce'),
        'type' => 'text',
        'id' => 'mwplg_maps_woocommerce_google_maps_api_key'
    ),
    array(
        'type' => 'sectionend',
        'id' => 'maps_woocommerce_options'
    ),
    array(
        'name' => __('Map style and display settings', 'maps-woocommerce'),
        'type' => 'title',
        'id' => 'maps_woocommerce_style_options'
    ),
    array(
        'name' => __('Set as Required', 'maps-woocommerce'),
        'desc' => __('Make it mandatory for users to select a location on the map before proceeding to checkout and completing payment.', 'maps-woocommerce'),
        'type' => 'checkbox',
        'id' => 'mwplg_maps_woocommerce_force_enter_location'
    ),
    array(
        'name' => __('Auto Detect User Location', 'maps-woocommerce'),
        'desc' => __('Automatically detect the user\'s location if they haven\'t saved their coordinates yet.', 'maps-woocommerce'),
        'desc_tip' => __('Requires location access permission to automatically determine the user\'s current location.', 'maps-woocommerce'),
        'type' => 'checkbox',
        'id' => 'mwplg_maps_woocommerce_auto_detect'
    ),
    array(
        'name' => __('Map Display Location', 'maps-woocommerce'),
        'desc' => __('Choose the address section where you want the map to be displayed.', 'maps-woocommerce'),
        'type' => 'select',
        'options' => array(
            'billing' => __('Billing Address', 'maps-woocommerce'),
            'shipping' => __('Shipping Address', 'maps-woocommerce'),
        ),
        'id' => 'mwplg_maps_woocommerce_map_display_location'
    ),
    array(
        'name' => __('Map Style', 'maps-woocommerce'),
        'desc' => __('Choose the map style you prefer to display.', 'maps-woocommerce'),
        'type' => 'select',
        'options' => apply_filters('maps_woocommerce_map_styles', array(
            'standard' => __('Standard', 'maps-woocommerce'),
            'silver' => __('Silver', 'maps-woocommerce'),
            'retro' => __('Retro', 'maps-woocommerce'),
            'dark' => __('Dark', 'maps-woocommerce'),
            'night' => __('Night', 'maps-woocommerce'),
            'aubergine' => __('Aubergine', 'maps-woocommerce'),
            'water' => __('Water', 'maps-woocommerce'),
            'natural' => __('Natural', 'maps-woocommerce'),
        )),
        'id' => 'mwplg_maps_woocommerce_map_style'
    ),
    array(
        'name' => __('Map Zoom Level', 'maps-woocommerce'),
        'desc' => __('Set the zoom level for the map.', 'maps-woocommerce'),
        'id' => 'mwplg_maps_woocommerce_map_zoom',
        'type' => 'number',
        'custom_attributes' => array(
            'min'  => 0,
            'step' => 1,
            'max' => 22,
        ),
        'css' => 'width: 80px;',
        'default' => '15',
        'autoload' => false,
        'class' => 'mwplg_maps_woocommerce_map_zoom',
    ),
    array(
        'name' => __('Map Marker', 'maps-woocommerce'),
        'desc' => __('Select a marker style for your map.', 'maps-woocommerce'),
        'type' => 'select',
        'options' => array(
            'default' => __('Default Marker', 'maps-woocommerce'),
            'blue' => __('Blue Marker', 'maps-woocommerce'),
            'green' => __('Green Marker', 'maps-woocommerce'),
            'yellow' => __('Yellow Marker', 'maps-woocommerce'),
            'purple' => __('Purple Marker', 'maps-woocommerce'),
            'pink' => __('Pink Marker', 'maps-woocommerce'),
            'custom' => __('+ Custom', 'maps-woocommerce'),
        ),
        'id' => 'mwplg_maps_woocommerce_map_marker'
    ),

    array(
        'type' => 'sectionend',
        'id' => 'maps_woocommerce_style_options'
    )
);
