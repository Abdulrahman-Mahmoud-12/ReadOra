# Data Cleaning & Normalization

## Normalization

- Structured dataset fields are mapped directly in `Database\Seeders\BookDatasetSeeder`.
- Author and category lists use semicolon separators.
- Empty optional fields become `null`.
- Missing language defaults to `en`.
- Missing publisher defaults to `Project Gutenberg`.
- Slugs are generated with Laravel's `Str::slug()`.

## Deduplication

- Books prefer ISBN-based lookup when ISBN values are available.
- Public-domain records without ISBNs use `source` plus `source_identifier`.
- Authors, categories, and publishers are deduplicated by slug.
- Book-copy creation is capped at the requested target count for each book, so reruns do not duplicate inventory.

## Known Limitations

- Cover images are intentionally left blank in Phase 3.
- Ratings are seed metadata only and are not yet user-generated.
- Some ancient works use approximate negative publication years so the schema uses a signed small integer.

