# Layout, Spacing, Width, and Responsive Behavior

## Start with generous space, then remove

When a composition feels cramped, do not incrementally add 2px/4px until it stops looking broken. Start from a clearly comfortable amount of whitespace and reduce it until the composition feels connected without becoming dense.

Dense interfaces are valid when information density is a real product requirement. Density must be deliberate, not the accidental result of small defaults.

## Use a nonlinear spacing/sizing scale

A useful scale needs meaningful differences between adjacent values. Small values may be close together; large values should grow in larger steps.

Do not enforce a simplistic "everything is a multiple of 4" rule if it still leaves dozens of near-identical options. The goal is decision reduction, not arithmetic purity.

Use the existing project scale. If the project has no scale, define one before proliferating arbitrary spacing values.

## Give content the width it needs

Do not stretch forms, cards, text blocks, sidebars, or media just to occupy available screen width.

Prefer:

- `max-width` constraints,
- intrinsic content sizing,
- flexible main regions with bounded supporting regions,
- separate columns when that improves balance without making primary content too wide.

A full-width navigation bar does not imply every child section should be full-width.

## Do not worship the grid

Grids are tools, not laws.

Use fluid percentage tracks when content should actually grow/shrink. Use fixed or bounded widths for elements whose usability depends on a stable measure (sidebars, forms, metadata rails, dialogs, reading columns).

Do not shrink an element before the viewport forces you to. If a card is best at a certain width, keep it there with `max-width` and only let it shrink below that threshold.

## Relative sizing is not a universal rule

Do not preserve the same proportion between large and small elements at every viewport.

At narrow widths:

- large display type often needs to shrink aggressively,
- body text changes less,
- generous desktop padding often compresses,
- oversized media may crop or recompose,
- controls may become full width while remaining comfortably tappable.

Component internals can also scale nonlinearly. A large button may have disproportionately more padding than a compact button.

## Spacing communicates grouping

When borders/backgrounds are absent, spacing becomes the primary grouping signal.

General rule: **space between groups > space within groups**.

Check this in:

- label/input pairs,
- stacked form fields,
- headings and following content,
- list items and their wrapped lines,
- cards with metadata clusters,
- horizontal control groups,
- navigation sections.

If users can plausibly pair the wrong label, description, icon, or action with an item, spacing is ambiguous.

## Mobile-first as a constraint tool

When a layout is hard to reason about, test a narrow viewport first. Narrow constraints reveal what is truly essential and prevent desktop whitespace from hiding hierarchy problems.

Do not force desktop structure onto mobile. Reorder or stack only when semantics and reading order remain correct.
