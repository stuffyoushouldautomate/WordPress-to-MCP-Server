<?php
/**
 * Provide an admin area view for the settings page
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
?>

<div class="wrap henjii-admin">
    <div class="henjii-logo" style="color: #fff; background: #000; padding: 16px 0; border-radius: 8px;">
        <span class="dashicons dashicons-rest-api" style="color: #fff; font-size: 32px;"></span>
        <h2 style="font-family: Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; text-transform: lowercase; color: #fff; margin: 0 0 0 10px; letter-spacing: 1px;">henjii mcp</h2>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields('henjii');
        ?>

        <div class="henjii-tabs">
            <div class="henjii-tab active" data-target="general-settings">General</div>
            <div class="henjii-tab" data-target="api-settings">API</div>
            <div class="henjii-tab" data-target="endpoint-settings">Endpoints</div>
            <div class="henjii-tab" data-target="content-settings">Content</div>
            <div class="henjii-tab" data-target="advanced-settings">Advanced</div>
        </div>

        <div class="henjii-settings-container">
            <!-- General Settings -->
            <div id="general-settings" class="henjii-tab-content active">
                <div class="henjii-settings-section">
                    <h2>General Settings</h2>
                    <p>Configure the basic settings for your MCP server.</p>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[usage_tracking]" <?php checked(isset($settings['usage_tracking']) && $settings['usage_tracking'] === 'on'); ?>>
                            Enable Usage Tracking
                        </label>
                        <p class="description">Track API usage statistics for your dashboard.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[dark_theme]" <?php checked(isset($settings['dark_theme']) && $settings['dark_theme'] === 'on'); ?>>
                            Use Dark Theme by Default
                        </label>
                        <p class="description">Set dark theme as the default for admin pages.</p>
                    </div>
                </div>
            </div>

            <!-- API Settings -->
            <div id="api-settings" class="henjii-tab-content">
                <div class="henjii-settings-section">
                    <h2>API Authentication</h2>
                    <p>Configure how clients authenticate with your MCP server.</p>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">Authentication Method</label>
                        <select name="henjii_settings[api_authentication]" class="henjii-form-select">
                            <option value="none" <?php selected(isset($settings['api_authentication']) && $settings['api_authentication'] === 'none'); ?>>None (Public Access)</option>
                            <option value="api_key" <?php selected(isset($settings['api_authentication']) && $settings['api_authentication'] === 'api_key'); ?>>API Key</option>
                        </select>
                        <p class="description">Choose how clients will authenticate with your MCP server.</p>
                    </div>

                    <div class="henjii-api-keys">
                        <h3>API Keys</h3>
                        <p>Create and manage API keys for client authentication.</p>

                        <?php
                        $api_keys = isset($settings['api_keys']) ? $settings['api_keys'] : array();
                        if (!empty($api_keys)) {
                            foreach ($api_keys as $index => $api_key) {
                                ?>
                                <div class="henjii-api-key-row">
                                    <input type="text" name="henjii_settings[api_keys][]" value="<?php echo esc_attr($api_key); ?>" class="henjii-form-input" readonly>
                                    <button type="button" class="henjii-button henjii-button-secondary henjii-copy-button" data-target="#api-key-<?php echo esc_attr($index); ?>">Copy</button>
                                    <button type="button" class="henjii-button henjii-button-secondary henjii-delete-api-key">Delete</button>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p>No API keys created yet.</p>';
                        }
                        ?>

                        <div class="henjii-api-key-actions">
                            <button type="button" class="henjii-button henjii-generate-api-key">Generate New API Key</button>
                        </div>
                    </div>
                </div>

                <div class="henjii-settings-section">
                    <h2>Rate Limiting</h2>
                    <p>Configure rate limiting to prevent abuse of your MCP server.</p>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[rate_limiting]" <?php checked(isset($settings['rate_limiting']) && $settings['rate_limiting'] === 'on'); ?>>
                            Enable Rate Limiting
                        </label>
                        <p class="description">Limit the number of requests clients can make to your MCP server.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">Requests per Minute</label>
                        <input type="number" name="henjii_settings[requests_per_minute]" value="<?php echo esc_attr(isset($settings['requests_per_minute']) ? $settings['requests_per_minute'] : 60); ?>" min="1" max="1000" class="henjii-form-input">
                        <p class="description">Maximum number of requests allowed per minute per client.</p>
                    </div>
                </div>
            </div>

            <!-- Endpoint Settings -->
            <div id="endpoint-settings" class="henjii-tab-content">
                <div class="henjii-settings-section">
                    <h2>Endpoint Settings</h2>
                    <p>Configure which MCP endpoints are enabled on your server.</p>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[enable_resources_endpoint]" <?php checked(isset($settings['enable_resources_endpoint']) && $settings['enable_resources_endpoint'] === 'on'); ?>>
                            Enable Resources Endpoint
                        </label>
                        <p class="description">Allow clients to access your WordPress content through the /resources endpoint.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[enable_tools_endpoint]" <?php checked(isset($settings['enable_tools_endpoint']) && $settings['enable_tools_endpoint'] === 'on'); ?>>
                            Enable Tools Endpoint
                        </label>
                        <p class="description">Allow clients to use tools like search through the /tools endpoint.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[enable_prompts_endpoint]" <?php checked(isset($settings['enable_prompts_endpoint']) && $settings['enable_prompts_endpoint'] === 'on'); ?>>
                            Enable Prompts Endpoint
                        </label>
                        <p class="description">Allow clients to access predefined prompts through the /prompts endpoint.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[enable_sampling_endpoint]" <?php checked(isset($settings['enable_sampling_endpoint']) && $settings['enable_sampling_endpoint'] === 'on'); ?>>
                            Enable Sampling Endpoint
                        </label>
                        <p class="description">Allow clients to use recursive LLM interactions through the /sampling endpoint.</p>
                    </div>
                </div>
            </div>

            <!-- Content Settings -->
            <div id="content-settings" class="henjii-tab-content">
                <div class="henjii-settings-section">
                    <h2>Content Type Settings</h2>
                    <p>Configure which content types are accessible through your MCP server.</p>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[content_types][post]" <?php checked(isset($settings['content_types']['post']) && $settings['content_types']['post'] === 'on'); ?>>
                            Enable Posts
                        </label>
                        <p class="description">Allow access to blog posts and articles.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[content_types][page]" <?php checked(isset($settings['content_types']['page']) && $settings['content_types']['page'] === 'on'); ?>>
                            Enable Pages
                        </label>
                        <p class="description">Allow access to static pages.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[content_types][attachment]" <?php checked(isset($settings['content_types']['attachment']) && $settings['content_types']['attachment'] === 'on'); ?>>
                            Enable Media
                        </label>
                        <p class="description">Allow access to media files (images, documents, etc.).</p>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div id="advanced-settings" class="henjii-tab-content">
                <div class="henjii-settings-section">
                    <h2>Advanced Settings</h2>
                    <p>Configure advanced settings for your MCP server.</p>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">Cache Duration (seconds)</label>
                        <input type="number" name="henjii_settings[cache_duration]" value="<?php echo esc_attr(isset($settings['cache_duration']) ? $settings['cache_duration'] : 300); ?>" min="0" max="86400" class="henjii-form-input">
                        <p class="description">Duration in seconds to cache API responses. Set to 0 to disable caching.</p>
                    </div>

                    <div class="henjii-form-group">
                        <label class="henjii-form-label">
                            <input type="checkbox" name="henjii_settings[debug_mode]" <?php checked(isset($settings['debug_mode']) && $settings['debug_mode'] === 'on'); ?>>
                            Enable Debug Mode
                        </label>
                        <p class="description">Include additional debugging information in API responses.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php submit_button('Save Settings', 'primary', 'submit', true); ?>
    </form>
</div>
