<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Current Pricing and Request Contract

For the migrated application, pricing is owned by [`config/waggies_pricing.php`](config/waggies_pricing.php). Its canonical shape is a top-level `currency`, `services` keyed by service identifier, and `transport` data containing product-to-tier mappings, rate-card rules, and customer messages. `ServicesController` passes this configuration to the Pricing page, and `ContactController` passes the same configuration to the Contact request schema.

Transport estimates remain intentionally client-side presentation estimates. Both consumers call `window.waggiesTransportEstimate` from `resources/js/app.js` with `productId`, `pickup`, `dropoff`, `tripType`, `distanceKm`, `petCount`, `petSpecies`, `additionalPetSafe`, `specialRequirements`, `waitingMinutes`, `stopCount`, `stopsWithinCorridor`, `transportUrgency`, `afterHours`, and `airportDetails`. The result is a state of `MISSING_INPUTS`, `ESTIMATE`, `QUOTE_ONLY`, or `UNAVAILABLE_ROUTE`, plus `currency`, `productId`, `amount` when calculated, `missingInputs`, `reason`, and `customerMessage`.

Contact keeps the current request gateway and query compatibility: `service`, `booking`, `quote`, `veterinary`, `transport`, `relocation`, `product-inquiry`, `cart-order`, `contact`, `general`, and `tool-assistance`; legacy `book`, `save`, and `consult` intents; service aliases such as `boarding-dogs`, `boarding-cats`, `boarding-exotic`, `vet`, and `transport`; `relocation` with `tier=local`; and transport context through `transportProduct` and the JSON `transportRoute` query parameter. Existing client-side draft/cart storage, review state, and WhatsApp serialization remain unchanged.

The eventual pricing domain, authoritative server-side quoting, availability, booking, payment, inventory, and database-backed pricing are deferred to the later domain phase.

## Waggies UI Primitive Vocabulary

Public Blade composition uses a small set of semantic primitives under `resources/views/components/waggies`:

| Primitive | Responsibility |
| --- | --- |
| `button` | Native `<button>` for actions or `<a>` for navigation; variants are `primary`, `secondary`, `outline`, and `link`, with `sm` available for compact controls. Icons are composed in the slot. |
| `field` | Shared label, control slot, help text, and field-error structure. `input` and `select` consume it while dynamic Alpine forms may keep their local markup. |
| `input` / `select` | Semantic native controls with consistent IDs, required markers, help/error associations, and token-backed control styling. The enhanced select preserves the native control as its source of truth. |
| `section-heading` / `page-header` | Section-level `h2` and page-level `h1` contracts. Article, card, and editorial headings remain local when their semantics differ. |
| `card` | Low-level token-backed surface shell. Service, pricing, article, product, and testimonial cards remain domain-specific compositions. |
| `icon` / `brand-icon` | Decorative icons by default; pass `label` only when the icon itself conveys meaning. Brand assets remain separate from the Material Symbols mapping. |
| `image` | Lightweight semantic image wrapper for explicit `src`, `alt`, loading, decoding, fetch priority, and caller-supplied layout classes. Dynamic previews and hero backgrounds remain local. |
| `alert` / `field-error` | Page or inline feedback versus validation feedback. Alerts use `alert` for errors and `status` for non-error notices by default. |

Shared primitives should preserve native HTML semantics, accept attribute-bag class overrides for local composition, and expose small deliberate variants rather than unrelated modes. Domain components should consume primitives without becoming universal components.

## Controller Resource Boundaries

The HTTP layer follows the Cruddy by Design resource vocabulary without changing established public URLs:

| Controller | Resource and actions | Routes |
| --- | --- | --- |
| `HomeController` | Home page (`__invoke`) | `/` |
| `AboutController` | About page (`__invoke`) | `/about` |
| `AboutPagesController` | About editorial pages (`testimonials`, `gallery`, `careers`, `partnerships`) | `/about/*` |
| `ServicesController` | Service catalogue and service pages (`index`, `boarding`, `boardingSpecies`, `grooming`, `training`, `vetCare`) | `/services/*` except pricing and relocation |
| `RelocationController` | Relocation content (`index`, `import`, `export`, `transport`, `checklist`) | `/services/relocation`, `/services/relocation/{import|export|transport|checklist}` |
| `PricingController` | Pricing catalogue (`index`) | `/services/pricing` |
| `FaqController` | FAQ collection (`index`) | `/faq` |
| `GuidesController` | Guide collection (`index`, `show`) | `/guides`, `/guides/{slug}` |
| `KnowledgeBaseController` | Knowledge-base article collection (`index`, `show`) | `/knowledge-base`, `/knowledge-base/{slug}` |
| `ToolsController` | Tool catalogue and static tool pages (`index` plus tool page actions) | `/tools/*` |
| `ShopController` | Product catalogue and product detail (`index`, `show`) | `/shop`, `/shop/{id}` |
| `LegalController` | Legal pages (`privacy`, `terms`, `cookies`) | policy URLs |
| `LoyaltyController` | Loyalty page (`__invoke`) | `/loyalty` |
| `NewsletterController` | Newsletter subscription capture (`store`) | `POST /api/newsletter` |
| `SearchController` | Search projection (`__invoke`) | `GET /api/search` |
| `ContactController` | Contact/request gateway (`__invoke`) | `/contact` |

`RelocationController`, `PricingController`, and `FaqController` were split from `ServicesController` because they represent independently navigated resources. Relocation is a coherent resource family, so `RelocationController` owns the canonical `/services/relocation` hub and its import, export, transport, and checklist pages.

Guides and Knowledge Base remain separate editorial resources. Their controllers share only the deterministic `ArticleBodyProcessor` for extracting headings consumed by the existing TOC and injecting the existing heading IDs; filtering, metadata, related content, and search contributions remain owned by each resource. Tools remain one catalogue because the routes are static tool pages with shared presentation behavior; splitting each tool would be artificial. Contact remains a request gateway until a future `ServiceRequest` domain is introduced.

The remaining non-standard public actions are presentation boundaries: `AboutPagesController` and `LegalController` expose distinct static pages; `ServicesController` exposes named service and boarding-species pages; `RelocationController` exposes the established import, export, transport, and checklist page variants; and `ToolsController` exposes the catalogue's distinct tool pages. These actions do not create, update, or destroy hidden workflow resources, so introducing generic action classes or a dispatcher would make the current resource model less clear.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
