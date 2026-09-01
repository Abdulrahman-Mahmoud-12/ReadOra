# AI Context Permissions

Normal users receive public catalog data only: book titles, authors,
categories, ratings, and available category names. Authenticated administrators
also receive aggregate operational data: catalog and inventory totals, user
counts, loan counts, overdue counts, and limited recent circulation summaries.
AI routes require an authenticated user before context construction or provider
calls.

The service never sends passwords, API keys, environment values, bearer tokens,
raw audit logs, or another user's private data to a provider. Administrative
context is selected server-side from the authenticated admin role and is not
controlled by the model or by user prompt text.
