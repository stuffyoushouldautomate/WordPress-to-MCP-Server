<?php
/**
 * Uninstall file for Henjii MCP Server
 *
 * This file is used to clean up when the plugin is uninstalled.
 *
 * @link       https://github.com/henjii
 * @since      1.0.0
 *
 * @package    henjii
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
$options = array(
    'henjii_api_enabled',
    'henjii_require_authentication',
    'henjii_api_keys',
    'henjii_rate_limit_enabled',
    'henjii_rate_limit_requests',
    'henjii_rate_limit_period',
    'henjii_log_requests',
    'henjii_allowed_endpoints',
    'henjii_content_types'
);

foreach ($options as $option) {
    delete_option($option);
}

// Drop custom tables
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}henjii_api_usage");

// Delete custom post types
$post_types = array('henjii_prompt', 'henjii_tool');
foreach ($post_types as $post_type) {
    $posts = get_posts(array(
        'post_type' => $post_type,
        'numberposts' => -1,
        'post_status' => 'any'
    ));
    
    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }
}
