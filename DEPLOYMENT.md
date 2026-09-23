# Waggies production deployment

This document describes the deployable boundary of the current Waggies application. It does not add checkout, payments, inventory, scheduling, customer accounts, a pricing CMS, or a second search engine. Public booking is request intake only; it does not reserve capacity or confirm an appointment.

## Runtime inventory

- PHP 8.5 with `bcmath`, `ctype`, `curl`, `dom`, `exif`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql` or `pdo_pgsql` for the selected database, `zip`, and `zlib`. The supported local PostgreSQL setup requires `pdo_pgsql`.
- Laravel 13.33, Filament 5.8, Livewire 4.4, Spatie Media Library 11.23, Backup 10.3, and Health 1.40 are installed. Versions are locked in `composer.lock`.
- Node.js and npm are required only to build Vite assets. The deployed web process serves the generated `public/build` assets.
- The web server must point its document root at `public/`. The repository root must not be web-accessible.
- `storage/` and `bootstrap/cache/` must be writable by the application process. The rest of the repository should remain read-only. `public/storage` is the link created by `php artisan storage:link`.

The current application uses a database-backed editorial and operational model. Services, relocation content, and service pricing remain code/configuration-owned; pricing is authoritative in `config/waggies_pricing.php`. Guides, Knowledge Base, FAQ, Gallery, Testimonials, Products, Contact, Newsletter, Booking, Job Opening, Business Profile, and Business Hours data are database-managed. Gallery and editorial media include both `media` rows and files under the configured Media Library disk.

## Local Windows development database

The supported local setup is PostgreSQL 18.6 with pgvector through compose.yaml. Laravel runs under Herd on Windows and connects through the host-published loopback port; it does not use the Compose service hostname.

```bash
docker compose up -d --build
php artisan migrate
```

The development connection is pgsql to 127.0.0.1:5432, database waggies, user waggies, with an empty password. The Compose service persists data in the named waggies-postgres-data volume and creates the isolated waggies_test database used by PHPUnit/Pest. POSTGRES_HOST_AUTH_METHOD=trust is intentionally limited to this localhost-bound development database and is not suitable for production.

## Environment

Copy `.env.example` to the deployment environment and supply secrets through the host's secret manager. Never commit `.env` or a production password.

Required production values:

- `APP_ENV=production`, `APP_DEBUG=false`, a real HTTPS `APP_URL`, and a stable `APP_KEY` kept outside source control.
- A production `DB_CONNECTION` and its connection variables. MySQL/MariaDB or PostgreSQL are the intended server database choices. The local development baseline is PostgreSQL; SQLite is only an explicitly isolated fallback for tooling that requires it.
- `FILESYSTEM_DISK` and `MEDIA_DISK`. Keep public media on a public disk only when it is intended to be public. Private operational files must remain on a private disk.
- `CACHE_STORE` and `SESSION_DRIVER` backed by the same production database or an explicitly provisioned alternative. Redis is optional and is not required by the current application.
- `MAIL_MAILER`, `MAIL_FROM_ADDRESS`, and the provider-specific variables only when production email delivery is enabled. Development uses `log`; no provider or credentials are selected here.
- `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, and `SESSION_SAME_SITE=lax` for the HTTPS deployment. Do not change local development to HTTPS-only cookies unless the local site is HTTPS.
- `WAGGIES_PHONE`, `WAGGIES_PHONE_INTERNATIONAL`, and `WAGGIES_WHATSAPP` when the public business contact details differ from the safe values in `.env.example`.
- `SUITECRM_BASE_URL`, `SUITECRM_TOKEN`, and `SUITECRM_TIMEOUT` only when the testimonial verification boundary is connected to a real SuiteCRM endpoint. Verification fails closed when these values are absent or the endpoint cannot be reached.

`APP_KEY` is a release-independent production secret. Generate it once before the first deployment, store it in the host secret manager, and reuse it for every release. Do not commit it or regenerate it during deployment: changing it invalidates encrypted session data and other encrypted values.

Optional operational values:

- `BACKUP_DISK=s3` when backups are sent to a separately managed object-storage disk. The default `backups` disk is private local storage and is appropriate only for a temporary or single-host setup.
- `BACKUP_ARCHIVE_PASSWORD` must be set through a secret manager when encrypted backup archives are required. `BACKUP_VERIFY=true` is enabled by default.
- `MEDIA_CONVERSIONS_DISK` can separate generated conversions from originals. Current Waggies conversions are synchronous and explicitly non-queued.
- `HEALTH_SECRET_TOKEN` is not needed for the public native `/up` route. Do not expose detailed health results publicly without an access-control decision.

## Deployment sequence

Run these commands from the release directory, with the production environment loaded:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci
npm run build

# Ensure the application process can write storage and bootstrap/cache.
# Use the host's ownership/permission mechanism; do not make the repository world-writable.

php artisan migrate --force
php artisan storage:link
php artisan optimize

curl --fail --silent --show-error "${APP_URL%/}/up"
```

`php artisan optimize` caches configuration, routes, events, and views. The `curl` request is the health check; `php artisan up` changes maintenance mode and is not a health check, so it is not part of this sequence. Do not run `db:seed` as part of deployment. The development seeder is guarded against production and is not a source of production admin credentials. Do not use `down` mode unless the chosen host's traffic strategy requires it and the migration is known to be compatible with the currently running code.

After the release is live, check `/up`, the public smoke-test paths below, `/sitemap.xml`, and an unauthenticated `/admin` request. If a queue worker is introduced later, restart it after code deployment with `php artisan queue:restart`; the current application does not require one.

## CI versus deployment

GitHub Actions verifies the source tree with PHP 8.5, Node 22, Composer/npm dependency installation, Composer and npm audits, PostgreSQL 18.6 + pgvector migrations, Pint, Larastan, the Pest suite at the measured 512 MB CLI memory limit, Laravel configuration/route/view/event caches, and the Vite build. It does not deploy, configure a web server, provide production secrets, verify HTTPS proxy headers, deliver mail, reach WhatsApp, write to production object storage, or prove a production backup/restore.

The server/operator performs the deployment sequence above. A clean release directory or archive assembled from the checked-out source is the release artifact; `vendor`, `node_modules`, `.env`, local databases, caches, and backup archives are not release source.

## Required processes

- PHP-FPM or the host's PHP web runtime behind the chosen web server.
- No Laravel scheduler is currently required; `routes/console.php` contains no scheduled work.
- No queue worker is currently required. Public submissions persist synchronously, and every registered Media Library conversion is `nonQueued()`.
- Search is the existing in-process database/config projection. Typesense, Scout, Meilisearch, and Algolia are not configured.

Backups are operational maintenance, not an application scheduler requirement. Run `backup:run`, `backup:clean`, and `backup:monitor` from the host/provider scheduler or backup runbook at the chosen cadence. Do not claim backup coverage until the commands succeed against the real destination.

## Backups and restore

Spatie Laravel Backup is already installed and configured in `config/backup.php`.

- A backup contains a database dump and application files, including Media Library originals and conversions under the configured storage path.
- Vendor, node modules, framework caches, logs, local database runtime state, backup archives, and `.env` are excluded. The database dump and the deployment environment are the restore authorities.
- Local development writes to the private `backups` disk. PostgreSQL backups require the matching `pg_dump` tooling. Production should use a separate encrypted/retained destination such as S3 by setting `BACKUP_DISK=s3` and its existing AWS variables.
- Database backups delegate to the selected engine's native CLI (`sqlite3`, `mysqldump`, or `pg_dump` as applicable). Install and verify the matching dump and restore tools on the production host before enabling the backup cadence.
- Archive verification is enabled. Set `BACKUP_ARCHIVE_PASSWORD` in production if archive encryption is required, and protect the destination with provider/server access controls.
- Backup notifications are intentionally disabled until a real notification destination is configured. The notification classes remain explicitly mapped to empty channel lists so a failed backup reports its actual dump error instead of raising a secondary notification configuration error.
- The default cleanup policy keeps all backups for 7 days, daily backups for 16 days, weekly backups for 8 weeks, monthly backups for 4 months, and yearly backups for 2 years, subject to the configured size limit.

Run a disposable restore test, never against production:

```bash
php artisan backup:run --only-db
php artisan backup:list
php artisan backup:clean
php artisan backup:monitor
```

The first command requires the selected engine's native dump binary, including `pg_dump` for the local PostgreSQL setup. Restore the database dump from a selected archive into a disposable database using the engine's native restore tooling, then run `php artisan migrate:status` and the smoke tests. Restore the matching media files from the same archive or storage snapshot. A database-only restore is incomplete for Gallery, editorial cover images, product images, and testimonial photos. The repository does not claim a production restore until a real destination and engine have been tested.

## Migration and compatibility limits

The current migration history is valid for a fresh install, but it is not a zero-downtime contract. The recent editorial migrations include forward-only UUID conversion/data-copy work, the operational-intake migration replaces an existing table after copying data, and `2026_09_22_100925_drop_service_prices_table` is destructive. Products and booking requests are additive table creation migrations. Back up the database before running pending migrations and validate the release against a disposable restored copy first.

Do not assume old application code can coexist with the new schema, or new code with the old schema, across the forward-only conversions and `service_prices` removal. If a migration has run, rollback means returning traffic to the previous release only after compatibility review; otherwise use a forward-fix or restore the matching database and media set in a controlled recovery environment.

## Failure and rollback

If a deploy fails before migrations, point the web runtime back to the previous release and rebuild its caches. If a migration has run, do not blindly roll it back: first assess whether the old code can read the new schema. Prefer forward-fix migrations. For an unrecoverable data change, restore the database backup into a controlled recovery environment, restore the matching media snapshot/archive, validate the application, then switch traffic deliberately. Never delete production data or automatically downgrade the schema as part of rollback.

Caches are disposable: clear and rebuild them in the selected release with `php artisan optimize:clear` followed by `php artisan optimize`. The `public/storage` link may be recreated safely with `php artisan storage:link` after verifying its target.

## Release rehearsal and recovery

For a release rehearsal, assemble a disposable directory from the checked-out source while excluding `.git`, `.env`, `vendor`, `node_modules`, generated `public/build`, runtime caches, local databases, and backup archives. Run `composer install --no-dev`, `npm ci`, `npm run build`, the cache commands, `storage:link`, and the HTTP smoke tests there. Create the required writable `storage/` and `bootstrap/cache/` directories explicitly; do not rely on ignored directories from a developer machine. The release must boot from its own files and persistent media/database configuration.

The safe failure rehearsal is to select the previous release directory, rebuild its caches, verify the `public/storage` target, and run `/up` plus the public smoke tests. This rehearsal does not downgrade a database or overwrite production data. Database and media restoration remain infrastructure-dependent until a disposable production-engine restore has been completed.

## Health, logs, and security

Laravel's native `/up` route is the public liveness/readiness check. It verifies application boot, the default database connection, and writable `storage/` and `bootstrap/cache/` paths without exposing diagnostic details. Errors remain logged; they are not globally suppressed. Production must set `APP_DEBUG=false`.

The application adds `X-Content-Type-Options`, `Referrer-Policy`, and `Permissions-Policy`. HSTS is added only for production HTTPS requests. CSP is intentionally not asserted here because the current Alpine/Vite/media setup needs a deployment-specific policy review. Configure the host's log retention and forwarding; the Laravel daily channel is available through `LOG_CHANNEL=daily` and `LOG_DAILY_DAYS`.

The application does not currently trust arbitrary reverse proxies. If the chosen host terminates TLS upstream, configure Laravel's trusted proxy addresses and forwarded-protocol headers for that known infrastructure before expecting secure URL generation or HSTS. Do not use a wildcard proxy trust setting without an infrastructure decision. The web server must also enforce the canonical host; `APP_URL` controls generated canonical, sitemap, robots, asset, and WhatsApp-adjacent links but is not a substitute for host configuration.

Public Booking, Contact, Newsletter, and Testimonial submissions use CSRF where applicable, validation, honeypots, and named rate limiters. Testimonial publication requires moderation, consent, a CRM customer match, identity verification, and customer-relationship verification; CRM failures do not publish a testimonial. Testimonial and admin uploads are image-constrained and Media Library rejects dangerous filename extensions; public media is stored on the public disk by explicit collection policy. Filament is protected by its authentication middleware. Operational records are not rendered on public pages.

WhatsApp is a handoff URL from the `WAGGIES_WHATSAPP` value in `config/waggies.php`; it is not an API integration and does not provide delivery confirmation. The current search, canonical URLs, robots, and sitemap use the configured `APP_URL`; staging must use a staging `APP_URL` and must not be indexed. In-process search reads database/config content and has no rebuild or search service deployment step.

## Smoke-test checklist

Run after every production deployment:

- `GET /up` returns success without diagnostics.
- Homepage, Services, Pricing, Booking, Contact, Shop, one product detail, Guides, Knowledge Base, FAQ, Gallery, Careers, Loyalty, and the existing search endpoint load.
- `/sitemap.xml` returns canonical URLs for the current `APP_URL`; `/robots.txt` disallows `/admin/` and `/api/`.
- Booking, Contact, Newsletter, and Testimonial forms reject invalid input and keep the documented request boundary.
- A guest request to `/admin` redirects to authentication; an authenticated staff check is performed separately with real local/staging credentials.
- Public media URLs resolve through the configured storage link/disk, while private operational data is not present in public HTML.

Do not treat a local production-like run as proof of a real web server, HTTPS proxy, SMTP provider, object-storage destination, or external monitoring service. Those remain environment-specific verification items.

## What must never happen automatically

- No production credentials or development Filament password may be generated or committed.
- No production `db:seed`, schema downgrade, destructive data deletion, or media purge is part of deployment.
- No automatic checkout, payment, inventory, scheduling, calendar sync, customer accounts, CRM synchronisation, generic Settings, pricing CMS, Services CMS, Relocation CMS, new search engine, or monitoring platform is introduced by deployment tooling.
- No backup is considered successful merely because a command exists; inspect destination, archive verification, retention, and restore evidence.
