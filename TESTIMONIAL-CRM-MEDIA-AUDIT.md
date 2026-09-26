# Testimonial CRM, Verification, and Media Audit

## Scope and evidence

This audit follows the previous field cleanup and traces the remaining
testimonial contact, CRM, verification, publication, and media responsibilities
through the model, historical/forward migrations, public submission endpoint,
Filament resource, SuiteCRM integration, seed data, Blade/Alpine components,
search/sitemap code, and focused tests.

The current local PostgreSQL database contains 13 testimonial records:

| Evidence | Count |
| --- | ---: |
| testimonial records | 13 |
| records with `contact_method` | 2 |
| records with `contact_value` | 2 |
| records with `suitecrm_record_id` | 0 |
| records with `crm_match_status` | 13 |
| records with `identity_verification_status` | 13 |
| records with `customer_relationship_status` | 13 |
| Spatie testimonial media records | 0 |

The current CRM state is 11 `matched` and 2 `not_found`, all with verified
identity/relationship values and approved status. Those values are primarily
development-seeder/application-state data; they do not demonstrate that
Waggies has a live requirement that every testimonial must be an existing CRM
customer.

## Dependency matrix

| Component | Current purpose | Consumers | Required by current product? | Recommendation |
| --- | --- | --- | --- | --- |
| `contact_method` | Captures a phone/email/WhatsApp selector from the public submission | `TestimonialController`; `VerifyTestimonialCustomer` | No independent consumer; it exists to support the CRM lookup | REMOVE |
| `contact_value` | Captures a customer contact value from the public submission | `TestimonialController`; `VerifyTestimonialCustomer` | No independent testimonial requirement after CRM removal | REMOVE |
| `crm_match_status` | Stores SuiteCRM lookup outcome | `VerifyTestimonialCustomer`; `Testimonial::published()`; Filament form/table | No demonstrated current product requirement; only the manually invoked CRM workflow | REMOVE |
| `identity_verification_status` | Stores a manual verification state | `Testimonial::published()`; Filament form; contract test | No producer or independent business process; only the old CRM/publication coupling | REMOVE |
| `customer_relationship_status` | Stores a manual Waggies relationship state | `Testimonial::published()`; Filament form; contract test | No producer or independent business process; only the old CRM/publication coupling | REMOVE |
| `suitecrm_record_id` | Stores an external customer identifier after a CRM match | `VerifyTestimonialCustomer`; Filament form | No local record has a value and no other domain consumes it | REMOVE |
| `VerifyTestimonialCustomer` | Manually calls SuiteCRM from a Filament row action and writes CRM status | `TestimonialsTable` only | No automatic submission, queue, scheduled job, or public workflow invokes it | REMOVE |
| `SuiteCrmClient::findCustomers()` | Calls `GET /customers/search` when SuiteCRM environment settings exist | `VerifyTestimonialCustomer` only | Not required by another current Waggies domain | REMOVE |
| `SuiteCrmVerificationStatus` | Enumerates CRM lookup outcomes | `VerifyTestimonialCustomer` only | Exists only for the removable testimonial workflow | REMOVE |
| `services.suitecrm` config | Supplies base URL/token/timeout to the testimonial CRM client | `SuiteCrmClient` only; unrelated Loyalty copy is informational and does not use the client | No remaining code-level integration consumer after testimonial removal | REMOVE |
| `status` | Controls pending/approved/rejected/archived publication state | Model saving guard/scope; Filament form/table; public controllers | Yes; pending submissions must remain private and staff need a publication decision | KEEP |
| `consented_at` | Records the public-use consent required at submission | `TestimonialController`; publication eligibility | Yes; consent is a real publication/legal gate | KEEP |
| `published_at` | Stores publication time and supports scheduled/future visibility | Model saving guard/scope; public queries; Filament read-only field | Yes; it is the current publication timestamp/scheduling control | KEEP |
| `sort_order` | Controls editorial display order | `AboutPagesController`; `HomeController`; Filament reorderable table | Yes; both public consumers order testimonials by it | KEEP |
| `photo` Spatie collection | Optional testimonial upload and thumbnail conversion | Controller upload; Filament upload/table; dead model mapper; photo-specific tests | No; the public testimonial grid and home spotlight do not render it, and local usage is zero | REMOVE |
| `publicPhotoUrl()` | Returns the unused Spatie thumbnail URL | `Testimonial::toPublicArray()` and Filament table | No after removing the dead media feature | REMOVE |
| `Testimonial::toPublicArray()['photo']` | Exposes a photo value in the public DTO | No Blade consumer; `testimonials-grid` renders content fields only | No | REMOVE |

## CRM verification decision

### Actual workflow

The current relationship is:

```text
public testimonial form
    -> stores contact_method/contact_value
    -> staff may click "Verify CRM" in the Filament table
    -> SuiteCrmClient searches /customers/search
    -> action writes crm_match_status/suitecrm_record_id
    -> publication scope also requires three verification values
```

This is not a normal submission workflow. `TestimonialController` does not
invoke the verifier, dispatch a job, or require a CRM lookup. There is no
scheduled or queued testimonial verification path. The only producer of CRM
match outcomes is the manually invoked Filament action, while the identity and
relationship statuses have no producer outside Filament editing and seed/test
data. `SuiteCrmClient` has no consumer outside that action.

The configured endpoint is optional: when `SUITECRM_BASE_URL` or
`SUITECRM_TOKEN` is absent, the client returns an empty match list. The local
database has no SuiteCRM record IDs. The public submission accepts any supplied
contact detail and does not establish a Waggies customer identity.

There is no current product requirement in the inspected code, public copy,
submission contract, or operational workflow that testimonials must come from
existing SuiteCRM customers. A legitimate public testimonial can therefore be
published after the existing consent and staff publication decision without
CRM matching. Retaining the CRM chain would preserve infrastructure because it
exists, not because the current Waggies product needs it.

### Three verification fields

The fields are not independent current product capabilities:

- `crm_match_status` has a real technical producer and consumer, but only in
  the optional manual CRM action and its self-imposed publication gate.
- `identity_verification_status` has no application producer and no separate
  decision path; it is merely a Filament-editable prerequisite for publication.
- `customer_relationship_status` has no application producer and no separate
  decision path; it duplicates the same prerequisite concept under another
  label.

They therefore do not represent three required Waggies decisions. They are
removed together with the CRM action, contact fields, external ID, enum, and
publication coupling. No replacement customer or verification system is
introduced.

## Publication decision

The minimum current publication contract is:

- `status`: the single staff-controlled publication state. The existing
  pending/approved/rejected/archived values are retained because they are the
  current Filament moderation vocabulary; only `approved` records can publish.
- `consented_at`: a non-null consent timestamp remains required for public use.
- `published_at`: the existing timestamp and future-date check remain the
  publication timing mechanism.
- `sort_order`: public pages and the Filament table use it for editorial order.

The CRM and verification predicates are removed from `published()` and
`eligibleForPublication()`. The resulting rule is understandable and local:

```text
approved + consented + published_at absent or due
    -> public testimonial
```

No new moderation workflow or publication table is added.

## Image decision

### Public rendering audit

The public testimonial grid renders rating, story, author name, author
location, and service filtering. It does not read `photo` or the `photo` key
returned by the model mapper. The home spotlight likewise maps and renders
only service, quote, initial, name, and subtitle. No public card, hero, schema,
search document, canonical URL, or sitemap entry depends on testimonial media.

The photo is visible only in the submission form and Filament resource/table.
The design does not depend on it: all current public records render without
one, the local database has zero testimonial media records, and the previous
`photo_path` column was already removed with zero non-null values. The only
photo behavior tests exercise the optional upload itself.

There is no demonstrated business reason for administrators to upload a
testimonial photo. The current implementation is decorative infrastructure,
not required testimonial content.

### Removal scope

The implementation will remove only testimonial-specific media behavior:

- the public photo upload and file validation;
- the Filament media upload and image table column;
- `Testimonial`'s media interface/trait, collection, conversion, and
  `publicPhotoUrl()` method;
- the dead public DTO photo key;
- testimonial photo-specific tests and frontend state.

Spatie Media Library remains installed and unchanged for guides, knowledge
articles, gallery items, and other models that genuinely use it. No path
column or replacement media architecture is introduced.

## Required decisions

### Testimonial CRM

**REMOVE.** The only current use is a manual Filament action with no normal
submission, automated, or independent operational consumer. There is no local
SuiteCRM record linkage and no demonstrated requirement that testimonial
authors be CRM customers.

### Testimonial verification

Remove all three fields:

- `crm_match_status`
- `identity_verification_status`
- `customer_relationship_status`

Also remove `suitecrm_record_id`, the CRM action/client/enum, and the
verification-specific publication predicates.

### Testimonial contact data

Remove both `contact_method` and `contact_value`. They exist only to feed the
removed CRM lookup and are not needed for public rendering, consent, ordering,
or ordinary testimonial publication.

### Testimonial images

**REMOVE.** Images are not publicly rendered, have zero local media usage, and
have no demonstrated business requirement. Remove testimonial-specific media
usage only; preserve global Spatie Media Library behavior.

## Final minimal testimonial contract

The proposed final `testimonials` columns are:

```text
id
rating
service
story
author_name
author_location
status
consented_at
published_at
sort_order
created_at
updated_at
```

The public submission retains rating, service, story, author name, author
location, and consent. Publication retains the existing status, consent,
publication-time, and ordering behavior. No contact/customer/CRM fields,
verification fields, media relationship, photo upload, replacement table, or
new customer entity remains in the testimonial domain.

## Implementation completed

The decisions above were implemented with the forward migration
`2026_09_24_021710_remove_testimonial_crm_and_media_contract.php`. The model,
CRM/configuration references, Filament resource, public submission flow,
frontend form, seeder/factory, fixtures, and focused tests now match the
minimal contract. Historical migrations were not rewritten, and unrelated
SuiteCRM/product work was not changed.

The testimonial-only CRM action, enum, client, config block, contact and
verification columns, publication coupling, Spatie media interface/collection,
photo upload/table controls, public photo mapper, and photo tests were removed.
Spatie Media Library remains active for other models.

## Verification completed

- Focused testimonial/publication/admin tests: **24 passed, 110 assertions**.
- Full PHPUnit suite: **145 passed, 1,537 assertions**.
- PHPStan completed with no errors.
- Pint completed successfully after formatting the model imports/operators.
- Vite production build completed successfully.
- Forward migration applied successfully; the final schema contains 12
  columns: content, publication state, consent/publication timestamps,
  ordering, and normal system timestamps.
- Two consecutive `php artisan db:seed --no-interaction` runs completed without
  duplicate testimonial records; the local count remained 13 and the existing
  operational record remained present.
- Local testimonial media remained at **0** records; no other model media was
  removed.
- Public testimonial and home rendering, submission, publication ordering,
  Filament access, search, and sitemap checks remain covered by the focused and
  existing feature tests.
- Repository searches found no runtime references to removed testimonial CRM,
  contact, verification, or photo identifiers. Historical migrations and audit
  evidence retain the old names intentionally.
