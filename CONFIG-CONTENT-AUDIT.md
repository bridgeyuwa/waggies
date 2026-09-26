# Waggies Configuration / Page-Content Audit

## Audit scope and method

All 23 files under `config/` were inspected. Custom Waggies configuration was traced through controllers, Blade views/components, support classes, migrations, seeders, models, AI tools, and tests. The migration target is runtime page content that is currently read from configuration. Database-backed content and authoritative service pricing remain outside this phase.

## A. Audit summary

```text
Config files inspected: 23
Config keys classified: 37 custom Waggies top-level keys, plus framework/package keys

Legitimate configuration: 8 custom keys
Page content: 17 custom keys
Page presentation data: 1 custom key
Shared content: 2 custom keys
Mixed: 9 custom keys
Unknown: 0
```

The counts above classify the custom Waggies configuration surface at the top-level-key boundary. Nested fields are called out in the inventory below where a key contains mixed ownership.

## B. Migration inventory

| Config file | Config key | What it contains | Used by | Public page(s) | Classification | Proposed controller / interim owner |
| --- | --- | --- | --- | --- | --- | --- |
| `config/waggies.php` | `phone`, `phone_international`, `whatsapp`, `map_url` | Business contact and operational endpoints, with environment overrides | `BusinessProfile`, booking/contact flows, shared CTA/floating-action components, seed/migration defaults | Shared site chrome and contact/booking pages | `LEGITIMATE_CONFIGURATION` | Remains in configuration; database-backed `BusinessProfile` remains authoritative where populated |
| `config/waggies.php` | `address`, `socials` | Business identity/contact presentation defaults | `BusinessProfile`, footer/contact/about defaults, development seeder | Shared site chrome and about/contact pages | `MIXED` | Remains as seed/fallback configuration until the existing database profile is authoritative |
| `config/waggies.php` | `navigation` | Global navigation labels, descriptions, routes, icons, and menu structure | `resources/views/components/waggies/navbar.blade.php` | Every public page | `SHARED_CONTENT` | Remains shared for this phase; passing it through every controller would duplicate global content and break the component contract |
| `config/waggies.php` | `service_comparison` | Services comparison labels, feature support, and presentation pricing labels | `ServicesController::serviceComparison()` | `/services` | `PAGE_PRESENTATION_DATA` | `ServicesController` |
| `config/waggies.php` | `services` | Service comparison feature data | `ServicesController::serviceComparison()` | `/services` | `PAGE_PRESENTATION_DATA` | `ServicesController` |
| `config/waggies_about_pages.php` | `testimonials.hero`, `filters`, `bottomCta` | Testimonials page copy, filters, CTA, and hero presentation | `AboutPagesController::testimonials()` | `/about/testimonials` | `PAGE_PRESENTATION_DATA` | `AboutPagesController::testimonials()` |
| `config/waggies_about_pages.php` | `testimonials.items` | Initial testimonial records used to seed the `testimonials` table | `DevelopmentDatasetSeeder`, migration, tests | `/about/testimonials` through `Testimonial` | `DATABASE_BACKED` | Moved to `database/seeders/fixtures/about_pages.php`; runtime content stays database-backed |
| `config/waggies_about_pages.php` | `gallery.hero` | Gallery page copy and presentation | `AboutPagesController::gallery()` | `/about/gallery` | `PAGE_PRESENTATION_DATA` | `AboutPagesController::gallery()` |
| `config/waggies_about_pages.php` | `gallery.images` | Initial gallery records used to seed the `gallery_items` table | `DevelopmentDatasetSeeder`, migration, tests | `/about/gallery` through `GalleryItem` | `DATABASE_BACKED` | Moved to `database/seeders/fixtures/about_pages.php`; runtime content stays database-backed |
| `config/waggies_about_pages.php` | `careers` | Careers hero, benefits, headings, and CTA copy | `AboutPagesController::careers()` | `/about/careers` | `PAGE_CONTENT` | `AboutPagesController::careers()` |
| `config/waggies_about_pages.php` | `partnerships` | Partnerships hero, partnership types, headings, and CTA copy | `AboutPagesController::partnerships()` | `/about/partnerships` | `PAGE_CONTENT` | `AboutPagesController::partnerships()` |
| `config/waggies_boarding.php` | `index` | Boarding overview metadata, hero, cards, and included features | `ServicesController::boarding()` | `/services/boarding` | `PAGE_CONTENT` | `ServicesController::boarding()` |
| `config/waggies_boarding.php` | `species` | Independent dog, cat, and exotic boarding page datasets | `ServicesController::boardingSpecies()` | `/services/boarding/dogs`, `/services/boarding/cats`, `/services/boarding/exotic` | `PAGE_CONTENT` | `ServicesController::boardingSpecies()` |
| `config/waggies_faqs.php` | root dataset | Initial FAQ records used to seed the `faqs` table | Migration, development seeder, tests | FAQ/service/relocation pages through `Faq` | `DATABASE_BACKED` | Moved to `database/seeders/fixtures/faqs.php`; runtime content stays database-backed |
| `config/waggies_guides.php` | `categories`, `items` | Guide seed/display options and initial HTML article records | Development seeder, migration, tests | `/guides`, `/guides/{slug}` through `Guide` | `DATABASE_BACKED` | Moved to `database/seeders/fixtures/guides.php`; runtime content stays database-backed |
| `config/waggies_knowledge_base.php` | `categories`, `items` | Knowledge-base seed/display options and initial HTML article records | Development seeder, migration, tests | `/knowledge-base`, `/knowledge-base/{slug}` through `KnowledgeArticle` | `DATABASE_BACKED` | Moved to `database/seeders/fixtures/knowledge_base.php`; runtime content stays database-backed |
| `config/waggies_loyalty.php` | root dataset | Loyalty page hero, process, programme boundaries, and CTA copy | `LoyaltyController` | `/loyalty` | `PAGE_CONTENT` | `LoyaltyController::__invoke()` |
| `config/waggies_pricing.php` | `currency`, `cost_calculator`, `services`, `transport` | Canonical rates, tier prices, calculation rules, estimate/quote behavior, and pricing presentation fields | `PricingController`, `ServicesController`, `ContactController`, `BookingRequest`, `PricingTool`, tests | `/services/pricing`, service detail/booking/contact flows, AI pricing tool | `LEGITIMATE_CONFIGURATION` | Remains configuration-backed by project architecture and pricing-authority rules |
| `config/waggies_relocation.php` | `import`, `export` | Import/export metadata, hero, sections, process steps, and CTA copy | `RelocationController::detail()` | `/services/relocation/import`, `/services/relocation/export` | `PAGE_CONTENT` | `RelocationController` |
| `config/waggies_relocation.php` | `transport` | Local transport page metadata, description, benefits, packages, steps, standards, and FAQs | `RelocationController::transport()` | `/services/relocation/transport` | `PAGE_CONTENT` | `RelocationController::transport()` |
| `config/waggies_relocation.php` | `checklist` | Relocation checklist page metadata, hero, and phases | `RelocationController::checklist()` | `/services/relocation/checklist` | `PAGE_CONTENT` | `RelocationController::checklist()` |
| `config/waggies_service_details.php` | `grooming`, `training`, `vet-care` | Service detail copy, hero, feature/benefit/standard presentation, package references, and SEO metadata | `ServicesController::serviceDetail()` | `/services/grooming`, `/services/training`, `/services/vet-care` | `PAGE_CONTENT` | `ServicesController::serviceDetail()`; canonical prices remain in `waggies_pricing` |
| `config/waggies_tool_data.php` | `reference` | Breed reference and behavior educational datasets | `ToolsController`, breed/behavior views | `/tools/breed-finder`, `/tools/behavior-tips` | `PAGE_CONTENT` | `ToolsController` controller-owned data helper |
| `config/waggies_tools.php` | `catalogue` | Tool names, descriptions, icons, categories, and routes | Tools index view, related-tools component, `SearchCatalog` | `/tools` and every tool page/search | `SHARED_CONTENT` | Shared controller-layer tool catalogue owner; no config access remains |
| `config/waggies_tools.php` | `symptom_checker`, `pet_types`, `body_areas`, `symptoms`, `red_flag_ids`, `guidance` | Symptom triage content and decision data | `ToolsController`, symptom checker view/JS | `/tools/symptom-checker` | `PAGE_CONTENT` | `ToolsController::symptomChecker()` |
| `config/waggies_tools.php` | `new_pet_checklist` | Interactive checklist sections/items | `ToolsController`, checklist view | `/tools/new-pet-checklist` | `PAGE_CONTENT` | `ToolsController::newPetChecklist()` |
| `config/waggies_tools.php` | `vaccination`, `emergency` | Vaccination schedule and emergency guide datasets | `ToolsController`, vaccination/emergency views | `/tools/vaccination-schedule`, `/tools/emergency-guide` | `PAGE_CONTENT` | `ToolsController` |

## C. Consumer trace and ownership decisions

The direct runtime configuration consumers found during the audit were:

* `AboutPagesController` for about child-page presentation and database-backed testimonial/gallery records.
* `ServicesController` for service overview, boarding, boarding child pages, service details, service comparison, and canonical pricing composition.
* `RelocationController` for relocation child pages.
* `LoyaltyController` for the loyalty page.
* `ToolsController` for tool-specific view data.
* `SearchCatalog` and the related-tools component for the shared tool catalogue.
* `navbar.blade.php` for shared navigation.
* Migrations and `DevelopmentDatasetSeeder` for initial database datasets.

Blade files that read page-content configuration directly were identified in the tools index and emergency guide. They are included in the migration so views receive their existing contracts from controllers. Shared navigation, contact endpoints, and database-backed runtime content remain in their established owners. Seed definitions now live under `database/seeders/fixtures` rather than Waggies runtime config.

## D. Remaining configuration intentionally left untouched

* Laravel framework configuration (`app`, `auth`, `cache`, `database`, `filesystems`, `logging`, `mail`, `queue`, `session`) remains configuration.
* Package/integration configuration (`ai`, `backup`, `media-library`, `scout`, `services`) remains configuration.
* Waggies contact endpoints and environment overrides remain configuration until the existing database-backed business profile is the sole operational authority.
* Waggies navigation remains shared configuration for this phase because it is consumed by the global navbar on every public page.
* Waggies pricing remains configuration-backed. Rates, calculation rules, pricing tiers, and pricing authority are application behavior, not CMS/page copy.
* FAQ, guide, knowledge-base, testimonial, and gallery records remain database-backed. Their reproducible seed definitions live under `database/seeders/fixtures` and are not copied into controllers.

## E. Remaining content candidates / boundaries

There are no unknown entries. The remaining shared/configuration boundaries are business-profile fallback values, global navigation, and pricing. Existing database-backed content uses dedicated seed fixtures plus database records; no duplicate controller source was introduced.

## G. Verification

```text
Focused affected-page tests: 18 passed (145 assertions)
Full test suite: 156 passed (1,578 assertions)
Database seeding: passed
Search rebuild: passed (101 public search documents)
PHPStan: passed with no errors
Pint: passed
Migrated runtime config references: 0
Local HTTP smoke check: 25 migrated public routes returned 200
```

The former `waggies_about_pages`, `waggies_faqs`, `waggies_guides`, and `waggies_knowledge_base` definitions now live under `database/seeders/fixtures`; no runtime or seed workflow references those config keys.

## F. Migration status

The migration is complete. The verified final counts are:

```text
Config content entries migrated: 23
Controllers changed: 7
Config files changed: 8
Config keys removed: 23
Views changed: 4
Tests changed: 0
```

| Config | Key | Page | Destination | Status |
| --- | --- | --- | --- | --- |
| `config/waggies.php` | `service_comparison` / `services` | `/services` | `ServicesController` | Migrated |
| `config/waggies_about_pages.php` | `testimonials.hero`, `filters`, `bottomCta` | `/about/testimonials` | `AboutPagesController::testimonials()` | Migrated |
| `config/waggies_about_pages.php` | `gallery.hero` | `/about/gallery` | `AboutPagesController::gallery()` | Migrated |
| `config/waggies_about_pages.php` | `careers` | `/about/careers` | `AboutPagesController::careers()` | Migrated |
| `config/waggies_about_pages.php` | `partnerships` | `/about/partnerships` | `AboutPagesController::partnerships()` | Migrated |
| `config/waggies_boarding.php` | `index` / `species` | `/services/boarding` and child pages | `ServicesController` | Migrated |
| `config/waggies_loyalty.php` | root | `/loyalty` | `LoyaltyController` | Migrated |
| `config/waggies_relocation.php` | `import`, `export`, `transport`, `checklist` | Relocation child pages | `RelocationController` | Migrated |
| `config/waggies_service_details.php` | `grooming`, `training`, `vet-care` | Service detail pages | `ServicesController` | Migrated |
| `config/waggies_tool_data.php` | `reference` | Breed finder / behavior tips | `ToolsController` layer | Migrated |
| `config/waggies_tools.php` | tool datasets | Tool pages | `ToolsController` layer | Migrated |
