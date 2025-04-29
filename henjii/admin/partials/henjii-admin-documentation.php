<?php
/**
 * Provide an admin area view for the plugin documentation
 *
 * This file is used to markup the admin-facing documentation of the plugin.
 *
 * @link       https://github.com/henjii
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/admin/partials
 */
?>

<div class="wrap henjii-admin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="henjii-documentation">
        <div class="henjii-documentation-header">
            <div class="henjii-logo">
                <span class="dashicons dashicons-rest-api"></span>
                <h2><?php _e('Henjii MCP Server Documentation', 'henjii'); ?></h2>
            </div>
        </div>
        
        <div class="henjii-documentation-content">
            <div class="henjii-doc-section">
                <h2><?php _e('Introduction', 'henjii'); ?></h2>
                <p><?php _e('Henjii turns your WordPress site into a Model Context Protocol (MCP) server that LLM applications can easily integrate with. This documentation will help you understand how to use and configure the Henjii MCP server.', 'henjii'); ?></p>
                
                <h3><?php _e('What is MCP?', 'henjii'); ?></h3>
                <p><?php _e('The Model Context Protocol (MCP) is an open standard that enables seamless integration between LLM applications and external data sources and tools. It provides a standardized way for applications to share contextual information with language models, expose tools and capabilities to AI systems, and build composable integrations and workflows.', 'henjii'); ?></p>
                
                <h3><?php _e('Why use Henjii?', 'henjii'); ?></h3>
                <p><?php _e('Henjii makes it easy to expose your WordPress content to LLM applications through a standardized API. This allows AI models to access and interact with your content, providing more relevant and contextual responses to users.', 'henjii'); ?></p>
                <ul>
                    <li><?php _e('Expose your WordPress content to LLM applications', 'henjii'); ?></li>
                    <li><?php _e('Provide custom tools for AI models to interact with your site', 'henjii'); ?></li>
                    <li><?php _e('Create templated prompts for consistent AI interactions', 'henjii'); ?></li>
                    <li><?php _e('Enable recursive LLM interactions through sampling', 'henjii'); ?></li>
                </ul>
            </div>
            
            <div class="henjii-doc-section">
                <h2><?php _e('Getting Started', 'henjii'); ?></h2>
                
                <h3><?php _e('Configuration', 'henjii'); ?></h3>
                <p><?php _e('To get started with Henjii, you need to configure the plugin settings:', 'henjii'); ?></p>
                <ol>
                    <li><?php _e('Go to the Settings page', 'henjii'); ?></li>
                    <li><?php _e('Enable the MCP server', 'henjii'); ?></li>
                    <li><?php _e('Configure authentication settings', 'henjii'); ?></li>
                    <li><?php _e('Generate API keys for your LLM applications', 'henjii'); ?></li>
                    <li><?php _e('Configure rate limiting and logging', 'henjii'); ?></li>
                    <li><?php _e('Enable the endpoints you want to expose', 'henjii'); ?></li>
                </ol>
                
                <h3><?php _e('Connection Information', 'henjii'); ?></h3>
                <p><?php _e('Once you have configured the plugin, you can find the connection information on the Connection Info page. This includes the base URL for your MCP server and the API endpoints available.', 'henjii'); ?></p>
            </div>
            
            <div class="henjii-doc-section">
                <h2><?php _e('API Endpoints', 'henjii'); ?></h2>
                
                <h3><?php _e('Configuration Endpoint', 'henjii'); ?></h3>
                <p><?php _e('The configuration endpoint provides information about your MCP server, including the available capabilities.', 'henjii'); ?></p>
                <pre><code>GET /wp-json/henjii/v1/config</code></pre>
                
                <h3><?php _e('Resources Endpoint', 'henjii'); ?></h3>
                <p><?php _e('The resources endpoint provides access to your WordPress content.', 'henjii'); ?></p>
                <pre><code>GET /wp-json/henjii/v1/resources</code></pre>
                <p><?php _e('To get the content of a specific resource:', 'henjii'); ?></p>
                <pre><code>GET /wp-json/henjii/v1/resources/{type}/{id}</code></pre>
                
                <h3><?php _e('Tools Endpoint', 'henjii'); ?></h3>
                <p><?php _e('The tools endpoint provides access to functions that LLM applications can execute.', 'henjii'); ?></p>
                <pre><code>GET /wp-json/henjii/v1/tools</code></pre>
                <p><?php _e('To execute a specific tool:', 'henjii'); ?></p>
                <pre><code>POST /wp-json/henjii/v1/tools/{id}</code></pre>
                
                <h3><?php _e('Prompts Endpoint', 'henjii'); ?></h3>
                <p><?php _e('The prompts endpoint provides access to templated messages and workflows.', 'henjii'); ?></p>
                <pre><code>GET /wp-json/henjii/v1/prompts</code></pre>
                <p><?php _e('To get a specific prompt:', 'henjii'); ?></p>
                <pre><code>GET /wp-json/henjii/v1/prompts/{id}</code></pre>
                
                <h3><?php _e('Sampling Endpoint', 'henjii'); ?></h3>
                <p><?php _e('The sampling endpoint allows for server-initiated agentic behaviors and recursive LLM interactions.', 'henjii'); ?></p>
                <pre><code>POST /wp-json/henjii/v1/sampling</code></pre>
            </div>
            
            <div class="henjii-doc-section">
                <h2><?php _e('Authentication', 'henjii'); ?></h2>
                <p><?php _e('Henjii uses API key authentication to secure your MCP server. You can generate API keys on the Settings page.', 'henjii'); ?></p>
                <p><?php _e('To authenticate requests, include the API key in the X-Henjii-API-Key header:', 'henjii'); ?></p>
                <pre><code>X-Henjii-API-Key: your-api-key</code></pre>
            </div>
            
            <div class="henjii-doc-section">
                <h2><?php _e('Rate Limiting', 'henjii'); ?></h2>
                <p><?php _e('Henjii includes rate limiting to prevent abuse of your MCP server. You can configure the rate limit on the Settings page.', 'henjii'); ?></p>
                <p><?php _e('If a client exceeds the rate limit, they will receive a 429 Too Many Requests response.', 'henjii'); ?></p>
            </div>
            
            <div class="henjii-doc-section">
                <h2><?php _e('Logging', 'henjii'); ?></h2>
                <p><?php _e('Henjii can log all API requests for monitoring and statistics. You can enable or disable logging on the Settings page.', 'henjii'); ?></p>
                <p><?php _e('The logs include information such as the endpoint, user ID, API key, request IP, request data, response code, and response time.', 'henjii'); ?></p>
            </div>
            
            <div class="henjii-doc-section">
                <h2><?php _e('Integration Examples', 'henjii'); ?></h2>
                
                <h3><?php _e('Python Example', 'henjii'); ?></h3>
                <pre><code>import requests

# Configuration
base_url = "https://your-wordpress-site.com/wp-json/henjii/v1"
api_key = "your-api-key"
headers = {
    "X-Henjii-API-Key": api_key
}

# Get server configuration
response = requests.get(f"{base_url}/config", headers=headers)
config = response.json()
print(f"Server name: {config['name']}")
print(f"Server capabilities: {config['capabilities']}")

# Get available resources
response = requests.get(f"{base_url}/resources", headers=headers)
resources = response.json()
for resource in resources:
    print(f"Resource: {resource['name']} ({resource['type']})")

# Get content of a specific post
post_id = 1
response = requests.get(f"{base_url}/resources/post/{post_id}", headers=headers)
post = response.json()
print(f"Post title: {post['title']}")
print(f"Post content: {post['content']}")

# Execute a search tool
search_params = {
    "query": "example search",
    "post_type": "post"
}
response = requests.post(f"{base_url}/tools/search", headers=headers, json=search_params)
search_results = response.json()
for result in search_results:
    print(f"Result: {result['title']} - {result['url']}")</code></pre>
                
                <h3><?php _e('JavaScript Example', 'henjii'); ?></h3>
                <pre><code>// Configuration
const baseUrl = "https://your-wordpress-site.com/wp-json/henjii/v1";
const apiKey = "your-api-key";
const headers = {
    "X-Henjii-API-Key": apiKey,
    "Content-Type": "application/json"
};

// Get server configuration
fetch(`${baseUrl}/config`, { headers })
    .then(response => response.json())
    .then(config => {
        console.log(`Server name: ${config.name}`);
        console.log(`Server capabilities: ${JSON.stringify(config.capabilities)}`);
    });

// Get available resources
fetch(`${baseUrl}/resources`, { headers })
    .then(response => response.json())
    .then(resources => {
        resources.forEach(resource => {
            console.log(`Resource: ${resource.name} (${resource.type})`);
        });
    });

// Get content of a specific post
const postId = 1;
fetch(`${baseUrl}/resources/post/${postId}`, { headers })
    .then(response => response.json())
    .then(post => {
        console.log(`Post title: ${post.title}`);
        console.log(`Post content: ${post.content}`);
    });

// Execute a search tool
const searchParams = {
    query: "example search",
    post_type: "post"
};
fetch(`${baseUrl}/tools/search`, {
    method: "POST",
    headers,
    body: JSON.stringify(searchParams)
})
    .then(response => response.json())
    .then(searchResults => {
        searchResults.forEach(result => {
            console.log(`Result: ${result.title} - ${result.url}`);
        });
    });</code></pre>
            </div>
        </div>
    </div>
</div>
