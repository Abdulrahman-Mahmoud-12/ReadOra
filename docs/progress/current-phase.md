# Current Phase

Current Phase: Phase 12 — Security, Authorization, Rate Limiting & REST API Tokens

Status: Complete, ready for user confirmation before the next phase.

Completed:

- Configured `OpenRouter` as the primary AI provider in `.env` and `config/services.php` using the specified API credentials.
- Created `App\Services\AiService` with library catalog context grounding, OpenRouter chat completion execution, structured book insights generator, and graceful fallback handling.
- Built `App\Http\Controllers\AiAssistantController` for the AI Librarian chat endpoint and asynchronous book insights generation.
- Created interactive AI Assistant chat page `resources/views/assistant/index.blade.php` with real-time response stream, prompt chips, typing indicator, and catalog highlights.
- Added "ReadOra AI Deep Book Insights" on-demand widget on `resources/views/books/show.blade.php`.
- Integrated "AI Assistant" link with star badge in the top navigation bar.
- Created automated test suite `tests/Feature/AiAssistantTest.php` with 4 test scenarios covering UI rendering, chat completions, book insights, and API fallback (100% passing).
- Formatted all code with Laravel Pint.
- Protected AI page, chat, and book insights routes with authentication and
	named rate limits.
- Added hashed personal API tokens with six-month expiry, bearer middleware,
	token revocation, and the protected `/api/me` endpoint.
- Added security tests for AI authentication, token issuance, hashing, invalid
	tokens, and revocation.

In Progress:

- None.

Pending:

- User confirmation before starting Phase 13.

Known Issues:

- API tokens are intentionally minimal and do not yet support named abilities.
- Rotate credentials exposed during local development.

Testing Instructions:

1. Run `vendor/bin/phpunit tests/Feature/AiAssistantTest.php tests/Feature/SecurityPhaseTest.php`.
2. Log in and visit `/assistant` to chat with the ReadOra AI Librarian.
3. Issue a token with `POST /api-tokens`, then call `GET /api/me` with its bearer token.

Recommended Commit:

```bash
git add .
git commit -m "feat: harden AI access and add API bearer tokens"
```

Next Phase: Phase 13 — Production Hardening
