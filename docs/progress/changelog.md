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
- Completed Phase 5: Borrowing & Circulation System.
- Created migrations and Eloquent models for `borrowings`, `favorites`, and `reading_histories`.
- Implemented `BorrowingService` with database transactions, row-level locking (`lockForUpdate`), patron active loan limits (max 5), duplicate loan prevention, checkouts, returns, and renewals.
- Updated `BorrowingController` and `FavoriteController` with full circulation endpoints and favorites toggling.
- Updated `books/show.blade.php`, `user/borrowings.blade.php`, `user/favorites.blade.php`, and `dashboard.blade.php` with real-time circulation UI and actions.
- Added comprehensive feature test suite `BorrowingCirculationTest.php` with 100% passing tests.
- Documented borrowing lifecycle in `docs/features/borrowing-system.md` and formatted code with Laravel Pint.
- Completed Phase 6: Content-Based Recommendation Engine.
- Implemented `App\Services\RecommendationService` with category affinity, author affinity, ratings quality, availability weighting, and novelty filtering.
- Added similar books matching for book details view and recommendation reason indicators on patron dashboard.
- Added comprehensive unit test suite `RecommendationServiceTest.php` with 100% passing tests.
- Documented recommendation scoring architecture in `docs/features/recommendation-system.md` and formatted code with Laravel Pint.
- Completed Phase 7: Admin Dashboard & Full Management Suite.
- Implemented `audit_logs` table, `AuditLog` model, and `App\Services\AuditLogger` service.
- Created Admin Dashboard (`/admin`) with real-time statistics (total works, copies, available/borrowed counts, overdue loans, registered patrons, active borrowers).
- Built Admin Circulation Desk (`/admin/circulations`) with filtering, search, check-in, and renewal override.
- Built Books CRUD (`/admin/books`), Copies Inventory (`/admin/copies`), Authors (`/admin/authors`), Categories (`/admin/categories`), and Publishers (`/admin/publishers`).
- Built User Management (`/admin/users`) with role promote/demote safeguards (protecting last admin) and loan deletion protection.
- Built Audit Log Viewer (`/admin/audit-logs`) with search and JSON payload inspection.
- Added comprehensive feature test suite `AdminManagementTest.php` (100% passing) and documented features in `docs/features/admin-features.md`.
