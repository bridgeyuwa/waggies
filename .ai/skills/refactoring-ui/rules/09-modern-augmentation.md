# Modern Web Augmentation

These rules extend the original design principles for current production web interfaces.

## WCAG 2.2 AA is a constraint, not a final polish pass

Accessibility must influence the design from the beginning.

Check:

- semantic landmarks and heading structure,
- accessible names and form labels,
- keyboard path and logical focus order,
- visible focus that is not obscured,
- text and non-text contrast,
- error identification and recovery,
- target size and spacing,
- zoom/reflow,
- reduced motion,
- state communication independent of color,
- screen-reader relationships for menus, dialogs, tabs, disclosures, alerts, and validation.

## State completeness

Hover is not a substitute for focus; focus is not selected; selected is not pressed; disabled is not loading.

Each interactive component should have a state model before styling. Avoid state rules that accidentally make `:hover` and current/active states indistinguishable.

## Motion

Animation should explain cause/effect, continuity, expansion/collapse, reordering, or feedback.

Avoid animation added only to make a static section feel "premium". Keep durations short, use consistent easing, avoid layout-jank properties where transforms/opacity suffice, and disable/reduce non-essential motion for `prefers-reduced-motion`.

## Responsive verification

Do not stop after desktop and mobile screenshots. Verify transition widths where layout pressure occurs.

At minimum, inspect:

- a small mobile width,
- a common mobile width,
- tablet/narrow desktop,
- standard desktop,
- wide desktop if the page uses wide compositions.

Test with long real content, not only ideal copy.

## Component resilience

Refactors must survive:

- long headings,
- missing optional images,
- unusually long names/labels,
- localization expansion where applicable,
- empty and loading states,
- error text,
- 200% text zoom,
- keyboard focus rings,
- slow image loading.

## Performance is visual quality

Avoid UI improvements that create obvious layout shift or excessive client-side work.

- Reserve media space.
- Avoid unnecessary client-side JavaScript.
- Do not add heavyweight animation/UI dependencies for effects achievable with CSS and existing utilities.
- Preserve framework image/font optimization.

## Modern color implementation

Prefer semantic CSS custom properties and the project's established perceptual color space. For Waggies, do not regress an OKLCH token system back to ad-hoc hex/HSL literals simply because the source book used HSL examples.

## Modern responsive implementation

Use flex/grid/intrinsic sizing and container-aware behavior. A 12-column grid may still be appropriate for macro layout, but it must not force fixed-content components into awkward percentage widths.

