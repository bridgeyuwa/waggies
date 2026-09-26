---
paths:
  - 'app/**'
  - 'bootstrap/**'
  - 'config/**'
  - 'database/**'
  - 'resources/**'
  - 'routes/**'
  - 'tests/**'
  - 'public/**'
  - composer.json
  - package.json
  - config/waggies_pricing.php
---

# Waggies Laravel — Reuse Before Reinventing

Waggies follows a **reuse-before-reinventing** principle.

When a new requirement appears, prefer established, well-maintained infrastructure over building generic functionality from scratch.

This is an **open-ended discovery rule**, not a finite package whitelist.

The examples and currently adopted packages documented below are known architectural choices, not the complete universe of solutions that may be appropriate for Waggies in the future.

## 1. Core principle

Before building custom generic infrastructure, investigate solutions in this order:

```text
1. Laravel's native capabilities
2. Existing Waggies capabilities and dependencies
3. The broader Laravel ecosystem
4. The broader Spatie ecosystem
5. Other mature, well-maintained ecosystem packages/libraries
6. A small Waggies-specific implementation
7. A larger custom infrastructure system only when genuinely necessary
```

Do not stop the investigation at any one ecosystem.

Do not assume Spatie is always the answer.

Do not assume an installed package is the best answer merely because it is already installed.

Do not assume Laravel itself is sufficient merely because a capability technically exists in the framework.

Choose based on the actual requirement, quality of fit, maintenance, complexity, and architectural consequences.

---

# 2. DISCOVER BY PROBLEM DOMAIN

When a requirement appears, investigate the **problem**, not just a guessed class or package name.

For example, if Waggies needs:

```text
business opening hours
special opening times
temporary closures
holiday closures
service operating schedules
```

do not immediately invent:

```text
BusinessHoursService
HolidayService
CalendarService
OpeningHoursManager
```

First investigate what Laravel, Spatie, and the broader ecosystem already provide.

The investigation should consider whether there are separate solutions for:

* opening-hours calculations
* public-holiday calculations
* calendar events
* calendar rendering
* calendar synchronization
* calendar exports
* booking/availability
* scheduling

These are different problems and should not be conflated.

---

# 3. SPATIE MUST BE INVESTIGATED, NOT ASSUMED

Spatie has a large and evolving ecosystem.

Whenever a generic requirement appears, consider whether one or more current Spatie packages address it, even if the package is not currently installed in Waggies and has never previously been mentioned.

For example, calendar-related requirements may lead to investigation of different Spatie packages addressing different concerns, such as opening hours, holidays, Google Calendar integration, calendar links, iCalendar generation, or calendar-month utilities.

Do not assume that a package exists, is maintained, supports the current Waggies stack, or solves the exact problem.

Verify it.

Do not assume that one package covers an entire problem domain merely because its name sounds relevant.

---

# 4. THE SEARCH SPACE IS NOT FINITE

The following are **examples of current Waggies package choices**, not an exhaustive list:

```text
laravel/head
spatie/schema-org
diglactic/laravel-breadcrumbs
spatie/laravel-sitemap
filament/filament
spatie/laravel-medialibrary
spatie/laravel-permission
spatie/laravel-activitylog
spatie/laravel-model-states
spatie/laravel-data
spatie/laravel-sluggable
spatie/laravel-backup
spatie/laravel-health
laravel/horizon
```

Future work must still investigate whether:

* Laravel has added a native capability,
* a newer package is better,
* an existing package has become obsolete,
* another package addresses the requirement more appropriately,
* a package should be added to Waggies,
* or custom code remains the better choice.

Never treat this list as a permanent boundary.

---

# 5. CURRENT PACKAGE OWNERSHIP

The following are current Waggies architectural directions.

## SEO/head metadata

Use:

```text
laravel/head
```

as the preferred mechanism for document-head/SEO metadata.

Do not build a competing metadata framework when Laravel Head satisfies the requirement.

---

## Structured data

Use:

```text
spatie/schema-org
```

where it is the appropriate structured-data builder.

Do not repeatedly hand-build Schema.org structures when the package can represent them cleanly.

However, if Laravel or another mature solution later provides a better fit for a particular requirement, evaluate that objectively.

---

## Breadcrumbs

Use:

```text
diglactic/laravel-breadcrumbs
```

for breadcrumb infrastructure where appropriate.

Do not create a second breadcrumb framework.

Where breadcrumb data is also used for structured data, ensure ownership is deliberate and duplicate/conflicting output is avoided.

---

## Sitemaps

Use:

```text
spatie/laravel-sitemap
```

for generic sitemap mechanics where appropriate.

The package should not define which Waggies resources are publishable or indexable. That remains an application/content decision.

---

## Administration and CMS

Use:

```text
filament/filament
```

as the Waggies administration/CMS foundation.

Do not create a second generic CMS/admin framework without a specific architectural reason.

Future packages may still complement Filament where an established integration provides meaningful value.

---

## Media

Use:

```text
spatie/laravel-medialibrary
```

for generic model-associated media management when appropriate.

Evaluate official integrations with Filament and other current Waggies infrastructure before writing custom glue code.

The package does not define Waggies' domain concepts for photos, documents, attachments, or records.

### Asset reuse must be evidence-based

The reuse-before-reinventing principle does not require visual assets to be reused when doing so would obscure ownership, purpose, crop, or visual meaning.

Before consolidating image assets, establish:

```text
asset → references → owner/purpose → canonical location → public URL risk
```

Reuse one physical asset when it is genuinely the same source asset and its uses share the same visual role. Keep separate files when an asset is entity-owned, purpose-specific, differently cropped, or intentionally distinct, even if the files look similar or reuse would reduce file count.

Do not force unrelated assets into a shared `editorial`, `shared`, or page directory merely to satisfy reuse. Do not create page-specific copies merely because one asset appears on multiple pages. Organize by ownership and domain, preserve legitimate existing assets, and make the smallest coherent change that improves maintainability without casually breaking public URLs, seeded content, or visual parity.

---

## Roles and permissions

Use:

```text
spatie/laravel-permission
```

for the established Waggies authorization infrastructure where appropriate.

Do not create a parallel generic permission system.

---

## Audit/activity logging

Use:

```text
spatie/laravel-activitylog
```

for generic audit/activity logging where appropriate.

Do not build a competing generic activity-log framework merely to record model changes.

Distinguish generic audit infrastructure from Waggies-specific business history.

---

## State management

Evaluate:

```text
spatie/laravel-model-states
```

for models that genuinely require meaningful state behavior and controlled transitions.

Do not allow the package to determine what Waggies states or lifecycle rules should exist.

The domain defines the meaning and validity of transitions.

---

## Data objects

Evaluate:

```text
spatie/laravel-data
```

when typed application data objects materially improve clarity or boundaries.

Do not introduce DTOs mechanically for every model or trivial operation.

---

## Slugs

Evaluate:

```text
spatie/laravel-sluggable
```

for database-backed resources with stable slugs.

Waggies' public URL policy takes precedence over package convenience.

---

## Backups and health

Evaluate:

```text
spatie/laravel-backup
spatie/laravel-health
```

for production backup and application-health infrastructure.

Do not create custom generic backup or health frameworks when established solutions meet the requirement.

---

## Queue operations

Use Laravel's queue infrastructure and evaluate:

```text
laravel/horizon
```

when Redis queue monitoring/worker management is needed.

Do not create a custom queue dashboard unnecessarily.

---

# 6. BUSINESS DOMAIN IS DIFFERENT

Reuse generic infrastructure aggressively.

Do **not** outsource Waggies-specific business meaning merely to avoid writing code.

Examples:

```text
Generic infrastructure:
    opening-hour calculations
    media storage
    audit logging
    queue monitoring
    schema serialization
    sitemap generation
```

versus:

```text
Waggies business/domain behavior:
    what constitutes a valid boarding booking
    when a pet may check in
    which pets require special handling
    what services can overlap
    how boarding capacity is allocated
    how quotes are calculated
    when a booking may be cancelled
    what staff may approve
```

Packages should support the second category, not dictate it.

---

# 7. EXAMPLE: BUSINESS HOURS AND HOLIDAYS

Suppose Waggies later requires staff to manage business hours through Filament and display them on:

* Contact
* Footer
* About
* service pages
* structured data

with support for:

* normal weekly hours
* one-off closures
* public holidays
* special holiday hours
* temporary closures
* possibly service-specific schedules

The agent must investigate the available ecosystem before inventing custom infrastructure.

For example, a package such as `spatie/opening-hours` may be relevant to opening-hour calculations, while another package may handle public-holiday data.

That does **not** mean Waggies should blindly adopt those packages as its domain model.

A possible architecture could instead be:

```text
Filament
    ↓
Waggies-managed operating schedule data
    ↓
appropriate scheduling/opening-hours library
    ↓
customer-facing presentation
```

The actual data model, permissions, override rules, service-specific behavior, and customer-facing messaging remain Waggies decisions.

This is an example of the discovery principle, not a prescribed permanent implementation.

---

# 8. COMPOSE FOCUSED SOLUTIONS

A Waggies requirement may be better served by several focused tools than by one large package.

For example:

```text
Filament
+
Waggies-managed schedule data
+
opening-hours library
+
holiday library
```

may be preferable to one enormous "business calendar" package.

Likewise:

```text
Filament
+
Spatie Media Library
+
official Filament integration
```

may be preferable to custom file-management code.

Choose composition when it keeps responsibilities clear.

---

# 9. EVALUATE PACKAGE FIT, NOT PACKAGE POPULARITY

For any candidate package, evaluate:

```text
Problem fit
Laravel compatibility
PHP compatibility
Maintenance activity
Release health
Documentation
Community/ecosystem quality
API quality
Upgrade burden
Database/schema implications
Architectural coupling
Testing/support maturity
Security history where relevant
Ability to remove or replace it later
```

Do not select a package simply because:

* it has many GitHub stars
* an AI model recognizes it
* a tutorial uses it
* it is made by a familiar vendor
* it is the first search result
* it eliminates a few lines of code

---

# 10. CURRENT INSTALLED PACKAGE VERSUS BETTER FUTURE PACKAGE

An installed package is not automatically sacred.

If a future requirement exposes that another mature solution is materially better, evaluate the alternatives objectively.

Likewise, do not replace an established package merely because a newer package exists.

Changing dependencies has cost.

The correct question is:

> Which solution is the best fit for the current Waggies requirement and architecture?

Not:

> Which package is newest?

---

# 11. DO NOT ADD PACKAGES SPECULATIVELY

Do not install packages solely for anticipated future features.

Install a dependency when:

* the capability is actually being implemented, or
* establishing the dependency early is itself an explicit architectural decision.

Do not accumulate unused packages "just in case."

---

# 12. DO NOT WRAP PACKAGES FOR NO REASON

Do not create:

```text
SeoManager
MediaManager
CalendarService
SitemapManager
BreadcrumbService
AuditService
PermissionManager
```

solely to forward calls to another package.

A Waggies wrapper is justified only when it creates a genuine application boundary or expresses Waggies-specific behavior.

---

# 13. DO NOT LET PACKAGES DICTATE THE DOMAIN

Never infer Waggies' domain from package structure.

A package's:

* models
* migrations
* traits
* terminology
* APIs
* lifecycle
* configuration

must not automatically become Waggies' domain model.

For example:

```text
Opening-hours package
    ≠ Waggies business-hours domain

Calendar package
    ≠ Waggies availability domain

Booking package
    ≠ Waggies Booking domain

Media Library
    ≠ Waggies PetDocument domain

Activitylog
    ≠ Waggies business-history domain

Filament Resource
    ≠ Waggies domain entity
```

---

# 14. DO NOT REINVENT GENERIC INFRASTRUCTURE INSIDE DOMAIN CODE

Conversely, domain code should not reimplement generic capabilities unnecessarily.

Avoid writing a custom Waggies implementation of:

* image conversion
* generic file attachment management
* sitemap XML generation
* Schema.org serialization
* breadcrumb registration
* generic audit logging
* queue monitoring
* generic backup routines
* generic role/permission infrastructure

when a suitable established solution already exists.

---

# 15. DO NOT USE PACKAGES TO AVOID THINKING

"There's a package for it" is not itself a reason to install or use one.

A package is inappropriate when it:

* distorts the domain
* introduces excessive complexity
* imposes unnecessary tables
* creates heavy coupling
* solves a much larger problem than Waggies has
* is poorly maintained
* is incompatible with the current stack
* is harder to understand than a small local implementation

Sometimes the correct answer is simply:

```text
A small Waggies-specific implementation.
```

That is not a failure of the reuse principle.

---

# 16. PACKAGE DISCOVERY IS PART OF THE ENGINEERING PROCESS

Whenever a new generic problem is identified, the agent should investigate before implementing.

The investigation should be proportional to the problem.

For a trivial problem, a quick Laravel/package check may be enough.

For an architectural capability, perform a broader ecosystem investigation.

Do not blindly implement the first solution that comes to mind.

---

# 17. WHEN USING AI/CODE AGENTS

Codex and other AI agents must not invent infrastructure prematurely.

Before creating a new:

```text
package
service
repository
manager
helper
framework
generic abstraction
```

for a generic concern, check:

```text
Laravel
↓
installed Waggies packages
↓
Spatie ecosystem
↓
broader Laravel ecosystem
↓
other mature ecosystem solutions
```

The agent should not rely solely on its prior training or memory of package names.

For package-selection decisions that may have changed over time, verify the current package/documentation status before implementation.

---

# 18. NEW PACKAGE PROPOSAL

When a new dependency is genuinely warranted, document briefly:

```text
Problem:
What generic capability is required?

Laravel:
What native capabilities were considered?

Existing Waggies packages:
What was already available?

Spatie:
What relevant packages were investigated?

Broader ecosystem:
What relevant alternatives were investigated?

Decision:
Which solution was selected and why?

Waggies-specific responsibility:
What remains owned by Waggies?
```

Do not turn this into paperwork for every trivial dependency.

Use deeper analysis when the architectural or dependency impact is significant.

---

# 19. OPEN-ENDED EXPLORATION

This rule must remain valid even when the relevant package does not yet exist.

The agent may discover:

* a newer Laravel first-party capability
* a new Spatie package
* a new official Filament plugin/integration
* a mature Laravel ecosystem package
* a framework-independent PHP library
* an official provider SDK
* a better-established alternative from another ecosystem

Such discoveries should be evaluated on their merits.

Do not limit investigation to the packages named in this document.

---

# 20. FINAL PRINCIPLE

> **Reuse mature generic infrastructure whenever it is the right fit, but keep Waggies' domain and business behavior under Waggies' control.**

More specifically:

```text
Laravel first.
Then investigate Waggies' existing capabilities.
Then investigate Spatie and the wider Laravel ecosystem.
Then investigate other mature solutions.
Then choose the smallest architecture that properly solves the requirement.
Only reinvent generic infrastructure when there is a demonstrated reason.
```

The package ecosystem is a **search space, not a whitelist**.

The current package list is an architectural baseline, **not a closed catalogue**.

Waggies should continuously prefer the best appropriate solution available at the time a requirement is implemented, while avoiding unnecessary dependency churn and preserving clear domain ownership.

## Post-conversion Laravel source of truth
After the conversion gate has passed, the current standalone Laravel implementation is the sole normative source for Waggies development. Historical implementations are archival and may only be consulted for explicit investigation of a known legacy behavior; they do not set current components, layout, spacing, architecture, naming, interactions, markup, or visual composition.

## Audit before consolidation
Standardize genuine semantic and behavioral responsibilities, but do not consolidate merely because implementations look similar. Before merging or abstracting, establish responsibility, usage, variation, dependencies, edge cases, and API requirements; then choose a shared implementation, deliberate variant, primitive/composition, or separate implementation. Avoid mega-components and boolean-branch-heavy abstractions.

## Keep service pricing configuration-backed
Do not move Waggies service pricing into Filament/CMS merely because staff-editable prices are technically possible. Pricing inputs participate in application-owned calculations and relationships; keep service pricing in version-controlled configuration unless a future product requirement materially changes this architecture.
