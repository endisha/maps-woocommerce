<?php

/**
 * Validate service class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Validate_Service
{
    public function validate(): string
    {
        $error = '';

        $mapService = new MWPLG_Map_Service;
        $required = MWPLG_Settings_Helper::is_location_map_required();
        $exist = $mapService->latitude_longitude_data_exist();
        $validation_message = $mapService->validate_latitude_longitude_data();

        if ($required && !$exist) {
            $error = __('The map location is missing.', 'maps-woocommerce');
        } elseif ($exist && !empty($validation_message)) {
            $error = $validation_message;
        }

        return $error;
    }
}
