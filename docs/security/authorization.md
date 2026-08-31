# Authorization

Browser administration routes use `auth` and `admin` middleware. A normal user
cannot access the administration interface. AI routes require authentication
before any AI request is sent.

API routes use `api.token` middleware. It validates the bearer token hash,
rejects missing, invalid, or expired tokens with `401`, resolves the owning
user, and records the last-used timestamp. Token authentication does not grant
administrative privileges by itself.

Rate limits:

- AI chat and book insights: 10 requests per minute per user and IP address.
- Token-protected API routes: 60 requests per minute per user, or IP address
	before authentication.
