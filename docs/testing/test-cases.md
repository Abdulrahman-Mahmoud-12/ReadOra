# Test Cases

The suite covers:

- Authentication, registration, logout, and role-based admin access.
- Catalog search, filters, relationships, borrowing, favorites, reviews, and
  reading lists.
- Admin CRUD, circulation, reports, exports, and audit logs.
- AI provider selection, reasoning metadata, fallback responses, and protected
  access.
- API token issuance, hashing, expiry handling, invalid-token rejection, and
  revocation.
- Global security headers and production dependency auditing.

Run all tests with `vendor/bin/phpunit --colors=never`. Use feature tests for
HTTP contracts and database workflows, and unit tests for isolated scoring or
service logic.
