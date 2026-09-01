# AI Providers

ReadOra supports NVIDIA NIM, OpenRouter, and Ollama through one provider-aware
service boundary.

The implementation uses the shared OpenAI-compatible chat-completions format.
OpenRouter receives the optional reasoning flag and preserves reasoning
metadata. NVIDIA NIM and Ollama use the common messages payload. Ollama runs
locally and does not require an API key.

Planned contract:

- Shared provider interface for chat requests.
- Provider classes for NVIDIA NIM and OpenRouter.
- `config/ai.php` for provider selection.
- `.env` values for credentials, base URLs, and model names.

No API keys should be committed.
