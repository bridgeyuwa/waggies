# Waggies Laravel Architecture, Design-System, and Product-Readiness Audit

**Audit date:** 2026-09-20  
**Scope:** standalone Laravel application in `C:\Users\Bridges\Herd\waggies`  
**Method:** repository inventory, route/controller/config/view inspection, package/version inspection, static searches, browser inspection of representative live pages, Debugbar request/query inspection, PHPUnit, and PHPStan.  
**Change policy:** application code, routes, dependencies, migrations, and generated assets were not changed. This report is the only artifact created by this audit.

Labels used below:

- **Observed** — directly evidenced in the repository or running application.
- **Recommendation** — proposed implementation direction.
- **Optional** — useful idea, but not required for the next consolidation pass.

## 1. Executive summary

**Observed:** Waggies is a coherent Laravel 13 server-rendered application with Blade pages, Alpine.js interactions, Tailwind CSS v4, configuration-backed content, a centralized SEO/head layer, a reusable component directory, and a working public route surface. It is not a generic starter anymore, but several starter/tooling residues remain.

**Strengths:**

- 38 page views, 40 Waggies Blade components, 17 controllers, and 69 registered routes are already organized into recognizable public domains.
- Public pages are largely composed from components rather than enormous page templates.
- `Controller::setPageHead()` and `AppServiceProvider::Head::defaults()` give metadata and schema a sensible ownership boundary.
- The public navigation, skip link, heading hierarchy, semantic landmarks, form labels, and 404 experience are strong in the inspected browser states.
- Representative successful pages had no browser console warnings/errors and no Debugbar exceptions.
- PHPUnit passes: **21 tests, 161 assertions**.
- Debugbar query inspection found **no N+1 groups, duplicate query groups, or failed queries** on the inspected successful pages.

**Highest-priority findings:**

1. **Newsletter and testimonial submission are presented as successful without a durable server-side destination.** `NewsletterController` validates and flashes success but does not persist, dispatch, or send anything (`app/Http/Controllers/NewsletterController.php:10-15`). The testimonial flow is client-only (`resources/js/app.js:624-635`).
2. **The cost calculator owns hard-coded rates in JavaScript** while pricing also exists in configuration (`resources/js/tools-calculators.js:95-108`; `config/waggies_pricing.php`). This creates a business-rule drift risk.
3. **Placeholder/stock-photo markers are visible in production-facing content**, including `client photo required` alt text (`config/waggies_boarding.php:7`, `app/Http/Controllers/ServicesController.php:17`).
4. **The UI system has converged in spirit but not in API.** Semantic utility classes, raw Tailwind utilities, inline styles, raw `w-cta` classes, and several one-off component variants coexist.
5. **Hero and service-detail responsibilities have clear duplication.** `legacy-image-hero` is used once and substantially overlaps a branch of `cover-hero`; `service-detail` and `service-page-enhanced` share the same service-page spine.
6. **PHPStan reports three existing findings** in `FaqController` and `RelocationController`; the test suite does not cover static-analysis correctness.
7. **Local Debugbar measured several slow requests**, up to 8.76 seconds for `/`, while the database work was only a few milliseconds and two or three queries. This points first to local/dev instrumentation, view rendering, browser asset loading, or environment overhead—not an identified database bottleneck.

**Readiness decision:** The application is ready for a controlled standardization/consolidation implementation in bounded batches. A few product contracts should be decided first: what newsletter and testimonial “submit” mean, whether configuration remains the editorial source of truth, which pricing source is canonical, how real media will be supplied, and whether the current Alpine-first interaction model remains the public-site default.

## 2. Current architecture

**Observed:** The application is a conventional Laravel monolith with a public web surface and a Filament admin foundation.

| Layer | Current shape | Audit assessment |
| --- | --- | --- |
| Framework | Laravel 13.31.0, PHP 8.5.10 | Current and appropriate |
| Routing | `routes/web.php`; no application API routes file | Appropriate for a server-rendered public site; `/api/*` is currently browser-facing form/search plumbing |
| Controllers | Resource-oriented public controllers; several invokable controllers; `ToolsController` owns 11 tool actions | Understandable, but the tools/config boundary should be made explicit |
| Domain models | Only `App\Models\User` is present | Intentional for config-backed content today; not CMS-ready without a domain model decision |
| Content | PHP config arrays under `config/waggies_*.php` plus controller-owned page data | Fast and deterministic, but editorially difficult to maintain at scale |
| Views | Blade layout, page views, `components/waggies/*` | Good foundation; component APIs need standardization |
| Interactivity | Alpine registered from `resources/js/app.js`; two calculator modules | Capable, but `app.js` is a large global registry and several behaviors are client-only |
| Styling | Tailwind v4 plus a 1,155-line CSS layer of tokens and semantic utilities | Strong design intent, mixed adoption |
| Admin | Filament provider exists; no domain resource inventory was found | Future CMS/admin foundation, not an active content back office yet |
| Persistence | SQLite framework tables for sessions/cache/jobs/users; no Waggies domain schema | Fine for static prototype; insufficient for submissions, editorial content, media, or audit history |

**Observed:** The app is served by Herd at `https://waggies.test` during browser inspection, while `php artisan about` reports `laravel.test` as the configured application URL. This environment/canonical URL mismatch should be verified before production deployment because canonical URLs, sitemap URLs, and generated links depend on the resolved URL configuration.

## 3. Design-system inventory

**Observed:** The design system is real, not absent. Its source of truth is distributed across `resources/css/app.css`, Blade component classes, Tailwind utilities, config content, and inline styles.

Current inventory:

- Brand tokens in `resources/css/app.css:25-115`.
- Generic/semantic token aliases in `resources/css/app.css:117-207`.
- Typography, card, navigation, form, and shell utilities in the component layer beginning around `resources/css/app.css:265`.
- CTA contract around `resources/css/app.css:906-970`.
- Reduced-motion, forced-colors, and print handling around `resources/css/app.css:1080-1155`.
- Blade component primitives: `button`, `input`, `select`, `icon`, `section-heading`, `page-header`, `cover-hero`, `faq-accordion`, `article-card`, `service-card`, `shop-product-card`, and related composites.
- Raw utility classes remain common: `text-sm` appears approximately 297 times, `px-4` approximately 196 times, `rounded-full` approximately 146 times, and `max-w-7xl` approximately 94 times across Blade templates.

**Assessment:** The next step is not to invent a new visual language. It is to make the existing language internally consistent and give it a small, documented set of canonical APIs.

## 4. Typography

**Observed:** `resources/css/app.css:1` imports Cormorant Garamond and DM Sans. The system uses serif display/headline styling and sans-serif body/UI styling, which suits a premium pet-care brand.

Current semantic classes include `text-display`, `text-h1`, `text-h2`, `text-h3`, `text-body`, `text-eyebrow`, `text-label`, and `text-meta`. They coexist with raw combinations such as `font-serif text-3xl font-bold`, `text-lg`, and arbitrary sizes such as `lg:text-[3.25rem]`.

**Recommendation:** Keep the type pairing. Define a short scale with explicit roles:

- Display/hero title
- Page title
- Section title
- Card title
- Body/lead/body-small
- Eyebrow/label/meta

Then migrate repeated public-facing headings to those roles gradually. Do not ban raw Tailwind typography where it expresses a genuinely local composition; ban only unexplained deviations in shared components.

**Risk:** The same semantic role can currently render through several class recipes, so a token change will not reliably propagate to the full site.

## 5. Color

**Observed:** The palette is strongly defined around purple primary tones, warm beige secondary tones, white/surface variants, dark ink, muted text, semantic status colors, and gold/silver/bronze accents. Tokens use OKLCH and are mapped into Tailwind-compatible names.

Important token ownership is in `resources/css/app.css:25-207`. However, raw values remain in Blade and PHP/JS, including `#25D366` for WhatsApp, `#6B2C91` in schema configuration, raw `rgba(0,0,0,...)` hero overlays, and arbitrary OKLCH shadow values.

**Recommendation:** Preserve the existing palette and brand beige. Consolidate only duplicate/near-duplicate semantic names, and introduce explicit tokens for:

- Hero scrim and image overlay strength
- Surface elevation levels
- Focus ring and error/success states
- Fixed-layer/z-index tiers
- External-brand exceptions such as WhatsApp

**Do not standardize:** Do not force every external brand color or image overlay into the purple palette. Those are legitimate exceptions when named and scoped.

## 6. Spacing

**Observed:** The system repeatedly uses `py-20`, `py-16`, `py-12`, `py-24`, `px-4`, `md:px-10`, and `lg:px-12`. This is a useful implicit rhythm. `section-pad` also exists in CSS (`resources/css/app.css:399-410`).

The issue is not arbitrary spacing everywhere; it is that the same section role is sometimes implemented with the shared class and sometimes with hand-written padding. Radius and shadow usage are similarly broad: `rounded-full`, `rounded-2xl`, and `rounded-xl` dominate, while `shadow-sm` and `shadow-soft` are common but supplemented by arbitrary shadows.

**Recommendation:** Keep the current rhythm and document a small section scale, card scale, and control scale. Adopt shared section/container primitives only where they reduce repeated shell markup.

## 7. Container and layout system

**Observed:** The dominant public container is `max-w-7xl` with `px-4 md:px-10 lg:px-12`. Narrow reading columns use `max-w-2xl`, `max-w-3xl`, and `max-w-prose`; these are appropriate distinctions.

The site uses a good mix of one-column mobile layouts, two-column content, 12-column desktop grids, cards, comparison tables, sticky table columns, and full-bleed escapes.

**Recommendation:** Establish three named layout contracts:

1. `page-container`: site-wide maximum width and horizontal gutters.
2. `content-column`: readable text width.
3. `section-shell`: vertical rhythm and surface/border behavior.

Implement these as classes or small Blade primitives only after confirming that they preserve the current page snapshots. Do not replace every `mx-auto max-w-* px-*` expression automatically.

## 8. Positioning and layout problems

**Observed:** Several patterns are structurally fragile or expensive to reason about:

- `cover-hero` contains three rendering branches with repeated absolute layers and multiple height contracts (`resources/views/components/waggies/cover-hero.blade.php:28-87`).
- `legacy-image-hero` reproduces the bottom-aligned background-hero pattern (`resources/views/components/waggies/legacy-image-hero.blade.php:3-16`).
- `tool-cta` uses a full-bleed `left-1/2 w-[100vw] -translate-x-1/2` escape. This can be correct, but it should be a named layout primitive rather than an unexplained local trick.
- The custom select component positions a fixed listbox from `getBoundingClientRect()` in `resources/js/app.js`; this needs keyboard, zoom, scroll, and viewport-edge QA.
- Fixed overlays use several literal z-index tiers: search, cart, toast, mobile navigation, and floating actions. There is no visible z-index contract.
- Hero and page sections use many bespoke minimum heights such as 390, 420, 428, 449, 454, 480, 560, and `80vh`.

**Recommendation:** Consolidate the layering and image-position contracts first. Treat bespoke heights as intentional content contracts until page-by-page visual QA proves otherwise.

## 9. Hero and page-header inventory

Current hero/header families:

| Component | Uses | Assessment |
| --- | --- | --- |
| `cover-hero` | Main service/about/relocation/public image heroes | Canonical candidate, but over-configured and branch-heavy |
| `legacy-image-hero` | Careers only | Duplicate responsibility; clear migration candidate |
| `page-header` | Static page headers such as pricing/gallery/partnerships | Reasonable separate composition for non-image pages |
| `tool-hero` | Tool pages | Tool-specific framing is defensible; compare with `page-header` before merging |
| Home hero | Inline in `pages/home.blade.php` | Should share the same image/scrim/title/CTA primitives without forcing the home composition into a generic component |

**Recommendation:** Create one deliberate image-hero contract with small, named variants for alignment, content density, and image technique. The implementation may keep a separate `ImageHero` and `BackgroundHero` primitive if the loading/accessibility contracts differ. Remove `legacy-image-hero` only after migrating its one caller and visually checking careers.

**Important accessibility note:** The canonical image branch uses an actual `<img>` with alt text, while background-image branches use a visually hidden alt string. Prefer the real-image contract for meaningful hero images; use decorative backgrounds only when the image is genuinely non-content.

## 10. Component inventory

**Observed:** The component library is substantial and mostly well named. It includes:

- Shell: `navbar`, `footer`, `mobile-bottom-nav`, `floating-actions`, `skip-link`.
- Navigation/content: `breadcrumb-strip`, `section-heading`, `page-header`, `article-toc`, `share-row`.
- Content cards: `service-card`, `article-card`, `shop-product-card`, `pricing-tier-card`, `testimonial*`, `feature-band`, `proof-band`, `process-steps`, `service-standards`, `service-comparison`.
- Form/control primitives: `button`, `input`, `select`, `field-error`, `alert`.
- Hero/media: `cover-hero`, `legacy-image-hero`, `tool-hero`, `gallery-lightbox`, `icon`, `brand-icon`.
- Service/detail composites: `service-detail`, `service-page-enhanced`, `service-feature-grid`, `related-tools`, `tool-cta`.

**Assessment:** Reuse exists, but adoption is uneven. The most important issue is not component count; it is that raw markup often bypasses canonical components. A component vocabulary should be established around roles and composition boundaries, not around every visual variation.

## 11. Blade structure

**Observed:** There are 38 page Blade views. Public pages generally extend `layouts.app` and define one content section. No page view is over 100 lines; the three largest are roughly 75 lines. This is a good baseline.

The main layout centralizes head rendering, Vite, Livewire bootstrapping, navigation, footer, mobile navigation, global search, cart, and toast surfaces (`resources/views/layouts/app.blade.php:1-100`).

Nine page views contain local PHP/logic, and two contain direct `config()` access. Some long one-line sections reduce reviewability even when the semantic structure is sound.

**Recommendation:** Keep page views as page composition files. Move repeated data shaping into controllers/support objects only when it improves ownership. Avoid turning every small block into a component; the current page-level composition is a strength.

## 12. Alpine and JavaScript architecture

**Observed:** `resources/js/app.js` is a global registration module of approximately 759 lines, with Alpine data components for navigation, search, cart, product pages, recently viewed products, toasts, sharing, contact requests, FAQs, testimonials, gallery lightbox, guides, knowledge-base, article TOC, and the testimonial form. Calculator concerns are split into `pricing-calculator.js` and `tools-calculators.js`.

The current global behaviors are understandable, but the module now has several independent product areas. `waggiesTransportEstimate` is exposed globally (`resources/js/app.js:381-405`) and calculator registration is centralized at `resources/js/app.js:721-741`.

**Recommendation:** Split by responsibility in a future pass:

- shell: navigation, search, cart, toasts
- public forms: contact/request, newsletter, testimonial
- commerce: shop/product/recently viewed
- content: FAQ, gallery, article TOC, guides/knowledge base
- tools/calculators

Keep Alpine as the public-site interaction model unless a product decision requires server-side component state. Do not migrate everything to Livewire merely because Livewire is installed.

**Correctness concern:** `waggiesToasts` defines a `listen()` method but no `init()` call is visible (`resources/js/app.js:379`). The layout uses the Alpine component, so the event listener should be verified; as written, a dispatched toast may not be observed by the global toast surface.

## 13. Forms and interaction architecture

**Observed:** The contact flow is a deliberate client-side request builder that stores a draft in session storage and routes the final request to WhatsApp. The inspected detailed contact state includes labeled fields, custom comboboxes, conditional sections, and a disabled review action until the required inputs are complete.

**Observed:** The shop uses local storage for cart and recently viewed state. The legal controller documents local/session storage keys, including `waggies-cart` and `waggies-request-draft`.

**Observed:** Newsletter and testimonials look like real submission workflows but currently do not have corresponding persistence or outbound integration. This is a product-contract issue before it is a frontend framework issue.

**Recommendation:** Define each form as one of:

- local-only utility
- WhatsApp handoff
- server-persisted lead/submission
- external integration

Then make copy, success state, retry state, privacy notice, and tests match that contract.

## 14. Responsive behavior

**Observed:** The public site has mobile navigation, responsive grids, stacked forms, responsive service cards, mobile-friendly comparison overflow, and a 656px-ish browser inspection that remained structurally usable. The main CSS breakpoint is 768px, with a custom 1153px navigation split (`resources/css/app.css:869-884`); templates also use `sm`, `md`, and `lg` utilities.

**Risks:**

- The custom select listbox is position-dependent and needs viewport-edge testing.
- Hero minimum heights are numerous and may create excessive vertical space on short mobile screens.
- Fixed mobile navigation, floating actions, modal layers, and Debugbar can compete for viewport space.
- Long comparison tables and wide CTA groups need explicit narrow-width tests.

**Recommendation:** Define a small responsive QA matrix: 360px, 390px, 768px, 1024px, and desktop wide. Test keyboard and zoom separately from CSS width.

## 15. Icons, images, and assets

**Observed:** Public assets include a Waggies logo, favicon, three local service hero images, approximately 185 Material Symbols outlined SVGs, and seven brand SVGs. The public tree is approximately 201 files and 5.1 MB by file size measurement.

Static searches found approximately 94 Unsplash references, 21 server-rendered `<img>` tags, 13 lazy-loading hints, five `fetchpriority` hints, and two intentionally empty alt attributes. Many editorial image URLs are remote Unsplash URLs in configuration.

**Risks:**

- Remote image availability, privacy, cache headers, and layout stability are outside the app’s control.
- There is no current evidence of a Waggies-owned media pipeline in public page rendering.
- `php artisan about` reports `public/storage` as not linked; this is not a current bug while images are config/remote-based, but it matters before uploads or Media Library use.
- The same remote image IDs are repeated in several configuration files.

**Recommendation:** Decide whether production media will be remote editorial URLs, versioned local assets, or Media Library-backed assets. Do not migrate images to CMS storage until the media ownership and replacement workflow is decided.

## 16. SEO and metadata

**Observed:** Metadata is centrally shaped in `app/Http/Controllers/Controller.php:18-53`, rendered through `@head` in the layout, and supplemented by organization/local-business schema defaults in `app/Providers/AppServiceProvider.php:27-70`. Sitemap/robots handling and an explicit `PublicUrlCatalog` exist.

This is a strong foundation. Route-level controllers still carry repeated metadata arrays, but that is acceptable while pages own their metadata.

**Recommendations:**

- Verify `APP_URL`/Herd host alignment before trusting canonical and sitemap URLs.
- Add regression assertions for title, description, canonical, robots, and key JSON-LD types on representative page families.
- Preserve noindex behavior for 404 responses and non-public paths.
- Treat rich HTML article content as a future sanitization boundary before any CMS/editor source is introduced.

## 17. Route and URL architecture

**Observed:** `routes/web.php` contains named public routes for home, about, services, pricing, relocation, FAQ, legal, contact, loyalty, shop, guides, knowledge base, tools, newsletter, search, sitemap, and robots. The route list contains 69 total entries including Filament, Livewire, and framework-generated routes.

The public URL structure is coherent: `/services/*`, `/services/relocation/*`, `/guides/{slug}`, `/knowledge-base/{slug}`, `/tools/*`, `/shop/{id}`. `PublicUrlCatalog` is an appropriate explicit catalog for static/config-backed URLs.

**Recommendation:** Treat these URLs and route names as a public contract. Standardization should not rename or flatten them without a migration/redirect plan. Validate all config-backed slugs against route generation in tests.

## 18. Controllers and application structure

**Observed:** Controllers are generally resource-oriented. `AboutPagesController` groups related about pages; `ServicesController` groups service family pages; `RelocationController` groups relocation pages; `ToolsController` groups tool pages. This is consistent with the project’s Cruddy-by-Design rule.

**Recommendation:** Keep controllers grouped by resource family until a controller becomes difficult to navigate or violates a real boundary. Do not split or merge controllers solely to reduce file count.

**Static-analysis findings:** PHPStan reports:

- `app/Http/Controllers/FaqController.php:49`: schema `mainEntity()` receives `array<array<string,mixed>>` instead of the package’s contract type.
- `app/Http/Controllers/RelocationController.php:42`: same schema type issue.
- `app/Http/Controllers/RelocationController.php:84`: null coalescing on an offset PHPStan believes is always present.

These should be handled in a dedicated correctness pass, not hidden with ignores or casts.

## 19. Configuration, content, and business rules

**Observed:** Waggies content is primarily configuration-backed. Files include `waggies_about_pages.php`, `waggies_boarding.php`, `waggies_faqs.php`, `waggies_guides.php`, `waggies_knowledge_base.php`, `waggies_loyalty.php`, `waggies_pricing.php`, `waggies_relocation.php`, `waggies_service_details.php`, `waggies_shop.php`, and a large `waggies_tool_data.php` of approximately 6,482 lines.

This gives deterministic builds, easy code review, and no migration overhead. It also creates three risks:

- large associative arrays are difficult to validate structurally;
- editorial content, presentation data, and business rules are mixed;
- the same business rules can be copied into JavaScript, as with calculator rates.

**Recommendation:** Keep static configuration for stable reference data, but introduce typed data objects or validation boundaries before the config corpus grows. Establish one canonical pricing source and pass derived values to Alpine rather than re-encoding rates in JavaScript.

## 20. Content structure and editorial readiness

**Observed:** Guides and knowledge-base entries are structured arrays with slugs, image data, metadata, and HTML body content. `ArticleBodyProcessor` extracts only simple `<h2>`/`<h3>` patterns and adds deterministic IDs (`app/Support/ArticleBodyProcessor.php:13-41`). Views render processed content as HTML.

**Recommendation:** For current project-controlled config content, the implementation is serviceable. Before CMS content is allowed, add a sanitization/allowlist boundary, richer heading parsing, link/image validation, and an editorial preview/test strategy.

## 21. Dependency inventory and rationalization

Direct package versions were inspected with Composer and `package.json`.

| Dependency | Version | Evidence/role | Decision |
| --- | ---: | --- | --- |
| Laravel framework | 13.31.0 | Application foundation | Keep |
| Filament | 5.8.2 | Admin panel provider/routes | Keep for admin/CMS foundation |
| Livewire | 4.4.4 | Global boot plus Filament ecosystem; no public app Livewire components found | Keep until Filament/public strategy is decided; do not expand usage automatically |
| Laravel Head | 0.2.2 | Central metadata/schema layer | Keep |
| Breadcrumbs | 10.1.0 | Breadcrumb route/component usage | Keep |
| Schema.org | 5.0.1 | Organization/FAQ/local-business schema | Keep |
| Sitemap | 8.2.0 | Sitemap route | Keep |
| Debugbar | 4.4.3 dev | Active local profiling | Keep dev-only; verify production disabled |
| Spatie Activitylog, Backup, Data, Health, Media Library, Model States, Permission, Sluggable, Tags | installed | No meaningful public app usage found in current inventory, except package setup/future foundation | Do not remove during this audit; re-evaluate from actual ownership needs before production/CMS work |
| Webpush | 13.0.1 | No public app usage found | Re-evaluate with evidence |
| Tailwind CSS | 4.x | Vite/Tailwind styling | Keep |
| Vite | 7.x | CSS/JS build | Keep |

**Observed:** The project contains a Filament foundation and many Spatie packages ahead of current domain usage. This is acceptable as a prepared foundation, but the dependency surface should not be treated as proof that corresponding product features exist.

## 22. Accessibility

**Positive observed signals:**

- Skip link and `main` landmark are present.
- Navigation, footer, content lists, headings, labels, and buttons appear in the accessibility tree.
- Images generally have descriptive alt text; decorative image grids use empty alt plus hidden semantics intentionally.
- Focus-visible CSS, reduced-motion CSS, forced-colors CSS, and print CSS exist.
- FAQ, custom selects, gallery lightbox, and contact controls expose semantic roles in the inspected states.
- The 404 page has a clear heading, recovery links, and a search action.

**Risks requiring targeted QA:**

- Custom listbox/combobox keyboard behavior and screen-reader announcements.
- Background-image heroes and the relationship between the visual image and hidden alt text.
- Disabled review/submit controls and error focus/announcement behavior.
- Fixed overlays and focus trapping in search/cart/assistant/lightbox modals.
- Color contrast of muted text, translucent hero copy, and hover-only affordances.

**Assessment:** Good semantic intent; not an accessibility sign-off. Run automated and manual checks after each component consolidation batch.

## 23. Performance

Debugbar request records from the inspected local browser run:

| Route | Debugbar duration | Queries | Memory | Exceptions | View templates |
| --- | ---: | ---: | ---: | ---: | ---: |
| `/` | 8.76s | 3 | 5 MB | 0 | not sampled in final summary |
| `/services` | 4.56s | 2 | 6 MB | 0 | 259 |
| `/services/grooming` | 3.12s | 2 | 5 MB | 0 | not sampled in final summary |
| `/tools/symptom-checker` | 7.57s | 2 | 6 MB | 0 | 146 |
| `/shop` | 1.68s | 2 | 5 MB | 0 | 114 |
| `/guides/preparing-pet-boarding` | 2.07s | 2 | 5 MB | 0 | 99 |

**Observed:** Query analysis for the inspected successful pages reported no failed queries, duplicate query groups, or N+1 groups. Query time was approximately 20–31ms in the sampled requests, so the slow durations are not currently explained by database access.

**Likely areas to measure next:** local Debugbar overhead, Blade/view instrumentation, remote image loading, Vite/dev asset delivery, icon component repetition, and production-like response timings. The symptom-checker request rendered 121 `icon` component instances according to Debugbar’s view collector; that is a useful optimization measurement, not proof of a defect.

**Recommendation:** Establish production-like performance baselines before changing architecture. Do not optimize by deleting repeated components or moving everything to client rendering without measurements.

## 24. Naming and API consistency

**Observed:** Naming is mostly clear but mixed across layers:

- `cover-hero`, `legacy-image-hero`, `page-header`, and `tool-hero` describe related presentation roles with different naming eras.
- CSS uses `w-cta`, `text-h2`, `text-h2-feature`, and raw Tailwind classes.
- Some data keys use `description`, others `descriptionBlock`; some hero calls use `primaryCta`, others `cta`.
- Service content is passed as `$page`, while route-specific page shapes differ.

**Recommendation:** Standardize data contracts around semantic names, not implementation history. Prefer `title`, `eyebrow`, `description`, `image`, `actions`, `features`, `benefits`, and explicit optional sections. Add PHPDoc array shapes or typed data objects at boundaries before CMS migration.

## 25. Dead, residual, or transitional structure

**Observed candidates:**

- `legacy-image-hero` has one caller: `resources/views/pages/about/careers.blade.php:5`.
- `waggiesToasts.listen()` appears not to be wired through Alpine initialization (`resources/js/app.js:379`).
- `waggies-recent-searches` is documented in legal/storage copy, but no corresponding JavaScript use was found in the application scan.
- Livewire is globally loaded in the layout, but no application Livewire component or `wire:` directive was found outside package/admin foundations.
- Several installed Spatie packages do not have current application references.
- `.agents/skills` and `.claude/skills` contain overlapping guidance copies; this is tooling upkeep rather than runtime dead code.
- `composer.json` still describes the generic `laravel/laravel` skeleton, despite the app being Waggies.
- `README.md` contains both Waggies material and generic Laravel/Boost starter material.
- `public/storage` is not linked in the current local environment.

**Recommendation:** Confirm each item through ownership before removal. Remove only after a usage search, route/browser check, and package/admin dependency check.

## 26. Tests and quality gates

**Observed:** `php artisan test --compact` passed with **21 tests and 161 assertions** in 46.71 seconds. Existing feature coverage includes about pages, contact, loyalty, pricing, relocation routes, SEO/search/sitemap, and examples.

**Observed:** `vendor/bin/phpstan analyse --no-progress` failed with the three findings listed in Section 18.

**Coverage gaps:**

- No evidence of JavaScript/component interaction tests for custom selects, cart, contact draft, calculators, testimonial flow, gallery, or toasts.
- No regression test proves newsletter persistence/integration because there is currently no persistence/integration behavior.
- No test confirms a submitted testimonial reaches a review queue or admin destination.
- No browser-level performance or accessibility budget is established.

**Recommendation:** Add tests only when behavior contracts are decided. Do not add tests for purely stylistic consolidation, but do add regression coverage for forms, canonical URLs, calculator source-of-truth, and any component API migration.

## 27. Project-guideline and AI-guidance audit

**Observed:** `AGENTS.md` and `CLAUDE.md` provide detailed Laravel/Boost rules. `.ai/rules` exists with controller-boundary and reuse-before-reinventing guidance, but `.ai/rules/index.md` is missing even though project instructions expect it to map applicable rule files.

**Observed:** `boost.json` enables Laravel Boost guidance/MCP and lists relevant skills. The project also contains overlapping `.agents/skills` and `.claude/skills` trees.

**Recommendation:** Repair the rule index and document the canonical source of agent guidance in a separate tooling-maintenance change. Do not duplicate or broaden rules during UI/application standardization.

## 28. Proposed canonical design system

**Recommendation:** Preserve the existing Waggies visual direction and formalize it as:

- **Brand:** deep purple primary, warm beige secondary, calm light surfaces, dark purple ink.
- **Type:** Cormorant Garamond for editorial/display emphasis; DM Sans for body, labels, controls, and metadata.
- **Shape:** rounded-xl/2xl cards, pill tags/controls, restrained borders, soft elevation.
- **Layout:** one site container, one readable content column, one full-bleed escape primitive, explicit section rhythm.
- **Controls:** one button contract, one input contract, one select/combobox contract, one field/error contract.
- **Motion:** short transform/opacity transitions, reduced-motion fallback, no motion required for comprehension.
- **Layers:** named shell, dropdown, modal, toast, and mobile-navigation tiers.
- **Media:** explicit loading priority, meaningful alt ownership, decorative-image contract, and image aspect-ratio contract.

## 29. Proposed component vocabulary

**Recommendation:** Canonical vocabulary for future work:

**Primitives:** `icon`, `brand-icon`, `button`, `field`, `input`, `select`, `textarea`, `field-error`, `badge`, `divider`.  
**Layout:** `page-container`, `content-column`, `section`, `stack`, `cluster`, `responsive-grid`, `full-bleed`.  
**Headers:** `page-header`, `image-hero`, `tool-header` (only if tool-specific semantics remain).  
**Content:** `section-heading`, `card`, `article-card`, `service-card`, `pricing-card`, `faq-accordion`, `article-toc`, `share-row`.  
**Shell:** `navbar`, `footer`, `mobile-bottom-nav`, `floating-actions`, `search-dialog`, `cart-drawer`, `toast-region`.  
**Domain composites:** `service-detail-layout`, `contact-request`, `testimonial-submission`, `gallery-lightbox`, `calculator-shell`.

This vocabulary intentionally does not create a universal “everything component.”

## 30. Consolidation matrix

| Current structure | Evidence | Proposed action | Priority |
| --- | --- | --- | --- |
| `legacy-image-hero` + `cover-hero` bottom/background branches | One career caller; same scrim/title/CTA structure | Migrate career to the canonical image-hero contract; then delete legacy component if visual QA passes | P1 |
| `service-detail` + `service-page-enhanced` | Same description/features/benefits/packages/FAQ/CTA spine; transport adds package semantics and process sections | Share one service-detail spine with explicit package presentation and optional slots | P1 |
| Raw headings + `section-heading` | Canonical component exists but adoption is mixed | Standardize shared section heading roles; leave genuinely bespoke editorial headings local | P1 |
| Raw CTA classes + `button` component | `w-cta` appears widely outside the component | Define button API and migrate high-traffic shells/forms first | P1 |
| `input`/`select` + hand-built form controls | Primitives exist; forms still duplicate markup | Introduce `field` wrapper and migrate form surfaces incrementally | P1 |
| `page-header` + `tool-hero` | Related heading responsibilities, but tools have special framing | Compare contracts; merge only shared anatomy, keep tool-specific composition if needed | P2 |
| Global `app.js` | Several independent domains in one module | Split by responsibility without changing Alpine behavior | P2 |
| Static config arrays + JavaScript copies | Cost rates duplicated | Establish one canonical data source and serialize it to the client | P1 |

## 31. Standardization matrix

| Area | Current drift | Standardize toward |
| --- | --- | --- |
| Containers | repeated `max-w-7xl px-4 md:px-10 lg:px-12` | named container contract |
| Typography | semantic classes plus raw recipes | documented role classes with local exceptions |
| Colors | tokens plus raw hex/rgba/OKLCH | semantic tokens plus named external exceptions |
| CTA | component and raw `w-cta` variants | component/API with state and icon slots |
| Forms | mixed heights and wrappers | field/control/error contract with 44px minimum interactive target |
| Heroes | multiple image techniques and heights | explicit image/background variants and height contracts |
| Cards | repeated radius, border, shadow recipes | small card/elevation vocabulary |
| Layers | literal z-index values | named layer tokens |
| Icons | central SVG component plus inline/masked references | one icon registry contract, preserving brand SVG exceptions |
| Image loading | some lazy/fetchpriority hints, remote URLs | explicit above-fold/lazy/remote/local policy |
| Data contracts | `$page`, `descriptionBlock`, `cta` variants | typed, documented page-shape contracts |

## 32. Correctness and product bug list

| Priority | Finding | Evidence | Impact |
| --- | --- | --- | --- |
| P1 | Newsletter claims subscription without durable side effect | `NewsletterController.php:10-15` | Users may believe they subscribed when no record or message exists |
| P1 | Testimonials claim submission without server/admin destination | `app.js:624-635`; testimonial page exposes multi-step submit UI | User content and consent are discarded; trust/compliance risk |
| P1 | Cost calculator rates are duplicated in JS | `tools-calculators.js:95-108` vs `config/waggies_pricing.php` | Estimates can silently diverge from commercial pricing |
| P1 | Placeholder image copy is public | `waggies_boarding.php:7`; `ServicesController.php:17` | Brand/content quality issue |
| P2 | Toast listener may not initialize | `app.js:379` defines `listen()` but no visible `init()` | Global toasts may not render dispatched events |
| P2 | PHPStan schema/type issues | Faq/Relocation controllers | Incorrect contracts can hide real runtime problems |
| P2 | Canonical URL environment mismatch needs verification | Artisan reports `laravel.test`; Herd serves `waggies.test` | SEO links/sitemap may be wrong outside the browser host |
| P2 | Article HTML processor is not a sanitization boundary | `ArticleBodyProcessor.php:28-35` and processed HTML rendering | Future CMS content could create XSS risk if trusted-content assumptions change |
| P3 | Stale/unused storage key documentation | `waggies-recent-searches` appears documented but not implemented | Maintenance confusion |

## 33. CMS readiness

**Current status:** **Not CMS-ready, but intentionally prepared for a future CMS.**

**Observed:** Filament, Media Library, Activitylog, Backup, Permission, Sluggable, Tags, Health, and related packages are installed or configured as foundations, but the public app has no domain models/resources for guides, knowledge-base articles, services, testimonials, products, or media workflows.

Before CMS work, decide:

- Which content becomes editable and which stays code-owned configuration.
- Whether slugs are immutable, redirectable, or self-healing.
- Whether testimonials require moderation, identity verification, and media review.
- Whether pricing is editorial content, business data, or a quote engine.
- Who owns media storage and replacement rights.
- Which actions require activity logs and which require backups/health checks.
- Whether public content uses draft/published/archived states.

**Recommendation:** Do not build a generic CMS schema yet. First define the editorial boundary and canonical data contracts; then create only the domain resources that support a real workflow.

## 34. Recommended implementation order

1. Decide the five product contracts: newsletter, testimonials, pricing source, media ownership, and Alpine/Livewire boundary.
2. Resolve the existing P1 correctness issues and PHPStan findings with focused tests.
3. Freeze current URLs, metadata expectations, and representative page screenshots/AX checks.
4. Formalize tokens/layers/type/container contracts without changing brand direction.
5. Standardize primitive APIs: button, field, select, section heading, card, and image.
6. Consolidate image heroes and the service-detail spine.
7. Split `app.js` by responsibility while retaining behavior.
8. Establish image/media loading policy and replace placeholder content.
9. Add browser regression coverage for forms, custom selects, modals, and responsive breakpoints.
10. Re-measure production-like performance; only then optimize view/component or asset delivery.
11. Decide and implement CMS boundaries as a separate product/architecture phase.

## 35. Candidate `.ai/rules`

These are candidates only; no rules were recorded during this audit.

- `resources/views/components/waggies/**`: canonical component API and composition boundaries.
- `resources/css/app.css`: token ownership, permitted raw-value exceptions, and layer naming.
- `config/waggies_*.php`: config content vs business-rule ownership and shape validation.
- `resources/js/**`: Alpine ownership, storage keys, side-effect contracts, and module boundaries.
- `routes/web.php`: public URL stability and redirect requirements.
- `app/Http/Controllers/**`: metadata ownership and resource-controller boundaries.

## 36. Candidate project guidelines

**Optional:** If the team wants persistent implementation guidance, document:

- one canonical source for pricing and calculator data;
- the required server-side destination for any UI that says “submitted,” “subscribed,” or “booked”;
- the distinction between WhatsApp handoff and application persistence;
- the public-site default of Alpine unless server-side state is required;
- image alt/loading/ownership rules;
- the rule that component consolidation must preserve URLs, semantics, and responsive behavior.

## 37. Candidate custom skills

**Optional:** Future agent skills could make repeated Waggies work safer:

- `waggies-ui-conventions`: inspect and apply the canonical tokens/components.
- `waggies-content-contracts`: validate config-backed content shapes, slugs, metadata, and HTML boundaries.
- `waggies-public-flow-audit`: exercise contact, newsletter, testimonial, cart, calculator, and WhatsApp handoff flows.
- `waggies-media-audit`: check remote/local media, alt text, loading priority, replacement status, and storage ownership.

These should only be created if the team expects repeated work in those domains.

## 38. Explicit do-not-change list

During standardization/consolidation, do not change without a separate product decision:

- Public route paths, route names, slugs, or URL hierarchy.
- Current brand palette, font pairing, or overall editorial visual direction.
- WhatsApp handoff behavior in the contact flow.
- The fact that content is currently config-backed.
- Existing page information architecture or service taxonomy.
- Filament/Spatie dependencies solely because current public pages do not use every installed capability.
- The excluded legacy/external codebase; this audit is for the standalone Laravel application only.

## 39. Explicit do-not-standardize list

Do not:

- Create one universal mega-component for every hero, section, card, or page.
- Merge all controllers into a single controller.
- Migrate every Alpine behavior to Livewire by default.
- Replace all raw Tailwind utilities with custom CSS classes without a role-based reason.
- Treat every repeated class as a component candidate.
- Force external brand colors into the Waggies token palette.
- Convert every image to CMS media before the editorial/media workflow is decided.
- Remove installed packages without checking Filament, providers, config, and future ownership.
- Solve local Debugbar timings by deleting component composition before production-like measurement.
- Add documentation/rules/skills merely to describe this audit; create them only when they will be maintained and used.

## 40. Final recommended next step

The application is ready for a **controlled standardization/consolidation implementation**, but not for an unconstrained rewrite. The unresolved questions are narrow and architectural rather than foundational:

1. What does newsletter subscription persist to or notify?
2. What is the moderation/storage destination for testimonials and photos?
3. Which source is canonical for pricing and calculator estimates?
4. What is the approved production media strategy?
5. Is Alpine the public interaction default, with Livewire reserved for admin or genuinely server-stateful flows?

Once those are answered, the safest first implementation batch is: fix the two fake-success flows and PHPStan findings, freeze current route/SEO behavior, formalize tokens and primitive APIs, then consolidate the legacy hero and shared service-detail spine with targeted browser and PHPUnit regression coverage.

