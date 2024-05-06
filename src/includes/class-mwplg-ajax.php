<?php

/**
 * Ajax class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_mwplg_maps_woocommerce_get_style_preview', $this);
    }

    public function __invoke()
    {
        $nonce = sanitize_text_field($_POST['nonce'] ?? '');
        if (!wp_verify_nonce($nonce, 'maps_woocommerce_nonce')) {
            wp_send_json_error(__('Token mismatch', 'map-woocommerce'), 400);
        }

        $style_name = sanitize_text_field($_POST['style_name'] ?? '');

        $service = new MWPLG_Map_Service;
        $styles = $service->get_map_style_json($style_name);

        wp_send_json_success($styles);
    }
}
