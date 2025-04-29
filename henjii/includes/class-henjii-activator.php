<?php
/**
 * Fired during plugin activation
 *
 * @link       https://github.com/henjii
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Henjii
 * @subpackage Henjii/includes
 * @author     jeremy harris
 */
class Henjii_Activator {

    /**
     * Activate the plugin.
     *
     * Create necessary database tables and set up initial options
     * for the Henjii MCP server.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Create custom database tables if needed
        self::create_tables();
        
        // Set default options
        self::set_default_options();
        
        // Flush rewrite rules to ensure our API endpoints work
        flush_rewrite_rules();
    }
    
    /**
     * Create custom database tables for the plugin.
     *
     * @since    1.0.0
     */
    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for tracking API usage
        $table_name = $wpdb->prefix . 'henjii_api_usage';
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            endpoint varchar(255) NOT NULL,
            user_id bigint(20) DEFAULT NULL,
            api_key varchar(64) DEFAULT NULL,
            request_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            request_ip varchar(100) NOT NULL,
            request_data longtext DEFAULT NULL,
            response_code smallint(6) NOT NULL,
            response_time float NOT NULL,
            PRIMARY KEY  (id),
            KEY endpoint (endpoint),
            KEY user_id (user_id),
            KEY api_key (api_key),
            KEY request_time (request_time)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Set default options for the plugin.
     *
     * @since    1.0.0
     */
    private static function set_default_options() {
        // Default MCP server settings
        $default_options = array(
            'henjii_api_enabled' => true,
            'henjii_require_authentication' => true,
            'henjii_rate_limit_enabled' => true,
            'henjii_rate_limit_requests' => 100,
            'henjii_rate_limit_period' => 'hour',
            'henjii_log_requests' => true,
            'henjii_allowed_endpoints' => array(
                'resources' => true,
                'tools' => true,
                'prompts' => true,
                'sampling' => true
            ),
            'henjii_content_types' => array(
                'post' => true,
                'page' => true,
                'attachment' => true
            )
        );
        
        foreach ($default_options as $option_name => $option_value) {
            if (get_option($option_name) === false) {
                add_option($option_name, $option_value);
            }
        }
    }
}
