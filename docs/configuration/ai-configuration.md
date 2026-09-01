# AI Configuration

ReadOra supports NVIDIA NIM, OpenRouter, and an optional local Ollama provider.

## Environment Variables

```env
AI_PROVIDER=ollama        # Options: ollama, nvidia, openrouter

# NVIDIA NIM
NVIDIA_NIM_API_KEY=
NVIDIA_NIM_BASE_URL=https://integrate.api.nvidia.com/v1/chat/completions
NVIDIA_NIM_MODEL=deepseek-ai/deepseek-v4-pro-0813

# OpenRouter
OPENROUTER_API_KEY=
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1/chat/completions
OPENROUTER_MODEL=nvidia/nemotron-3.5-lightning:free

# Ollama (local, no cloud API key required)
OLLAMA_API_KEY=
OLLAMA_BASE_URL=http://127.0.0.1:11434/v1/chat/completions
OLLAMA_MODEL=llama3.2
```

## Switching Providers

1. Set `AI_PROVIDER` to `nvidia`, `openrouter`, or `ollama`
2. Fill in the corresponding API key, base URL, and model
3. Clear the config cache: `php artisan config:clear`

For Ollama, install and start Ollama, then download a model:

```bash
ollama serve
ollama pull qwen2.5:7b
```

Ollama listens locally at `http://127.0.0.1:11434`. Do not expose this port
through ngrok or another public tunnel.

The service reads the selected provider's API key, URL, and model from
`config/ai.php`. It accepts either a provider base URL or a complete
`/chat/completions` URL. OpenRouter reasoning metadata is preserved across
chat turns; NVIDIA NIM uses the same OpenAI-compatible message format.

## Config File

See `config/ai.php` for the provider configuration structure.
