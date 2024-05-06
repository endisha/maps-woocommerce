<?php

/**
 * Global helper class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Global_Helper
{
    /**
     * Generates a key for storing coordinates and an address in the cache.
     *
     * @param string $key The key to be used in the cache.
     * @return string The generated key for storing coordinates and an address in the cache.
     */
    public static function get_coordinates_address_key(string $key): string
    {
        return sprintf('%s_%s', 'coordinates', $key);
    }

    /**
     * Determines if scripts should be enqueued.
     *
     * @return bool Returns true if scripts should be enqueued, false otherwise.
     */
    public static function should_enqueue_scripts()
    {
        $valid = false;
        if (
            (is_checkout() && !is_wc_endpoint_url('order-received') && MWPLG_Map_Visibility_Helper::isVisible('checkout_page')) ||
            (is_account_page() && is_wc_endpoint_url('edit-address') && MWPLG_Map_Visibility_Helper::isVisible('address_account_page'))
        ) {
            $valid = true;
        }

        return apply_filters('maps_woocommerce_validate_enqueue_js', $valid);
    }

    /**
     * Determine if order scripts should be enqueued.
     *
     * @return bool Whether or not order scripts should be enqueued.
     */
    public static function should_enqueue_order_scripts(): bool
    {
        $valid = false;
        if (is_view_order_page() && MWPLG_Map_Visibility_Helper::isVisible('order_details_page')) {
            $valid = true;
        }
        if (is_checkout() && is_wc_endpoint_url('order-received') && MWPLG_Map_Visibility_Helper::isVisible('order_details_page')) {
            $valid = true;
        }

        return apply_filters('maps_woocommerce_validate_enqueue_js', $valid);
    }

    /**
     * Determines whether to enqueue admin scripts.
     *
     * This function checks whether the user is in the admin area and on a specific screen.
     * If the conditions are met, it returns true; otherwise, it returns false.
     *
     * @return bool Returns true if admin scripts should be enqueued, false otherwise.
     */
    public static function should_enqueue_admin_scripts(): bool
    {
        $valid = false;
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen) {
                $valid = $screen->base === 'post' && $screen->post_type === 'shop_order' &&
                    MWPLG_Map_Visibility_Helper::isVisible('admin_order_details_page');
            }
        }
        return apply_filters('maps_woocommerce_validate_admin_enqueue_js', $valid);
    }

    /**
     * Determines whether to enqueue admin user scripts.
     *
     * This function checks whether the user is in the admin area and on a specific screen.
     * If the conditions are met, it returns true; otherwise, it returns false.
     *
     * @return bool Returns true if admin scripts should be enqueued, false otherwise.
     */
    public static function should_enqueue_admin_user_scripts(): bool
    {
        $valid = false;
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen) {
                $valid = in_array($screen->base, array('profile', 'user-edit')) &&
                    MWPLG_Map_Visibility_Helper::isVisible('admin_user_profile_page');
            }
        }
        return apply_filters('maps_woocommerce_validate_admin_enqueue_js', $valid);
    }

    /**
     * Determines if the current page is the plugin settings page.
     *
     * @return bool Returns true if the current page is the plugin settings page, false otherwise.
     */
    public static function is_plugin_settings_page(): bool
    {
        $valid = false;
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen) {
                $valid = in_array($screen->base, array('woocommerce_page_wc-settings')) &&
                    isset($_GET['tab']) && sanitize_key($_GET['tab']) === 'maps_woocommerce';
            }
        }
        return $valid;
    }

    /**
     * Retrieves the configuration file as an array.
     *
     * @param string $config The name of the configuration file.
     * @return array The configuration file as an array.
     */
    public static function get_config_file(string $config): array
    {
        $configs = [];
        $file = MWPLG_MAPS_WOOCOMMERCE_CONFIG_DIR . '/' . $config . '.php';
        $file = apply_filters('maps_woocommerce_config_file', $file, $config);

        if (file_exists(realpath($file))) {
            $configs = require realpath($file);
        }
        return $configs;
    }

    /**
     * Get view template file
     *
     * @param string $template
     * @param array $data
     * @return void
     */
    public static function get_view(string $template, array $data = array()): void
    {
        $file =  MWPLG_MAPS_WOOCOMMERCE_VIEWS_DIR . '/' . $template . '.php';
        $file = apply_filters('maps_woocommerce_view_file', $file, $template, $data);

        if (file_exists(realpath($file))) {
            extract($data);
            require realpath($file);
        }
    }

    /**
     * Generates the URL for a local CSS asset file.
     *
     * @param string $file The name of the CSS file.
     * @return string The full URL of the CSS asset file.
     */
    public static function load_local_css_asset_file(string $file): string
    {
        return MWPLG_MAPS_WOOCOMMERCE_ASSETS_CSS_URL . '/' . $file;
    }

    /**
     * Load a local JavaScript asset file.
     *
     * @param string $file The name of the file to load.
     * @return string The URL of the loaded asset file.
     */
    public static function load_local_js_asset_file(string $file): string
    {
        return MWPLG_MAPS_WOOCOMMERCE_ASSETS_JS_URL . '/' . $file;
    }

    /**
     * Retrieve the base URL for local map markers.
     *
     * @return string The URL for local map markers.
     */
    public static function get_local_map_markers_base_url(): string
    {
        return MWPLG_MAPS_WOOCOMMERCE_ASSETS_IMG_URL . '/markers';
    }

    /**
     * Get custom map style json file
     *
     * @param string $style_path
     * @return array
     */
    public static function get_custom_map_style_json_file($style_path = ''): array
    {
        if (!file_exists(realpath($style_path))) {
            return array();
        }
        return json_decode(file_get_contents(realpath($style_path)));
    }
}
