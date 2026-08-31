# AI Configuration

ReadOra supports two AI providers: NVIDIA NIM and OpenRouter.

## Environment Variables

```env
AI_PROVIDER=nvidia        # Options: nvidia, openrouter

# NVIDIA NIM
NVIDIA_NIM_API_KEY=
NVIDIA_NIM_BASE_URL=https://integrate.api.nvidia.com/v1/chat/completions
NVIDIA_NIM_MODEL=deepseek-ai/deepseek-v4-pro-0813

# OpenRouter
OPENROUTER_API_KEY=
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1/chat/completions
OPENROUTER_MODEL=nvidia/nemotron-3.5-lightning:free
```

## Switching Providers

1. Set `AI_PROVIDER` to either `nvidia` or `openrouter`
2. Fill in the corresponding API key, base URL, and model
3. Clear the config cache: `php artisan config:clear`

The service reads the selected provider's API key, URL, and model from
`config/ai.php`. It accepts either a provider base URL or a complete
`/chat/completions` URL. OpenRouter reasoning metadata is preserved across
chat turns; NVIDIA NIM uses the same OpenAI-compatible message format.

## Config File

See `config/ai.php` for the provider configuration structure.
