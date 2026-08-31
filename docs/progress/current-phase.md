# Current Phase

Current Phase: Phase 5 — Borrowing System

Status: Complete, awaiting user confirmation to begin Phase 6.

Completed:

- Created migration `2026_08_31_000001_create_borrowings_table` with `borrowed_at`, `due_at`, `returned_at`, `status`, and performance composite indexes.
- Created migration `2026_08_31_000002_create_favorites_table` with unique `(user_id, book_id)` constraint.
- Created migration `2026_08_31_000003_create_reading_histories_table` for patron activity scoring.
- Implemented Eloquent models `Borrowing`, `Favorite`, and `ReadingHistory` with relationships and query scopes (`active`, `overdue`, `returned`).
- Enhanced `User`, `Book`, and `BookCopy` models with circulation relationships, status helpers, and availability checks.
- Implemented `App\Services\BorrowingService` with database transactions, row-level locking (`lockForUpdate`), patron loan limits (max 5 active loans), duplicate checkout prevention, return handling, and 14-day renewals.
- Updated `BorrowingController` and `FavoriteController` with full checkout, return, renewal, listing, and favorite toggle capabilities.
- Updated web routes with secure authenticated patron circulation endpoints.
- Updated `books/show.blade.php` with dynamic "Borrow This Book" checkout form, loan status indicators, and interactive favorites toggle button.
- Updated `user/borrowings.blade.php` displaying active loans with countdown badges, return actions, online renewal buttons, and paginated past circulation archive.
- Updated `user/favorites.blade.php` displaying favorited works with instant removal action.
- Updated `DashboardController` and `dashboard.blade.php` to display live active borrowing metrics.
- Created comprehensive automated feature test suite `BorrowingCirculationTest.php` covering checkout limits, concurrency prevention, returns, renewals, and favorites with 100% pass rate.
- Formatted code with Laravel Pint.

In Progress:

- None.

Pending:

- Phase 6 — Content-Based Recommendation Engine.
- `RecommendationService` multi-signal affinity scoring (categories, authors, reading history, popularity).
- Recommended works carousel on user dashboard and similar titles on book details.

Known Issues:

- None.

Testing Instructions:

1. Run `vendor/bin/phpunit --colors=never tests/Feature/BorrowingCirculationTest.php`.
2. Sign in as demo patron (`patron@readora.test` / `password`).
3. Visit `/books` and click on any available title (e.g., *Pride and Prejudice*).
4. Click **Borrow This Book** and verify redirection to `/borrowings` with active 14-day due date countdown.
5. Click **Renew (+14d)** or **Return Book** and verify status and copy availability immediately update.
6. Click **Save to Favorites** on any book and view it in `/favorites`.

Recommended Commit:

```bash
git add .
git commit -m "feat: implement Phase 5 borrowing system and circulation lifecycle"
```

Next Phase: Phase 6 — Recommendation Engine
