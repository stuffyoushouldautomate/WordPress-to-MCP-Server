=== Henjii - WordPress MCP Server ===
Contributors: Jeremy Harris
Tags: mcp, ai, llm, api, model context protocol
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Turn your WordPress site into a Model Context Protocol (MCP) server that LLM applications can easily integrate with.

== Description ==

Henjii transforms your WordPress site into a powerful Model Context Protocol (MCP) server, making it simple for LLM applications to access and interact with your content. With a sleek admin interface and comprehensive API, Henjii bridges the gap between your WordPress content and AI applications.

### What is MCP?

The Model Context Protocol (MCP) is an open standard that enables seamless integration between LLM applications and external data sources and tools. It provides a standardized way for applications to share contextual information with language models, expose tools and capabilities to AI systems, and build composable integrations and workflows.

### Key Features

* **MCP Server Implementation**: Full implementation of the Model Context Protocol standard
* **REST API Endpoints**: Expose your WordPress content through standardized API endpoints
* **Resource Access**: Allow LLM applications to access posts, pages, and media
* **Tool Execution**: Provide custom tools for AI models to interact with your site
* **Prompt Templates**: Create templated prompts for consistent AI interactions
* **Sampling Support**: Enable recursive LLM interactions through sampling
* **Sleek Admin Interface**: Modern dashboard with usage statistics and configuration options
* **Comprehensive Documentation**: Detailed guides for integration with LLM applications
* **Security Controls**: API key authentication, rate limiting, and access controls
* **Usage Tracking**: Monitor API usage with detailed statistics

### Use Cases

* **Content Retrieval**: Allow AI assistants to access and reference your WordPress content
* **Search Integration**: Enable AI applications to search your site content
* **Custom Tools**: Create specialized tools for AI models to interact with your site
* **Templated Responses**: Define consistent response templates for AI interactions
* **Enhanced AI Capabilities**: Provide your AI applications with access to your domain expertise

== Installation ==

1. Upload the `henjii` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Henjii MCP settings page to configure the plugin
4. Generate API keys for your LLM applications
5. Use the connection information to integrate with your AI applications

== Frequently Asked Questions ==

= What is MCP? =

The Model Context Protocol (MCP) is an open standard that enables seamless integration between LLM applications and external data sources and tools. It provides a standardized way for applications to share contextual information with language models.

= Do I need to know how to code to use this plugin? =

No coding knowledge is required to set up and configure the plugin. However, to integrate with LLM applications, some basic understanding of APIs and the specific LLM application's integration methods would be helpful.

= Is authentication required? =

By default, authentication is enabled and required for all API requests. You can disable this in the settings, but it's recommended to keep authentication enabled for security reasons.

= How do I generate API keys? =

You can generate API keys on the Settings page of the Henjii MCP admin interface. These keys can then be used to authenticate requests from your LLM applications.

= Can I limit which content is accessible? =

Yes, you can configure which content types (posts, pages, media) are accessible through the API in the plugin settings.

= Does this plugin support custom post types? =

The current version supports the default WordPress post types (posts, pages, and media). Support for custom post types will be added in a future update.

= How can I monitor API usage? =

The plugin includes a dashboard with usage statistics, including total requests, average response time, and requests by endpoint.

== Screenshots ==

1. Dashboard with usage statistics
2. Settings page for configuration
3. Documentation page with integration guides
4. Connection information page

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release of the Henjii WordPress MCP Server plugin.

== Documentation ==

For detailed documentation and integration guides, please visit the Documentation page in the plugin admin interface.
