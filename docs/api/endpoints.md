# API Endpoints

## Token Management

`POST /api-tokens` is a session-authenticated browser endpoint. Body:

```json
{ "name": "Mobile client" }
```

It returns a plaintext token once with an expiry timestamp. The token is stored
only as a SHA-256 hash.

## Protected API

| Method   | Path                  | Authentication | Purpose                                   |
| -------- | --------------------- | -------------- | ----------------------------------------- |
| `GET`    | `/api/me`             | Bearer token   | Return the current user's public identity |
| `DELETE` | `/api/tokens/current` | Bearer token   | Revoke the current token                  |

Missing, invalid, expired, or revoked tokens return `401` JSON responses.
