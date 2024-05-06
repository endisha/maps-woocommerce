<?php

/**
 * Application
 *
 * @package MapsWooCommerce
 */

defined('ABSPATH') || exit;

class MWPLG_Application
{

    /**
     * Boot the plugin.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->dependencies_enabled()) {
            register_activation_hook(MWPLG_MAPS_WOOCOMMERCE_FILE, [$this, 'register_default_data']);
            add_action('activated_plugin', [$this, 'activation']);
            add_filter('plugin_action_links', [$this, 'plugin_action_settings'], 10, 2);
            $this->load_actions_filters();
            add_action('plugins_loaded', [$this, 'load_i18n']);
        } else {
            $this->required_dependencies_notice();
        }
    }

    /**
     * Load actions and filters for the plugin.
     *
     * @return void
     */
    protected function load_actions_filters(): void
    {
        if ($this->can_be_booted()) {
            new MWPLG_Frontend;
            new MWPLG_Hooks;
        }
        new MWPLG_Ajax;
        new MWPLG_Adminarea;
    }

    /**
     * Load internationalization (i18n) languages for the plugin.
     *
     * @return void
     */
    public function load_i18n(): void
    {
        load_plugin_textdomain('maps-woocommerce', false, MWPLG_MAPS_WOOCOMMERCE_LANGUAGES_DIR);
    }

    /**
     * Activation
     *
     * @param string $plugin
     * @return void
     */
    public function activation(string $plugin): void
    {
        if ($this->is_plugin($plugin)) {
            wp_safe_redirect($this->get_plugin_settings_url());
            exit;
        }
    }

    /**
     * Plugin action settings
     *
     * @param array $links
     * @param string $plugin
     * @return array
     */
    public function plugin_action_settings(array $links, string $plugin): array
    {
        if ($this->is_plugin($plugin)) {
            $links[] = sprintf('<a href="%s">%s</a>', $this->get_plugin_settings_url(), esc_html__('Settings', 'maps-woocommerce'));
        }
        return $links;
    }

    /**
     * Register default data for the plugin's settings.
     *
     * @return void
     */
    public function register_default_data()
    {
        $options = array(
            'active' => 'no',
            'map_display_location' => 'billing',
            'google_maps_valid_api_key' => 'no',
            'auto_detect' => 'yes',
            'force_enter_location' => 'no',
            'map_zoom' => '15',
            'map_style' => 'standard',
            'map_marker' => 'default',
            'use_default_coordinates' => 'no',
            'map_default_latitude' => '0',
            'map_default_longitude' => '0',
            'visibility_key' => [
                'checkout_page' => true,
                'address_account_page' => true,
                'order_details_page' => true,
                'admin_order_details_page' => true,
                'admin_user_profile_page' => true,
            ],
        );
        foreach ($options as $key => $value) {
            $option = sprintf('mwplg_maps_woocommerce_%s', $key);
            if (get_option($option) === false) {
                update_option($option, $value);
            }
        }
    }

    /**
     * Show required dependencies notice
     *
     * @return void
     */
    protected function required_dependencies_notice(): void
    {
        add_action('admin_notices', function () {
            $plugin_name = '<strong>' . __('Maps for WooCommerce', 'maps-woocommerce') . '</strong>';
            $dependency_plugin = '<a href="https://wordpress.org/plugins/woocommerce" target="_blank">WooCommerce</a>';
            ?>
            <div class="notice notice-error">
                <p>
                    <?php echo wp_kses(
                        sprintf(
                            /* translators: %1$s is the plugin name, %2$s is the dependency plugin name */
                            __('%1$s requires %2$s to be activated.', 'intl-phone-number-format'),
                            $plugin_name,
                            $dependency_plugin
                        ),
                        array('a' => array('href' => array(), 'title' => array(), 'target' => array()), 'strong' => array())
                    );
                    ?>
                </p>
            </div>
        <?php
        });
    }

    /**
     * Dependencies are enabled
     *
     * @return boolean
     */
    protected function dependencies_enabled()
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        return is_plugin_active('woocommerce/woocommerce.php');
    }

    /**
     * Can be booted?
     *
     * @return boolean
     */
    protected function can_be_booted()
    {
        return get_option('mwplg_maps_woocommerce_active', 'no') == 'yes' &&
            get_option('mwplg_maps_woocommerce_google_maps_api_key', '') !== '' &&
            get_option('mwplg_maps_woocommerce_google_maps_valid_api_key', 'no') == 'yes';
    }

    /**
     * Get the plugin settings URL
     *
     * @return string
     */
    protected function get_plugin_settings_url(): string
    {
        return admin_url('admin.php?' . http_build_query([
            'page' => 'wc-settings',
            'tab' => 'maps_woocommerce',
        ]));
    }

    /**
     * Check if the provided plugin is MW plugin
     *
     * @param string $plugin
     * @return boolean
     */
    protected function is_plugin(string $plugin): bool
    {
        return $plugin == MWPLG_MAPS_WOOCOMMERCE_PLUGIN_BASENAME;
    }
}
