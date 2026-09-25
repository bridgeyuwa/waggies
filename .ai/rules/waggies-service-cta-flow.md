---
paths:
  - 'resources/views/pages/services/**'
  - 'resources/views/pages/contact.blade.php'
  - 'resources/views/components/waggies/**'
  - 'app/Livewire/**'
  - 'routes/**'
---

# Waggies Service and CTA Flow

This rule governs Waggies' Laravel Blade/Alpine public service surfaces and their shared request-builder components.

Waggies uses this product-led progression:

**Discovery → Evaluation → Commitment → Request Builder → Review → WhatsApp / handoff**

Do not collapse every step into a generic `Book Now` CTA.

## Canonical service CTA behavior

- Homepage/nav/service discovery: prefer **Explore Services** where the user is still discovering.
- Boarding package decisions may expose estimate and request/booking actions appropriate to the selected tier.
- Grooming: use a grooming request path rather than pretending instant booking exists if the flow is request-based.
- Veterinary care: use appointment-request language.
- Training: use training-request language.
- Local transport: treat as a first-class route-estimated service when supported by the product.
- International relocation: quote/request flow, not instant booking.

Preserve the existing canonical service/product/request-builder data contracts. A visual refactor must not fork product names, package names, or pricing rules in page-local UI.

## Action hierarchy

At each stage, emphasize the next action appropriate to that stage rather than the action with the strongest sales wording.

Do not show multiple equal-weight CTAs that jump users to different stages without explaining the difference.

## Deep links

Preserve service/tier/intent query parameters and deep-link behavior used by the Request Builder. UI changes must not silently strip or rename them.

## Forms/request builder

The request form is a primary task surface. Information/supporting content should not visually overpower it.

After step transitions/submission, manage scroll/focus so the user is taken to the new relevant content/heading/error summary instead of being dumped at an arbitrary page position.

