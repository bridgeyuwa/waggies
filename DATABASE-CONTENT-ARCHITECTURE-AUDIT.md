# Waggies — Database Content Architecture Audit

> Superseded clinical decision: this historical audit records the governance subsystem that existed when it was written. The subsystem has since been removed because no demonstrated clinician editorial workflow was required. See `CLINICAL-GOVERNANCE-REMOVAL-AUDIT.md` for the current runtime and schema decision.

## 1. Scope and conclusion

This is a read-only architecture audit based on:

- `CONTROLLER-CONTENT-AUDIT.md`;
- the current `app/Models`, `app/Enums`, `app/Actions`, `app/Policies`, `app/Support`, and relevant `app/Filament` code;
- the current migrations, seeders, and factories;
- the service, relocation, tool, about, pricing, route, and view boundaries.

No application code, schema, migrations, seeders, factories, routes, views, assets, configuration, tests, or packages were changed. This file is the only intended output.

### Executive decision

The future architecture should have several bounded content/reference areas, not one generic CMS:

1. Reuse the existing database models for FAQs, testimonials, gallery items, jobs, guides, knowledge articles, products, business data, and medication reference.
2. Introduce a Services domain in a later implementation phase, with a canonical `Service` identity and independent `ServicePage` records. A page record must represent each public URL independently, including boarding overview, each boarding species page, relocation overview, each relocation page, grooming, training, and veterinary care.
3. Keep page sections, feature bullets, CTAs, comparison rows, and similar nested presentation data embedded in their owning page/content record until independent identity, reuse, or querying is proven.
4. Keep pricing configuration and pricing calculations application-owned. A content record may carry a stable pricing reference, but it must never become pricing authority.
5. Model a structured `Checklist` aggregate only when the relocation and/or new-pet checklists need editorial changes, versioning, or reuse. Do not store browser completion state as CMS content.
6. Treat breeds and behavior as structured reference records, not ordinary service pages. A future `BreedReference` is justified now by the 245-record catalogue; behavior can use a dedicated structured reference entity when it leaves code ownership.
7. Use the existing clinical governance boundary for vaccination, emergency, symptom, behavior, and health-related breed content. Clinical copy may be persisted, but red flags, severity, escalation, prohibited actions, medication safety, and decision thresholds must not become freely editable CMS fields.
8. Do not create a generic `pages` table merely to move small about, careers, partnerships, or homepage arrays into the database. Those remain code-owned until a real editorial workflow or growth requirement exists.

The main architectural distinction is:

```text
public page identity      != service/domain identity
marketing content         != pricing authority
educational copy          != clinical decision logic
clinical reference data   != ordinary CMS content
media attachment          != page/entity ownership
derived search document   != source content
```

## 2. Existing database inventory

The following is the relevant domain inventory. Laravel infrastructure tables (`users`, cache, jobs, sessions, and related framework tables) are omitted unless they participate in an application relationship.

| Model | Table | Purpose | Relevant relationships |
| --- | --- | --- | --- |
| `BusinessProfile` | `business_profiles` | Current business identity, contact details, address, social links, timezone, and map URL. It has a config fallback when the table is unavailable or empty. | No declared Eloquent relationship to `BusinessHour`; they are conceptually related through the single business profile but are persisted independently. |
| `BusinessHour` | `business_hours` | Weekly hours and dated exceptions/closures. `weekly()` and `exceptions()` scopes resolve the public schedule. | No declared model relationship. `BusinessHour::forDate()` and `publicSchedule()` own schedule selection and display behavior. |
| `Faq` | `faqs` | Published/draft/archived FAQ records grouped by category and subcategory. | No foreign-key relationship to a service or page; service controllers project records by category/subcategory. |
| `Testimonial` | `testimonials` | Customer stories with consent, CRM matching, identity/customer verification, moderation, publication, and service classification. | Owns one `photo` media collection through Spatie Media Library. Service association is currently a controlled string, not a foreign key. |
| `GalleryItem` | `gallery_items` | Published gallery image records with category, alt text, caption, ordering, and publication state. | Owns one `image` media collection through Spatie Media Library. |
| `JobOpening` | `job_openings` | Open, closed, draft, or archived careers vacancies, including application details and closing dates. | No relationship to a careers page; the careers page projects openings separately. |
| `Guide` | `guides` | Editorial guide records with slug, rich content, publication state, SEO/indexability, sitemap state, and fallback image fields. | `slugHistories()`; optional `clinicalContent()` morph-one; cover and rich-editor attachment media collections. |
| `GuideSlugHistory` | `guide_slug_histories` | Reserves previous Guide URLs for stale-slug protection. | Belongs to `Guide`. |
| `KnowledgeArticle` | `knowledge_articles` | Knowledge-base article records with slug, rich content, ordering, publication state, SEO/indexability, and sitemap state. | `slugHistories()`; optional `clinicalContent()` morph-one; cover and rich-editor attachment media collections. |
| `KnowledgeArticleSlugHistory` | `knowledge_article_slug_histories` | Reserves previous Knowledge Article URLs. | Belongs to `KnowledgeArticle`. |
| `KnowledgeSource` | `knowledge_sources` | Provenance/synchronization metadata for externally sourced knowledge material and vector-store/provider identifiers. | No Eloquent relationship to `KnowledgeArticle`; it is a source registry, not article content. |
| `Product` | `products` | Shop catalogue records with slug, price, currency, availability, features, publication, SEO, and search behavior. | Owns `image` and `images` media collections and is searchable through Laravel Scout. |
| `Medication` | `medications` | Structured medication reference data: generic/brand identity, ingredients, species, indications, routes, warnings, contraindications, interactions, and classification. | Belongs to `ClinicalContent`; has many `MedicationFormulation` and `MedicationJurisdiction` records. |
| `MedicationFormulation` | `medication_formulations` | Formulation, route, strength, concentration, combination ingredients, and dose-display data. | Belongs to `Medication`; dose display is gated by clinical publication and an explicit flag. |
| `MedicationJurisdiction` | `medication_jurisdictions` | Registration, product-label, applicant, approval, and jurisdiction-specific medication data. | Belongs to `Medication`; unique per medication/jurisdiction. |
| `ClinicalContent` | `clinical_contents` | Governance envelope for clinical or mixed content: status, publication gate, risk, jurisdiction, version, review due date, withdrawal, conflicts, and metadata. | Polymorphic `contentable`; many-to-many `ClinicalSource`; many `ClinicalReview` and `ClinicalContentVersion`; optional withdrawing user. |
| `ClinicalContentVersion` | `clinical_content_versions` | Immutable-ish JSON snapshots and version approval metadata for governed content. | Belongs to `ClinicalContent`. |
| `ClinicalReview` | `clinical_reviews` | Reviewer decision, credentials, source/version references, notes, and next-review timing. | Belongs to `ClinicalContent`, optional `ClinicalSource`, and optional reviewer `User`. |
| `ClinicalSource` | `clinical_sources` | Source provenance, organization, reference, jurisdiction, species, topic, evidence level, version, conflict state, and active status. | Many-to-many with `ClinicalContent`; has many reviews. |
| `ClinicalToolReview` | `clinical_tool_reviews` | Tool-level clinical status, risk, jurisdiction, version, source, review date, and review due date keyed by `tool_key`. | Belongs to a `ClinicalSource`; no relationship to individual tool records or tool entries. |
| `SearchDocument` | `search_documents` | Derived searchable projection with source type/key, title, body, URL, category, publication, and boost. | No foreign key to source records; it is an index projection, not content ownership. |
| `BusinessProfile`, `BusinessHour`, `Faq`, `GalleryItem`, `JobOpening`, `Guide`, `KnowledgeArticle`, `Product`, `Testimonial`, and clinical models | See above | Existing CMS, operational, commerce, and clinical boundaries. | Filament resources already exist for these areas, including clinical workflow resources. |

### Schema observations that affect future design

- Guide and Knowledge Article tables already establish a reusable publication vocabulary: `status`, `published_at`, SEO fields, `is_indexable`, and `include_in_sitemap`.
- Guide and Knowledge Article slugs are route identities with historical URL protection. A future public page entity should use the same strength of identity if it owns a route.
- Media is already model-associated through Spatie Media Library. No new media schema is justified for future service pages or reference entities.
- The former `service_prices` table was created by one migration and explicitly dropped by a later migration. There is no runtime service-price model/table. The current source of truth is `config/waggies_pricing.php`, consumed by controllers and tests.
- Existing FAQs use category/subcategory classification rather than page foreign keys. Existing testimonials use a controlled service string rather than a `Service` foreign key. These are deliberate current projections, not evidence that future pages must use the same shape.
- Clinical governance is already more than a future idea: models, enums, policies, Filament resources, workflow actions, publication gating, version snapshots, sources, and reviews are present. It currently governs Guides, Knowledge Articles, and Medication, but not the large ToolData payload.
- Factories exist for the main editorial/operational records. There are no factories or seeders for a future service-page, breed, behavior, vaccination, emergency, or checklist entity, which supports treating those as future design rather than existing domain records.

## 3. Candidate inventory

The decisions below start from the candidate matrix in `CONTROLLER-CONTENT-AUDIT.md`. “Database later” means a justified future direction, not permission to implement it in this audit.

| Controller dataset | Current owner | Candidate entity | Reuse existing model? | Confidence | Reason |
| --- | --- | --- | --- | --- | --- |
| Services overview | `ServicesController::index()` | `Service` plus an overview `ServicePage` | No existing service model | High | A growing service catalogue has stable identity and relationships, while the overview is still page-specific content. |
| Boarding overview | `ServicesController::boardingPages()['index']` | `ServicePage` for the boarding overview | No | High | It is a distinct public page with its own hero, cards, and content. |
| Dog boarding | `ServicesController::boardingPages()['species']['dogs']` | Independent `ServicePage` for dog boarding, referencing boarding service context | No | High | It has its own URL, copy, media, packages, and customer intent. |
| Cat boarding | Same | Independent `ServicePage` for cat boarding | No | High | Same domain family does not remove its independent page identity. |
| Exotic boarding | Same | Independent `ServicePage` for exotic boarding | No | High | Different handling claims, packages, and audience justify separate page content. |
| Grooming | `ServicesController::serviceDetails()['grooming']` | Independent `ServicePage` plus content-owned package descriptions | No | High | Marketing copy and package presentation may be edited; pricing remains config-owned. |
| Training | `ServicesController::serviceDetails()['training']` | Independent `ServicePage` plus content-owned package descriptions | No | High | Same as grooming; behavior/clinical claims require review where applicable. |
| Veterinary care | `ServicesController::serviceDetails()['vet-care']` | Independent `ServicePage`, with governed clinical claims where needed | No | High | Marketing content is a candidate, but clinical claims cannot be ordinary unchecked CMS copy. |
| Service comparison matrix | `ServicesController::serviceComparisonData()` | Code-owned presentation definition | No | High | It is a display matrix derived from services and pricing; it is not a standalone business entity. |
| Service package prices and transport rules | `config/waggies_pricing.php` and application logic | Configuration/application pricing boundary | No | High | Pricing affects calculations, aliases, request semantics, and tests. The project explicitly keeps it out of CMS. |
| Relocation overview | `RelocationController::index()` | `ServicePage` for relocation overview | No | High | Overview content is independently editable from the detail pages. |
| Relocation import | `RelocationController::relocationPages()['import']` | Independent `ServicePage` for import | No | High | It has distinct compliance claims, audience, media, and workflow. |
| Relocation export | Same | Independent `ServicePage` for export | No | High | It must not be collapsed into an undifferentiated relocation blob. |
| Relocation transport | Same | Independent `ServicePage` for transport, referencing transport offering context | No | High | Transport has operational and pricing behavior that must remain separate from page copy. |
| Relocation checklist | Same | `Checklist` aggregate, rendered through an independent relocation page | No | Medium | Ordered phases and items are structured and may be edited/versioned, but the page remains independent. |
| Careers marketing copy | `AboutPagesController::careersPage()` | Dedicated careers page content only if editorial need is demonstrated | No | Medium | `JobOpening` already owns vacancies; the surrounding marketing copy is small and currently code-owned. |
| Careers openings | `AboutPagesController::careers()` | Existing `JobOpening` | Yes | High | This is already a lifecycle-bearing database entity with publication and closing dates. |
| Partnerships marketing copy | `AboutPagesController::partnershipsPage()` | Dedicated partnerships page content only if independently edited | No | Medium | Several paragraphs do not by themselves justify a generic CMS record. |
| About page marketing copy | `AboutController` | Remain code-owned for now; dedicated about content only after an editorial requirement | No | Medium | Current content is one coherent page with no evidence of independent records or workflow. |
| Tool catalogue | `ToolData::catalogue()` consumed by tools, search, and related-tools | Remain a shared code-owned catalogue for now | No | High | It is genuinely shared identity/configuration, but ten stable entries do not yet require database editing. |
| New-pet checklist | `ToolData::newPetChecklist()` | `Checklist` aggregate with sections/items if editing/versioning becomes necessary | No | Medium | Ordering and completion keys matter; browser progress is user/application state, not content. |
| Breed reference | `ToolData::reference()['breeds']` | New `BreedReference` records | No existing Breed model | High | 245 structured records need identity, filtering, searchability, editorial review, and future growth. |
| Behavior reference | `ToolData::reference()['behavior']` | New structured behavior reference records, governed where claims require it | No suitable existing model | Medium | Six records are not enough to justify immediate migration, but their stable IDs, structured advice, escalation, and review flags are not ordinary article prose. |
| Vaccination reference | `ToolData::vaccination()` | Governed vaccination reference records/content | No existing vaccine model | High | Species, life stage, jurisdiction, source, version, and review are necessary; a fixed universal calendar is unsafe. |
| Emergency guide educational entries | `ToolData::emergency()` | Governed emergency reference entries/content | No | High | Structured entries may grow, but actions, severity, and avoid rules require clinical governance and controlled publication. |
| Symptom labels and educational guidance | `ToolData::symptomChecker()` | Governed clinical tool content or structured symptom entries later | No | Medium | Labels/copy are separable from the decision rules and may eventually need review/versioning. |
| Symptom red flags, species rules, severity, escalation | `ToolData::symptomChecker()` | Code-owned safety/application policy | No | High | These determine decisions and escalation; they are not ordinary CMS fields. |
| Medication catalogue and dose visibility | `ToolsController::medication()` | Existing medication and clinical governance models | Yes | High | Medication, formulation, jurisdiction, source, and publication gates already exist. |
| FAQ projections on service/relocation pages | Service and relocation controllers | Existing `Faq`; optional future explicit page assignment | Yes | High | FAQs are already database-backed; only a future page-assignment relationship might be needed. |
| Gallery and testimonial projections | Home/about controllers | Existing `GalleryItem` and `Testimonial` | Yes | High | Multiple pages intentionally project the same source records. |
| Guides and knowledge articles | Guides/Knowledge controllers | Existing `Guide` and `KnowledgeArticle` | Yes | High | They already own editorial publication, slugs, SEO, media, and optional clinical governance. |

## 4. Domain boundaries and decisions

### 4.1 Services

#### Service identity is necessary, but service identity is not page content

A future `Service` entity is justified because service concepts are referenced across pages, pricing keys, contact context, testimonials, search, and future operational features. It should represent a canonical service family or offering identity, not a bag of marketing paragraphs.

The initial domain should be deliberately small:

```text
Service
  boarding
  grooming
  training
  vet-care
  relocation
  local-transport (if transport becomes an independently managed offering)
```

Boarding species and relocation directions should not automatically become child content blobs. They are independent public offerings/pages. A stable variant key on `ServicePage` is sufficient initially unless booking, capacity, eligibility, or lifecycle behavior proves that `boarding-dogs`, `boarding-cats`, `boarding-exotic`, `relocation-import`, and `relocation-export` require independent domain records. That later decision should be based on operational identity, not on URL nesting.

#### Service page content

`ServicePage` is the right future boundary for public service page identity and editable page data. Each public page receives its own record:

```text
/services                         → services overview page
/services/boarding                → boarding overview page
/services/boarding/dogs           → dog boarding page
/services/boarding/cats           → cat boarding page
/services/boarding/exotic         → exotic boarding page
/services/grooming                → grooming page
/services/training                → training page
/services/vet-care                → veterinary care page
/services/relocation              → relocation overview page
/services/relocation/import       → import page
/services/relocation/export       → export page
/services/relocation/transport   → transport page
/services/relocation/checklist    → relocation checklist page
```

The rows can share a `Service` reference, but they must retain independent `page_key`/route identity, content, publication state, SEO fields, and media ownership. This preserves page independence while allowing service-level navigation and search.

#### Package/content/presentation separation

- Package labels, descriptions, feature bullets, benefits, process steps, standards, and page claims are content owned by the relevant `ServicePage` or a small embedded content value.
- `pricingKey` is a reference into the canonical pricing configuration; it is not a price and must not be editable as a commercial amount through the content record.
- `serviceComparisonData()` remains application-owned presentation configuration unless a real editorial comparison workflow appears.
- Form field schemas, allowed species, pricing mode, alias resolution, and request semantics remain application behavior.
- Existing `Faq` records remain the FAQ source. Do not duplicate FAQ text inside a service page.
- Service-page media should be attached to the `ServicePage` owner. Overview card images and detail-page hero images can be different media items even when they depict the same service.

### 4.2 Relocation

Relocation is a service domain with several independent public page/content identities, not one generic “relocation content” row.

The recommended relationship is:

```text
Service(relocation)
  ├── ServicePage(relocation overview)
  ├── ServicePage(import)
  ├── ServicePage(export)
  ├── ServicePage(transport)
  └── ServicePage(checklist presentation)
```

The import/export/transport pages may share domain facts or reusable content values later, but they must not share a single mutable page payload. The checklist is both a public page and a structured checklist candidate; its phases/items should be owned by the checklist aggregate, while the page owns hero, introduction, CTA, and SEO presentation.

Relocation content must be divided as follows:

- page-specific marketing copy, process descriptions, hero/CTA content: `ServicePage`;
- stable relocation offering identity: `Service` or a future offering/variant record;
- ordered relocation preparation tasks: `Checklist` if editorial/versioning needs justify it;
- destination/import/export legal or regulatory requirements: governed reference data with provenance and jurisdiction, not free-form marketing content;
- quote/pricing, distance rules, route constraints, surcharges, special-handling rules: application-owned pricing/operations configuration;
- FAQ text: existing `Faq` records.

### 4.3 About, careers, and partnerships

`JobOpening` already represents the actual careers domain entity. The careers page should compose `JobOpening` records with small page-specific marketing copy. Do not create a second job or “career content” entity merely because the page has a hero and perks.

The partnerships page has marketing content with several partnership types. It may become a dedicated page-content record only when a non-developer editor, publication workflow, scheduling, or frequent change is demonstrated. A generic `Page` table is not justified by the current evidence.

The main `/about` page is currently a coherent controller-owned composition of story, standards, team, stats, address, mosaic, and CTA. Operational facts should continue to come from `BusinessProfile`/`BusinessHour` where applicable. The rest can remain code-owned until editorial requirements become real.

The same decision applies to the homepage, testimonials-page shell, gallery-page shell, FAQ-page shell, and loyalty-page copy: existing database records should remain database-backed, but small page shells should not be converted to database records for size alone.

### 4.4 Tool architecture

The tools area contains four different concerns:

| Concern | Current example | Decision |
| --- | --- | --- |
| Tool identity/configuration | `ToolData::catalogue()` IDs, route names, icons, categories, descriptions | Keep code-owned for now. It is a shared catalogue provider, not a CMS record. |
| Structured reference content | breeds, behavior entries, vaccination entries, emergency entries | Persist selectively as structured reference entities when growth, search, review, or editing justifies it. |
| Educational page copy | tool introductions, explanatory text, limitations | Can be governed content later, but should not be mixed into the tool's behavior rules. |
| Application/safety behavior | symptom red flags, species red flags, severity, escalation, avoid rules, calculator formulas | Keep application-owned and version-controlled. Do not expose as ordinary editable CMS fields. |

The shared catalogue is real reuse: `ToolsController`, `SearchCatalog`, and the related-tools component consume it. That does not imply that every tool's body data belongs in the same table.

### 4.5 Breed reference

The breed data is already a catalogue-shaped domain: stable ID, species, name, filters, temperament, exercise/grooming needs, living environment, narrative overview, weight, lifespan, origin, coat, trainability, popularity, and health considerations. With 245 records, a dedicated `BreedReference` is more appropriate than:

- a separate Guide or Knowledge Article for every breed;
- one JSON blob on a tool record;
- a generic CMS page row;
- a service-specific data structure.

The first database version should keep structured attributes queryable and keep free-form narrative fields constrained. Health considerations must be treated as governed reference content, not casual SEO copy. If individual breed URLs are added later, the record will need canonical slug/indexability fields; the current finder only proves the need for filterable reference records, not individual SEO pages.

### 4.6 Behavior reference

Behavior entries have stable IDs, species, category, title, description, practical advice, escalation copy, review status, and `cmsReplaceable` intent. That is more structured than an article, but only six records exist today.

Decision:

- keep the current provider code-owned until the catalogue grows or editors genuinely need to manage it;
- when persisted, use a dedicated structured behavior reference entity rather than `KnowledgeArticle` unless the product intentionally changes the content into long-form articles;
- attach clinical governance to entries whose advice, escalation, or health claims require it;
- do not use the `cmsReplaceable` flag as permission to bypass review or turn safety decisions into free-form CMS content.

### 4.7 Vaccination and emergency reference data

These are governed reference domains, not ordinary page content.

Vaccination entries need at least species, life-stage/context, vaccine/topic, educational guidance, jurisdiction, source references, version, review status, publication status, and review due date. The current data intentionally avoids a universal fixed schedule and defers product/timing decisions to a veterinarian. A future schedule record must preserve that limitation.

Emergency entries need structured species/category/title/symptom/action/avoid data, but the safety significance is not equal across fields:

- educational description and context can be persisted as governed content;
- emergency severity, immediate escalation, prohibited actions, and red-flag interpretation are safety policy and require controlled workflow;
- “avoid” rules must not be freely editable marketing copy;
- jurisdiction and source provenance are necessary for anything that claims regulatory or clinical authority.

The existing `ClinicalContent`, `ClinicalSource`, `ClinicalReview`, `ClinicalContentVersion`, and `ClinicalToolReview` models provide important infrastructure. `ClinicalToolReview` currently governs a whole `tool_key`, not individual entries. If entry-level editing becomes necessary, the concrete reference entry should be the `contentable` record with its own clinical governance envelope; do not overload the tool-level review row to act as item-level content.

### 4.8 Symptom checker and clinical safety boundaries

The symptom checker must be split conceptually before any migration:

```text
pet types/body areas/symptom labels       → educational/tool content
routine/prompt/urgent explanatory copy   → governed educational content
red_flag_ids                              → safety policy
species_red_flag_ids                      → safety policy
severity and escalation outcomes          → application behavior
prohibited medication/home-remedy rules  → safety policy
```

A database content record must never be able to silently change a clinical decision merely because an editor changed a paragraph. Safety rules require code review, explicit versioning, regression coverage, and clinical ownership. A future persisted rule set would be a governed application artifact, not ordinary CMS content.

Medication safety is already correctly separated: `Medication`, formulations, jurisdictions, sources, clinical publication gates, and dose-display controls are database-backed, while the decision to show a dose remains governed application behavior.

## 5. Proposed entity map

These are design targets only. No model or migration is proposed in this audit.

### Entity: `Service`

- **Purpose:** Canonical identity for a Waggies service family or stable offering context.
- **Identity:** Stable key/slug such as `boarding`, `grooming`, `training`, `vet-care`, `relocation`; public URL identity belongs to `ServicePage`.
- **Owner/domain:** Services.
- **Key attributes:** Stable key, display name, short catalogue label, lifecycle/public availability, optional non-authoritative pricing reference, service category.
- **Relationships:** Has many `ServicePage` records; may later have offering/variant records if operational identity requires them.
- **Page relationship:** One service can have multiple independent public pages.
- **Media relationship:** Service-level media only for genuinely service-owned assets; page-specific media belongs to `ServicePage`.
- **SEO relationship:** Do not put page SEO fields here unless the service itself has a canonical resource URL distinct from its pages.
- **Publication requirements:** Basic availability/publish state may be sufficient; no clinical approval unless the service record contains clinical claims.

### Entity: `ServicePage`

- **Purpose:** Independent public page identity and editable content for a service route.
- **Identity:** Stable `page_key` plus route/canonical URL identity. Slug history is needed if editable slugs are introduced.
- **Owner/domain:** Services/content.
- **Key attributes:** Service reference, optional variant key, page kind, title/eyebrow, structured page content, CTA references, media references, publication state, published-at, SEO fields, indexability, sitemap participation.
- **Relationships:** Belongs to `Service`; may eventually have explicit FAQ assignments and content/reference links.
- **Page relationship:** One record per independent public page. Boarding species and relocation subtype pages remain separate records.
- **Media relationship:** Has page-owned media through existing Spatie Media Library infrastructure; do not share a single hero field across independent pages by convention alone.
- **SEO relationship:** Owns page-level title, description, canonical/indexability, and structured-data inputs that are editorial facts. Schema rendering remains application-level.
- **Publication requirements:** Draft/published/archived, scheduling if required, revision/history, audit log, and editor permissions. Veterinary claims may require clinical review.

### Entity: `Checklist`

- **Purpose:** Ordered, versionable checklist content used by a public tool or service page.
- **Identity:** Stable checklist key, such as `new-pet` or `relocation`.
- **Owner/domain:** Tools or Services/Operations depending on the checklist.
- **Key attributes:** Title, description, checklist type, version, publication state, optional completion mode, and ordered sections/items.
- **Relationships:** Has many ordered sections; sections have many ordered items. Reusable items should only be separated when the same item is genuinely shared and needs independent identity.
- **Page relationship:** The public page remains a separate page/content owner and renders one checklist. Relocation and new-pet pages are not forced into one URL or page record.
- **Media relationship:** Page-owned media belongs to the owning page; checklist items should not own media unless a real need appears.
- **SEO relationship:** Page-level SEO belongs to the public page, not to individual checklist items.
- **Publication requirements:** Draft/published versioning and change history; local browser completion state is not persisted as editorial state. Health-related items require clinical review where their wording is clinically consequential.

### Entity: `BreedReference`

- **Purpose:** Structured breed reference record for finder filters and educational display.
- **Identity:** Stable canonical breed key/slug, species, and name.
- **Owner/domain:** Tools/reference data, with clinical review for health claims.
- **Key attributes:** Species, size, temperament, exercise/grooming needs, living environment, overview, weight/lifespan, popularity, group, origin, coat, trainability, and governed health considerations.
- **Relationships:** Optional `ClinicalContent` for health claims; no relationship to `Service` merely because a breed may use a service.
- **Page relationship:** Initially rendered within the breed finder; an individual breed page is a future product decision, not assumed.
- **Media relationship:** Optional entity-owned media if the product later adds breed imagery; do not use GalleryItem as an implicit breed image owner.
- **SEO relationship:** Only add per-record SEO/canonical fields if individual breed URLs are introduced. Finder-level SEO remains page-owned.
- **Publication requirements:** Draft/published/reviewed state and provenance for health claims; history becomes important when reference copy changes.

### Entity: `BehaviorReference`

- **Purpose:** Structured behavior/training topic record when the six-record code catalogue grows or becomes editorially managed.
- **Identity:** Stable behavior key, species, and topic/category.
- **Owner/domain:** Tools/reference data, with clinical/behavior review where escalation or health claims are involved.
- **Key attributes:** Species, category, title, description, ordered practical advice, when-to-seek-help text, escalation metadata, status, version, and review metadata.
- **Relationships:** Optional `ClinicalContent` for governed entries; no forced relationship to `Service` or `KnowledgeArticle`.
- **Page relationship:** Rendered by the behavior tips tool; it is not automatically a standalone public page.
- **Media relationship:** None initially.
- **SEO relationship:** Tool page owns SEO unless individual topic routes are introduced.
- **Publication requirements:** Draft/review/published lifecycle and provenance for health-related advice; no free-form bypass of review gates.

### Entity: governed vaccination/emergency reference records

- **Purpose:** Structured educational/reference records whose publication is governed by clinical evidence and jurisdiction.
- **Identity:** Stable topic key plus species/jurisdiction/context; exact concrete types may be `VaccinationReference` and `EmergencyReference` if their schemas diverge.
- **Owner/domain:** Clinical Reference/Tools.
- **Key attributes:** Structured topic data, educational copy, species, jurisdiction, source/version references, risk, publication state, review due date, and withdrawal state. Emergency records may also contain controlled action/avoid policy fields.
- **Relationships:** Concrete record is `contentable` for existing `ClinicalContent`; `ClinicalContent` links sources, versions, and reviews.
- **Page relationship:** Tool page owns presentation; reference records supply structured content.
- **Media relationship:** None initially; any future illustration should be reference-owned or page-owned deliberately.
- **SEO relationship:** Tool/page level initially; record-level SEO only if individual reference URLs become a product requirement.
- **Publication requirements:** Source provenance, jurisdiction, reviewer identity/credential, version history, review due date, conflict handling, withdrawal, and explicit publication gate.

### Explicitly not proposed as entities now

- Generic `Page`, `PageSection`, `Feature`, `Bullet`, `Cta`, or `Paragraph` tables.
- A generic `ToolContent` table combining tool identity, education, clinical reference, and safety rules.
- A `ServicePrice` runtime model/table under the current pricing architecture.
- A `SymptomRule` or `EmergencyRule` ordinary CMS table without a separately designed governed application-policy workflow.
- A duplicate `Article` model that merges `Guide`, `KnowledgeArticle`, breed, behavior, vaccination, and emergency records.

## 6. Proposed relationships

Only relationships with a clear ownership or integrity reason are proposed.

| Entity A | Relationship | Entity B | Cardinality | Why |
| --- | --- | --- | --- | --- |
| `Service` | owns public page contexts | `ServicePage` | 1:M | A service family can be represented by an overview and multiple independent pages. |
| `ServicePage` | optionally assigns existing FAQs | `Faq` | M:M, only if category/subcategory becomes insufficient | A FAQ may appear on multiple pages; explicit assignment is justified only when page targeting cannot remain a controlled projection. |
| `ServicePage` | owns page media | Media Library `Media` | 1:M polymorphic | Page hero/card/gallery assets need page ownership, ordering, and conversions without a new media schema. |
| `Checklist` | contains ordered sections | `ChecklistSection` | 1:M | Sections have meaningful ordering and are edited with the checklist. |
| `ChecklistSection` | contains ordered items | `ChecklistItem` | 1:M | Items have stable completion keys/order within a checklist; they should not become globally reusable records without evidence. |
| `BreedReference` | is governed by | `ClinicalContent` | 0:1 | Health considerations need clinical status, source, version, and publication gating separate from the breed identity. |
| `BehaviorReference` | is governed by | `ClinicalContent` | 0:1 | Advice and escalation may require governed review while the reference identity remains structured. |
| `VaccinationReference` | is governed by | `ClinicalContent` | 1:1 or 0:1 during staged adoption | A vaccination recommendation must not publish without source, jurisdiction, version, and review controls. |
| `EmergencyReference` | is governed by | `ClinicalContent` | 1:1 or 0:1 during staged adoption | Emergency educational material needs provenance and publication gates; safety policy remains separately controlled. |
| `ClinicalContent` | cites | `ClinicalSource` | M:M | A content item can have multiple sources, and a source can support multiple governed records. |
| `ClinicalContent` | has reviews | `ClinicalReview` | 1:M | Review history and version-specific approval are required for governance. |
| `ClinicalContent` | has versions | `ClinicalContentVersion` | 1:M | History must remain available when governed content changes. |
| `Medication` | has formulations | `MedicationFormulation` | 1:M | Formulation and dose-display data have independent structure. |
| `Medication` | has jurisdiction records | `MedicationJurisdiction` | 1:M | Registration and label facts vary by jurisdiction. |
| `Medication` | is governed by | `ClinicalContent` | M:1/0:1 in current schema | Existing medication publication already uses the clinical governance envelope. |
| `Guide` / `KnowledgeArticle` | preserves old routes through | slug history | 1:M | Existing URL stability is part of editorial identity. |

No direct `Service` foreign key is proposed for `Testimonial` or `Faq` at this stage. Existing service/category strings are functioning projections, and replacing them with relationships requires a concrete assignment/editing requirement.

## 7. Existing model reuse matrix

| Concept | Existing model | Reuse? | Why/why not |
| --- | --- | --- | --- |
| Business identity | `BusinessProfile` | Yes | Already authoritative with fallback and public projection behavior. |
| Business hours | `BusinessHour` | Yes | Already models weekly rules and dated exceptions. |
| FAQ | `Faq` | Yes | Already supports status, publication, category, subcategory, ordering, and service projections. |
| Customer testimonial | `Testimonial` | Yes | Already includes moderation, verification, consent, publication, and media. |
| Gallery image record | `GalleryItem` | Yes | Already owns category, caption, ordering, publication, alt text, and media. |
| Careers vacancy | `JobOpening` | Yes | A vacancy has an independent lifecycle and already has the correct model. |
| Long-form guide | `Guide` | Yes | Already has slug, publication, SEO, media, rich content, and optional clinical governance. |
| Knowledge article | `KnowledgeArticle` | Yes | Same reason, while preserving its distinct knowledge-base ownership. |
| Article/source provenance | `KnowledgeSource` | Partially | Reuse for its existing synchronization/source registry; do not use it as a clinical source or as article content. |
| Product | `Product` | Yes | Already owns catalogue, price, availability, media, SEO, and search behavior. |
| Medication | `Medication` | Yes | Existing structured clinical domain; do not duplicate it in tool content. |
| Medication formulation | `MedicationFormulation` | Yes | Existing independent formulation data and dose gate. |
| Medication jurisdiction | `MedicationJurisdiction` | Yes | Existing jurisdiction-specific registration boundary. |
| Clinical governance envelope | `ClinicalContent` | Yes | Existing status/publication/risk/version/withdrawal gate. |
| Clinical source | `ClinicalSource` | Yes | Existing provenance and conflict model. |
| Clinical review history | `ClinicalReview` | Yes | Existing reviewer decision and version linkage. |
| Clinical content history | `ClinicalContentVersion` | Yes | Existing snapshot/version history. |
| Tool-level review | `ClinicalToolReview` | Yes, but only at tool scope | It can govern a whole tool. It is not a substitute for item-level governed reference content. |
| Search index | `SearchDocument` | Yes as a projection | Reuse for derived search documents; never treat it as the source of page/reference truth. |
| Generic media | Spatie Media Library `Media` | Yes | Existing polymorphic media, collections, ordering, conversions, and Filament integration are sufficient. |
| Service | None | No existing model | A canonical service identity is genuinely missing. |
| Service page | None | No existing model | Public service page identity and content ownership are genuinely missing. |
| Breed | None | No existing model | The 245-record structured reference needs a dedicated future entity. |
| Behavior topic | None | No suitable existing model | A dedicated structured reference entity is preferable when code ownership ends; `KnowledgeArticle` is not an equivalent. |
| Checklist | None | No suitable existing model | The two checklist candidates share a useful aggregate shape but not necessarily one current owner. |
| Vaccination reference | None | No existing vaccine model | Needs structured, jurisdictional, source-governed records. |
| Emergency reference | None | No existing emergency model | Needs structured, governed educational entries separate from safety policy. |

## 8. Content versus behavior matrix

| Current data | Content | Reference data | Application behavior | Safety policy | Future home |
| --- | --- | --- | --- | --- | --- |
| Service hero, title, intro, benefits, standards, process copy | Yes | No | No | No, unless it makes a clinical claim | `ServicePage` content/media. |
| Service package descriptions and feature bullets | Yes | No | No | No | `ServicePage` embedded structured content. |
| `pricingKey` on a package | No | No | Yes, it resolves canonical configuration | No | Application reference to `config/waggies_pricing.php`. |
| Service amounts, tiers, quote/fixed/estimate types, distance bands, surcharges | No | No | Yes | Sometimes operational safety constraints | Version-controlled pricing/application boundary. |
| Service comparison rows | Presentation | No | Yes, display composition | No | Code-owned presentation provider. |
| Contact field schemas, requiredness, allowed species, pricing mode | No | No | Yes | No | Application/form schema code. |
| Relocation import/export/transport marketing copy | Yes | Some compliance claims | No | Compliance claims may need review | Independent `ServicePage` content plus governed references where needed. |
| Relocation checklist phases/items | Yes, structured | Some regulatory facts | Completion behavior is browser/application state | Regulatory/safety wording may need review | `Checklist` aggregate plus independent page. |
| Tool name, route, icon, category | No | Tool identity/configuration | Route and rendering behavior | No | Shared code-owned tool catalogue. |
| Breed name, filters, temperament, origin, coat, overview | Yes/reference | Yes | Finder filtering/search | Health claims require review | `BreedReference`, with optional `ClinicalContent`. |
| Breed health considerations | Educational copy | Governed clinical reference | No | Yes | `BreedReference` plus clinical governance. |
| Behavior practical advice | Educational copy | Structured reference | Tool rendering | Escalation/health claims may be safety-sensitive | `BehaviorReference` plus governance where required. |
| Vaccination names, life-stage guidance, risk context | Educational copy | Governed reference | Tool display/tracking | Veterinary/product/jurisdiction decisions are safety-sensitive | Governed vaccination reference records. |
| Symptom labels and body areas | Educational/tool copy | Structured reference | Filtering and selection behavior | No by themselves | Tool content or governed symptom entries. |
| Symptom red flags and species-specific flags | No | No | Decision input | Yes | Version-controlled application safety policy. |
| Symptom severity, escalation, emergency outcome | No | No | Decision output | Yes | Application logic with clinical ownership and tests. |
| Emergency symptoms and educational explanations | Yes | Governed reference | Tool rendering | Some claims require review | Governed emergency reference content. |
| Emergency severity, immediate actions, avoid rules | Limited explanatory copy | Governed reference | Decision/escalation behavior | Yes | Controlled clinical/safety boundary, not ordinary CMS fields. |
| Medication identity, formulation, jurisdiction, label data | No | Yes | Dose display gate | Yes | Existing medication models plus clinical governance. |
| Guide/knowledge article prose | Yes | Sometimes | Publication/search/sitemap behavior | Optional clinical gate | Existing `Guide`/`KnowledgeArticle` plus optional `ClinicalContent`. |
| Search document body/title/URL | Derived content | No | Search indexing | No | `SearchDocument` projection only. |
| Gallery/testimonial images | Yes | No | Media rendering | No | Existing owning model plus Media Library. |

## 9. Database versus controller decision matrix

| Dataset | Remain controller/code | Move to database | Application service | Existing model | Reason |
| --- | --- | --- | --- | --- | --- |
| Services overview | Later, for now | Yes, selectively | Optional service/page composition | None | Growth, shared service identity, and editorial need justify a future boundary; current implementation can remain interim. |
| Boarding overview | Later, for now | Yes | Page composition | None | Independent page with substantial structured marketing content. |
| Boarding dogs | Later, for now | Yes | Page composition | None | Independent public identity and content; not a child blob. |
| Boarding cats | Later, for now | Yes | Page composition | None | Independent public identity and content; not a child blob. |
| Boarding exotic | Later, for now | Yes | Page composition | None | Independent public identity and content; not a child blob. |
| Grooming | Later, for now | Yes | Page composition | None | Editable marketing content; price authority stays outside. |
| Training | Later, for now | Yes | Page composition | None | Editable marketing content; behavior claims may require review. |
| Veterinary care | Later, for now | Yes, governed selectively | Page composition/clinical gate | None | Marketing content is editable candidate; clinical claims need a separate gate. |
| Service comparison | Yes | No current need | No | None | Presentation matrix is stable code-owned configuration. |
| Relocation overview | Later, for now | Yes | Page composition | None | Distinct overview owner. |
| Relocation import | Later, for now | Yes | Page composition | None | Distinct audience and compliance claims. |
| Relocation export | Later, for now | Yes | Page composition | None | Distinct audience and compliance claims. |
| Relocation transport | Later, for now | Yes for content only | Pricing/operations services remain code-owned | None | Page content and operational pricing have different owners. |
| Relocation checklist | Yes, until editorial need | Yes, as `Checklist` | Completion remains client/application behavior | None | Structured ordered content may grow; do not store completion state as content. |
| Careers marketing page | Yes | Conditional | Page composition | `JobOpening` for vacancies | Current copy is small; database only with actual editorial workflow. |
| Partnerships marketing page | Yes | Conditional | Page composition | None | Several paragraphs are not enough justification. |
| About page | Yes | Conditional | Page composition | `BusinessProfile`/`BusinessHour` for facts | Keep coherent page copy code-owned until independent editing is required. |
| Tool catalogue | Yes | No current need | Catalogue/search provider | None | Shared identity/configuration is stable and consumed by code. |
| New-pet checklist | Yes, until need | Conditional | Browser completion logic | None | Structured content could justify DB later; progress is not CMS data. |
| Breed reference | Yes, interim | Yes | Finder/search projection | None | 245 records and filtering/search justify a reference domain. |
| Behavior reference | Yes, interim | Conditional | Tool rendering | None | Six records do not require immediate DB, but structure and review flags support a future entity. |
| Vaccination reference | Yes, interim | Yes, governed | Clinical publication workflow | None; reuse clinical governance | Source, jurisdiction, version, and review requirements justify persistence. |
| Emergency educational entries | Yes, interim | Yes, governed | Clinical publication workflow | None; reuse clinical governance | Structured reference data may grow; safety fields require control. |
| Symptom educational labels | Yes, interim | Conditional, governed | Tool rendering | None; reuse clinical governance | Separate copy from safety rules before persisting. |
| Symptom red flags/escalation | No | No ordinary CMS | Yes | None | Application behavior and safety policy. |
| Medication guide | No controller-owned source | Already database-backed | Clinical publication gate | `Medication` family | Existing models are authoritative. |
| FAQs | No controller-owned source | Already database-backed | Query/projection | `Faq` | Controllers only project records. |
| Testimonials | No controller-owned source | Already database-backed | Query/projection | `Testimonial` | Controllers only project records. |
| Gallery | No controller-owned source | Already database-backed | Query/projection | `GalleryItem` | Controllers only project records. |
| Guides/knowledge | No controller-owned source | Already database-backed | Publication/search projection | `Guide`, `KnowledgeArticle` | Existing source-of-truth models. |

## 10. Media architecture

The existing media audit and current models establish a page/entity-oriented ownership model:

- `Guide`, `KnowledgeArticle`, `Product`, `Testimonial`, and `GalleryItem` already use model-associated Media Library collections.
- Future `ServicePage` media should be page-owned when the asset is a hero, card, process image, or page gallery item.
- Future `BreedReference` media should be entity-owned only if breed images become a real reference feature; the finder page should not become the owner of every breed image by default.
- `GalleryItem` remains the owner of gallery records. A service page can reference or display gallery material only when that relationship is intentional; copying a gallery row into page content would create competing ownership.
- Existing path fallbacks remain legitimate legacy/page assets. A future CMS migration should not casually replace them with unrelated shared files or break public URLs.
- Media ordering and “primary image” are concerns of the owning model/collection. They do not justify a new universal image entity beyond the existing Media Library infrastructure.

## 11. SEO and publication architecture

SEO fields belong to records that own a public, independently addressable resource. They do not belong automatically on every entity.

| Future record | SEO/indexability | Publication/editorial needs |
| --- | --- | --- |
| `ServicePage` | Yes: title, description, canonical/indexability, sitemap state, structured-data inputs where factual | Draft/published/archived, scheduling if needed, history, editor permissions, audit log. |
| `Service` | Usually no separate SEO fields initially | Identity/availability lifecycle; page publication belongs to pages. |
| `Checklist` | Usually page-owned | Versioned ordered content, draft/published state, history; no per-item SEO. |
| `BreedReference` | Only if individual breed URLs are introduced | Reference review, provenance, publication/version history, especially for health claims. |
| `BehaviorReference` | Tool page-owned unless topic routes exist | Review/versioning for advice and escalation content. |
| Vaccination/emergency reference | Tool/page-owned initially | Source, jurisdiction, review, version, publication gate, withdrawal, conflict handling. |
| `Careers`/`Partnerships` page content | If eventually made independently addressable/editable | Editorial draft/publish/history only if real editing need exists. |

SEO serialization and Schema.org generation remain application responsibilities. A database record may supply factual inputs; it should not become the owner of generic SEO mechanics.

## 12. Editorial, provenance, and workflow requirements

### Marketing/service/about content

Only database-back it when a real editor, change cadence, scheduling need, page growth, or reuse requirement exists. Where it is database-backed, draft/published/archived state, publication time, history, permissions, and audit logging are appropriate. Clinical claims inside otherwise marketing pages need a separate review boundary rather than silently inheriting ordinary marketing publication.

### Structured reference data

Breed and behavior records need stable identifiers, search/filterable fields, ordering where relevant, and an editorial review trail. Health claims should have source/provenance and a review state even if the remainder of the record is non-clinical.

### Governed clinical reference

Vaccination, emergency, symptom educational content, behavior escalation guidance, and breed health considerations need:

- source/provenance and source version;
- jurisdiction and species scope;
- explicit content version;
- reviewer identity/credential and decision;
- publication state separate from clinical approval;
- review due date;
- conflict and withdrawal handling;
- immutable or reconstructable history;
- controlled access and audit logging.

The existing clinical models cover most of this. The missing design question is how concrete future tool/reference records become `contentable` records without putting decision logic into editable fields.

### Operational/application data

Pricing, route aliases, form semantics, calculator formulas, symptom decisions, emergency escalation, and completion state are not editorial records. They require application tests, code review, and operational ownership rather than CMS publication workflow.

## 13. Overlap with existing knowledge content

The following distinctions prevent accidental merging:

| Comparison | Finding |
| --- | --- |
| Same subject | A Guide, Knowledge Article, Breed Reference, and Behavior Reference may all discuss “pet care,” but subject overlap does not make them one entity. |
| Same entity | A Guide has a public slug and article lifecycle; a Breed Reference has structured biological/reference identity; these are different entities even when both contain prose. |
| Same content type | Several records may contain rich text, but rich text is a field format, not an ownership model. |
| Same clinical governance | A Guide, Medication, Vaccination Reference, or Emergency Reference may all require clinical review, but `ClinicalContent` is a governance envelope, not a replacement for the concrete domain record. |
| Same search index | `SearchDocument` can index all of them, but it remains a derived projection and must not become the source of truth. |
| Same tool page | The tool catalogue, tool educational copy, structured entries, and safety rules share a route family but have different responsibilities. |

Recommendation: keep `Guide` and `KnowledgeArticle` as separate existing editorial models; add structured reference models only where the structured domain requires them; reuse `ClinicalContent` for governance rather than merging all clinical material into an article table.

## 14. Unresolved architectural questions

Only the following questions remain genuinely dependent on evidence that is not present in the current code/schema.

1. **Who is the real editor for marketing service/about content?**  
   The code shows Filament administration for existing models but no demonstrated editorial workflow for the controller-owned service/about datasets. The answer determines whether `ServicePage` and dedicated careers/partnerships content are implemented now or remain code-owned. Evidence: confirmed editor roles, expected change frequency, and a concrete request for non-developer editing.

2. **Do service variants need independent operational identity?**  
   Boarding species and relocation directions have independent pages and pricing keys, but the current code does not prove that they need separate availability, capacity, authorization, or booking lifecycles. The answer determines whether a `ServicePage` variant key is enough or whether a separate `ServiceOffering`/variant entity is justified. Evidence: booking, capacity, eligibility, and reporting requirements.

3. **Which clinical sources and jurisdictions govern the tool reference data?**  
   The current ToolData includes review markers but does not attach each breed, behavior, vaccination, emergency, or symptom entry to an actual source record. The answer determines publication readiness and concrete clinical content relationships. Evidence: named clinical owner, approved sources, jurisdiction scope, and review policy per content family.

4. **Will breed and behavior entries have individual public URLs?**  
   The current feature is a filterable finder/tips page, not a confirmed per-entry route. The answer determines whether those records need individual slug, canonical, indexability, and sitemap fields. Evidence: SEO/product requirements for individual entries.

5. **Should relocation and new-pet checklists share an editable schema?**  
   Both have ordered sections/items, but they have different audiences and one may carry relocation-specific regulatory facts. The answer determines whether one generic `Checklist` aggregate is appropriate or whether two bounded checklist types should share only implementation primitives. Evidence: planned editor experience, reuse requirements, and versioning policy.

6. **Do pages need explicit FAQ assignment rather than category projection?**  
   Existing FAQ filtering works by category/subcategory and no page-specific assignment requirement is present. The answer determines whether a page/FAQ pivot is warranted. Evidence: an editorial requirement to place the same category FAQ selectively on some pages but not others.

## 15. Unnecessary database candidates

The following should explicitly not be migrated to ordinary database content under the current evidence:

- canonical service prices, price tiers, quote/estimate modes, distance bands, transport surcharges, and pricing aliases;
- symptom red-flag IDs, species red-flag rules, severity, escalation, and decision thresholds;
- emergency severity, prohibited-action policy, and safety escalation logic;
- medication dose visibility and clinical safety gates;
- contact form schemas, conditional requiredness, allowed species, and pricing mode;
- service comparison matrices and related display-only configuration;
- tool catalogue route names, icons, and related-tool mappings while the catalogue remains small and stable;
- tiny immutable homepage/about/FAQ-page shell copy;
- database projections already owned by `Faq`, `Testimonial`, `GalleryItem`, `JobOpening`, `Guide`, `KnowledgeArticle`, `Product`, `BusinessProfile`, `BusinessHour`, and the medication/clinical models;
- search documents, because they are derived index records rather than editorial source records;
- browser-local checklist completion state;
- tables for every section, bullet, CTA, paragraph, or feature solely to normalize nested arrays.

## 16. Recommended future implementation order

This is sequencing guidance, not implementation authorization.

1. Keep the current code-owned state stable while defining the service identity vocabulary and confirming editorial ownership.
2. If editorial demand is confirmed, introduce `Service` and independent `ServicePage` records first; migrate one non-clinical service page family while preserving canonical routes and pricing references.
3. Add structured checklist persistence only when both editing/versioning requirements and the relationship between relocation and new-pet checklists are known.
4. Move the breed catalogue into `BreedReference` when search/editing/provenance benefits outweigh the migration cost; keep the finder route stable.
5. Define clinical source ownership and concrete contentable record shapes before persisting vaccination, emergency, behavior, symptom, or breed health material.
6. Extend the existing clinical governance boundary rather than creating a second review/publication system.

## Final answer

### What should become database-backed

- A canonical Services domain and independently identified service pages, when editorial ownership is confirmed.
- The 245-record breed reference catalogue.
- Governed vaccination and emergency reference records when source/jurisdiction/review ownership is established.
- A structured checklist aggregate when checklists need editing, versioning, or reuse.
- Behavior and symptom educational records selectively, after separating copy from safety behavior.

### What should remain code-owned

- Pricing authority and calculations.
- Pricing aliases, transport rules, quote modes, form semantics, and calculators.
- Symptom red flags, severity, escalation, prohibited rules, and other safety decisions.
- Tool catalogue identity/configuration while it remains small and stable.
- Small immutable page shells and presentation matrices.
- Browser-local checklist completion state.

### What should be reused

- `Faq`, `Testimonial`, `GalleryItem`, `JobOpening`, `Guide`, `KnowledgeArticle`, `Product`, `BusinessProfile`, `BusinessHour`, `Medication`, `MedicationFormulation`, `MedicationJurisdiction`, `ClinicalContent`, `ClinicalSource`, `ClinicalReview`, `ClinicalContentVersion`, `ClinicalToolReview`, `SearchDocument`, and Spatie Media Library.

### What is genuinely new

- `Service` and `ServicePage` as separate domain/page concerns.
- `Checklist` and its ordered children only when justified by editing/versioning needs.
- `BreedReference`.
- A structured behavior reference record when its current six-entry code catalogue grows or needs editorial management.
- Concrete governed vaccination/emergency reference records if those datasets leave code ownership.

### How page independence is preserved

Each boarding page and each relocation page remains an independently identified public page record, even when it references the same higher-level `Service`. Shared domain identity does not imply parent/child content inheritance, and no single generic relocation or boarding content blob is proposed.

No database implementation should begin until the unresolved ownership and clinical-source questions are answered. 
