# ReadOra — Project Instructions

> **ReadOra** is a production-ready Laravel Library Management System with an integrated role-aware AI assistant.
> This document is the **single source of truth** for all development work.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Project Goals](#2-project-goals)
3. [Technology Stack](#3-technology-stack)
4. [AI Provider Configuration](#4-ai-provider-configuration)
5. [Architectural Principles](#5-architectural-principles)
6. [UI/UX Design](#6-uiux-design)
7. [Theme System](#7-theme-system)
8. [Branding and Logo](#8-branding-and-logo)
9. [User Roles](#9-user-roles)
10. [Normal User Capabilities](#10-normal-user-capabilities)
11. [Admin Capabilities](#11-admin-capabilities)
12. [Authentication](#12-authentication)
13. [Database Design](#13-database-design)
14. [Book Entity](#14-book-entity)
15. [Authors](#15-authors)
16. [Publishers](#16-publishers)
17. [Categories](#17-categories)
18. [Book Copies](#18-book-copies)
19. [Borrowing System](#19-borrowing-system)
20. [Favorites](#20-favorites)
21. [Reading History](#21-reading-history)
22. [Recommendation System](#22-recommendation-system)
23. [Book Discovery](#23-book-discovery)
24. [Book Details Page](#24-book-details-page)
25. [User Dashboard](#25-user-dashboard)
26. [User Profile](#26-user-profile)
27. [Admin Dashboard](#27-admin-dashboard)
28. [Admin Navigation](#28-admin-navigation)
29. [Admin CRUD](#29-admin-crud)
30. [Admin User Management](#30-admin-user-management)
31. [Audit Logging](#31-audit-logging)
32. [AI Assistant](#32-ai-assistant)
33. [AI Security](#33-ai-security)
34. [AI Context Scopes](#34-ai-context-scopes)
35. [AI Authorization Architecture](#35-ai-authorization-architecture)
36. [AI Prompt Injection Protection](#36-ai-prompt-injection-protection)
37. [AI Conversations](#37-ai-conversations)
38. [RAG-Ready Architecture](#38-rag-ready-architecture)
39. [Real Book Dataset](#39-real-book-dataset)
40. [Book Data Import Pipeline](#40-book-data-import-pipeline)
41. [Search](#41-search)
42. [Notifications](#42-notifications)
43. [Error Handling](#43-error-handling)
44. [File Uploads](#44-file-uploads)
45. [API Architecture](#45-api-architecture)
46. [Route Organization](#46-route-organization)
47. [Reusable UI Components](#47-reusable-ui-components)
48. [Responsive Design](#48-responsive-design)
49. [Accessibility](#49-accessibility)
50. [Testing](#50-testing)
51. [Performance](#51-performance)
52. [Logging](#52-logging)
53. [Documentation Structure](#53-documentation-structure)
54. [Development Phases](#54-development-phases)
55. [Git Workflow](#55-git-workflow)
56. [Progress Tracking](#56-progress-tracking)
57. [Code Quality](#57-code-quality)
58. [Anti-Patterns (Do NOT)](#58-anti-patterns-do-not)
59. [Final Project Structure](#59-final-project-structure)
60. [Phase Report Format](#60-phase-report-format)

---

## 1. Project Overview

Build a web-based library management platform called **ReadOra**.

ReadOra is a modern digital library management system that provides:

- Book discovery and exploration
- Book details
- User accounts and profiles
- Borrowing management
- Favorites & borrowing history
- Book recommendations
- Library administration
- Analytics and dashboard
- Role-based access control
- Role-aware AI assistant
- Search and filtering
- Notifications
- Dark/light mode
- Responsive UI
- Secure authentication and authorization
- Production-oriented architecture
- Comprehensive technical documentation

The application has **two clearly separated interfaces**:

| Interface | Description |
|-----------|-------------|
| **Normal User Interface** | Book browsing, borrowing, favorites, profile, AI chat |
| **Library Admin Interface** | Full management dashboard, analytics, user/book management |

Both interfaces share the same backend and database but have different layouts, navigation, permissions, and accessible functionality.

---

## 2. Project Goals

> [!IMPORTANT]
> This is **not** a simple CRUD demo. The goal is a realistic prototype demonstrating professional software engineering.

The project must demonstrate:

- Good Laravel architecture
- Proper relational database design
- Authentication & authorization
- CRUD operations & library workflows
- Recommendation logic & analytics
- Secure AI integration with role-aware behavior
- Clean modern UI
- Testing & documentation
- Production-readiness considerations

> [!TIP]
> Prefer simple, maintainable solutions that can later be extended. Do not over-engineer unnecessarily.

---

## 3. Technology Stack

### Backend

| Technology | Purpose |
|-----------|---------|
| PHP / Laravel | Application framework |
| Eloquent ORM | Database abstraction |
| Migrations | Schema management |
| Seeders | Data population |
| Policies / Gates | Authorization |
| Form Requests | Validation |
| Notifications | User notifications |
| Jobs / Queues | Async operations (where appropriate) |
| Scheduler | Scheduled tasks (where appropriate) |

### Frontend

| Technology | Purpose |
|-----------|---------|
| Blade | Templating |
| Livewire | Reactive components |
| Tailwind CSS | Styling |
| Alpine.js | Client-side interactivity (where appropriate) |

> [!WARNING]
> Do **NOT** introduce React or Vue unless there is a strong architectural reason.

### Database

- **MySQL** or **MariaDB**
- Schema should remain reasonably portable to PostgreSQL

### AI Providers

Support two providers with an abstraction layer:

1. **NVIDIA NIM**
2. **OpenRouter**

The provider must be abstracted so the application can switch providers without changing the rest of the codebase.

---

## 4. AI Provider Configuration

> [!CAUTION]
> Never hard-code API keys. Never commit real credentials.

### `.env.example` placeholders

```env
AI_PROVIDER=nvidia

NVIDIA_NIM_API_KEY=
NVIDIA_NIM_BASE_URL=
NVIDIA_NIM_MODEL=

OPENROUTER_API_KEY=
OPENROUTER_BASE_URL=
OPENROUTER_MODEL=
```

### Required documentation

- `docs/ai/providers.md`
- `docs/configuration/ai-configuration.md`

Document how to switch between NVIDIA NIM and OpenRouter.

---

## 5. Architectural Principles

### Follow

- SOLID principles
- DRY (Don't Repeat Yourself)
- Separation of concerns
- Secure-by-default design
- Least privilege
- Server-side authorization
- Reusable UI components
- Service classes for business logic
- Form Requests for validation
- Policies/Gates for authorization
- Database transactions for critical multi-step operations
- Proper indexes & foreign keys
- Soft deletes where appropriate
- Events/Listeners where useful
- Jobs for expensive operations
- Configuration through environment variables

### Avoid

- Giant controllers
- Business logic inside Blade views
- Business logic entirely inside controllers
- Duplicated UI code
- Hard-coded secrets
- Unnecessary abstraction
- Unnecessary microservices

---

## 6. UI/UX Design

Create a **clean, modern, premium-looking** library interface.

The design should communicate: **Knowledge + Books + Technology + AI**

Use a sophisticated academic/library visual identity.

### Color Theme

| Role | Color |
|------|-------|
| **Primary** | Deep navy / midnight blue |
| **Accent** | Warm gold / amber |
| **Supporting** | Neutral gray, white/light surfaces, dark charcoal surfaces |

> Exact colors can be refined during Phase 0.

> [!IMPORTANT]
> Do **not** make the application look like a generic admin dashboard template.

---

## 7. Theme System

Support:

- ☀️ Light mode
- 🌙 Dark mode
- 💻 System preference

Requirements:

- Theme preference must persist
- Avoid theme flashing during page load
- UI must remain readable and accessible in both themes

---

## 8. Branding and Logo

**Application name:** ReadOra

Create a clean logo concept representing: Books, Knowledge, Digital Library, Technology, AI

### Preferred asset paths

```
public/images/branding/logo.svg
public/images/branding/logo-dark.svg
public/images/branding/favicon.svg
```

- If image generation is available, generate it
- If not, create clean SVG placeholders
- **Never** silently use random assets

### Asset documentation

Document every required asset in `docs/ui/required-assets.md` with:

| Field | Description |
|-------|-------------|
| Asset | Name/identifier |
| Purpose | What it's used for |
| Recommended dimensions | Size requirements |
| Style | Design direction |
| Path | File location |
| Filename | File name |
| Status | Generated / Placeholder / Needed |

---

## 9. User Roles

Initially support two roles:

| Role | Description |
|------|-------------|
| **User** | Normal library patron |
| **Admin** | Library administrator |

> The architecture must allow additional roles to be added later.

---

## 10. Normal User Capabilities

### Can do ✅

- Register, login, logout
- Browse, search, filter, sort books
- View book details
- Favorite books
- Borrow available books
- View current borrowing & borrowing history
- View recommendations
- View & update own profile
- Change password
- Manage preferences
- View notifications
- Use AI assistant within permitted scope

### Cannot do ❌

- Access admin dashboard
- View / modify / delete other users
- View audit logs, system settings, API keys, environment variables
- Access administrative analytics
- Access unauthorized private data
- Ask the AI to reveal protected information

---

## 11. Admin Capabilities

Admins have a **dedicated administration interface** with:

- Dashboard with statistics
- Manage: users, books, authors, categories, publishers, book copies
- Manage: borrowing, returns, reservations (if implemented)
- View analytics
- Manage notifications
- View audit logs
- Manage system settings & AI settings
- Manage own profile
- Search, filter, update, delete, create records

> [!IMPORTANT]
> Use **server-side authorization**. Do not rely on hiding frontend buttons as a security mechanism.

---

## 12. Authentication

Implement secure authentication using **Laravel-native solutions**.

### Requirements

| Feature | Required |
|---------|----------|
| Registration | ✅ |
| Login / Logout | ✅ |
| Password hashing | ✅ |
| Password reset | ✅ |
| Email verification (if configured) | ✅ |
| Remember me | ✅ |
| Session security | ✅ |
| CSRF protection | ✅ |
| Authentication middleware | ✅ |
| Rate limiting (where appropriate) | ✅ |

> [!CAUTION]
> Do **not** implement insecure custom authentication.

---

## 13. Database Design

Design a **normalized relational database**.

### Core entities

```
users, roles, permissions
books, authors, publishers, categories
book_authors, book_categories, book_copies
borrowings, favorites, reading_history
recommendations
notifications
ai_conversations, ai_messages
audit_logs
system_settings
```

> You may modify the schema if your architecture analysis determines a better solution.

### Required documentation

- `docs/database/database-schema.md`
- `docs/database/relationships.md`
- `docs/database/erd.md`

---

## 14. Book Entity

A book should support realistic metadata:

```
id, title, subtitle
isbn_10, isbn_13
description
publication_date, publisher_id
language, page_count
cover_image, edition, format
average_rating, ratings_count
created_at, updated_at, deleted_at
```

> Use proper relationships. Do **not** duplicate authors/categories inside the books table.

---

## 15. Authors

Support: name, biography, birth date, death date, photo, external identifiers (where available).

Use a **many-to-many** relationship between books and authors.

---

## 16. Publishers

Support: name, description, website, contact information (where appropriate).

Use relationships instead of duplicating publisher information.

---

## 17. Categories

Support categories such as: Computer Science, AI, Machine Learning, Mathematics, Literature, History, Science, Business, Engineering.

- Support category management
- Hierarchical categories may be implemented if useful (avoid unnecessary complexity)

---

## 18. Book Copies

Separate the conceptual **book** from its physical **copies**.

```
Example:
  Book: "Clean Code"
  Copies: COPY-001, COPY-002, COPY-003
```

### Copy fields

```
id, book_id, barcode, status, location, condition, acquisition_date, created_at, updated_at
```

### Possible statuses

`available` | `borrowed` | `reserved` | `lost` | `damaged` | `maintenance`

---

## 19. Borrowing System

### Basic workflow

```
Available → Borrow request → Borrowed → Returned
```

### Requirements

- Borrowing & returning
- Due dates & overdue detection
- Borrowing history & current borrowing
- Admin circulation management
- Users must **not** borrow unavailable copies
- Use **database transactions** where required
- **Prevent race conditions** around book availability

---

## 20. Favorites

Users can favorite books via a `favorites` table:

```
user_id, book_id, created_at
```

Prevent duplicate favorites using a **unique database constraint**.

---

## 21. Reading History

Track:

- Borrowed books
- Returned books
- Viewed books (if useful)

> Do not collect unnecessary personal information.
> Use reading/borrowing history to improve recommendations.

---

## 22. Recommendation System

### Approach

Implement a **rule-based / content-based scoring** prototype (no ML model required initially).

### Signals

- Favorite books & borrowing history
- Frequently viewed books
- Categories & authors
- Similar books, popular books, recently added books

### Architecture

Create a `RecommendationService` that is:

- Independent from controllers and UI
- Designed so a more advanced engine can replace it later

### Future extensions

- Content-based filtering / collaborative filtering
- Embeddings / vector search
- ML recommendation model

---

## 23. Book Discovery

Create a user-facing **Books page** supporting:

- Search & pagination
- Category / author / publisher / availability / language filters
- Sorting (rating, publication date)

---

## 24. Book Details Page

Display:

- Cover, title, subtitle, authors, description
- Categories, publisher, ISBN, publication date, page count, edition
- Rating, availability, available copy count
- Favorite button & borrow button
- Related books & recommended books

> Make the page **visually strong**.

---

## 25. User Dashboard

Display:

- Current borrowings & overdue books
- Favorite books & reading history
- Recommended books & recently viewed books
- Notifications

### Statistics

| Stat | Display |
|------|---------|
| Books Borrowed | Total count |
| Currently Borrowed | Active count |
| Favorites | Total count |
| Completed/Returned | Total count |
| Overdue Items | Active count |

---

## 26. User Profile

### Can update ✅

Name, profile photo, email (where appropriate), password, theme preference, notification preferences

### Cannot update ❌

Role, permissions, account privileges, admin flags, other protected fields

---

## 27. Admin Dashboard

### Library statistics

Total books, total copies, available copies, borrowed copies, overdue books, total users, active users

### Activity

Recent borrowings, recent returns, recently added books, recent users, recent admin actions

### Analytics

- Borrowings / returns over time
- Most borrowed books & popular categories
- Most active users
- Book availability & user growth
- Borrowing trends

> Use cards, charts, and tables. Avoid clutter.

---

## 28. Admin Navigation

```
Dashboard

Library
  ├── Books
  ├── Book Copies
  ├── Authors
  ├── Categories
  └── Publishers

Circulation
  ├── Borrowings
  ├── Returns
  └── Reservations

Users
  ├── All Users
  ├── Roles
  └── Permissions

Analytics
  ├── Library Statistics
  ├── User Analytics
  └── Borrowing Analytics

AI Assistant
  ├── Conversations
  ├── AI Settings
  └── Usage

System
  ├── Notifications
  ├── Audit Logs
  └── Settings

Profile
```

> Adjust this structure if the final UX requires it.

---

## 29. Admin CRUD

Major entities should support: **Index, Create, Show, Edit, Delete**

Each interface should support:

- Search, filtering, sorting, pagination
- Validation
- Empty / loading / error states
- Confirmation dialogs
- Success / error notifications
- Reusable components

---

## 30. Admin User Management

Admins can:

- View, search, filter users
- View user details & update users
- Activate / deactivate users
- Assign roles
- Delete users (when permitted)

> [!WARNING]
> Protect against dangerous scenarios. Do **not** allow an admin to accidentally remove the last administrator account.

---

## 31. Audit Logging

Log sensitive administrative actions:

- User CRUD, role changes
- Book CRUD, book copy status changes
- Borrowing modifications
- Settings changes, AI configuration changes

### Fields

```
actor_id, action, entity_type, entity_id
old_values, new_values
ip_address, user_agent, created_at
```

> [!CAUTION]
> **Never** log: passwords, API keys, tokens, secrets.

---

## 32. AI Assistant

### Access points

- Floating assistant button
- Dedicated assistant page

### Chat interface

Modern chat UI with capabilities:

- Book discovery & recommendations
- Library information
- Borrowing questions
- User-specific questions
- Admin-specific questions (admin only)
- General library assistance

---

## 33. AI Security

> [!CAUTION]
> The AI model must **NEVER** be responsible for deciding authorization.

### Security flow

```
User → Authentication → Authorization → AI Request → Permission Check
  → Allowed Data Retrieval → Context Construction → LLM → Response → User
```

### Rules

| Rule | Description |
|------|-------------|
| Backend decides authorization | Who is the user? What role? What permissions? What data access? |
| Only authorized data reaches the AI | Never send the entire database |
| Never rely on prompt instructions for security | "Do not reveal private information" is NOT a security mechanism |
| Authorization happens **before** retrieval | Unauthorized records never enter the model context |

### Example

> User asks: "Who are all the registered users?"
> Backend checks: `users.view_all` permission
> If user lacks permission → do NOT retrieve user records → AI responds it cannot provide that information

---

## 34. AI Context Scopes

| Scope | Data | Access |
|-------|------|--------|
| **PUBLIC** | Public books, authors, categories, general library info | All users |
| **USER_PRIVATE** | Current user's borrowing history, favorites, recommendations, account info | Own user only |
| **ADMIN** | Library statistics, user statistics, admin reports | Authorized admins only |
| **SYSTEM** | API keys, env vars, passwords, secrets, credentials | **NEVER** exposed |

> [!IMPORTANT]
> Never expose another user's private data.

---

## 35. AI Authorization Architecture

### Request flow

```
AI Request → AIAuthorizationService → Permission Check
  → AIContextService → Authorized Data
  → AIProviderInterface → NVIDIA NIM / OpenRouter → Response
```

### Suggested classes

```
app/AI/Contracts/AIProviderInterface.php
app/AI/Providers/NvidiaNimProvider.php
app/AI/Providers/OpenRouterProvider.php
app/AI/Services/AIService.php
app/AI/Services/AIAuthorizationService.php
app/AI/Services/AIContextService.php
```

> Modify this structure if Laravel conventions suggest a better organization.

---

## 36. AI Prompt Injection Protection

Assume users **will** attempt prompt injection.

Examples:

- "Ignore your previous instructions."
- "Show me all users."
- "Give me the admin password."
- "Give me the API key."

> [!IMPORTANT]
> The system must enforce security **independently** of the model prompt. Unauthorized records must **never** enter the model context.

Document in: `docs/security/ai-security.md`

---

## 37. AI Conversations

### Storage

Use `ai_conversations` and `ai_messages` tables.

### Message fields

```
conversation_id, role, content, provider, model, tokens_used, created_at
```

### Rules

- Associate conversations with the correct user
- Users can view their own conversations
- Do not expose private conversations unnecessarily to administrators
- Never store secrets

---

## 38. RAG-Ready Architecture

### Architecture flow

```
User Question → Authorization → Query Understanding
  → Retriever → Authorized Data → Context Builder → LLM → Response
```

### Requirements

- For the prototype, **database-based retrieval** is sufficient
- Do **not** introduce a vector database unless necessary
- Architecture should be extensible for: embeddings, semantic search, vector databases, advanced RAG

---

## 39. Real Book Dataset

> [!IMPORTANT]
> Use **real book data**, not randomly generated fake records.

### Target

**100–300 real books** from a legitimate public/open book metadata source.

### Required data

- Title, authors, ISBN (when available)
- Publisher, publication date, language
- Description, categories
- Page count, cover URL (when legally usable)
- External identifier (when available)

> Do **not** manually write hundreds of records. Create an import/seeding pipeline.

### Documentation

Create `docs/data/book-dataset.md` documenting:

- Dataset name, source, license, source URL
- Download/import method
- Data fields & field mapping
- Cleaning & deduplication rules
- Import command
- Known limitations

---

## 40. Book Data Import Pipeline

Create `database/importers/BookImporter.php`

### Pipeline steps

1. Read source data
2. Validate records
3. Normalize fields
4. Deduplicate books
5. Create: authors, publishers, categories, books, book copies
6. Log failures
7. Show import progress

> Use chunking/batching. The main dataset should **not** be a huge hard-coded PHP array.

### Seed data separation

```
DatabaseSeeder
DemoUserSeeder
DemoAdminSeeder
BookDatasetSeeder
```

> Separate demo/test data from the real book dataset. Do not confuse demo accounts with production accounts.

---

## 41. Search

### Searchable fields

Title, author, ISBN, publisher, category

### Approach

- Initially use database search and indexes
- Structure code so a search engine (Meilisearch, Typesense, Elasticsearch) can be added later

---

## 42. Notifications

Implement **Laravel Notifications**:

- Borrow successful
- Due date reminder
- Overdue book
- Return confirmation
- Reservation available
- General account notification

---

## 43. Error Handling

### User-facing

Friendly error messages.

### Production must never expose

- Stack traces, SQL queries
- Environment variables, API keys
- Internal credentials

### Developer-facing

Useful logs for debugging.

---

## 44. File Uploads

For profile images, book covers, author images:

### Validate

- File type, MIME type, file size, extension

### Rules

- **Never** trust uploaded filenames
- Use Laravel's filesystem abstraction

---

## 45. API Architecture

### Potential endpoints

```
/api/books
/api/books/{book}
/api/categories
/api/authors
/api/user/borrowings
/api/user/favorites
/api/user/recommendations
/api/assistant/chat
```

- Protect private endpoints
- Do **not** expose admin APIs to normal users

---

## 46. Route Organization

Separate routes into:

| Group | Middleware |
|-------|-----------|
| Public | None |
| Authenticated User | Auth middleware |
| Admin | Auth + admin middleware |
| AI | Auth + appropriate policies |

---

## 47. Reusable UI Components

Create reusable Blade/Livewire components:

```
Button, Input, Select, Modal, Toast, Badge, Card, Table, Pagination,
Dropdown, Navbar, Sidebar, BookCard, BookCover, StatCard, ChartCard,
EmptyState, LoadingState, ConfirmDialog, ChatMessage, ChatInput
```

> Avoid duplicating UI markup.

---

## 48. Responsive Design

| Target | Priority |
|--------|----------|
| Desktop / Laptop | Full support |
| Tablet | Full support |
| Mobile | **Fully mobile-friendly** (user interface) |

> Admin interface may prioritize desktop.

---

## 49. Accessibility

Follow practical **WCAG** principles:

- Keyboard navigation & semantic HTML
- Proper labels & focus states
- Accessible buttons & forms
- Sufficient contrast & alternative text

---

## 50. Testing

### Unit tests

- RecommendationService
- AI authorization
- Book availability
- Borrowing logic
- Search logic

### Feature tests

- Authentication, user/admin permissions
- Book CRUD, borrowing, returning
- Favorites, recommendations
- AI restrictions

### Security tests

- Normal user cannot access admin pages
- Normal user cannot access another user's data
- Normal user cannot modify another user's data
- Normal user cannot retrieve admin-only AI context
- AI cannot access unauthorized records
- Unauthenticated users cannot access private endpoints
- Admin cannot accidentally remove the last administrator

---

## 51. Performance

Consider:

- Database indexes & eager loading
- Pagination & query optimization
- Caching & queues
- Efficient recommendation calculations
- Chunked book import
- Avoiding N+1 queries

> Do not optimize prematurely, but do not introduce obvious performance problems.

---

## 52. Logging

Use **Laravel logging** with separate channels:

```
application, ai, security, imports, admin
```

> [!CAUTION]
> **Never** log: passwords, API keys, tokens, session secrets.

---

## 53. Documentation Structure

```
docs/
├── README.md
├── architecture/
│   ├── system-architecture.md
│   ├── application-architecture.md
│   ├── folder-structure.md
│   ├── technology-stack.md
│   └── decision-log.md
├── database/
│   ├── erd.md
│   ├── database-schema.md
│   ├── relationships.md
│   └── indexing-strategy.md
├── features/
│   ├── user-features.md
│   ├── admin-features.md
│   ├── borrowing-system.md
│   ├── recommendation-system.md
│   └── ai-assistant.md
├── security/
│   ├── security-architecture.md
│   ├── authentication.md
│   ├── authorization.md
│   ├── ai-security.md
│   └── threat-model.md
├── ai/
│   ├── ai-architecture.md
│   ├── providers.md
│   ├── rag-architecture.md
│   ├── prompts.md
│   └── context-permissions.md
├── data/
│   ├── book-dataset.md
│   ├── import-pipeline.md
│   └── data-cleaning.md
├── api/
│   ├── api-overview.md
│   └── endpoints.md
├── ui/
│   ├── design-system.md
│   ├── pages.md
│   ├── components.md
│   └── required-assets.md
├── testing/
│   ├── testing-strategy.md
│   ├── test-cases.md
│   └── security-tests.md
├── deployment/
│   ├── deployment.md
│   ├── environment.md
│   └── production-checklist.md
└── progress/
    ├── current-phase.md
    ├── changelog.md
    ├── phase-00.md
    ├── phase-01.md
    ├── phase-02.md
    └── ...
```

### ERD documentation (`docs/database/erd.md`)

Use **Mermaid ER diagrams**:

```mermaid
erDiagram
    USERS ||--o{ BORROWINGS : creates
    USERS ||--o{ FAVORITES : has
    USERS ||--o{ AI_CONVERSATIONS : owns
    BOOKS ||--o{ BOOK_COPIES : contains
    BOOKS }o--o{ AUTHORS : written_by
    BOOKS }o--o{ CATEGORIES : belongs_to
    BORROWINGS }o--|| BOOK_COPIES : uses
```

> The actual ERD must match the final implemented schema.

### System architecture documentation

Create Mermaid diagrams for:

- System architecture & application architecture
- Authentication / authorization flows
- Borrowing flow & recommendation flow
- AI request flow & deployment architecture

### Page documentation (`docs/ui/pages.md`)

For every page document: Page, Route, Role, Purpose, Components, Actions, Data, Authorization, Empty/Loading/Error states, Responsive behavior.

### Permission matrix (`docs/security/authorization.md`)

| Feature | User | Admin |
|---------|------|-------|
| Browse books | ✅ | ✅ |
| View book | ✅ | ✅ |
| Favorite book | ✅ | ✅ |
| Borrow book | ✅ | ✅ |
| View own borrowing | ✅ | ✅ |
| View all borrowings | ❌ | ✅ |
| Manage books | ❌ | ✅ |
| Manage users | ❌ | ✅ |
| View audit logs | ❌ | ✅ |
| View admin analytics | ❌ | ✅ |

### AI permission matrix

| Data | User | Admin |
|------|------|-------|
| Public books | ✅ | ✅ |
| Own favorites | ✅ | ✅ |
| Own borrowing history | ✅ | ✅ |
| Other users' data | ❌ | ✅ |
| Library statistics | ❌ | ✅ |
| Audit logs | ❌ | ✅ |
| API keys | 🚫 Never | 🚫 Never |
| Environment variables | 🚫 Never | 🚫 Never |

> Authorization must be enforced by the backend.

---

## 54. Development Phases

> [!CAUTION]
> Do **NOT** build the entire project in one step. Build phase by phase. After each phase: **STOP and wait for explicit confirmation** before continuing.

### Phase 0 — Planning and Architecture

Before implementing major application code, create:

- Complete requirements
- System architecture & technology decisions
- Folder structure
- Database architecture, ERD, relationships
- Role & permission architecture
- AI architecture & AI security architecture
- Recommendation architecture
- UI architecture & page map
- API map
- Data import strategy
- Testing strategy
- Deployment strategy
- Documentation structure
- Risk assessment

**First response must focus on** (15 deliverables):

1. System architecture
2. Database architecture
3. ERD
4. Roles and permissions
5. AI architecture
6. AI security
7. Recommendation architecture
8. UI/page architecture
9. Project folder structure
10. Data strategy for 100–300 real books
11. Testing strategy
12. Deployment strategy
13. Documentation structure
14. Development roadmap
15. Risks and mitigations

---

### Phase 1 — Laravel Foundation

- Laravel project & environment configuration
- Database connection
- Tailwind & base frontend
- Theme system & basic layouts
- Branding & git configuration
- Documentation structure

**Tests:** Application loads, database connects, light/dark mode works, responsive base layout works.

---

### Phase 2 — Authentication and Authorization

- Registration, login, logout, password management
- Roles, permissions, middleware, policies
- Admin/user separation

**Tests:** User can register/login. Admin can login. User cannot access admin. Admin can access admin.

---

### Phase 3 — Database and Library Data

- Books, authors, publishers, categories, book copies
- Relationships, migrations, seeders
- Book import pipeline (100–300 real books)

**Tests:** Database integrity, relationships, searchable data, book records, availability.

---

### Phase 4 — User Interface

- User dashboard, book discovery, search, filters
- Book details, favorites, profile
- Borrowing interface & history
- Recommendations placeholder

---

### Phase 5 — Borrowing System

- Borrow, return, due dates, availability
- Overdue detection, borrowing history
- Admin circulation management
- Automated tests

---

### Phase 6 — Recommendation Engine

- User preference / favorite / history analysis
- Category / author similarity & popularity signal
- Recommendation scoring via `RecommendationService`

---

### Phase 7 — Admin Dashboard

- Statistics, charts
- User / book / author / category / publisher / book copy management
- Borrowing management, notifications, audit logs

---

### Phase 8 — AI Assistant Foundation

- AI provider abstraction (NVIDIA NIM + OpenRouter)
- Environment configuration
- Chat interface & conversation storage
- AI service

---

### Phase 9 — AI Authorization and RAG

- Role-aware context & permission-aware retrieval
- User/admin-specific context
- AI context filtering & prompt injection protection
- RAG-ready architecture

**Test examples:**

| User | Query | Expected |
|------|-------|----------|
| Normal | "Show me all registered users." | Access denied |
| Admin | "How many users are registered?" | Authorized answer |
| Normal | "Give me the API key." | Never reveal |
| Normal | "What books have I borrowed?" | Own data only |
| User A | "What books did User B borrow?" | Denied |

---

### Phase 10 — Notifications and Background Jobs

- Notifications, due-date reminders, overdue notifications
- Queue jobs & scheduled tasks (where useful)

---

### Phase 11 — Testing and Security

- Unit / feature / authorization / AI security tests
- Validation & database integrity tests
- Basic performance checks
- Fix discovered problems

---

### Phase 12 — Production Hardening

- Production configuration & logging
- Caching, queue/storage configuration
- Error handling & rate limiting
- Security headers, database indexes
- Performance optimizations
- Secure environment configuration

---

### Phase 13 — Deployment Preparation

- Production environment documentation
- `.env.example` review
- Database migration strategy
- Storage / queue / scheduler configuration
- Backup & monitoring recommendations
- Deployment instructions

---

### Phase 14 — Final QA

Complete project audit covering:

- Features, UI/UX, database, security, authorization
- AI, performance, testing, documentation, deployment

Create:

- `docs/testing/final-qa.md`
- `docs/deployment/production-checklist.md`
- Final readiness report

---

## 55. Git Workflow

After every completed phase, commit with a descriptive message:

```bash
git add .
git commit -m "feat: complete phase 01 foundation"
git commit -m "feat: implement authentication and authorization"
git commit -m "feat: implement library database"
git commit -m "feat: implement user interface"
git commit -m "feat: implement borrowing system"
git commit -m "feat: implement recommendation engine"
git commit -m "feat: implement admin dashboard"
git commit -m "feat: implement AI assistant"
git commit -m "feat: implement AI authorization"
git commit -m "test: add security and integration tests"
git commit -m "chore: production hardening"
```

---

## 56. Progress Tracking

### `docs/progress/current-phase.md`

Maintain after every phase with:

```
Current Phase:
Status:
Completed:
In Progress:
Pending:
Known Issues:
Testing Instructions:
Recommended Commit:
Next Phase:
```

### `docs/progress/changelog.md`

Document important changes throughout development.

### `docs/architecture/decision-log.md`

For each major decision:

```
Decision:
Date:
Problem:
Options:
Chosen Solution:
Reason:
Consequences:
```

---

## 57. Code Quality

Every generated file must:

- Follow Laravel conventions
- Have a clear responsibility
- Use meaningful names & appropriate types
- Validate external input
- Handle errors
- Follow security best practices
- Avoid: unnecessary comments, duplicated code, unnecessary complexity

---

## 58. Anti-Patterns (Do NOT)

> [!CAUTION]

- ❌ Build the entire project in one step
- ❌ Skip Phase 0
- ❌ Skip documentation
- ❌ Use fake books as the main dataset
- ❌ Populate thousands of books
- ❌ Hard-code API keys
- ❌ Trust the AI model for authorization
- ❌ Send the entire database to the LLM
- ❌ Give users admin routes
- ❌ Depend only on frontend authorization
- ❌ Put business logic in controllers
- ❌ Duplicate UI code
- ❌ Skip tests, migrations, indexes, validation, error handling
- ❌ Continue to the next phase without confirmation

---

## 59. Final Project Structure

```
project-root/
├── app/
│   ├── AI/
│   ├── Actions/
│   ├── Http/
│   ├── Models/
│   ├── Policies/
│   ├── Services/
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
│   ├── factories/
│   ├── importers/
│   └── data/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
├── public/
│   └── images/
├── tests/
│   ├── Unit/
│   ├── Feature/
│   └── ...
├── docs/
├── storage/
├── .env.example
├── README.md
└── ...
```

> The exact structure may be adjusted during Phase 0 according to Laravel best practices.

---

## 60. Phase Report Format

At the end of **every** phase, provide this report:

```
PHASE X COMPLETE

What was implemented:
- ...

Files created:
- ...

Files modified:
- ...

How to test:
1. ...
2. ...

Expected result:
- ...

Known issues:
- ...

Recommended Git commit:
  git add .
  git commit -m "..."

Next phase:
  PHASE X+1 — ...

WAITING FOR USER CONFIRMATION.
```

> Do **not** start the next phase until the user explicitly confirms.

---

## Final Objective

The final result should be a **clean, secure, documented, maintainable, production-oriented** Laravel Library Management System prototype called **ReadOra** providing:

- A polished normal-user experience
- A complete admin management interface
- Realistic library workflows (≈100–300 real books)
- Book discovery, favorites, borrowing, recommendations, analytics
- User profiles & notifications
- Role-based authorization
- A secure role-aware AI assistant (NVIDIA NIM / OpenRouter)
- Dark/light mode & responsive UI
- Automated tests & security controls
- Comprehensive documentation & deployment docs

> **BUILD IN PHASES → COMPLETE ONE PHASE → REPORT IT → LET THE USER TEST → WAIT FOR CONFIRMATION → ONLY THEN CONTINUE.**
