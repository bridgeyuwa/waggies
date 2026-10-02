# Canonical booking-request intake

## Status

Accepted

## Decision

The public booking flow keeps the legacy flat POST route and the Livewire request builder as transport adapters, but both feed one canonical structured booking-request intake module.

The canonical payload keeps the existing associative-array shape:

```php
[
    'contact' => [...],
    'pets' => [...],
    'services' => [...],
    'idempotency_key' => ?string,
    'source' => ?string,
    'context' => [...],
]
```

`BookingRequestIntake` owns normalization, shared structural validation, service availability and option rules, pet compatibility, assignments, duplicate-service detection, and human-readable validation messages. Machine-readable dot-notated keys remain internal so Livewire can target fields, but those keys must not be used as user-facing messages.

`CreateBookingRequest` remains responsible for the transaction, idempotency, persistence, and staff notification. It does not own request-shape or service-domain validation.

`BookingRequestBuilder` owns pure request-state transitions such as creating services and pets, changing service options, and re-indexing pet assignments. The Livewire component retains serialized UI state, rendering, focus, announcements, and step-specific presentation validation. An in-progress wizard is UI state; it is not a persisted draft domain object.

`BookingPricingCatalog` is the sole application-facing interpreter of `config/waggies_pricing.php`. Other callers use catalog methods rather than depending on the configuration tree.

## Consequences

This preserves existing routes, behavior, idempotency, notifications, estimates, and public wording while giving both intake adapters one validation surface. It also makes the pricing source replaceable later without forcing transport or view code to understand configuration shape.
