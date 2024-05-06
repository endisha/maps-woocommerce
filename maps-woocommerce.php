<?php

/**
 * Plugin Name: Map for WooCommerce
 * Plugin URI: https://endisha.ly/
 * Description: Integrate Google Maps with WooCommerce for easy location selection during checkout and in user account addresses to elevate the shopping experience.
 * Author: Mohamed Endisha
 * Author URI: https://endisha.ly
 * Version: 1.0.0
 * Text Domain: maps-woocommerce
 * Domain Path: /src/languages/
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package MapsWooCommerce
 * @version 1.0.0
 * @author Mohamed Endisha
 * @copyright Copyright (c) 2023.
 */
defined('ABSPATH') || exit;

// Define constants
if (!defined('MWPLG_MAPS_WOOCOMMERCE_DIR')) {
    define('MWPLG_MAPS_WOOCOMMERCE_VERSION', '1.0.0');
    define('MWPLG_MAPS_WOOCOMMERCE_FILE', __FILE__);
    define('MWPLG_MAPS_WOOCOMMERCE_DIR', __DIR__);
    define('MWPLG_MAPS_WOOCOMMERCE_DIR_BASENAME', basename(__DIR__));
    define('MWPLG_MAPS_WOOCOMMERCE_FILE_BASENAME', basename(__FILE__));
    define('MWPLG_MAPS_WOOCOMMERCE_PLUGIN_BASENAME', basename(__DIR__) . '/' . basename(__FILE__));
    define('MWPLG_MAPS_WOOCOMMERCE_SRC_DIR', __DIR__ . '/src');
    define('MWPLG_MAPS_WOOCOMMERCE_CONFIG_DIR', __DIR__ . '/configs');
    define('MWPLG_MAPS_WOOCOMMERCE_LANGUAGES_DIR', basename(__DIR__) . '/src/languages/');
    define('MWPLG_MAPS_WOOCOMMERCE_VIEWS_DIR', __DIR__ . '/src/views');
    define('MWPLG_MAPS_WOOCOMMERCE_AUTOLOADER', __DIR__ . '/src/core/class-mwplg-autoloader.php');
    define('MWPLG_MAPS_WOOCOMMERCE_ASSETS_URL', plugin_dir_url(__FILE__) . 'assets');
    define('MWPLG_MAPS_WOOCOMMERCE_ASSETS_CSS_URL', MWPLG_MAPS_WOOCOMMERCE_ASSETS_URL . '/css');
    define('MWPLG_MAPS_WOOCOMMERCE_ASSETS_IMG_URL', MWPLG_MAPS_WOOCOMMERCE_ASSETS_URL . '/img');
    define('MWPLG_MAPS_WOOCOMMERCE_ASSETS_JS_URL', MWPLG_MAPS_WOOCOMMERCE_ASSETS_URL . '/js');
    define('MWPLG_MAPS_WOOCOMMERCE_ASSETS_JSON_PATH',  __DIR__ . '/assets/json');
    define('MWPLG_MAPS_WOOCOMMERCE_ASSETS_JSON_URL', MWPLG_MAPS_WOOCOMMERCE_ASSETS_URL . '/json');
}

require_once __DIR__ . '/bootstrap.php';
