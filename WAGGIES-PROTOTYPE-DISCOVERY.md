# Waggies Prototype Discovery Audit

> This document analyzes the historical Waggies Google Stitch prototype archive for inspiration and product discovery. It is not an implementation specification and does not override the current Waggies Laravel application.

**Snapshot:** 21 September 2026  
**Current-project baseline:** `93e3c3a` (`Refine Waggies public frontend runtime architecture`)  
**Archive inspected:** `C:\Users\Bridges\Herd\waggies-claude-design`  
**Current project inspected:** `C:\Users\Bridges\Herd\waggies`

## 1. Purpose and authority

This is a discovery and comparison artifact, not a product backlog, design specification, CMS schema, or implementation plan.

The current standalone Laravel application is authoritative. Its public routes, Blade-first rendering, Alpine-first interaction model, current semantic components, server-owned values, existing content configuration, Filament administration foundation, and current accessibility architecture remain the source of truth. The Stitch archive is an historical inspiration source only.

No page, claim, interaction, URL, visual treatment, package, data model, business promise, or workflow in the archive is approved automatically. Prototype copy is not treated as a verified current Waggies fact. Prototype repetition is evidence that an idea was explored, not evidence of customer demand or product priority.

This audit intentionally stops at inspection, classification, evaluation, and documentation. It makes no application, route, database, dependency, CMS, design-system, or public-behavior changes.

### Evidence conventions

- **Strong prototype signal:** substantial treatment and/or repeated across related concepts; still not a requirement.
- **Moderate prototype signal:** clearly represented once or in a small group.
- **Weak prototype signal:** small, incomplete, or largely decorative treatment.
- **Artifact:** visibly unfinished, placeholder-driven, contradictory, or too implementation-like to treat as product evidence.
- **A — Already present:** substantially represented in current Waggies.
- **B — Present but different:** current Waggies covers the capability through a different structure or experience.
- **C — Partially present:** the idea exists, but meaningful content or capability is incomplete.
- **D — Potential addition:** a potentially useful concept not clearly represented today.
- **E — Needs validation:** interesting, but product fit or operational feasibility is unclear.
- **F — Not appropriate:** does not fit the current product direction without a major, unvalidated change.
- **G — Prototype artifact:** should not be treated as product evidence.

## 2. Archive inventory

### What exists

The archive contains **37 page directories**, each with:

- one `code.html` file;
- one `screen.png` screenshot;
- no local Blade, PHP, React, Next.js, JSON, Markdown, CSS, or JavaScript source files;
- external image URLs, Google font URLs, Material Symbols references, and Tailwind CDN/config scaffolding inside the HTML.

There are no route definitions, content repositories, mock API responses, CMS exports, database fixtures, component source files, or application configuration files in the archive. The HTML is therefore evidence of rendered concepts and copy, not evidence of a working prototype backend.

### Directory organization

Most concepts are top-level directories. Three editorial groups contain an additional `listing_page` directory:

- `blog_category/listing_page`
- `guides_category/listing_page`
- `knowledge_base_category/listing_page`

The remaining directories are flat page concepts, including two separate partnership explorations: `partnerships_page` and `stitch_waggies_partnership page list`.

### Asset and rendering observations

The screenshots are long vertical captures, approximately 323–1120 pixels wide and 1600 pixels tall. They do not form explicit desktop/mobile pairs. The HTML does contain responsive utility classes and mobile/desktop visibility variants, so responsive intent can be studied, but exact breakpoints and dimensions should not be copied.

The pages use several visual systems rather than one stable design system: Playfair Display, Plus Jakarta Sans, Inter, Noto Sans, Lexend, Public Sans, and Newsreader appear across different exports. Color tokens, navigation structures, button treatments, and layout density also vary. This is evidence of parallel visual exploration, not a single approved design direction.

### Archive groups

| Group | Concepts found | Interpretation |
| --- | --- | --- |
| Core marketing | Home, services overview, about, contact | Repeated product-positioning and conversion exploration. |
| Service detail | Boarding landing, dog boarding, cat boarding, exotic boarding, grooming, training, vet care | Service presentation patterns with strong content signals but inconsistent claims and structures. |
| Relocation | Relocation landing, import, export, local transport, checklist generator | A broad relocation information and enquiry journey was explored. |
| Editorial | Blog lists, blog post, guides lists, guides section, guide article | Multiple overlapping editorial models were explored. |
| Knowledge support | Knowledge Base landing, category listing, article | A more searchable/support-oriented content model was explored. |
| Commerce and retention | Shop, loyalty program | Product catalogue/cart and account-backed rewards concepts were explored. |
| Trust and company | Testimonials, gallery, careers, partnerships, about | Proof, people, facilities, hiring, and partner narratives were explored. |
| Legal | Privacy, cookies, terms | Long-form policy presentation and preference-management concepts were explored. |

### Archive reliability cautions

Many controls use `href="#"`, forms have no meaningful action, and no application event handlers or API calls were found in the HTML scan. Buttons labelled “Book”, “Apply”, “Redeem”, “Load More”, “Subscribe”, “Track”, or “Download” should therefore be recorded as depicted intentions, not implemented features. Some values also read like generated marketing placeholders, for example precise customer counts, named specialists, partner logos, service guarantees, and operational promises.

## 3. Prototype page inventory

The table groups each distinct export as a concept. A mobile or visual state is not counted as a separate page unless the archive gives it a separate directory.

| Prototype concept | Purpose | Major sections | Features/interactions | Current Waggies equivalent | Classification | Evidence strength |
| --- | --- | --- | --- | --- | --- | --- |
| `waggies_homepage` | Premium pet-care entry point | Hero, VIP relocation promotion, service cards, difference/standards, testimonials, CTA | Service discovery, quote and appointment CTAs, facility-video affordance | `/`, `pages/home.blade.php`, service navigation, proof components | B: current home and service discovery exist; presentation and claims differ | Strong |
| `services_overview_page` | Catalogue and service selection | Expertise, boarding, grooming, vet, relocation, training, microchipping, testimonials, “not sure” CTA | Service cards, quote/contact paths | `/services`, service cards/comparison, contact intent selection | A/B: same catalogue intent with more structured current architecture | Strong |
| `about_us_page` | Company, facility, people, standards, location | Philosophy, facility areas, specialists, map, hours, CTA | Facility-gallery link, quote/contact | `/about`, `/about/gallery`, config-backed about content | B: current about exists; facility/team depth is represented differently | Strong |
| `contact_us_page` | Contact and enquiry | Emergency notice, contact information, location/map, hours, message form | Phone/email links, map CTA, message form, chat affordance | `/contact`, server-authoritative contact intent form, config contact data | A/B: same conversion need; archive’s emergency/map presentation differs | Strong |
| `pet_boarding_services_landing_page` | Boarding category selection | Boarding promise, dog/cat/exotic options, trust factors, testimonials, CTA | Species selection, quote/contact | `/services/boarding`, `/services/boarding/{species}` | A/B: current boarding hierarchy is explicit and more structured | Strong |
| `dog_boarding_services_page` | Dog boarding detail | Benefits, live webcam, vet, security, climate control, packages, daily schedule, safety, location | Package booking, gallery, quote, requirements link | `/services/boarding/dogs`, `waggies_boarding.php`, boarding components | C/E: current dog boarding exists; webcam, schedule, and claims need validation | Strong |
| `cat_boarding_services_page` | Cat boarding detail | Quiet zones, vertical space, suites, facility, packages, testimonials | Package selection, gallery, booking/quote, WhatsApp/update affordance | `/services/boarding/cats`, species configuration | C/E: current equivalent exists; daily update and package presentation need validation | Strong |
| `exotic_pet_boarding_page` | Specialist boarding for non-standard pets | Habitat, climate/UV, enclosures, vet oversight, species packages, facilities | Bird/reptile/small-mammal selection, custom quote, gallery | `/services/boarding/exotic` | B/C: current species route exists; archive provides more specific content ideas | Strong |
| `grooming_services_page` | Grooming service/package selection | Standards, packages, add-ons, testimonials, FAQ, CTA | Package selection, FAQ disclosure, booking | `/services/grooming`, service detail and pricing components | A/B: current grooming page and pricing exist; package copy is different | Strong |
| `pet_training_services_page` | Training programme selection | Programmes, benefits, delivery modes, trainers, testimonials, assessment CTA | Programme selection, assessment/enquiry form | `/services/training` | C/E: service exists; trainer profiles and assessment workflow are not clearly current capabilities | Strong |
| `vet_care_services_page` | Veterinary service overview | Preventative care, diagnostics, emergency triage, microchipping, specialists, hours | Service selection, contact/booking | `/services/vet-care` | B/C: current service exists; specialist profiles and emergency framing need validation | Strong |
| `pet_relocation_services_landing_page` | Relocation service selection | Import, export, local transport, safety, documents, updates, testimonials | Service selection, quote/contact | `/services/relocation`, relocation child routes | A/B: current relocation hierarchy covers the same discovery path | Strong |
| `pet_import_services_page` | Bringing a pet into Nigeria | Requirements, documentation, vet/NAQS claims, process, CTA | Quote/enquiry, document-oriented discovery | `/services/relocation/import` | B/C: current import page exists; named authority and delivery claims need verification | Moderate |
| `pet_export_services_page` | Moving a pet from Abuja abroad | Guarantee, planning, medical/microchip, permits, crate, flight, recent relocations, FAQ | Quote, FAQ, package/support links | `/services/relocation/export` | B/C: current export page exists; guarantee, case studies, crate support need validation | Strong |
| `local_pet_transport_page` | Local pet transport/door-to-door service | Safety metrics, GPS, climate control, vet oversight, fleet, process, reviews | Request quote, schedule pickup, track ride, arrival confirmation | `/services/relocation/transport` | C/E: current transport route exists; tracking/scheduling are not evidenced as current capabilities | Strong |
| `pricing_&_calculator_page` | Transparent pricing and estimate | Rate cards, currency options, calculator, FAQs, loyalty promotion | Currency toggle, select fields, pet/service choices, quantity, options, estimate CTA | `/services/pricing`, `/tools/cost-calculator`, config-backed pricing | A/B: current pricing/calculator are substantially present; current architecture is authoritative | Strong |
| `relocation_doc_checklist_generator` | Relocation preparation tool | Journey details, origin/destination, service type, pet types, tips, PDF/share | Radio cards, pet checkboxes, wizard-like form, PDF/save/share affordances | `/services/relocation/checklist`, current checklist view | B/C/E: current checklist exists; generator/PDF/share behavior is not evidenced as implemented | Moderate |
| `blog_category/listing_page` | Journal/blog catalogue | Featured/listed articles, topics, newsletter/community CTA | Search input, topic filters, article links | `/guides`, `/knowledge-base`, search endpoint | E/G: current editorial domains exist, but this exact blog domain is not current | Moderate |
| `waggies_blog_page` | Alternate blog landing | Featured article, recent articles, popular content, social/newsletter | Category links, article links, subscribe form | `/guides`, `/knowledge-base` | E/G: overlapping alternate to the other blog export | Moderate |
| `blog_post_content_page` | Blog article detail | Long article, author, comments, related posts/services, CTA | Comment form, related links, book-service CTAs | `/guides/{slug}` or `/knowledge-base/{slug}` | B/C: article detail exists in two current editorial domains; comments are not evidenced | Moderate |
| `guides_category/listing_page` | Guides landing | Guide introduction, featured/latest guides, newsletter | Search, login affordance, subscribe | `/guides` | B: current guides index exists; account/login is prototype-only | Moderate |
| `guides_section_page` | Filterable guide catalogue | Search, topic filters, article cards, load-more, CTA | Search, filters, load more | `/guides` and route-backed guide data | B/C: current guide listing exists; load-more/filter depth differs | Strong |
| `guide_content_page` | In-depth relocation guide | TOC, article sections, tips, warnings, related services/resources | Print, PDF, share, in-page anchors, quote CTA | `/guides/{slug}` | B/C: current article detail and TOC exist; PDF/print/share need validation | Strong |
| `knowledge_base_page` | Searchable support hub | Filters, pet type/content type, topics, featured articles, downloads | Search/filter controls, downloadable resources | `/knowledge-base`, search | B/C: current KB exists; filter/download model may be deeper in archive | Strong |
| `knowledge_base_category/listing_page` | Knowledge article category | Search hero, category/latest articles, load more, personal-advice CTA | Search, load more | `/knowledge-base` | B/C: current index exists; category landing is not a separate current route | Moderate |
| `knowledge_base_article_page` | Support article detail | TOC, article, expert author, related articles, professional-help CTA | Search, comment form, related content | `/knowledge-base/{slug}` | B/C: current article detail exists; comment and author workflow need validation | Moderate |
| `online_shop_main_page` | Product catalogue | Search, category cards, filters, product grid, newsletter, relocation CTA | Search, price/brand/pet filters, cart/add-to-cart, load more | `/shop`, `/shop/{id}` | B/C: current shop and cart behavior exist; archive has richer catalogue/filter ideas | Strong |
| `pet_photo_gallery_page` | Visual proof/facility/customer gallery | Named pet/photo grid, community CTA | Gallery/lightbox-like controls and image selection affordances | `/about/gallery`, gallery-lightbox component | A/B: current gallery and lightbox exist; archive’s taxonomy is different | Strong |
| `testimonials_page` | Social proof catalogue and submission | Testimonials, review cards, submission CTA | Testimonial submission, service/category proof | `/about/testimonials`, testimonial API/form and moderation | A/B: current capability is more authoritative and moderated | Moderate |
| `loyalty_programs_page` | Rewards acquisition and account dashboard concept | Join/earn/redeem, tiers, rewards, balance/history, referrals, FAQ | Login, points dashboard, redeem, referral, booking | `/loyalty`, config-backed loyalty content | D/E: current public loyalty page exists, but account/points runtime is not evidenced | Strong |
| `career_opportunities_page` | Recruitment marketing and openings | Benefits, culture, media, process, openings, staff quotes | Openings anchors, apply buttons, CV/general-interest CTA | `/about/careers` | B/C: current careers page exists; application handling needs validation | Moderate |
| `partnerships_page` | Partnership proposition | Benefits, partner logos, partner CTA/form | Partner enquiry form | `/about/partnerships` | B/C: current page exists; partner claims and form workflow need validation | Moderate |
| `stitch_waggies_partnership page list` | Partner directory/list | Partner logos/cards, network CTA | Partner discovery/list | `/about/partnerships` | E/G: duplicate/alternate partnership concept; not a distinct current requirement | Weak/moderate |
| `privacy_policy_page` | Privacy information | Introduction, collection/use, security, transfers, cookies, rights, contact | Email/contact links | `/privacy-policy`, legal page | A/B: current legal page exists; archive is a more specific long-form variant | Moderate |
| `cookies_policy_page` | Cookie explanation and preference management | TOC, cookie types, use, preferences, contact | TOC anchors, download/print, open settings, clear cookies, checkboxes | `/cookies-policy` | C/E: legal page exists; consent-management controls are not evidenced as current | Moderate |
| `terms_of_service_page` | Terms and service boundaries | Scope, responsibilities, payment, cancellations, liability, IP, law | TOC-style section navigation | `/terms-of-service` | A/B: current legal page exists; archive emphasizes operational terms | Moderate |

## 4. Page types discovered

The archive contains these distinct page types or concepts:

1. **Marketing home and service catalogue:** broad brand entry points, service summaries, proof, and primary CTAs.
2. **Service detail:** grooming, training, veterinary care, boarding, and relocation pages that explain outcomes, inclusions, process, and next actions.
3. **Service category and species detail:** boarding is divided into a category landing and dog, cat, and exotic variants.
4. **Relocation subtype pages:** import, export, local transport, and a preparation/checklist tool.
5. **Pricing and estimator:** rate-card presentation combined with a configurable estimate interaction.
6. **Editorial listing and article:** blog, guides, and guide article concepts.
7. **Knowledge-support listing and article:** a more utility-oriented searchable content model with filters and downloadable resources.
8. **Commerce catalogue:** product listing with search, filters, categories, cart, and newsletter capture.
9. **Proof and media:** testimonial catalogue and photo gallery.
10. **Retention/account concept:** loyalty programme with points, tiers, account balance, history, and redemption.
11. **Company and ecosystem:** about, careers, partnerships, facilities, team, and location content.
12. **Legal/information:** privacy, cookies, and terms pages with long-form navigation.
13. **Implied operational flows:** booking, scheduling, tracking, comments, login, applications, document generation, and account dashboards. These are depicted as destinations or controls but are not implemented in the static archive.

## 5. Section patterns discovered

### Opening and orientation

- Image-led or text-led hero with a location/service eyebrow.
- One or two primary actions such as “Get a Quote”, “Book”, “Contact”, or “View Services”.
- Breadcrumb or in-page orientation on articles, guides, legal pages, and the FAQ.
- Short promise statements that frame the service around safety, care, convenience, or expertise.

### Service explanation

- Service summaries and category cards.
- Benefits and feature lists.
- Package/tier cards with inclusions and sometimes prices.
- “How it works” or process steps.
- Day-in-the-life schedules for boarding.
- Service-specific standards such as climate control, secure facilities, vet oversight, handling, or travel documentation.
- Related services and next-step CTAs.

### Pricing and decision support

- Transparent rate cards.
- Package comparison.
- Currency controls in the pricing concept.
- Estimate/calculator form.
- “Custom quote” framing where fixed prices are inappropriate.
- FAQs about pricing, booking, cancellations, and service expectations.

### Trust and proof

- Testimonials and review cards.
- Customer/pet stories.
- Staff, veterinarian, trainer, or specialist profiles.
- Facility photography and map/location blocks.
- Safety standards and process assurances.
- Credentials, partner logos, and named operational standards.
- Statistics and guarantees, often as unverified prototype copy.

### Education and support

- Long-form guides with table of contents, tips, warnings, checklists, related services, and related resources.
- Knowledge-base search/filtering.
- FAQ categories and accordion answers.
- Downloadable or printable resources.
- Preparation guidance for boarding, grooming, vet care, relocation, and pet ownership.

### Conversion and contact

- Quote requests.
- Book/appointment CTAs.
- Contact forms.
- Phone, email, WhatsApp, map, and directions affordances.
- Newsletter/community subscription.
- Assessment or consultation requests.

### Supporting navigation

- Service navigation grouped into boarding, wellness, and relocation.
- Company navigation grouped into about, careers, partnerships, testimonials, and gallery.
- Resources grouped into guides, FAQ, and knowledge base.
- Footer service/company/legal links.
- Sticky or fixed mobile action patterns on some exports.

## 6. Feature inventory

### Discovery

- Service catalogue and category discovery.
- Species-specific boarding discovery.
- Article and guide browsing.
- Knowledge-base search and topic filtering.
- Shop search, category browsing, brand/pet-type/price filtering.
- Related service, related article, and “not sure which service” pathways.
- Location and facility discovery.

### Service

- Boarding package selection.
- Grooming package and add-on selection.
- Training programme selection and assessment request.
- Vet service selection and contact/booking.
- Relocation import/export/transport selection.
- Service process and preparation education.
- Pet type/species selection.

### Commerce

- Product catalogue and add-to-cart.
- Cart affordance and product search.
- Product badges such as new, best seller, and discount.
- Newsletter/shop promotion.
- Loyalty points and reward redemption concept.
- Currency selection in the pricing export.

The shop and cart are the strongest commerce signal, but the static HTML does not prove inventory, checkout, payment, fulfillment, or account behavior.

### Enquiry and contact

- Quote request.
- Booking or appointment request.
- Contact/message form.
- Phone, email, WhatsApp, and directions.
- Relocation consultation.
- Training assessment.
- Partnership enquiry.
- Career application/general CV submission.
- Newsletter subscription.

### Content

- Blog and guide listing.
- Article detail with author, metadata, reading time, TOC, related content, and related services.
- Knowledge-base content with topics, pet types, content types, featured content, and downloads.
- FAQs grouped by service/problem.
- Print/PDF/share affordances for high-value guides and policies.

### Trust

- Testimonials and service-specific proof.
- Expert/team profiles.
- Facility and gallery content.
- Safety policies and standards.
- Partner directories/logos.
- Credentials and regulatory references.
- Customer statistics and guarantees.

The last group needs the most skepticism: a visual badge, named person, partner logo, guarantee, or statistic is not evidence that the current business can substantiate or operate the claim.

### Loyalty and account

- Membership tiers.
- Earn/redeem points.
- Balance and activity history.
- Rewards catalogue.
- Referral concept.
- Login/dashboard.
- Priority booking.

This is a substantial product concept, not merely a content section. It would require identity, ledger, eligibility, redemption, and customer-support rules that are not present in the current public architecture.

### Tools

- Pricing estimate calculator.
- Relocation document checklist generator.
- In-page FAQ search.
- Knowledge-base search/filter.
- Print/download/share of documents.

### Navigation

- Desktop navigation variants.
- Mobile menu/drawer affordances.
- Sticky headers and mobile bottom/floating CTAs.
- In-page TOC and anchor navigation.
- Footer cross-linking.

## 7. Interaction inventory

### Interactions actually evidenced by the export

- Native or CSS-style FAQ/details disclosures appear on the FAQ, grooming, loyalty, relocation, pricing, and export concepts.
- Anchor links appear in FAQ categories, legal TOCs, careers openings, and article TOCs.
- Form controls appear on contact, training, partnership, newsletter, knowledge-base, shop, pricing, and checklist concepts.
- Radio-card and checkbox selection styling appears in the relocation checklist generator.
- Pricing controls include selects, pet-type buttons, quantity input, checkboxes, and an estimate CTA.
- Shop controls include a search field, filter checkboxes, category navigation, add-to-cart buttons, and “load more” presentation.
- Gallery pages include repeated image buttons/controls suggestive of a gallery or lightbox.
- Mobile/desktop visibility classes and fixed/sticky elements indicate responsive navigation and CTA intent.

### Interactions depicted but not proven to work

- Booking, appointment scheduling, and package selection.
- Live webcam access.
- GPS ride tracking and real-time updates.
- Login and customer dashboard.
- Loyalty balance, points ledger, referral, and redemption.
- Comment submission.
- Job application and CV submission.
- PDF generation/download.
- Cookie settings and clearing.
- Load more, search, filtering, and currency switching.
- Newsletter subscription.

The archive scan found no `addEventListener`, `onclick`, `fetch`, `axios`, Alpine directives, or equivalent application event wiring. There are also hundreds of placeholder `href="#"` links. These controls should be treated as product hypotheses and presentation affordances, not existing implementation.

### Interaction value assessment

- **Likely valuable if real:** service comparison/selection, pricing estimation, preparation checklists, searchable support content, FAQs, related content, quote/contact intent, and gallery proof.
- **Potentially valuable but operationally expensive:** booking, scheduling, live updates, GPS tracking, loyalty accounts, comments, online checkout, and job applications.
- **Primarily presentation:** hover scaling, decorative badges, social icon circles, animated arrows, gradient overlays, and repeated “premium/luxury” labels.

## 8. User journeys

### Service discovery to enquiry

`Home or services overview → choose service → read benefits/inclusions/process → review proof or standards → request quote/contact/book`

This is strongly supported by the archive and substantially supported by current service routes and the current contact intent form. The missing question is whether “book” means a real availability workflow or only an enquiry.

### Boarding selection

`Boarding landing → choose dog/cat/exotic → compare environment/package → review safety and daily-care expectations → request a quote or book a stay`

Current Waggies has the boarding landing and species routes. The archive adds package-specific presentation, species-specific preparation, daily schedules, and update expectations as content ideas.

### Relocation planning

`Relocation landing → choose import/export/local transport → understand documentation and process → use checklist or request expert help → submit a quote/enquiry`

This is one of the clearest cross-archive journeys. Current Waggies has the same route family and a checklist page. The archive suggests stronger cross-linking between education, checklist, and enquiry.

### Content to service

`Guides/blog/knowledge base → search or browse topic → read article → follow related service or preparation CTA → request help`

Current Waggies has both guides and knowledge-base domains, article routes, related tools, and service CTAs. The archive reinforces related-content relationships but does not establish that a third blog domain is needed.

### Shop discovery to cart

`Shop landing → search/filter/category → inspect product → add to cart → continue browsing or proceed to checkout`

Current Waggies has shop listing, product detail, and cart behavior according to the current implementation audit. The archive provides catalogue/filter/promotion ideas but does not prove checkout or fulfillment requirements.

### Proof and trust

`About/service page → inspect facility, team, credentials, testimonials, partners, or gallery → contact or request a quote`

Current Waggies has about, testimonials, gallery, careers, partnerships, proof components, and contact. The archive suggests deeper relationship between proof content and service-specific decisions.

### Loyalty/account concept

`Public loyalty page → join/login → earn points through services → view balance/history → redeem reward or book`

This is depicted clearly but is not evidenced as a current runtime capability. It should remain a product-validation question, not a CMS assumption.

### Support answer journey

`FAQ or knowledge-base search → filter by topic/pet/content type → read answer → download/print checklist or contact an expert`

Current Waggies has FAQ, knowledge base, guides, and tools. The archive suggests a more explicit answer-to-action bridge and downloadable support material.

## 9. Current Waggies crosswalk

The current Laravel application already covers much of the archive’s public information architecture:

- Home: `/` and `resources/views/pages/home.blade.php`.
- Services: `/services`, boarding, species boarding, grooming, vet care, training, pricing, relocation, import, export, and transport routes.
- Contact: `/contact` with server-authoritative intent selection and submission handling.
- Content: `/guides`, `/guides/{slug}`, `/knowledge-base`, `/knowledge-base/{slug}`, and `/faq`.
- Tools: tools index plus calculators, checklists, health, behavior, nutrition, breed, and planning tools.
- About and proof: about, testimonials, gallery, careers, partnerships, and loyalty routes.
- Shop: `/shop` and `/shop/{id}` with current cart interaction.
- Legal: privacy, cookies, and terms routes.

The archive’s strongest net-new signals are not basic pages. They are deeper content relationships and a few potentially new capabilities: service-specific preparation content, structured proof/team/facility data, richer search/filtering, downloadable resources, and account-backed loyalty. Those all require validation before architecture work.

### Crosswalk by concept

| Discovery | Current state | Assessment |
| --- | --- | --- |
| Service catalogue and service detail | Present across service routes and reusable service components | Keep current capability; adapt only where content gaps are validated. |
| Boarding species hierarchy | Present with dedicated boarding/species routes | Keep current hierarchy; consider richer preparation/safety content later. |
| Relocation import/export/transport | Present with dedicated routes and checklist | Keep current hierarchy; investigate stronger journey linking. |
| Pricing and estimate | Present through pricing page and cost calculator | Keep server-authoritative current model; do not copy static prototype rates. |
| FAQ | Present with current FAQ page and accordion architecture | Keep; evaluate archive’s category/search framing only if useful. |
| Guides and knowledge base | Both present as separate current domains | Keep the distinction; do not create a blog solely to match the archive. |
| Search and filtering | Current search/content tools exist; archive shows additional filters | Adapt only if a concrete discovery problem is evidenced. |
| Shop and cart | Present and tested in current audit | Keep current behavior; treat archive filters/promotions as content/product ideas. |
| Gallery/lightbox | Present as current about gallery and reusable lightbox | Keep current capability; archive provides content/taxonomy ideas. |
| Testimonials | Present with public submission and moderation | Keep current trust boundary; do not adopt unverified archive quotes. |
| About/team/facilities | Present in current about/config structure, with different depth | Adapt content only after claims, people, facilities, and media are verified. |
| Careers | Present as a current about subpage | Keep page concept; application workflow is unverified. |
| Partnerships | Present as a current about subpage | Keep page concept; do not treat archive logos as current partners. |
| Loyalty | Present as a public content page | Current public content does not prove account/ledger capability; validate separately. |
| Relocation checklist | Present as a current page | Adapt if downloadable/generator behavior is a genuine need. |
| Blog domain | Not a distinct current route; guides and knowledge base cover related content | Needs validation; likely redundant as a third editorial domain. |
| Downloadable/printable content | Archive repeatedly depicts it; current routes provide content/tools but not necessarily the same outputs | Worth discussing for specific high-value documents, not as a universal feature. |
| Live webcam, GPS tracking, real-time updates | Not evidenced by current route or public architecture | Needs validation and likely application/integration work. |
| Login/account dashboard | Not evidenced in current public route inventory | Product-level addition, not CMS content. |
| Booking/scheduling | Archive uses booking language, but static export does not implement it | Clarify whether Waggies needs enquiry capture or true availability booking. |

## 10. Potential current Waggies gaps

These are potential gaps, not approved work.

| Potential gap | Evidence from prototype | Current coverage | Possible value | Confidence | CMS relevance |
| --- | --- | --- | --- | --- | --- |
| Service-specific preparation and expectations | Boarding day schedule, grooming preparation, relocation documents/crates, vet explanations | Services, guides, FAQ, checklist, and tools cover parts of this | Reduces uncertainty before enquiry and may improve service fit | Medium/high | Likely structured service guidance plus reusable editorial content |
| Clear service/package comparison | Boarding and grooming packages; pricing cards; service comparison patterns | Current service comparison and pricing components exist | Helps users choose without contacting staff for basic distinctions | Medium | Structured service/package fields; current pricing authority remains separate |
| Stronger article-to-service cross-linking | Every article concept includes related services, tools, or CTAs | Current article and related-tool components cover some of this | Turns education into an informed next action | High | Relationships between articles, services, tools, and CTAs |
| Downloadable/printable relocation resources | Guide and checklist include PDF/print/download affordances | Checklist and guide pages exist; generated file behavior is uncertain | Useful for complex, offline, multi-step relocation work | Medium | Media/document outputs, publication metadata, possibly generated application output |
| More explicit knowledge-base filtering | Pet type, content type, topic filters and search | Current knowledge base and search exist | Improves answer discovery if content volume warrants it | Medium | Taxonomy fields and filterable relationships |
| Service-specific proof and specialist context | Named vets/trainers, facility areas, standards, service testimonials | Current proof/testimonials/about components exist | Builds trust at the decision point | Medium | Team/provider, facility, credential, testimonial relationships |
| Operational information depth | Hours, location, map, emergency framing, service-area hints | Current contact/config data and page content exist | Reduces pre-contact questions and failed visits | Medium | Structured operating/location data if the business confirms it |
| Relocation journey integration | Import/export/transport pages, checklist, guide, and quote path | Current hierarchy exists but may be navigationally distributed | Makes a complex service easier to understand and act on | High | Relationships among service variants, guides, checklists, FAQs, and CTAs |
| Shop discovery depth | Search, price/brand/pet filters, categories, badges | Current shop/cart exists | Helps customers find products as catalogue grows | Medium | Structured product taxonomy and inventory data, not only editorial content |
| Mobile persistent action | Several exports show fixed/sticky CTAs or mobile action priority | Current app has mobile/floating action primitives | Keeps enquiry/contact available on long service pages | Medium | Mostly application presentation; content may need CTA ownership |
| Trust claim governance | Archive repeatedly uses credentials, guarantees, statistics, logos, and named staff | Current architecture supports proof but claims must be verified | Prevents accidental publication of unsupported claims | High | Publication workflow, verification/expiry fields, media and relationships |
| Customer retention capability | Loyalty page depicts points, tiers, history, redemption, and priority booking | Public loyalty page exists; runtime account capability is not evidenced | Could encourage repeat service use if unit economics support it | Low/medium | Mostly application/domain logic; CMS only owns explanatory content |
| Real booking/availability | Booking language appears across service pages and loyalty | Current public flow is primarily quote/contact oriented | Could reduce manual coordination if operations can support it | Low/medium | CMS is secondary; availability and rules are application data |

## 11. Potential new pages

The following may be worth considering during future product planning. None is approved, and several can be satisfied through current page types rather than new routes.

- A service-preparation page or structured preparation section attached to boarding, grooming, vet, and relocation services.
- A relocation resource hub that intentionally connects import, export, transport, checklist, guide, FAQ, and enquiry paths.
- A dedicated service comparison/decision page only if the current services page cannot answer the selection problem.
- A downloadable resources index if the number of validated checklists and documents grows.
- A service-area or operating-information page if geographic coverage and hours become a recurring customer question.
- A team/provider detail page if verified credentials and staff profiles become important to service decisions.
- A facility detail page if facility tours, safety standards, or specialist environments are real differentiators.
- A product category page if shop catalogue scale makes the current index insufficient.

The archive does **not** provide strong evidence for adding a separate blog route, a public account dashboard, a public booking calendar, a live tracking page, or a partner directory as immediate pages.

## 12. Potential new sections

Potential reusable semantic sections, independent of the archive’s exact visual presentation, include:

- What to expect before, during, and after a service.
- Preparation checklist and required documents.
- Service/package comparison with clear “custom quote” boundaries.
- Safety standards and handling principles.
- Verified team/provider or facility context.
- Service-specific FAQs.
- Related guides, tools, and services.
- “Not sure which service?” decision support.
- Operating hours, service area, contact route, and emergency guidance where verified.
- Customer update expectations, only where operationally true.
- Downloadable/printable resource callout for validated documents.
- Product recommendation or related-service callout in the shop, only if commercial rules support it.

These are content and capability patterns. They do not require the cards, tabs, grids, gradients, or typography used by the archive.

## 13. Potential new features

### Clearly plausible future capabilities

- Better cross-linking between services, guides, knowledge articles, FAQs, and tools.
- Structured service preparation content.
- Search/filter improvements for knowledge content when volume justifies them.
- Validated printable/downloadable relocation resources.
- Service/package comparison where current content leaves real ambiguity.

### Capabilities requiring product and operational validation

- Booking and availability.
- Scheduling and appointment management.
- Quote workflows with status and follow-up.
- Customer accounts.
- Loyalty points ledger, tiers, rewards, referrals, and redemption.
- Live boarding updates or daily photo delivery.
- GPS transport tracking and ride status.
- Online shop checkout, payment, inventory, and fulfillment.
- Career application intake and file handling.
- Article comments and community moderation.

The archive does not establish which of these is commercially or operationally appropriate. Several would expand Waggies from a public information/conversion site into an operational platform.

## 14. CMS implications

### NOT YET APPROVED CMS REQUIREMENTS

The following concepts may influence future CMS discovery, but none is an approved schema or implementation requirement.

| Discovered concept | Why it may matter | Likely implication |
| --- | --- | --- |
| Service-specific structured information | Services repeatedly need packages, inclusions, preparation, process, safety, FAQs, and CTAs | Structured service fields and relationships may be more durable than page-only copy. |
| Service variants/species | Boarding has dog, cat, and exotic variants with different environments and requirements | A variant relationship or specialized content model may be useful; do not flatten materially different services. |
| Packages and tiers | Boarding, grooming, loyalty, and pricing use tiers/packages | Structured package content, eligibility, price labels, and publication state may be needed. |
| FAQs | FAQs recur by general topic and service | Reusable FAQ entries/groups with ordering and publication status may help. |
| Guides and knowledge articles | Archive shows overlapping editorial domains with metadata, topics, authors, TOCs, related content, and reading time | Keep content-domain boundaries explicit; discover shared primitives and relationships before merging. |
| Related content | Articles link to services, tools, other articles, and CTAs | Typed relationships or curated related-content fields may matter. |
| Preparation/checklist content | Relocation and boarding require repeatable checklists and instructions | Could be editorial checklists, structured steps, or application-generated documents; decide by use case. |
| Testimonials/proof | Testimonials, staff quotes, statistics, logos, and service-specific proof recur | Moderation, provenance, consent, service association, and claim verification matter. |
| Team/provider profiles | Vets, trainers, groomers, and company specialists are shown as trust content | Profiles may need role, credentials, bio, media, service associations, and active status. |
| Facilities and locations | About/service pages use facility areas, maps, hours, and location information | Structured location/facility data may support reuse, but only after business facts are confirmed. |
| Media-rich content | Gallery, facility images, staff portraits, product images, PDFs, and article media recur | Media ownership, alt text, captions, licensing, conversions, and document handling need discovery. |
| Promotions and offers | Shop badges, loyalty bonuses, and service promotions appear | Publication windows, eligibility, and commercial authority may be necessary. |
| Partner content | Partner pages show logos and partnership descriptions | Relationship status, consent, logo provenance, and expiry should be considered. |
| SEO/publication | Slugs, article metadata, canonical/indexing, TOCs, and downloadable content are implied | Publication metadata, canonical behavior, sitemap/indexing, and structured data may matter. |
| Workflow | Careers, testimonials, guides, partner content, and claims may require review | Draft/review/publish, moderation, revision, and claim verification are possible needs. |

The archive does not justify designing a generic page builder, a universal content block system, or a single CMS model for every concept. Future discovery should begin with the smallest set of domain content types needed by validated use cases.

## 15. Content opportunities

Concrete content topics represented in the archive include:

- Boarding expectations, daily routine, safety, species-specific environments, and what to bring.
- Grooming benefits, coat care in Abuja’s climate, nail/ear/skin observations, de-shedding, and grooming preparation.
- Training programme expectations, puppy socialization, obedience, behavior support, delivery modes, and trainer context.
- Vet care topics including preventative care, vaccination, microchipping, diagnostics, dental health, and when to seek help.
- Pet relocation documentation, export/import permits, vaccination/titer requirements, travel crates, airline considerations, heat restrictions, and timelines.
- Local transport preparation, safety, handoff expectations, route limitations, and arrival communication.
- Pet health, nutrition, exercise, parasite prevention, emergency guidance, breed information, and new-pet checklists.
- Product education around carriers, grooming tools, food, collars, beds, and enrichment.
- Privacy, cookie, cancellation, payment, liability, and service-scope explanations.
- Careers, staff culture, partnerships, and facility information.

These are content opportunities indicated by the archive, not an endorsement of every title, date, named person, regulation statement, or claim appearing in it.

## 16. Trust and credibility opportunities

The archive suggests several trust mechanisms worth evaluating:

- Explain the care process and safety standards at the point of service selection.
- Show verified specialist roles and qualifications where permission and current facts exist.
- Use service-specific testimonials rather than only generic praise.
- Explain what “custom quote” means and which variables affect it.
- Publish transparent preparation requirements, operating hours, contact routes, and escalation guidance.
- Use authentic facility/gallery media with captions and meaningful alternative text.
- Identify partner relationships only with current consent and a clear relationship status.
- Distinguish business guarantees from general design language.
- Add provenance or review ownership for statistics, credentials, regulatory references, and expert claims.

The archive includes claims such as “24/7 vet”, “500+ families”, “100%”, “NAQS approved”, named doctors/trainers, global standards, guarantees, and specific partner organizations. These must not be carried forward as current Waggies facts without business verification.

## 17. Commercial and conversion opportunities

The archive repeatedly uses these action concepts:

- Request a quote.
- Book a service or stay.
- Contact a specialist.
- Schedule a pickup.
- Get a free assessment.
- Use a pricing estimate.
- Start a relocation checklist.
- Call, email, WhatsApp, or get directions.
- Add products to a cart.
- Subscribe to educational or promotional updates.
- Join a loyalty programme.
- Redeem rewards.

Current Waggies already has strong quote/contact intent patterns, a pricing/calculator route, service and relocation routes, shop/cart behavior, newsletter/testimonial submissions, and a public loyalty page. The main discovery question is whether future conversion should remain enquiry-led or expand into authoritative booking, availability, payment, tracking, and account workflows.

## 18. Information architecture observations

### Useful observations

- The archive consistently groups services into boarding, wellness, and relocation, while current Waggies has a similar service navigation hierarchy.
- Boarding benefits from a category-to-species relationship rather than a flat list.
- Relocation benefits from an explicit landing page with import, export, local transport, and preparation tools connected together.
- Editorial content is more useful when related services, tools, and next actions are visible near the article.
- Knowledge-base content is presented as a utility/search experience, while guides are presented as in-depth editorial content. Current Waggies already keeps these domains separate; this distinction is worth preserving unless future evidence argues otherwise.
- About, proof, facilities, team, testimonials, and partnerships are treated as supporting decision content rather than isolated corporate pages.
- Footer links repeat the main service/company/legal groupings, making them useful fallback discovery paths.

### Cautions

The archive contains alternate navigation labels and duplicated editorial concepts. It does not prove a new route hierarchy is required. Current named routes and publication inventory remain authoritative; these observations should inform future content linking, not trigger route changes.

## 19. Mobile and responsive observations

The screenshots are narrow, tall captures and the HTML includes responsive classes, hidden desktop/mobile elements, sticky headers, fixed action areas, responsive grids, and mobile-oriented controls. The underlying UX observations are:

- Mobile users need an immediately visible primary action for quote/contact/service selection on long pages.
- Navigation needs a compact mobile disclosure or drawer with the same semantic destinations as desktop.
- Dense pricing/package comparisons need a readable mobile ordering, not simply a compressed desktop grid.
- Service preparation and process content should remain scannable on small screens.
- Article TOCs and filters need a mobile-friendly way to open, close, and return to content.
- Forms should minimize simultaneous fields and maintain clear grouping for origin/destination, pet type, service type, and contact details.
- Gallery and product grids need touch-sized controls and predictable return behavior.

No exact archive breakpoint, width, spacing, or mobile visual composition should be copied. The current Waggies responsive architecture remains authoritative.

## 20. Accessibility observations

Potential requirements suggested by the archive include:

- Meaningful heading hierarchy for long service, article, and policy pages.
- Native, keyboard-operable disclosure controls for FAQs and package details.
- Visible focus and correct focus return for mobile menus, filters, galleries, and dialogs.
- Clear labels and error relationships for quote, contact, checklist, training, newsletter, and partnership forms.
- Accessible names and state communication for selected package, pet type, currency, filter, and loyalty controls.
- In-page TOCs that remain usable by keyboard and screen-reader users.
- Useful alt text, captions, and context for facility, staff, pet, product, and gallery media.
- Reduced-motion handling for hover/scale/accordion transitions.
- Adequate touch targets and readable contrast for sticky mobile actions.
- Print/PDF outputs that retain document structure if those features are ever implemented.

The prototype is not evidence that these concerns were solved correctly. The current Waggies accessibility rules and tested component contracts remain the standard. In particular, the current architecture audit already identifies global overlay focus lifecycle as a separate concern; the archive does not change that priority or authorize fixing it here.

## 21. Duplicate and contradictory prototype concepts

### Repeated concepts

- **Blog/editorial:** `blog_category/listing_page`, `waggies_blog_page`, and `blog_post_content_page` overlap with current guides and knowledge base. Repetition indicates exploration, not a requirement for a third content domain.
- **Guides:** `guides_category/listing_page`, `guides_section_page`, and `guide_content_page` represent alternate listing/detail treatments of similar editorial content.
- **Knowledge base:** `knowledge_base_page`, `knowledge_base_category/listing_page`, and `knowledge_base_article_page` represent a hub/category/article model.
- **Partnerships:** `partnerships_page` is a proposition/form page; `stitch_waggies_partnership page list` is a partner directory. They may be different page states or separate explorations, not necessarily two required pages.
- **Services:** `services_overview_page` and `waggies_homepage` both summarize the service catalogue, with different emphasis and visual systems.
- **Relocation:** landing, import, export, transport, guide, and checklist exports collectively explore the same complex journey from different angles.
- **Proof:** home, about, services, boarding, training, vet, testimonials, gallery, and partnerships all use proof, people, or facility content differently.

### Contradictions and variations

- Navigation labels vary between Blog, Guides, Resources, and Knowledge Base.
- Visual identity varies substantially in font, palette, density, and button style.
- Some pages imply direct booking; others use quote/contact language.
- Some service pages imply fixed packages and transparent rates; others imply custom quotes.
- Trust language ranges from cautious explanatory content to strong guarantees, statistics, credentials, and “global” claims.
- Some exports imply live updates, dashboards, or tracking, while others are purely informational.
- Legal pages imply login and consent tooling that is not otherwise present as a working product.

## 22. Negative findings

The following should probably not be carried forward automatically:

- A separate blog domain solely because two blog-like exports exist; current guides and knowledge base already cover editorial needs differently.
- Placeholder login, account, dashboard, loyalty, or booking experiences without an operational reason and a real service owner.
- Live webcam, GPS tracking, real-time updates, and “track the ride” unless Waggies can operate and support the underlying systems.
- Precise statistics, guarantees, “100%” statements, named partner logos, credentials, and specialist profiles without verification, consent, or expiry ownership.
- Static prototype prices or package values; current server-owned pricing remains authoritative.
- Generic “luxury/premium/global standards” language when it does not explain a verifiable customer benefit.
- Comments on articles unless moderation, spam control, privacy, and support value are established.
- A universal filter/search system merely because the archive shows controls; filtering is valuable only when content volume and taxonomy justify it.
- Arbitrary downloadable PDFs when the source content is not stable, maintained, accessible, or operationally useful.
- Exact visual patterns, breakpoints, font pairings, card layouts, animations, or navigation markup from the archive.
- Treating `href="#"`, visual buttons, and static forms as functioning booking, application, payment, or subscription systems.

## 23. Candidate ideas for product validation

### Clearly worth evaluating

- Improve the service-to-preparation-to-enquiry journey, especially for boarding and relocation.
- Make related guides, FAQs, tools, and services more visible at decision points.
- Clarify service/package differences and fixed-price versus custom-quote boundaries.
- Expand verified, service-specific safety and expectation content.
- Identify which relocation documents or checklists genuinely need printable/downloadable outputs.
- Strengthen searchable/filterable knowledge content if current content volume and user questions justify it.

### Worth discussing during product planning

- Whether service-specific provider/facility profiles materially improve trust and conversion.
- Whether a service-area/operating-information content model would reduce repeated contact questions.
- Whether shop filters, category pages, and related products become necessary as catalogue size grows.
- Whether a verified partner directory supports a real partnership strategy.
- Whether a controlled quote-status workflow would improve follow-up without becoming a booking system.
- Whether loyalty has a viable commercial model and a sufficiently frequent repeat-service base.

### Weak or uncertain

- Blog as a separate third editorial domain.
- Article comments.
- Public login and dashboard.
- Live boarding webcam.
- GPS transport tracking.
- Real-time daily updates as a product promise.
- Direct scheduling and availability.
- Large statistics and guarantee bands.
- Currency switching beyond the current commercial requirements.

### Prototype-only or reject unless new evidence appears

- Unverified claims, named people, logos, and partner relationships.
- Placeholder account and booking flows.
- Decorative premium/luxury patterns without a user problem.
- Static “load more” or filter controls without a real content/data source.
- Copying the archive’s visual system or URL assumptions.

## 24. Current Waggies KEEP / ADAPT / REPLACE / REMOVE mapping

This is a planning classification, not an implementation instruction.

| Current area | Planning classification | Reason |
| --- | --- | --- |
| Current Blade-first public rendering and Alpine-first interaction model | KEEP | It is an explicit current architectural decision and is more authoritative than the static export. |
| Current semantic design-system primitives and reusable Waggies components | KEEP | The archive does not provide a stable replacement system. |
| Current service route hierarchy, including boarding species and relocation variants | KEEP | It matches the strongest product structure in the archive and is already implemented. |
| Current server-authoritative pricing and calculator contract | KEEP | Prototype prices are static and untrusted; current commercial values remain authoritative. |
| Current guides/knowledge-base distinction | KEEP, then ADAPT selectively | The archive reinforces two useful content modes but also contains redundant blog variants. |
| Current contact intent and submission flow | KEEP | The archive’s many CTAs ultimately point to an enquiry need; current server acceptance is more reliable than prototype forms. |
| Current gallery, testimonials, proof, careers, partnerships, and loyalty page concepts | KEEP | They are already represented; enrich only with verified content and validated capabilities. |
| Current shop/cart foundation | KEEP, ADAPT selectively | Consider catalogue/filter content needs if real product volume grows. |
| Current FAQ and article structures | ADAPT selectively | Add relationships, preparation content, or filters only where validated. |
| Current relocation checklist/tool content | ADAPT selectively | Evaluate generated/downloadable outputs and journey linking; do not assume a wizard/backend is required. |
| Current static/config-backed editorial content | ADAPT only after content-model discovery | The archive suggests relationships and publication concerns, not an automatic CMS migration. |
| Prototype blog as a new route/domain | REPLACE with current editorial model | It overlaps current guides and knowledge base without enough evidence for a third domain. |
| Prototype account, booking, tracking, and loyalty runtime concepts | DO NOT ADOPT; validate first | These are capabilities, not presentation upgrades, and require substantial operational ownership. |
| Prototype visual layouts, fonts, and exact interaction patterns | REMOVE from authority | They are exploratory exports and not the current design system. |

## 25. CMS discovery implications

The archive teaches that future CMS discovery should investigate five separate layers rather than create a generic page builder by default.

1. **Editorial content:** guides, knowledge articles, FAQs, service preparation, policy sections, testimonials, and explanatory copy.
2. **Structured domain content:** services, variants, packages, inclusions, preparation requirements, provider profiles, facilities, locations, partner relationships, and product metadata.
3. **Reusable relationships:** article-to-service, article-to-tool, service-to-FAQ, service-to-testimonial, service-to-provider, and related-content links.
4. **Application-owned data:** pricing calculations, availability, bookings, accounts, loyalty ledgers, tracking, notifications, carts, orders, and moderation state. These should not be inferred from editorial layouts.
5. **Publication and governance:** slugs, metadata, canonical/indexing decisions, sitemap inclusion, structured data, drafts, review, revision, scheduling, consent, provenance, expiry, and claim verification.

Media discovery should cover authentic photography, staff images, facility/gallery media, product media, downloadable documents, alt text, captions, licensing, conversions, and storage ownership. The archive’s remote image URLs are not a production media policy.

The most important CMS lesson is that repeated visual sections do not necessarily imply one reusable content block. First establish whether the underlying responsibility is editorial, structured domain data, runtime logic, or media. Then decide how much reuse is warranted.

## 26. Recommended questions for the next Waggies product/CMS session

1. Is the intended primary conversion still a quote/contact request, or does Waggies want true booking and availability?
2. Which services have real fixed packages and which must remain custom quote workflows?
3. What boarding, grooming, vet, training, and relocation preparation questions do staff answer repeatedly?
4. Which archive claims, credentials, people, facilities, partners, statistics, and guarantees can be verified and maintained?
5. Which content belongs in guides versus the knowledge base versus service pages?
6. Do users need a third blog domain, or can current guides and knowledge base cover the content jobs?
7. Which articles, checklists, or policies genuinely need downloadable or printable outputs?
8. Does content need pet-type, service, topic, location, audience, author, or lifecycle taxonomies?
9. Which relationships must editors curate: related services, tools, articles, FAQs, testimonials, providers, or products?
10. What review, consent, moderation, expiry, and provenance rules apply to testimonials, photos, credentials, and partner logos?
11. Is there a real operational owner for booking, customer accounts, loyalty, tracking, notifications, and support?
12. If loyalty is desired, what earns points, what redeems them, what is the ledger authority, and what are the financial limits?
13. If transport tracking or boarding updates are desired, what external systems and customer-support commitments are required?
14. What service areas, hours, emergency paths, and location facts are stable enough to publish as structured data?
15. Which shop filters and categories are needed now, and which should wait for catalogue scale?
16. What publication and SEO rules should apply to guides, knowledge articles, tools, policies, downloadable documents, and service variants?
17. Which current config-backed content should remain code-owned, and where would editorial editing produce real value?
18. What accessibility and mobile requirements must be preserved when richer filters, dialogs, downloads, or forms are introduced?

## 27. Final conclusion

The archive adds useful understanding in four areas:

- Waggies has repeatedly explored a service-led information architecture built around boarding, wellness, relocation, proof, education, and enquiry.
- Relocation is best understood as a connected journey of import/export/transport, documents, preparation, education, and expert assistance rather than isolated pages.
- The most promising product ideas are clearer preparation content, service/package explanation, related-content relationships, verified trust content, and selectively improved search/filter/download behavior.
- The archive also reveals tempting but high-cost concepts—accounts, loyalty ledgers, live tracking, webcams, direct booking, checkout, comments, and real-time updates—that require product and operational decisions rather than CMS styling.

Current Waggies already covers most page families and core conversion paths. The report therefore points more toward targeted content and relationship discovery than wholesale page restoration. The strongest future work should preserve the current route/design-system/application authority, validate real customer and operational needs, and distinguish editorial content from structured domain data and runtime capability.

The archive should not be carried forward as a requirements document. Its visual decisions, placeholder links, static prices, unverified business claims, alternate blog structures, and implied operational systems should remain archival unless separately validated.

## Audit boundary and verification

- No current application code was modified.
- No current routes or URLs were modified.
- No dependencies, configuration, database schema, migrations, CMS resources, or public behavior were modified.
- `WAGGIES-ARCHITECTURE-AUDIT.md` was pre-existing and remains untouched by this audit.
- The only intentionally created current-project artifact is this document.
- No commit or push was performed.
