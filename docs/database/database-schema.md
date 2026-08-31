# Database Schema

Phase 3 adds the normalized catalog schema for ReadOra.

## Tables

### `authors`

- `id`
- `name`, unique
- `slug`, unique
- `biography`, nullable text
- `birth_date`, nullable date
- `death_date`, nullable date
- `photo_path`, nullable string
- `external_identifiers`, nullable JSON
- timestamps

### `publishers`

- `id`
- `name`, unique
- `slug`, unique
- `website`, nullable string
- `country`, nullable string
- timestamps

### `categories`

- `id`
- `name`, unique
- `slug`, unique
- `description`, nullable text
- timestamps

### `books`

- `id`
- `publisher_id`, nullable foreign key to `publishers`
- `title`
- `subtitle`, nullable
- `slug`, unique
- `isbn_10`, nullable unique
- `isbn_13`, nullable unique
- `description`, nullable text
- `language`, default `en`
- `publication_year`, nullable signed small integer
- `edition`, nullable
- `page_count`, nullable unsigned small integer
- `cover_image_path`, nullable
- `average_rating`, decimal
- `ratings_count`, unsigned integer
- `source`, nullable
- `source_identifier`, nullable
- `source_url`, nullable
- `metadata`, nullable JSON
- timestamps

### `book_copies`

- `id`
- `book_id`, foreign key to `books`
- `barcode`, unique
- `status`, default `available`
- `location`, nullable string
- `condition`, default `good`
- `acquisition_date`, nullable date
- timestamps

Allowed copy statuses are currently represented in `App\Models\BookCopy` as `available`, `borrowed`, `reserved`, `lost`, and `maintenance`.

### `author_book`

- `author_id`, foreign key to `authors`
- `book_id`, foreign key to `books`
- timestamps
- composite primary key on `author_id`, `book_id`

### `book_category`

- `book_id`, foreign key to `books`
- `category_id`, foreign key to `categories`
- timestamps
- composite primary key on `book_id`, `category_id`
