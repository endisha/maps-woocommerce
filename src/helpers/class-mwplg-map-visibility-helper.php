<?php

/**
 * Map visibility helper class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Map_Visibility_Helper
{
    const PREFIX_KEY = 'mwplg_maps_woocommerce_visibility_key';

    /**
     * Checks if a specific key is visible.
     *
     * @param string $key The key to check visibility for.
     * @return bool Returns true if the key is visible, false otherwise.
     */
    public static function isVisible(string $key): bool
    {
        $data = get_option(self::PREFIX_KEY, []);
        return wp_validate_boolean($data[$key] ?? false);
    }

    /**
     * Toggles the visibility of a map.
     *
     * @param string $key The key of the map to toggle.
     * @param bool $value The new visibility value for the map.
     * @return void
     */
    public static function toggleMapVisibility(string $key, bool $value): void
    {
        $data = get_option(self::PREFIX_KEY, []);
        $data[$key] = $value;
        update_option(self::PREFIX_KEY, $data);
    }
}
