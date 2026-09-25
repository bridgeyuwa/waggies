# Refactoring UI Review Checklist

Use this checklist after implementation or during a focused UI audit.

## Product and hierarchy

- [ ] The page's primary user goal is identifiable within a few seconds.
- [ ] One local action is clearly primary where a primary action exists.
- [ ] Secondary/tertiary actions do not compete with the primary action.
- [ ] Destructive actions are emphasized only when the user is actually confirming destruction.
- [ ] Content order matches user priority, not implementation order.
- [ ] Current/selected states are distinct from hover/focus states.

## Systems

- [ ] No unnecessary one-off font sizes, spacing values, radii, shadows, colors, or border widths were added.
- [ ] Existing tokens were reused where appropriate.
- [ ] New tokens, if any, solve a reusable gap and are named semantically.
- [ ] Raw colors are confined to token definitions.

## Layout and spacing

- [ ] Content is not stretched wider than needed.
- [ ] Text measure is readable.
- [ ] Fixed/bounded elements do not use fluid widths by habit.
- [ ] Large elements compress more aggressively than small elements at narrow widths where appropriate.
- [ ] Space between groups is greater than space within groups.
- [ ] No ambiguous label/input, heading/content, or list grouping.
- [ ] Dense areas are intentionally dense.

## Typography

- [ ] Type sizes come from the scale.
- [ ] Body/UI text is not too light.
- [ ] Large headings have appropriately tighter line-height.
- [ ] Small/wide text has enough line-height.
- [ ] Mixed-size inline text aligns cleanly, preferably by baseline.
- [ ] Long centered paragraphs have been avoided.
- [ ] All-caps labels use appropriate tracking if needed.
- [ ] Numeric tables are aligned for comparison.

## Color and accessibility

- [ ] WCAG 2.2 AA contrast is met for changed content/controls.
- [ ] Color is never the only state cue.
- [ ] Foreground colors on colored surfaces are explicit tokens, not casual opacity hacks.
- [ ] Semantic state colors remain semantically understandable.
- [ ] Focus-visible styles are clear on every surface.

## Depth and separation

- [ ] Shadows communicate elevation or interaction rather than generic decoration.
- [ ] Border count is justified; spacing/backgrounds are used where simpler.
- [ ] Overlap does not clip content or focus rings.
- [ ] Elevation levels are consistent.

## Media

- [ ] Image quality and crop suit the target size.
- [ ] Text over images remains readable for realistic crops.
- [ ] Icons are used near their intended visual scale.
- [ ] Screenshots are legible rather than microscopically scaled.
- [ ] User/unpredictable images have controlled aspect ratio and fit behavior.
- [ ] Image dimensions/aspect ratios prevent layout shift.
- [ ] Alt text semantics are correct.

## States and interaction

- [ ] Default, hover, focus-visible, pressed, selected/current, disabled and loading states are handled where relevant.
- [ ] Empty/error/success states are intentional.
- [ ] Keyboard behavior works.
- [ ] Essential interactions do not require hover.
- [ ] Pointer targets are comfortably usable on touch.
- [ ] Reduced-motion preference is respected.

## Responsive and verification

- [ ] Small mobile inspected.
- [ ] Typical mobile inspected.
- [ ] Tablet/narrow desktop inspected.
- [ ] Standard desktop inspected.
- [ ] Transition widths checked where the layout changes.
- [ ] Long/edge-case content tested.
- [ ] Relevant verification passes: `npm run build` for frontend assets, targeted `php artisan test --compact` for behavior, and `vendor/bin/pint --dirty --format agent` when PHP changed.
- [ ] Unrelated issues are reported rather than silently expanded into scope.
