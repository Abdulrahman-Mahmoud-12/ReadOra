# Import/Export, Reports & Circulation Analytics

ReadOra provides library administrators with a circulation reporting dashboard and bulk CSV streaming export capabilities.

---

## 1. Circulation Analytics & Reporting Dashboard (`/admin/reports`)

- **Circulation KPIs**:
  - Total Lifetime Loans Count
  - Active Borrowings (currently checked out by patrons)
  - Overdue Loans Count (exceeded due date threshold)
  - Monthly Activity Volume (loans issued during the current calendar month)
- **Top Borrowed Titles Ranking**: Identifies the most popular works in circulation with aggregate loan counts.
- **Patron Activity Leaderboard**: Displays patrons ranked by lifetime and active borrowing engagement.
- **Overdue Attention Desk**: Summarizes urgent overdue loans with due dates and overdue duration.
- **Category Popularity Breakdown**: Visualizes distribution of books across collection classifications.

---

## 2. CSV Data Export Engine

All export endpoints generate standard RFC 4180 CSV files with **UTF-8 BOM** headers for maximum compatibility across Microsoft Excel, Google Sheets, and data science pipelines:

| Export Endpoint | Output File Pattern | Key Columns Included |
|---|---|---|
| `/admin/reports/export/books` | `readora_books_catalog_*.csv` | ID, Title, Subtitle, Authors, Categories, Publisher, ISBN-10, ISBN-13, Year, Language, Pages, Rating, Ratings Count, Total Copies, Available Copies |
| `/admin/reports/export/circulations` | `readora_circulations_*.csv` | Loan ID, Patron Name, Patron Email, Book Title, Copy Barcode, Location, Borrowed Date, Due Date, Returned Date, Status, Is Overdue |
| `/admin/reports/export/patrons` | `readora_patrons_*.csv` | User ID, Name, Email, Role, Active Borrowings, Total Lifetime Loans, Favorites Count, Reviews Written, Member Since |
| `/admin/reports/export/copies` | `readora_shelf_inventory_*.csv` | Copy ID, Barcode, Book Title, ISBN 13, Location, Condition, Status, Acquired Date |
