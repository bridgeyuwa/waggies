# Visual Hierarchy and Action Hierarchy

## Establish an explicit hierarchy

Before styling, classify visible information and actions:

- **Primary**: what the user came to do or understand.
- **Secondary**: useful supporting content/actions.
- **Tertiary**: optional, infrequent, or contextual content/actions.

A page should usually have one dominant action in a local decision context. Multiple equal-weight CTAs create hesitation and visual noise.

## Size is only one hierarchy tool

Use a combination of:

- font weight,
- contrast,
- color,
- whitespace,
- grouping,
- position,
- surface treatment,
- size.

Do not make primary text enormous or secondary text tiny just to manufacture hierarchy. Secondary information should remain readable.

For UI body text, avoid thin weights that reduce legibility. Use reduced contrast or size to de-emphasize before reaching for very light weights.

## De-emphasize competitors

When an important item is not standing out, first ask what is competing with it. Often the better fix is to quiet neighboring items:

- soften inactive navigation,
- remove a competing panel background,
- reduce redundant borders,
- lower supporting text contrast while keeping it accessible,
- remove decorative emphasis from secondary actions.

## Labels are supporting content

For data display—not form accessibility labels—avoid mechanical `Label: Value` formatting when context or formatting already makes the value clear.

Prefer human-readable combinations such as a number plus unit/category when it improves scanning.

When labels are needed:

- make the value dominant when users care about the value,
- make the label dominant when users are scanning for a known field name,
- never remove programmatic labels or accessible names from form controls.

## Document semantics do not dictate visual size

Use heading levels according to document structure, not appearance. An `h2` may be visually small; a visually large piece of text may not be the next semantic heading.

Never choose a heading level because the browser's default size happens to look right.

## Balance weight and contrast

Heavy visual shapes—solid icons, thick glyphs, filled badges—can overpower nearby text. Counterbalance them with lower contrast or smaller size.

Conversely, a low-contrast boundary that is too subtle can sometimes be made slightly heavier rather than much darker.

## Action hierarchy beats action semantics

Style actions based on importance in the current context:

- **Primary**: high visibility; usually solid or otherwise strongly emphasized.
- **Secondary**: clearly interactive but quieter; often tonal, outline, or lower-contrast.
- **Tertiary**: discoverable and unobtrusive; often text/link treatment.

A destructive action is not automatically the dominant action. Keep it quiet in routine contexts; make it strongly destructive only in the confirmation context where the user must explicitly commit to the destructive choice.

## Current/selected/active state

Current location or selected state must be detectable without depending solely on color. Weight, indicator, background, underline, icon, or position may support the distinction. Hover, focus, selected/current, and pressed states are different states and should not be collapsed into one ambiguous style.

