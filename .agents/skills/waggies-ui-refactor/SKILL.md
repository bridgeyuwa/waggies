---
name: waggies-ui-refactor
description: Implement a bounded UI refactor for the Waggies website using its project rules, Refactoring UI principles, and WCAG 2.2 AA. Trigger when asked to improve, redesign, polish, refactor, or fix a Waggies page/component/navigation/form while preserving product behavior and the established Waggies design system. Includes strict scope, verification, and stop conditions.
---

# Waggies UI Refactor

Waggies public UI is Laravel Blade-first with Alpine.js and Tailwind CSS v4. Use Livewire only for meaningful server-side state and Filament only for administration. Preserve the existing Waggies component and token system.

Use this skill for implementation after the target and desired outcome are known.

## Before editing

1. Read `.ai/rules/index.md` and all rules matching every file in scope.
2. Read `.ai/skills/refactoring-ui/SKILL.md` and the relevant detailed rules.
3. Inspect the target file, direct child components, shared primitives it uses, and the relevant token/style definitions.
4. Determine the primary user goal and CTA hierarchy for the target page.
5. Identify what behavior must remain unchanged.
6. Write a short implementation plan before making changes.

## Refactor in passes

### Pass 1 — Structure and hierarchy

- Fix content order and grouping.
- Establish primary/secondary/tertiary actions.
- Remove competing emphasis.
- Correct semantic structure without using semantics as a visual-size constraint.

### Pass 2 — Layout and spacing

- Correct width constraints.
- Remove unnecessary full-width stretching.
- Normalize spacing to existing tokens.
- Make grouping unambiguous.
- Check mobile structure before adding desktop flourish.

### Pass 3 — Typography

- Normalize to project type roles/scale.
- Fix line length and line-height.
- Correct alignment/tracking issues.
- Preserve Waggies font personality.

### Pass 4 — Color and state

- Use semantic tokens.
- Correct hierarchy/contrast.
- Make active/current/selected states distinct from hover/focus.
- Ensure state does not depend on color alone.

### Pass 5 — Depth, media, finishing

- Use borders/shadows/surfaces purposefully.
- Correct image crop/aspect ratio/resolution.
- Add restrained finishing details only after structure works.
- Implement empty/loading/error/success states that are in scope.

### Pass 6 — Accessibility and interaction

Verify keyboard, focus-visible, labels, accessible names, menu/dialog semantics, pointer targets, reduced motion, and WCAG 2.2 AA contrast.

### Pass 7 — Responsive verification

Inspect all relevant transition widths, not only one mobile and one desktop screenshot.

## Engineering constraints

- Preserve the current framework/component architecture unless it directly prevents the requested refactor.
- Reuse existing dependencies and primitives.
- Avoid adding a dependency for an effect that can be implemented cleanly with existing CSS/components.
- Keep Blade-first rendering intentional; do not add Livewire or client-side JavaScript for static styling.
- Do not hide accessibility problems behind custom visuals.
- Do not introduce raw one-off colors/spacing/radii/shadows when project tokens exist.

## Verification

Run the narrowest relevant checks first, then broader checks if warranted. For this Laravel Waggies repo, use:

- targeted `php artisan test --compact`,
- `npm run build` when views, CSS, or JavaScript are changed,
- `vendor/bin/pint --dirty --format agent` when PHP is changed,
- visual inspection at narrow and wide widths,
- keyboard/focus walkthrough,
- contrast/state review.

Use only the repository's actual Laravel and package scripts.

## Stop conditions

- Stop after the requested slice is implemented and verified.
- Do not fix unrelated issues discovered during the work; report them separately.
- If the same implementation approach fails twice for the same reason, stop and diagnose before attempting a third variation.
- If a required change would alter product behavior, architecture, or dependencies beyond the agreed scope, stop and surface the decision instead of silently expanding scope.
- If existing design-system rules conflict, report the conflict and use the narrower/more recent project decision when it can be determined from the repo.

## Completion report

Summarize:

- files changed,
- design problems fixed,
- behavior preserved,
- accessibility/responsive checks performed,
- commands run and results,
- remaining out-of-scope issues.
