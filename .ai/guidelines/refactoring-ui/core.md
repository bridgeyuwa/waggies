# Refactoring UI — Core Guidance

For Waggies, apply these principles within the Laravel 13 + Blade + Alpine.js + Tailwind CSS v4 frontend. The current Waggies implementation and its existing tokens are the starting evidence; the project rules define the intended direction.

Apply these principles whenever a task changes the visual interface, layout, hierarchy, typography, color, imagery, responsiveness, interaction states, or design-system behavior.

## Load the right skill

- For detailed UI design or review work, load the `refactoring-ui` skill.
- For an evidence-based Waggies audit with no implementation, load `waggies-ui-audit`.
- For a bounded Waggies implementation/refactor, load `waggies-ui-refactor`.

## Foundational principles

- Start from the **feature, user task, and content priority**, not from a decorative shell.
- Separate structural decisions from polish. Solve content, hierarchy, grouping, layout, and spacing before effects and ornament.
- Work in short design/implementation cycles. Prefer the smallest coherent improvement that can be built and verified over a speculative full-site redesign.
- Never imply functionality that the product does not actually support.
- Use constrained systems for recurring choices: type, spacing, sizing, color, radius, border, shadow, opacity, and motion.
- Establish a clear primary/secondary/tertiary hierarchy. When everything is emphasized, nothing is emphasized.
- Prefer de-emphasizing competing elements over continuously adding emphasis to the primary element.
- Keep semantic document structure and visual prominence separate: choose HTML for meaning/accessibility, then style it according to the intended visual hierarchy.
- Treat spacing as a relationship signal. Space inside a group must generally be smaller than space between groups.
- Give components only as much width as their content needs. Do not stretch elements merely because screen space exists.
- Do not assume everything should scale proportionally. Large elements often need to shrink faster than small elements at narrow viewports.
- Keep text readable: controlled line length, deliberate line-height, logical alignment, and a restricted type scale.
- Use color to reinforce existing meaning, never as the sole carrier of state or information.
- Use borders, shadows, backgrounds, and overlap only when they communicate separation, elevation, focus, or layering.
- Design with real media constraints. Images, icons, screenshots, avatars, and user content need intentional aspect ratios and target sizes.
- Empty, loading, error, success, disabled, hover, focus, pressed, and selected states are part of the component—not afterthoughts.

## Decision discipline

Before introducing any new visual value or pattern:

1. Inspect nearby components and the existing token system.
2. Reuse an existing token/pattern if it already solves the problem.
3. If no existing option works, explain why before adding a new token or pattern.
4. Do not create one-off values to solve local discomfort that should be addressed at the system level.

## Scope discipline

For refactors, preserve behavior unless behavior change is explicitly requested. Do not use a visual task as permission for unrelated architecture changes, dependency additions, route changes, data-model changes, or copy rewrites outside the affected experience.

