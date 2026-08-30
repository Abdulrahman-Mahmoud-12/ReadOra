# Architecture Decision Log

## Decision 001: Use a Laravel Monolith

Date: 2026-08-30

Problem: ReadOra needs user workflows, admin workflows, authorization, database operations, notifications, and AI integration without unnecessary distributed complexity.

Options:

- Laravel monolith.
- Separate backend API and frontend SPA.
- Microservices.

Chosen solution: Laravel monolith with Blade, Livewire, Tailwind CSS, and Alpine.js.

Reason: This matches the project instructions, keeps development focused, supports rapid iteration, and is appropriate for a production-oriented academic prototype.

Consequences: The app remains simpler to deploy and test. Strict separation must be maintained through route organization, layouts, policies, services, and folder structure.

## Decision 002: Use Role Field First, Policies for Enforcement

Date: 2026-08-30

Problem: The app needs user/admin access control without overbuilding a permission system too early.

Options:

- Simple `role` field on users.
- Full permissions package.
- Custom roles and permissions tables from day one.

Chosen solution: Start with a `role` field and enforce access through middleware, policies, and gates.

Reason: The project currently has two roles. Laravel policies provide strong server-side enforcement while keeping the data model understandable.

Consequences: If more roles are needed later, the role field can evolve into dedicated tables or a permission package.

## Decision 003: Keep AI Authorization Outside the Model

Date: 2026-08-30

Problem: The AI assistant needs role-aware answers without exposing unauthorized data.

Options:

- Ask the model to decide authorization.
- Authorize data before context construction.
- Send broad context and filter the response.

Chosen solution: Authorize and scope all data in Laravel before sending context to the provider.

Reason: The model is not a security boundary. Backend policies and allowlisted context builders are auditable and testable.

Consequences: AI features require explicit context methods and security tests, but the assistant remains safer and easier to reason about.

## Decision 004: Start Recommendations with Explainable Scoring

Date: 2026-08-30

Problem: ReadOra needs recommendations that are realistic but maintainable.

Options:

- LLM-only recommendations.
- Vector search from the start.
- Rule/scoring based recommendations using local signals.

Chosen solution: Use a `RecommendationService` with weighted local signals first.

Reason: Favorites, borrowing history, categories, authors, popularity, and availability are enough for a useful prototype and can be tested deterministically.

Consequences: Recommendations are explainable and cheap. RAG/vector capabilities can be added later without replacing the user-facing contract.
