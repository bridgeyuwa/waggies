# Color Systems and Accessible Contrast

## Use color as a system

Components should consume semantic tokens, not arbitrary raw color values.

A mature palette normally includes:

- a neutral scale,
- one or two brand/primary families,
- semantic families for success, warning, danger/error, and informational states,
- enough steps within each family to support text, borders, surfaces, hover/pressed states, and tinted backgrounds.

Do not solve each component by generating a new tint on the fly.

## Prefer perceptual token construction

The book recommends HSL over hex because hue/saturation/lightness are easier to reason about. In a modern codebase with OKLCH tokens, keep OKLCH as the source of truth unless there is a compatibility reason not to.

The principle is: **choose colors through a deliberate, inspectable system rather than unrelated literals**.

## Define the scale before component use

For a new color family:

1. choose a useful base step,
2. choose the darkest practical text/strong state,
3. choose the lightest practical tinted surface,
4. fill intermediate steps by perceptual comparison,
5. test the colors in real components,
6. adjust the scale rather than adding local one-offs.

Do not assume equal mathematical lightness steps will look perceptually equal.

## Keep chroma alive at the extremes

Very light/dark colors often need adjusted chroma/saturation to avoid washed-out results. Hue can also shift slightly across a scale to maintain a coherent perceived temperature/brightness.

Use such shifts conservatively. A shade family should still read as one color family.

## Neutral does not mean zero-chroma grey

Warm or cool neutrals can reinforce product personality. Choose one neutral temperature direction and keep the scale coherent rather than mixing cold blue-greys with unrelated warm browns unless there is a deliberate role distinction.

## Contrast and hierarchy

Meet WCAG 2.2 AA, but do not make every accessible element maximally dark or high-contrast. Hierarchy can still exist through accessible tonal steps, weight, spacing, and surfaces.

On colored backgrounds, avoid lowering opacity on white text as a default de-emphasis technique. It can look disabled and can become unpredictable over images/patterns. Prefer an explicit foreground token designed for that surface.

When a dark saturated surface would become too dominant solely to support white text, consider flipping the treatment: a light tinted surface with dark colored text often preserves brand character with less visual weight.

## Never rely on color alone

Positive/negative, selected/unselected, error/success, active/inactive, chart series, and validation states must have a non-color cue.

Examples:

- icon + text,
- icon direction + value,
- underline/indicator + color,
- pattern/dash style + color for charts,
- text label + surface color,
- shape + color.

## Semantic colors are not brand colors

Do not force brand purple into success/error/warning roles if it damages clarity. Brand color and semantic state color serve different jobs.

