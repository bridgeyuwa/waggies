# Waggies Implementation Brief

## Purpose

This brief captures the product and business-logic decisions made during the Waggies architectural review. Use it when implementing the agreed changes in the existing Waggies Laravel application.

This is not a new product-discovery exercise. Waggies already exists. First compare these decisions with the current implementation, then reconcile the differences. Do not begin another long sequence of discovery questions. Raise only a genuine blocker that cannot be resolved from this brief or the existing code.

## Project

Repository:

`C:\Users\Bridges\Herd\waggies`

The implementation uses Laravel, Blade, Livewire, Alpine.js, Tailwind CSS, and Filament.

## Required preparation

Before editing:

1. Read `AGENTS.md`.
2. Read `.ai/rules/index.md`.
3. Read every matching rule file for the paths being modified.
4. Inspect the current implementation before designing replacements.
5. Preserve unrelated user changes in the worktree.
6. Reuse existing Laravel, Blade, Livewire, Alpine, Filament, and pricing patterns where appropriate.
7. Do not introduce React, Vue, Inertia, or another frontend framework.
8. Use `apply_patch` for file edits.
9. Do not create documentation files unless explicitly required. This brief is explicitly requested documentation.
10. Use the relevant project skills when applicable:
    - Laravel best practices
    - Spatie Laravel/PHP standards
    - Livewire development
    - Filament development
    - Testing best practices

## Final public service catalogue

Waggies should publicly expose only these service categories:

1. Boarding
2. Veterinary Care
3. Relocation

Relocation has two subservices:

- Import
- Export

The following are not active Waggies services and must be removed completely from the service and booking surfaces:

- Standalone grooming
- Dog training
- Local transport
- Exotic-pet boarding

“Removed completely” means removing them from public pages, routes, navigation, booking choices, pricing configuration, SEO metadata, search/catalogue data, contact contexts, frontend logic, admin/service options, fixtures, and tests where appropriate. Do not merely leave them disabled as visible future services.

General educational articles that mention grooming or training may remain if they are clearly educational and do not represent Waggies as actively selling those services. Review those articles rather than blindly deleting them.

## Boarding model

Boarding is one core service priced per pet per night.

There are no boarding packages or tiers.

Every boarding pet receives its own enclosure. The fact that animals share an indoor facility is internal operational information only. Do not publish claims about the shared indoor layout. Do not add public copy that exposes the common-room arrangement.

Do not use package capacity or `included_pet_count` pricing.

Boarding supports dogs and cats only.

### Boarding inclusions

The core boarding service includes:

- Individual enclosure
- Water
- Owner-supplied food and feeding instructions
- Routine cleaning
- Basic welfare checks
- Light bath/wash for boarded dogs before pickup, where safe and appropriate

The light bath/wash is not a standalone grooming service. It must not imply professional grooming, styling, clipping, spa treatment, or a dedicated groomer.

Do not promise these as standard boarding benefits unless Waggies separately confirms them:

- Daily photos
- Scheduled owner updates
- Outdoor walks
- Structured play
- Enrichment programmes
- Daily veterinary checks
- 24/7 supervision

Medication, special handling, intensive supervision, and other special-care needs require staff review and a separate charge or quote.

Owner-supplied food is the default. If the owner does not supply food, Waggies may provide approved food only after confirmation, with any applicable cost handled manually.

### Dog size selection

Customers select the dog size. Do not ask for weight and do not calculate size automatically from weight.

Use these operational guidance bands:

- Small: up to 10kg
- Medium: over 10kg through 25kg
- Large: over 25kg through 40kg
- Above 40kg or unusual size: manual review

The boundaries must be unambiguous. Do not retain overlapping ranges.

Show size guidance in the request UI, but treat it as customer guidance rather than a medical or legal classification.

Staff must be able to correct the selected size during review. Any revised price is confirmed manually with the customer before final confirmation.

Cats should have one fixed nightly boarding rate, not cat packages or tiers.

Do not invent new numeric prices. Consolidate the existing rates only after identifying which values are authoritative. If the current cat rate cannot be safely selected from the existing configuration, isolate that as a small pricing-value blocker rather than silently choosing a new amount.

### Boarding duration

Boarding is priced by the number of overnight stays.

Do not enforce an artificial 30-night maximum.

Long stays remain subject to manual availability review.

Recommended date behaviour:

- Charge per overnight stay.
- The checkout date is not another night unless the pet remains past the agreed cutoff.
- Late pickup may trigger a late fee or additional night according to the policy.

### Multiple-pet discounts

The multiple-pet discount feature remains part of the architecture.

It is currently disabled while development issues are being fixed.

When enabled in the future:

- It applies only to boarding.
- It applies to the second and subsequent pets in the same boarding request.
- It is independent of packages because packages no longer exist.
- It must not affect veterinary or relocation pricing.
- Discounts that require staff judgement are calculated manually during quotation.
- Public wording may advertise availability without promising a specific percentage, for example: “Multi-pet discounts available and applied during quotation.”

Do not allow the automatic calculator to become the final authority for a manually quoted discount.

## Veterinary Care

Veterinary Care should contain:

- Wellness consultation
- Comprehensive examination
- Vaccination request
- Microchip implantation

The current generic fixed vaccination price is not acceptable as the final model because Waggies does not yet have a curated vaccine list.

Until the vaccine list exists:

- Keep vaccination visible as a request-only veterinary service.
- Do not present one generic final vaccination price.
- Do not invent vaccine products or prices.
- Staff/veterinary review determines the appropriate vaccine, assessment, administration, and product costs.

Once a confirmed vaccine catalogue exists, the model can support:

- Basic pre-vaccination assessment
- Specific vaccine product
- Administration
- Record or certificate where applicable

Microchipping belongs under Veterinary Care → Identification.

Microchipping must be available as a standalone service when requested.

For relocation:

- Export includes microchipping by default if the pet has no existing chip.
- If a chip already exists, scan and record it rather than implanting another.
- Import includes microchipping only when required by the destination or route.
- Do not model microchipping as dependent on a government licence in Nigeria.
- Registration should be handled by Waggies where the provider supports it; otherwise give the owner the chip number and registration instructions.

## Relocation

Relocation supports dogs and cats only.

Import and Export are both:

- Request-based
- Custom-quoted
- Manually reviewed

Waggies may handle:

- Airline booking
- Transport to the departure airport
- Pickup from the arrival airport
- Required document coordination
- Facilitation of veterinary steps
- Microchip checks or implantation where applicable
- Coordination with third-party providers

Local transport remains removed as a standalone Waggies service. Airport transport exists only as an internal component of an Import or Export relocation booking. Do not recreate a Local Transport navigation item, service key, standalone route, calculator, or product.

Relocation quotes should be itemized manually where practical, including:

- Airline costs
- Airport or third-party charges
- Veterinary/documentation costs
- Crate or travel equipment
- Airport transfer costs
- Waggies coordination fees

Relocation should collect or support:

- Import/export direction
- Origin country
- Destination country
- Travel date
- Pet species
- Existing microchip status
- Documentation status
- Airline/airport details
- Pickup and destination details
- Additional route or timing notes

The owner supplies original documents, while Waggies helps coordinate and facilitate the required process.

Relocation needs its own public policy page with Import and Export sections.

## Booking-request workflow

The primary action is “Submit Booking Request,” not “Book Now.”

After the customer submits a Booking Request, everything is handled manually:

- Availability
- Boarding-size review
- Final price
- Multiple-pet discount
- Special-care assessment
- Veterinary review
- Relocation feasibility
- Payment instructions
- Confirmation

The customer should initially receive acknowledgement that the request was received. The request is not confirmed until Waggies confirms it manually.

Recommended workflow states:

- Request received
- Under review
- Quote/confirmation sent
- Awaiting customer/payment
- Confirmed
- Completed
- Declined
- Cancelled

Use the existing status model where possible, but add or adapt states only when needed.

The existing request flow is a good foundation because it already communicates that requests are not confirmed and nothing is charged immediately.

However, the current implementation automatically calculates and stores price snapshots and discounts when a request is created. Treat those calculations as indicative/draft data only, or remove them from final quotation authority. The staff-entered quote must be authoritative.

The existing Filament admin fields for quote amount, quote notes, internal notes, and status should be reused and extended only where necessary.

Do not build online checkout unless it already exists. Payment is handled manually after staff review.

Recommended payment policy:

- Boarding and ordinary service requests: staff confirms the quote, sends payment instructions, and confirms the booking after required payment is received.
- Relocation: require a deposit before Waggies commits to airline or other non-refundable third-party bookings.

## Boarding admission and safety

Create a public Boarding Requirements & Admission Policy covering:

- Accepted species
- Required records or health information
- Illness and contagious-condition handling
- Parasite concerns
- Behaviour-risk disclosure
- Feeding instructions
- Medication and special-care requests
- Emergency contacts
- Admission refusal or cancellation
- Check-in and pickup expectations

Do not invent a specific vaccine list or legal requirement that Waggies has not confirmed. Use careful wording where requirements remain operationally pending.

Recommended safety rules:

- Waggies may decline admission when a pet appears ill.
- Contagious illness or active parasite concerns require treatment or clearance.
- Owners must disclose aggression, escape behaviour, bite history, severe anxiety, or handling difficulties.
- Difficult behaviour is reviewed case by case.
- One primary and one secondary emergency contact should be collected.
- Emergency veterinary authorization should be part of the boarding process.

## Booking, payment, and cancellation policies

Recommended cancellation rules:

- More than 48 hours before check-in: full refund.
- Within 48 hours: partial refund or credit.
- No-show: no refund.
- Early pickup: confirmed booking remains chargeable unless Waggies can resell the released nights.
- Late pickup: defined grace period, then late fee or additional night.

The booking, payment, and cancellation process remains manual after the Booking Request. Do not create automatic refund logic unless the current implementation genuinely requires it.

## Policy hub

Create a public Policies hub with dedicated pages for:

- Boarding Requirements & Admission
- Cancellation, Rescheduling & Refunds
- Check-in, Check-out & Late Pickup
- Medication & Special Care
- Emergency Veterinary Care
- Pet Behaviour & Safety
- Relocation
- Any general service terms that do not belong in the legal Terms page

The existing Privacy, Terms, and Cookies pages should remain, but their stale references to removed services must be updated. The Terms page currently mentions grooming, training, transport, and generic core-vaccination requirements and must be reconciled with this brief.

## Known implementation mismatches

Inspect and reconcile at minimum:

- `config/waggies_pricing.php`
- `app/Support/BookingPricingCatalog.php`
- `app/Support/BookingRequestSchema.php`
- `app/Actions/CreateBookingRequest.php`
- `app/Enums/BookingRequestStatus.php`
- `app/Http/Controllers/ServicesController.php`
- `app/Http/Controllers/RelocationController.php`
- `app/Http/Controllers/BookingRequestsController.php`
- `app/Http/Controllers/PricingController.php`
- `app/Http/Controllers/LegalController.php`
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/AboutController.php`
- `app/Http/Controllers/AboutPagesController.php`
- `app/Http/Controllers/ContactController.php`
- `app/Http/Controllers/FaqController.php`
- `app/Providers/AppServiceProvider.php`
- `app/Support/WaggiesPageHead.php`
- `app/Support/SearchCatalog.php`
- `app/Support/PublicUrlCatalog.php`
- `app/Support/ContactContextResolver.php`
- `resources/views/components/booking-request-wizard.blade.php`
- `resources/views/components/pricing-calculator.blade.php`
- `resources/views/pages/services/**`
- `resources/views/pages/relocation/**`
- `resources/views/components/waggies/**`
- `resources/js/pricing-calculator.js`
- `resources/js/alpine/requests.js`
- `routes/web.php`
- Related factories, fixtures, and tests

The current implementation has a known pricing overlap:

1. `BookingPricingCatalog` calculates dog prices from size ranges and tier adjustments.
2. `ServicesController` displays package/tier amounts.
3. The booking wizard requires package/tier selection.
4. The pricing calculator exposes package/tier selections.
5. Static public copy claims services that are being removed.

Resolve this overlap rather than adding another pricing system.

The current request flow has two related intake paths: the Livewire booking wizard and the older controller/FormRequest path. Inspect both and ensure they do not expose different service catalogues or contradictory validation rules.

## Testing requirements

Update tests to reflect the new product model.

Important coverage should include:

- Only Boarding, Veterinary Care, and Relocation appear as active public booking services.
- Grooming, training, local transport, and exotic boarding are no longer active services.
- Boarding has no package/tier selection.
- Boarding prices are per pet per night.
- Dog size is selected directly.
- Weight is not required or used to derive size.
- Size boundaries are correct.
- Above-40kg dogs require manual review.
- Cats use one boarding rate rather than packages.
- Boarding has no artificial 30-night maximum.
- Multiple-pet discount remains configurable but disabled.
- Manual quotation remains the final authority.
- Vaccination is request-only until vaccine products are defined.
- Microchipping exists under veterinary care.
- Relocation supports only dogs and cats.
- Import/export remain quote-only.
- Relocation airport transfers do not recreate standalone local transport.
- Policy pages and relevant routes exist.
- Removed service routes and public links no longer appear.

Run the narrowest relevant tests first, then the broader suite.

If PHP files are changed, run:

```text
vendor/bin/pint --dirty --format agent
```

Also run relevant Laravel tests and frontend build checks where applicable.

## Working discipline

Do not start another broad product-discovery loop. First produce a short implementation gap summary, then implement the settled changes in coherent bounded slices.

Do not invent exact new price amounts, vaccine products, legal requirements, facility promises, or operational capabilities. If one exact value remains genuinely undefined, isolate it as a small blocker without stopping the rest of the reconciliation work.
