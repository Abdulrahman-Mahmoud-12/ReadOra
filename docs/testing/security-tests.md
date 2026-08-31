# Security Tests

Security coverage includes:

- Unauthenticated users cannot access protected dashboards or AI endpoints.
- Normal users cannot access administration routes.
- Missing, invalid, and revoked bearer tokens receive `401` responses.
- API tokens are stored as hashes and expire after six months.
- AI requests are rate-limited before the provider call.
- Security response headers are applied globally.
- AI context contains public catalog data only and never contains credentials.

Run the focused security tests with:

```bash
vendor/bin/phpunit tests/Feature/SecurityPhaseTest.php tests/Feature/AuthorizationTest.php
```
