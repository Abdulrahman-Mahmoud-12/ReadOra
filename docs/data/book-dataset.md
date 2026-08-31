# Book Dataset

## Source

- Dataset: ReadOra Project Gutenberg public-domain catalog subset
- Source: Project Gutenberg
- Source URL pattern: `https://www.gutenberg.org/ebooks/{id}`
- License: Project Gutenberg public-domain and freely redistributable metadata for the included works
- Storage: Native Laravel database seeder (`Database\Seeders\BookDatasetSeeder`)

## Size

The seed dataset contains 107 real public-domain book records, which is within the Phase 3 target of 100-300 books.

## Field Mapping

- `source`, `source_identifier`, `source_url` map to the book source tracking columns and `metadata`.
- `title`, `subtitle`, `language`, `publication_year`, `edition`, `page_count`, `description` map to `books`.
- `authors` is a semicolon-separated list seeded into `authors` and `author_book`.
- `publisher` seeds into `publishers`.
- `categories` is a semicolon-separated list seeded into `categories` and `book_category`.
- `copies` controls how many `book_copies` records are created.
- ISBN columns are included where available; many public-domain source texts predate ISBNs.

## Seeding Method

Run the database seeder:

```bash
php artisan db:seed
```

`Database\Seeders\BookDatasetSeeder` seeds the SQL database directly, creating publishers, authors, categories, books, and physical copies idempotently.
