# Waggies Page-Data Ownership Audit

> Current clinical governance status: the former clinical editorial governance subsystem described in historical audit language below was removed by `CLINICAL-GOVERNANCE-REMOVAL-AUDIT.md`. Safety and escalation behavior remains code-owned; no clinician review workflow or governance database is part of the current runtime.

## Scope and evidence

This audit follows the ownership rule that application data used by one concrete public page is owned by the controller action for that page. It covers the sources identified by `CONFIG-CONTENT-AUDIT.md`, `CONTROLLER-CONTENT-AUDIT.md`, `DATABASE-CONTENT-ARCHITECTURE-AUDIT.md`, and `DOMAIN-ARCHITECTURE-DECISIONS.md`.

The consumer trace was checked through the public route/controller actions, the search catalogue, the related-tools component, database seed/migration consumers, and the affected feature tests. No new database entity or schema was introduced.

## Dataset ownership matrix

| Dataset | Current/source location | Consuming page(s) | Concrete owner/action | Classification | Action taken | Final source of truth |
|---|---|---|---|---|---|---|
| Services overview, comparison rows, service cards, and service CTAs | `ServicesController` page methods and private page-data methods | `/services`, service overview sections | `ServicesController::index()` | `CONTROLLER_OWNED` | Retained in the existing owning controller | `ServicesController` |
| Boarding overview | `ServicesController` boarding page data | `/services/boarding` | `ServicesController::boarding()` | `CONTROLLER_OWNED` | Retained in the existing owning controller | `ServicesController` |
| Boarding dogs, cats, and exotic pages | `ServicesController` boarding species data | `/services/boarding/dogs`, `/services/boarding/cats`, `/services/boarding/exotic` | `ServicesController::boardingSpecies()` | `CONTROLLER_OWNED` | Kept as independent page datasets | `ServicesController` |
| Grooming, training, and veterinary page data | `ServicesController` service-detail data | `/services/grooming`, `/services/training`, `/services/veterinary-care` | `ServicesController::serviceDetail()` | `CONTROLLER_OWNED` | Retained in the existing owning controller | `ServicesController` |
| Relocation overview | `RelocationController` relocation page data | `/services/relocation` | `RelocationController::index()` | `CONTROLLER_OWNED` | Retained in the existing owning controller | `RelocationController` |
| Relocation import, export, transport, and checklist data | `RelocationController` relocation page data | `/services/relocation/import`, `/services/relocation/export`, `/services/relocation/transport`, `/services/relocation/checklist` | Corresponding `RelocationController` actions | `CONTROLLER_OWNED` | Kept as independent page datasets | `RelocationController` |
| About, careers, and partnerships page data | `AboutPagesController` | `/about`, `/careers`, `/partnerships` | Corresponding `AboutPagesController` actions | `CONTROLLER_OWNED` | Retained in the existing owning controller | `AboutPagesController` plus database-backed editorial relationships where applicable |
| Loyalty page data | `LoyaltyController` | `/loyalty` | `LoyaltyController::index()` | `CONTROLLER_OWNED` | Retained in the existing owning controller | `LoyaltyController` |
| Tool catalogue | `app/Http/Controllers/ToolData.php` | Tools index, search results, related-tools component | `ToolData::catalogue()` | `GENUINELY_SHARED` | Retained as the shared catalogue; its consumers use the same tool identity, labels, routes, and descriptions | `ToolData::catalogue()` |
| Symptom-checker page dataset | Previously `ToolData::symptomChecker()` | `/tools/symptom-checker` | `ToolsController::symptomChecker()` and private `symptomCheckerData()` | `CONTROLLER_OWNED` | Moved into the owning controller | `ToolsController::symptomCheckerData()` |
| New-pet checklist dataset | Previously `ToolData::newPetChecklist()` | `/tools/new-pet-checklist` | `ToolsController::newPetChecklist()` and private `newPetChecklistData()` | `CONTROLLER_OWNED` | Moved into the owning controller | `ToolsController::newPetChecklistData()` |
| Vaccination planning dataset | Previously `ToolData::vaccination()` | `/tools/vaccination-schedule` | `ToolsController::vaccination()` and private `vaccinationData()` | `CONTROLLER_OWNED` | Moved into the owning controller | `ToolsController::vaccinationData()`; clinical safeguards remain code-owned |
| Emergency and poison guide dataset | Previously `ToolData::emergency()` | `/tools/emergency-guide` | `ToolsController::emergency()` and private `emergencyData()` | `CONTROLLER_OWNED` | Moved into the owning controller | `ToolsController::emergencyData()`; escalation guidance remains code-owned |
| Breed reference dataset | Previously `ToolData::reference()['breeds']` | `/tools/breed-finder` | `ToolsController::breedFinder()` and private `breedReferenceData()` | `CONTROLLER_OWNED` | Split from the mixed reference dataset and moved into the owning controller | `ToolsController::breedReferenceData()` |
| Behavior reference dataset | Previously `ToolData::reference()['behavior']` | `/tools/behavior-tips` | `ToolsController::behaviorTips()` and private `behaviorReferenceData()` | `CONTROLLER_OWNED` | Split from the mixed reference dataset and moved into the owning controller | `ToolsController::behaviorReferenceData()`; escalation flags remain code-owned |
| Symptom/emergency/vaccination safety rules and escalation behavior | Tool data consumed by tool-page behavior and view/client logic | Symptom checker, emergency guide, vaccination guide, behavior tips | Owning tool actions and existing safety code paths | `APPLICATION_BEHAVIOR` | Preserved in code; not converted to editorial database content | Owning controller actions and safety support code |
| Contact endpoints: `phone`, `phone_international`, `whatsapp`, `map_url` | `config/waggies.php` | Contact, booking, shared CTAs, emergency/tool CTAs, business fallback model | Global business/configuration boundary | `CONFIGURATION` | Retained as environment-aware operational configuration | `config/waggies.php`, with populated `BusinessProfile` values authoritative where established |
| Business identity defaults: `address`, `socials` | `config/waggies.php` | Contact/about defaults, business seeding, shared profile projection | Global business/configuration boundary | `CONFIGURATION` | Retained as fallback/seed configuration; not page copy | `BusinessProfile` when populated, otherwise `config/waggies.php` |
| Global navigation | `config/waggies.php` | Global navbar on every public page | Shared navigation component | `GENUINELY_SHARED` | Retained because labels, routes, and menu structure have one site-wide semantic owner | `config/waggies.php` |
| Pricing: `currency`, `cost_calculator`, `services`, `transport` | `config/waggies_pricing.php` | Pricing page, service pricing displays, pricing redirects, booking/contact flows, pricing tool | Pricing consumers | `CONFIGURATION` | Retained as the pricing authority | `config/waggies_pricing.php` |
| Testimonials and gallery seed fixtures | `database/seeders/fixtures/about_pages.php` (formerly `config/waggies_about_pages.php`) | Database seeding/migration only; public pages read records | `Testimonial` and `GalleryItem` persistence workflows | `DATABASE_BACKED` | Moved from Waggies config into a dedicated seed fixture; not used as runtime page-copy storage | Database records |
| FAQ seed fixture | `database/seeders/fixtures/faqs.php` (formerly `config/waggies_faqs.php`) | FAQ seeding/migrations; FAQ pages read records | FAQ persistence workflow | `DATABASE_BACKED` | Moved from Waggies config into a dedicated seed fixture | FAQ database records |
| Guide seed fixture | `database/seeders/fixtures/guides.php` (formerly `config/waggies_guides.php`) | Guide seeding/migration; guide pages read records | Guide persistence workflow | `DATABASE_BACKED` | Moved from Waggies config into a dedicated seed fixture | Guide database records |
| Knowledge-base seed fixture | `database/seeders/fixtures/knowledge_base.php` (formerly `config/waggies_knowledge_base.php`) | Knowledge-base seeding/migration; knowledge-base pages read records | Knowledge-article persistence workflow | `DATABASE_BACKED` | Moved from Waggies config into a dedicated seed fixture | Knowledge-article database records |
| FAQ, Guide, KnowledgeArticle, Testimonial, GalleryItem, JobOpening, Product, BusinessProfile/Hour, and Medication data | Existing models, relationships, repositories/actions, and Filament workflows | Their existing public/admin consumers | Existing domain owners | `DATABASE_BACKED` | No duplication into controllers and no schema changes | Existing database architecture |

## Config locations reviewed

The following page-content config files are absent from the runtime working tree and have no remaining runtime consumers: `waggies_about_pages.php`, `waggies_boarding.php`, `waggies_faqs.php`, `waggies_guides.php`, `waggies_knowledge_base.php`, `waggies_loyalty.php`, `waggies_relocation.php`, `waggies_service_details.php`, `waggies_tool_data.php`, and `waggies_tools.php`. The earlier config-content migration recorded by the source audit removed 23 page-content entries; this follow-up also removed the remaining database seed definitions from Waggies config.

The former `waggies_about_pages.php`, FAQ, guide, and knowledge-base definitions are now dedicated seed fixtures. Runtime content remains database-backed. Pricing remains in `waggies_pricing.php` by design. No page-specific content keys were left behind for the six datasets moved from `ToolData`.

## Final database-content runtime ownership and seeding

The five existing editorial/database-backed datasets below are populated by `Database\Seeders\WaggiesContentSeeder`, which is called directly by `DatabaseSeeder`. `DevelopmentDatasetSeeder` now owns only development business-profile, business-hours, product, and job-opening data; it no longer owns editorial content.

| Dataset | Fixture/source location | Consuming pages | Owning persistence workflow | Classification | Stable seed identity | Final source of truth |
| --- | --- | --- | --- | --- | --- | --- |
| FAQs | `database/seeders/fixtures/faqs.php` | FAQ index and FAQ category views | `WaggiesContentSeeder::seedFaqs()` → `Faq` | `DATABASE_BACKED` | `question` (the existing schema has no seed-key column) | `faqs` records |
| Guides | `database/seeders/fixtures/guides.php` | Guide index and guide detail pages | `WaggiesContentSeeder::seedGuides()` → `Guide` | `DATABASE_BACKED` | `slug` | `guides` records and `cover` media |
| Knowledge articles | `database/seeders/fixtures/knowledge_base.php` | Knowledge-base index and article detail pages | `WaggiesContentSeeder::seedKnowledgeArticles()` → `KnowledgeArticle` | `DATABASE_BACKED` | `slug` | `knowledge_articles` records and `cover` media |
| Testimonials | `database/seeders/fixtures/about_pages.php` | About/testimonials page and shared testimonial components | `WaggiesContentSeeder::seedTestimonials()` → `Testimonial` | `DATABASE_BACKED` | `author_name` + `service` + `author_location` (the existing schema has no seed-key column) | `testimonials` records; no fixture photo is supplied |
| Gallery items | `database/seeders/fixtures/about_pages.php` | About/gallery page | `WaggiesContentSeeder::seedGallery()` → `GalleryItem` | `DATABASE_BACKED` | `image` fixture path | `gallery_items` records and `image` media |

`WaggiesContentSeeder` uses `firstOrCreate` with those existing-schema identities. It maps every fixture field that has a model destination, generates model UUIDs through the models, and does not assign fixture ordering IDs to database primary-key columns. Existing records are never updated, so Filament edits remain authoritative. Guides, knowledge articles, and gallery items attach their initial fixture image through the existing Spatie Media Library collections (`cover` or `image`) only when that collection has no media; media is never cleared or replaced. The stored legacy image path remains as the model fallback. The checked-in guide/knowledge fixture paths are legacy `/cover.jpg` paths, so the seeder explicitly resolves them to the existing `/card-{slug}.jpg` public assets when the exact path is absent; missing sources fail loudly.

Fixture category lists are taxonomy metadata for the fixture files; each item’s `category` is persisted in its existing model column. The testimonial `authorInitial` is intentionally not duplicated because `Testimonial::toPublicArray()` derives it from the persisted `author_name`; all other testimonial fields with schema destinations are mapped. No category table, page table, CMS abstraction, migration, or new domain entity was introduced. The five runtime datasets remain database-backed and continue to be consumed by their existing controllers, search/sitemap behavior, views, and Filament resources.

### Before/after ownership summary

Before this seeder refactor, the database-backed definitions were split between migration files and `DevelopmentDatasetSeeder`, with repeated seeding using update-oriented writes and no initial model media. After the refactor, all five groups have one repeatable entry point (`WaggiesContentSeeder`), fixtures are the canonical seed input, model records are the runtime source, and initial media is attached through the existing media architecture without overwriting editorial/admin state.

- Page-specific application datasets moved to owning controllers: **6**.
- Database-backed fixture groups moved out of Waggies config: **4**.
- Database-backed content groups retained and now seeded by `WaggiesContentSeeder`: **5**.
- Genuinely shared application datasets retained: **1** global navigation dataset, plus shared business/configuration values documented above.
- Configuration datasets retained: **2** (`waggies.php` and `waggies_pricing.php`).
- Existing database-backed dataset families preserved: **9**; no migrations or schema changes were added.
- Ambiguous cases resolved: **fixture IDs** are ordering/identity metadata rather than database IDs; **testimonial identity** uses the strongest existing business composite because the schema has no seed-key column; **guide/knowledge image paths** use an explicit existing-asset fallback because the fixture URL layout predates the checked-in card-asset layout.

## Before/after ownership summary

Before:

```text
Concrete tool pages
        -> ToolData page datasets
        -> previously, some config-backed tool content

Shared tool index/search/related-tools
        -> ToolData catalogue

Persistent editorial content
        -> existing database models and workflows
```

After:

```text
Concrete tool page
        -> owning ToolsController action/private page-data method

Shared tool index/search/related-tools
        -> ToolData::catalogue()

Genuine configuration
        -> config/waggies.php and config/waggies_pricing.php

Persistent/editorial content
        -> existing database models and workflows

Clinical and application behavior
        -> existing code-owned safety/application paths
```

The concrete pages remain independent. The relocation species and tool-reference datasets were not merged into generic parent records, and no URL or route boundary was changed.

## Counts

- Page-specific datasets moved in this refactor: **6** (`symptomChecker`, `newPetChecklist`, `vaccination`, `emergency`, `breeds`, and `behavior`). The preceding config-content migration had already moved **23 config page-content entries**.
- Genuinely shared application datasets retained: **2 logical datasets** (the shared tool catalogue and shared global navigation/business facts).
- Configuration source boundaries retained: **2** (`waggies.php` shared/config values and `waggies_pricing.php` pricing).
- Database seed fixture groups moved out of Waggies config: **4** (about-page records, FAQs, guides, and knowledge-base records).
- Existing database-backed logical datasets/families preserved: **9** (FAQ, Guide, Knowledge Article, Testimonials, Gallery, Job Openings, Products, Business Profile/Hours, and Medication safety records).
- Database schema changes introduced by this refactor: **0**.

## Ambiguous cases and resolution evidence

1. **`ToolData::catalogue()` versus page data:** the catalogue is consumed by the tools index, `SearchCatalog`, and the related-tools component. Those consumers use the same semantic tool identity, route, label, and description. It therefore has a genuine shared owner and remains in `ToolData`.
2. **`ToolData::reference()`:** a single method returned breed data and behavior tips, but the datasets serve different public pages and have different semantic owners. They were split into two `ToolsController` private methods.
3. **Clinical-looking tool content:** symptom, emergency, vaccination, and behavior data include safety language and escalation flags. The page data now lives with the owning controller, while decision behavior remains code-owned; no editorial governance system or database copy was introduced.
4. **Database seed definitions:** testimonials, gallery items, FAQs, guides, and knowledge articles are persisted content. Their definitions moved to dedicated seed fixtures because seeding is the legitimate use, while runtime ownership remains with the database models and records.
5. **Pricing:** pricing is shared across pricing-related displays but is configuration for application behavior and is explicitly established as config-owned. It remains in `waggies_pricing.php`.
