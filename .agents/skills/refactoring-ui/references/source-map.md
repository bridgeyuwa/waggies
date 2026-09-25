# Source Coverage Map — Refactoring UI to AI Rules

This file verifies that the pack covers the full conceptual outline of *Refactoring UI* rather than extracting only the popular spacing/color advice. The entries below are paraphrased implementation guidance, not a reproduction of the book.

| Source topic | Implementation interpretation | Encoded in |
| --- | --- | --- |
| Start with a feature, not a layout | Begin from user task/content/action; shell follows feature needs | `01-workflow.md` |
| Detail comes later | Solve structure/hierarchy/spacing before decoration | `01-workflow.md` |
| Hold the color | Use grayscale/low-decoration review to expose weak hierarchy | `01-workflow.md` |
| Don't over-invest | Treat wireframes/prototypes as decision tools, not deliverables | `01-workflow.md` |
| Don't design too much | Refactor/build one coherent slice rather than the entire product in abstraction | `01-workflow.md` |
| Work in cycles | Audit → implement → inspect real UI → iterate → stop | `01-workflow.md` |
| Be a pessimist | Do not design unsupported functionality; ship smallest useful version | `01-workflow.md` |
| Choose a personality | Keep visual language aligned with product character | project rules + `04`, `05`, `06` |
| Font choice | Typography roles communicate character; body text prioritizes legibility | `04-typography.md` |
| Color | Brand/semantic color choices support intended tone | `05-color-accessibility.md` |
| Border radius | Radius is a personality token; use consistent scale | project token rules |
| Language | Interface copy contributes to tone and trust | project content rules |
| Decide what you want | Study audience/context; do not clone competitors | project rules |
| Limit your choices | Restrict recurring choices with tokens | `01-workflow.md` |
| Define systems in advance | Establish scales/tokens before proliferating one-offs | `01-workflow.md` |
| Process of elimination | Compare neighboring token values instead of pixel-tuning | `01-workflow.md` |
| Systematize everything | Cover type, spacing, color, radius, shadow, opacity, sizing | `01-workflow.md` + token rules |
| Not all elements are equal | Explicit primary/secondary/tertiary hierarchy | `02-hierarchy-actions.md` |
| Size isn't everything | Use weight/contrast/position/space before huge size gaps | `02-hierarchy-actions.md` |
| Grey text on colored backgrounds | Use explicit foreground tokens tuned to colored surfaces | `05-color-accessibility.md` |
| Emphasize by de-emphasizing | Quiet competing elements instead of endlessly boosting the primary one | `02-hierarchy-actions.md` |
| Labels are a last resort | Avoid mechanical label/value display where context is sufficient | `02-hierarchy-actions.md` |
| Combine labels and values | Write scan-friendly human units/phrasing | `02-hierarchy-actions.md` |
| Labels are secondary | Data is primary when value is what users need | `02-hierarchy-actions.md` |
| When to emphasize a label | In specification-like scanning, field names can lead | `02-hierarchy-actions.md` |
| Visual hierarchy vs document hierarchy | Semantic heading level is independent from visual size | `02-hierarchy-actions.md`, `09-modern-augmentation.md` |
| Balance weight and contrast | Heavy icons/shapes can use lower contrast; subtle lines may need weight | `02-hierarchy-actions.md` |
| Semantics are secondary (actions) | Button prominence follows task priority, not verb category | `02-hierarchy-actions.md` |
| Destructive actions | Destructive styling becomes dominant at confirmation, not everywhere | `02-hierarchy-actions.md` |
| Start with too much whitespace | Begin comfortably spacious and remove deliberately | `03-layout-spacing-responsive.md` |
| Dense UIs have their place | Density is a product decision, not default compression | `03-layout-spacing-responsive.md` |
| Spacing/sizing system | Use a nonlinear token scale with meaningful steps | `03-layout-spacing-responsive.md` |
| Linear scale won't work | Adjacent large values need larger perceptual differences | `03-layout-spacing-responsive.md` |
| Don't fill the whole screen | Bound forms/text/cards to useful widths | `03-layout-spacing-responsive.md` |
| Shrink the canvas | Narrow layout first can expose real constraints | `03-layout-spacing-responsive.md` |
| Thinking in columns | Use supporting columns rather than stretching primary content | `03-layout-spacing-responsive.md` |
| Don't force it | Use large space when needed; avoid artificial fill or compression | `03-layout-spacing-responsive.md` |
| Grids are overrated | Grid is a tool; fixed/intrinsic widths are valid | `03-layout-spacing-responsive.md` |
| Not all elements should be fluid | Bound stable components; let flexible regions absorb space | `03-layout-spacing-responsive.md` |
| Don't shrink until needed | Keep optimal width until viewport pressure requires change | `03-layout-spacing-responsive.md` |
| Relative sizing doesn't scale | Responsive relationships change by context | `03-layout-spacing-responsive.md` |
| Relationships within elements | Component padding/type may scale nonlinearly | `03-layout-spacing-responsive.md` |
| Avoid ambiguous spacing | Space around groups > space within groups | `03-layout-spacing-responsive.md` |
| Establish a type scale | Use restricted named type tokens | `04-typography.md` |
| Modular scales | Mathematical ratios are optional, not authoritative | `04-typography.md` |
| Fractional values | Avoid accidental off-scale computed sizes | `04-typography.md` |
| Need more sizes | UI scales should be practical rather than mathematically pure | `04-typography.md` |
| Hand-crafted scales | Deliberate project-specific scale is acceptable/preferred | `04-typography.md` |
| Avoid em units | Prefer rem tokens for core scale; use em/ch only intentionally | `04-typography.md`, modernized |
| Use good fonts | Prioritize legibility, role fit, and family quality | `04-typography.md` |
| Safe neutral fonts | Body/UI type can be neutral; personality may live elsewhere | `04-typography.md` |
| Multiple weights | Complete families are usually safer for UI hierarchy | `04-typography.md` |
| Optimize for legibility | Avoid condensed/low-x-height display styles for dense UI text | `04-typography.md` |
| Crowd/precedent | Use proven typography as reference, not blind novelty | `04-typography.md` |
| Line length | Bound reading measure, roughly 45–75 characters | `04-typography.md` |
| Wider content | Media may be wider than text within same section | `04-typography.md` |
| Baseline, not center | Baseline-align mixed type sizes where practical | `04-typography.md` |
| Line-height proportional | Leading responds to type size and line length | `04-typography.md` |
| Not every link needs color | Contextual link treatment; body links remain clearly identifiable | `04-typography.md`, modernized for accessibility |
| Align for readability | Default to language-direction alignment; center only short copy | `04-typography.md` |
| Right-align numbers | Align numeric data for scanning/comparison | `04-typography.md` |
| Hyphenate justified text | If justification is used, prevent excessive word gaps | `04-typography.md` |
| Letter spacing | Leave defaults unless role justifies adjustment | `04-typography.md` |
| Tighten headlines | Display headings may use slight negative tracking | `04-typography.md` |
| All-caps legibility | Uppercase labels may need extra tracking | `04-typography.md` |
| HSL over hex | Modernized: use perceptual/semantic color tokens, preferably existing OKLCH | `05-color-accessibility.md` |
| More colors than expected | Real UI needs neutral, brand, semantic families with steps | `05-color-accessibility.md` |
| Greys | Build a coherent neutral family; avoid raw black by default | `05-color-accessibility.md` |
| Primary colors | Use a full brand family, not one brand swatch | `05-color-accessibility.md` |
| Accent colors | Separate semantic/attention roles from brand color | `05-color-accessibility.md` |
| Define shades up front | Tokenize palette before component proliferation | `05-color-accessibility.md` |
| Choose base/edges/fill gaps | Construct scales from real text/surface use cases | `05-color-accessibility.md` |
| It's not a science | Test real components and adjust perceptually | `05-color-accessibility.md` |
| Lightness and saturation | Preserve chroma/perceived strength across light/dark steps | `05-color-accessibility.md` |
| Perceived brightness/hue rotation | Small hue/chroma changes can preserve perceived energy | `05-color-accessibility.md` |
| Greys need not be grey | Warm/cool chromatic neutrals can express personality | `05-color-accessibility.md` |
| Accessible need not be ugly | Use accessible tonal hierarchy instead of max contrast everywhere | `05-color-accessibility.md`, `09-modern-augmentation.md` |
| Flip contrast | Light tinted surface + dark colored text can reduce visual dominance | `05-color-accessibility.md` |
| Don't rely on color alone | Always add non-color state cues | `05-color-accessibility.md` |
| Emulate a light source | Maintain consistent depth cues | `06-depth-surfaces.md` |
| Raised/inset elements | Use subtle consistent surface/elevation treatment | `06-depth-surfaces.md` |
| Don't get carried away | Avoid decorative pseudo-realism | `06-depth-surfaces.md` |
| Shadows convey elevation | Map shadow intensity/blur to virtual z-level | `06-depth-surfaces.md` |
| Elevation system | Use a small named elevation scale | `06-depth-surfaces.md` |
| Shadows + interaction | Lift on drag/hover where meaningful; lower on press | `06-depth-surfaces.md` |
| Two-part shadows | Broad cast + tight contact shadow where justified | `06-depth-surfaces.md` |
| Account for elevation | Contact shadow diminishes as virtual elevation increases | `06-depth-surfaces.md` |
| Flat designs still have depth | Use tonal surfaces/solid offsets/overlap | `06-depth-surfaces.md` |
| Overlap creates layers | Use overlap deliberately and responsively | `06-depth-surfaces.md` |
| Overlapping images | Preserve separation between overlapping media | `06-depth-surfaces.md` |
| Use good photos | Media quality is part of design quality | `07-images-media.md` |
| Text needs consistent contrast | Control image dynamics rather than only text color | `07-images-media.md` |
| Overlay / lower contrast / colorize / text shadow | Toolkit for controlled image text contrast | `07-images-media.md` |
| Intended size | Use assets drawn/captured for their target scale | `07-images-media.md` |
| Don't scale up/down icons blindly | Use size-appropriate icon artwork | `07-images-media.md` |
| Don't scale down screenshots | Crop/capture/simplify rather than making details microscopic | `07-images-media.md` |
| Beware user-uploaded content | Constrain aspect ratio, fit, and bleed | `07-images-media.md` |
| Control shape and size | Fixed media boxes for repeated layouts | `07-images-media.md` |
| Prevent background bleed | Subtle inner boundary rather than clashing hard border | `07-images-media.md` |
| Supercharge defaults | Improve existing markers/controls/links before adding containers | `08-finishing-states.md` |
| Accent borders | Use restrained brand punctuation | `08-finishing-states.md` |
| Decorate backgrounds | Subtle surface/gradient/pattern/shape, low contrast | `08-finishing-states.md` |
| Empty states | First-class onboarding state with clear next action | `08-finishing-states.md` |
| Use fewer borders | Prefer spacing/background/shadow when they communicate better | `06-depth-surfaces.md`, `08-finishing-states.md` |
| Think outside the box | Recompose menus/tables/radios when task benefits, preserving semantics | `08-finishing-states.md` |
| Look for decisions you wouldn't make | Study good interfaces for non-obvious decisions | `waggies-ui-audit` workflow |
| Rebuild favorite interfaces | Use reconstruction as a learning tool, not as a copying mandate | `waggies-ui-audit` workflow |

## Modern additions not explicit in the book

The pack intentionally adds: WCAG 2.2 AA, keyboard/focus behavior, touch target sizing, reduced motion, responsive transition testing, semantic custom controls, empty/error/loading/success completeness, performance/layout-shift awareness, semantic design tokens, OKLCH support, intrinsic/container-aware layout, Laravel's media and asset pipeline, and explicit implementation guardrails.
