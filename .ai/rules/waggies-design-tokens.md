---
paths:
  - 'resources/css/**'
  - 'resources/views/**/*.blade.php'
  - 'resources/js/**/*.js'
  - 'app/Livewire/**'
  - 'app/Filament/**'
  - 'app/Providers/Filament/**'
---

# Waggies design tokens

The canonical visual-token source is `resources/css/app.css`, especially its Tailwind v4 `@theme` block and the semantic aliases that follow it. Do not invent a parallel token file, `tailwind.config.js`, or page-local brand palette.

## Color authority

Waggies uses an OKLCH-based token system. Consume the existing semantic roles and aliases, including:

- `--color-primary`, `--color-primary-hover`, and `--color-primary-dark`
- `--color-secondary` and `--color-secondary-hover`
- `--color-surface`, `--color-surface-purple`, and `--color-surface-raised`
- `--color-text`, `--color-text-secondary`, and `--color-text-muted`
- `--color-border-subtle`, `--color-focus`, and the semantic feedback colors

The `--color-waggies-*` variables are the canonical brand primitives. Their numeric OKLCH values belong in `resources/css/app.css), not in component markup, guidelines, skills, or page-local examples. Do not document or introduce HEX brand primitives.

Raw color values are allowed only where the CSS implementation genuinely requires a local technical exception, such as print output or forced-colors handling. New UI should use the token system.

## Scales and arbitrary values

Use the existing type, spacing, radius, border, shadow, icon-size, breakpoint, z-index, and motion scales. Prefer the named utilities and semantic aliases already used by Waggies components.

When the current scale cannot express a repeated need:

1. show the gap in the existing scale;
2. add one role-based token in `resources/css/app.css`;
3. use it consistently across the affected pattern;
4. do not add a page-name token or a one-off literal.

Avoid arbitrary Tailwind values and inline design values when an existing token solves the problem.

## Typography

Preserve the established role split:

- DM Sans for body and interface text;
- Cormorant Garamond for display headings and selected editorial emphasis.

Use the existing `text-h*`, `text-body*`, `text-label`, and `text-meta` roles where they fit. Do not create component-local type ladders.

## Surfaces, borders, radii, and shadows

Use the project's small radius vocabulary and named elevation tokens. Use borders for controls and boundaries that need crisp discoverability; do not turn every section into a bordered card.

Shadows communicate elevation, floating behavior, or interaction. They are not a generic “premium” effect.

## Icons

Use the existing `<x-waggies.icon>` component and the local Material Symbols assets. Do not introduce another icon family inside a refactored Waggies surface without an explicit migration decision.
