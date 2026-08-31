# Authentication

ReadOra uses Laravel session authentication for the browser interface. The AI
assistant page, chat endpoint, and book insights endpoint require an
authenticated user.

Authenticated users can create a personal bearer token with `POST /api-tokens`
and a required `name`. Tokens expire after six months. Only the plaintext token
is returned at creation time; the database stores a SHA-256 hash.

Use `Authorization: Bearer readora_...` for protected API requests. `GET
/api/me` verifies the token and returns the authenticated user's public
identity fields. `DELETE /api/tokens/current` revokes the token in use.

Never commit API keys, bearer tokens, or populated `.env` files.
