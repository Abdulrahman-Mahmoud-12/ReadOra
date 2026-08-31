# Catalog Seeding Pipeline

The Phase 3 catalog seeding pipeline lives in `database/seeders/BookDatasetSeeder.php`.

## Steps

1. Load embedded bibliographic dataset containing 107 real Gutenberg book records.
2. Validate required fields (title, authors, categories).
3. Deduplicate books by ISBN when present, otherwise by source and source identifier.
4. Create or reuse the publisher (`publishers` table).
5. Create or reuse authors (`authors` table) and categories (`categories` table).
6. Persist the book record (`books` table).
7. Sync author and category pivot records (`author_book`, `book_category`).
8. Create missing physical copies in `book_copies` up to the target `copies` count.
9. Return a seeding summary with `books`, `copies`, and `skipped` counts.

## Idempotency

The seeder is safe to run multiple times. Existing records are updated in place, relationships are synced, and existing copies are not duplicated.

## Direct Seeding

Executed as part of the normal Laravel database lifecycle:

```bash
php artisan db:seed --class=BookDatasetSeeder
```

