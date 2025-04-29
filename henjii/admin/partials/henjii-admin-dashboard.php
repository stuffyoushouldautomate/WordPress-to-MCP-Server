<?php
/**
 * Provide an admin area view for the dashboard
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

// Get usage statistics
$total_requests = get_option('henjii_total_requests', 0);
$resources_requests = get_option('henjii_resources_requests', 0);
$tools_requests = get_option('henjii_tools_requests', 0);
$prompts_requests = get_option('henjii_prompts_requests', 0);
$sampling_requests = get_option('henjii_sampling_requests', 0);
?>

<div class="wrap henjii-admin">
    <div class="henjii-logo" style="color: #fff; background: #000; padding: 16px 0; border-radius: 8px;">
        <span class="dashicons dashicons-rest-api" style="color: #fff; font-size: 32px;"></span>
        <h2 style="font-family: Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; text-transform: lowercase; color: #fff; margin: 0 0 0 10px; letter-spacing: 1px;">henjii mcp</h2>
    </div>

    <div class="henjii-dashboard-header">
        <div>
            <h3>MCP Server Status</h3>
            <p>Your WordPress site is now an MCP server that LLM applications can easily integrate with.</p>
        </div>
        <div class="henjii-status">
            <span class="henjii-status-active">Active</span>
        </div>
    </div>

    <div class="henjii-dashboard-cards">
        <div class="henjii-card">
            <div class="henjii-card-header">
                <h3>Usage Statistics</h3>
            </div>
            <div class="henjii-card-content">
                <div class="henjii-stats-grid">
                    <div class="henjii-stat-box">
                        <div class="henjii-stat-number"><?php echo esc_html($total_requests); ?></div>
                        <div class="henjii-stat-label">Total Requests</div>
                    </div>
                    <div class="henjii-stat-box">
                        <div class="henjii-stat-number"><?php echo esc_html($resources_requests); ?></div>
                        <div class="henjii-stat-label">Resources</div>
                    </div>
                    <div class="henjii-stat-box">
                        <div class="henjii-stat-number"><?php echo esc_html($tools_requests); ?></div>
                        <div class="henjii-stat-label">Tools</div>
                    </div>
                    <div class="henjii-stat-box">
                        <div class="henjii-stat-number"><?php echo esc_html($prompts_requests); ?></div>
                        <div class="henjii-stat-label">Prompts</div>
                    </div>
                </div>

                <?php if ($total_requests > 0) : ?>
                <div class="henjii-chart">
                    <h4>Endpoint Usage</h4>
                    <ul class="henjii-chart-bars">
                        <li>
                            <span class="henjii-chart-label">Resources</span>
                            <div class="henjii-chart-bar" style="width: <?php echo esc_attr(($resources_requests / $total_requests) * 100); ?>%">
                                <?php echo esc_html(round(($resources_requests / $total_requests) * 100)); ?>%
                            </div>
                        </li>
                        <li>
                            <span class="henjii-chart-label">Tools</span>
                            <div class="henjii-chart-bar" style="width: <?php echo esc_attr(($tools_requests / $total_requests) * 100); ?>%">
                                <?php echo esc_html(round(($tools_requests / $total_requests) * 100)); ?>%
                            </div>
                        </li>
                        <li>
                            <span class="henjii-chart-label">Prompts</span>
                            <div class="henjii-chart-bar" style="width: <?php echo esc_attr(($prompts_requests / $total_requests) * 100); ?>%">
                                <?php echo esc_html(round(($prompts_requests / $total_requests) * 100)); ?>%
                            </div>
                        </li>
                        <li>
                            <span class="henjii-chart-label">Sampling</span>
                            <div class="henjii-chart-bar" style="width: <?php echo esc_attr(($sampling_requests / $total_requests) * 100); ?>%">
                                <?php echo esc_html(round(($sampling_requests / $total_requests) * 100)); ?>%
                            </div>
                        </li>
                    </ul>
                </div>
                <?php else : ?>
                <div class="henjii-no-data">
                    <p>No usage data available yet. Start using your MCP server to see statistics.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="henjii-card">
            <div class="henjii-card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="henjii-card-content">
                <div class="henjii-quick-actions">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-settings')); ?>" class="henjii-action-button">
                        <span class="dashicons dashicons-admin-settings"></span>
                        Settings
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-api-testing')); ?>" class="henjii-action-button">
                        <span class="dashicons dashicons-code-standards"></span>
                        Test API
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-documentation')); ?>" class="henjii-action-button">
                        <span class="dashicons dashicons-book"></span>
                        Docs
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-connection-info')); ?>" class="henjii-action-button">
                        <span class="dashicons dashicons-admin-links"></span>
                        Connect
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="henjii-card">
        <div class="henjii-card-header">
            <h3>System Status</h3>
        </div>
        <div class="henjii-card-content">
            <div class="henjii-status-grid">
                <div class="henjii-status-item">
                    <span class="henjii-status-label">API Authentication</span>
                    <span class="henjii-status-value">
                        <?php 
                        if (isset($settings['api_authentication']) && $settings['api_authentication'] === 'api_key') {
                            echo '<span class="henjii-status-enabled">Enabled</span>';
                        } else {
                            echo '<span class="henjii-status-disabled">Disabled</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="henjii-status-item">
                    <span class="henjii-status-label">Rate Limiting</span>
                    <span class="henjii-status-value">
                        <?php 
                        if (isset($settings['rate_limiting']) && $settings['rate_limiting'] === 'on') {
                            echo '<span class="henjii-status-enabled">Enabled</span>';
                        } else {
                            echo '<span class="henjii-status-disabled">Disabled</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="henjii-status-item">
                    <span class="henjii-status-label">Resources Endpoint</span>
                    <span class="henjii-status-value">
                        <?php 
                        if (isset($settings['enable_resources_endpoint']) && $settings['enable_resources_endpoint'] === 'on') {
                            echo '<span class="henjii-status-enabled">Enabled</span>';
                        } else {
                            echo '<span class="henjii-status-disabled">Disabled</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="henjii-status-item">
                    <span class="henjii-status-label">Tools Endpoint</span>
                    <span class="henjii-status-value">
                        <?php 
                        if (isset($settings['enable_tools_endpoint']) && $settings['enable_tools_endpoint'] === 'on') {
                            echo '<span class="henjii-status-enabled">Enabled</span>';
                        } else {
                            echo '<span class="henjii-status-disabled">Disabled</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="henjii-status-item">
                    <span class="henjii-status-label">Prompts Endpoint</span>
                    <span class="henjii-status-value">
                        <?php 
                        if (isset($settings['enable_prompts_endpoint']) && $settings['enable_prompts_endpoint'] === 'on') {
                            echo '<span class="henjii-status-enabled">Enabled</span>';
                        } else {
                            echo '<span class="henjii-status-disabled">Disabled</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="henjii-status-item">
                    <span class="henjii-status-label">Sampling Endpoint</span>
                    <span class="henjii-status-value">
                        <?php 
                        if (isset($settings['enable_sampling_endpoint']) && $settings['enable_sampling_endpoint'] === 'on') {
                            echo '<span class="henjii-status-enabled">Enabled</span>';
                        } else {
                            echo '<span class="henjii-status-disabled">Disabled</span>';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="henjii-card">
        <div class="henjii-card-header">
            <h3>Getting Started</h3>
        </div>
        <div class="henjii-card-content">
            <p>Welcome to Henjii MCP Server! Here's how to get started:</p>
            
            <ol>
                <li>Configure your <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-settings')); ?>">settings</a> to customize your MCP server</li>
                <li>Generate API keys for authentication</li>
                <li>View your <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-connection-info')); ?>">connection information</a> to integrate with LLM applications</li>
                <li>Test your endpoints using the <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-api-testing')); ?>">API testing tool</a></li>
                <li>Read the <a href="<?php echo esc_url(admin_url('admin.php?page=henjii-documentation')); ?>">documentation</a> for detailed information</li>
            </ol>
            
            <p>Need help? Visit our <a href="https://henjii.com/support" target="_blank">support site</a> or contact us at <a href="mailto:support@henjii.com">support@henjii.com</a>.</p>
        </div>
    </div>
</div>
