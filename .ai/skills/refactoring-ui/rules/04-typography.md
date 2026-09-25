# Typography and Readability

## Use a restricted type scale

Do not introduce arbitrary one-off font sizes. Use the project's established type tokens.

A UI scale should have enough steps for body, small supporting text, labels, section titles, page titles, and display text without creating a different size for every component.

Prefer `rem`-based tokens. Avoid nested `em` sizing for the core type scale when it produces computed sizes that fall outside the system.

## Font choice follows role

For UI/body copy, prioritize legibility and a complete weight/style family. Display type can carry more personality, but it should not be forced into small labels or dense controls.

Do not introduce a new typeface to solve a hierarchy problem that should be solved with existing type roles.

## Line length

For paragraph-style reading, keep measure roughly in the 45–75 character range as a practical target. `ch` or a bounded content width is usually more robust than stretching paragraphs to the container edge.

If a section contains both wide media and long-form text, the text may be narrower than the media. Shared container width is not a reason to sacrifice readability.

## Align mixed sizes by baseline

When different text sizes share a row, align them by typographic baseline where possible rather than geometric center. This is especially relevant for prices/units, titles/actions, metrics/labels, and compact metadata rows.

## Line-height depends on size and measure

Do not use one line-height ratio everywhere.

- Smaller text generally needs more leading.
- Longer lines generally need more leading.
- Large display headings generally need tighter leading.
- Multi-line headings require visual inspection; default utility values are not always correct.

## Link styling depends on context

Links in body copy need a clear persistent affordance and must satisfy accessibility requirements. In navigation or link-dense UI, every link does not need bright accent color; use weight, contrast, underline, background, or state indicators according to hierarchy.

Ancillary links may become more visually apparent on hover/focus, but essential actions must not depend on hover discovery.

## Alignment

- Match text alignment to language direction by default.
- Avoid center-aligned long-form text; centered copy should generally remain short.
- Right-align numbers in data tables when comparison is important.
- If justified text is intentionally used for editorial effect, enable appropriate hyphenation and inspect word spacing.

## Letter spacing

Trust the typeface default unless there is a reason to change it.

Common justified exceptions:

- slightly tighter tracking for large display headings when the face feels too open,
- increased tracking for all-caps labels to improve recognition.

Do not stretch a headline face into body text or repair a poor font-role choice with extreme tracking.

