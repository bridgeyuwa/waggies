# Waggies — Domain Architecture Decisions

> Superseded clinical decision: the clinical governance boundary described in this historical decision record has been removed. Current retained safety behavior, medication ownership, and schema cleanup are documented in `CLINICAL-GOVERNANCE-REMOVAL-AUDIT.md`.

## Status and scope

This document resolves the remaining database and domain questions identified by `DATABASE-CONTENT-ARCHITECTURE-AUDIT.md`.

It is a read-only architecture decision record. It does not authorize migrations, model changes, route changes, controller changes, view changes, configuration changes, seeders, tests, Filament resources, package installation, or data migration.

The decisions are based on the current Laravel implementation, especially:

- `app/Http/Controllers/ServicesController.php` and `RelocationController.php`;
- `app/Http/Controllers/BookingRequestsController.php`, `ContactController.php`, and `ToolData.php`;
- `app/Support/PublicUrlCatalog.php`, `SearchCatalog.php`, and `SearchDocumentSynchronizer.php`;
- `routes/web.php` and `config/waggies_pricing.php`;
- the current FAQ, booking, clinical governance, media, guide, knowledge article, and product models/migrations;
- the existing public views for service, relocation, tool, FAQ, and checklist pages.

The current code-owned datasets remain authoritative until a later implementation deliberately migrates them.

## A. Executive decisions

| Question | Decision | Confidence |
| --- | --- | --- |
| Service versus ServicePage | **A — both are required.** `Service` owns canonical service/domain identity; `ServicePage` owns each independently addressable public service page. | High |
| Boarding variants | Use `Service` + explicit operational `ServiceOffering` records for dogs, cats, and exotic boarding, with separate `ServicePage` records. The overview is a page for the boarding service, not an offering. | High |
| Relocation variants | Use `Service` + operational offerings for import, export, and local transport, with separate pages. The relocation checklist is a checklist/content artifact, not an offering. | High |
| Clinical/reference records | Use concrete structured reference entities and attach governed claims to the existing `ClinicalContent` envelope. Do not use one generic tool-content table. | High |
| Breed public identity | **B — stable data identity, but no individual public URLs yet.** The existing product proves a finder catalogue, not a breed-page product. | High |
| Checklist architecture | **B — separate bounded checklist aggregates sharing ordered section/item implementation primitives.** Do not force relocation and new-pet checklists into one generic domain root. | High |
| FAQ assignment | **A — category/subcategory projection is sufficient now.** Keep explicit page assignment out until selective page curation is an actual requirement. | High |
| Public page identity | A page is independently addressable only when it owns a stable route key/URL, page-level publication and SEO state, and a distinct public presentation. | High |
| Media ownership | Page-specific media belongs to `ServicePage` or the owning public entity. Shared gallery assets remain owned by `GalleryItem`; do not add a universal image owner. | High |
| SEO ownership | SEO belongs to the independently addressable public page/resource, not automatically to its domain parent or to every embedded/reference record. | High |
| Publication/governance | Marketing pages use ordinary editorial publication; clinical facts use `ClinicalContent` governance; operational rules and browser state remain application-owned. | High |

### The central boundary

Waggies needs three different kinds of identity:

```text
Service / ServiceOffering
    canonical business and operational meaning

ServicePage / public resource
    independently addressable presentation and SEO identity

ClinicalContent
    review, provenance, approval, publication, and withdrawal governance
```

These identities may be related, but they must not be collapsed merely because one page currently renders all of them together.

## B. Service architecture

### Decision

Waggies requires both `Service` and `ServicePage`.

`Service` is the canonical business concept. It is referenced by pricing keys, booking/contact context, service classification, future reporting, and the offering catalogue. It must not be a bag of marketing sections.

`ServicePage` is the independently managed public representation of a service or offering. It owns page content, route identity, page media, SEO, and ordinary page publication state.

The relationship is:

```text
Service
  ├── ServiceOffering(s), where operational identity exists
  └── ServicePage(s), one for each independently addressable public page
```

A `ServicePage` belongs to a `Service` and may optionally reference a `ServiceOffering`. This allows all of the following without inventing a generic CMS page:

- a services overview page belonging to the services domain but no single offering;
- a boarding overview page belonging to `boarding` but no single species offering;
- a dog boarding page belonging to `boarding` and referencing the dog-boarding offering;
- an import page belonging to `relocation` and referencing the import offering;
- a relocation checklist page belonging to the relocation page family and rendering a checklist aggregate.

### Why one `Service` record is not enough

The current implementation already separates page concerns from service concerns:

- one boarding family has an overview plus three species pages;
- relocation has an overview plus import, export, transport, and checklist URLs;
- service pages have different titles, descriptions, canonical URLs, Schema.org names, hero assets, FAQs, and structured content;
- booking and contact flows use service keys and variants independently of page rendering;
- pricing is resolved from canonical configuration rather than from the page content arrays;
- the same service identity is projected into different contexts such as booking, pricing, FAQs, testimonials, search, and future reporting.

The code therefore demonstrates a real distinction between the thing Waggies offers and the public page used to explain or request it.

### Why `ServicePage` is not a generic CMS wrapper

`ServicePage` has a bounded domain responsibility: it represents a service-facing public route. It is not proposed as a replacement for `Guide`, `KnowledgeArticle`, `Product`, `Faq`, or every other public resource. Those existing models already own their own identities and lifecycles.

The page record should contain the service page's structured marketing content as embedded data until a nested item gains independent identity, querying, reuse, or governance requirements. Do not create tables for every feature, benefit, bullet, CTA, process step, or comparison row.

### Service identity and SEO

`Service` owns canonical domain identity and operational availability. It does not own page-level SEO by default. A service can have multiple pages with different titles, descriptions, canonical URLs, indexability, sitemap participation, structured-data inputs, and media.

`ServicePage` owns those page-level values. A service-level SEO record is unnecessary unless Waggies later gives the service itself a distinct canonical URL separate from all of its pages.

### Service identity and pricing

Pricing remains application-owned in `config/waggies_pricing.php`. A future service or offering may carry a stable pricing reference such as `pricing_key`, but it must not own amounts, distance bands, quote rules, surcharges, or calculator formulas. The current code and tests explicitly establish configuration as pricing authority.

## C. Boarding architecture

### Family decision

Boarding has both public page identity and operational offering identity. The operational distinction is proven by more than URL nesting:

- `config/waggies_pricing.php` has separate dog, cat, and exotic tier sets;
- booking/contact context carries a boarding variant;
- boarding options are selected by species variant;
- the public intake language and required care context differ by species;
- dog, cat, and exotic pages describe materially different facilities, safety conditions, care routines, and pre-arrival requirements.

This is enough to justify a focused `ServiceOffering` boundary. It is not evidence for a larger availability, capacity, or resource-allocation system; those remain out of scope until the application requires them.

### Explicit classification

| Current concept | Future classification | Required relationship | Reason |
| --- | --- | --- | --- |
| `/services/boarding` | `ServicePage` for the canonical `boarding` service | `ServicePage.service_id → Service` | It is a distinct overview page with its own content, hero, FAQs, and canonical URL. It is not itself a bookable species offering. |
| `boarding-dogs` | `ServiceOffering` plus a dog boarding `ServicePage` | `ServiceOffering.service_id → boarding`; page references offering | It has its own pricing tiers, booking context, pet-type semantics, care policy, and page content. |
| `boarding-cats` | `ServiceOffering` plus a cat boarding `ServicePage` | Same pattern | It has its own tiers, cat-specific facilities, dog-free handling context, and intake meaning. |
| `boarding-exotic` | `ServiceOffering` plus an exotic boarding `ServicePage` | Same pattern | It has distinct species scope, habitat requirements, specialist handling, estimates/quotes, and pre-arrival consultation. |

The offering key may initially remain a stable application identifier while the current code-owned flow is in place. A future database record should not duplicate the pricing authority or turn every pricing tier into a service offering.

### What is not yet justified

No separate tables for boarding capacity, suites, staff allocation, availability slots, or species eligibility are decided here. The current application confirms distinct offerings but not those deeper operational subsystems.

## D. Relocation architecture

### Family decision

Relocation is a canonical `Service` with distinct operational offerings and independently addressable public pages. The current application supplies distinct quote semantics, input fields, pricing keys, and service identifiers for import, export, and transport.

### Explicit classification

| Current concept | Future classification | Reason |
| --- | --- | --- |
| `/services/relocation` | Relocation `ServicePage` for the `relocation` service | It is an overview with its own hero, cards, FAQs, canonical URL, and service schema. |
| `/services/relocation/import` | `ServiceOffering` `relocation-import` plus independent `ServicePage` | Import has direction-specific origin/destination semantics, permit and documentation requirements, a quote flow, and a distinct page identity. |
| `/services/relocation/export` | `ServiceOffering` `relocation-export` plus independent `ServicePage` | Export has different direction-specific compliance, destination, airline, and documentation requirements, plus its own quote flow. |
| `/services/relocation/transport` | Local-transport `ServiceOffering` plus independent `ServicePage` | Transport is operationally distinct: it has product keys, route distance, pickup/drop-off, vehicle, pet-count, waiting, stop, urgency, and safety inputs. Its public page is grouped under relocation, but its operational identity is `local-transport`. |
| `/services/relocation/checklist` | Independent public page rendering a `RelocationChecklist` aggregate | It is a planning/document artifact. It has ordered phases and items but no booking, quote, availability, or operational lifecycle. It is not a service offering. |

### Relocation and transport are deliberately separated

The public information architecture groups local transport under relocation, but the code does not treat transport as merely relocation copy. `local-transport` has its own pricing configuration and a dedicated request schema, including route-distance calculations, transport products, waiting, extra stops, same-day handling, after-hours requests, and safety assessment fields.

Therefore transport receives operational offering identity while remaining free to use a relocation-family page route. URL hierarchy does not define the domain model.

### The checklist is not a relocation offering

The relocation checklist is a public planning tool. It explains preparation phases and can be printed. It does not select a service tier, create a booking, calculate a quote, reserve capacity, or represent a customer transaction. Its domain is checklist content, not service operations.

## E. Clinical/reference architecture

### Existing governance boundary

The current clinical architecture already provides:

- `ClinicalContent` as the contentable governance envelope;
- `ClinicalSource` and the many-to-many source relationship;
- `ClinicalReview` with reviewer identity, credentials, decision, version, and review timing;
- `ClinicalContentVersion` snapshots;
- publication, approval, withdrawal, source-conflict, jurisdiction, risk, and review-due fields;
- `ClinicalToolReview` for whole-tool review;
- `ClinicalContentWorkflow` and `ClinicalPublicationGate`;
- policies and Filament workflow actions.

This is sufficient infrastructure to reuse. It is not a concrete breed, symptom, vaccination, or emergency domain model. `ClinicalToolReview` governs a whole tool and must not be stretched into item-level content ownership.

### Clinical decision table

| Family | Current source | Future entity | Existing model reuse | `ClinicalContent` relationship | Editable | Must remain code-owned | Decision |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Breed | `ToolData::reference()['breeds']`, approximately 245 structured records | `BreedReference` | No suitable existing model; do not use `Guide`, `KnowledgeArticle`, or `GalleryItem` as the breed owner | Optional for the record's health-claim subset; use the record as `contentable`, not as a replacement for the record | Structured identity, filters, narrative overview, and non-clinical reference facts; health claims only through governed workflow | Finder interaction, filters, URL policy, and any safety/clinical interpretation | Create a dedicated structured reference boundary when persisted. Keep individual public pages out for now. |
| Behavior | `ToolData::reference()['behavior']`, six structured entries with advice, escalation, and review intent | `BehaviorReference` when the catalogue leaves code ownership | No existing model is equivalent; `KnowledgeArticle` would change the meaning into article content | Optional/required for entries whose advice, escalation, or health claims are clinically consequential | Topic identity, species, category, explanatory advice, and practical guidance subject to review | Escalation policy, safety thresholds, and any decision behavior | Decide the future shape now, but keep the current small catalogue code-owned until editorial growth or a real editing requirement exists. |
| Vaccination | `ToolData::vaccination()`, grouped by species and life-stage/context | `VaccinationReference` | No existing vaccine model; reuse `ClinicalSource`, reviews, versions, and publication gate | Required for governed vaccination guidance; normally one governance record per reference entry/version | Vaccine/topic identity, species, life-stage context, educational guidance, jurisdiction and source metadata | Patient-specific product/timing decisions, universal schedule claims, and veterinary safety behavior | Persist only as a governed reference record, never as ordinary CMS copy. |
| Emergency | `ToolData::emergency()`, structured by species/category/title/symptoms/actions/avoid | `EmergencyReference` | No existing emergency model; reuse the clinical governance system | Required for educational emergency material and any controlled reference entry | Educational description, context, symptoms, and reviewed action wording | Severity interpretation, red-flag decisioning, immediate escalation policy, prohibited-action policy, and emergency routing | Use a governed reference record, with safety-policy fields separately controlled. |
| Symptom identity/content | `ToolData::symptomChecker()`: pet types, body areas, symptom IDs/labels, explanatory guidance | `SymptomReference` or equivalent structured symptom records | No existing model; do not use generic `ToolContent` | Governed where educational copy or triage explanation is clinically consequential | Stable symptom identity, body-area grouping, species applicability, labels, limitations, and educational guidance | Selected-symptom evaluation, red-flag mapping, severity, escalation result, and rule thresholds | Separate persisted content from decision logic. Persist structured records only when editing/provenance requires it. |
| Symptom decision logic | `red_flag_ids`, `species_red_flag_ids`, severity and escalation output in `ToolData` and application behavior | No ordinary CMS entity | Reuse `ClinicalToolReview` only for tool-level review; it is not a rule store | If a governed policy artifact is later designed, it needs its own controlled boundary; this is not decided as a CMS table | Nothing freely editable through normal content forms | Red flags, species rules, severity, escalation, prohibited actions, and decision thresholds | Remain application-owned, version-controlled, clinically reviewed, and regression-tested. |

### BreedReference

`BreedReference` is justified by the size and structure of the current catalogue. Each record has a stable ID, species, filters, temperament, exercise/grooming needs, living environment, origin, coat, trainability, overview, weight/lifespan, and health considerations. That is a structured reference domain rather than article prose.

The non-clinical record and the health-claim governance are separate concerns. A breed record may exist as reference data while its health considerations are pending, reviewed, changed, or withdrawn. The finder does not need to become unavailable merely because a health-claim subset is under review; the public projection must omit or qualify content that fails the clinical gate.

### BehaviorReference

Behavior is a structured reference family, but the current six-entry catalogue is still small and code-owned. The future entity is decided as `BehaviorReference` rather than `KnowledgeArticle`, because its stable topic ID, species, category, practical advice, escalation intent, and structured rendering are not article identity.

The future record must not make `escalate` or similar safety behavior an unrestricted editorial boolean. Educational advice may be edited under appropriate review; escalation policy remains controlled application behavior.

### VaccinationReference

The current vaccination data deliberately avoids a universal fixed calendar. It uses species and life-stage/context groupings and repeatedly directs product selection and timing to a veterinarian, local risk, records, and current product rules.

`VaccinationReference` therefore owns reviewed educational reference data, jurisdiction and source context, and version identity. It does not own patient-specific schedules, prescribing decisions, or a universal automated vaccination rule.

### EmergencyReference

Emergency entries contain both content and safety policy. The future entity may persist symptoms, explanatory context, and reviewed immediate-action wording, but severity, red flags, escalation, and prohibited-action behavior must remain behind an explicit controlled boundary. The record must not turn “avoid” rules into ordinary marketing fields that any editor can change without clinical review.

### SymptomReference and decision logic

The current symptom checker already exposes the separation:

```text
pet types, body areas, symptom IDs, labels, limitations, guidance
    structured educational/tool content

red_flag_ids, species red flags, severity, escalation, thresholds
    application-owned safety policy and decision logic
```

If the content side is persisted, stable symptom IDs must remain the join point used by code-owned rules. A content edit must not silently change the triage outcome.

### Governance relationship

For governed reference records, the concrete record owns domain identity and structured fields. `ClinicalContent` owns clinical status, publication gate, risk, jurisdiction, source links, review history, version snapshots, conflicts, due dates, and withdrawal.

The existing `ClinicalToolReview` remains useful for reviewing an entire tool such as the symptom checker or emergency guide. It is not a substitute for item-level `ClinicalContent` attached to a `VaccinationReference`, `EmergencyReference`, `BehaviorReference`, `SymptomReference`, or governed breed claim.

## F. Breed URL decision

### Decision: B — stable database identity, no individual public URLs yet

Every breed already has a stable data identity in the current provider: an ID such as `affenpinscher`, species, and a structured record. A future `BreedReference` should preserve that stable canonical key, independently of whether it ever receives a route.

Individual public URLs are not justified by the current product:

- the only route is `/tools/breed-finder`;
- the view presents a filterable finder rather than linked detail pages;
- `PublicUrlCatalog` includes the finder route but no breed route;
- the sitemap has no breed-entry expansion;
- `SearchCatalog`/`SearchDocumentSynchronizer` do not index breed entries as independent public documents;
- there is no canonical URL, slug history, per-breed SEO metadata, or internal-linking contract;
- the comparable public entities with individual identity—`Guide`, `KnowledgeArticle`, and `Product`—already have slugs, route binding, publication/indexability fields, and sitemap/search behavior.

Therefore:

- **Data identity:** yes, stable breed key plus species and name.
- **Public identity:** no `/breeds/{slug}` route now.
- **SEO identity:** the finder page owns title, description, canonical, indexability, and sitemap participation. Breed records do not receive individual SEO fields or sitemap state until individual routes are a deliberate product requirement.

If breed URLs are later introduced, the URL-bearing public representation must gain its own slug/canonical/indexability/sitemap contract. That later decision must not be assumed merely because the data is database-backed.

## G. Checklist decision

### Decision: B — separate checklist aggregates sharing implementation primitives

The two current checklists are related by presentation shape but not yet by one bounded domain:

| Checklist | Current structure | Interaction | Meaning | Owner/page relationship |
| --- | --- | --- | --- | --- |
| Relocation | Ordered phases with icons and ordered items | Static planning artifact; printable; no completion state | Travel/document preparation for a relocation service | Rendered by the independently addressable relocation checklist page |
| New pet | Ordered titled sections with stable item IDs | Interactive checkboxes; progress is saved in browser local storage; printable | General preparation and settling-in for a new pet | Rendered by the code-owned new-pet tool route |

The common shape does not establish one aggregate owner, one lifecycle, or one user interaction model. The audiences, page relationships, semantics, and current ownership are different.

### Aggregate design

Use two bounded roots when persistence becomes justified:

```text
RelocationChecklist
  └── owned ordered sections/phases
        └── owned ordered items

NewPetChecklist
  └── owned ordered sections
        └── owned ordered items
```

They may share implementation primitives for ordered section/item data, validation, version snapshots, and rendering helpers. Those primitives must not become globally reusable `Section` and `Item` entities without evidence that items are shared independently and need their own identity.

Each aggregate owns the order and lifecycle of its children. Child items are not globally reusable content by default.

### Editorial content versus completion state

Checklist title, description, section order, item order, labels, stable item keys, publication state, and versions are editorial content.

The user's checked/unchecked state is browser/application state. The current new-pet view explicitly stores progress in local storage; it is not CMS content and must not be persisted in checklist tables as editorial state. The relocation checklist currently has no completion state at all.

### Versioning and publication

Each bounded checklist should be versionable when editorial persistence is introduced. A published checklist version must remain reconstructable for a user-facing page and print artifact. A draft edit must not mutate the meaning of a currently published version without an explicit publication step.

Clinical or regulatory wording inside a checklist is subject to the same clinical governance boundary as other reference content. Checklist publication and clinical approval are related gates, not one generic lifecycle.

### Page relationship

The relocation checklist page owns the public URL and page-level SEO. It renders the current published `RelocationChecklist`. The checklist does not own the page's canonical URL or per-item SEO.

The new-pet tool page currently remains code-owned. If its editorial content is later persisted, it should render a published `NewPetChecklist`; browser progress remains separate.

## H. FAQ assignment decision

### Decision: A — category/subcategory projection is sufficient now

The current implementation already uses category and subcategory as a deliberate projection mechanism:

- the main FAQ page groups and filters by category;
- the services overview projects the `services` category;
- the boarding overview projects general boarding FAQs;
- each boarding species page projects the boarding category plus either general or species subcategory FAQs;
- relocation import/export pages project shared relocation FAQs plus the matching subcategory;
- transport projects the separate `transport` category;
- a FAQ with no subcategory can intentionally appear across pages in that category;
- the same FAQ record can therefore be projected to multiple relevant pages without a pivot.

The current code does **not** show an editor requirement such as “put FAQ X on import but not export even though both use relocation,” nor does it show page-specific ordering or assignment management. A many-to-many page pivot would therefore introduce a new editorial relationship without a demonstrated need.

Keep `Faq.category`, `Faq.subcategory`, publication, and ordering as the source of the current projection. Do not add an explicit page assignment yet.

The decision should be revisited only when a concrete requirement demands arbitrary selective placement that category/subcategory cannot express. That requirement would justify a hybrid migration path: retain categories for broad classification and add explicit assignments only for exceptions. It is not a current architecture requirement.

## I. Public page identity

### Definition

An independently addressable public page is a resource with all of the following:

1. a stable domain/page identity that is not only an array index or controller method;
2. a stable route key and canonical URL;
3. an independently meaningful public presentation and navigation target;
4. page-level publication state, including whether it is eligible to render publicly;
5. page-level SEO state where applicable: title, meta description, canonical, indexability, and sitemap participation;
6. an intentional media owner for page-specific assets;
7. a clear source of structured-data inputs, without moving Schema.org serialization into the database;
8. a lifecycle/history boundary appropriate to the content's risk and editorial needs.

For editable slugs, stable URL history and stale-slug protection are part of identity. `Guide` and `KnowledgeArticle` establish this existing convention.

### What is not a page

The following are not independently addressable pages merely because they appear in a route family or are rendered on a page:

- a service offering key;
- a pricing tier;
- a checklist section or item;
- a breed record shown inside the finder;
- a behavior, vaccination, emergency, or symptom entry shown inside a tool;
- an FAQ projected into a page;
- a testimonial or gallery item projected into several pages;
- a search document;
- a feature, benefit, bullet, CTA, card, paragraph, or process step.

Their own route, SEO, publication, media, or governance requirements would have to be demonstrated before they become public page resources.

### Current versus future page sources

The current service and relocation pages are controller-owned compositions. The architecture decision identifies the correct future boundary; it does not claim that every page must be migrated immediately. The existing static routes and page metadata remain the current runtime source until an implementation phase is authorized.

## J. Media ownership

### Decision

Use the existing Spatie Media Library model-associated ownership. Do not redesign the media system or create a universal image table.

| Asset role | Owner |
| --- | --- |
| Service-page hero, card, process, gallery, or page-specific image | `ServicePage` |
| Asset that is genuinely shared at the service/domain level and has no page-specific crop or meaning | `Service`, only if such a service-owned asset is demonstrated |
| Breed image, if a real breed-image feature is introduced | `BreedReference`, not the finder page and not an implicit `GalleryItem` |
| Checklist illustration | The owning public page initially; checklist children do not own media by default |
| Gallery photograph | Existing `GalleryItem` |
| Testimonial photograph | Existing `Testimonial` |
| Guide, knowledge article, or product media | Existing owning model |
| Search result or derived projection | No media ownership; resolve media from the source owner |

Page-specific imagery must remain separate from shared assets when ownership, crop, public URL history, or visual role differs. One physical file may be reused when it is genuinely the same source asset and the visual role is the same; reuse is not a reason to erase domain ownership.

The current path-based service media is legitimate code-owned/page-owned content. A later database migration must preserve public URL behavior and must not casually turn page-specific assets into a shared gallery pool.

## K. SEO ownership

### Decision

SEO state belongs to the independently addressable public resource.

| Entity | SEO ownership |
| --- | --- |
| `ServicePage` | Owns page title, meta description, canonical, indexability, sitemap participation, and factual structured-data inputs. |
| `Service` | No page SEO by default; only owns domain identity and availability. |
| `ServiceOffering` | No SEO by default; its page owns SEO. |
| Relocation/new-pet checklist aggregate | No independent SEO; its public page owns SEO. |
| `BreedReference` | No per-record SEO until individual breed routes exist. The breed finder page owns current SEO. |
| `BehaviorReference`, `VaccinationReference`, `EmergencyReference`, `SymptomReference` | Tool/page owns SEO initially; record-level SEO only if a record becomes an independently addressable public resource. |
| `Faq` | Existing FAQ page/category projection owns page metadata and schema; individual FAQ rows do not get standalone SEO. |
| `Guide`, `KnowledgeArticle`, `Product` | Continue reusing their existing slug, SEO, publication, indexability, and sitemap fields. |

The application remains responsible for serializing metadata and Schema.org. A record supplies factual inputs; it does not become a generic SEO engine.

## L. Publication and governance ownership

### Marketing and public service content

`ServicePage` requires ordinary editorial lifecycle when it becomes database-backed:

- draft;
- published;
- archived or withdrawn from public navigation;
- `published_at` where scheduling is useful;
- revision/history and audit trail;
- editor permissions;
- optional scheduling only when a real publishing requirement exists.

The page's publication state controls public rendering and sitemap/indexability. It does not control operational availability or pricing authority.

### Service and offering lifecycle

`Service` and `ServiceOffering` need a small availability/active lifecycle for domain use, but they do not inherit page publication automatically. An offering may be operationally unavailable while its explanatory page remains useful, or a page may be unpublished while the offering is still known internally.

Do not add capacity, booking-state, eligibility, or availability schedules until those operational requirements are evidenced.

### Clinical/reference content

Concrete governed reference records reuse `ClinicalContent` for:

- clinical status and approval;
- publication gate;
- risk and jurisdiction;
- source provenance and source version;
- reviewer identity, credential, decision, and next review;
- immutable/reconstructable content versions;
- source conflicts;
- withdrawal and withdrawal reason.

Ordinary editorial publication and clinical approval are separate concerns. A record is not publicly eligible merely because an editor marked it published.

Non-clinical breed identity fields may have ordinary reference lifecycle, but health claims require the clinical envelope. Behavior, vaccination, emergency, symptom educational copy, and veterinary claims in service pages must use the appropriate review boundary rather than bypassing it.

### Code-owned definitions and state

The following remain application-owned and version-controlled:

- pricing amounts, tiers, formulas, aliases, distance bands, surcharges, and quote semantics;
- booking and contact form schemas, requiredness, allowed species, and intent semantics;
- service comparison matrices and static tool catalogue routes/icons;
- symptom red flags, species rules, severity, escalation, and prohibited actions;
- emergency escalation and safety policy;
- medication dose visibility and safety gates;
- browser-local checklist completion state;
- derived search documents and sitemap mechanics.

## M. Proposed entity map

These are future design targets only. This section does not authorize their creation.

| Entity | Purpose | Identity | Domain owner | Key attributes | Relationships | Page relationship | Media relationship | SEO relationship | Publication/governance |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `Service` | Canonical business service family | Stable key/slug such as `boarding`, `grooming`, `training`, `vet-care`, `relocation` | Services | Key, display name, category, active/available state, optional non-authoritative pricing references | Has many offerings and pages | Parent of service pages; not necessarily a public URL itself | Only genuinely service-owned assets | No page SEO initially | Small domain lifecycle; no automatic page publication |
| `ServiceOffering` | Operationally meaningful bookable/quotable offering | Stable offering key such as `boarding-dogs`, `relocation-import`, `local-transport` | Services/Operations | Service, key, label, scope, active state, application references, optional variant attributes | Belongs to Service; may have pages | Optional target of one or more offering pages | Only offering-owned assets if a real shared operational asset exists; otherwise page owns | No SEO by default | Active/inactive operational state; pricing remains config-owned |
| `ServicePage` | Independent public service-page identity and content | Stable page key plus route/canonical identity; slug history if editable | Services/Content | Service, optional offering, page kind, structured content, route key, publication, SEO, factual schema inputs | Belongs to Service; optionally references Offering; may render FAQ/checklist | Owns one independent public URL | Owns page-specific Media Library collections | Owns title, description, canonical, indexability, sitemap state | Draft/published/archived, history, permissions, audit; clinical claims may have a separate gate |
| `RelocationChecklist` | Ordered relocation preparation content | Stable key, version | Relocation/Tools | Title, description, version, ordered phases/items, publication | Owns ordered children | Rendered by relocation checklist page | Page-owned initially | Page-owned | Versioned publication; regulatory/clinical wording reviewed as needed |
| `NewPetChecklist` | Ordered new-pet preparation content | Stable key, version | Tools | Title, description, version, ordered sections/items, publication | Owns ordered children | Rendered by the new-pet tool page | Page-owned initially | Page-owned | Versioned publication; browser completion is separate |
| `ChecklistSection` / phase | Ordered child of one checklist | Stable key only within its aggregate unless proven otherwise | Owning checklist | Heading/title, icon or presentation data, order | Belongs to one checklist | No page identity | No media by default | None | Changes with parent version |
| `ChecklistItem` | Ordered child of one checklist section | Stable completion/content key within its aggregate | Owning checklist | Label, stable key, order, optional notes | Belongs to one section and therefore one checklist | No page identity | No media by default | None | Changes with parent version; not globally reusable by default |
| `BreedReference` | Structured breed reference and finder data | Stable breed key, species, name | Tools/Reference | Species, filters, temperament, origin, coat, overview, lifespan/weight, health considerations | Optional governed `ClinicalContent` | Finder projection initially; no individual page | Optional entity-owned media only if breed imagery is a real feature | Finder page owns SEO initially | Reference lifecycle/provenance; health claims governed clinically |
| `BehaviorReference` | Structured behavior/training topic | Stable behavior key, species, category | Tools/Reference | Topic identity, species, category, advice, reviewed escalation copy | Optional governed `ClinicalContent` | Behavior tool projection initially | None initially | Tool page owns SEO initially | Code-owned now; governed editorial lifecycle if persisted |
| `VaccinationReference` | Governed vaccination education | Stable topic/species/life-stage/jurisdiction key | Clinical Reference/Tools | Topic, species, context, educational guidance, jurisdiction | `contentable` to `ClinicalContent`; sources/reviews/versions through it | Vaccination tool projection initially | None initially | Tool page owns SEO initially | Clinical approval, source, version, publication, review due, withdrawal |
| `EmergencyReference` | Governed emergency education/reference | Stable topic/species/category key | Clinical Reference/Tools | Symptoms, context, reviewed action wording, source/jurisdiction | `contentable` to `ClinicalContent` | Emergency tool projection initially | None initially | Tool page owns SEO initially | Clinical governance; safety policy fields separately controlled |
| `SymptomReference` | Structured symptom identity and educational copy | Stable symptom key | Clinical Reference/Tools | Label, species applicability, body area, educational limitation/guidance | `contentable` to `ClinicalContent` where needed; referenced by code-owned rules | Symptom tool projection initially | None initially | Tool page owns SEO initially | Governed educational publication if persisted; rules remain code-owned |
| `ClinicalContent` | Reusable governance envelope | Stable `content_key` plus polymorphic contentable | Clinical governance | Status, publication, risk, jurisdiction, version, review due, withdrawal, conflicts | Belongs to one concrete contentable; sources, reviews, versions | No public page identity by itself | No media ownership | No generic SEO ownership | Existing workflow/gate is authoritative |
| `ClinicalSource` | Source provenance and conflict record | Stable source record and reference/version | Clinical governance | Organization, reference, jurisdiction, species, topic, evidence, status, conflict | Many-to-many with ClinicalContent; review references | None | None | None | Active/conflicted source lifecycle |
| `ClinicalReview` | Version-specific review history | Stable review record | Clinical governance | Reviewer, credential, decision, source/version, notes, next review | Belongs to ClinicalContent | None | None | None | Immutable history of approval decisions |
| `ClinicalContentVersion` | Snapshot of governed content | Content + version | Clinical governance | Snapshot, reason, creators/reviewers, approval timestamp | Belongs to ClinicalContent | None | None | None | Reconstructable version history |

## N. Relationship matrix

| Entity A | Relationship | Entity B | Cardinality | Reason |
| --- | --- | --- | --- | --- |
| `Service` | owns canonical service context | `ServicePage` | 1:M | One service family has an overview and multiple independently addressable pages. |
| `Service` | offers operational contexts | `ServiceOffering` | 1:M | Only offerings with distinct booking, quote, pricing, intake, or operational meaning become records. |
| `ServiceOffering` | is represented by | `ServicePage` | 0:M | An offering may have a primary page and potentially other contextual pages; page identity remains separate. |
| `ServicePage` | optionally renders | `RelocationChecklist` | 0:1 for the current relocation checklist page | The page owns URL/SEO; the checklist owns ordered content. |
| `ServicePage` | may project | `Faq` | Current category projection; no pivot now | The current application uses category/subcategory and can reuse one FAQ across pages. |
| `ServicePage` | owns page media | Spatie Media Library `Media` | 1:M polymorphic | Hero, card, process, and page-gallery assets need page ownership and ordering. |
| `RelocationChecklist` | owns ordered sections/phases | `ChecklistSection` | 1:M | Sections are edited and versioned with their checklist. |
| `NewPetChecklist` | owns ordered sections | `ChecklistSection` | 1:M within its own bounded aggregate | Shared implementation shape does not make the roots one domain. |
| `ChecklistSection` | owns ordered items | `ChecklistItem` | 1:M | Item order and ownership are local to one checklist section. |
| `BreedReference` | has governed claims | `ClinicalContent` | 0:1 or a deliberately scoped governed relationship | Health considerations need source/review/version without making the whole breed a clinical article. |
| `BehaviorReference` | has governed advice where needed | `ClinicalContent` | 0:1 | Not all behavior identity is clinical, but escalation/health claims may be. |
| `VaccinationReference` | is governed by | `ClinicalContent` | 1:1 when persisted | Vaccination guidance needs source, jurisdiction, version, review, and publication gates. |
| `EmergencyReference` | is governed by | `ClinicalContent` | 1:1 when persisted | Emergency education has clinical and safety significance. |
| `SymptomReference` | has governed educational content | `ClinicalContent` | 0:1 or 1:1 for persisted governed entries | Copy and limitations may require review; decision rules remain separate. |
| `ClinicalContent` | cites | `ClinicalSource` | M:M | One governed record may require multiple sources and one source may support many records. |
| `ClinicalContent` | has review history | `ClinicalReview` | 1:M | Approval is version-specific and must remain auditable. |
| `ClinicalContent` | has snapshots | `ClinicalContentVersion` | 1:M | Published/reviewed content must be reconstructable. |
| `ClinicalToolReview` | reviews | tool catalogue key | 1:1 by `tool_key` | Whole-tool review is distinct from item-level governed reference content. |
| `Faq` | is projected into | public pages | M:M conceptually, category-based in current implementation | Explicit assignment is not justified until category/subcategory cannot express the editorial requirement. |
| `SearchDocument` | indexes | source entity | derived 1:M projection | Search is not content ownership and must not become the source of truth. |

## O. Existing-model reuse

The following existing boundaries remain authoritative and should be reused:

| Existing model/system | Reuse decision |
| --- | --- |
| `Faq` | Reuse for FAQ records, publication, category/subcategory, ordering, and current page projections. Do not add a page pivot now. |
| `Testimonial` | Reuse moderation, consent, verification, publication, service classification, and testimonial media. Do not duplicate service stories inside service pages. |
| `GalleryItem` | Reuse gallery ownership, publication, alt text, ordering, and media. A page may project gallery content intentionally without taking ownership. |
| `JobOpening` | Reuse vacancy identity and lifecycle. It is not a generic careers-page content record. |
| `Guide` | Reuse article identity, slug, slug history, publication, SEO, sitemap, media, and optional clinical governance. |
| `KnowledgeArticle` | Reuse knowledge-base identity, slug history, publication, SEO, sitemap, media, and optional clinical governance. |
| `Product` | Reuse product identity, slug, publication, SEO, sitemap/search behavior, price, availability, and media. It is not a service offering. |
| `BusinessProfile` / `BusinessHour` | Reuse business facts and schedules. Do not put those facts into service pages as duplicate authority. |
| `Medication` / `MedicationFormulation` / `MedicationJurisdiction` | Reuse existing structured medication and dose-display boundaries. Do not duplicate medication records in tool content. |
| `ClinicalContent` | Reuse as the clinical governance envelope for concrete future reference records and governed claims. |
| `ClinicalSource` | Reuse provenance, jurisdiction, evidence, source-version, and conflict handling. |
| `ClinicalReview` | Reuse reviewer identity, credentials, decision, version linkage, and review timing. |
| `ClinicalContentVersion` | Reuse governed snapshots and approval history. |
| `ClinicalToolReview` | Reuse only for whole-tool clinical review; do not use it as item-level content. |
| Spatie Media Library | Reuse model-associated media, collections, conversions, ordering, and Filament integration. |
| `SearchDocument` | Reuse as a derived search projection for future pages/reference entities if they become searchable. It never owns source content. |
| `PublicUrlCatalog` and sitemap infrastructure | Reuse for explicit public URL and sitemap policy. Adding a URL requires a product/page decision, not merely a new database row. |
| `BookingRequest` / `ContactEnquiry` | Reuse operational request records and context fields. Future service/offering identifiers may be referenced without making requests the service catalogue. |
| `config/waggies_pricing.php` | Keep as pricing authority. A future entity may reference pricing keys but may not replace the configuration boundary without a separate product decision. |

## P. Explicitly rejected models

The following models are rejected under the current evidence.

### Generic `Page`

Rejected as a universal table. The existing application already has distinct public resource boundaries (`Guide`, `KnowledgeArticle`, `Product`, `Faq`, tool routes, and service pages). A generic page would erase meaningful ownership and create conditional content rules. `ServicePage` is justified because it has service-specific page responsibility, not because every route needs a page table.

### Generic `CMSSection`, `Feature`, `Benefit`, `Bullet`, `CTA`, `Paragraph`, or `Card`

Rejected as standalone tables. These are embedded presentation structures in the current service and relocation content. They do not presently have independent identity, querying, reuse, lifecycle, or governance.

### Generic `ToolContent`

Rejected. Tool identity/configuration, educational copy, structured reference records, and safety behavior have different owners and lifecycles. Combining them would make clinical decision logic look like ordinary CMS data.

### Generic `ServicePrice`

Rejected under the current architecture. Pricing amounts, tiers, quote/estimate modes, distance bands, surcharges, aliases, and calculator formulas participate in application-owned calculations and tests. Service pages and offerings may reference pricing keys, but pricing authority remains in version-controlled configuration.

### `ServicePage` as the only service model

Rejected. Booking/contact context, pricing keys, offering semantics, future reporting, and operational identity need a canonical service/offering vocabulary independent of individual page content.

### `Service` as the only service model

Rejected. One service has multiple independently managed URLs with distinct copy, media, SEO, FAQs, and publication needs. Putting all page content on `Service` would make page identity ambiguous.

### One generic `Checklist` root for both checklists

Rejected. The current relocation and new-pet checklists have different bounded owners, audience, page relationship, and interaction semantics. They may share implementation primitives without sharing one aggregate root.

### Globally reusable checklist item table

Rejected. Current items are owned by one checklist and have local meaning/order. Global reuse would add identity and synchronization problems without evidence of independent reuse.

### Individual breed pages now

Rejected as a current product surface. The current finder has stable record IDs but no detail route, per-record SEO contract, sitemap expansion, search projection, or internal-linking requirement.

### `SymptomRule` or `EmergencyRule` ordinary CMS tables

Rejected. Symptom and emergency decisions affect safety behavior. They require a separately designed, clinically owned, version-controlled application-policy boundary if they ever leave code ownership; ordinary editable CMS fields are not appropriate.

### Duplicate article model for all reference content

Rejected. Guides, knowledge articles, breeds, behaviors, vaccination references, emergencies, and symptoms may all contain prose, but their identities, fields, queries, routes, and governance differ.

### FAQ page pivot now

Rejected for the current product. Category/subcategory already supports the observed page projections, shared FAQs, and species/subtype filtering. A pivot becomes justified only when arbitrary selective page assignment is required.

## Q. Implementation boundaries

### Eventual implementation may touch

When implementation is separately authorized, it may introduce only the focused boundaries decided here:

- `Service`, `ServiceOffering`, and `ServicePage` for the service domain;
- bounded relocation/new-pet checklist aggregates and their owned ordered children when editorial persistence is justified;
- `BreedReference`;
- `BehaviorReference` when editorial growth justifies leaving code ownership;
- governed `VaccinationReference`, `EmergencyReference`, and `SymptomReference` records when source/review ownership is established;
- relationships from governed concrete records to the existing clinical governance models;
- adapters/projections that preserve existing routes, canonical URLs, pricing keys, booking/contact semantics, search behavior, sitemap policy, and public media URLs;
- page-owned Spatie Media Library collections where page-specific media becomes database-backed.

### Eventual implementation must not touch without a separate decision

- pricing authority, rates, tiers, formulas, distance rules, or quote semantics;
- booking and contact form meaning merely to fit a new content model;
- symptom red-flag, severity, escalation, prohibited-action, or emergency decision logic as ordinary CMS data;
- browser-local checklist completion state;
- existing `Guide`, `KnowledgeArticle`, `Product`, `Faq`, `Testimonial`, `GalleryItem`, `JobOpening`, business, or medication ownership boundaries;
- public route names, canonical URL behavior, or legacy asset redirects without an explicit migration plan;
- a generic `Page`/CMS framework;
- an explicit FAQ assignment pivot unless a selective-placement requirement is demonstrated;
- individual breed URLs, per-breed SEO, or breed sitemap entries unless product requirements change;
- packages or generic wrappers without a new capability requirement.

### Migration constraints

Any later migration must be incremental and preserve the current public contract:

1. define stable service, offering, page, reference, and checklist keys before moving content;
2. keep current route names and canonical URLs stable;
3. preserve pricing references rather than copying pricing into CMS records;
4. preserve page-specific media ownership and public asset paths;
5. populate search and sitemap projections only from records that are independently eligible;
6. attach clinical governance before publishing governed reference claims;
7. keep code-owned safety rules and completion state outside editorial content;
8. migrate one bounded family at a time with regression coverage and an explicit rollback path.

### Residual decisions that code cannot resolve

The architecture is resolved, but two implementation inputs are genuinely external to the repository:

1. **Editorial ownership and timing:** the code does not identify the staff role, cadence, or approval process that will edit service-page marketing content. This affects when the controller-owned arrays should be migrated, not whether `Service` and `ServicePage` are distinct concepts.
2. **Clinical source/jurisdiction ownership:** the repository contains the governance mechanism but not the approved source catalogue, jurisdiction policy, or named clinical owner for each future reference family. The concrete records and their governance relationship are decided; source assignment and publication readiness require an external clinical/product decision.

These are not reasons to create alternative architectures. They are prerequisites for a later implementation and content-governance rollout.

## Final canonical decisions

```text
Service and ServicePage are both required.

Boarding species and relocation import/export/transport have operational offering identity
as well as independent public page identity.

Relocation and new-pet checklists are separate bounded aggregates with shared primitives,
not one generic CMS checklist root and not persisted browser state.

Breed records need stable data identity but no individual public URLs yet.

Breed, behavior, vaccination, emergency, and symptom content are concrete reference
domains; ClinicalContent governs claims and publication, while safety decisions remain
code-owned.

FAQ category/subcategory projection is sufficient until selective page assignment is a
real editorial requirement.

Public page identity owns route, canonical, SEO, publication, and page-specific media.

Pricing, safety policy, search projections, and browser completion state remain outside
the CMS/content model.
```
