# Final QA

## Verification Commands

```bash
vendor/bin/phpunit --colors=never
vendor/bin/pint --dirty --format agent
npm run build
composer audit
```

## Manual Checks

- Register and log in as a patron.
- Browse, search, favorite, borrow, return, and review a book.
- Verify light, dark, and system themes on desktop and mobile widths.
- Confirm normal users receive `403` for admin routes.
- Confirm AI chat and book insights require authentication.
- Switch `AI_PROVIDER` between `openrouter` and `nvidia` after clearing config.
- Issue, use, and revoke a bearer API token.
- Log in as an admin and verify dashboard, CRUD, reports, exports, and audit log
  access.

## Release Gates

Do not release until the full test suite passes, exposed credentials have been
revoked, production uses HTTPS and `APP_DEBUG=false`, backups are verified, and
the deployment checklist is complete.
