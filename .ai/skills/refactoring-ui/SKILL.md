---
name: refactoring-ui
description: Apply this skill whenever designing, reviewing, or refactoring frontend UI using the principles of Refactoring UI. Trigger for visual hierarchy, spacing, sizing, responsive layout, typography, colors, design tokens, buttons and action hierarchy, shadows/elevation, images, empty states, component polish, accessibility, or design-system decisions. Also use for UI code review when the problem is that an interface feels ordinary, cluttered, inconsistent, hard to scan, visually weak, or visually over-designed.
---

# Refactoring UI

Waggies context: public UI is Laravel Blade-first with Alpine.js and Tailwind CSS v4. Livewire is selective and server-state-driven; Filament is for administration. Use the existing Waggies components and `resources/css/app.css` token system.

This skill is a modern, implementation-oriented interpretation of *Refactoring UI*. It is designed to help an agent make disciplined visual decisions without turning a refactor into an uncontrolled redesign.

## Consistency first

Before applying any rule:

1. Inspect the existing design tokens, neighboring components, page structure, copy conventions, and interaction patterns.
2. Prefer an established project pattern when it is coherent and accessible.
3. Deviate only when the existing pattern creates a clear usability, accessibility, visual-hierarchy, or consistency defect.
4. If you introduce a new reusable token or pattern, justify it and use it consistently in the changed scope.

## How to apply this skill

1. Identify the user's task and the primary user action for the affected feature/page.
2. Read the project rules in `.ai/rules/index.md` that match every file you may edit.
3. Map the task to the rule index below and read only the relevant files.
4. Audit before editing. Identify the specific hierarchy/layout/system problem you are solving.
5. Make the smallest coherent change that fixes the problem while preserving behavior.
6. Verify narrow and wide viewports, interaction states, and WCAG 2.2 AA concerns.
7. Re-read the diff against the relevant rules. Stop when the requested scope is complete.

## Rule index

| Concern | Read |
| --- | --- |
| Feature-first workflow, low-fidelity thinking, short cycles, constraint systems | `rules/01-workflow.md` |
| Visual hierarchy, labels, CTA hierarchy, emphasis/de-emphasis | `rules/02-hierarchy-actions.md` |
| Width, whitespace, spacing scales, responsive behavior, grouping, grids | `rules/03-layout-spacing-responsive.md` |
| Type scales, fonts, line length, line height, alignment, letter spacing | `rules/04-typography.md` |
| Palette construction, semantic colors, contrast, color-blind-safe states | `rules/05-color-accessibility.md` |
| Borders, surfaces, shadows, elevation, overlap/layers | `rules/06-depth-surfaces.md` |
| Photography, icons, screenshots, avatars, user-uploaded media | `rules/07-images-media.md` |
| Empty states, defaults, accent details, separation, non-boring components | `rules/08-finishing-states.md` |
| WCAG 2.2, modern CSS, motion, touch, states, performance and current web practices | `rules/09-modern-augmentation.md` |

For a complete coverage map from the book to this skill, read `references/source-map.md`.

For final review, use `references/review-checklist.md`.

## Core decision rules

- Structure before polish.
- Hierarchy before decoration.
- Systems before arbitrary values.
- Content width before container width.
- Grouping before borders.
- Contrast and weight before raw font-size escalation.
- Accessibility before stylistic cleverness.
- Semantic HTML before visual mimicry.
- Product flow before generic CTA conventions.
- Existing project character before trend-driven redesign.

