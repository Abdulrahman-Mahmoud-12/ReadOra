# Reading Lists, Custom Shelves & Activity Tracking

ReadOra provides patrons with personalized reading shelves, custom curated book lists, and public sharing features.

---

## 1. Data Model & Architecture

- **`reading_lists` Table**: `id`, `user_id`, `name`, `slug`, `description`, `is_public`, `timestamps`. Unique constraint on `['user_id', 'slug']`.
- **`book_reading_list` Pivot Table**: `id`, `reading_list_id`, `book_id`, `notes`, `order`, `timestamps`. Unique constraint on `['reading_list_id', 'book_id']`.
- **Default Shelves Auto-Initialization**:
  Every patron automatically receives standard shelves upon visiting their shelves area or opening a book:
  - *Want to Read*
  - *Currently Reading*
  - *Read*

---

## 2. Patron Shelves Interface (`/reading-lists`)

- **Collage Previews**: Shows miniature cover thumbnail collages for books within each shelf.
- **Custom List Creation**: Modal allowing patrons to create lists with custom names, descriptions, and public/private visibility.
- **Shelf Detail Management (`/reading-lists/{slug}`)**:
  - Add / remove books.
  - Personal notes per book (e.g. "Read chapter 4", "Recommended by colleague").
  - Privacy toggling (`is_public`).
  - Shelf deletion with cascade cleanup.

---

## 3. Public Sharing (`/lists/{slug}`)

- Publicly shared lists can be viewed by anyone (including unauthenticated guests) via shareable direct URLs.
- Displays the curator patron name and full book cards.

---

## 4. One-Click "Add to Shelf" Dropdown on Book Details (`/books/{slug}`)

- Interactive dropdown right on the book details page allowing patrons to add or remove the book from any of their shelves or custom reading lists with one click.
