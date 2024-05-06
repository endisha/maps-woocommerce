<?php

/**
 * Hooks class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Hooks
{
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'order_map_metabox'));
		add_action('woocommerce_after_checkout_validation', [$this, 'validate_checkout_latitude_longitude_coordinates'], 10, 2);
		add_action('woocommerce_after_save_address_validation', [$this, 'validate_saved_address_latitude_longitude_coordinates'], 10, 2);
		add_action('woocommerce_after_order_details', [$this, 'display_map_in_order_details_after_details'], 10);
		add_action('woocommerce_new_order', [$this, 'save_latitude_longitude_coordinates_after_create_new_order'], 10);
		add_action('woocommerce_after_edit_address_form_billing', [$this, 'include_address_account_page_latitude_longitude_fields_to_billing'], 10);
		add_action('woocommerce_after_checkout_billing_form', [$this, 'include_checkout_latitude_longitude_fields_to_billing'], 10);
		add_action('woocommerce_after_edit_address_form_shipping', [$this, 'include_address_account_page_latitude_longitude_fields_to_shipping'], 10);
		add_action('woocommerce_after_checkout_shipping_form', [$this, 'include_checkout_latitude_longitude_fields_to_shipping'], 10);
		add_action('show_user_profile', [$this, 'display_map_in_edit_user_profile']);
		add_action('edit_user_profile', [$this, 'display_map_in_edit_user_profile']);
		add_filter('woocommerce_customer_meta_fields', [$this, 'include_latitude_longitude_coordinates_to_customor_profile_fields'], 100, 1);
		add_action('user_profile_update_errors', [$this, 'validate_user_latitude_longitude_coordinates_in_edit_user_profile'], 10, 3);
		add_action('personal_options_update', array($this, 'save_user_latitude_longitude_coordinates_in_edit_user_profile'));
		add_action('edit_user_profile_update', array($this, 'save_user_latitude_longitude_coordinates_in_edit_user_profile'));
	}

	public function order_map_metabox(): void
	{
		if (MWPLG_Map_Visibility_Helper::isVisible('admin_order_details_page')) {
			add_meta_box('order-map-metabox', __('Order Location Map', 'maps-woocommerce'), [new MWPLG_Map_Service, 'show_admin_order_map'], 'shop_order', 'side', 'core');
		}
	}

	public function save_user_latitude_longitude_coordinates_in_edit_user_profile($user_id)
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('admin_user_profile_page')) {
			return;
		}

		(new MWPLG_Map_Service)->update_user_coordinates(intval($user_id));
	}

	public function validate_user_latitude_longitude_coordinates_in_edit_user_profile($errors, $update, $user)
	{
		if (!$update) {
			return;
		}

		if (!MWPLG_Map_Visibility_Helper::isVisible('admin_user_profile_page')) {
			return;
		}

		$error = (new MWPLG_Validate_Service)->validate();

		if (!empty($error)) {
			$errors->add('coordinates', $error);
		}
	}

	public function display_map_in_edit_user_profile($user)
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('admin_user_profile_page')) {
			return;
		}

		$mapService = new MWPLG_Map_Service;
		$mapService->show_profile_map($user);
		$mapService->show_map_fields();
	}

	public function include_latitude_longitude_coordinates_to_customor_profile_fields(array $fields): array
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('admin_user_profile_page')) {
			return $fields;
		}

		$load_address = MWPLG_Settings_Helper::get_map_display_location();

		$fields[$load_address]['fields']['billing_latitude_longitude'] = array(
			'label' => __('Location', 'maps-woocommerce'),
			'type' => 'button',
			'text' => __('Location', 'maps-woocommerce'),
			'description' => __('Drag the marker to choose user location.', 'maps-woocommerce'),
		);

		return $fields;
	}

	public function validate_checkout_latitude_longitude_coordinates(array $data, WP_Error $errors): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('checkout_page')) {
			return;
		}

		$error = (new MWPLG_Validate_Service)->validate();

		if (!empty($error)) {
			$errors->add('validation', $error);
		}
	}

	public function validate_saved_address_latitude_longitude_coordinates($user_id, $load_address): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('address_account_page')) {
			return;
		}

		if ($load_address !== MWPLG_Settings_Helper::get_map_display_location()) {
			return;
		}

		$error = (new MWPLG_Validate_Service)->validate();
		
		if (!empty($error)) {
			wc_add_notice($error, 'error');
			return;
		}

		(new MWPLG_Map_Service)->update_user_coordinates(intval($user_id));
	}

	public function display_map_in_order_details_after_details($order): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('order_details_page')) {
			return;
		}

		$mapService = new MWPLG_Map_Service;
		$mapService->show_order_details_map($order);
	}

	public function save_latitude_longitude_coordinates_after_create_new_order(int|string $order_id): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('checkout_page')) {
			return;
		}

		$mapService = new MWPLG_Map_Service;
		if ($mapService->latitude_longitude_data_exist()) {
			$mapService->store_order_coordinates(intval($order_id), intval(get_current_user_id()));
		}
	}


	public function include_checkout_latitude_longitude_fields_to_billing(): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('checkout_page')) {
			return;
		}

		if (MWPLG_Settings_Helper::display_address_location_is('billing')) {
			$mapService = new MWPLG_Map_Service;
			$mapService->show_map();
			$mapService->show_map_fields();
		}
	}

	public function include_checkout_latitude_longitude_fields_to_shipping(): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('checkout_page')) {
			return;
		}

		if (MWPLG_Settings_Helper::display_address_location_is('shipping')) {
			$mapService = new MWPLG_Map_Service;
			$mapService->show_map();
			$mapService->show_map_fields();
		}
	}

	public function include_address_account_page_latitude_longitude_fields_to_billing(): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('address_account_page')) {
			return;
		}

		if (MWPLG_Settings_Helper::display_address_location_is('billing')) {
			$mapService = new MWPLG_Map_Service;
			$mapService->show_map();
			$mapService->show_map_fields();
		}
	}

	public function include_address_account_page_latitude_longitude_fields_to_shipping(): void
	{
		if (!MWPLG_Map_Visibility_Helper::isVisible('address_account_page')) {
			return;
		}

		if (MWPLG_Settings_Helper::display_address_location_is('shipping')) {
			$mapService = new MWPLG_Map_Service;
			$mapService->show_map();
			$mapService->show_map_fields();
		}
	}
}
