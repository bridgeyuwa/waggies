---
paths:
  - 'resources/css/app.css'
  - 'resources/views/pages/**'
  - 'resources/views/components/waggies/**'
  - 'resources/views/layouts/**'
  - 'resources/views/errors/**'
  - 'resources/js/**/*.js'
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

The `--color-waggies-*` variables are the canonical Waggies brand primitives. Their numeric OKLCH values belong in `resources/css/app.css`, not in component markup, guidelines, skills, or page-local examples. Do not document or introduce HEX brand primitives.

Waggies semantic colors and external-service colors are separate from the Waggies brand primitives, but they should still be represented as deliberate semantic tokens. For example, WhatsApp may keep its recognizable service color through a dedicated OKLCH `--color-whatsapp` token; it must not be repeated as raw HEX in component markup.

Raw color syntax is allowed only where the implementation genuinely requires a local technical exception, such as print output, forced-colors handling, or the source of an owned/external asset. New public UI should consume semantic tokens.

## Scales and arbitrary values

Use the existing type, spacing, radius, border, shadow, icon-size, breakpoint, z-index, and motion scales. Prefer the named utilities and semantic aliases already used by Waggies components. The goal is a coherent system, not arithmetic purity or a ban on every Tailwind arbitrary value.

When the current scale cannot express a repeated need:

1. show the gap in the existing scale;
2. add one role-based token in `resources/css/app.css`;
3. use it consistently across the affected pattern;
4. do not add a page-name token or a one-off literal.

Do not introduce an arbitrary Tailwind value when an existing token expresses the same intent. Arbitrary values remain acceptable for intentional formulas, responsive constraints, accessibility dimensions, and technical geometry. When a custom design value repeats or represents a reusable role, promote it to a semantic token instead of multiplying one-off literals.

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

## Use Laravel Head as the sole public SEO renderer
Render public-page title, description, canonical, robots, Open Graph, and Twitter metadata through the layout's Laravel Head output. Keep the head stack reserved for exceptional non-SEO tags; do not add a second SEO renderer in Blade.
