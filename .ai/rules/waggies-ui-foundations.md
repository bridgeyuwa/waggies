---
paths:
  - 'resources/views/**'
  - 'resources/css/**'
  - 'resources/js/**'
  - 'app/Livewire/**'
  - 'app/Filament/**'
  - 'app/Providers/Filament/**'
---

# Waggies UI Foundations

Waggies is a Laravel 13 public website using Blade, Alpine.js, Tailwind CSS v4, and selective Livewire. Filament is the administration surface. Public UI is Blade-first; use Alpine for local browser interaction and Livewire only when server-side state materially improves correctness, persistence, authorization, or stateful behavior.

## Brand character

Waggies should feel **warm, trustworthy, premium, practical, and pet-centered**. It should not drift into generic purple SaaS, toy-like pet-shop visuals, excessive cartoon styling, or sterile clinical/veterinary aesthetics.

Use visual warmth through typography, imagery, spacing, and restrained brand accents rather than excessive decoration.

## Established typography

- Body/UI: **DM Sans**.
- Display/headings: **Cormorant Garamond**.
- Do not introduce additional font families without an explicit design-system decision.
- Do not use the display face for dense UI controls, long body copy, or tiny labels.

## Light mode

Waggies is a light-mode design. Do not add dark-mode variants or `dark:` styles unless the product decision changes explicitly.

## Visual hierarchy

Every page should make the primary user goal obvious without making every element loud.

- Use one primary local CTA where a single next step exists.
- Supporting actions must be quieter.
- Section headings should not dominate the actual service/product content.
- Prefer de-emphasis of secondary elements over making primary elements increasingly large or saturated.

## Framework and path guardrails

Keep public page composition in `resources/views/pages/**`, shared public components in `resources/views/components/waggies/**`, layouts in `resources/views/layouts/**`, CSS tokens in `resources/css/app.css`, and Alpine behavior in `resources/js/alpine/**`. Keep Livewire and Filament work in their Laravel locations. Do not introduce React, Next.js, Vue, or Inertia into this surface.

## Consistency before novelty

Before inventing a new card style, button style, radius, shadow, section pattern, icon treatment, or layout convention, inspect adjacent Waggies components and tokens.

Preserve the established design language unless the existing pattern is inaccessible, inconsistent, or clearly undermines the user task.

## Component composition

Avoid "card soup". Do not wrap every section or piece of information in a bordered rounded rectangle. Use spacing, alignment, background changes, and typography first; use a card only when the content is conceptually a bounded object or needs a distinct interaction surface.

## Copy tone

Interface copy should be friendly, clear, calm, and confident. Avoid generic startup language, exaggerated marketing claims, or overly cute pet puns that reduce trust in boarding, veterinary, relocation, or other high-responsibility services.

