<?php
/**
 * Provide an admin area view for the connection info page
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://henjii.com
 * @since      1.0.0
 *
 * @package    Henjii
 * @subpackage Henjii/admin/partials
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Get plugin settings
$settings = get_option('henjii_settings');
$site_url = get_site_url();
$api_base_url = rest_url('henjii/v1/');
?>

<div class="wrap henjii-admin">
    <div class="henjii-logo" style="color: #fff; background: #000; padding: 16px 0; border-radius: 8px;">
        <span class="dashicons dashicons-rest-api" style="color: #fff; font-size: 32px;"></span>
        <h2 style="font-family: Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; text-transform: lowercase; color: #fff; margin: 0 0 0 10px; letter-spacing: 1px;">henjii mcp</h2>
    </div>

    <div class="henjii-connection-info-header">
        <div>
            <h3>MCP Server Connection Information</h3>
            <p>Use this information to connect LLM applications to your MCP server.</p>
        </div>
    </div>

    <div class="henjii-connection-info-content">
        <div class="henjii-connection-card">
            <div class="henjii-connection-card-header">
                <h3>Server Information</h3>
            </div>
            <div class="henjii-connection-card-content">
                <div class="henjii-form-group">
                    <label class="henjii-form-label">Base URL</label>
                    <div class="henjii-connection-url">
                        <code id="base-url"><?php echo esc_url($api_base_url); ?></code>
                        <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#base-url">Copy</button>
                    </div>
                </div>

                <div class="henjii-form-group">
                    <label class="henjii-form-label">Authentication</label>
                    <?php if (isset($settings['api_authentication']) && $settings['api_authentication'] === 'api_key') : ?>
                        <p>API Key Authentication is enabled. Include your API key in the request header:</p>
                        <div class="henjii-connection-url">
                            <code id="auth-header">X-Henjii-API-Key: YOUR_API_KEY</code>
                            <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#auth-header">Copy</button>
                        </div>
                        <p>Or as a query parameter:</p>
                        <div class="henjii-connection-url">
                            <code id="auth-param"><?php echo esc_url($api_base_url); ?>?api_key=YOUR_API_KEY</code>
                            <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#auth-param">Copy</button>
                        </div>
                    <?php else : ?>
                        <p>Authentication is disabled. Your MCP server is publicly accessible.</p>
                    <?php endif; ?>
                </div>

                <?php if (isset($settings['rate_limiting']) && $settings['rate_limiting'] === 'on') : ?>
                    <div class="henjii-form-group">
                        <label class="henjii-form-label">Rate Limiting</label>
                        <p>Rate limiting is enabled. Maximum <?php echo esc_html(isset($settings['requests_per_minute']) ? $settings['requests_per_minute'] : 60); ?> requests per minute per client.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="henjii-connection-card">
            <div class="henjii-connection-card-header">
                <h3>Available Endpoints</h3>
            </div>
            <div class="henjii-connection-card-content">
                <div class="henjii-endpoints-list">
                    <?php if (isset($settings['enable_resources_endpoint']) && $settings['enable_resources_endpoint'] === 'on') : ?>
                        <div class="henjii-endpoint-item">
                            <div class="henjii-endpoint-name">
                                <h4>Resources</h4>
                                <span class="henjii-endpoint-method">GET</span>
                            </div>
                            <div class="henjii-endpoint-url">
                                <code id="resources-url"><?php echo esc_url($api_base_url . 'resources'); ?></code>
                                <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#resources-url">Copy</button>
                            </div>
                            <div class="henjii-endpoint-description">
                                Access WordPress content including posts, pages, and media.
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($settings['enable_tools_endpoint']) && $settings['enable_tools_endpoint'] === 'on') : ?>
                        <div class="henjii-endpoint-item">
                            <div class="henjii-endpoint-name">
                                <h4>Tools</h4>
                                <span class="henjii-endpoint-method">GET</span>
                            </div>
                            <div class="henjii-endpoint-url">
                                <code id="tools-url"><?php echo esc_url($api_base_url . 'tools'); ?></code>
                                <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#tools-url">Copy</button>
                            </div>
                            <div class="henjii-endpoint-description">
                                Access tools like search functionality.
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($settings['enable_prompts_endpoint']) && $settings['enable_prompts_endpoint'] === 'on') : ?>
                        <div class="henjii-endpoint-item">
                            <div class="henjii-endpoint-name">
                                <h4>Prompts</h4>
                                <span class="henjii-endpoint-method">GET</span>
                            </div>
                            <div class="henjii-endpoint-url">
                                <code id="prompts-url"><?php echo esc_url($api_base_url . 'prompts'); ?></code>
                                <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#prompts-url">Copy</button>
                            </div>
                            <div class="henjii-endpoint-description">
                                Access predefined prompts for LLM applications.
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($settings['enable_sampling_endpoint']) && $settings['enable_sampling_endpoint'] === 'on') : ?>
                        <div class="henjii-endpoint-item">
                            <div class="henjii-endpoint-name">
                                <h4>Sampling</h4>
                                <span class="henjii-endpoint-method">POST</span>
                            </div>
                            <div class="henjii-endpoint-url">
                                <code id="sampling-url"><?php echo esc_url($api_base_url . 'sampling'); ?></code>
                                <button type="button" class="henjii-button henjii-button-small henjii-copy-button" data-target="#sampling-url">Copy</button>
                            </div>
                            <div class="henjii-endpoint-description">
                                Process recursive LLM interactions.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="henjii-connection-card">
            <div class="henjii-connection-card-header">
                <h3>Integration Examples</h3>
            </div>
            <div class="henjii-connection-card-content">
                <div class="henjii-code-tabs">
                    <div class="henjii-code-tab-buttons">
                        <button type="button" class="henjii-code-tab-button active" data-target="curl-example">cURL</button>
                        <button type="button" class="henjii-code-tab-button" data-target="js-example">JavaScript</button>
                        <button type="button" class="henjii-code-tab-button" data-target="python-example">Python</button>
                    </div>

                    <div id="curl-example" class="henjii-code-tab-content active">
                        <pre><code>curl -X GET "<?php echo esc_url($api_base_url . 'resources'); ?>" \
-H "X-Henjii-API-Key: YOUR_API_KEY"</code></pre>
                    </div>

                    <div id="js-example" class="henjii-code-tab-content">
                        <pre><code>fetch('<?php echo esc_url($api_base_url . 'resources'); ?>', {
  method: 'GET',
  headers: {
    'X-Henjii-API-Key': 'YOUR_API_KEY'
  }
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));</code></pre>
                    </div>

                    <div id="python-example" class="henjii-code-tab-content">
                        <pre><code>import requests

url = "<?php echo esc_url($api_base_url . 'resources'); ?>"
headers = {
    "X-Henjii-API-Key": "YOUR_API_KEY"
}

response = requests.get(url, headers=headers)
data = response.json()
print(data)</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
