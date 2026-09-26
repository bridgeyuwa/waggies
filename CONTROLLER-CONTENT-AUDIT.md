# Waggies — Controller-Owned Page Data Audit

> Historical note: references to a future clinical governance boundary describe the pre-removal architecture. Current tool safety and medication decisions are code-owned without a clinical editorial governance subsystem; see `CLINICAL-GOVERNANCE-REMOVAL-AUDIT.md`.

## Scope and method

This is a read-only audit of the post-migration controller layer. `CONFIG-CONTENT-AUDIT.md` was used as the migration baseline. The audit covers the 13 page/data controller classes that either received migrated page data or directly overlap its ownership boundary:

`AboutController`, `AboutPagesController`, `ContactController`, `FaqController`, `GuidesController`, `HomeController`, `KnowledgeBaseController`, `LoyaltyController`, `PricingController`, `RelocationController`, `ServicesController`, `ShopController`, and `ToolsController`.

`app/Http/Controllers/ToolData.php` was audited as the directly related page-data class. It is not counted as a controller. `app/View/` and `app/Livewire/` do not exist in this checkout. Database models, migrations, seeders, routes, affected views, and affected feature tests were inspected only to establish ownership and coverage.

Dataset counts below use logical datasets rather than every nested array key. A dataset is split when its ownership differs, such as symptom labels versus symptom triage rules or database records versus page copy.

## A. Executive summary

| Measure | Exact count |
| --- | ---: |
| Controllers audited | 13 |
| Controller methods containing or assembling substantial page data | 36 |
| Related page-data provider methods (`ToolData`) | 6 |
| Datasets identified | 45 |
| `PAGE_CONTENT` datasets | 6 |
| `SHARED_CONTENT` datasets | 1 |
| `APPLICATION_BEHAVIOR` datasets | 2 |
| `PRESENTATION_CONFIGURATION` datasets | 1 |
| `DATABASE_CANDIDATE` datasets | 19 |
| `DATABASE_ALREADY_BACKED` datasets | 10 |
| `MIXED` datasets | 5 |
| `UNKNOWN` datasets | 0 |

The migration is a good interim ownership correction: migrated page copy is no longer read from runtime configuration, database-backed content remains database-backed, and pricing remains configuration-backed. It is not a final content architecture.

The largest architectural pressure points are:

* `ServicesController` is 1,348 lines, with approximately 1,077 lines of page data and three service/page families mixed with pricing composition and FAQ querying.
* `RelocationController` is 475 lines, with approximately 353 lines in one nested page-data provider plus overview data.
* `ToolData` is 7,315 lines, including approximately 6,482 lines of JSON reference data. It is a useful interim extraction from `ToolsController`, but its location under `app/Http/Controllers` is misleading and the reference data is a strong future content/reference boundary.
* The service pages, relocation pages, breed reference, educational tool content, and editable about-page copy are strong future database/content candidates. They should remain code-owned for now because there is no established editorial workflow for these specific records and clinical content requires governance.
* Pricing, pricing aliases, canonical rate composition, symptom red-flag decisions, and clinical safety guidance are application behavior or safety policy. They should not be moved to a CMS merely because they are represented as arrays.
* Boarding dogs, cats, and exotic pages remain separate content owners. Relocation import, export, transport, and checklist pages also remain separate owners. No consolidation recommendation is made.

## B. Controller inventory

Approximate sizes refer to the relevant method or logical slice, not the whole file. `DB projection` means the controller assembles a public array from an existing model; it does not own the source records.

| Controller / method | Page(s) | Dataset | Classification | Approx. size | Future destination |
| --- | --- | --- | --- | ---: | --- |
| `HomeController::__invoke()` | `/` | `homeHero` | `PAGE_CONTENT` | 10 lines | Page content; future CMS candidate if homepage editing is required |
| `HomeController::__invoke()` | `/` | `homeServiceCards` | `PAGE_CONTENT` | 6 lines | Keep page-owned unless a shared service catalogue is explicitly established |
| `HomeController::__invoke()` | `/` | `careStandardRows` | `PAGE_CONTENT` | 5 lines | Page content; possibly shared domain standards later, but not proven shared |
| `HomeController::__invoke()` | `/` | `homeTestimonials` | `DATABASE_ALREADY_BACKED` | DB query/projection | `Testimonial` records; keep database-backed |
| `AboutController::__invoke()` | `/about` | About page payload: hero, stats, philosophy, mosaic, story, standards, team, address, CTA | `PAGE_CONTENT` | ~63 lines | Page content; future CMS candidate, with factual/operational fields separated later |
| `AboutPagesController::testimonialsPage()` | `/about/testimonials` | Hero, filters, bottom CTA | `PAGE_CONTENT` | 75 lines | Page content; future CMS candidate |
| `AboutPagesController::testimonials()` | `/about/testimonials` | Published testimonial projection | `DATABASE_ALREADY_BACKED` | DB query/projection | `Testimonial`; keep database-backed |
| `AboutPagesController::galleryPage()` | `/about/gallery` | Gallery hero | `PAGE_CONTENT` | 10 lines | Page content; future CMS candidate |
| `AboutPagesController::gallery()` | `/about/gallery` | Published gallery image projection | `DATABASE_ALREADY_BACKED` | DB query/projection | `GalleryItem` and media; keep database-backed |
| `AboutPagesController::careersPage()` | `/about/careers` | Hero, perks, headings | `DATABASE_CANDIDATE` | 49 lines | CMS-managed careers page copy if editorial ownership is required |
| `AboutPagesController::careers()` | `/about/careers` | Open job projection | `DATABASE_ALREADY_BACKED` | DB query/projection | `JobOpening`; keep database-backed |
| `AboutPagesController::partnershipsPage()` | `/about/partnerships` | Hero, partnership types, CTA | `DATABASE_CANDIDATE` | 54 lines | CMS-managed marketing content if partnership copy changes independently |
| `ServicesController::index()` | `/services` | Service overview hero, cards, stats, standards | `DATABASE_CANDIDATE` | ~13 data lines plus inline arrays | Service catalogue/page sections; future content records, not pricing records |
| `ServicesController::serviceComparisonData()` | `/services` | Six service rows and eight comparison features | `PRESENTATION_CONFIGURATION` | 120 lines | Application-owned presentation definition or focused page-data provider |
| `ServicesController::boardingPages()['index']` | `/services/boarding` | Boarding overview hero, cards, included features | `DATABASE_CANDIDATE` | ~90 lines | Service-page content records; preserve overview ownership |
| `ServicesController::boardingPages()['species']['dogs']` | `/services/boarding/dogs` | Dog boarding page | `DATABASE_CANDIDATE` | ~173 lines | Independent dog boarding content record/page model |
| `ServicesController::boardingPages()['species']['cats']` | `/services/boarding/cats` | Cat boarding page | `DATABASE_CANDIDATE` | ~185 lines | Independent cat boarding content record/page model |
| `ServicesController::boardingPages()['species']['exotic']` | `/services/boarding/exotic` | Exotic boarding page | `DATABASE_CANDIDATE` | ~206 lines | Independent exotic boarding content record/page model |
| `ServicesController::serviceDetails()['grooming']` | `/services/grooming` | Grooming copy, features, benefits, packages, standards, metadata | `DATABASE_CANDIDATE` | ~109 lines | Service content records; pricing keys remain separate |
| `ServicesController::serviceDetails()['training']` | `/services/training` | Training copy, features, benefits, packages, standards, metadata | `DATABASE_CANDIDATE` | ~112 lines | Service content records; pricing keys remain separate |
| `ServicesController::serviceDetails()['vet-care']` | `/services/vet-care` | Veterinary copy, features, benefits, packages, standards, metadata | `DATABASE_CANDIDATE` | ~116 lines | Service content records; clinical claims need review workflow |
| `ServicesController` pricing composition methods | service detail, boarding, `/services/pricing`, contact, AI pricing tool | Canonical pricing inputs and display transformations | `APPLICATION_BEHAVIOR` | ~90 lines of transforms | Keep configuration/application-owned under current pricing authority |
| `ServicesController::publishedFaqs()` | service pages | Published FAQ projection | `DATABASE_ALREADY_BACKED` | DB query/projection | `Faq`; keep database-backed |
| `RelocationController::index()` | `/services/relocation` | Relocation overview hero and three cards | `DATABASE_CANDIDATE` | ~12 data lines | Relocation/service catalogue content; keep overview separate |
| `RelocationController::relocationPages()['import']` | `/services/relocation/import` | Import hero, features, process, CTA | `DATABASE_CANDIDATE` | ~99 lines | Independent import page content |
| `RelocationController::relocationPages()['export']` | `/services/relocation/export` | Export hero, features, process, CTA | `DATABASE_CANDIDATE` | ~99 lines | Independent export page content |
| `RelocationController::relocationPages()['transport']` | `/services/relocation/transport` | Transport description, features, benefits, packages, process, standards | `DATABASE_CANDIDATE` | ~91 lines | Independent transport content; route-pricing behavior remains code-owned |
| `RelocationController::relocationPages()['checklist']` | `/services/relocation/checklist` | Checklist hero and four phases | `DATABASE_CANDIDATE` | ~59 lines | Structured checklist content if editorial updates become necessary |
| `RelocationController::faqs()` | relocation pages | Published relocation/transport FAQ projection | `DATABASE_ALREADY_BACKED` | DB query/projection | `Faq`; keep database-backed |
| `LoyaltyController::pageData()` | `/loyalty` | Hero, programme steps, programme boundaries, CTA | `MIXED` | 89 lines | Copy may become CMS content; programme boundaries remain application/operational policy |
| `ToolData::catalogue()` | `/tools`, search, related-tools | Ten tool records: labels, descriptions, routes, icons, categories | `SHARED_CONTENT` | 95 lines | Shared tool catalogue provider; database only if tool catalogue becomes editorially managed |
| `ToolData::symptomChecker()` | `/tools/symptom-checker` | Pet types, body areas, symptoms, red flags, species rules, guidance | `MIXED` | 376 lines | Split educational labels from safety decision rules; clinical governance boundary later |
| `ToolData::newPetChecklist()` | `/tools/new-pet-checklist` | Five interactive checklist sections and item records | `DATABASE_CANDIDATE` | 166 lines | Structured tool content if it needs editorial/versioned updates |
| `ToolData::vaccination()` | `/tools/vaccination-schedule` | Dog/cat educational vaccination reference | `DATABASE_CANDIDATE` | 84 lines | Governed clinical content store if publication/review workflow is required |
| `ToolData::emergency()` | `/tools/emergency-guide` | Four emergency/poison reference entries with actions and avoid rules | `MIXED` | 97 lines | Educational text may become governed content; escalation/safety rules remain code-owned |
| `ToolData::reference()['breeds']` | `/tools/breed-finder` | 245 structured breed records | `DATABASE_CANDIDATE` | Within ~6,482-line JSON payload | Reference-data records if the catalogue grows or needs editorial review |
| `ToolData::reference()['behavior']` | `/tools/behavior-tips` | Six behavior educational records | `DATABASE_CANDIDATE` | Within ~6,482-line JSON payload | Educational reference content; future governed content store |
| `ToolsController::medication()` | `/tools/medication-dosage-guide` | Published medication/formulation/jurisdiction projection | `DATABASE_ALREADY_BACKED` | DB query/projection | Clinical models and governance tables; keep database-backed |
| `ContactController::schema()` | contact form variants | Field labels, options, conditional fields, requiredness, pricing mode | `MIXED` | ~80 lines of schema branches | Presentation schema can move to a focused form-data provider; validation and pricing behavior remain application-owned |
| `ContactController::businessData()` | `/contact`, booking/contact surfaces | Business profile and public hours projection | `DATABASE_ALREADY_BACKED` | DB query/projection | `BusinessProfile`/`BusinessHour`; keep database-backed |
| `FaqController::index()` | `/faq` | FAQ page shell plus published FAQ projection/categories | `MIXED` | DB query/projection plus small hero | FAQ records remain database-backed; the hero/category presentation remains page content |
| `GuidesController::index()/show()` | `/guides` and `/guides/{slug}` | Guide records, article body, media fallbacks | `DATABASE_ALREADY_BACKED` | DB query/projection | `Guide` and media; keep database-backed |
| `KnowledgeBaseController::index()/show()` | `/knowledge-base` and detail pages | Knowledge article records, article body, related records | `DATABASE_ALREADY_BACKED` | DB query/projection | `KnowledgeArticle` and media; keep database-backed |
| `ShopController::index()/show()` | `/shop` and `/shop/{product}` | Product records and related/recent projections | `DATABASE_ALREADY_BACKED` | DB query/projection | `Product` and media; keep database-backed |
| `PricingController::index()` | `/services/pricing` | Service aliases and query-state resolution | `APPLICATION_BEHAVIOR` | 10 lines | Keep in application routing/calculation code |

### Dataset detail and ownership notes

* `HomeController`, `AboutController`, and the four `AboutPagesController` page providers contain page-specific marketing copy. Their interim controller ownership is sound because each page has a clear owner. They are not evidence of genuinely shared content merely because the same services are named across pages. Future destination: CMS/content records only if independent editing, scheduling, or growth justifies it.
* The `ServicesController` service overview, boarding overview, three species pages, and three service-detail pages are separate content owners. Their current controller location is acceptable for the migration, but the volume makes them the strongest non-clinical CMS candidates. Pricing keys such as `pricingKey` are references into canonical application configuration, not editable marketing content.
* The four relocation datasets must remain independent. Import, export, transport, and checklist have different audiences, claims, workflows, and image ownership even though they share a route family.
* `LoyaltyController::pageData()` is mixed: the headings and explanatory copy are page content, while “no online account”, SuiteCRM ownership, eligibility handling, and the absence of point tracking are operational boundaries. Those parts should not be treated as generic CMS copy without a product decision.
* `ToolData::catalogue()` is genuinely shared: `ToolsController`, `SearchCatalog`, and the related-tools component consume the same tool identity and route metadata. This is actual shared ownership, not merely identical text.
* `ToolData::symptomChecker()` and `ToolData::emergency()` are mixed. Labels and educational explanations are content; red-flag IDs, species-specific red flags, severity, escalation, and “avoid” rules are safety behavior/policy. They should be decomposed before any future content migration.
* The guide, knowledge-base, FAQ, gallery, testimonial, job, product, business-profile, business-hours, and medication projections are already database-backed. The controller is only shaping public view data and should not become the source of record.

`Migration now: NO` for every dataset in this report. This report makes no schema or migration recommendation.

## C. Fat-controller audit

| Controller | Method/provider | Approx. lines devoted to data | Datasets | Pages owned | Assessment |
| --- | --- | ---: | ---: | --- | --- |
| `ServicesController` | `boardingPages()` | ~656 | 4 | boarding overview, dogs, cats, exotic | High pressure; three independent child pages are nested in one provider |
| `ServicesController` | `serviceDetails()` | ~338 | 3 | grooming, training, vet care | High pressure; repeated shape is useful, but the copy is independent and vet content has clinical sensitivity |
| `ServicesController` | `serviceComparisonData()` | ~120 | 1 | services overview | Moderate; presentation matrix is coherent but should remain distinct from service copy and pricing |
| `RelocationController` | `relocationPages()` | ~353 | 4 | import, export, transport, checklist | High pressure; page independence is correct, but the method is large |
| `ToolData` | `reference()` | ~6,482 | 2 | breed finder, behavior tips | Very high; the JSON reference catalogue dominates the file |
| `ToolData` | `symptomChecker()` | ~376 | 1 mixed | symptom checker | Moderate/high; clinical decision data is mixed with UI labels |
| `AboutPagesController` | four page providers | ~188 total | 4 | testimonials, gallery, careers, partnerships | Acceptable interim size; page boundaries are clear |
| `LoyaltyController` | `pageData()` | ~89 | 1 mixed | loyalty | Moderate; content and operational boundary are co-located |

No refactor was performed. The key finding is decomposition by ownership, not a mechanical line-count extraction.

## D. Duplicate-content matrix

| Dataset/content | Location A | Location B | Same meaning? | Intentional? | Review |
| --- | --- | --- | --- | --- | --- |
| Service summaries | `HomeController::homeServiceCards` | `ServicesController::index` cards | Yes, same service catalogue concepts; copy differs | Probably intentional page-specific summaries | Keep independent until a shared service-domain owner is proven |
| Relocation summaries | `RelocationController::index` cards | `relocationPages()` import/export/transport | Yes, same service concepts; detail copy differs | Intentional overview/detail composition | Do not merge the four independent page owners |
| Tool identity metadata | `ToolData::catalogue()` | tool page metadata in `ToolsController` | Partly | Intentional shared catalogue plus page-specific SEO copy | Catalogue is genuinely shared; page descriptions remain page-specific unless standardized deliberately |
| `24/7 Supervision` and `Daily Updates` claims | `ServicesController::index` standards | boarding page datasets and about content | Same claims, wording varies | Repeated marketing claim; not proven shared domain content | Review for factual source ownership before consolidation |
| Import/export CTA labels and routes | `relocationPages()['import']` | `relocationPages()['export']` | Yes | Intentional shared action semantics | Shared CTA primitive is possible later; page copy remains independent |
| FAQ records | `ServicesController::publishedFaqs()` | `RelocationController::faqs()` and `FaqController` | Yes, same `Faq` model/source | Intentional database projection | Correctly shared at database level, not duplicated in controllers |
| Testimonial records | `HomeController::homeTestimonials` | `AboutPagesController::testimonials` | Yes, same `Testimonial` model/source | Intentional different public projections | Correctly database-backed |
| Relocation hero path | `relocationPages()` stores a hero path | `detail()` assigns the same `/media/services/relocation/{type}/hero.jpg` pattern | Yes | Redundant definition inside one controller | Review later; not cross-page sharing |
| Contextual related images | `ShopController` product page paths | `KnowledgeBaseController` article page paths | Same pattern, different domain and parent page | Intentional page-context asset model | Preserve contextual ownership; do not move into a generic shared bucket |

No exact accidental copy of a complete independent page dataset across two controllers was found. The main duplication risk is semantic marketing claims, not duplicated source records.

## E. Page independence

The current structure preserves the required independence:

* `/services/boarding/dogs`, `/services/boarding/cats`, and `/services/boarding/exotic` have separate keys, different page sections, different hero/daily/feline/exotic assets, and independently queried FAQ subcategories.
* `/services/relocation/import`, `/services/relocation/export`, `/services/relocation/transport`, and `/services/relocation/checklist` have separate datasets and distinct page contracts.
* The shared `ToolData::catalogue()` is a tool index/search catalogue, not a shared content container for the individual tool pages.

The audit does not recommend replacing these with one generic service or relocation dataset.

## F. Image-reference ownership audit

The following records every controller-supplied image expression, grouping only paths generated by the same expression.

| Controller/method | Page/slot | Image path or expression | Current owner | Finding |
| --- | --- | --- | --- | --- |
| `HomeController::__invoke()` | Home hero | `/media/home/hero.jpg` | Home page | Coherent page-owned asset |
| `HomeController::__invoke()` | Home service cards | `/media/home/services-boarding.jpg`, `services-grooming.jpg`, `services-vet-care.jpg`, `services-training.jpg`, `services-relocation.jpg` | Home page card slots | Coherent; these are not reused service-detail hero slots |
| `AboutController::__invoke()` | About hero/mosaic | `/media/about/intro.jpg`, `/media/about/veterinary-care.jpg`, `/media/about/grooming.jpg` | About page | Coherent page-owned assets |
| `AboutPagesController::testimonialsPage()` | Testimonials hero | `/media/about/testimonials/hero.jpg` | Testimonials page | Coherent child-page asset |
| `AboutPagesController::careersPage()` | Careers hero | `/media/about/careers/team.jpg` | Careers page | Coherent child-page asset |
| `FaqController::index()` | FAQ hero | `/media/faq/hero.jpg` | FAQ page | Coherent page-owned asset |
| `ServicesController::index()` | Services hero/cards | `/media/services/hero.jpg`; `/media/services/boarding/card-dogs.jpg`; `/media/services/grooming/hero.jpg`; `/media/services/vet-care/hero.jpg`; `/media/services/training/hero.jpg`; `/media/services/relocation/hero.jpg` | Services overview | Overview uses service-domain imagery; grooming/vet/training hero reuse is intentional slot reuse, not an ownership error |
| `ServicesController::boarding()` / `boardingPages()` | Boarding overview | `/media/services/boarding/hero.jpg`; `card-dogs.jpg`; `card-cats.jpg`; `card-exotic.jpg` | Boarding overview | Coherent |
| `ServicesController::boardingSpecies()` | Species hero/daily gallery | `/media/services/boarding/{species}/hero.jpg`; `/media/services/boarding/{species}/daily-01.jpg` through `daily-04.jpg` | Each species page | Correct independent ownership; generated paths match page keys |
| `ServicesController::boardingSpecies()` | Cat-specific section | `/media/services/boarding/cats/feline.jpg` | Cat boarding page | Correct child-page asset |
| `ServicesController::serviceDetails()` | Grooming/training/vet heroes | `/media/services/grooming/hero.jpg`; `/media/services/training/hero.jpg`; `/media/services/vet-care/hero.jpg` | Each service-detail page | Coherent service ownership |
| `RelocationController::index()` | Relocation overview | `/media/services/relocation/hero.jpg`; `card-import.jpg`; `card-export.jpg`; `card-transport.jpg` | Relocation overview | Coherent overview assets |
| `RelocationController::relocationPages()` / `detail()` | Import/export/transport/checklist heroes | `/media/services/relocation/{type}/hero.jpg` | Each relocation page | Correct independent ownership; the same path is stored and then re-derived in `detail()` |
| `ShopController::index()` | Shop cards | `/media/shop/card-{product}.jpg` | Shop listing slot | Page-context fallback, with model/media taking precedence |
| `ShopController::show()` | Product hero | `/media/shop/{product}/hero.jpg` | Product detail page | Product-page fallback |
| `ShopController::show()` | Related/recent slots | `/media/shop/{parent}/related-{related}.jpg`; `/media/shop/{parent}/recent-{recent}.jpg` | Current product detail page | Deliberately contextual slot ownership; not an unrelated domain folder |
| `GuidesController::index()/show()` | Guide cards/covers | `/media/guides/card-{slug}.jpg`; `/media/guides/{slug}/cover.jpg` | Guide records/page slots | Database media remains authoritative; fallbacks are correctly domain-scoped |
| `KnowledgeBaseController::index()/show()` | Article cards/covers/related | `/media/knowledge-base/card-{slug}.jpg`; `/media/knowledge-base/{parent}/cover.jpg`; `/media/knowledge-base/{parent}/related-{related}.jpg` | Knowledge article/page slots | Contextual and domain-scoped; not shared editorial placeholders |

No controller was found referencing an unrelated domain folder. The notable review items are redundant relocation hero assignment and the need to keep contextual related/recent assets clearly distinguished from entity-owned primary media.

## G. Database-candidate matrix

| Current controller dataset | Page/domain | Candidate DB concept | Confidence | Reason |
| --- | --- | --- | --- | --- |
| Services overview content | `/services` | Service catalogue/page sections | High | Marketing catalogue likely to grow and change independently |
| Boarding overview and species pages | Boarding | Independent service-page content records | High | Large structured content with three independent owners |
| Grooming/training/vet detail pages | Service detail | Service-page content records | High | Editable marketing copy, feature lists, packages, and metadata |
| Relocation import/export/transport/checklist | Relocation | Independent relocation page/content records | High | Structured pages with distinct audiences and workflows |
| Careers/perks and partnerships copy | About | Editorial page sections | Medium | Likely to change independently, but current volume is modest |
| New-pet checklist | Tool | Versioned checklist content | Medium | Structured and likely to grow; browser progress is behavior, not content |
| Breed reference | Tool | Breed reference records | High | 245 records and a 6,482-line payload are already catalogue-shaped |
| Behavior tips | Tool | Educational reference articles/entries | Medium | Six records today; likely related to broader knowledge content |
| Vaccination reference | Tool | Governed clinical reference content | High | Educational content needs source, review, jurisdiction, and publication controls |
| Emergency guide educational text | Tool | Governed emergency reference content | High | Text may be editorial, but safety rules must remain controlled |
| Symptom educational labels/descriptions | Tool | Governed clinical tool content | Medium | Content can be managed later only after separating decision rules |

These are future candidates only. No migration, model, schema, or Filament resource should be inferred from this matrix.

## H. Application-behavior matrix

| Dataset/logic | Purpose | Why behavior rather than content | Recommended future home |
| --- | --- | --- | --- |
| `config/waggies_pricing.php` consumed by `ServicesController`, `ContactController`, `PricingTool`, and tests | Rates, tiers, quote/estimate types, transport rules, and canonical price display | It affects calculations, request interpretation, pricing authority, and AI output | Version-controlled configuration plus focused application logic |
| `ServicesController` pricing composition | Resolves `pricingKey`, calculates “from” amounts, formats tiers, and rejects missing canonical tiers | It enforces the relationship between page packages and authoritative rates | Application service/helper or existing pricing boundary; do not make page content authoritative |
| `PricingController::$aliases` | Maps legacy/request aliases to canonical service and variant state | It controls routing/query interpretation | Controller/application behavior |
| `ToolData::symptomChecker()` red flags, species rules, severity, and guidance | Determines escalation and safe next steps | It is a clinical safety decision system, not merely page copy | Code-owned safety boundary with governed clinical inputs if the product evolves |
| `ToolData::emergency()` severity/actions/avoid rules | Determines urgent behavior and prohibited advice | Safety policy must not be freely editable marketing content | Code-owned safety boundary; educational prose may be governed separately |
| `ContactController::schema()` requiredness, conditional fields, pricing mode, allowed species | Controls form behavior and downstream request semantics | It determines what the application accepts and how requests are classified | Focused application/form schema provider; validation remains server-owned |
| `ShopController` sort options and filtering | Controls catalogue query behavior | It changes retrieval/order behavior, not product copy | Controller/query/application code |

## I. Database boundaries already established

The following existing domain models are authoritative at runtime and were not relocated into controllers:

* `BusinessProfile` and `BusinessHour` for public business identity, contact values, and hours.
* `Faq` for service, relocation, transport, and general FAQ records.
* `Testimonial` for homepage and testimonials-page records.
* `GalleryItem` for gallery records and media.
* `JobOpening` for careers openings.
* `Guide` and `KnowledgeArticle` for editorial articles.
* `Product` for shop records.
* `Medication`, `MedicationFormulation`, and `MedicationJurisdiction` for medication reference data.

Configuration entries retained as seed/migration input are not runtime controller content. This boundary is correct.

## J. Potential architectural problems

1. **Fat service controller.** `ServicesController` contains three major page families, a comparison matrix, FAQ querying, and pricing composition. It is the clearest future decomposition candidate.
2. **Large relocation provider.** `relocationPages()` holds four distinct page owners. Keeping them separate in the data model is correct; keeping all four in one 353-line provider is an interim convenience.
3. **Tool provider placement and scale.** `ToolData` is a data provider under `app/Http/Controllers`, not a controller. Its 245-breed JSON payload dominates the file and obscures the difference between reference content and safety rules.
4. **Behavior mixed with tool content.** Symptom and emergency structures mix UI labels, educational copy, clinical safety rules, severity, and escalation behavior.
5. **Marketing content mixed with pricing references.** Service packages contain page copy next to `pricingKey` references. The current transform preserves pricing authority, but the future content boundary must keep those concerns separate.
6. **Shared claims without a proven owner.** “24/7 supervision”, “daily updates”, and similar claims repeat across pages. Repetition is not enough to establish shared domain ownership; factual ownership should be decided before consolidation.
7. **Redundant image derivation.** Relocation page image paths are present in the dataset and then assigned again from the route type in `detail()`.
8. **Controller page-data providers are untyped arrays.** This is acceptable for the migration interim state, but the lack of explicit contracts makes future decomposition and validation harder.

## K. Good interim state versus final architecture

| Area | Good interim location? | Likely permanent location | Future DB candidate? |
| --- | --- | --- | --- |
| Home/about/child-page marketing copy | Yes, with clear page ownership | Page content provider or CMS-backed page sections if editing is needed | Yes, selectively |
| Boarding species pages | Yes, because independence is preserved | Independent service-page content records | Yes |
| Relocation pages | Yes, because each page remains independent | Independent relocation content records | Yes |
| Service detail copy | Yes, but controller is already large | Service content records/provider separate from pricing | Yes |
| Tool catalogue | Yes, as one genuine shared provider | Shared tool catalogue provider; DB only with editorial need | Maybe |
| Breed and behavior reference | Barely; current provider is very large | Reference/content records or governed knowledge content | Yes |
| Vaccination/emergency educational text | Only as a controlled interim source | Governed clinical content boundary | Yes, with review workflow |
| Symptom red flags and emergency rules | Yes | Code-owned clinical safety/application behavior | No, not as ordinary CMS content |
| Service pricing and transport rules | Yes, and explicitly required by current project rules | Configuration/application pricing authority | No under current architecture |
| FAQ, guides, knowledge, testimonials, gallery, jobs, products, business profile, medications | No controller relocation needed | Existing models and media/admin boundaries | Already backed |

## L. Current test coverage and gaps

Existing coverage is meaningful and protects several important contracts:

* `AboutPagesTest` verifies all four about child routes, page titles, and schema output.
* `LoyaltyPageTest` verifies key sections, the intentional programme boundary, metadata, and search exposure.
* `RelocationRoutesTest` verifies canonical route hierarchy, retired paths, and sitemap behavior.
* `WaggiesServiceDetailTest` verifies booking CTA context, service-detail composition, package pricing, FAQ composition, and transport messaging.
* `PricingAuthorityTest`, `PricingPageTest`, and `ContactPageTest` protect canonical pricing, aliases, field schemas, and transport context.
* `ClinicalSafetyRegressionTest` protects symptom, emergency, vaccination, medication, nutrition, and assistant safety boundaries.
* `GalleryCmsTest`, `OperationalIntakeTest`, and `DevelopmentSeederTest` protect database-backed gallery/testimonial/seed behavior.
* `SeoSearchSitemapTest` protects page metadata, search, canonical URLs, and public URL boundaries.

Important regression gaps remain, without changing tests in this audit:

* No focused test asserts the full page-data shape for all three boarding species or all four relocation child datasets.
* No test detects accidental cross-page reuse of a species or relocation dataset.
* No test asserts that every controller-supplied image fallback exists or that the relocation image expression remains aligned with the data key.
* No focused test protects the ten-entry shared tool catalogue contract across the tools index, search catalogue, and related-tools component.
* The clinical tests protect safety outcomes, but do not test provenance/version freshness for the large breed, behavior, vaccination, and emergency payloads.
* Current coverage verifies visible strings and route behavior more often than structured view-data contracts. That is appropriate for many public-page tests, but it leaves array-shape regressions less directly diagnosed.

## Final audit answer

* **Controllers:** appropriate interim owners for page-specific copy and view composition, but not a permanent CMS by default.
* **Application behavior:** pricing authority, price composition, aliases, form semantics, symptom red flags, severity, escalation, and emergency avoidance rules.
* **Genuinely shared:** the tool catalogue and the existing database model projections. Repeated service claims and CTA labels are not automatically shared domain data.
* **Already database-backed:** business profile/hours, FAQs, testimonials, gallery, jobs, guides, knowledge articles, products, and medication reference records.
* **Likely future database/content:** service-page copy, relocation-page copy, editable about-page copy, breed/behavior reference data, checklists, and governed educational/clinical reference content.
* **Must remain code-owned for now:** pricing rules and authority, calculation/alias logic, and clinical safety decisions.

No application code, configuration, database data, assets, models, migrations, views, routes, tests, or packages were modified by this audit. Only this report was created.
