# Product Gallery Audit

## Scope

Audited the rendered product page at `/shop/bmx-bicycle`, the `Product` model, Filament product form, shop controller, Blade view, Alpine shop behavior, Spatie Media Library collections, product tests, and product seed/data paths.

## Current implementation

- `Product` implements Spatie Media Library through `HasMedia` and `InteractsWithMedia`.
- The `image` collection is a single public-disk primary-image collection.
- The `images` collection is a public-disk, responsive-image collection. Its persisted media order is the existing gallery order.
- The model's current primary-image logic prefers the first item in `images`; it falls back to the single `image` collection and then to the legacy `image` path passed by the controller.
- The product detail controller eager-loads `media` and passes the product through `Product::toPublicArray()`; no new query or image table is required.
- The rendered `BMX Bicycle` page currently exposes three product media images in the `images` collection. The first is rendered as the main image and all three are rendered again as passive thumbnail `<img>` elements.
- The Filament form already uses `SpatieMediaLibraryFileUpload` for the `images` collection with multiple upload and reordering enabled. The gallery is now explicitly capped at eight additional images and appends new uploads so the displayed order matches the editor order.

## Observed UX problems

The existing Blade view rendered the additional media as:

```html
<div class="grid grid-cols-4 gap-3">
    <img ...>
    <img ...>
    <img ...>
</div>
```

Those elements were not buttons or links, did not have an active state, did not update the main image, and had no previous/next controls. On mobile they were simply stacked below the large square image; there was no touch carousel or horizontally scrollable rail. The first media image was also duplicated as the first passive thumbnail, which made the relationship between the main image and the thumbnails unclear.

## Accessibility findings

- Additional images had no interactive semantics or keyboard path.
- There was no active-image state for assistive technology.
- There were no labeled previous/next controls.
- The main image could not be opened at a larger size.
- The fixed thumbnail grid did not communicate the total image count or current position.

## Recommended interaction model

Use an Alpine-powered gallery that keeps the existing Spatie media data as its source of truth:

1. Keep the first ordered product media item selected initially.
2. Use a vertical thumbnail rail on desktop and a horizontally scrollable, touch-contained rail on mobile.
3. Make every thumbnail a semantic button with `aria-current` and visible focus/active styling.
4. Provide previous/next controls over the main image and in the enlarged view.
5. Support left/right keyboard navigation, Escape to close the lightbox, and horizontal touch swipes.
6. Keep the existing product content, pricing, CTA, route, canonical metadata, and structured data unchanged.
7. Remove the unused product SEO title/description inputs from Filament. The current shop controller does not read those fields; it derives page metadata from the product name and description. The existing database columns remain untouched for compatibility.

## Implementation result

The gallery now uses the existing Blade + Alpine + Tailwind stack and existing Waggies lightbox layer. No third-party gallery dependency, product image table, new product field, or alternate media architecture was introduced.
