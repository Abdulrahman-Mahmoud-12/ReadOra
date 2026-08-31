# Security Architecture

Browser requests use Laravel sessions, CSRF protection, validation, and
`auth`/`admin` middleware. Administrative actions are server-side protected;
hidden navigation links are not treated as authorization.

AI calls are authenticated and rate-limited before outbound HTTP. API calls use
hashed bearer tokens with expiry and revocation. Global responses include
content-type, framing, referrer, and permissions policy headers.

Secrets are loaded through configuration and are excluded from logs and source
control. Production must use HTTPS and `APP_DEBUG=false`.
