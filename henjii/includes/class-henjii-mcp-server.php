<?php
/**
 * The MCP server implementation
 *
 * @link       https://github.com/henjii
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/includes
 */

/**
 * The MCP server implementation.
 *
 * Defines the core functionality for the Model Context Protocol server
 * that allows LLM applications to interact with WordPress content.
 *
 * @since      1.0.0
 * @package    Henjii
 * @subpackage Henjii/includes
 * @author     jeremy harris
 */
class Henjii_MCP_Server {

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
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Initialize the MCP server.
     *
     * @since    1.0.0
     */
    public function init() {
        // Only initialize if the API is enabled
        if (!get_option('henjii_api_enabled', true)) {
            return;
        }

        // Register custom post types if needed
        $this->register_post_types();
    }

    /**
     * Register custom post types for the MCP server.
     *
     * @since    1.0.0
     */
    private function register_post_types() {
        // Register custom post type for MCP prompts if needed
        register_post_type('henjii_prompt', 
            array(
                'labels' => array(
                    'name' => __('MCP Prompts', 'henjii'),
                    'singular_name' => __('MCP Prompt', 'henjii'),
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'henjii',
                'supports' => array('title', 'editor', 'custom-fields'),
                'capability_type' => 'post',
                'has_archive' => false,
            )
        );

        // Register custom post type for MCP tools if needed
        register_post_type('henjii_tool', 
            array(
                'labels' => array(
                    'name' => __('MCP Tools', 'henjii'),
                    'singular_name' => __('MCP Tool', 'henjii'),
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'henjii',
                'supports' => array('title', 'editor', 'custom-fields'),
                'capability_type' => 'post',
                'has_archive' => false,
            )
        );
    }

    /**
     * Get available resources for the MCP server.
     *
     * @since    1.0.0
     * @return   array    The available resources.
     */
    public function get_resources() {
        $resources = array();
        
        // Get enabled content types
        $content_types = get_option('henjii_content_types', array(
            'post' => true,
            'page' => true,
            'attachment' => true
        ));
        
        // Add resources based on enabled content types
        if (!empty($content_types)) {
            foreach ($content_types as $type => $enabled) {
                if ($enabled) {
                    $resources[] = array(
                        'type' => $type,
                        'name' => ucfirst($type) . 's',
                        'description' => sprintf(__('WordPress %s content', 'henjii'), $type)
                    );
                }
            }
        }
        
        return $resources;
    }

    /**
     * Get available tools for the MCP server.
     *
     * @since    1.0.0
     * @return   array    The available tools.
     */
    public function get_tools() {
        $tools = array();
        
        // Get tools from custom post type
        $tool_posts = get_posts(array(
            'post_type' => 'henjii_tool',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));
        
        if (!empty($tool_posts)) {
            foreach ($tool_posts as $tool_post) {
                $tool = array(
                    'id' => $tool_post->ID,
                    'name' => $tool_post->post_title,
                    'description' => $tool_post->post_content,
                    'parameters' => get_post_meta($tool_post->ID, 'parameters', true)
                );
                
                $tools[] = $tool;
            }
        }
        
        // Add default tools
        $tools[] = array(
            'id' => 'search',
            'name' => 'search',
            'description' => __('Search WordPress content', 'henjii'),
            'parameters' => array(
                'query' => array(
                    'type' => 'string',
                    'description' => __('Search query', 'henjii')
                ),
                'post_type' => array(
                    'type' => 'string',
                    'description' => __('Post type to search', 'henjii'),
                    'default' => 'post'
                )
            )
        );
        
        return $tools;
    }

    /**
     * Get available prompts for the MCP server.
     *
     * @since    1.0.0
     * @return   array    The available prompts.
     */
    public function get_prompts() {
        $prompts = array();
        
        // Get prompts from custom post type
        $prompt_posts = get_posts(array(
            'post_type' => 'henjii_prompt',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));
        
        if (!empty($prompt_posts)) {
            foreach ($prompt_posts as $prompt_post) {
                $prompt = array(
                    'id' => $prompt_post->ID,
                    'name' => $prompt_post->post_title,
                    'description' => $prompt_post->post_content,
                    'template' => get_post_meta($prompt_post->ID, 'template', true)
                );
                
                $prompts[] = $prompt;
            }
        }
        
        return $prompts;
    }

    /**
     * Execute a tool for the MCP server.
     *
     * @since    1.0.0
     * @param    string    $tool_id    The tool ID.
     * @param    array     $params     The tool parameters.
     * @return   mixed                 The tool execution result.
     */
    public function execute_tool($tool_id, $params) {
        // Handle default tools
        if ($tool_id === 'search') {
            return $this->execute_search_tool($params);
        }
        
        // Handle custom tools
        $tool_post = get_post($tool_id);
        if ($tool_post && $tool_post->post_type === 'henjii_tool') {
            $handler = get_post_meta($tool_post->ID, 'handler', true);
            if ($handler && function_exists($handler)) {
                return call_user_func($handler, $params);
            }
        }
        
        return new WP_Error('invalid_tool', __('Invalid tool ID', 'henjii'));
    }

    /**
     * Execute the search tool.
     *
     * @since    1.0.0
     * @param    array     $params     The tool parameters.
     * @return   array                 The search results.
     */
    private function execute_search_tool($params) {
        $query = isset($params['query']) ? sanitize_text_field($params['query']) : '';
        $post_type = isset($params['post_type']) ? sanitize_text_field($params['post_type']) : 'post';
        
        if (empty($query)) {
            return new WP_Error('invalid_params', __('Search query is required', 'henjii'));
        }
        
        $args = array(
            'post_type' => $post_type,
            's' => $query,
            'posts_per_page' => 10
        );
        
        $query = new WP_Query($args);
        $results = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $results[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'url' => get_permalink()
                );
            }
            wp_reset_postdata();
        }
        
        return $results;
    }

    /**
     * Log API usage.
     *
     * @since    1.0.0
     * @param    string    $endpoint       The API endpoint.
     * @param    int       $user_id        The user ID.
     * @param    string    $api_key        The API key.
     * @param    string    $request_ip     The request IP.
     * @param    mixed     $request_data   The request data.
     * @param    int       $response_code  The response code.
     * @param    float     $response_time  The response time.
     */
    public function log_usage($endpoint, $user_id, $api_key, $request_ip, $request_data, $response_code, $response_time) {
        // Only log if enabled
        if (!get_option('henjii_log_requests', true)) {
            return;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'henjii_api_usage';
        
        $wpdb->insert(
            $table_name,
            array(
                'endpoint' => $endpoint,
                'user_id' => $user_id,
                'api_key' => $api_key,
                'request_ip' => $request_ip,
                'request_data' => is_array($request_data) || is_object($request_data) ? json_encode($request_data) : $request_data,
                'response_code' => $response_code,
                'response_time' => $response_time
            )
        );
    }
}
