# API Overview

ReadOra exposes a small JSON API for authenticated integrations. API requests
use the `Authorization: Bearer <token>` header and are protected by the custom
`api.token` middleware. The API is intentionally separate from browser session
routes and uses JSON responses.

Rate limits are applied through the `api` limiter at 60 requests per minute per
authenticated user, or per IP address before authentication.

Current scope: authenticated identity and personal token revocation. Additional
catalog and patron endpoints can be added without changing browser routes.
