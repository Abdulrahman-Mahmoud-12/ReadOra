# ReadOra Pages

This document details all implemented and planned pages in the ReadOra digital library application.

---

## 1. Implemented User Interface Pages (Phase 4)

### Home Page (`/`)
- **Route**: `home` (`GET /`)
- **Role**: Public (Guest & Authenticated)
- **Purpose**: Welcoming landing page showcasing ReadOra's mission, core features (discovery, borrowing, AI assistant), live statistics, and registration CTA.
- **Components**: `x-layouts.app`, `x-logo`, `x-button`, `x-theme-toggle`.
- **Actions**: Direct links to `/register`, `/login`, and `/books`.

### Library Catalog & Book Discovery (`/books`)
- **Route**: `books.index` (`GET /books`)
- **Role**: Public (Guest & Authenticated)
- **Purpose**: Full catalog search and discovery engine across 107 real public-domain works.
- **Filters & Controls**:
  - Full-text search (title, author, category, ISBN).
  - Category dropdown filter with dynamic book counts.
  - Quick category pill chips for trending subjects.
  - Physical copy availability filter checkbox.
  - Sorting options: Highest Rated, Most Popular, Newest Published, Oldest Published, Title (A-Z), Title (Z-A).
  - Pagination (12 books per page).
- **Components**: `x-book-card`, `x-badge`, `x-empty-state`, `x-button`.
- **States**:
  - **Results**: Responsive 4-column book grid.
  - **Empty State**: Friendly illustration and reset filters button.

### Book Details Page (`/books/{slug}`)
- **Route**: `books.show` (`GET /books/{slug}`)
- **Role**: Public (Guest & Authenticated)
- **Purpose**: Comprehensive bibliographic profile and physical inventory breakdown for an individual work.
- **Data Presented**:
  - Book title, subtitle, cover spine representation, authors, publisher.
  - Bibliographic specs: Publication year, page count, language, edition.
  - Full synopsis / description.
  - Identifiers: ISBN-10, ISBN-13, Project Gutenberg catalog identifier.
  - Community rating and ratings count.
  - Physical copies table: Barcode, shelf location, condition, acquisition date, and availability badge.
  - Related works carousel/grid sharing the primary category.
- **Actions**: "Borrow This Book" (Phase 5), "Save to Favorites", "Ask AI".

### Patron Dashboard (`/dashboard`)
- **Route**: `dashboard` (`GET /dashboard`)
- **Role**: Authenticated Patron (`user`, `admin`)
- **Purpose**: Centralized patron overview showing real-time catalog metrics, curated recommendations, category discovery, and patron shortcuts.
- **Data Presented**:
  - Total catalog books count.
  - Real-time available copies for loan.
  - Subject categories count.
  - Patron status and role badge.
  - Top-rated recommendation preview cards (`x-book-card`).
  - Popular category exploration list.
  - AI Assistant prompt card and patron shortcuts.
- **Components**: `x-stat-card`, `x-book-card`, `x-button`, `x-badge`.

### Saved Favorites (`/favorites`)
- **Route**: `favorites.index` (`GET /favorites`)
- **Role**: Authenticated Patron
- **Purpose**: Personal reading list space for saving and organizing favorite books.
- **Components**: `x-empty-state`, `x-book-card`, `x-button`.
- **States**:
  - **Empty**: Informative empty state prompting catalog discovery.
  - **Suggested Reading**: Curated classic works to jumpstart patron wishlists.

### Borrowing History (`/borrowings`)
- **Route**: `borrowings.index` (`GET /borrowings`)
- **Role**: Authenticated Patron
- **Purpose**: Circulation management space tracking active loans, due dates, and return history.
- **Features**:
  - Circulation policy banner (14-day loan duration, 5-book loan limit).
  - Active loans status and due dates indicator.
  - Empty state with direct link to available catalog books.
  - "Available in Stacks Now" showcase.

### Patron Profile & Settings (`/profile`)
- **Route**: `profile.show` (`GET /profile`), `profile.update` (`PATCH /profile`)
- **Role**: Authenticated Patron
- **Purpose**: Account details, digital library card visualization, and profile update settings.
- **Features**:
  - Digital Library Card widget displaying unique Card Number (`RO-00000X`), cardholder name, membership date, and active status.
  - Account info summary (email, borrowing privileges standing).
  - Edit Profile form (Name, Email) with CSRF protection, validation feedback, and session flash status messages.

---

## 2. Implemented Authentication Pages (Phase 2)

- **Login (`/login`)**: Secure patron and admin sign-in with rate limiting and remember-me support.
- **Register (`/register`)**: Patron account registration with password confirmation.

---

## 3. Planned Pages (Upcoming Phases)

- **Phase 5**: Full borrowing workflow modal, return processing, admin circulation desk.
- **Phase 6**: Algorithmic personalized recommendation engine feed.
- **Phase 7**: Admin CRUD pages (Books, Authors, Publishers, Categories, Copies, Users, Circulation, Audit Logs).
- **Phase 8**: Interactive AI assistant chat interface (`/assistant`).
