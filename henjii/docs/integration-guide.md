# Henjii WordPress MCP Server - Integration Guide

This guide provides detailed instructions for integrating your WordPress site as an MCP server with LLM applications.

## What is Henjii?

Henjii is a WordPress plugin that turns your WordPress site into a Model Context Protocol (MCP) server. This allows LLM applications to easily access and interact with your WordPress content through a standardized API.

## What is MCP?

The Model Context Protocol (MCP) is an open standard that enables seamless integration between LLM applications and external data sources and tools. It provides a standardized way for applications to share contextual information with language models, expose tools and capabilities to AI systems, and build composable integrations and workflows.

## Getting Started

### Prerequisites

- WordPress 5.8 or higher
- PHP 7.4 or higher
- An LLM application that supports MCP integration

### Connection Information

To connect your LLM application to your WordPress site, you'll need the following information:

- **Base URL**: `https://your-wordpress-site.com/wp-json/henjii/v1`
- **API Key**: Generated in the Henjii settings page
- **Authentication Header**: `X-Henjii-API-Key: your-api-key`

## API Endpoints

Henjii provides the following API endpoints:

### Configuration Endpoint

```
GET /wp-json/henjii/v1/config
```

Returns information about your MCP server, including the available capabilities.

Example response:
```json
{
  "name": "My WordPress Site",
  "description": "My awesome WordPress site",
  "url": "https://example.com",
  "version": "1.0.0",
  "capabilities": {
    "resources": true,
    "tools": true,
    "prompts": true,
    "sampling": true
  }
}
```

### Resources Endpoint

```
GET /wp-json/henjii/v1/resources
```

Returns a list of available resources (content types) on your WordPress site.

Example response:
```json
[
  {
    "type": "post",
    "name": "Posts",
    "description": "WordPress post content"
  },
  {
    "type": "page",
    "name": "Pages",
    "description": "WordPress page content"
  },
  {
    "type": "attachment",
    "name": "Media",
    "description": "WordPress attachment content"
  }
]
```

### Resource Content Endpoint

```
GET /wp-json/henjii/v1/resources/{type}/{id}
```

Returns the content of a specific resource.

Example response for a post:
```json
{
  "id": 1,
  "title": "Hello World",
  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start writing!",
  "excerpt": "Welcome to WordPress. This is your first post.",
  "date": "2023-01-01 12:00:00",
  "modified": "2023-01-01 12:00:00",
  "author": "Admin",
  "url": "https://example.com/hello-world",
  "featured_image": "https://example.com/wp-content/uploads/2023/01/featured-image.jpg",
  "taxonomies": {
    "category": ["Uncategorized"],
    "post_tag": ["welcome", "first-post"]
  }
}
```

### Tools Endpoint

```
GET /wp-json/henjii/v1/tools
```

Returns a list of available tools that LLM applications can execute.

Example response:
```json
[
  {
    "id": "search",
    "name": "search",
    "description": "Search WordPress content",
    "parameters": {
      "query": {
        "type": "string",
        "description": "Search query"
      },
      "post_type": {
        "type": "string",
        "description": "Post type to search",
        "default": "post"
      }
    }
  }
]
```

### Tool Execution Endpoint

```
POST /wp-json/henjii/v1/tools/{id}
```

Executes a specific tool.

Example request for the search tool:
```json
{
  "query": "WordPress",
  "post_type": "post"
}
```

Example response:
```json
[
  {
    "id": 1,
    "title": "Hello WordPress",
    "excerpt": "This is a post about WordPress.",
    "url": "https://example.com/hello-wordpress"
  },
  {
    "id": 2,
    "title": "Getting Started with WordPress",
    "excerpt": "Learn how to get started with WordPress.",
    "url": "https://example.com/getting-started-with-wordpress"
  }
]
```

### Prompts Endpoint

```
GET /wp-json/henjii/v1/prompts
```

Returns a list of available prompts (templated messages and workflows).

Example response:
```json
[
  {
    "id": 1,
    "name": "Welcome Message",
    "description": "A welcome message for new users",
    "template": "Welcome to {{site_name}}! We're glad you're here."
  }
]
```

### Prompt Content Endpoint

```
GET /wp-json/henjii/v1/prompts/{id}
```

Returns the content of a specific prompt.

Example response:
```json
{
  "id": 1,
  "name": "Welcome Message",
  "description": "A welcome message for new users",
  "template": "Welcome to {{site_name}}! We're glad you're here."
}
```

### Sampling Endpoint

```
POST /wp-json/henjii/v1/sampling
```

Handles sampling requests for server-initiated agentic behaviors and recursive LLM interactions.

Example request:
```json
{
  "prompt": "Tell me about WordPress"
}
```

Example response:
```json
{
  "completion": "WordPress is a popular content management system that powers over 40% of all websites on the internet. It's known for its flexibility, ease of use, and large community of developers and users."
}
```

## Integration Examples

### Python Example

```python
import requests

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
    print(f"Result: {result['title']} - {result['url']}")
```

### JavaScript Example

```javascript
// Configuration
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
    });
```

## Security Considerations

### Authentication

Henjii uses API key authentication to secure your MCP server. You can generate API keys on the Settings page of the plugin admin interface.

To authenticate requests, include the API key in the X-Henjii-API-Key header:

```
X-Henjii-API-Key: your-api-key
```

### Rate Limiting

Henjii includes rate limiting to prevent abuse of your MCP server. You can configure the rate limit on the Settings page of the plugin admin interface.

If a client exceeds the rate limit, they will receive a 429 Too Many Requests response.

### Content Access Control

You can control which content types are accessible through the API in the plugin settings. By default, posts, pages, and media are accessible.

## Troubleshooting

### Common Issues

1. **Authentication Failed**: Ensure you're including the correct API key in the X-Henjii-API-Key header.
2. **Rate Limit Exceeded**: Check your rate limit settings and ensure you're not making too many requests in a short period.
3. **Endpoint Not Found**: Verify that the endpoint you're trying to access is enabled in the plugin settings.
4. **Resource Not Found**: Ensure the resource type and ID you're requesting exist and are accessible.

### Getting Help

If you encounter any issues with the Henjii plugin, please visit the plugin support forum on WordPress.org or create an issue on the GitHub repository.

## Advanced Configuration

### Custom Tools

You can create custom tools for your MCP server by adding custom post types with the appropriate metadata. This allows you to extend the functionality of your MCP server beyond the built-in tools.

### Custom Prompts

You can create custom prompts for your MCP server by adding custom post types with the appropriate metadata. This allows you to define templated messages and workflows for consistent AI interactions.

### Webhook Integration

You can use WordPress actions and filters to integrate with webhooks and other external services. This allows you to extend the functionality of your MCP server and integrate with your existing workflows.

## Conclusion

Henjii makes it easy to turn your WordPress site into a powerful MCP server that LLM applications can easily integrate with. By following this guide, you should be able to set up and configure your MCP server, and integrate it with your LLM applications.

For more information and updates, please visit the plugin website or GitHub repository.
