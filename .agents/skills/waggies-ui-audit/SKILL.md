---
name: waggies-ui-audit
description: Audit the Waggies website UI without editing files. Use when asked to review, critique, inspect, assess, or plan improvements to a Waggies page, component, navigation, form, responsive layout, CSS/design system, accessibility, or visual quality. Produces evidence-based findings grounded in the Waggies project rules, Refactoring UI principles, and WCAG 2.2 AA, with a bounded implementation plan.
---

# Waggies UI Audit

Waggies public UI is Laravel Blade + Alpine.js + Tailwind CSS v4. Inspect actual Blade templates, shared Blade components, `resources/css/app.css`, Alpine modules, and any Livewire/Filament surface in scope; never infer a UI from a framework-agnostic filename alone.

This is an **audit skill**, not an implementation skill. Do not edit files unless the user explicitly converts the task into implementation.

## Required context

Before auditing:

1. Read `.ai/rules/index.md` when it is available; Laravel Boost owns rebuilding that index from rule frontmatter.
2. Read every project rule whose glob covers the target files.
3. Read `.ai/skills/refactoring-ui/SKILL.md`.
4. Read the relevant detailed rule files from that skill.
5. Inspect the target page/component and its directly related tokens/styles/components. Do not infer the interface from filenames alone.

## Audit order

### 1. Product intent

State:

- target user goal,
- primary action,
- secondary actions,
- expected content/state variants,
- whether the current hierarchy communicates the product flow.

### 2. Existing system inventory

Identify what already exists for:

- type roles,
- spacing scale,
- color/semantic tokens,
- radius scale,
- borders,
- elevation/shadows,
- icon family,
- button/CTA variants,
- container widths,
- responsive breakpoints/containers,
- state styling.

Do not recommend a new system before documenting the current one.

### 3. Refactoring UI review

Review the target against:

- hierarchy and action prominence,
- whitespace and grouping,
- widths and responsive scaling,
- type scale/readability,
- color hierarchy and state semantics,
- borders/elevation/layering,
- media treatment,
- empty/loading/error/success states,
- finishing details and visual character.

### 4. WCAG 2.2 AA review

Check at least:

- semantic headings/landmarks,
- keyboard navigation and focus visibility,
- contrast,
- accessible names/labels,
- state not conveyed by color alone,
- target size,
- reflow/zoom resilience,
- reduced motion,
- error identification,
- menus/dialogs/disclosures/tabs semantics where present.

### 5. Responsive review

Inspect a small mobile width, common mobile width, tablet/narrow desktop, standard desktop, and any transition width where layout pressure appears.

Look for:

- premature wrapping,
- full-width elements that should be bounded,
- fixed elements that should flex,
- clipped focus rings,
- overlapped content,
- text measure problems,
- inconsistent action order,
- hit-target compression.

## Finding format

For each meaningful issue, report:

- **Location**: file/component/section.
- **Observed problem**: concrete evidence, not adjectives like "boring" alone.
- **Principle**: which Refactoring UI/modern rule is implicated.
- **User impact**: scanability, comprehension, trust, accessibility, task completion, responsive failure, consistency, etc.
- **Recommended direction**: outcome-oriented fix, not premature code.
- **Scope**: local / shared component / token-system change.

Use severity only to communicate implementation priority:

- **Critical**: blocks access/task completion or creates serious accessibility failure.
- **High**: strong usability/hierarchy/system defect.
- **Medium**: meaningful consistency/readability/polish defect.
- **Low**: optional refinement with limited user impact.

## Final output

End with:

1. **What should remain unchanged** — existing strengths/patterns worth preserving.
2. **Ordered implementation plan** — smallest coherent sequence, with dependencies.
3. **Out-of-scope observations** — useful issues discovered but not part of the requested scope.
4. **Verification plan** — viewports, states, accessibility, and code-quality commands relevant to the repo.

## Guardrails

- Do not edit files in audit mode.
- Do not prescribe a wholesale redesign when a local/system correction is enough.
- Do not invent product functionality.
- Do not replace established brand identity with generic SaaS aesthetics.
- Do not recommend dependencies before proving the existing stack cannot handle the need.
- Do not expand the audit into backend architecture unless backend behavior directly causes the UI defect.
