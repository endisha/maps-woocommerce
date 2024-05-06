<?php

/**
 * Settings helper class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Settings_Helper
{
    /**
     * Check whether the auto-detect option is enabled.
     *
     * @return boolean
     */
    public static function auto_user_detect_location(): bool
    {
        return get_option('mwplg_maps_woocommerce_auto_detect', 'no') == 'yes';
    }

    /**
     * Retrieves the display location of the map for WooCommerce.
     *
     * @return string The display location of the map. Defaults to 'billing'.
     */
    public static function get_map_display_location(): string
    {
        return sanitize_text_field(get_option('mwplg_maps_woocommerce_map_display_location', 'billing'));
    }

    /**
     * Check if the display address location is equal to the given address.
     *
     * @param mixed $address The address to compare.
     * @return bool Returns true if the display address location is equal to the given address, false otherwise.
     */
    public static function display_address_location_is(string $address): bool
    {
        return self::get_map_display_location() === $address;
    }

    /**
     * Returns whether the location map is required.
     *
     * @return bool The result of the validation.
     */
    public static function is_location_map_required(): bool
    {
        return get_option('mwplg_maps_woocommerce_force_enter_location', 'no') === 'yes';
    }

    /**
     * Retrieves the file path of the Google Maps style based on the selected style option.
     *
     * @return string The file path of the Google Maps style.
     */
    public static function get_google_maps_style_file($style = ''): string
    {
        $style = $style ? $style : self::get_google_maps_style_name();
        $style_file = apply_filters('maps_woocommerce_map_style_file', MWPLG_MAPS_WOOCOMMERCE_ASSETS_JSON_PATH . '/' . $style . '.json', $style);
        return realpath($style_file);
    }

    /**
     * Retrieves the name of the Google Maps style name.
     *
     * @return string The name of the Google Maps style.
     */
    public static function get_google_maps_style_name(): string
    {
        return sanitize_text_field(get_option('mwplg_maps_woocommerce_map_style', 'standard'));
    }

    /**
     * Retrieves the Google Maps marker for the WooCommerce map.
     *
     * @return string The Google Maps marker.
     */
    public static function get_google_maps_marker(): string
    {
        return sanitize_text_field(get_option('mwplg_maps_woocommerce_map_marker', 'default'));
    }

    /**
     * Retrieves the custom marker image for Google Maps.
     *
     * @return string The URL of the custom marker image.
     */
    public static function get_google_maps_custom_marker_image(): string
    {
        return esc_url_raw(get_option('mwplg_maps_woocommerce_map_marker_custom_image', ''));
    }

    /**
     * Retrieves the zoom level for the Google Maps.
     *
     * @return int The zoom level.
     */
    public static function get_google_maps_zoom_level(): int
    {
        return absint(get_option('mwplg_maps_woocommerce_map_zoom', '15'));
    }

    /**
     * Determines if the default map should use default coordinates.
     *
     * @return bool Returns true if the default map should use default coordinates, false otherwise.
     */
    public static function use_default_coordinates(): bool
    {
        return get_option('mwplg_maps_woocommerce_map_use_default_coordinates', 'no') == 'yes';
    }

    /**
     * Retrieves the URL segment for the Google Maps API.
     *
     * @return string The URL segment for the Google Maps API.
     */
    public static function get_google_maps_api_url_segment(): string
    {
        $api_key = self::get_google_maps_api_key();
        $locale = sanitize_text_field(substr(get_locale(), 0, 2));

        return build_query(array(
            'key' => $api_key,
            'language' => $locale,
            'callback' => 'initializeMap'
        ));
    }

    /**
     * Retrieves the Google Maps API key for the WooCommerce Maps integration.
     *
     * @return string The Google Maps API key.
     */
    public static function get_google_maps_api_key(): string
    {
        return sanitize_text_field(get_option('mwplg_maps_woocommerce_google_maps_api_key', ''));
    }

    /**
     * Checks if the provided Google Maps API key is valid.
     *
     * @return bool Returns true if the API key is valid, false otherwise.
     */
    public static function is_valid_google_maps_api_key(): bool
    {
        return get_option('mwplg_maps_woocommerce_google_maps_valid_api_key', 'no') == 'yes';
    }

    /**
     * Sets the validity status of the Google Maps API key.
     *
     * @param bool $valid The validity status of the Google Maps API key.
     * @return void
     */
    public static function set_as_valid_google_maps_api_key(bool $valid): void
    {
        $value = $valid ? 'yes' : 'no';
        update_option('mwplg_maps_woocommerce_google_maps_valid_api_key', $value);
    }

    /**
     * Check if the provided Google Maps API key is valid remotely.
     *
     * @param string $api_key The Google Maps API key to check.
     * @return bool Returns true if the API key is valid, false otherwise.
     */
    public static function allow_remote_check_google_maps_api_key(string $api_key): bool
    {
        return self::get_google_maps_api_key() != $api_key || !self::is_valid_google_maps_api_key();
    }

    /**
     * Retrieves the Google Maps API URL.
     *
     * @return string The Google Maps API URL.
     */
    public static function get_google_maps_api_url(): string
    {
        return sprintf('https://maps.googleapis.com/maps/api/js?%s', self::get_google_maps_api_url_segment());
    }
}
