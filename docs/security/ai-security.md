# AI Security

AI endpoints are protected by Laravel authentication and a dedicated rate
limiter before the model provider is called. The model is never responsible
for deciding whether a request is authorized.

The current prototype sends only public catalog context to the AI service. It
does not expose users, passwords, API keys, environment values, or audit logs
to the model. OpenRouter `reasoning_details` values are accepted only as
conversation metadata and are forwarded unmodified for the same conversation.

Provider failures return a friendly response and are logged with the provider
name, never with credentials or request secrets.
