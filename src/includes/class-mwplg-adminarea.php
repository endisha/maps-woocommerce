<?php

/**
 * Adminarea class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Adminarea
{

	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_filter('woocommerce_settings_tabs_array', array($this, 'include_plugin_tab'), 50);
		add_action('woocommerce_settings_tabs_maps_woocommerce', array($this, 'get_fields'));
		add_action('woocommerce_update_options_maps_woocommerce', array($this, 'update_fields'));
	}

	public function enqueue_scripts(): void
	{
		if (MWPLG_Global_Helper::is_plugin_settings_page()) {
			wp_enqueue_script('maps-woocommerce-plugin-settings', MWPLG_Global_Helper::load_local_js_asset_file('plugin-settings.js'), array('jquery'), MWPLG_MAPS_WOOCOMMERCE_VERSION, true);
			wp_enqueue_style('maps-woocommerce-plugin-settings', MWPLG_Global_Helper::load_local_css_asset_file('plugin-settings.css'), array(), MWPLG_MAPS_WOOCOMMERCE_VERSION);
			wp_enqueue_media();

			if (MWPLG_Settings_Helper::is_valid_google_maps_api_key()) {
				$data = (new MWPLG_Map_Service)->get_admin_preview_js_data();
				wp_enqueue_script('google-maps-api', MWPLG_Settings_Helper::get_google_maps_api_url(), array('maps-woocommerce', 'jquery'), MWPLG_MAPS_WOOCOMMERCE_VERSION, false);
				wp_enqueue_script('maps-woocommerce', MWPLG_Global_Helper::load_local_js_asset_file('preview-map-script.js'), array('jquery'), MWPLG_MAPS_WOOCOMMERCE_VERSION, true);
				wp_localize_script('maps-woocommerce', 'MapsWoocommerce', $data);
			}
		}
	}

	public function include_plugin_tab(array $tabs): array
	{
		$tabs['maps_woocommerce'] = __('Google Maps for WooCommerce', 'maps-woocommerce');
		return $tabs;
	}

	public function get_fields(): void
	{
		$fields = apply_filters('maps_woocommerce_admin_settings_fields', MWPLG_Global_Helper::get_config_file('admin-settings-fields'));

		$settings = new MWPLG_Admin_Settings_Service;
		$settings->get_fields($fields);

		$service = new MWPLG_Map_Service;
		$service->show_custom_marker_input();
		$service->show_preview_map();
		$service->visibility_settings_fields();
	}

	public function update_fields(): void
	{
		$fields = apply_filters('maps_woocommerce_admin_settings_fields', MWPLG_Global_Helper::get_config_file('admin-settings-fields'));
		$visiblity_fields = apply_filters('maps_woocommerce_visibility_settings_fields', MWPLG_Global_Helper::get_config_file('visibility-settings-fields'));

		$settings = new MWPLG_Admin_Settings_Service;
		$settings->update_fields($fields);
		$settings->update_additional_fields();
		$settings->update_visiblity_fields($visiblity_fields);
	}
}
