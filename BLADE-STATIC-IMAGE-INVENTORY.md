# Waggies — Blade Static Image Inventory

Read-only inventory of image references whose final source is fixed by the Blade view layer.

## Audit scope and classification

- Re-scanned all 83 `*.blade.php` files under `resources/views/`, including pages, layouts, components, error views, and Filament widget views.
- Counted direct fixed image references and indirect values fixed by Blade-local variables, Blade-local arrays/configuration, fixed component props, fixed include parameters, fixed conditional branches, and fixed fallbacks.
- Counted one occurrence per rendering location. Repeated rendering of the same fixed source would therefore produce multiple ledger rows.
- Audited the source Blade tree only; compiled views under `storage/framework/views/` were excluded.
- Image values supplied through controllers, models, database/config data, request/user data, API data, Alpine state, or other runtime selection were excluded unless the Blade source itself fixed the final value.
- Inline SVG markup and dynamically selected SVG mask/icon files were not counted as content-image source occurrences.

## Summary

| Metric | Count |
| --- | ---: |
| Blade files audited | 83 |
| Blade files containing Blade-fixed image references | 2 |
| Direct fixed occurrences | 2 |
| Indirect Blade-fixed occurrences | 0 |
| Total Blade-fixed occurrences | 2 |
| Unique local image paths | 2 |
| Unique external image URLs | 0 |
| Static local raster image occurrences | 2 |
| Static local SVG image occurrences | 0 |
| Static external image occurrences | 0 |
| Static background-image occurrences | 0 |
| Static image fallbacks | 0 |
| Fixed component-prop occurrences | 0 |
| Fixed include-parameter occurrences | 0 |
| Blade-local variable/array occurrences | 0 |
| Existing local references | 2 |
| Missing local references | 0 |

## Complete occurrence ledger

| # | Blade file | Definition line | Rendering line | Page/component | Section/function | Image source | Direct/fixed form | Local/external | Exists? | Source variable/array/prop | Notes |
| ---: | --- | ---: | ---: | --- | --- | --- | --- | --- | --- | --- | --- |
| 1 | `resources/views/pages/home.blade.php` | — | 59 | `/` | Care standards section image | `/media/home/care-standards.jpg` | Direct | Local | Yes | — | Local raster JPG; `public/media/home/care-standards.jpg`; 600×400; lazy-loaded; alt text describes a puppy resting in a boarding suite. |
| 2 | `resources/views/pages/services/relocation.blade.php` | — | 13 | `/services/relocation` | Planning-tool image / section image | `/media/services/training/training-dog.jpg` | Direct | Local | Yes | — | Local raster JPG; `public/media/services/training/training-dog.jpg`; 800×600; lazy-loaded; alt text is `Pet relocation document checklist`. |

## Per-view summary

### `resources/views/pages/home.blade.php`

Blade-fixed image occurrences: 1 direct, 0 indirect

- Rendering line 59 — Care standards section image — `/media/home/care-standards.jpg`

The other image rendering in this view uses `$card['imageSrc']`, which is application-provided and therefore excluded.

### `resources/views/pages/services/relocation.blade.php`

Blade-fixed image occurrences: 1 direct, 0 indirect

- Rendering line 13 — Planning-tool image / section image — `/media/services/training/training-dog.jpg`

## Per-page summary

### `/`

Blade-fixed image occurrences: 1

- Direct: Care standards section image — `/media/home/care-standards.jpg`

### `/services/relocation`

Blade-fixed image occurrences: 1

- Direct: Planning-tool image / section image — `/media/services/training/training-dog.jpg`

## Indirect Blade-fixed review

No indirect Blade-fixed occurrences were found.

The scan specifically checked for and found none of the following fixed forms:

- Blade-local scalar image variables rendered later.
- Blade-local arrays or nested configuration containing fixed image values.
- Fixed `image`, `src`, `poster`, or equivalent component props.
- Fixed image values passed through `@include` parameters.
- Fixed image candidates in conditional branches.
- Fixed fallbacks in null-coalescing, ternary, or equivalent expressions.
- Fixed external image URLs or fixed CSS `background-image` URLs.

Dynamic image renderings such as `$homeHero`, `$mosaicImages`, `$guide['image']`, `$article['image']`, `$product['image']`, `$page[...]`, `$card['imageSrc']`, and `$image['thumb']` remain excluded because their final sources originate outside the Blade view. The boarding related-image expression also remains dynamic because it depends on `$species` and `$sibling['key']`.

## Repeated/static image matrix

No fixed image source is referenced more than once in the audited Blade tree.

| Image | Occurrences | Blade files | Functions |
| --- | ---: | --- | --- |
| No repeated static image | 0 | — | — |

## High-interest review findings

- `/media/services/training/training-dog.jpg` is hard-coded in the relocation page while its path is under the `services/training` directory. This is a potential ownership/path review item only; the audit does not infer that the reference is incorrect.
- Both occurrences use direct root-relative `/media/...` paths in Blade. They are fixed references that may deserve future centralization review.
- No indirect Blade-fixed image values were found after the corrective pass.
- No fixed external image URLs, fixed background-image URLs, fixed fallbacks, fixed component props, or fixed include parameters were found.
- No missing local Blade-fixed image references were found.

## Integrity check

- Re-scanned the full `resources/views/` Blade tree after applying the broader direct and indirect classification.
- Included direct fixed references and checked all indirect categories required for this pass.
- Preserved one row per rendering occurrence; no duplicate fixed source was present.
- Confirmed dynamic-only sources were excluded, including controller/model-provided image arrays, Alpine `:src` bindings, component asset paths with dynamic filenames, and runtime-dependent boarding paths.
- Confirmed no static fallback, layout/global-partial, email-view, fixed component-prop, fixed include-parameter, or external fixed-image occurrence was omitted.
- Ledger totals reconcile with the summary: 2 direct + 0 indirect = 2 total occurrences; 2 local raster occurrences; 2 existing local references + 0 missing local references.

No application files were changed during this audit.
