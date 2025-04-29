<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://henjii.com
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks for
 * enqueuing the admin-specific stylesheet and JavaScript.
 *
 * @package    Henjii
 * @subpackage Henjii/admin
 * @author     Jeremy Harris <info@henjii.com>
 */
class Henjii_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        // Register AJAX handler
        add_action('wp_ajax_henjii_generate_api_key', array($this, 'ajax_generate_api_key'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/henjii-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-dark', plugin_dir_url(__FILE__) . 'css/henjii-admin-dark.css', array(), $this->version, 'all');
        
        // Add inline CSS to handle theme switching
        $inline_css = '
            body.henjii-dark-theme .henjii-admin * {
                color-scheme: dark;
            }
            body.henjii-dark-theme #wpcontent {
                background-color: #121212;
            }
        ';
        wp_add_inline_style($this->plugin_name . '-dark', $inline_css);
        // Always add the dark theme class to the body
        add_filter('admin_body_class', function($classes) { return "$classes henjii-dark-theme"; });
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/henjii-admin.js', array('jquery'), $this->version, false);
        
        // Localize the script with API base URL
        wp_localize_script($this->plugin_name, 'henjiiAdmin', array(
            'apiBaseUrl' => rest_url('henjii/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'ajaxUrl' => admin_url('admin-ajax.php')
        ));
    }

    /**
     * Register the administration menu for this plugin.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        // Main menu item
        add_menu_page(
            'Henjii MCP Server', 
            'Henjii MCP', 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_admin_dashboard'), 
            'dashicons-rest-api', 
            30
        );

        // Dashboard submenu
        add_submenu_page(
            $this->plugin_name, 
            'Dashboard', 
            'Dashboard', 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_admin_dashboard')
        );

        // Settings submenu
        add_submenu_page(
            $this->plugin_name, 
            'Settings', 
            'Settings', 
            'manage_options', 
            $this->plugin_name . '-settings', 
            array($this, 'display_plugin_admin_settings')
        );

        // Documentation submenu
        add_submenu_page(
            $this->plugin_name, 
            'Documentation', 
            'Documentation', 
            'manage_options', 
            $this->plugin_name . '-documentation', 
            array($this, 'display_plugin_admin_documentation')
        );

        // Connection Info submenu
        add_submenu_page(
            $this->plugin_name, 
            'Connection Info', 
            'Connection Info', 
            'manage_options', 
            $this->plugin_name . '-connection-info', 
            array($this, 'display_plugin_admin_connection_info')
        );
        
        // API Testing submenu
        add_submenu_page(
            $this->plugin_name, 
            'API Testing', 
            'API Testing', 
            'manage_options', 
            $this->plugin_name . '-api-testing', 
            array($this, 'display_plugin_admin_api_testing')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links) {
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name . '-settings') . '">' . __('Settings', 'henjii') . '</a>',
        );
        return array_merge($settings_link, $links);
    }

    /**
     * Render the dashboard page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_dashboard() {
        include_once('partials/henjii-admin-dashboard.php');
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_settings() {
        include_once('partials/henjii-admin-settings.php');
    }

    /**
     * Render the documentation page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_documentation() {
        include_once('partials/henjii-admin-documentation.php');
    }

    /**
     * Render the connection info page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_connection_info() {
        include_once('partials/henjii-admin-connection-info.php');
    }
    
    /**
     * Render the API testing page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_api_testing() {
        include_once('partials/henjii-admin-api-testing.php');
    }

    /**
     * Register plugin settings
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register settings
        register_setting($this->plugin_name, 'henjii_settings');
        
        // Default settings if not set
        if (false === get_option('henjii_settings')) {
            $default_settings = array(
                'api_authentication' => 'api_key',
                'rate_limiting' => 'on',
                'requests_per_minute' => 60,
                'enable_resources_endpoint' => 'on',
                'enable_tools_endpoint' => 'on',
                'enable_prompts_endpoint' => 'on',
                'enable_sampling_endpoint' => 'on',
                'content_types' => array(
                    'post' => 'on',
                    'page' => 'on',
                    'attachment' => 'on'
                ),
                'usage_tracking' => 'on',
                'dark_theme' => 'off'
            );
            
            add_option('henjii_settings', $default_settings);
        }
    }

    public function ajax_generate_api_key() {
        check_ajax_referer('wp_rest', '_ajax_nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }
        $settings = get_option('henjii_settings');
        if (!is_array($settings)) $settings = array();
        if (!isset($settings['api_keys']) || !is_array($settings['api_keys'])) {
            $settings['api_keys'] = array();
        }
        // Generate a secure random API key
        $new_key = bin2hex(random_bytes(24));
        $settings['api_keys'][] = $new_key;
        update_option('henjii_settings', $settings);
        wp_send_json_success(['api_key' => $new_key]);
    }
}
