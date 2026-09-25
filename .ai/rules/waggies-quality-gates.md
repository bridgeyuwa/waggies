---
paths:
  - 'resources/views/**'
  - 'resources/css/**'
  - 'resources/js/**'
  - 'app/Livewire/**'
  - 'app/Filament/**'
  - 'app/Providers/Filament/**'
---

# Waggies Frontend Quality Gates

These gates apply to Blade, Alpine.js, Tailwind CSS, Livewire, and Filament UI work in the Laravel application. They complement Laravel Boost's generated rule index.

Every changed frontend slice must preserve behavior and meet these gates before the task is considered complete.

## Accessibility

Target WCAG 2.2 AA.

Verify:

- semantic landmarks/headings,
- form labels and accessible names,
- keyboard operation,
- visible focus,
- contrast,
- non-color state cues,
- target size,
- reflow/zoom resilience,
- reduced motion,
- error/success communication.

Do not remove semantics or focus treatment to achieve a cleaner visual result.

## Responsive

Check more than two screenshots. Inspect at least one small mobile, one typical mobile, one tablet/narrow desktop, and one desktop width, plus any breakpoint where the affected layout changes.

Test long content and wrapped labels, not only ideal sample text.

## Engineering

- Preserve Laravel Blade/Alpine/Livewire boundaries unless behavior requires otherwise.
- Do not add a dependency for simple styling/motion already supported by the stack.
- Reuse existing design primitives/tokens. Use the public Waggies token system for public UI and the Filament/admin theme primitives for administration UI; do not cross those boundaries casually.
- Avoid broad file rewrites when a focused edit is sufficient.
- Preserve routes, query parameters, request-builder behavior, and service data unless explicitly changing product behavior.

## Verification commands

Use the repository's actual scripts. Run the narrowest relevant checks first: `npm run build` for frontend assets, targeted `php artisan test --compact` for behavior, and `vendor/bin/pint --dirty --format agent` when PHP changed. Add visual/browser verification for affected states and responsive widths.

## Scope guardrails

- Stay inside the requested page/component and directly required shared primitives.
- Report unrelated defects instead of fixing them opportunistically.
- Stop after verification.
- After two failed attempts at the same fix, diagnose the root cause before trying again.
- If the correct solution requires a broader architecture/product decision, surface it rather than silently expanding scope.
