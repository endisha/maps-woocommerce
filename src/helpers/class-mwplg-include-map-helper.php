<?php

/**
 * IncludeMap helper class
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Include_Map_Helper
{
    public static function include_map($styles = array(), string $class = '', string $html = ''): void
    {
        MWPLG_Global_Helper::get_view('shared/map-short-code', ['styles' => $styles, 'class' => $class, 'html' => $html]);
    }
}
