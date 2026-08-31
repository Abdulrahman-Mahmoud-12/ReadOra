# AI Context Permissions

The current AI context scope is public catalog data only: book titles, authors,
categories, ratings, and available category names. AI routes require an
authenticated user before context construction or provider calls.

The service never sends passwords, API keys, environment values, audit logs, or
another user's private data to a provider. User-private and admin context are
reserved for a future permission-aware context service.
