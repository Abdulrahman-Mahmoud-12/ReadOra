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
- Completed Phase 3: Database and Library Data.
- Added normalized catalog schema for books, authors, publishers, categories, book copies, and many-to-many pivots.
- Added catalog Eloquent models, factories, search scope, and availability helpers.
- Replaced CSV data resources with pure SQL database seeding via `Database\Seeders\BookDatasetSeeder` containing 107 real public-domain book records.
- Added demo admin/user seeders, relationship/search/seeding tests, and Phase 3 database/data documentation.
- Completed Phase 4: User Interface.
- Built searchable, filterable, sortable, paginated Book Discovery catalog (`/books`).
- Built rich Book Details profile (`/books/{slug}`) with complete metadata, publisher/authors, related books, and physical copy inventory.
- Enhanced Patron Dashboard (`/dashboard`) with real catalog discovery metrics, recommendation cards, and category exploration.
- Created Patron personal space: Saved Favorites (`/favorites`), Borrowings History (`/borrowings`), and Profile Settings (`/profile`) with Digital Library Card.
- Built reusable Blade components (`x-book-card`, `x-badge`, `x-stat-card`, `x-empty-state`).
- Added automated feature tests (`BookDiscoveryTest.php`, `UserDashboardTest.php`) with 100% test pass rate.


