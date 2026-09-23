# Waggies Batch 10 Architecture Audit

Audit date: 2026-09-22
Project root: `C:\Users\Bridges\Herd\waggies`
Audit mode: whole-system architecture, runtime, product, accessibility, testing, and public/admin reconciliation
Starting revision: `3e2e3320ec1b19ea08531f833e437988e03604f5`

This file retains historical audit context and the current Batch 30 reconciliation below. The current product is a hybrid application: config/code owns Services, Relocation, and service pricing; database records own Guides, Knowledge Base, FAQs, Gallery, Products, BookingRequests, ContactEnquiries, Testimonials, and NewsletterSubscribers; Blade/Alpine owns the public surface; and Filament/Livewire owns justified admin resources.

## Current local database baseline — 2026-09-23

Local Windows development now uses PostgreSQL 18.6 with pgvector through Docker Compose. Laravel connects through the default pgsql connection to 127.0.0.1:5432, database waggies, as waggies with no password. The Compose service binds PostgreSQL to localhost only and persists its data in the named waggies-postgres-data volume. Trust authentication is local-development-only and must not be used in production.

The original SQLite database is preserved as corrupt evidence and is not a migration source. The new PostgreSQL database is reconstructed from migrations and legitimate configuration-owned seed data; unrecoverable SQLite records are not represented as migrated data.

## Final verification — 2026-09-23

This verification supersedes the earlier open findings below where they describe the pre-final-audit runtime. The normal local `DatabaseSeeder` was run against PostgreSQL, making the standard local admin fixture available without adding production credentials or changing the seeder's local/testing guard. The seeded account logged into `/admin`, rendered the structured dashboard and Work Queue, and signed out back to `/admin/login`.

- All 17 active Filament resource classes were audited. The 13 non-clinical resources opened through the authenticated panel; clinical governance routes were checked through their authorization boundary. Empty states, populated tables, forms, relationship selectors, thumbnails/previews, publication fields, and clinical gates rendered without raw JSON, serialized data, raw Markdown, or raw HTML.
- Ordinary staff access to ClinicalContent, ClinicalReview, ClinicalSource, and ClinicalToolReview routes returned 403. No clinical rows or medications were created; the clinical workflow remains structurally verified and permission-gated.
- Global search and saved-list dialogs were keyboard-tested: focus enters the dialog, Tab wraps within it, Escape closes it, and focus returns to the trigger.
- Knowledge Base pagination rendered as an accessible pagination navigation with the current page, page 2, and Next link. No Alpine pagination warning was present.
- Public browser checks covered the route inventory, canonical/robots/structured-data contracts, 404 handling, gallery filters, key image URLs, responsive widths (375/768/1280/1440), and fresh-page console errors. No horizontal overflow or console errors were observed.
- `waggies:search-rebuild` synchronized 99 public search documents. PostgreSQL, pgvector, migrations, routes, PHPStan, Pint, PHPUnit, and the Vite production build all passed.

The remaining permission-gated limitation is deliberate: the active user model has a clinical-reviewer flag but no general staff-role/RBAC authority. Broadening admin authorization therefore requires an approved permission model and is not invented as part of this audit.

## Batch 1 implementation reconciliation — 2026-09-23

The current implementation extends that boundary with persisted booking-request lifecycle/context fields, canonical `BusinessProfile` and `BusinessHour` records, `JobOpening` records for careers, product availability/search/sort and multiple media, and testimonial CRM/identity/customer-relationship verification metadata. `/book` remains request intake only: it does not schedule, allocate capacity, take payment, or confirm an appointment. `/services/pricing` is the canonical pricing surface; the legacy cost-calculator URL redirects there and is not navigated from the public Tools catalogue.

Contact enquiries and booking requests remain separate persisted records. Loyalty remains manual handoff content with no points, account, tier, referral, or redemption runtime. SuiteCRM is an optional verification boundary only; absent configuration or failed requests fail closed. The public shop remains a catalogue and saved-for-enquiry flow, with no order, checkout, payment, inventory, or fulfilment domain.

## 1. Git state

- Branch: `main`.
- `HEAD`: `3e2e3320ec1b19ea08531f833e437988e03604f5` (`Standardize Waggies content and list patterns`).
- The expected Batch 10 starting revision was present.
- The working tree was clean before the audit.
- No implementation files, dependencies, migrations, routes, or database records were changed during the audit.
- This report file is the only intended artifact update for Batch 10; no commit or push was made.

Recent context confirms that the current code is the result of the preceding standardization batches, including primitive APIs, hero/page-header compositions, service compositions, forms/public interactions, and content/list patterns. The earlier version of this report predated those batches and was therefore not treated as current evidence.

## 2. Executive architecture assessment

The application is structurally coherent and is not in need of a broad rewrite. Its strongest architectural decision is the clear separation between:

1. config-backed editorial and catalogue content;
2. controllers that resolve page context, request schemas, metadata, and server-owned values;
3. Blade components that own semantic markup and reusable visual primitives; and
4. Alpine factories that own local browser interaction.

The current test suite, static analysis, build, and targeted browser checks all pass. Service pricing is intentionally owned by version-controlled configuration plus application-owned calculation rules. The global search/cart dialog lifecycle now uses the shared focus-trap and trigger-return behavior in the current runtime, and Knowledge Base pagination now renders numbered navigation without the historical Alpine multi-root warning. The remaining concerns are bounded maintenance and permission decisions rather than evidence for a rewrite.

The remaining issues are bounded maintenance concerns rather than evidence for a new framework, CMS, repository layer, global JavaScript rewrite, or wholesale component migration.

## 3. Current architecture map

```text
routes/web.php
  -> invokable/resource-shaped controllers
  -> config-backed Services/Relocation/pricing plus persisted domain records
  -> Blade layouts, pages, and x-waggies components
  -> Alpine factories in resources/js/app.js
  -> Vite/Tailwind assets in the browser

Filament /admin
  -> app/Providers/Filament/AdminPanelProvider.php
  -> Filament's Livewire infrastructure

Public submissions
  -> NewsletterController -> NewsletterSubscriber (newsletter_subscriptions)
  -> TestimonialController -> Testimonial (pending moderation)

Publication metadata
  -> Controller::setPageHead
  -> AppServiceProvider::boot defaults/errors
  -> PublicUrlCatalog -> sitemap.xml
```

The public application is Blade-first and Alpine-first. No application-owned Livewire components were found under `app/Livewire`; Livewire is present for Filament and is also bootstrapped in the public layout, which is recorded as a bounded deferred concern below.

## 4. Project structure findings

The directory structure is understandable for the current scope:

- `app/Http/Controllers` contains 18 controllers organized around public resource/page families.
- `app/Models` contains the two persisted public submission models plus `User`.
- `app/Support` contains focused cross-cutting helpers such as `PublicUrlCatalog` and `ArticleBodyProcessor`.
- `resources/views/components/waggies` contains the reusable semantic component vocabulary, including buttons, fields, headings, cards, heroes, service detail, article/shop/testimonial cards, navigation, and interaction shells.
- `resources/views/pages` contains page compositions rather than a second competing component system.
- `resources/js` contains a global Alpine registry plus focused calculator modules.
- `tests/Feature` and `tests/Unit` cover routes, SEO, submission contracts, component semantics, and public interactions.

The largest files are not automatically architectural defects. `ContactController` is large because it is the request-intent and form-schema gateway; `resources/js/app.js` is large because it is the application-wide Alpine registry; several page files are large because they compose content-rich pages. The useful follow-up is bounded extraction by responsibility, not splitting based on byte count alone.

## 5. Controller and route findings

### Coherent boundaries

- `ServicesController` composes service index, boarding/species, grooming, training, and vet-care pages from config and shared views.
- `GuidesController` and `KnowledgeBaseController` remain separate because they represent different content families, even though both use `ArticleBodyProcessor`.
- `ToolsController` contains the tools catalogue and its static tool actions. This is an intentional static catalogue boundary, not a reason to create one controller per page.
- `AboutPagesController` and `LegalController` group static page actions by public resource family.
- `NewsletterController` and `TestimonialController` are thin persistence gateways with validation and response contracts.
- `Controller::setPageHead` provides a shared metadata/schema entry point without hiding page-specific SEO decisions.

### Findings

1. **Pricing is intentionally split by responsibility, not ownership.** `config/waggies_pricing.php` owns commercial inputs for services, ranges, transport bands, and surcharges. `ServicesController`, the calculators, and the transport estimator own presentation and calculation semantics. `waggies_boarding`, `waggies_service_details`, and `waggies.php` retain non-commercial service composition and editorial labels only.

2. **`/api/newsletter`, `/api/testimonials`, and `/api/search` live in `routes/web.php`.** They are browser-facing endpoints used by the current public application and share the web middleware/session conventions. This is intentional for the current product, but the convention should be documented if more endpoints are added so that an accidental second API architecture does not emerge.

3. **`ShopController::show` uses published database-backed `Product` slug binding.** The public catalogue remains intentionally bounded: there is no order, checkout, payment, inventory, fulfilment, or customer-account domain.

## 6. Blade and component findings

The component vocabulary is coherent and is actively used. The generic `card` is a low-level shell; semantic components such as `service-card`, `article-card`, `shop-product-card`, `pricing-tier-card`, and `testimonials-grid` carry domain meaning. Form primitives (`input`, `select`, `field-error`, `alert`) are present, and page-level compositions remain readable.

The main adoption issue is partial rather than broken: direct utility composition and raw values remain alongside semantic classes and component APIs. Examples include direct `w-cta` usage, page-specific arbitrary values, and local layout classes. These are valid for unique geometry and responsive composition; the risk is that repeated visual decisions may gradually fork from the primitive vocabulary. This is recorded as design-system adoption drift, not a request for global tokenization.

The following paragraph records a historical finding from the earlier runtime. It is resolved in the final verification above: `resources/views/pages/knowledge-base/index.blade.php` now gives each desktop numbered-page `x-for` iteration one root, and browser accessibility output includes the numbered pagination.

The former knowledge-base pagination finding was:

```text
Alpine Warning: x-for templates require a single root element, additional elements will be ignored.
```

The former effect was that previous/next navigation remained available while desktop numbered links were ignored. It is retained here only as historical audit context.

## 7. Design-system findings

### Established strengths

- `resources/css/app.css` defines semantic tokens, component-layer styles, CTA conventions, reduced-motion behavior, forced-colors handling, and print behavior.
- `x-waggies.button`, `x-waggies.page-header`, `x-waggies.cover-hero`, `x-waggies.card`, `x-waggies.input`, and `x-waggies.select` establish a recognizable API.
- Existing tests protect several primitive semantics and rendered contracts.
- Hero geometry, card family, service detail, form fields, and page headings have meaningful boundaries rather than one universal component with many unrelated flags.

### Adoption drift

The component API is not yet the exclusive route for repeated UI decisions. Measured usage confirms meaningful adoption but not full convergence: button/page-header/card/field primitives coexist with direct utility strings and page-local styling across `resources/views/pages` and components. This is maintenance debt only where the same decision is repeated and independently editable.

Recommended direction: define a small boundary between canonical semantic primitives and legitimate page-specific composition, then migrate repeated cases opportunistically. Do not run a global utility-to-component conversion in this project phase.

## 8. Card, hero, and service architecture findings

### Cards

The card family is coherent. `resources/views/components/waggies/card.blade.php` provides the shell, while domain cards own their content and interaction contracts. `shop-product-card` keeps the product link and cart action as separate controls without nested interactive elements. `WaggiesCardSemanticsTest` provides regression coverage.

### Heroes

`cover-hero` and `page-header` represent two real visual/content modes: image-led cover content and text-led page framing. `hero-actions` centralizes action rendering. Treating them as one component would obscure the distinction and increase conditional complexity. `WaggiesHeroComponentsTest` covers the current contract.

### Services

`service-detail` is the shared composition for the ordinary grooming/training/vet-care detail spine. Boarding/species and relocation have materially different content and interaction needs, so they retain specialized compositions. This is a legitimate exception to maximal reuse, not component duplication requiring immediate consolidation.

## 9. Forms and interaction findings

The public form architecture is substantially coherent:

- `ContactController` resolves the request intent and serializes server-owned pricing/schema data.
- `resources/views/components/waggies/testimonial-form.blade.php` owns the testimonial submission UI and validation display contract.
- Newsletter and testimonial submissions persist through dedicated controllers and models.
- The current tests cover normalization, duplicate newsletter behavior, invalid input, pending testimonial moderation, and invalid photo handling.
- Browser checks confirmed contact intent selection, FAQ disclosure, shop add-to-cart behavior, and public page rendering.

The pricing calculator correctly receives serialized server data instead of owning a second independent rate table in JavaScript. The outstanding problem is upstream configuration duplication, not the transport mechanism from PHP to JavaScript.

The following paragraph records a historical finding from the earlier runtime. The current `waggiesDialog` Alpine behavior in `resources/js/alpine/global-ui.js` provides the shared focus-trap and trigger-return contract for search and saved-list dialogs; the final browser audit verified open, Tab-wrap, Escape, and return-focus behavior.

The former global dialog finding was:

Search and saved-list dialogs exposed `x-show` and `aria-modal` without a shared focus-trap and trigger-return contract. This could leave keyboard users without a reliable return point and was recorded for a focused accessibility batch.

## 10. Data and business-logic findings

The current persistence boundary matches the product:

- `NewsletterSubscriber` stores a normalized email in the historical `newsletter_subscriptions` table and protects duplicate subscription behavior. `NewsletterSubscription` remains only as a deprecated compatibility alias and is not a runtime authority.
- `Testimonial` stores submitted content and a pending/approved/rejected/archived moderation state. The public query uses the approved scope.
- No domain-table expansion is justified by the current config-backed catalogue.
- Native uploaded-file storage in `TestimonialController` is a deliberate simple implementation for the current submission flow; the presence of `spatie/laravel-medialibrary` does not by itself require migration.
- `ArticleBodyProcessor` is a focused deterministic service and is reused by article-like content without introducing a repository layer.

Pricing is a deliberate application boundary: configuration owns commercial inputs while controllers and calculator modules own formatting and calculation rules. No pricing repository, generic settings editor, or CMS resource is justified by the current product.

## 11. SEO and publication findings

The publication architecture is coherent and tested:

- `AppServiceProvider::boot` establishes safe defaults and error-page handling through Laravel Head.
- `Controller::setPageHead` handles page-specific titles, descriptions, canonical URLs, robots directives, Open Graph/Twitter values, and schemas.
- `PublicUrlCatalog::urls()` is the explicit publication inventory used by the sitemap route.
- `routes/web.php` exposes `sitemap.xml` and `robots.txt` without introducing a competing publication mechanism.
- `WaggiesBreadcrumb` and page-level schema helpers provide structured navigation/schema output.
- `SeoSearchSitemapTest` and related route tests protect key contracts.

The catalogue is deliberately distributed between explicit static route entries and published database-backed Guide/Knowledge Article/Product loops. That is a maintainable choice for the current publication model. There is no evidence here for a URL redesign, automatic crawl discovery, or CMS migration. A future content-publication batch could consider richer publication metadata such as `lastmod`, but that is deferred and not a current defect.

## 12. Dependency findings

The installed direct dependencies include Laravel 13.33.0, Filament 5.8.2, Livewire 4.4.6, Pest/PHPUnit, Larastan, Pint, Head, sitemap, breadcrumbs, schema.org, Debugbar, and several Spatie packages.

Clearly evidenced usage includes Laravel/framework, Head, schema.org, sitemap, breadcrumbs, Filament, Livewire through Filament infrastructure, Pest/PHPUnit, Larastan, Pint, Debugbar, Vite, and Tailwind.

The dependency inventory now classifies the remaining packages by verified role:

- Active runtime: Filament, Livewire, Scout, medialibrary, permission, sluggable, schema.org, sitemap, breadcrumbs, Head, and the framework.
- Infrastructure-required: backup (configured and covered by production-readiness checks) and health (the `/up` health route).
- Planned or deferred: activitylog, data, model-states, and tags have no current application-owned call sites but remain installed for the product roadmap.
- Verified unused and removed: `laravel-notification-channels/webpush` had no application, configuration, migration, route, or test usage and was removed with its lockfile-only transitive packages.

This inventory is evidence for the current application boundary, not permission to remove planned or infrastructure packages without a separately tested decision.

Recommended control: perform one dependency inventory before production hardening, classify each package as active, infrastructure-required, planned, or verified-unused, and remove only verified-unused packages in a separately tested batch. Do not combine package removal with pricing, focus, or UI changes.

## 13. Testing findings

The current baseline is healthy:

- `php artisan test --compact`: 154 passed, 1,552 assertions.
- `vendor/bin/phpstan analyse`: no errors across 193 files.
- `vendor/bin/pint --dirty --format agent`: passed.
- `npm run build`: passed with Vite 7.3.6 and Tailwind 4.3.3.
- Waggies-owned PHP lint for `app`, `bootstrap`, `config`, `database`, `routes`, and `tests`: passed.
- `php artisan view:cache`: passed.
- `git diff --check`: passed.

The suite uses Pest as the runner but contains both PHPUnit-style class tests and Pest function tests. This is valid and currently green. A wholesale PHPUnit-to-Pest migration would create churn without improving the current regression signal. Prefer opportunistic migration only when a touched test benefits from it.

Coverage is strongest for route contracts, SEO/search/sitemap, component semantics, submission behavior, and selected public interactions. There are no browser/Dusk tests and no JavaScript unit tests. That is acceptable for the current scope; the knowledge-base pagination warning and future dialog focus batch should receive targeted browser or interaction assertions if the team decides those behaviors warrant end-to-end protection.

## 14. Accessibility architecture findings

The application has good systemic foundations:

- skip-link support;
- semantic headings and lists;
- labels and field-error relationships;
- alert/status patterns;
- card links and buttons with meaningful controls;
- reduced-motion and forced-colors CSS handling;
- native disclosure behavior for FAQ-style content.

Targeted browser accessibility snapshots showed valid landmarks, headings, labels, buttons, and links on home, services, contact, FAQ, shop, and knowledge-base pages. The FAQ disclosure interaction expanded correctly and kept focus on its trigger.

The global search/cart focus lifecycle remains the high-value exception. Record it separately from the knowledge-base template warning because the scope and remediation are different: one is a single template structure defect; the other is a shared overlay interaction contract.

## 15. Responsive and design consistency findings

Responsive composition is mostly consistent. Pages repeatedly use the same max-width and horizontal-padding vocabulary, mobile/desktop variants are explicit, and Tailwind’s default breakpoint naming is not competing with a second breakpoint system.

Direct utility classes and arbitrary values remain in page compositions. Most observed cases are legitimate for hero geometry, icons, image treatment, or local responsive exceptions. The actionable concern is repeated styling decisions that bypass the semantic primitive API; this is the same bounded design-system adoption drift recorded in Section 7, not a general responsiveness failure.

## 16. Naming and vocabulary findings

The vocabulary is mostly clear. `title` and `heading` are used in different contracts, and `service` versus `resolvedService` distinguishes a selected/normalized service from an input concept. These are intentional distinctions.

There is one lower-severity component API inconsistency: action arrays use both `route` and `href`. For example, `HomeController` hero actions use route-shaped values, while `ServicesController` and some config-backed actions use `href` or a mixed shape. `hero-actions.blade.php` currently accommodates the difference. This works, but it means callers must know an implicit union contract.

Recommended future cleanup: choose one normalized action shape at the controller/component boundary, retain support for external URLs if needed, and add a focused contract test. This is P2/P3 maintenance work, not a reason to rewrite current pages.

## 17. `.ai/rules` and agent guidance

`.ai/rules/index.md` is discoverable and maps the relevant path globs to current rules. The loaded rules accurately describe:

- focused Cruddy-by-Design controllers;
- Blade-first and Alpine-first public interactions;
- Livewire only where server state materially benefits;
- config-backed pricing as the authority serialized to JavaScript;
- semantic card and component reuse;
- avoiding speculative abstractions.

No direct contradiction was found between the path-scoped rules and the current implementation. The generated `AGENTS.md`/`CLAUDE.md` guidance does contain a test-instruction ambiguity: it describes the project in PHPUnit terms while the project also treats Pest as the runner and current convention. The underlying PHPUnit engine remains valid, so this is documentation clarity debt rather than a test failure.

Do not record new `.ai/rules` during this audit. A durable rule should be recorded only after the team explicitly decides that the pricing authority or action-shape convention is settled.

## 18. Documentation findings

`README.md` still begins with the generic Laravel starter framing and generic Laravel learning/contributing material. Waggies-specific sections exist later in the file, including pricing/request contracts and primitive vocabulary, but the document does not yet present the current application architecture, test command, static-analysis command, Pint/build workflow, or the current config-backed publication model as the primary onboarding path.

This is a P2 documentation gap with a small/medium scope. The next documentation update should be focused: replace stale starter framing, document the authoritative config boundaries, list the verification commands, and point contributors to `.ai/rules/index.md`. Do not create a second architecture guide or duplicate every rule file.

The existing audit artifact is being updated by this report rather than preserved as a second stale snapshot.

## 19. Legitimate exceptions and intentional choices

The following should not be “fixed” merely to make the code look more uniform:

- `ToolsController` grouping a static tools catalogue.
- `ContactController` owning request-intent resolution and form-schema generation.
- `AboutPagesController` and `LegalController` grouping related static pages.
- Guides and knowledge base remaining separate content domains.
- `cover-hero` and `page-header` remaining separate hero modes.
- Domain-specific card components coexisting with a generic card shell.
- Shared service-detail composition coexisting with specialized boarding and relocation pages.
- Config-backed editorial content remaining outside a CMS.
- Browser-facing `/api` endpoints remaining in the web route file while they use the current public form/search contract.
- Filament/Livewire remaining an admin/infrastructure dependency even though public pages are Blade/Alpine-first.
- Page-specific raw CSS/utility values for unique geometry, imagery, or responsive behavior.
- Mixed PHPUnit-style and Pest-style tests while the suite is green; migrate gradually only when useful.
- Native testimonial photo storage until a deliberate media-library policy is chosen.

## 20. Architectural debt register

| ID | Severity | Status | Evidence and why it matters | Recommended remediation | Scope |
| --- | --- | --- | --- | --- | --- |
| B10-01 | P1 | `RESOLVED IN R26` | Service commercial inputs are restored to `config/waggies_pricing.php`; public consumers read that configuration and application code owns display/calculation semantics. | Preserve the configuration/application boundary. Do not recreate database-backed pricing or a pricing CMS without a materially different product requirement. | Complete |
| B10-02 | P1 / high P2 | `RESOLVED; VERIFIED 2026-09-23` | The current shared dialog behavior traps focus, handles Escape, and restores focus to the trigger for global search and saved-list dialogs. | Preserve the shared lifecycle and keep the browser interaction check covered by future audits. | Complete |
| B10-03 | P2 | `RESOLVED; VERIFIED 2026-09-23` | The Knowledge Base numbered-page `x-for` has one root per iteration; browser accessibility output includes page 2 and Next navigation without the historical warning. | Preserve the single-root template and pagination regression coverage. | Complete |
| B10-04 | P2 | `OPPORTUNISTIC` | Semantic component APIs are adopted but coexist with repeated direct utility/raw values across `resources/views/pages` and components. Repeated decisions may fork. | Define the primitive-versus-page-composition boundary and migrate repeated cases only when touched. | Medium, incremental |
| B10-05 | P2 | `DEFERRED` | `resources/js/app.js` is an 813-line global Alpine registry and event surface. It is coherent today but increases shared-bundle coupling as features grow. | Extract bounded behavior/domain modules without changing Alpine or introducing a state library; pair extraction with focused tests. | Medium/large |
| B10-06 | P2 | `DEFERRED` | Public `layouts/app.blade.php` includes Livewire styles/config and `app.js` starts Livewire, while no application-owned public Livewire components exist. Filament still needs Livewire. | Verify Filament asset isolation, then either remove unnecessary public bootstrap or document the compatibility boundary. Do not remove Livewire/Filament. | Small/medium |
| B10-07 | P2 / P3 | `DEFERRED` | Action contracts use both `route` and `href`; `hero-actions.blade.php` carries an implicit union. | Normalize the action shape at one boundary and add a focused component contract test. | Small |
| B10-08 | P2 | `DEFERRED` | `README.md` retains Laravel starter framing and omits current Waggies verification/onboarding guidance. | Replace stale framing with concise Waggies architecture, commands, config ownership, and rules entry points. | Small/medium |
| B10-09 | P2 / P3 | `DEFERRED INVENTORY` | Several direct runtime packages have no current application-owned usage evidence. Removing them blindly could break planned infrastructure. | Classify packages as active, infrastructure-required, planned, or verified-unused; remove only verified-unused packages in an isolated batch. | Medium |
| B10-10 | P3 | `OPPORTUNISTIC` | Tests run under Pest but mix class-based PHPUnit syntax and Pest functions; generated guidance also uses PHPUnit-centric wording. | Clarify runner/engine language; migrate individual tests only when touched. | Small, incremental |

## 21. Remediation roadmap

### Immediate

- Keep the current green baseline intact.
- Preserve the R26 pricing authority boundary.
- Preserve the verified dialog focus lifecycle and Knowledge Base pagination behavior.

### Next controlled batches

No implementation batch is required for B10-02 or B10-03; both were verified resolved during the final audit. Future work remains subject to the existing scope and permission gates.

### Opportunistic

- Normalize `route`/`href` action contracts when hero/action components are next touched.
- Migrate repeated direct utility patterns to semantic primitives only where repetition is proven.
- Improve README onboarding while touching related documentation.
- Convert individual class-style tests only when a test is already being edited.

### Deferred

- Global Alpine registry extraction.
- Public Livewire bootstrap decision after Filament asset-isolation verification.
- Dependency inventory and any package removals.
- Media-library policy for testimonial uploads.
- Any CMS, search redesign, URL redesign, or database expansion.

## 22. Historical Batch 11 pricing authority recommendation (completed)

This was the previously recommended controlled pricing batch. It was implemented in the active repository before Batch 26 and is retained as the implementation baseline for R26.

**Batch 11 — Reconcile pricing authority and derived public displays.**

### Scope

- Confirm `config/waggies_pricing.php` as the canonical structured commercial source, or record a deliberate alternative if product ownership requires it.
- Map every displayed boarding, grooming, training, vet-care, relocation, and calculator value to that authority.
- Remove or replace duplicated commercial values in `config/waggies_boarding.php`, `config/waggies_service_details.php`, and `config/waggies.php` where they represent the same rate.
- Preserve editorial “from” labels only where they are intentionally not exact tier prices, and make that distinction explicit in the data shape.
- Keep server-side serialization through `ContactController`/the existing calculator contract.
- Add focused tests for structured pricing, public display values, calculator input, and the important mismatch/failure cases.
- Run the existing test, PHPStan, Pint, build, view-cache, and diff checks.

### Explicit exclusions

- No URL or route changes.
- No database schema or CMS changes.
- No search redesign.
- No dialog focus remediation.
- No global Alpine refactor.
- No dependency removal.
- No broad component migration or design-token rewrite.

The work was completed as one controlled pricing contract change, with the public amounts reconciled against the version-controlled pricing source. R26 then removed the later database/CMS authority and preserved this configuration/application boundary.

## 25. Batch 29 media architecture audit

Batch 29 re-audited the active repository, development database, and development filesystem rather than relying on earlier reports.

### Inventory and ownership

- Developer-owned static assets are limited to the application logo, favicon, icons, and three local boarding service hero JPEGs under `public/`.
- Waggies-owned managed-content candidates are persisted Products, Gallery Items, Guides, Knowledge Articles, and Testimonials. All five models now implement Media Library ownership with intentional collections and conversions. Guide and Knowledge Article rich-editor attachments are separately owned in `content-attachments`.
- Service, relocation, home, about, FAQ, and legacy catalogue imagery remains config/controller-backed remote imagery. The audit found Unsplash references across those surfaces and in the imported legacy image fields. No safe owned source was present for re-hosting, so none was falsely migrated.
- The development `media` table currently contains zero rows. Existing database records therefore still use their legacy remote image fields until staff uploads an owned replacement.

### Storage and lifecycle

Media Library is configured for the `public` disk, whose root is `storage/app/public` and whose public URL is `/storage`. The default application filesystem remains `local`; private application storage remains `storage/app/private`. Managed collections use the public disk because their content is intentionally public. Rich-editor content attachments are also public because their URLs are embedded in public HTML.

Single-file collections, `clearMediaCollection()`, and the Media Library model deletion hook provide the replacement, removal, and owning-record deletion semantics. Batch 29 adds focused tests that prove the old original and conversions disappear on replacement, collection clearing, and owner deletion, and that responsive detail media is rendered publicly.

### Orphan findings

- No orphan Media DB rows were found because the actual `media` table is empty.
- Media Library dry-run identified one unowned `storage/app/public/1` directory. It was preserved in the pre-change backup before safe cleanup. The existing `storage/media-library/temp` directories are temporary conversion/upload output, not domain-owned media records; they remain outside the public managed-media contract and are not treated as migrated content.
- The repository contains an intentional Batch 27 private backup under `storage/app/private`; it was not touched.

### Filament preview hardening

Products, Gallery, Guides, Knowledge Articles, and Testimonials now expose compact thumbnails in their Filament tables. Their resources eager-load media. Their edit forms already use the installed `SpatieMediaLibraryFileUpload`; legacy remote records additionally receive a current-image preview so staff do not have to infer the image from a URL or filename.

### Scope

No generic media manager, DAM, CDN migration, new image package, custom upload abstraction, or unrelated business-domain feature was introduced. Existing public fallbacks remain explicitly unresolved legacy dependencies until safe owned source images are supplied.

## 23. R26 pricing architecture rollback

R26 intentionally reverses Batch 26. The runtime authority is:

```text
version-controlled config/waggies_pricing.php
        + application-owned pricing rules/calculations
        = service pricing authority
```

`config/waggies_pricing.php` contains the developer-owned commercial inputs for service tiers, estimate ranges, packages, transport distance bands, and transport surcharges. `ServicesController`, the cost/pricing calculators, and the transport estimator determine how those inputs are formatted, combined, multiplied, or treated as quote-only. Configuration contains no executable pricing rules.

The `ServicePrice` model, `PricingCatalog`, Service Prices Filament resource, Batch 26 enums/factory, and their runtime tests were removed. The applied Batch 26 create migration remains as historical migration history, and a forward migration removes `service_prices` from existing and fresh databases. The table is not runtime-authoritative and no pricing settings UI exists.

BookingRequest remains intact and resolves service options from the developer-controlled service configuration. Shop/Product pricing remains database-backed as a separate commerce/catalogue boundary. Guides, Knowledge Base, FAQs, Gallery, Contact Enquiries, Testimonials, Newsletter, and other preserved vertical slices are outside this rollback.

Future-agent guardrail: **Do not move Waggies service pricing into Filament/CMS merely because staff-editable prices are technically possible. Pricing inputs participate in application-owned calculations and relationships; keep service pricing in version-controlled configuration unless a future product requirement materially changes this architecture.**

## 24. Batch 10 historical changes

- Updated `WAGGIES-ARCHITECTURE-AUDIT.md` with this current, evidence-based Batch 10 report.
- Made no application-code changes.
- Made no dependency changes.
- Made no route, URL, database, CMS, search, or public interaction changes.
- Recorded the global dialog focus lifecycle as a separate systemic accessibility finding and did not fix it in this batch.
- Verified the current application baseline with the passing checks listed in Section 13.

## Batch 30 current evidence report

The sections below supersede stale historical statements above where they describe the pre-Batch-30 runtime.

## 1. Overall result

The current Waggies slices form one coherent hybrid application after reconciliation. Services, Relocation, and service pricing remain config/application-owned; persisted content, product catalogue, operational intake, booking requests, and managed media use their established database/package authorities. No new business domain or generic CMS was introduced.

## 2. Runtime authority matrix

| Domain | Runtime authority | Admin-managed? | Public? |
| --- | --- | :---: | :---: |
| Services | Config/code page families | No | Yes |
| Relocation | Config/code page families | No | Yes |
| Service pricing | `config/waggies_pricing.php` + application logic | No | Yes |
| Guides | `guides` database records | Yes | Yes |
| Knowledge Base | `knowledge_articles` database records | Yes | Yes |
| FAQs | `faqs` database records | Yes | Yes |
| Gallery | `gallery_items` + Media Library where owned media exists | Yes | Yes |
| Contact | `contact_enquiries` database records | Yes | Submit publicly; records private |
| Testimonials | `testimonials` database records with approval gate | Yes | Approved records only |
| Newsletter | `newsletter_subscriptions` via `NewsletterSubscriber` | Yes | Submit publicly; records private |
| Products | `products` database records | Yes | Published records only |
| Booking | `booking_requests` via `BookingRequest` | Yes | Submit publicly; records private |

## 3. Concrete issues found

- FAQ structured data used the complete public FAQ payload even on a category URL. Impact: a Boarding canonical page advertised Grooming, Vet Care, and other questions in `FAQPage` schema. Fix: category schema now uses a separate filtered set while the all-category Alpine payload remains intact; a regression test covers it.
- Global search advertised products and FAQs but indexed only selected pages, tools, Guides, and Knowledge Articles. Impact: public DB-backed content was undiscoverable through the global search contract. Fix: published Products and non-services FAQs are now projected into the existing ranked endpoint; no new engine was introduced.
- Newsletter and testimonial intake lacked the contact/booking paths’ named rate limits and honeypots. Impact: weaker abuse resistance and inconsistent form contracts. Fix: added named throttles, server-side `website` honeypot validation, hidden fields, and focused tests.
- Architecture documentation still described the shop as config-backed and the newsletter model as the runtime authority. Fix: updated the CMS target and audit documents to reflect DB-backed Products, `NewsletterSubscriber`, BookingRequest, search, publication, and media boundaries.
- Media conversion temp directories were untracked runtime residue. Fix: added `/storage/media-library/` to `.gitignore`; ambiguous existing files were preserved.

## 4. Runtime reconciliation

The applied runtime has no `service_prices` table and no Pricing Catalog/Service Price Filament resource. `config/waggies_shop.php` is absent from the current tree; Products are read from `products`. The old `NewsletterSubscription` class remains only as a deprecated compatibility alias and is not used by application paths. FAQ config remains migration/parity input, not the public read authority. No Services or Relocation CMS, generic Page resource, generic Settings pricing, or generic Media Manager exists.

## 5. Route / URL reconciliation

The route inventory contains 97 non-vendor routes. The important public contracts remain `/shop`, `/shop/{product:slug}`, GET/POST `/book`, `/faq` without standalone FAQ detail URLs, `/about/gallery` without Gallery item URLs, slug-only Guides and Knowledge Base routes, and their historical slug redirects. No stale Pricing CMS, Services CMS, or Relocation CMS routes were found. Product slugs are included in `PublicUrlCatalog`; operational records are not.

## 6. SEO/search reconciliation

The existing canonical, robots, sitemap, breadcrumb, and structured-data infrastructure remains in place. The concrete SEO correction was category-scoped FAQPage schema. Search remains a noindex JSON endpoint and now intentionally covers selected pages/services/tools plus published Guides, Knowledge Articles, Products, and FAQs. Draft, archived, future, and operational records are excluded. Sitemap tests and search tests pass.

## 7. Filament reconciliation

All 17 active resource classes were audited: Booking Requests, Business Hours, Business Profile, Clinical Content, Clinical Reviews, Clinical Sources, Clinical Tool Reviews, Contact Enquiries, FAQs, Gallery Items, Guides, Job Openings, Knowledge Articles, Medication References, Newsletter Subscribers, Products, and Testimonials. Tables/forms use their persisted models and current publication, moderation, media, and clinical-policy semantics. Empty and populated states rendered in the authenticated browser; clinical routes correctly denied the ordinary seeded user. Empty ServicePrices and Services directories contain no active resource classes.

## 8. Form/customer journey reconciliation

Public browser journeys verified rendering and navigation for Home → Services → Booking, Home → Services → Contact, Home → Shop → Product, Home → Guides/Knowledge Base, Home → Gallery/Testimonials, and the FAQ path. Feature tests verify booking/contact validation and persistence, newsletter normalization/duplicate handling/honeypot rejection, testimonial pending moderation/photo validation/honeypot rejection, and public approved-only rendering. Booking/contact success and WhatsApp continuation are covered by the existing request contracts; no new scheduling or checkout behaviour was added.

## 9. Database proof

The current PostgreSQL schema is reconstructed from migrations and legitimate configuration-owned seed data; no unrecovered SQLite records are claimed as migrated. Current proof counts include Guides 4, Knowledge Articles 10, FAQs 52, Gallery Items 23, Testimonials 12, Products 10, Booking Requests 0, and Media 0. PostgreSQL 18.6 and pgvector 0.8.1 are active through the local development stack.

## 10. Media proof

Managed collections are owned by Product, GalleryItem, Guide, KnowledgeArticle, and Testimonial models, using the public disk with the established image/cover/photo/content-attachment collections and conversions. The live `media` table has 0 rows, so real Waggies-owned managed-media mutation proof remains unavailable in this dataset. The 33 source-controlled editorial JPGs are all referenced by current application data or source; the current runtime contains no Unsplash CDN image URL. A stale remote URL observed in a pre-existing browser saved-list item is client-local state, not a current application asset. Key public asset checks returned HTTP 200, image alternatives were present in the audited surfaces, and media architecture tests passed.

## 11. Browser proof

### Authenticated Filament

Anonymous `/admin` access redirected to `/admin/login`. The local seeded account authenticated in the browser, rendered the dashboard and all active resource surfaces, and signed out successfully. Read-only form and relationship checks covered Guides, Knowledge Base, FAQs, Gallery, Testimonials, Products, Booking, Contact, Newsletter, Business Profile, Business Hours, Job Openings, and Medication References; clinical governance routes returned 403 to the ordinary user. Real content mutation and managed-media upload/replacement/deletion were not performed because the local dataset has no safe disposable media fixture.

### Public

The local browser rendered Home, Services, Pricing, Booking, Contact, Shop, Product detail, Guide detail, Knowledge Base detail, FAQ, Gallery, and Testimonials. Browser console/error logs were empty after the view cache was rebuilt. The first Testimonials navigation collided with concurrent compiled-view file activity and returned a transient Windows `rename(...): Access is denied`; clearing/rebuilding the view cache and reloading rendered the page successfully with no logs.

### Permission-gated or not verified

Clinical reviewer-only approval/withdrawal flows require a reviewer identity and approved clinical records, neither of which is seeded locally. Real disposable browser media upload/replacement/deletion remains unverified because the local `media` table is empty; no real content was altered to manufacture proof. General staff-role/RBAC separation remains a permission-required product decision because no active role model exists.

## 12. Tests

- Focused remediation tests: FAQ/SEO 11 passed (122 assertions); intake/public interaction 11 passed (51 assertions).
- Full PHPUnit suite: 154 passed, 1,551 assertions, using `php artisan test --compact`.
- PHPStan: passed, 193 files, no errors.
- Pint: passed with `vendor/bin/pint --test --format agent`.
- PHP lint: covered by the passing test/static-analysis baseline.
- Blade cache: passed.
- Vite production build: passed with Vite 7.3.6.
- Composer audit: no security vulnerability advisories found.
- Fresh schema: the migration suite is intended to run against PostgreSQL 18.6, with the pgvector extension enabled by `2026_09_23_115837_enable_pgvector_extension`; the final schema omits `service_prices`.
- `git diff --check`: passed.

## 13. Documentation

Updated `WAGGIES-ARCHITECTURE-AUDIT.md` with this current evidence report and reconciled stale runtime statements. Updated `WAGGIES-CMS-TARGET-ARCHITECTURE.md` to describe DB-backed Products/FAQs, published Product sitemap/search behaviour, the bounded catalogue boundary, and the current CMS/media boundary. Added `/storage/media-library/` to `.gitignore`.

## 14. Remaining legitimate limitations

The development database currently has no managed Media Library rows, so real upload/replacement/deletion proof remains unverified. Clinical reviewer workflows require a separately approved reviewer fixture and governed records. Granular general staff-role/RBAC authorization is not implemented; the current architecture relies on protected Filament authentication plus the existing clinical-reviewer policy boundary rather than inventing an RBAC system. Future operational scheduling, commerce, and account requirements remain outside this reconciliation.

## 15. Files changed

Batch-30-specific changes are in `app/Http/Controllers/FaqController.php`, `app/Http/Controllers/SearchController.php`, `app/Providers/AppServiceProvider.php`, `app/Http/Controllers/NewsletterController.php`, `app/Http/Controllers/TestimonialController.php`, `routes/web.php`, the footer/testimonial form Blade components, their focused Feature tests, `.gitignore`, and the two architecture documents. The working tree also contains intentional accumulated Batch 25–29 changes and new CMS/migration files; they were preserved. A recoverable pre-change backup exists at `C:\Users\Bridges\Herd\waggies-batch30-prechange-20260922`.

## 16. Scope discipline

No scheduling system, payment system, checkout, inventory, CRM, customer accounts, generic CMS, Page Builder, generic Settings, pricing CMS, Services CMS, Relocation CMS, or Media Manager was introduced.

## 17. Batch 2 search and assistant architecture

The public search contract remains `GET /api/search`, but its source of truth is now a `search_documents` projection. Static public routes and tools are owned by `SearchCatalog`; published Guides, Knowledge Articles, Products, FAQs, and open Job Openings are synchronized by `SearchContentObserver` and can be rebuilt with `php artisan waggies:search-rebuild`. The endpoint uses Laravel Scout's database engine, filters unpublished rows at query time, caps results at eight, returns opaque result keys, and retains `X-Robots-Tag: noindex, nofollow`.

The local PostgreSQL implementation deliberately uses Scout's database engine because it requires no external service. It remains substring matching rather than relevance-ranked full-text search; PostgreSQL full-text/trigram search is a future optimization, not part of this migration. The pgvector extension is now available in the same server as a foundation for future application-managed semantic retrieval, but no local embedding/chunk/similarity path is claimed yet. Turbopuffer, Typesense, Meilisearch, and Algolia are not part of the Waggies local architecture.

The assistant uses the Laravel AI SDK behind `POST /api/assistant` and `POST /api/assistant/stream`. It has no write tools, no customer-record access, bounded message/history input, named rate limits, explicit medical/emergency guardrails, and a safe 503 response until a provider is configured. Public content is written to a local knowledge manifest with `php artisan waggies:knowledge-sync`; an optional approved-only provider vector store can be enabled with `WAGGIES_AI_VECTOR_STORE_ID` or created deliberately through configuration. Provider keys remain environment-only. The assistant must not be treated as a booking, diagnostic, or authoritative availability system.

## Batch 3 clinical governance

Clinical publication is a separate, fail-closed domain. `clinical_sources` stores bibliographic provenance, source type, jurisdiction, version, evidence level, status, hashes, and conflict flags. `clinical_contents` separates clinical type, clinical review lifecycle, public publication state, risk level, jurisdiction, version, review due date, source conflicts, and withdrawal metadata. Sources, review decisions, current-version approvals, and snapshots are retained through the pivot, `clinical_reviews`, and `clinical_content_versions` tables.

The publication gate requires an active non-conflicted source, an approval review for the current content version, approved clinical state, published state, no withdrawal, and a current review date. Clinical content cannot be approved or published by simply changing a CMS status. `User::is_clinical_reviewer` is false by default; no reviewer identity or veterinary credential is seeded. Withdrawal hides content from public rendering and search while retaining review history.

Medication data is structured by medication, formulation, jurisdiction, active ingredients, strength/concentration, indication, route, restrictions, and safety fields. Dose display is disabled unless a formulation is linked to eligible clinical content and has explicit source-backed dose data. NAFDAC registration is product information, not clinical suitability or Waggies endorsement. The public guide therefore defaults to human-medication warnings and veterinary escalation rather than a generic OTC dose table.

The symptom tool is triage-only and prioritizes configured red flags and species context; it does not diagnose or assign urgency from raw symptom count. Vaccination and parasite tools distinguish risk-based planning from legal requirements and avoid fixed unsupported schedules. The nutrition calculator labels RER/MER output as an estimate, not a prescription. Existing Guides and Knowledge Base records remain editorial content unless explicitly linked to governed clinical metadata; once linked as clinical or mixed, their public/search eligibility follows the clinical gate.

Search synchronization checks governed content through the same public eligibility rule. The assistant has a deterministic high-risk safety boundary for poisoning, dangerous medication ingestion, dose requests, diagnosis requests, seizures, breathing difficulty, collapse, bleeding, urinary obstruction, and major trauma. Remaining clinical sources, reviewers, Nigerian legal/product facts, local epidemiology, and product-specific dosage records must be supplied and approved by the business and a real qualified reviewer before publication.

## Current clinical ownership and Filament workflow (2026-09-23)

The resolved boundary is that Guides and Knowledge Articles remain the only body-bearing public clinical-adjacent content families. Guides are long-form editorial explainers; Knowledge Articles are support and search-oriented articles. Both remain independently editable, searchable, indexable, media-capable records with optional clinical governance metadata. `ClinicalContent` is not a third editorial family: it is an internal governance wrapper that links to a Guide or Knowledge Article, carries the clinical lifecycle, and can optionally gate a structured Medication record.

| Record | Owner | Staff surface | Public manifestation |
| --- | --- | --- | --- |
| Guide | Content/editorial staff | Guides | `/guides` and `/guides/{slug}` |
| Knowledge Article | Content/editorial staff | Knowledge Articles | `/knowledge-base` and `/knowledge-base/{slug}` |
| ClinicalContent | Developer/import plus clinical workflow | Reviewer-only internal edit surface | No body, URL, SEO, or direct public page |
| ClinicalSource | Clinical reviewer/import | Reviewer-only `Clinical references` | No current public citation consumer |
| ClinicalReview | Workflow-generated clinical record | Reviewer-only read-only history | No public manifestation |
| ClinicalToolReview | Developer-owned dormant infrastructure | Hidden and policy-blocked | No current public tool consumes it |
| Medication | Developer/import; permitted reference maintenance | `Medication references` | Structured public data is gated, but the current safety-guide Blade does not render medication rows |

The Filament workflow now reflects that ownership. Ordinary staff can maintain the existing editorial families and see the Medication reference list, but cannot open clinical governance records. Clinical reviewers can inspect governed content, references, and review history. Clinical reviews are generated by workflow actions rather than entered through CRUD. A reviewer must approve a current-version item with an active, non-conflicted source before publication; publication then runs the existing fail-closed gate. Request-changes and withdrawal are explicit actions with notes/reasons. Raw status editing, clinical-content creation, review creation/editing, and destructive review actions are no longer exposed.

The Medication relationship is a scoped relationship selector, not a database-ID input. It only offers approved and published clinical content. No public routes, public clinical records, new data, local RAG retrieval, embeddings, chunks, or citations were added. The existing pgvector extension remains infrastructure only; search and the assistant still use their existing bounded paths.

Two limitations are intentionally left for a separately approved product decision. First, the existing medication controller queries eligible rows but the current safety-guide view does not render them; implementing a reviewed medication display would be a new public clinical feature and requires approved content, presentation, and citation requirements. Second, the current schema has no clinical-content author/creator identity, so a distinct creator-versus-approver self-approval rule cannot be asserted without a schema and governance change. The local development database currently contains no ClinicalContent, ClinicalSource, ClinicalReview, ClinicalToolReview, or Medication records, so these paths were verified structurally and through authorization/workflow tests rather than with real clinical data.
