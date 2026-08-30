# AI Providers

ReadOra will support NVIDIA NIM and OpenRouter through a provider abstraction.

Phase 1 adds configuration placeholders only. The implementation will be added during the AI assistant phases.

Planned contract:

- Shared provider interface for chat requests.
- Provider classes for NVIDIA NIM and OpenRouter.
- `config/ai.php` for provider selection.
- `.env` values for credentials, base URLs, and model names.

No API keys should be committed.
