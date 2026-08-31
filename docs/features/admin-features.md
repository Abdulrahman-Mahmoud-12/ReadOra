# Administration Suite & Operations Desk

The ReadOra Administration Portal provides a complete management suite for library staff, circulation managers, and catalog administrators.

---

## 1. Role-Based Access Control & Security

All administrative routes are protected by the `['auth', 'admin']` middleware pipeline:
- Only accounts with `role = 'admin'` can access `/admin/*` routes.
- Unauthorized patrons or guests receive a `403 Forbidden` response.
- **Safety Safeguards**:
  - The system blocks demoting or deleting the last remaining administrator account.
  - Users with active, unreturned book loans cannot be deleted until their physical copies are checked in.

---

## 2. Real-Time Library Analytics & Dashboard (`/admin`)

- **Catalog Total**: Live count of all unique bibliographic titles.
- **Physical Inventory**: Total physical copies, available count, and in-loan copies.
- **Circulation Health**: Real-time counter of active loans and overdue loans.
- **Patron Engagement**: Total registered users and active borrowers.
- **Feeds**: Real-time recent circulation transactions and audit trail stream.

---

## 3. Circulation Desk (`/admin/circulations`)

- Filter loans by status: `All`, `Active`, `Overdue`, `Returned`.
- Search across patron name, patron email, book title, and barcode.
- **Librarian Actions**:
  - **Check In**: Instantly marks the loan as `returned`, records the return timestamp, logs a reading history event, and restores the physical copy to `available`.
  - **Renewal Override**: Extends the due date by 14 days and logs the renewal.

---

## 4. Catalog Management (`/admin/books`, `/admin/copies`, etc.)

- **Books CRUD (`/admin/books`)**:
  - Create, edit, and delete catalog records.
  - Multi-select authors and categories.
  - Automatic physical inventory copy generation upon creation.
  - Deletion blocked if any copies of the book are actively checked out.
- **Copies Manager (`/admin/copies`)**:
  - Add physical copies with unique barcodes, shelf locations, and conditions (`new`, `good`, `fair`, `damaged`, `maintenance`).
  - Track active loan holder and shelf status.
- **Authors, Categories, and Publishers CRUD**:
  - Full management with dependency protection (cannot delete categories, authors, or publishers with active catalog books).

---

## 5. Audit Logging (`/admin/audit-logs`)

- Powered by `App\Services\AuditLogger` recording to the `audit_logs` table.
- Logs actor ID, action name (e.g. `book.created`, `user.role_updated`, `circulation.returned`), entity type/ID, before/after JSON payloads, client IP address, and user agent.
- Sensitive fields (passwords, tokens, credentials) are automatically filtered and redacted.
