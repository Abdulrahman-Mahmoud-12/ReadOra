# Book Reviews, Ratings & Patron Feedback

ReadOra provides an interactive review and rating system enabling patrons to rate books, write critiques, and track reading community sentiment.

---

## 1. Data Model & Unique Constraint

- **Table**: `reviews` (`user_id`, `book_id`, `rating` [1-5], `title`, `content`, `status` [`approved`, `pending`, `rejected`], `timestamps`).
- **One Review Rule**: A unique index `unique(['user_id', 'book_id'])` ensures each patron submits exactly one review per book. Subsequent submissions update the patron's existing review.

---

## 2. Dynamic Rating Aggregates & Star Distribution

Whenever an approved review is submitted, edited, deleted, or moderated:
- The system automatically triggers `updateBookAggregates()` to recalculate the book's `average_rating` and total `ratings_count`.
- `Book::ratingDistribution()` calculates live percentage bars across 5★, 4★, 3★, 2★, and 1★ ratings.

---

## 3. Patron Experience & Book Details UI

On `/books/{slug}`:
- **Rating Summary Panel**: Visual breakdown of star distribution percentages and average rating.
- **Review Submission & Editing**: Authenticated patrons can select 1-5 stars, provide an optional title, and write commentary. If the patron already reviewed the book, the form switches to update/delete mode.
- **Community Reviews Feed**: Displays approved reviews with author avatar, rating stars, timestamp, and review body.
- **Guest CTA**: Directs unauthenticated guests to sign in before posting reviews.

---

## 4. Administration Moderation (`/admin/reviews`)

- Administrators can review community feedback filtered by status (`approved`, `pending`, `rejected`).
- Quick **Approve** and **Reject** actions dynamically recalculate book aggregates.
- Moderation and review deletions are recorded in `audit_logs`.
