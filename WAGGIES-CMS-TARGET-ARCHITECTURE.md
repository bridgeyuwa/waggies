# Waggies CMS Target Architecture

**Status:** Verified implementation contract for the current CMS foundation
**Date:** 22 September 2026
**Authority:** The current standalone Laravel repository is authoritative. `WAGGIES-ARCHITECTURE-AUDIT.md` is architectural evidence. `WAGGIES-PROTOTYPE-DISCOVERY.md` is product/design discovery only.

## 1. Executive Summary

Waggies is currently a public, service-led pet-care product for pet owners in Abuja. Its implemented centre of gravity is:

1. service discovery and education;
2. contextual quote, appointment, transport, product, and general enquiry capture;
3. transparent, server-owned pricing and estimate tools;
4. guides, knowledge-base answers, FAQs, and browser-only calculators/checklists;
5. trust content, moderated testimonial submission, newsletter subscription, and a small illustrative shop/cart experience.

It is not currently an operational booking platform, customer-account platform, loyalty ledger, live tracking system, or checkout/fulfilment system. Prototype labels such as “Book”, “Track”, “Redeem”, and “Download” are not evidence that those systems exist.

The verified architecture is a deliberately small hybrid architecture.

- Keep Services developer-controlled and config-backed for the current product scale, rendered through known fixed Blade page families.
- Use persisted editorial content models for Guides and Knowledge Articles; keep FAQs, legal documents, and selected company/support content conditional on a demonstrated editing need.
- Keep page composition in Blade and application code. Editors may manage approved fields and relationships, but they do not assemble arbitrary layouts.
- Keep pricing inputs in version-controlled configuration and pricing calculation, enquiry contracts, calculators, cart behaviour, future booking, accounts, loyalty, notifications, and integrations in application/runtime boundaries.
- Preserve the existing `serveable != published != indexable != sitemap-listed` boundary. `PublicUrlCatalog` is the publication inventory for persisted editorial routes.

The implemented CMS is therefore not a universal page builder or a Service CMS. It is a Filament administration surface over the justified Guide and Knowledge Article resources, with publication, SEO, owned-media, rich-content, and slug-history controls where they are actually needed.

## 2. Current Product Reality

### Product, users, and journeys

The current public user is primarily a pet owner researching care in Abuja, deciding whether Waggies fits their needs, and contacting Waggies for a response. The staff-facing surface is the Filament `/admin` foundation, with application-owned Guide and Knowledge Article resources.

The important implemented journeys are:

| Journey | Current evidence | Product meaning |
| --- | --- | --- |
| Discover a service | `/services`, boarding/species routes, grooming, training, vet care, relocation | Core product discovery journey. |
| Understand a service | Service detail views, packages, inclusions, standards, FAQs, preparation-like copy | Informational and decision-support content. |
| Estimate or understand price | `/services/pricing`, `/tools/cost-calculator`, contact pricing payload | Interactive application behaviour with pricing authority. |
| Ask for help | `/contact` with contextual intents for service, booking/appointment language, transport, relocation, product, tools, and general enquiries | Current primary conversion. “Booking” currently means a request path, not confirmed availability. |
| Learn and self-serve | Guides, knowledge base, FAQ, tools, checklists, search | Editorial/support journey. |
| Evaluate trust | About, standards, testimonials, gallery, careers, partnerships, team-like content | Proof and credibility journey; claims require verification. |
| Subscribe or submit proof | Newsletter persistence and testimonial submission with pending moderation | The current persisted public-submission boundary. |
| Browse products | `/shop`, product detail, browser cart, cart-aware request form | Catalogue and enquiry/cart intent, not authoritative commerce. |

### Informational, operational, commerce, and tools

**Informational:** service education, about/company copy, guides, knowledge articles, FAQs, legal text, loyalty explanation, careers, partnerships, gallery captions, and trust copy.

**Operational:** the current application accepts enquiries and stores newsletter/testimonial submissions. It does not allocate capacity, confirm a booking, schedule a pickup, manage a customer case, or send a transactional notification workflow.

**Commerce:** the current shop is a database-backed `Product` catalogue managed through the Filament Product resource. Cart state is browser-local and can be carried into an enquiry request. There is no persisted order, checkout, payment, inventory, fulfilment, or customer account boundary.

**Interactive tools:** cost/pricing estimation, transport estimation, symptom guidance, vaccination/parasite/emergency references, medication guidance, pet-age/nutrition calculators, breed/behaviour finders, and browser-local checklists. These are application behaviour and safety-sensitive content, not generic CMS pages.

### Structured business data already present in configuration

The configuration files already express meaningful structure, even though they are not persisted domain models:

- service families, boarding species, service variants, packages, inclusions, and pricing keys;
- FAQ categories and subcategories;
- guide and knowledge-base items with slugs, body content, categories, and related data;
- relocation subtypes and checklist content;
- products, categories, features, images, and prices;
- tool catalogues, reference data, schedules, and guidance;
- about pages, gallery, testimonials/proof presentation, careers, partnerships, and loyalty copy;
- site navigation, contact details, service comparisons, and publication metadata assembled by controllers.

This is evidence for a future typed model where relationships and independent editing are validated. It is not evidence that every config array should immediately become a table or CMS resource.

### Content-like things that are actually behaviour

The following may look like editable page content but must remain application-owned:

- pricing formulas, tier selection, transport estimates, and quote-required rules;
- contact intent resolution and field schemas in `ContactController`;
- browser cart state, request drafts, and calculator state;
- search endpoint behaviour and result ranking;
- tool calculations and medical-safety escalation logic;
- route shape, canonical URL generation, sitemap inclusion logic, and noindex rules;
- future availability, booking, order, loyalty-ledger, notification, and tracking workflows.

## 3. Product Capability Map

| Capability | Classification | Current reality | Target decision |
| --- | --- | --- | --- |
| Services catalogue | KEEP | Implemented as route families plus config-backed cards and comparison data. | Keep Services developer-controlled/config-backed. Defer persisted Service entities until recurring editing, staff ownership, meaningful querying, or operational identity needs are demonstrated. |
| Service variants/subservices | KEEP | Boarding species and relocation subtypes are meaningful route and content boundaries. | Preserve the existing config-backed variants and fixed page families. Do not introduce a Service CMS merely because the data is structured. |
| Packages and inclusions | KEEP | Present in service configuration and Blade compositions; some packages have numeric rates and some are quote-only. | Keep package and inclusion data in configuration until independent editing or relationship needs justify persistence. |
| Pricing | KEEP / ADAPT | Server-owned config is serialized to browser tools; the audit records overlapping authorities and drift risk. | Keep one canonical pricing authority. If rates later require non-deployment editing, expose narrowly typed pricing records with audit/version controls; never let generic CMS copy become pricing authority. |
| Contact/enquiry | KEEP | Contextual `/contact` request schema is the strongest current conversion system. | Keep as application-owned. CMS may own labels, explanatory copy, and curated service links, not request validation or acceptance. |
| Relocation | KEEP / ADAPT | Landing, import, export, transport, checklist, and enquiry routes exist. | Keep the connected config-backed service journey. Do not build tracking, scheduling, or Service persistence from prototype affordances. |
| Guides | KEEP / ADAPT | Persisted listing/detail domain with article processing, TOC, SEO, and related tools. | Keep as a distinct editorial type with the existing Guide CMS and publication boundary. |
| Knowledge base | KEEP / ADAPT | Separate persisted searchable/paginated support content. | Preserve the distinction from Guides and the existing Knowledge Article CMS/publication rules. |
| FAQs | KEEP / ADAPT | Persisted, ordered, published FAQ entries are reused on service pages and the FAQ page. | Keep the typed FAQ resource and publication state; do not make FAQ text a universal block. |
| Testimonials | KEEP | Public submissions persist as pending/approved/rejected/archived records; public display is approval-gated. | Keep submission and moderation boundary. A future CMS may manage approved display metadata, provenance, service association, and consent without turning testimonials into unmoderated copy. |
| Gallery/media | ADAPT | Gallery and lightbox exist; current media includes remote URLs and native testimonial upload storage. | Keep presentation. Introduce an owned media asset policy before migrating assets. Model alt text, caption, consent, licence, and usage context explicitly. |
| Calculators/tools | KEEP / ADAPT | Multiple route-backed tools and Alpine modules are implemented. | Keep algorithms and safety rules in code. CMS may manage reviewed explanatory text/reference rows only when a clear editor and review need exists. |
| Shop catalogue | KEEP / ADAPT | Database-backed `Product` records with published slug detail routes and a client-side cart. | Keep the catalogue boundary without expanding it into orders, stock, payment, or fulfilment. |
| Cart | KEEP / DEFER | Browser-local cart supports an enquiry/order-intent path; no order is created. | Keep the current interaction. Do not call it checkout or build order persistence without payment/fulfilment requirements. |
| Loyalty | KEEP / DEFER | Public explanatory loyalty page is config-backed; no account, ledger, or redemption runtime exists. | Keep the page as informational content. Defer all account/points/redemption systems. |
| Careers | KEEP / ADAPT | Current careers page is informational. | Keep typed page content. Defer application intake and document workflow until recruitment ownership is validated. |
| Partnerships | KEEP / ADAPT | Current partnership page is informational; prototype directory/logos are unverified. | Keep proposition content. Do not build a directory until there is a maintained partnership strategy and consented data. |
| Company/about | KEEP / ADAPT | About and child pages are composed from controller/config data and include many claims. | Keep fixed page compositions with editable verified fields where useful. Require claim ownership and expiry for credentials, statistics, people, and guarantees. |
| Legal | KEEP / ADAPT | Privacy, terms, and cookies are route-backed long-form pages. | Use typed legal documents with revision/publication history if non-developer editing is required; retain explicit review/publish authority. |
| Search | KEEP / ADAPT | Search is a JSON endpoint covering tools, selected pages/services, published Guide/Knowledge Article/Product/FAQ records. | Keep the contract and noindex boundary. Do not add a second search stack or persisted Service search source. |
| SEO/publication | KEEP / ADAPT | `Controller::setPageHead`, `PublicUrlCatalog`, sitemap, robots, canonical, robots metadata, and tests are established. | Preserve the boundary exactly and extend it through published typed records. |
| Booking | DEFER | “Booking” exists as contact intent language, not availability or reservation. | Validate the business and operational model before designing a Booking domain. |
| Scheduling/availability | DEFER | No authoritative schedule or capacity system exists. | Do not model in CMS. If needed, build an application/integration domain separately. |
| Tracking/live updates | DEFER / REJECT FOR NOW | Prototype-only signals include GPS, webcam, and updates. | Require an operational owner, data source, support commitment, and privacy model first. |
| Customer accounts | DEFER | No customer identity or dashboard exists. | Do not create accounts to support a prototype loyalty or booking concept. |
| Notifications | DEFER | Current flows do not establish transactional notification requirements. | Add only as part of a validated operational workflow, not as CMS publishing infrastructure. |

## 4. KEEP / ADAPT / REPLACE / NEW / DEFER / REJECT

### KEEP

- Blade-first public rendering and Alpine-first local browser interaction.
- The current service URL hierarchy, including boarding species and relocation variants.
- Contextual enquiry/contact as the primary conversion boundary.
- Server-authoritative pricing and browser serialization of server-owned values.
- Separate Guides and Knowledge Base domains.
- Existing FAQ, testimonial moderation, gallery, shop/cart, tools, legal, careers, partnerships, and loyalty page concepts.
- Semantic Waggies Blade components, design tokens, accessibility foundations, and focused tests.
- Explicit SEO/publication inventory through `PublicUrlCatalog`.

### ADAPT

- Service facts, variants, packages, preparation information, and selected company content remain config-backed while developer ownership is appropriate.
- Relocation content as a deliberately connected journey of config-backed service variants, preparation requirements, FAQs, guides, tools, and enquiry CTAs.
- Proof content with verified provenance, consent, service association, media metadata, and claim expiry.
- Search indexing for the persisted Guide and Knowledge Article content, with future editorial types considered separately.
- Shop catalogue only if catalogue size, stock, order, or fulfilment needs become real.
- Publication metadata, redirects, preview, and workflow around the existing URL contract.

### REPLACE

- Duplicated commercial values with one canonical pricing source. This is a data-authority replacement, not a CMS page-builder exercise.
- Any future third “blog” concept with the existing Guides/Knowledge Base distinction unless new evidence shows a genuinely different content job.
- Prototype or placeholder claims with verified business facts; do not migrate them verbatim.

### NEW

- Typed publication metadata and revision records for content that genuinely becomes CMS-managed.
- Curated Service relationships only when a future requirement justifies a persisted Service identity; do not add a generic relationship layer now.
- Claim/provenance/consent metadata for testimonials, credentials, people, partners, statistics, and media where those claims are approved for publication.
- A small, explicit editorial workflow and Filament resources for validated content families.
- Redirect records or an equivalent URL-history mechanism when persisted content introduces editable slugs.

### DEFER

- Service persistence and Service CMS/CRUD/publication/visibility workflows until a real requirement exists, such as recurring non-developer editing, multiple staff maintainers, independently maintained variants/packages, meaningful cross-domain querying, operational integration requiring persistent Service identity, or demonstrated admin-managed lifecycle needs.
- Database-backed products, providers, facilities, and locations until their editing and relationship needs are validated.
- Downloadable/printable resources until specific documents have stable owners and a real customer use case.
- Richer knowledge filters until volume and search behaviour justify taxonomy.
- Careers applications, partnership intake, booking, scheduling, accounts, loyalty, notifications, checkout, orders, inventory, fulfilment, and tracking.

### REJECT FOR THIS TARGET

- A universal `Page -> blocks[]` page builder.
- Arbitrary content blocks as the default representation for service, article, legal, or tool pages.
- Reconstructing the historical Stitch layouts, copy, static prices, or unverified claims.
- A duplicate blog/content system.
- Generic CMS ownership of calculations, availability, cart/order state, or business rules.

## 5. CMS vs Domain vs Application vs Integration Boundary

### A. Editorial / CMS content

CMS ownership is appropriate for content that has an independent editor, meaningful publication lifecycle, structured presentation requirements, and a reason to change outside a deployment. Initial candidates are:

- Guides and knowledge articles;
- FAQs;
- service education and preparation guidance;
- selected legal documents and long-form policy content;
- approved testimonial display records and moderation metadata;
- selected company/about copy, when claims are verified;
- media captions, alt text, consent/licence metadata, and downloadable-document metadata;
- SEO fields and publication controls for CMS-managed records.

CMS content should not own the surrounding page layout or application behaviour. A Guide has a known Blade article composition; an FAQ has a known accordion/list composition; a Service has a known service-detail composition.

### B. Structured domain data

Structured domain data can exist in configuration without becoming persisted CMS data. The current Service catalogue uses that simpler boundary:

- Service families and fixed route/page compositions;
- service variants, packages, inclusions, eligibility, and preparation requirements in configuration;
- `Provider`/team profile and verified credentials, if staff profiles are a real product asset;
- `Facility`/`Location` and service areas, if multiple locations or service coverage become real;
- `Product` and `ProductCategory`, only when commerce is more than a static catalogue;
- approved `Testimonial` display data, distinct from public submissions where necessary;
- document/resource records when downloads become maintained product assets.

If a future requirement gives a Service persistent identity, its structured facts should retain domain ownership rather than being buried in arbitrary rich text. That is a deferred possibility, not the current architecture.

### C. Application / runtime logic

Application code owns:

- pricing authority, formulas, quote-required decisions, and estimate serialization;
- contact intent resolution, validation, acceptance, and request references;
- calculators, symptom escalation, and other tool algorithms;
- cart state until a real order boundary is approved;
- search behaviour, filtering semantics, and indexing;
- publication eligibility and `PublicUrlCatalog` assembly;
- booking, availability, scheduling, payment, orders, inventory, fulfilment, accounts, loyalty ledgers, and notifications if those products are later approved.

CMS editors may enter safe inputs to an application-owned system only after the input contract is explicit. For example, a typed pricing rate may be editable in an admin resource in a later phase; the calculation engine and validation remain application-owned.

### D. External integrations

Possible future external authorities include payment/commerce, scheduling/calendar, transport or relocation providers, email/WhatsApp messaging, maps, analytics, storage/CDN, or a newsletter provider. Waggies should own:

- the customer-facing canonical content and public URL;
- the Waggies service/product mapping to an external identifier;
- consent, request, and integration status needed for customer support;
- the publication and display policy.

The external system should own the authoritative record for the capability it actually operates, such as payment settlement, appointment availability, inventory, or delivery status. Do not mirror an external system into CMS content merely to make it visible.

## 6. CMS Necessity Matrix

| Content / capability | CMS needed? | Why | Preferred ownership |
| --- | --- | --- | --- |
| Home page composition | Selective | Layout and hierarchy are stable; some campaign/claim copy may change. | Blade composition with narrowly typed site/home fields only if an editor and cadence exist. |
| Service catalogue and detail | No for current phase | The catalogue is small, curated, developer-controlled, and already rendered by known Blade page families. | Existing Service configuration and application/controllers; persisted Service management is deferred. |
| Service packages/inclusions | No for current phase | Structured configuration does not by itself justify database persistence or Filament CRUD. | Existing configuration and fixed compositions, with pricing separated from descriptive inclusions. |
| Pricing rates and formulas | No for current phase | Commercial correctness and calculator behaviour require one authority, validation, tests, and audit. | Application-owned canonical pricing; revisit narrow admin editing only with business ownership. |
| Contact/enquiry forms | No | Validation, intent schemas, pricing context, and acceptance are application behaviour. | Controllers/application services; CMS owns explanatory copy only. |
| Relocation preparation | No for current phase | Existing preparation copy is part of the curated Service page compositions. | Service configuration and application-owned rendering; revisit only with a real editor/owner. |
| Guides | Yes | Independent publication, SEO, revisions, related content, and long-form editing justify the current persisted editorial resource. | Guide editorial resource in Filament. |
| Knowledge articles | Yes | Searchable support content benefits from structured metadata and publication control. | KnowledgeArticle resource; retain distinct content purpose from Guides. |
| FAQs | Selective/yes | Ordering, category/service association, and publication state are useful. | Typed FAQ entries; no arbitrary FAQ blocks. |
| Testimonials | Yes for moderation, not necessarily authoring | Public submissions already need moderation and approved-only publication. | Application submission model plus Filament moderation; optional approved display metadata. |
| Gallery/facility media | Selective | Media metadata, consent, alt text, captions, and usage context need ownership. | Waggies media records plus owned storage; page composition remains fixed. |
| Team/provider profiles | Only if verified profiles are maintained | Profiles, credentials, active status, and service association are structured trust data. | Typed Provider resource with verification metadata. |
| Facilities/locations/hours | Only when facts are operationally maintained | Reuse across contact/about/service/SEO requires structured ownership. | Typed Location/Facility data; schedule/availability logic remains application/integration-owned. |
| Legal pages | Selective/yes | Revision history, approval, and publication dates may matter. | Typed LegalDocument resource with explicit review/publish. |
| Shop product catalogue | Yes, bounded | The catalogue is a typed Product resource with published public detail routes and no order boundary. | Keep Product as catalogue authority; defer stock, order, payment, fulfilment, and customer accounts. |
| Cart/order/checkout | No CMS | Cart, payment, inventory, fulfilment, and order state are runtime/commerce concerns. | Application plus validated external commerce/payment integrations. |
| Tools and calculators | No for behaviour | Algorithms, safety rules, and state are code-owned. | Application modules; CMS only for reviewed reference copy/data if needed. |
| Navigation and route structure | No | Navigation and URLs are application contracts with tests and SEO implications. | Developer-controlled config/routes; allow only explicitly approved link-label overrides. |
| SEO metadata | Yes for CMS records | Editors need title/description/canonical/robots controls, but publication rules must be constrained. | Shared publication contract owned by application, editable fields in typed resources. |
| Sitemap membership | No direct editor switch without policy | Sitemap inclusion is a publication decision with safety defaults. | Application publication policy; resource metadata may provide an explicit opt-out. |

## 7. Target Content Model

### Recommended shape

The target should use typed records and fixed compositions, with a hybrid boundary between editorial content and structured domain data.

#### Editorial types

The implemented editorial types are limited to:

- `Guide`: in-depth editorial education with author/date/reading-time-like metadata, TOC-capable body, related services/tools, and SEO fields.
- `KnowledgeArticle`: answer-oriented support content with topics, pet/service applicability, and related help paths.
The following remain deferred editorial candidates and were not implemented in this batch:

- `FaqEntry`: short answer, category/service association, ordering, and publication state.
- `LegalDocument`: a named policy/document with explicit revision and approval metadata.
- `ResourceDocument`: only when a real, maintained downloadable/printable document exists.

Selected company/about content can use typed page records or remain config-backed until editing value is demonstrated. Do not create a universal `Page` model merely to hold every route.

#### Deferred structured domain candidates

The future domain vocabulary may include:

- `Service`;
- `ServiceVariant` for meaningful variants such as boarding species or relocation type;
- `ServicePackage` and ordered inclusions;
- `PreparationRequirement` or an equivalent ordered, typed preparation record;
- `Provider` and verified credentials, only when profiles are real and maintained;
- `Facility`/`Location`, only when locations/service areas are more than one stable site fact;
- `Product` and `ProductCategory`, only when the shop becomes a real catalogue/commerce system;
- moderated testimonial submission/display data;
- media/resource records where ownership and metadata require them.

These deferred domain candidates are not current tables or Filament resources. They should be introduced only when an independent editor, relationship, query, or operational need is evidenced.

#### Shared publication contract

Typed publishable records should share a constrained publication contract, whether implemented through a small reusable abstraction or repeated typed fields:

- lifecycle status: draft, review, published, archived;
- `published_at`, optional future publication time, and optional expiry;
- slug and URL identity;
- title/description metadata;
- canonical override only when justified;
- robots/indexability decision;
- sitemap eligibility;
- author/editor attribution where meaningful;
- revision/approval timestamps and attribution.

This contract must not become a universal content table with polymorphic body blocks. The content type still owns its validation and composition.

### Content body strategy

Use a constrained rich-text/structured-body representation appropriate to each content type, rendered by a known Blade composition. For articles, the existing body/TOC approach can be retained or replaced by a safer typed editor at implementation time. For service facts, packages, FAQs, and preparation steps, use fields and relationships rather than HTML as the source of truth.

The system should not allow an editor to insert arbitrary components, forms, calculators, or business logic into a body field. A content record can reference a known tool or service CTA through a typed relationship or approved CTA field.

## 8. Page Composition Strategy

| Page family | Target composition | Decision |
| --- | --- | --- |
| Home | Statically composed Blade with selected typed/site fields and curated relationships | Preserve the current designed hierarchy. Do not make home an arbitrary block canvas. |
| Service listing | Config-backed route families rendered by the existing fixed catalogue/comparison composition | Keep the current developer-controlled hierarchy and defer Service persistence. |
| Service detail | Fixed service-family compositions using existing configuration and application context | Keep specialized boarding and relocation compositions where their responsibilities differ from ordinary service detail. |
| Boarding species | Config-backed service variants rendered by the current species composition | Preserve dedicated dog/cat/exotic routes and semantics. |
| Relocation pages | Config-backed relocation variants plus curated guides, FAQs, checklist/tool references, and enquiry CTA | Treat the family as a connected journey, not as generic pages or a Service CMS. |
| Pricing | Application-generated page with authoritative rate data and calculator state | CMS may supply explanation, not commercial calculation authority. |
| Guide listing/article | CMS-managed Guide records in fixed index/detail templates | Add typed filters and relationships only when needed. |
| Knowledge-base listing/article | CMS-managed KnowledgeArticle records in separate support templates | Preserve support/search semantics; do not merge with Guides for implementation convenience. |
| FAQ | CMS-managed ordered FAQ entries in fixed page/service compositions | Use category/service associations and publication state, not page blocks. |
| Testimonials | Fixed catalogue/grid and submission/moderation flow | Approved records only are public; service filters are typed values. |
| Gallery | Fixed gallery/lightbox composition over owned, approved media records | Media metadata and consent are managed; layout is not arbitrary. |
| Shop listing/detail | Config-backed now; later structured Product records with fixed catalogue templates | A real catalogue may gain filters/categories, but not until scale or commerce demands it. |
| About/company | Fixed page families with selective typed fields and verified relationships | Keep company pages understandable and claim-governed. |
| Careers | Fixed informational page; later typed openings only if recruiting is owned | Do not make applications a CMS feature by implication. |
| Partnerships | Fixed proposition page; later relationship records only with a strategy | No speculative directory. |
| Legal | Fixed long-form legal composition over versioned LegalDocument content | Explicit review and publication; no arbitrary marketing layout. |
| Tools/calculators | Dynamically generated from named application modules and reviewed reference data | Tool identity, algorithm, and safety boundary remain code-owned. |

## 9. Relationship Model

Relationships should be explicit where they help editors or runtime queries. They should not be generalized just because several things can be called “related content.”

| Relationship | Recommended representation | Reason |
| --- | --- | --- |
| Service → ServiceVariant | Existing configuration and fixed route/page families | Variants have meaningful presentation, but the current catalogue does not need persisted identity. |
| Service/Variant → ServicePackage | Existing configuration arrays | Packages and inclusions are structured and comparable without requiring database persistence. |
| Service/Variant → PreparationRequirement | Existing configuration and fixed composition data | Preparation needs labels and ordering, but no current editor or operational owner justifies a child table. |
| Service → Guide | Curated config/route links for now | Editors do not currently maintain Service relationships; a typed relation is a future option only if Service persistence is justified. |
| Service → KnowledgeArticle | Curated config/route links for now | Support answers can be service-specific without adding a Service database relationship. |
| Service → FaqEntry | Curated config/category links for now | Reuse is valuable, but Service publication/relationship CMS is deferred. |
| Article → Service | Curated named-route/config reference | Converts education into a deliberate next action without requiring a persisted Service model. |
| Article → Tool | Named application/tool reference | A tool is runtime code; the relationship is a curated link, not embedded executable content. |
| Service → Testimonial | Curated relation using approved testimonial records | Keeps proof contextual while preserving moderation. |
| Service → Provider | Many-to-many when verified providers serve several services | Provider profiles are structured trust data, not copied text. |
| Service → Facility/Location | Relation only if facilities/locations are maintained entities | Avoid introducing a location model for one stable address. |
| RelocationVariant → Resource | Curated relation to guides, FAQs, checklist/tool, and documents | Makes the relocation journey navigable and maintainable. |
| Product → ProductCategory | Belongs-to relation when products become persisted | Categories are useful only when catalogue scale warrants them. |
| Product → Service/content | Curated relation only for verified commercial cross-sells | Do not add generic product/content links by default. |
| Content → Content | Typed, directional curated relations where a real editorial use exists | Prefer explicit `relatedGuides`, `relatedArticles`, or `relatedResources` semantics over a universal polymorphic graph. |

Do not model ordinary application logic as content relationships. Availability, price eligibility, cart totals, order status, and notifications are not editorial relations.

## 10. Media Architecture

### Ownership and storage

Waggies should own production media that it publishes: service imagery, facility images, staff/provider portraits, gallery images, approved testimonial photos, product images, and maintained downloadable documents. Remote prototype URLs and unverified stock imagery are not a production media policy.

The current native testimonial upload path is adequate for the present small submission boundary. Do not migrate it as part of CMS discovery. When CMS-managed media is actually implemented, evaluate the already-installed `spatie/laravel-medialibrary` and its current Filament integration as the generic storage/conversion layer; introduce it only if its model-associated media, conversions, and storage behaviour meet the validated requirement.

Waggies-specific media semantics must remain explicit regardless of storage library:

- alt text and decorative-vs-informative status;
- caption and context;
- owner/source and licence/permission;
- consent and publication status for people, pets, and testimonials;
- media role, such as hero, gallery, provider portrait, product image, or document;
- responsive conversion requirements and accessible document variants;
- replacement/retirement policy and whether old URLs must redirect or remain available.

Media Library, if used, is infrastructure. It does not define `Facility`, `Testimonial`, `Provider`, `Product`, or any Waggies business concept.

### Moderation

Submitted testimonial media is pending until its associated testimonial is approved. Staff/provider imagery, partner logos, credentials, statistics, and customer/pet images require consent/provenance before publication. Expiry or review dates are appropriate for claims that can become stale.

## 11. SEO / Publication Architecture

The target must preserve the existing rule:

> **serveable != published != indexable != sitemap-listed**

### Publication states

The smallest justified lifecycle is:

```text
draft -> review (optional) -> published -> archived
                         \-> rejected/returned to draft
```

Scheduled publication and expiry should be supported only where a real editorial cadence requires them. A draft or future record must not be returned by public show routes, search, canonical metadata, or the sitemap.

### `PublicUrlCatalog` boundary

`PublicUrlCatalog` should remain the explicit public inventory. It enumerates static/config-backed Service routes, persisted Guide and Knowledge Article routes, and published Product routes. Any future database content should be added only when it satisfies the application’s publishability policy:

- published status;
- publication window currently active;
- valid canonical URL;
- not explicitly `noindex` where sitemap policy excludes it;
- sitemap eligibility;
- required public relationships/data present.

Search should apply the same publication policy but may include indexable, serveable records that are intentionally not sitemap-listed. Preview routes should be authenticated or signed and must not enter public search, canonical tags, robots output, or sitemaps.

### URL and slug policy

Keep current named routes and public paths. A persisted content record may own a slug, but a slug change must create a redirect/history record or an equivalent explicit redirect policy. Unpublishing should normally return a controlled 404/noindex response unless a deliberate replacement redirect exists. Do not silently redirect every old URL to a generic page.

Canonical URLs, robots directives, Open Graph/Twitter metadata, structured data, and sitemap membership should be generated from the published page context. Editors can provide constrained metadata fields; they do not bypass the application’s publication policy.

### Structured data

Continue using the existing Laravel Head and Schema.org boundaries. Typed service, article, product, organisation, FAQ, or web-page schema should be emitted only when the underlying facts are verified and the page is public. The CMS must not allow arbitrary JSON-LD injection as a substitute for typed schema decisions.

## 12. Editorial Workflow

The smallest workflow justified by the evidence is:

- **Draft:** editable, private.
- **Review:** optional handoff for content, claim, legal, or clinical review.
- **Published:** public if the publication policy passes.
- **Archived:** retained for history, not public unless explicitly republished.

Required governance varies by content:

| Content | Minimum governance |
| --- | --- |
| Guides/knowledge articles | Author/editor attribution, revision history, publication state, SEO review. |
| FAQs/service preparation | Subject-matter review where medical, safety, relocation, or regulatory claims are involved. |
| Testimonials | Existing pending → approved/rejected/archived moderation; consent retained; approved-only public scope. |
| Provider credentials/claims | Verification owner and review/expiry date. |
| Partner logos/relationships | Consent, relationship status, and review/expiry date. |
| Legal documents | Named reviewer/approver, revision identity, publication date, and effective date. |
| Media | Alt text, provenance/licence, consent where relevant, and publication status. |

Do not build multi-stage enterprise workflow, comments, granular approvals, or complex editorial assignments until actual staffing and compliance needs require them. A small roles model can begin with authenticated admin, editor, reviewer/publisher, and administrator; permissions should follow real responsibilities rather than every model/action combination.

## 13. Filament Boundary

### Filament should own

- editing and publishing Guide, KnowledgeArticle, FaqEntry, and LegalDocument records when those types move to persistence;
- managing approved testimonial moderation and display metadata;
- maintaining verified service/domain fields once those become structured records;
- maintaining curated relationships among services, articles, FAQs, tools, testimonials, providers, and resources;
- media metadata, consent, provenance, and approved asset selection;
- constrained SEO/publication fields and preview workflows;
- narrowly scoped site/company content that has a real editor and review owner.

### Filament should not own

- pricing algorithms, transport estimates, calculator logic, symptom severity, or medical escalation rules;
- public contact validation/acceptance or request workflow;
- cart state, checkout, payment, inventory, fulfilment, booking, availability, or scheduling;
- customer accounts, loyalty ledger, redemptions, tracking, or transactional notifications;
- arbitrary page layout assembly or arbitrary executable/content blocks;
- developer-owned routes, component contracts, navigation architecture, or public interaction semantics;
- unsupported business claims merely because an editor wants to display them.

### Developer-controlled boundaries

Blade page compositions, route names/URL policy, application actions, tool algorithms, pricing calculations, publication policy, structured-data policy, and integration contracts remain code-owned. Filament resources are not automatically justified by the existence of an Eloquent model.

## 14. Durable vs Transitional Architecture

### Durable foundations

- Semantic design tokens and Waggies primitives in `resources/css` and `resources/views/components/waggies`.
- Blade-first public rendering and Alpine factories for local browser state.
- Server/browser boundary: PHP resolves authoritative context and values; JavaScript presents and interacts with them.
- Pricing authority principle and contract tests, once the duplicated sources are reconciled.
- Focused controllers that resolve page context and invoke application behaviour without a generic repository/CMS layer.
- Separate Guides, Knowledge Base, services, relocation, tools, shop, proof, and legal page families.
- `ArticleBodyProcessor` as a focused article concern unless a validated editor requires a safer replacement.
- `PublicUrlCatalog`, named routes, canonical metadata, robots handling, sitemap generation, and publication tests.
- Existing submission models and moderation boundary for newsletters/testimonials.
- Existing semantic accessibility contracts and tests, including card semantics, list semantics, forms, and public interactions.

### Likely transitional implementation

- Large config arrays for developer-controlled families that do not yet have a demonstrated editing need. Guide and Knowledge Article arrays are retained only as migration/parity snapshots.
- HTML article bodies embedded in the legacy Guide/Knowledge configuration as a migration snapshot; persisted HTML is now the runtime representation.
- Controller-owned page arrays that duplicate content across home/about/service compositions.
- Expanding the Product catalogue into stock, order, payment, or fulfilment without a separately validated commerce boundary.
- Remote prototype image URLs retained only as narrowly scoped legacy fallbacks; new CMS media is governed by Spatie Media Library.
- Hard-coded proof/testimonial copy in presentation components where approved, attributed records later become necessary.
- The current pricing boundary is durable: commercial inputs are in `config/waggies_pricing.php`, while pricing semantics/calculations remain application-owned. Do not introduce a pricing record set or generic settings editor.
- The Guide and Knowledge Article Filament resources: durable, typed CMS boundaries that do not imply resources for other product areas.

Do not refactor these transitional areas merely to make them look like the eventual CMS. Migrate them when a validated content/domain need gives the migration a clear destination.

## 15. Migration Strategy

### Stage 0 — Establish the contract (complete)

- Preserve the completed R26 pricing authority boundary: configuration owns commercial inputs and application code owns pricing semantics.
- Inventory config content, route names, published URLs, claims, media sources, and duplicated fields.
- Classify each config section as developer-owned, editor-candidate, structured-domain candidate, or runtime logic.
- Record content owners and claim verification needs before importing anything. For Guides and Knowledge Articles, the migration gate passed with parity checks against the legacy snapshot and no invented content.

### Stage 1 — Introduce the publication contract (implemented)

Guide and Knowledge Article records define status, publication window, slug, canonical, robots/indexability, sitemap eligibility, and redirect behaviour. `PublicUrlCatalog`, search, controllers, and SEO use the same explicit scopes. Every current public route is preserved.

The first implementation should be a narrow vertical slice, not a framework for every future type.

### Stage 2 — Migrate the justified editorial families (implemented)

Guides and Knowledge Base are the currently justified persisted editorial families. They have clear index/detail routes, article processing, metadata, and publication/search needs without requiring operational state. Their public rendering and publication boundaries remain separate.

Guides and Knowledge Base are persisted separately because their editorial and support/search/filter semantics differ. Their database records are the runtime authority; the legacy configuration remains only as a migration/parity snapshot and is not read by normal application paths.

### Stage 3 — Reassess FAQ and service relationships

Move FAQs only when editors need independent ordering, service association, or review. Keep Service-to-content links curated in configuration/routes for now; add persisted relationships only after a real Service editing or querying requirement exists. Keep page layout and CTA behaviour in Blade.

### Stage 4 — Reassess validated service data

Do not introduce Service/Variant/Package/Preparation records in the current phase. Revisit them only if recurring non-developer editing, multiple staff maintainers, independently maintained variants/packages, meaningful cross-domain querying, operational integration requiring persistent Service identity, or demonstrated admin-managed lifecycle needs emerge. Preserve current route names and fixed compositions if that decision changes; keep pricing separate.

### Stage 5 — Govern proof and media

Move approved testimonial display data, provider/facility facts, and gallery media only after consent/provenance rules are defined. Keep public submissions pending by default. Migrate authentic, owned assets; do not import prototype URLs or unsupported claims.

### Stage 6 — Reassess shop and company content

The Product catalogue boundary is now justified and implemented. If inventory, payment, or fulfilment becomes real, design a separate Product/Order commerce project rather than expanding the current catalogue into an implicit order system. Apply the same selective approach to careers, partnerships, hours, locations, and about pages.

### Stage 7 — Separate operational products

Only after product and operational validation should Waggies design booking, scheduling, availability, accounts, loyalty, notifications, tracking, or external integrations. These are new application domains, not later CMS resources.

### Migration safety requirements

- Preserve current named routes and canonical URLs.
- Keep old slugs as redirect records when they change.
- Never publish imported records by accident; default imports to draft/review.
- Compare page HTML contracts, metadata, schema, robots, sitemap membership, and search visibility before cutover.
- Keep the existing config source available as a historical migration/parity snapshot; it is not a competing runtime authority.
- Do not migrate unverified claims, prototype content, placeholder links, static prototype prices, or decorative concepts.
- Do not migrate a page merely because a route exists; migrate a content/domain responsibility only when its ownership and lifecycle justify it.

## 16. Explicit Do-Not-Build List

The following should not be built in this architecture phase or inferred from the prototype:

- A generic universal page builder or arbitrary `blocks[]` system.
- A third blog/content domain alongside Guides and Knowledge Base without a distinct validated job.
- A universal “content block” abstraction for services, legal pages, tools, and articles.
- CMS-managed pricing inputs, formulas, duplicated rate tables, or a generic pricing/settings editor.
- Customer accounts, dashboards, or login solely because the loyalty prototype depicts them.
- Loyalty points, tiers, referrals, redemption, or a ledger without defined commercial rules and an owner.
- Booking, availability, scheduling, or appointment allocation without an operational source of truth.
- Live webcam, GPS transport tracking, real-time updates, or notification promises without systems and support ownership.
- Checkout, payment, orders, stock, and fulfilment without an actual commerce requirement.
- Article comments without moderation, spam, privacy, and support justification.
- A partner directory based on unverified logos or prototype lists.
- A taxonomy for every possible pet type, audience, topic, location, or service before filtering demand exists.
- A generic polymorphic “related everything” graph when explicit typed relationships are sufficient.
- Enterprise editorial workflow, granular approval matrices, or an abstraction layer that only forwards Filament/package APIs.
- A media-package migration solely because `spatie/laravel-medialibrary` is installed.
- A new search architecture before the current content volume and search failure modes justify it.
- Prototype layouts, fonts, claims, guarantees, statistics, named specialists, partner relationships, or prices as requirements.
- Route/URL redesign as part of CMS implementation.

## 17. Recommended Target Architecture

### Product shape

Waggies should remain a service-led pet-care discovery, education, trust, and enquiry product until validated operational capabilities prove otherwise. The primary public action is a contextual request to Waggies; calculators and guides help a customer decide; proof and preparation content reduce uncertainty.

### Major domains/capabilities

1. **Service catalogue:** developer-controlled configuration, meaningful variants, packages, inclusions, preparation, and fixed Blade compositions.
2. **Editorial/support:** persisted Guides and Knowledge Articles, with FAQs, legal documents, and selected resources as conditional future candidates.
3. **Trust/media:** moderated testimonials, verified providers/facilities, owned media, and claim governance.
4. **Enquiry/conversion:** application-owned contact/request contracts and pricing context.
5. **Tools:** named application modules with reviewed reference data and clear safety boundaries.
6. **Catalogue/commerce:** bounded database-backed Product catalogue and browser cart; a separate product/order system only if validated.
7. **Publication/SEO:** shared application policy exposed to typed records and `PublicUrlCatalog`.
8. **Future operational domains:** booking, availability, accounts, loyalty, notifications, tracking, and integrations only as separately justified products.

### CMS boundary

Filament currently manages the justified editorial resources: Guides and Knowledge Articles, with their publication, SEO, and moderation boundaries. A Service CMS is a deferred future possibility, not current architecture; it must not be introduced without a real editing, ownership, relationship, or operational requirement. Filament does not become the runtime engine or page-layout editor.

### Structured domain-data boundary

Services, variants, packages, and preparation requirements remain structured in configuration because the current curated catalogue needs predictable fixed compositions, not persistence. Product editing is already justified as a bounded catalogue resource; providers, facilities, locations, and order/fulfilment domains remain deferred until independent editing/querying/relationship needs are real.

### Application boundary

Laravel controllers, focused application services/actions as needed, Blade compositions, Alpine modules, pricing, enquiry schemas, tools, cart state, publication policy, search behaviour, and future transactional workflows remain application-owned. The current focused controller and component architecture is the baseline.

### Pricing authority boundary

Service pricing is not a CMS domain. `config/waggies_pricing.php` is the single version-controlled source for commercial inputs: service tiers, variants, packages, estimate ranges, transport distance bands, and surcharges. Application code owns the meaning of those inputs, including distance-band selection, surcharge applicability, multipliers, quote-only behaviour, rounding, and presentation. Filament must not manage service pricing records, and no generic Settings screen may edit these values.

Do not move Waggies service pricing into Filament/CMS merely because staff-editable prices are technically possible. Pricing inputs participate in application-owned calculations and relationships; keep service pricing in version-controlled configuration unless a future product requirement materially changes this architecture.

R26 preserves the historical `service_prices` migration only as migration history and removes the table through a forward migration. Existing and fresh databases therefore have no service-pricing record set for the application to read; Shop/Product pricing remains a separate database-backed commerce boundary.

### Integration boundary

External systems own the capabilities they operate. Waggies owns canonical public content, mappings, customer-facing status, consent, and support context. Integrations must not silently become CMS content sources.

### Page-composition philosophy

Use fixed, typed compositions for known page families. Let content and domain records fill validated fields and curated relationships. Let application code render behaviour. Avoid both extremes: do not hard-code every future editorial sentence, and do not turn every visible section into an arbitrary block.

### Publication boundary

No draft, preview, expired, or non-indexable record may leak into public search, canonical metadata, structured data, or sitemaps. `PublicUrlCatalog` remains the intentional inventory, and slug history/redirects protect existing URLs.

### Media strategy

Waggies owns and governs production media. CMS Guide and Knowledge Article covers and rich-editor attachments use Spatie Media Library on the public disk, with separate `cover` and `content-attachments` collections, explicit image conversions, and responsive detail variants. Existing Unsplash prototype references remain narrowly scoped legacy fallbacks because no owned source asset was available for import; newly uploaded CMS media never uses arbitrary external URLs. Alt text remains a Waggies-owned content field.

### Migration approach

The Guide and Knowledge Base vertical slice is complete: persisted UUIDv7 records, Filament write paths, public reads, search, sitemap/catalog integration, SEO, rich-content sanitization, owned media, and slug-history redirects are tested together. Keep Services, static pages, navigation, tools, pricing, and Service relationships config/code-owned until evidence says otherwise. Preserve URLs and test publication/SEO parity at every step.

This is the architectural contract for the current implementation: keep the Guide, Knowledge Base, FAQ, Product, Gallery, and moderated Testimonial resources small and publication-safe; keep Services and pricing config-backed; and keep product behaviour, cart state, and future commerce workflows outside the CMS.

## 25. Batch 29 media architecture rules

Batch 29 establishes the following implementation contract:

- `Product`, `GalleryItem`, `Guide`, `KnowledgeArticle`, and `Testimonial` are the owning domain records for their managed image collections. Their staff-managed images use Spatie Media Library on the configured `public` disk.
- Covers use `cover`; gallery and product images use `image`; testimonial photos use `photo`; Guide and Knowledge Article rich-editor attachments use `content-attachments`. Single-image collections replace the previous item and Media Library owns removal of originals, conversions, and responsive derivatives.
- Meaningful Filament image resources expose a compact table thumbnail. The edit form shows the current Media Library image through the Media Library upload field. Where a record still has an inherited remote legacy URL and no managed media, the form may show that URL as a clearly labelled legacy preview; uploading a replacement makes Media Library authoritative.
- Public rendering uses the managed conversion and responsive `srcset` when media exists. The database image field is a deliberate, temporary legacy fallback only; it is not treated as Waggies-owned media and is not used for new uploads.
- Guides and Knowledge Articles keep separate cover and rich-editor attachment ownership. Rich-editor attachments remain public because their HTML is public; a future private editorial attachment requirement must use private visibility plus the appropriate renderer rather than exposing editor storage accidentally.
- Stable logos, icons, fixed illustrations, and other developer-owned assets remain ordinary application assets. Remote images remain only where no safe owned source was available; they must not be silently re-hosted or represented as migrated Waggies assets.
- Orphan cleanup requires ownership evidence. Media Library's clean command may remove a confirmed unowned managed directory; arbitrary files, backups, and source assets are not deleted merely because a database query does not mention them.

## 18. Open Questions That Are Genuinely Irreducible

These questions cannot be answered from the repository and discovery documents alone; they require business or operational decisions:

1. Is Waggies intentionally remaining quote/enquiry-led, or does the business want authoritative booking and availability for any specific service?
2. Which current claims—people, credentials, hours, facilities, statistics, guarantees, service promises, partner relationships, and medical/relocation statements—are verified, consented, and owned by a named reviewer?
3. Who will edit Guides, Knowledge Base, FAQs, service facts, media, legal content, and testimonials, and what change cadence justifies moving each family out of config?
4. Which commercial prices are truly fixed, which are estimates, and which require custom quote; separately, does the business require rate changes without deployments?
5. Which relocation/checklist/legal documents have a real maintained owner and a customer need for download/print output?
6. If the shop is intended to become commerce, what are the authoritative systems for stock, payment, fulfilment, returns, and customer support?
7. If loyalty is intended to become a product, what are the earning, redemption, liability, eligibility, expiry, and support rules?

All other target decisions in this document are resolved from current implementation evidence, the architecture audit, the prototype discovery findings, or the smallest defensible Laravel architecture.
