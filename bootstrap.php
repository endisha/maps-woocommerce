<?php

/**
 * Bootstrap file
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

require_once MWPLG_MAPS_WOOCOMMERCE_AUTOLOADER;

$autoloader = new MWPLG_Autoloader;
$autoloader->boot();

$app = new MWPLG_Application;
$app->boot();
