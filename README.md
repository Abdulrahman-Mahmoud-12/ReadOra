# ReadOra

ReadOra is a modern library management platform built with Laravel and designed for both public catalog browsing and institutional operations. It combines traditional library workflows with a role-aware AI assistant to help users discover books, manage borrowing activity, and support administrative decision-making.

## Overview

ReadOra enables a complete digital library experience:

- Book discovery with catalog browsing, search, filtering, and metadata-rich detail pages
- Patron features for account management, favorites, reading lists, reviews, and borrowing history
- Secure circulation workflows for checkout, return, renewal, and availability enforcement
- Administrative controls for books, copies, users, reports, reviews, and audit logs
- AI-assisted recommendations and librarian-style insights through local or hosted model providers

The application is designed for a library environment where public access, staff workflows, and protected operational data must remain clearly separated.

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL
- Blade templates
- Tailwind CSS 4
- Vite
- PHPUnit
- AI providers: Ollama, OpenRouter, or NVIDIA NIM

## Core Features

### Public catalog experience

- Browse books and search by title, metadata, and category
- View detailed book information with related titles and reading context
- Access public reading lists and community-facing library content

### Patron experience

- User registration and authentication
- Profile management and borrowing history
- Favorites and custom reading lists
- Book reviews and ratings
- Personal API token support for machine-readable endpoints

### Administration

- Secure admin dashboard and protected admin routes
- Inventory and copy management
- User and role administration
- Review moderation and audit visibility
- CSV export for books, copies, circulation, and patron reports

### AI librarian

- AI-powered assistant for library queries and recommendations
- Model support for local inference with Ollama and hosted inference via OpenRouter or NVIDIA NIM
- Controlled access so public users and admins see different levels of operational context
- Guardrails to avoid exposing sensitive tokens, secrets, or raw audit content

## Project Structure

```text
app/                Application logic and controllers
config/             Laravel configuration files
database/           Migrations, factories, seeders, and import data
docs/               Architecture, AI, security, deployment, and QA docs
public/             Web entrypoint and built frontend assets
resources/          Views, CSS, and frontend assets
routes/             Web and API routes
storage/            Logs, cache, and generated files
tests/              Feature and unit tests
```

## Requirements

Before starting, make sure you have:

- PHP 8.3 or newer
- Composer
- Node.js and npm
- MySQL running locally
- An AI provider configuration if you plan to use the assistant

## Quick Start

From the project root, run:

```powershell
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

Create the MySQL database referenced in your `.env` file, then run:

```powershell
php artisan migrate:fresh --seed
php artisan optimize:clear
php artisan serve --host=0.0.0.0 --port=8000
```

Open the app at:

```text
http://127.0.0.1:8000
```

> If you are using a local MySQL stack such as Laragon or XAMPP, make sure the database service is running before visiting database-backed pages. A stopped MySQL instance can trigger slow requests or 500-level errors.

## Environment Configuration

The project ships with a template at `.env.example`. Copy it to `.env` and configure your local environment values before running the application.

Key settings include:

- `APP_NAME`, `APP_URL`, `APP_ENV`
- `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- AI provider credentials and model settings

## AI Provider Setup

Set `AI_PROVIDER` to one of the supported options:

- `ollama`
- `openrouter`
- `nvidia`

Example configuration:

```env
AI_PROVIDER=ollama
OLLAMA_API_KEY=
OLLAMA_BASE_URL=http://127.0.0.1:11434/v1/chat/completions
OLLAMA_MODEL=qwen2.5:7b
OLLAMA_MAX_TOKENS=384
```

### Ollama

Make sure Ollama is installed and running locally.

```powershell
ollama list
ollama pull qwen2.5:7b
```

### OpenRouter

```env
AI_PROVIDER=openrouter
OPENROUTER_API_KEY=your-key
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1/chat/completions
OPENROUTER_MODEL=your-model
```

### NVIDIA NIM

```env
AI_PROVIDER=nvidia
NVIDIA_NIM_API_KEY=your-key
NVIDIA_NIM_BASE_URL=https://integrate.api.nvidia.com/v1/chat/completions
NVIDIA_NIM_MODEL=your-model
```

Security note: the assistant intentionally limits what it sees. Public users receive only catalog-facing context, and admin users receive only aggregate operational metadata. Secrets, passwords, and raw audit data are not sent to the model.

## Roles and Access

The application uses role-aware access control:

- Guests can browse public catalog pages
- Authenticated patrons can access personalized library features and the AI assistant
- Administrators can access protected management routes under `/admin` and operational reporting tools

Personal bearer tokens can be generated through the app and used with API requests such as:

```http
Authorization: Bearer readora_...
```

## Testing and Quality Checks

Run the project checks with:

```powershell
vendor/bin/phpunit --colors=never
vendor/bin/pint --dirty --format agent
npm run build
composer audit
```

The test suite includes AI-related coverage that does not require live external network access.

## Development Notes

- Use `php artisan serve` for local development
- Use `npm run dev` if you want Vite in watch mode
- Keep `.env` outside of version control
- Prefer `php artisan optimize:clear` after changing configuration or AI settings

## Deployment

For production deployment:

- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Use HTTPS and the correct `APP_URL`
- Keep secrets in environment-managed configuration
- Serve only the `public/` directory
- Configure database backups, scheduling, logging, and monitoring

## Documentation

Additional project documentation is available in the docs folder:

- `docs/architecture/` — architecture and system design
- `docs/ai/` — AI integration and guardrails
- `docs/configuration/` — environment and app configuration
- `docs/features/` — feature specifications
- `docs/security/` — authentication and security notes
- `docs/api/` — API usage information
- `docs/testing/` — QA and test strategy
- `docs/deployment/` — deployment guidance and checklists
- `docs/progress/` — project milestones and history

## License

This project is licensed under the MIT License.
