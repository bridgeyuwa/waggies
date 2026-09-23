# Waggies

Waggies is a Laravel application for a pet-care centre in Abuja. It contains the public Waggies website, database-backed editorial content, public request journeys, catalogue and gallery management, and a Filament administration panel for operational and clinical governance workflows.

## Stack

- Laravel 13.33
- PHP 8.5
- Blade and Alpine
- Livewire 4 and Filament 5 for administration
- Tailwind CSS 4 and Vite
- PostgreSQL 18.6 with pgvector 0.8.1
- Docker Compose for local PostgreSQL

## Local database

Start the local database with Docker Compose:

```bash
docker compose up -d
php artisan migrate
php artisan db:seed
```

The host application connects to PostgreSQL at `127.0.0.1:5432` using the `waggies` database and user. Local development uses passwordless PostgreSQL trust authentication. This is local-development-only configuration and must never be copied to production.

The test suite uses the isolated `waggies_test` database. Laravel Herd serves the application at `https://waggies.test`.

## Search

Search uses Laravel Scout with its PostgreSQL database engine. It does not use Typesense, Meilisearch, Elasticsearch, Algolia, or another external search service.

## Public content and workflows

- Services and service pricing are application-owned; pricing remains in `config/waggies_pricing.php`.
- Relocation content is configuration-backed.
- Guides, Knowledge Articles, FAQs, Products, Gallery items, Testimonials, Newsletter subscribers, and Booking Requests are database-backed resources.
- Gallery, editorial covers, and other managed model media use Spatie Media Library.
- The public shop is a catalogue and enquiry journey, not checkout, payment, inventory, or fulfilment.
- Public requests are stored with operational moderation states; staff complete arrangements outside the public form.

Clinical governance is internal. Guides and Knowledge Articles remain separate public content families; `ClinicalContent` is a governance wrapper and `ClinicalSource` records provenance. Clinical review history is reviewer-controlled and read-only. RAG is not implemented: pgvector is installed, but there are no local chunks, embeddings, vector columns, or similarity retrieval.

There is no general staff-role/RBAC architecture in this application. Any future need for one is **PERMISSION REQUIRED** and must be designed separately from the current clinical reviewer boundary.

## Development commands

```bash
docker compose up -d
php artisan migrate
php artisan db:seed
php artisan test
vendor/bin/phpstan analyse
vendor/bin/pint --test
npm run build
```

The project uses a Pest test suite running on PHPUnit. Pest is the test runner; PHPUnit is the underlying engine. Run one focused file during development, for example:

```bash
php artisan test --compact tests/Feature/MediaArchitectureTest.php
```

Rebuild Scout's database-backed projection with the project's approved search command when needed:

```bash
php artisan waggies:search-rebuild
```

Inspect the application and routes with:

```bash
php artisan about
php artisan route:list
```

## Architecture guidance

Keep the current product boundaries intact: do not move service pricing into Filament or the database, do not add a pricing CMS, do not implement RAG, do not create public medication functionality, and do not introduce general RBAC without permission. Prefer existing Laravel, Waggies, Filament, and Spatie infrastructure before creating new abstractions.

Read the load-bearing project rules before changing code:

[`.ai/rules/index.md`](.ai/rules/index.md)

## Local administration

Filament is available under `/admin`. Local browser verification uses the existing disposable account created by `DatabaseSeeder`; obtain its credentials from the active local task context and never commit or document them.
