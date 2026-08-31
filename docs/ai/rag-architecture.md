# RAG Architecture

The prototype uses database-backed retrieval: the AI service queries a small
set of top-rated books and all catalog categories, then builds a system context
before calling the model. This keeps the first implementation simple and
auditable.

Future retrieval should remain behind a context/retriever boundary so indexed
search, embeddings, or a vector store can replace the catalog queries without
moving authorization or provider code into controllers.
