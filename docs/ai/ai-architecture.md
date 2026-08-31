# AI Architecture

`App\Services\AiService` is the application boundary for AI requests. It reads
the selected provider from `config/ai.php`, loads that provider's API key, URL,
and model, and sends the common OpenAI-compatible chat-completion payload.

Supported providers:

- OpenRouter, with reasoning enabled and `reasoning_details` preserved.
- NVIDIA NIM, using the same messages format without the OpenRouter reasoning
  option.

Controllers coordinate validation and HTTP responses; provider configuration
and outbound HTTP behavior remain inside the service.
