<?php

/**
 * Frontend class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Frontend
{

	public function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	public function enqueue_scripts(): void
	{
		$resource_file = $this->get_map_source_file();
		if (!empty($resource_file)) {
			$data = (new MWPLG_Map_Service)->get_map_js_data();
			wp_enqueue_script('google-maps-api', MWPLG_Settings_Helper::get_google_maps_api_url(), array('maps-woocommerce', 'jquery'), MWPLG_MAPS_WOOCOMMERCE_VERSION, false);
			wp_enqueue_script('maps-woocommerce', MWPLG_Global_Helper::load_local_js_asset_file($resource_file), array('jquery'), MWPLG_MAPS_WOOCOMMERCE_VERSION, true);
			wp_localize_script('maps-woocommerce', 'MapsWoocommerce', $data);
			wp_enqueue_style('maps-woocommerce', MWPLG_Global_Helper::load_local_css_asset_file('style.css'), array(), MWPLG_MAPS_WOOCOMMERCE_VERSION);
			wp_add_inline_style('maps-woocommerce', apply_filters('maps_woocommerce_inline_css', ''));
		}
	}

	protected function get_map_source_file(): string
	{
		$checkout = MWPLG_Global_Helper::should_enqueue_scripts();
		$order = MWPLG_Global_Helper::should_enqueue_order_scripts();
		$adminarea = MWPLG_Global_Helper::should_enqueue_admin_scripts();
		$user_admin_profile = MWPLG_Global_Helper::should_enqueue_admin_user_scripts();

		$resource_file = '';
		if ($checkout || $user_admin_profile) {
			$resource_file = 'checkout-map-script.js';
		} elseif ($order || $adminarea) {
			$resource_file = 'order-map-script.js';
		}
		return $resource_file;
	}
}
