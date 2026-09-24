# Waggies 404 Page Audit

## Existing Page

The existing Waggies 404 response used the shared public header, a cream page surface, Waggies typography, a prominent `404`, an explicit `Page not found` heading, a homepage CTA, the existing global search action, a two-item recovery rail, and a compact footer.

The response is rendered by Laravel's normal error handling. Missing URLs remain HTTP 404 responses. The page uses the existing Blade layout, Tailwind utilities, Waggies button primitive, Material Symbols icon component, search dialog, compact footer, and mobile navigation.

## Problems

The previous implementation was more successful than the retired version at communicating the error, but it still borrowed a generic error-page composition:

- The pet icon badge beside the number made the error state feel like an app status card rather than a Waggies page.
- The desktop recovery rail created a two-column layout that competed with the main message.
- Icon tiles, descriptions, and the `Helpful next steps` label added visual weight without improving recovery.
- Fixed WhatsApp and assistant controls remained present on the error state and were especially distracting at mobile widths.
- The compact footer was appropriate for keeping the page short and was retained.

## Waggies Design Evidence

The final page reuses patterns visible across public Waggies pages:

- Cormorant Garamond display headings with DM Sans body copy.
- Cream and white surfaces with the established purple accent and deep purple text.
- The shared `page-container` width and responsive gutter tokens.
- The existing `text-h1` and `text-lead` typography utilities.
- The shared pill CTA tiers, 44px control targets, and visible focus treatment.
- Border-led grouping used in page intros, service rows, and public navigation.
- Material Symbols through `x-waggies.icon` instead of page-specific SVG artwork.
- The normal Waggies header, compact footer, and mobile bottom navigation.

## Final Design Decision

The chosen composition is a simple focal composition with a left-aligned editorial hierarchy:

1. A prominent `404` establishes the status immediately.
2. `Page not found` is the single H1 and states the problem without interpretation.
3. One short sentence explains what happened.
4. `Return to homepage` and `Search Waggies` provide the two most useful recovery actions.
5. A quiet inline row keeps `Browse services` and `Contact Waggies` available without becoming a second navigation system.

This is intentionally one column at every breakpoint. It feels like a Waggies page because it uses the site's real type, palette, spacing, CTA, borders, header, footer, and navigation patterns. The 404 state remains the visual focal point rather than a branded illustration or a miniature sitemap.

The existing compact footer is kept because the full newsletter and directory footer would make the error journey unnecessarily long. Floating WhatsApp and assistant controls are suppressed only for this error view because they overlap the first mobile viewport and are not needed to recover from a missing page.

## Rejected Ideas

- Two-column split layout: rejected because it made recovery content visually equal to the error message and did not reflect a consistent Waggies page pattern.
- Large ghost numeral: rejected because the visible numeral is clearer and more useful than a decorative background treatment.
- New illustration or stock image: rejected because Waggies has no established 404 illustration language and the page does not need one to feel branded.
- Route-similarity suggestions: rejected because there is no demonstrated need for speculative near-miss infrastructure.
- An embedded search field: rejected because the existing accessible global search dialog already provides this behavior.
- A broad error-page component system: rejected in favour of one small shared surface only for the five repeated secondary status pages.

## Remaining Status Pages

Before this pass, the repository only had a custom `404.blade.php`. The remaining useful HTML error states are now covered by `403.blade.php`, `419.blade.php`, `429.blade.php`, `500.blade.php`, and `503.blade.php`:

- `403` explains that access is denied and offers the homepage and contact paths.
- `419` explains that a form session expired and offers a clean restart path.
- `429` asks the visitor to wait and keeps the same low-friction recovery actions.
- `500` and `503` distinguish an unexpected failure from temporary unavailability and offer a retry link plus the homepage.

These five views share `components/waggies/error-page.blade.php` because their structure is intentionally the same: visible status, one clear heading, brief explanation, and two recovery actions. They still use the normal Waggies shell, compact footer, and existing typography and CTA primitives. Floating WhatsApp and assistant controls are suppressed so recovery actions stay visible on small screens.

The 429 HTML view applies to ordinary browser requests. The newsletter throttle keeps its existing inline redirect message, and JSON/API failures keep their JSON response contracts. No custom 401, 405, or 422 view was added because the current application does not have a public HTML recovery journey for those statuses; authentication, method handling, and validation already have their own flows.

## Accessibility / Responsive Review

- There is one clear H1: `Page not found`.
- The visible `404` and explicit H1 communicate the error without relying on color.
- Recovery actions are native links and a native button with meaningful names.
- Shared focus-visible outlines remain in use; inline recovery links keep the shared 44px minimum target height.
- The layout is mobile-first and stacks actions below 640px, with no horizontal overflow from the page content.
- The responsive utility classes keep the main message focal from narrow mobile widths through large desktop layouts, with actions stacking below the small-screen breakpoint.
- Existing reduced-motion behavior remains unchanged because the 404 page adds no authored motion.
- The public header, compact footer, and mobile bottom navigation remain accessible landmarks.

## HTTP / SEO / Verification

The implementation does not add routes, redirects, catch-all handling, logging, or alternate status behavior. Missing URLs still return HTTP 404, use the existing `Page not found - Waggies` title, remain `noindex, follow`, and do not receive a canonical URL.

The focused feature tests cover the 404 status plus the five additional HTML statuses, their status-specific copy, recovery actions, and absence of floating actions. The production build passed, the browser review confirmed the narrow 404 flow and existing search dialog, and the browser log check returned no entries.
