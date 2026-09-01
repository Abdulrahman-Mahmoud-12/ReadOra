# AI Security

AI endpoints are protected by Laravel authentication and a dedicated rate
limiter before the model provider is called. The model is never responsible
for deciding whether a request is authorized.

Normal users receive only public catalog context. Authenticated administrators
also receive a limited, aggregate operational summary containing catalog,
inventory, user-count, loan, overdue, and recent circulation information. The
backend selects this context from the authenticated role before the provider
call.

The service does not expose passwords, API keys, environment values, bearer
tokens, or raw audit payloads to the model. OpenRouter `reasoning_details`
values are accepted only as conversation metadata and are forwarded unmodified
for the same conversation.

Provider failures return a friendly response and are logged with the provider
name, never with credentials or request secrets.
