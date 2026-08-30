# AI Configuration

ReadOra supports two AI providers: NVIDIA NIM and OpenRouter.

## Environment Variables

```env
AI_PROVIDER=nvidia        # Options: nvidia, openrouter

# NVIDIA NIM
NVIDIA_NIM_API_KEY=
NVIDIA_NIM_BASE_URL=
NVIDIA_NIM_MODEL=

# OpenRouter
OPENROUTER_API_KEY=
OPENROUTER_BASE_URL=
OPENROUTER_MODEL=
```

## Switching Providers

1. Set `AI_PROVIDER` to either `nvidia` or `openrouter`
2. Fill in the corresponding API key, base URL, and model
3. Clear the config cache: `php artisan config:clear`

## Config File

See `config/ai.php` for the provider configuration structure.
