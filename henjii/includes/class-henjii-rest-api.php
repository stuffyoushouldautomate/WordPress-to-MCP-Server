<?php
/**
 * The REST API implementation for the Henjii MCP Server.
 *
 * @link       https://henjii.com
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/includes
 */

/**
 * The REST API implementation for the Henjii MCP Server.
 *
 * This class defines all code necessary to handle the MCP REST API endpoints.
 *
 * @package    Henjii
 * @subpackage Henjii/includes
 * @author     Jeremy Harris <info@henjii.com>
 */
class Henjii_REST_API {

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
    }

    /**
     * Register the REST API routes.
     *
     * @since    1.0.0
     */
    public function register_routes() {
        register_rest_route('henjii/v1', '/test', array(
            'methods' => 'GET',
            'callback' => array($this, 'test_endpoint'),
            'permission_callback' => array($this, 'api_permissions_check'),
        ));

        // Resources endpoints
        register_rest_route('henjii/v1', '/resources', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_resources'),
            'permission_callback' => array($this, 'api_permissions_check'),
        ));

        register_rest_route('henjii/v1', '/resources/(?P<type>[a-zA-Z0-9-]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_resource_by_type'),
            'permission_callback' => array($this, 'api_permissions_check'),
            'args' => array(
                'type' => array(
                    'required' => true,
                    'validate_callback' => function($param) {
                        return in_array($param, array('post', 'page', 'media'));
                    }
                ),
                'id' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    }
                ),
                'slug' => array(
                    'validate_callback' => function($param) {
                        return is_string($param);
                    }
                ),
                'per_page' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param) && $param > 0;
                    }
                ),
                'page' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param) && $param > 0;
                    }
                )
            )
        ));

        // Tools endpoints
        register_rest_route('henjii/v1', '/tools', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_tools'),
            'permission_callback' => array($this, 'api_permissions_check'),
        ));

        register_rest_route('henjii/v1', '/tools/search', array(
            'methods' => 'POST',
            'callback' => array($this, 'search_content'),
            'permission_callback' => array($this, 'api_permissions_check'),
            'args' => array(
                'query' => array(
                    'required' => true,
                    'validate_callback' => function($param) {
                        return is_string($param) && !empty($param);
                    }
                ),
                'type' => array(
                    'validate_callback' => function($param) {
                        return is_string($param);
                    }
                ),
                'per_page' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param) && $param > 0;
                    }
                ),
                'page' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param) && $param > 0;
                    }
                )
            )
        ));

        // Prompts endpoints
        register_rest_route('henjii/v1', '/prompts', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_prompts'),
            'permission_callback' => array($this, 'api_permissions_check'),
        ));

        // Sampling endpoints
        register_rest_route('henjii/v1', '/sampling', array(
            'methods' => 'POST',
            'callback' => array($this, 'process_sampling'),
            'permission_callback' => array($this, 'api_permissions_check'),
            'args' => array(
                'prompt' => array(
                    'required' => true,
                    'validate_callback' => function($param) {
                        return is_string($param) && !empty($param);
                    }
                ),
                'max_tokens' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param) && $param > 0;
                    }
                ),
                'temperature' => array(
                    'validate_callback' => function($param) {
                        return is_numeric($param) && $param >= 0 && $param <= 1;
                    }
                )
            )
        ));
    }

    /**
     * Check if the request has valid API permissions.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   bool
     */
    public function api_permissions_check($request) {
        $settings = get_option('henjii_settings');
        
        // If API authentication is disabled, allow all requests
        if (empty($settings['api_authentication']) || $settings['api_authentication'] === 'none') {
            return true;
        }
        
        // Check API key authentication
        if ($settings['api_authentication'] === 'api_key') {
            $api_key = $request->get_header('X-Henjii-API-Key');
            
            // If no API key provided, check query parameter
            if (empty($api_key)) {
                $api_key = $request->get_param('api_key');
            }
            
            if (empty($api_key)) {
                return new WP_Error(
                    'rest_forbidden',
                    __('API key is required.', 'henjii'),
                    array('status' => 401)
                );
            }
            
            // Get stored API keys
            $api_keys = isset($settings['api_keys']) ? $settings['api_keys'] : array();
            
            if (empty($api_keys) || !in_array($api_key, $api_keys)) {
                return new WP_Error(
                    'rest_forbidden',
                    __('Invalid API key.', 'henjii'),
                    array('status' => 401)
                );
            }
            
            return true;
        }
        
        // Default to requiring authentication
        return new WP_Error(
            'rest_forbidden',
            __('Authentication is required.', 'henjii'),
            array('status' => 401)
        );
    }

    /**
     * Test endpoint for API testing feature.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function test_endpoint($request) {
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'API is working correctly',
            'version' => $this->version,
            'timestamp' => current_time('mysql'),
            'request' => array(
                'method' => $request->get_method(),
                'params' => $request->get_params(),
                'headers' => $request->get_headers()
            )
        ));
    }

    /**
     * Get all available resources.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function get_resources($request) {
        $settings = get_option('henjii_settings');
        $enabled_content_types = isset($settings['content_types']) ? $settings['content_types'] : array();
        
        $resources = array();
        
        // Add posts if enabled
        if (isset($enabled_content_types['post']) && $enabled_content_types['post'] === 'on') {
            $resources[] = array(
                'type' => 'post',
                'name' => 'Posts',
                'description' => 'Blog posts and articles',
                'endpoint' => rest_url('henjii/v1/resources/post')
            );
        }
        
        // Add pages if enabled
        if (isset($enabled_content_types['page']) && $enabled_content_types['page'] === 'on') {
            $resources[] = array(
                'type' => 'page',
                'name' => 'Pages',
                'description' => 'Static pages',
                'endpoint' => rest_url('henjii/v1/resources/page')
            );
        }
        
        // Add media if enabled
        if (isset($enabled_content_types['attachment']) && $enabled_content_types['attachment'] === 'on') {
            $resources[] = array(
                'type' => 'media',
                'name' => 'Media',
                'description' => 'Images, documents, and other media files',
                'endpoint' => rest_url('henjii/v1/resources/media')
            );
        }
        
        return rest_ensure_response(array(
            'resources' => $resources
        ));
    }

    /**
     * Get resources by type.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function get_resource_by_type($request) {
        $type = $request->get_param('type');
        $id = $request->get_param('id');
        $slug = $request->get_param('slug');
        $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 10;
        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
        
        $settings = get_option('henjii_settings');
        $enabled_content_types = isset($settings['content_types']) ? $settings['content_types'] : array();
        
        // Map resource type to WordPress post type
        $post_type_map = array(
            'post' => 'post',
            'page' => 'page',
            'media' => 'attachment'
        );
        
        $post_type = isset($post_type_map[$type]) ? $post_type_map[$type] : 'post';
        
        // Check if this content type is enabled
        $content_type_key = $post_type === 'attachment' ? 'attachment' : $post_type;
        if (!isset($enabled_content_types[$content_type_key]) || $enabled_content_types[$content_type_key] !== 'on') {
            return new WP_Error(
                'rest_forbidden',
                __('This resource type is not enabled.', 'henjii'),
                array('status' => 403)
            );
        }
        
        // If ID is provided, get a single item
        if ($id) {
            $post = get_post($id);
            
            if (!$post || $post->post_type !== $post_type) {
                return new WP_Error(
                    'rest_not_found',
                    __('Resource not found.', 'henjii'),
                    array('status' => 404)
                );
            }
            
            return rest_ensure_response($this->prepare_item_for_response($post));
        }
        
        // If slug is provided, get by slug
        if ($slug) {
            $args = array(
                'name' => $slug,
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => 1
            );
            
            $posts = get_posts($args);
            
            if (empty($posts)) {
                return new WP_Error(
                    'rest_not_found',
                    __('Resource not found.', 'henjii'),
                    array('status' => 404)
                );
            }
            
            return rest_ensure_response($this->prepare_item_for_response($posts[0]));
        }
        
        // Otherwise, get a list of items
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page
        );
        
        $query = new WP_Query($args);
        $posts = $query->posts;
        
        $items = array();
        foreach ($posts as $post) {
            $items[] = $this->prepare_item_for_response($post);
        }
        
        $response = rest_ensure_response(array(
            'items' => $items,
            'total' => $query->found_posts,
            'pages' => $query->max_num_pages
        ));
        
        return $response;
    }

    /**
     * Get available tools.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function get_tools($request) {
        $settings = get_option('henjii_settings');
        
        $tools = array();
        
        // Add search tool
        $tools[] = array(
            'id' => 'search',
            'name' => 'Search',
            'description' => 'Search for content across the site',
            'endpoint' => rest_url('henjii/v1/tools/search'),
            'method' => 'POST',
            'parameters' => array(
                'query' => array(
                    'type' => 'string',
                    'description' => 'Search query',
                    'required' => true
                ),
                'type' => array(
                    'type' => 'string',
                    'description' => 'Content type to search (post, page, media)',
                    'required' => false
                ),
                'per_page' => array(
                    'type' => 'integer',
                    'description' => 'Number of results per page',
                    'required' => false
                ),
                'page' => array(
                    'type' => 'integer',
                    'description' => 'Page number',
                    'required' => false
                )
            )
        );
        
        return rest_ensure_response(array(
            'tools' => $tools
        ));
    }

    /**
     * Search content.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function search_content($request) {
        $query = $request->get_param('query');
        $type = $request->get_param('type');
        $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 10;
        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
        
        $settings = get_option('henjii_settings');
        $enabled_content_types = isset($settings['content_types']) ? $settings['content_types'] : array();
        
        // Determine post types to search
        $post_types = array();
        
        if ($type) {
            // Map resource type to WordPress post type
            $post_type_map = array(
                'post' => 'post',
                'page' => 'page',
                'media' => 'attachment'
            );
            
            $post_type = isset($post_type_map[$type]) ? $post_type_map[$type] : 'post';
            
            // Check if this content type is enabled
            $content_type_key = $post_type === 'attachment' ? 'attachment' : $post_type;
            if (isset($enabled_content_types[$content_type_key]) && $enabled_content_types[$content_type_key] === 'on') {
                $post_types[] = $post_type;
            }
        } else {
            // Search all enabled content types
            if (isset($enabled_content_types['post']) && $enabled_content_types['post'] === 'on') {
                $post_types[] = 'post';
            }
            
            if (isset($enabled_content_types['page']) && $enabled_content_types['page'] === 'on') {
                $post_types[] = 'page';
            }
            
            if (isset($enabled_content_types['attachment']) && $enabled_content_types['attachment'] === 'on') {
                $post_types[] = 'attachment';
            }
        }
        
        if (empty($post_types)) {
            return new WP_Error(
                'rest_forbidden',
                __('No content types are enabled for search.', 'henjii'),
                array('status' => 403)
            );
        }
        
        $args = array(
            'post_type' => $post_types,
            'post_status' => 'publish',
            's' => $query,
            'posts_per_page' => $per_page,
            'paged' => $page
        );
        
        $search_query = new WP_Query($args);
        $posts = $search_query->posts;
        
        $items = array();
        foreach ($posts as $post) {
            $items[] = $this->prepare_item_for_response($post);
        }
        
        $response = rest_ensure_response(array(
            'query' => $query,
            'items' => $items,
            'total' => $search_query->found_posts,
            'pages' => $search_query->max_num_pages
        ));
        
        return $response;
    }

    /**
     * Get available prompts.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function get_prompts($request) {
        $settings = get_option('henjii_settings');
        $prompts = isset($settings['prompts']) ? $settings['prompts'] : array();
        
        if (empty($prompts)) {
            // Default prompts
            $prompts = array(
                array(
                    'id' => 'site_info',
                    'name' => 'Site Information',
                    'description' => 'Information about this website',
                    'content' => 'This is the ' . get_bloginfo('name') . ' website. ' . get_bloginfo('description')
                ),
                array(
                    'id' => 'contact_info',
                    'name' => 'Contact Information',
                    'description' => 'Contact information for this website',
                    'content' => 'You can contact us through our website contact form or by email.'
                )
            );
        }
        
        return rest_ensure_response(array(
            'prompts' => $prompts
        ));
    }

    /**
     * Process sampling request.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    Full data about the request.
     * @return   WP_REST_Response
     */
    public function process_sampling($request) {
        $prompt = $request->get_param('prompt');
        $max_tokens = $request->get_param('max_tokens') ? intval($request->get_param('max_tokens')) : 100;
        $temperature = $request->get_param('temperature') ? floatval($request->get_param('temperature')) : 0.7;
        
        // This is a placeholder implementation since we don't have an actual LLM
        // In a real implementation, this would call an external API or local model
        $response = "This is a sample response to the prompt: \"$prompt\". In a real implementation, this would be generated by an LLM. The response would be up to $max_tokens tokens long with a temperature of $temperature.";
        
        return rest_ensure_response(array(
            'prompt' => $prompt,
            'response' => $response,
            'max_tokens' => $max_tokens,
            'temperature' => $temperature
        ));
    }

    /**
     * Prepare a single item for response.
     *
     * @since    1.0.0
     * @param    WP_Post    $post    Post object.
     * @return   array
     */
    private function prepare_item_for_response($post) {
        $item = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'type' => $post->post_type,
            'date' => $post->post_date,
            'modified' => $post->post_modified,
            'url' => get_permalink($post->ID)
        );
        
        // Add content and excerpt for posts and pages
        if ($post->post_type === 'post' || $post->post_type === 'page') {
            $item['content'] = apply_filters('the_content', $post->post_content);
            $item['excerpt'] = has_excerpt($post->ID) ? get_the_excerpt($post->ID) : wp_trim_words($post->post_content, 55);
            
            // Add featured image if available
            if (has_post_thumbnail($post->ID)) {
                $featured_image_id = get_post_thumbnail_id($post->ID);
                $featured_image = wp_get_attachment_image_src($featured_image_id, 'full');
                
                if ($featured_image) {
                    $item['featured_image'] = array(
                        'id' => $featured_image_id,
                        'url' => $featured_image[0],
                        'width' => $featured_image[1],
                        'height' => $featured_image[2]
                    );
                }
            }
            
            // Add categories and tags for posts
            if ($post->post_type === 'post') {
                $categories = get_the_category($post->ID);
                $tags = get_the_tags($post->ID);
                
                $item['categories'] = array();
                if ($categories) {
                    foreach ($categories as $category) {
                        $item['categories'][] = array(
                            'id' => $category->term_id,
                            'name' => $category->name,
                            'slug' => $category->slug
                        );
                    }
                }
                
                $item['tags'] = array();
                if ($tags) {
                    foreach ($tags as $tag) {
                        $item['tags'][] = array(
                            'id' => $tag->term_id,
                            'name' => $tag->name,
                            'slug' => $tag->slug
                        );
                    }
                }
            }
        }
        
        // Add media details for attachments
        if ($post->post_type === 'attachment') {
            $attachment_url = wp_get_attachment_url($post->ID);
            $attachment_metadata = wp_get_attachment_metadata($post->ID);
            
            $item['url'] = $attachment_url;
            $item['mime_type'] = $post->post_mime_type;
            
            if (strpos($post->post_mime_type, 'image/') === 0) {
                $item['type'] = 'image';
                
                if ($attachment_metadata) {
                    $item['width'] = isset($attachment_metadata['width']) ? $attachment_metadata['width'] : null;
                    $item['height'] = isset($attachment_metadata['height']) ? $attachment_metadata['height'] : null;
                    
                    if (isset($attachment_metadata['sizes'])) {
                        $item['sizes'] = array();
                        
                        foreach ($attachment_metadata['sizes'] as $size_name => $size_data) {
                            $size_url = wp_get_attachment_image_src($post->ID, $size_name);
                            
                            if ($size_url) {
                                $item['sizes'][$size_name] = array(
                                    'url' => $size_url[0],
                                    'width' => $size_data['width'],
                                    'height' => $size_data['height']
                                );
                            }
                        }
                    }
                }
            } else {
                $item['type'] = 'file';
            }
        }
        
        return $item;
    }
}
