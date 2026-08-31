# Database Relationships

## Catalog

- A `Publisher` has many `Book` records.
- A `Book` belongs to one optional `Publisher`.
- A `Book` belongs to many `Author` records through `author_book`.
- An `Author` belongs to many `Book` records through `author_book`.
- A `Book` belongs to many `Category` records through `book_category`.
- A `Category` belongs to many `Book` records through `book_category`.
- A `Book` has many `BookCopy` records.
- A `BookCopy` belongs to one `Book`.

## Availability

The application separates title metadata from physical inventory:

- `books` stores bibliographic data.
- `book_copies` stores physical inventory and status.
- `Book::availableCopies()` returns copies where `status = available`.
- `Book::availableCopiesCount()` counts borrowable copies without loading all copy models.
- `Book::isAvailable()` returns true when at least one copy is available.

Borrowing records are scheduled for Phase 5 and will reference `book_copies`, not `books`, so circulation can lock and update one physical item at a time.
