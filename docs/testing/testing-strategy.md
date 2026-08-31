# Testing Strategy

The project uses PHPUnit with Laravel's feature and database testing helpers.
Run the complete suite with:

```bash
vendor/bin/phpunit --colors=never
```

Feature tests cover authentication, role authorization, catalog workflows,
borrowing, reviews, reading lists, reports, AI integration, API tokens, and
security headers. External AI calls use `Http::fake()` and must never reach a
real provider during tests. Database tests use `RefreshDatabase` and factories.

Before release, run the full suite, `vendor/bin/pint --dirty --format agent`,
`npm run build`, and `composer audit`. Confirm the production checklist and
exercise login, admin access, borrowing, AI chat, and API token revocation
manually in a staging environment.
