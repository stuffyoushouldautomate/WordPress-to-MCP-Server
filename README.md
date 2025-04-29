
# WordPress MCP – The Ultimate WordPress to Model Context Protocol Server Plugin

**Open Source | Cloud & Enterprise Licenses Available**

---

## 🚀 Introduction

**WordPress MCP** is the most powerful, open-source plugin for turning your WordPress site into a full-featured Model Context Protocol (MCP) server. Instantly connect your content, tools, and workflows to LLMs (Large Language Models) and AI applications—no coding required.

- **Modern, dark, responsive admin UI** (Helvetica, edge-to-edge)
- **API key management** for secure, scalable access
- **Open source** for everyone, with cloud and enterprise options for teams
- **SEO-optimized** for anyone searching for "WordPress MCP" or "WordPress AI API"

> "If you want to make your WordPress site AI-ready, this is the plugin you need."

---

## 🆕 What's New in v2.0?
- One-click API key generation (secure, random)
- Always-on dark mode, Helvetica font, edge-to-edge UI
- Streamlined dashboard and settings
- Improved documentation and onboarding
- Bug fixes and performance improvements

---

## 💡 Why Use WordPress MCP?

- **Expose your content to AI:** Let ChatGPT, Claude, or any LLM access your posts, pages, and media.
- **Build AI-powered search:** Enable smart, context-aware search for your site's content.
- **Automate workflows:** Trigger custom tools and endpoints from AI or automation platforms.
- **Standardize AI responses:** Use prompt templates for consistent, branded outputs.
- **Scale securely:** API key auth, rate limiting, and usage tracking out of the box.
- **Open source freedom:** Use, extend, and contribute—or upgrade for cloud/enterprise features.

---

## 📋 Features Table

| Feature                | Description                                                      | Example Use/How-To                                                                 |
|------------------------|------------------------------------------------------------------|-------------------------------------------------------------------------------------|
| MCP REST API           | Standardized endpoints for LLM integration                       | `GET /wp-json/henjii/v1/resources` to list all posts/pages/media                    |
| API Key Authentication | Secure access to all endpoints                                   | Add `X-Henjii-API-Key: YOUR_KEY` header to your API requests                        |
| API Key Management     | Generate/delete API keys in admin UI                             | Click "Generate New API Key" in settings                                            |
| Rate Limiting          | Prevent abuse by limiting requests per minute                    | Set max requests/minute in settings                                                 |
| Usage Dashboard        | Visual stats on API usage and endpoint activity                  | View dashboard for total requests, endpoint breakdown, etc.                         |
| Endpoint Toggles       | Enable/disable resources, tools, prompts, sampling endpoints     | Uncheck endpoints in settings to hide from API                                      |
| Content Type Control   | Choose which post types are exposed                              | Enable/disable posts, pages, media in settings                                      |
| Prompt Templates       | Define reusable prompts for LLM workflows                        | Add prompt templates in the admin, fetch via `/prompts` endpoint                    |
| Tools Endpoint         | Expose custom tools for LLMs to trigger                          | POST to `/tools/search` with `{ "query": "example" }`                               |
| Sampling Endpoint      | Enable recursive/agentic LLM flows                               | POST to `/sampling` with `{ "prompt": "Hello", "max_tokens": 100 }`                |
| Connection Info Page   | Easy copy-paste API URLs and auth headers                        | Visit "Connection Info" in admin for ready-to-use examples                          |
| API Testing Tool       | Test endpoints directly from the admin UI                        | Use the "API Testing" tab to try requests and see responses                         |
| Documentation Page     | Built-in, always up-to-date integration guide                    | See "Documentation" tab for full API and usage docs                                 |

---

## 🌍 Real-World Use Cases
- **AI Chatbots:** Let LLMs answer questions based on your WordPress content.
- **Automated Research Agents:** Build LLM tools that query your articles, media, and tools.
- **Custom Workflows:** Trigger WordPress functions (tools) directly from AI or automation apps.
- **Enterprise Integrations:** Provide API access to internal teams, partners, and apps securely.
- **Prompt Consistency:** Standardize AI outputs with pre-made prompts and templates.

---

## 🎨 Fun Interactive Demo

We created a live interactive UI that shows random (and OpenAI-generated) use case ideas to help you imagine what’s possible:  
👉 **Demo coming soon...** 

---

## 📚 Documentation & Support
- In-app Documentation Tab (full API guide)
- Example code for Python, JS, cURL
- Troubleshooting and advanced configuration included
- Need help? support@henjii.com

---

## 🤝 Contributing

Pull requests and issues are very welcome! 🚀  
Star the repo ⭐ if you find it helpful and want to see it grow!

**GitHub Issues**

---

## 📜 License

GPL v2 or later.  
See the LICENSE file for full license text.

---

**Built by henjii — Modern Tools for Smarter Builders.**
