# Indexing Strategy

Phase 3 indexes support catalog discovery and inventory availability without over-indexing early tables.

## Unique Integrity Indexes

- `authors.name`, `authors.slug`
- `publishers.name`, `publishers.slug`
- `categories.name`, `categories.slug`
- `books.slug`
- `books.isbn_10`
- `books.isbn_13`
- `books.source`, `books.source_identifier`
- `book_copies.barcode`
- Composite primary keys on `author_book` and `book_category`

## Query Indexes

- `books(language, publication_year)` supports language filters sorted or grouped by publication era.
- `books(title, publication_year)` supports catalog title scans and date sorting.
- `book_copies(book_id, status)` supports availability counts per title.
- `book_copies(status, condition)` supports inventory management views by status and condition.

## Future Review

Phase 4 search starts with relational `LIKE` queries across book title, subtitle, ISBN, authors, and categories. If real usage needs stronger relevance ranking or typo tolerance, add a dedicated search service in a later phase rather than expanding ad hoc query logic in controllers.
