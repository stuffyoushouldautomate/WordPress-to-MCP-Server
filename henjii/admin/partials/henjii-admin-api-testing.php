<?php
/**
 * Provide an admin area view for API testing
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

    <div class="henjii-api-test">
        <div class="henjii-api-test-header">
            <h3>Test MCP API Requests</h3>
            <p>Use this tool to test API requests to your MCP server endpoints and see the responses.</p>
        </div>

        <div class="henjii-api-test-form">
            <div class="henjii-api-test-endpoint henjii-form-group">
                <label for="henjii-api-test-endpoint" class="henjii-form-label">Endpoint</label>
                <select id="henjii-api-test-endpoint" class="henjii-form-select">
                    <option value="">Select an endpoint</option>
                    <optgroup label="Resources">
                        <option value="resources">GET /resources</option>
                        <option value="resources/post">GET /resources/post</option>
                        <option value="resources/page">GET /resources/page</option>
                        <option value="resources/media">GET /resources/media</option>
                    </optgroup>
                    <optgroup label="Tools">
                        <option value="tools">GET /tools</option>
                        <option value="tools/search">POST /tools/search</option>
                        <option value="tools/contact">POST /tools/contact</option>
                    </optgroup>
                    <optgroup label="Prompts">
                        <option value="prompts">GET /prompts</option>
                    </optgroup>
                    <optgroup label="Sampling">
                        <option value="sampling">POST /sampling</option>
                    </optgroup>
                </select>
            </div>

            <div class="henjii-form-group">
                <label class="henjii-form-label">Method</label>
                <div class="henjii-api-test-method">
                    <button type="button" class="henjii-api-test-method-button active" data-method="GET">GET</button>
                    <button type="button" class="henjii-api-test-method-button" data-method="POST">POST</button>
                    <button type="button" class="henjii-api-test-method-button" data-method="PUT">PUT</button>
                    <button type="button" class="henjii-api-test-method-button" data-method="DELETE">DELETE</button>
                </div>
            </div>

            <div class="henjii-form-group">
                <label class="henjii-form-label">API Key</label>
                <input type="text" class="henjii-form-input" id="henjii-api-test-key" placeholder="Enter API key (if required)">
            </div>

            <div class="henjii-api-test-params henjii-form-group">
                <label class="henjii-form-label">Parameters</label>
                <div class="henjii-api-test-params-container">
                    <div class="henjii-api-test-param-row">
                        <input type="text" class="henjii-form-input" placeholder="Parameter name">
                        <input type="text" class="henjii-form-input" placeholder="Value">
                        <button type="button" class="henjii-api-test-param-remove">×</button>
                    </div>
                </div>
                <button type="button" class="henjii-api-test-add-param">
                    <span class="dashicons dashicons-plus-alt"></span> Add Parameter
                </button>
            </div>

            <div class="henjii-form-group">
                <label class="henjii-form-label">Request Body (for POST/PUT)</label>
                <textarea class="henjii-form-textarea" id="henjii-api-test-body" placeholder="Enter JSON request body"></textarea>
            </div>

            <div class="henjii-api-test-buttons">
                <button type="button" class="henjii-button henjii-button-secondary henjii-api-test-clear">Clear</button>
                <button type="button" class="henjii-button henjii-api-test-button">Test Request</button>
            </div>
        </div>

        <div class="henjii-api-test-result" style="display: none;">
            <!-- Results will be displayed here -->
        </div>
    </div>

    <div class="henjii-card">
        <div class="henjii-card-header">
            <h3>API Testing Guide</h3>
        </div>
        <div class="henjii-card-content">
            <h4>How to use the API Testing Tool</h4>
            <ol>
                <li>Select an endpoint from the dropdown or enter a custom endpoint path</li>
                <li>Choose the appropriate HTTP method (GET, POST, PUT, DELETE)</li>
                <li>Add your API key if authentication is enabled</li>
                <li>Add parameters as needed for your request</li>
                <li>For POST or PUT requests, enter a JSON request body if required</li>
                <li>Click "Test Request" to see the response</li>
            </ol>

            <h4>Common Test Cases</h4>
            <ul>
                <li><strong>List all resources:</strong> GET /resources</li>
                <li><strong>Search for content:</strong> POST /tools/search with {"query": "your search term"}</li>
                <li><strong>Get a specific post:</strong> GET /resources/post?id=123</li>
                <li><strong>List available prompts:</strong> GET /prompts</li>
                <li><strong>Test sampling:</strong> POST /sampling with {"prompt": "Hello, how can I help?", "max_tokens": 100}</li>
            </ul>

            <h4>Troubleshooting</h4>
            <ul>
                <li><strong>401 Unauthorized:</strong> Check your API key</li>
                <li><strong>404 Not Found:</strong> Verify the endpoint path</li>
                <li><strong>400 Bad Request:</strong> Check your parameters and request body format</li>
                <li><strong>429 Too Many Requests:</strong> You've exceeded the rate limit, try again later</li>
            </ul>
        </div>
    </div>
</div>
