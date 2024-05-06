<?php

/**
 * Map service class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Map_Service
{
    public function get_map_js_data()
    {
        $coordinates = $this->get_latlng_for_map();

        $latitude = $coordinates['latitude'];
        $longitude = $coordinates['longitude'];

        $auto_detect = !is_admin() && MWPLG_Settings_Helper::auto_user_detect_location() && $latitude == 0 && $longitude == 0;
        $use_default = MWPLG_Settings_Helper::use_default_coordinates() && $latitude == 0 && $longitude == 0;

        if ($use_default) {
            $latitude = floatval(get_option('mwplg_maps_woocommerce_map_default_latitude', '0'));
            $longitude = floatval(get_option('mwplg_maps_woocommerce_map_default_longitude', '0'));
        }

        return apply_filters('maps_woocommerce_enqueue_js_data', array(
            'markers_url' => MWPLG_Global_Helper::get_local_map_markers_base_url(),
            'address_label' => __('Address', 'maps-woocommerce'),
            'latitude_key' => MWPLG_Global_Helper::get_coordinates_address_key('latitude'),
            'longitude_key' => MWPLG_Global_Helper::get_coordinates_address_key('longitude'),
            'auto_detect' => $auto_detect,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'get_my_location_button' => __('Get My Location', 'maps-woocommerce'),
            'location_access_denied_message' => __('Location access denied. To use this feature, please enable location services in your browser settings.', 'maps-woocommerce'),
            'default_zoom' => MWPLG_Settings_Helper::get_google_maps_zoom_level(),
            'marker' => MWPLG_Settings_Helper::get_google_maps_marker(),
            'custom_marker_image' => MWPLG_Settings_Helper::get_google_maps_custom_marker_image(),
            'style' => (array) $this->load_custom_map_style(),
        ));
    }

    public function get_admin_preview_js_data()
    {
        return apply_filters('maps_woocommerce_enqueue_admin_js_data', array(
            'markers_url' => MWPLG_Global_Helper::get_local_map_markers_base_url(),
            'address_label' => __('Address', 'maps-woocommerce'),
            'style' => (array) $this->load_custom_map_style(),
            'style_name' => MWPLG_Settings_Helper::get_google_maps_style_name(),
            'style_path' => MWPLG_MAPS_WOOCOMMERCE_ASSETS_JSON_URL,
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('maps_woocommerce_nonce')
        ));
    }

    public function get_map_style_json(string $style_name): array
    {
        $styles = array();
        $style = MWPLG_Settings_Helper::get_google_maps_style_file($style_name);
        if (!empty($style)) {
            $styles = MWPLG_Global_Helper::get_custom_map_style_json_file($style);
        }
        return $styles;
    }

    public function get_order_coordinates(int $id): array
    {
        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        $latitude = sanitize_text_field(get_post_meta($id, $latitude_key, true));
        $longitude = sanitize_text_field(get_post_meta($id, $longitude_key, true));

        return [
            'lat' => floatval($latitude),
            'lng' => floatval($longitude)
        ];
    }

    public function get_user_coordinates(int $id): array
    {
        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        $latitude = sanitize_text_field(get_user_meta($id, $latitude_key, true));
        $longitude = sanitize_text_field(get_user_meta($id, $longitude_key, true));

        return [
            'lat' => floatval($latitude),
            'lng' => floatval($longitude)
        ];
    }

    public function update_user_coordinates(int $user_id): void
    {
        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        $latitude = sanitize_text_field($_POST[$latitude_key]);
        $longitude = sanitize_text_field($_POST[$longitude_key]);

        if ($latitude > 0 && $longitude > 0) {
            update_user_meta($user_id, $latitude_key, $latitude);
            update_user_meta($user_id, $longitude_key, $longitude);
        }
    }

    public function store_order_coordinates(int $order_id, int $user_id): void
    {
        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        $latitude = sanitize_text_field($_POST[$latitude_key]);
        $longitude = sanitize_text_field($_POST[$longitude_key]);

        update_post_meta($order_id, $latitude_key, $latitude);
        update_post_meta($order_id, $longitude_key, $longitude);

        if ($user_id > 0 && $latitude > 0 && $longitude > 0) {
            update_user_meta($user_id, $latitude_key, $latitude);
            update_user_meta($user_id, $longitude_key, $longitude);
        }
    }

    public function latitude_longitude_data_exist(): bool
    {
        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        $required = MWPLG_Settings_Helper::is_location_map_required();

        $found = false;

        if (
            $required &&
            isset($_POST[$latitude_key]) && is_numeric($_POST[$latitude_key]) && $_POST[$latitude_key] != 0 &&
            isset($_POST[$longitude_key]) && is_numeric($_POST[$longitude_key]) && $_POST[$longitude_key] != 0
        ) {
            $found = true;
        } elseif (
            !$required &&
            isset($_POST[$latitude_key]) && is_numeric($_POST[$latitude_key]) &&
            isset($_POST[$longitude_key]) && is_numeric($_POST[$longitude_key])
        ) {
            $found = true;
        }
        return $found;
    }

    public function validate_latitude_longitude_data(): string
    {

        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        $latitude = sanitize_text_field($_POST[$latitude_key] ?? 0);
        $longitude = sanitize_text_field($_POST[$longitude_key] ?? 0);

        return $this->validate_latitude_longitude(floatval($latitude), floatval($longitude));
    }

    public function validate_latitude_longitude(float $latitude, float $longitude): string
    {
        $valid_latitude_range = array(-90, 90);
        $valid_longitude_range = array(-180, 180);
        if (
            ($latitude < $valid_latitude_range[0] || $latitude > $valid_latitude_range[1]) ||
            ($longitude < $valid_longitude_range[0] || $longitude > $valid_longitude_range[1])
        ) {
            return __('The coordinates you entered are invalid. Please ensure they are within the valid range and try again.', 'maps-woocommerce');
        }

        return '';
    }

    public function show_map_fields()
    {
        $latitude_key = MWPLG_Global_Helper::get_coordinates_address_key('latitude');
        $longitude_key = MWPLG_Global_Helper::get_coordinates_address_key('longitude');

        MWPLG_Global_Helper::get_view('shared/map-fields', ['latitude_key' => $latitude_key, 'longitude_key' => $longitude_key]);
    }

    public function show_map()
    {
        $key = sprintf('%s_map',  MWPLG_Settings_Helper::get_map_display_location());
        $required = MWPLG_Settings_Helper::is_location_map_required();

        MWPLG_Global_Helper::get_view('frontend/checkout-map', ['key' => $key, 'required' => $required]);
    }

    public function show_admin_order_map($post): void
    {
        $coordinates = $this->get_order_coordinates(intval($post->ID));
        $exist = $coordinates['lat'] && $coordinates['lng'];

        MWPLG_Global_Helper::get_view('admin/order-map', ['exist' => $exist]);
    }


    public function show_order_details_map($order): void
    {
        $coordinates = $this->get_order_coordinates(intval($order->get_id()));
        $exist = $coordinates['lat'] && $coordinates['lng'];

        MWPLG_Global_Helper::get_view('frontend/order-details-map', ['exist' => $exist]);
    }

    public function show_profile_map($user): void
    {
        $properties = array('width' => '50%', 'display' => 'none');
        MWPLG_Global_Helper::get_view('admin/order-map', ['exist' => true, 'properties' => $properties]);
    }

    public function show_preview_map()
    {
        $preview = MWPLG_Settings_Helper::is_valid_google_maps_api_key();

        MWPLG_Global_Helper::get_view('admin/preview-map', ['preview' => $preview]);
    }

    public function show_custom_marker_input()
    {
        $selected = get_option('mwplg_maps_woocommerce_map_marker', '') == 'custom';

        MWPLG_Global_Helper::get_view('admin/custom-marker-field', ['selected' => $selected]);
    }

    public function visibility_settings_fields()
    {
        $visiblity_fields = apply_filters('maps_woocommerce_visibility_settings_fields', MWPLG_Global_Helper::get_config_file('visibility-settings-fields'));

        MWPLG_Global_Helper::get_view('admin/visibility-settings-fields', ['visiblity_fields' => $visiblity_fields]);
    }

    protected function load_custom_map_style()
    {
        $style = MWPLG_Settings_Helper::get_google_maps_style_file();
        return MWPLG_Global_Helper::get_custom_map_style_json_file($style);
    }

    protected function get_latlng_for_map(): array
    {
        $id = 0;

        if (MWPLG_Global_Helper::should_enqueue_order_scripts()) {
            if (get_query_var('order-received', 0) > 0) {
                $id = get_query_var('order-received', 0);
            } elseif (get_query_var('view-order', 0) > 0) {
                $id = get_query_var('view-order', 0);
            } elseif (intval($_GET['order_id'] ?? 0) > 0) {
                $id = intval($_GET['order_id'] ?? 0);
            }
            $coordinates = $this->get_order_coordinates($id);
        } elseif (MWPLG_Global_Helper::should_enqueue_admin_scripts()) {
            $id = intval($_GET['post']);
            $coordinates = $this->get_order_coordinates($id);
        } elseif (MWPLG_Global_Helper::should_enqueue_admin_user_scripts()) {
            if (isset($_GET['user_id'])) {
                $id = intval($_GET['user_id']);
            } else {
                $id = get_current_user_id();
            }
            $coordinates = $this->get_user_coordinates(intval($id));
        } elseif (MWPLG_Global_Helper::should_enqueue_scripts()) {
            $id = get_current_user_id();
            $coordinates = $this->get_user_coordinates(intval($id));
        }

        $latitude = $id > 0 ? $coordinates['lat'] : 0;
        $longitude = $id > 0 ? $coordinates['lng'] : 0;

        return ['latitude' => $latitude, 'longitude' => $longitude];
    }
}
