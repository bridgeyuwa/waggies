# Waggies Blade component system

## Layouts (`<x-layouts.*>`)

| Component | Use for |
|-----------|---------|
| `app` | Marketing pages with nav, footer, schema, cookie banner |
| `listing` | Blog/guides/KB index & category grids — pass full-bleed `hero` slot |
| `content` | Article detail with sidebar — pass full-bleed `hero` slot |
| `minimal` | Legal pages |
| `utility` | FAQ, checklist-style tools — pass full-bleed `hero` slot |

Pages must **only** use layouts — never duplicate HTML shells in `pages/`.

## Breadcrumbs (diglactic/laravel-breadcrumbs)

| Piece | Role |
|-------|------|
| `routes/breadcrumbs.php` | Trail definitions — names must match route names |
| `vendor/breadcrumbs/waggies.blade.php` | B6 markup (home icon + chevrons) |
| `<x-breadcrumb />` | Renders trail for the current route |
| `<x-breadcrumb.strip />` | Width-constrained strip above heroes or page headers on marketing pages |

Listing, content, and utility layouts render breadcrumbs above the `hero` slot. Minimal layout renders `<x-breadcrumb />` in-page. Home has no definition and renders nothing.

## Heroes (`<x-hero.*>`)

| Component | Use for |
|-----------|---------|
| `image` | Full-bleed photo hero — services, relocation, article detail, marketing CTAs |
| `hub` | Content section landing & category pages (blog, guides, KB, FAQ, checklist) |
| `split` | Two-column purple-band hero with stat badge (testimonials, team pages) |
| `gradient` | Decorative gradient hero (home secondary band, loyalty, about hub) |
| `plain` | Rare: minimal white-band when a photo hero is inappropriate |

### Choosing a hero (gold standard)

1. **Conversion / service page** → `image` (variant `center` for hubs, `bottom` for detail)
2. **Blog or guides index** → `hub` for section identity; `content.featured-story` below for lead post
3. **Blog or guides category** → `hub` with category title; `content.featured-story` when a post exists in category
4. **Blog or guide article** → `image` using `$post->hero_image_url`
5. **KB / FAQ / checklist** → `hub` with matching `type`
6. **KB article** → `image` (static help imagery + title)
7. **Contact** → `plain` hero; **pricing, shop, gallery** → `page-header` (purple title band)
8. **Social proof page** → `split` with rating badge
9. **Legal** → none (`minimal` layout)

**Conventions**

- Prefer **camelCase** props: `image-src`, `primary-cta`, `eyebrow-icon`.
- CTAs: `:primary-cta="['label' => '…', 'href' => route('…'), 'icon' => 'arrow_forward']"`.
- Always pass meaningful `image-alt` on image heroes.
- One hero per page (except home).

## Content (`<x-content.*>`)

| Component | Use for |
|-----------|---------|
| `featured-story` | Lead post/guide card on blog & guides index & category (in-content, not page hero) |

Removed: `overlay` (merged into `image`), `plainx`, `action`, `hero.featured` (featured belongs in-content, not as page hero).

## UI primitives (`<x-ui.*>`)

| Component | Use for |
|-----------|---------|
| `page-header` | Hub pages without a photo hero (contact, pricing, shop). Use `bare` only as legacy fallback |
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
2. Use `<x-section-heading>` for in-page sections below the hero.
3. Push page-specific schema via `@push('head')` — base org schema lives in `layouts/app`.
4. FAQ lists: `<x-faq-accordion>` with plain text answers (escaped).
