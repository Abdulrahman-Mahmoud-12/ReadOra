# Advanced Search, Multi-Filter & Facets

ReadOra provides a responsive, multi-facet discovery engine on `/books` to allow patrons and librarians to filter large catalog collections across dimensions.

---

## 1. Multi-Field Keyword Search

The `Book::scopeSearch($term)` method performs keyword lookups across:
- `title` & `subtitle`
- `description`
- `isbn_10` & `isbn_13`
- Linked `authors.name` (many-to-many relationship)
- Linked `categories.name` (many-to-many relationship)
- Linked `publisher.name` (belongs-to relationship)

---

## 2. Faceted Filters & Parameters

| Parameter | Type | Description | Example |
|---|---|---|---|
| `search` | String | Full-text query across title, author, isbn, etc. | `search=craftsmanship` |
| `availability` | String | Restricts to books with at least 1 shelf copy. | `availability=available` |
| `min_rating` | Float | Filter by minimum average patron rating. | `min_rating=4.5` |
| `era` | String | Publication era / decade range. | `era=2020s`, `era=classic` |
| `languages[]` | Array | 2-letter ISO language codes. | `languages[]=en&languages[]=ar` |
| `categories[]` | Array | Category slugs with live book counts. | `categories[]=computer-science` |
| `publishers[]` | Array | Publisher slugs with book counts. | `publishers[]=oreilly` |
| `sort` | String | Ordering mode: `rating_desc`, `popular`, `year_desc`, `year_asc`, `title_asc`, `title_desc`. | `sort=title_asc` |

---

## 3. UI Layout & User Experience

- **Sticky Facet Sidebar**: Allows patrons on desktop and mobile to select multi-select filters, toggle shelf availability, or pick minimum rating thresholds.
- **Active Filter Chips**: Every active filter renders as a dismissible chip with `×` remove link and a global "Reset All" button.
- **Query Feedback**: Prominently highlights the active search term and item count.
- **Eager Loading**: All queries eager load `publisher`, `authors`, `categories`, and `copies` to prevent N+1 query bottlenecks.
