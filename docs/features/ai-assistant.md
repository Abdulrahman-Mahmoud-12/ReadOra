# ReadOra AI Virtual Librarian & Deep Insights

ReadOra provides an intelligent AI Librarian assistant and book analysis system powered by **OpenRouter LLM**.

---

## 1. Provider & Configuration

- **Provider**: OpenRouter (`AI_PROVIDER=openrouter`)
- **Base URL**: `https://openrouter.ai/api/v1`
- **Model**: `qwen/qwen3.8-flash` (or any configurable OpenRouter model)
- **API Key**: Configured via `OPENROUTER_API_KEY` in `.env` and mapped in `config/services.php`.

---

## 2. AI Virtual Librarian (`/assistant`)

- **Interactive Conversational UI**: Full chat interface where patrons can ask about book recommendations, author backgrounds, reading advice, and conceptual explanations.
- **Catalog Grounding**: Injects live library catalog context (genres, top-rated books, authors) directly into the system prompt for accurate recommendations.
- **Suggested Quick Prompts**: Quick prompt chips for common queries.
- **Typing Indicator & Markdown Formatting**: Renders structured responses with bold headings, bullet points, and paragraphs.
- **Graceful Offline Fallback**: Handles network errors or rate limits gracefully without failing page execution.

---

## 3. Asynchronous Book Insights (`/books/{book}/ai-insights`)

- **On-Demand Synopsis & Study Takeaways**: Patrons can click **"Generate AI Insights"** on any book details page (`/books/{slug}`).
- **Structured Synthesis**:
  1. *Core Thesis & Literary Synopsis*
  2. *Key Takeaways & Conceptual Highlights*
  3. *Ideal Reader & Prerequisites*
  4. *Discussion & Study Reflection Questions*
