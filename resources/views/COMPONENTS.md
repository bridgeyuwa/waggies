# Waggies Blade component system

## Layouts (`<x-layouts.*>`)

| Component | Use for |
|-----------|---------|
| `app` | Marketing pages with nav, footer, schema, cookie banner |
| `listing` | Blog/guides/KB index & category grids |
| `content` | Article detail with sidebar |
| `minimal` | Legal pages |
| `utility` | FAQ, checklist-style tools |

Pages must **only** use layouts — never duplicate HTML shells in `pages/`.

## Heroes (`<x-hero.*>`)

| Component | Use for |
|-----------|---------|
| `image` | Full-bleed photo hero (services, relocation, home CTAs) |
| `plain` | White-band hub/instructional headers |
| `split` | Two-column purple-band hero with optional stat badge |
| `gradient` | Decorative gradient hero (home, loyalty, about) |

**Conventions**

- Prefer **camelCase** props: `image-src`, `primary-cta`, `eyebrow-icon`.
- CTAs: `:primary-cta="['label' => '…', 'href' => route('…'), 'icon' => 'arrow_forward']"`.
- Legacy snake_case (`primary_label`, `image_src`) still works on `image` and `plain` during migration.
- Always pass meaningful `image-alt` on image heroes.

Removed: `overlay` (merged into `image`), `plainx` (merged into `plain`), `action` (unused).

## UI primitives (`<x-ui.*>`)

| Component | Use for |
|-----------|---------|
| `page-header` | Title band (contact, simple pages) |
| `cta-buttons` | Hero CTA pairs (internal; prefer hero components) |
| `markdown` | CMS body from Filament MarkdownEditor |

## Forms (`<x-form.*>`)

Use on all user-facing forms — do not duplicate raw `<input>` markup.

## Sections (`<x-sections.*>`)

| Component | Use for |
|-----------|---------|
| `timeline` | Ordered step lists |

## Page rules

1. One hero per page (except home).
2. Use `<x-section-heading>` for in-page sections.
3. Push page-specific schema via `@push('head')` — base org schema lives in `layouts/app`.
4. FAQ lists: `<x-faq-accordion>` with plain text answers (escaped).
