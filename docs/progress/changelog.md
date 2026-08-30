# Changelog

## 2026-08-30

- Started ReadOra project from supplied instructions.
- Added Phase 0 planning and architecture documentation.
- Added architecture decision log.
- Added progress tracker.
- Added project README.

## 2026-08-31

- Installed Laravel Boost and generated project-specific agent guidance.
- Completed the Phase 1 Laravel foundation shell.
- Added ReadOra Tailwind theme tokens, branding assets, Blade layouts, reusable components, and persistent theme switching.
- Added a foundation feature test for the public home page.
- Verified the Phase 1 shell with PHPUnit and a production Vite build.
- Completed Phase 2: Authentication and Authorization.
- Implemented user roles (`user`, `admin`), `AdminMiddleware`, `LoginRequest` (with rate limiting), `RegisterRequest`, and auth controllers.
- Created patron dashboard (`/dashboard`) and admin portal overview (`/admin`).
- Created authentication views (`login.blade.php`, `register.blade.php`) and component layouts.
- Added comprehensive feature tests for authentication and role-based authorization in `AuthenticationTest.php` and `AuthorizationTest.php`.
- Ran full test suite (16 tests passed) and applied Laravel Pint formatting.
