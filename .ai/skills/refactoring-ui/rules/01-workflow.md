# Workflow: Feature First, Detail Later, Systems Early

## Start from the feature

Do not begin a UI refactor by redesigning the shell, navbar, or global layout unless the problem actually lives there. First define:

- the user task,
- the key content,
- the primary action,
- the necessary secondary actions,
- the states that can occur,
- the constraints imposed by real data and real copy.

The page shell should be a consequence of those needs, not a substitute for understanding them.

## Separate structure from polish

When a design is weak, first inspect:

1. content order,
2. hierarchy,
3. grouping,
4. widths,
5. spacing,
6. type scale,
7. color and effects.

A useful diagnostic is a temporary grayscale review. If the hierarchy collapses when brand color is removed, the structure is doing too little work.

Do not start by adding gradients, shadows, illustrations, decorative cards, or animations to compensate for weak hierarchy.

## Work in short cycles

For an existing product, refactor one coherent slice at a time:

1. audit the slice,
2. state the design problem,
3. propose the smallest useful change,
4. implement,
5. inspect the real rendered result,
6. correct issues found in use,
7. stop before drifting into unrelated areas.

Do not design every edge case in the abstract. Build a stable base and then handle the real edge cases that appear.

## Do not promise unbuilt behavior

Visual affordances must correspond to real behavior. Do not add controls, filters, tabs, upload zones, booking actions, or states that are not implemented or intentionally scheduled in the task.

If a nice-to-have visual treatment depends on unbuilt behavior, leave it out rather than creating a dead affordance.

## Constrain recurring choices

Any time the same kind of micro-decision appears more than once, look for a system:

- spacing,
- width,
- height,
- font size,
- weight,
- line-height,
- radius,
- border width,
- shadow/elevation,
- color,
- opacity,
- icon size,
- motion duration/easing.

Do not create a new token merely because a component is slightly awkward. First test adjacent values in the existing scale. Add a new token only when the system genuinely lacks a needed step.

## Process of elimination

When choosing among tokens, compare neighboring values rather than tuning by tiny increments. If one option is too small and the next is too large, that is evidence the scale may need a new deliberate step; it is not permission to sprinkle a one-off value into the component.

## Refactor guardrail

A UI refactor is not a general cleanup pass. Report unrelated defects separately. Do not opportunistically restructure backend logic, data models, routing, or unrelated components unless they block the requested UI work.

