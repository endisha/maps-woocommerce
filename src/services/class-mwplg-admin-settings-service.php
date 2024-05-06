<?php

/**
 * Admin settings service class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Admin_Settings_Service
{
    public function get_fields(array $settings): void
    {
        woocommerce_admin_fields($settings);
    }

    public function update_fields(array $settings): void
    {
        $validated = $this->validate_api_inputs();
        woocommerce_update_options($settings, $validated);
    }

    public function update_additional_fields(): void
    {
        // Update custom marker field
        $this->update_custom_marker_field();
        // Update default latitude and longitude fields
        $this->update_default_latitude_longitude_fields();
    }

    public function update_visiblity_fields(array $fields): void
    {
        foreach ($fields as $key => $field) {
            $option = boolval($_POST[MWPLG_Map_Visibility_Helper::PREFIX_KEY][$key] ?? false);
            MWPLG_Map_Visibility_Helper::toggleMapVisibility($key, boolval($option));
        }
    }

    protected function validate_api_inputs()
    {
        $api_key = trim(sanitize_text_field($_POST['mwplg_maps_woocommerce_google_maps_api_key'] ?? ''));

        $valid = true;
        if (empty($api_key)) {
            WC_Admin_Settings::add_error(__('Google Maps API key is missing.', 'maps-woocommerce'));
            $valid = false;
        } elseif (!$this->is_valid_api_key($api_key)) {
            WC_Admin_Settings::add_error(__('Google Maps API key is not valid.', 'maps-woocommerce'));
            $valid = false;
        }

        if (!$valid) {
            $_POST['mwplg_maps_woocommerce_google_maps_api_key'] = '';
            $_POST['mwplg_maps_woocommerce_active'] = 'no';
        }

        MWPLG_Settings_Helper::set_as_valid_google_maps_api_key($valid);

        return map_deep($_POST, 'sanitize_text_field');
    }

    protected function update_custom_marker_field(): void
    {
        if (isset($_POST['mwplg_maps_woocommerce_map_marker']) && sanitize_text_field($_POST['mwplg_maps_woocommerce_map_marker']) == 'custom') {
            if (isset($_POST['mwplg_maps_woocommerce_map_marker_custom_image'])) {
                $value = trim(esc_url_raw($_POST['mwplg_maps_woocommerce_map_marker_custom_image']));
                if (empty($value)) {
                    update_option('mwplg_maps_woocommerce_map_marker', 'default');
                    delete_option('mwplg_maps_woocommerce_map_marker_custom_image');
                } else {
                    update_option('mwplg_maps_woocommerce_map_marker_custom_image', $value);
                }
            }
        } else {
            delete_option('mwplg_maps_woocommerce_map_marker_custom_image');
        }
    }

    protected function update_default_latitude_longitude_fields(): void
    {
        if (isset($_POST['mwplg_maps_woocommerce_map_use_default_coordinates'])) {
            update_option('mwplg_maps_woocommerce_map_use_default_coordinates', 'yes');
        } else {
            delete_option('mwplg_maps_woocommerce_map_use_default_coordinates');
        }

        if (isset($_POST['mwplg_maps_woocommerce_map_default_latitude']) && isset($_POST['mwplg_maps_woocommerce_map_default_longitude'])) {
            $latitude = trim(sanitize_text_field($_POST['mwplg_maps_woocommerce_map_default_latitude']));
            $longitude = trim(sanitize_text_field($_POST['mwplg_maps_woocommerce_map_default_longitude']));
            $validate_latitude_longitude_message = (new MWPLG_Map_Service)->validate_latitude_longitude(floatval($latitude), floatval($longitude));
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                WC_Admin_Settings::add_error(__('The default latitude or longitude coordinates are invalid.', 'maps-woocommerce'));
            } elseif (!empty($validate_latitude_longitude_message)) {
                WC_Admin_Settings::add_error($validate_latitude_longitude_message);
            } else {
                update_option('mwplg_maps_woocommerce_map_default_latitude', $latitude);
                update_option('mwplg_maps_woocommerce_map_default_longitude', $longitude);
            }
        }
    }

    protected function is_valid_api_key(?string $api_key): bool
    {
        if (empty($api_key)) {
            return false;
        }
        return true;
    }

    protected function check_is_valid_api_key(?string $api_key): bool
    {
        $response = wp_remote_get("https://maps.googleapis.com/maps/api/geocode/json?address=Libya&key=$api_key");
        if (is_wp_error($response)) {
            return false;
        }
        $data = json_decode(wp_remote_retrieve_body($response));
        return isset($data->status) && $data->status === "OK";
    }
}
