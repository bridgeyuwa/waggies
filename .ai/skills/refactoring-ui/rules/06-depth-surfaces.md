# Depth, Surfaces, Borders, and Elevation

## Depth must communicate something

Use depth to clarify:

- which element is above another,
- which surface is interactive,
- which item is being dragged/pressed,
- which overlay demands attention,
- which region is inset or grouped.

Do not add shadows simply because a card looks plain.

## Maintain a coherent light model

If the visual system uses raised/inset effects, keep lighting direction consistent. Subtle highlights and shadows should not imply contradictory light sources.

Avoid photorealistic skeuomorphism. The goal is perceptual clarity, not simulation for its own sake.

## Use an elevation scale

Prefer a small named set of elevations rather than one-off shadows:

- near-flat / control,
- raised card,
- floating menu/popover,
- sticky/floating surface,
- modal/dialog.

Higher elevation generally uses a broader, softer shadow and may reduce the tight contact shadow. Interaction can temporarily change elevation: drag lifts; press lowers.

## Two-part shadows

When the design system needs richer elevation, a shadow can combine:

- a broad soft component for cast shadow,
- a tighter darker component near the surface.

Do not apply this everywhere. Reserve complex shadows for components that benefit from meaningful elevation.

## Flat design can still have layers

Depth can be communicated with:

- lighter/darker surfaces,
- tonal contrast,
- solid offset shadows,
- overlap,
- spacing and background transitions.

A no-shadow aesthetic should not become a no-hierarchy aesthetic.

## Overlap deliberately

Overlapping elements can create layering and energy when the relationship is clear. Ensure overlap does not:

- hide content at responsive widths,
- reduce tap targets,
- create reading-order confusion,
- clip focus rings,
- cause images to visually collide.

When overlapping images, preserve separation with a background-matching ring/gap or another deliberate boundary.

## Use fewer borders, not zero borders

Borders are appropriate for controls, states, and surfaces that need a crisp boundary. They should not be the default solution for every separation problem.

Before adding a border, consider whether separation is better communicated by:

- spacing,
- a surface change,
- a subtle shadow,
- grouping,
- typography.

If a border is required for accessibility or control discoverability, do not remove it merely to achieve a cleaner aesthetic.

