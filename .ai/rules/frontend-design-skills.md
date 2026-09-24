---
paths:
  - 'resources/views/**'
  - 'resources/css/**'
  - 'resources/js/**'
  - 'app/Livewire/**'
  - 'app/Filament/**'
  - 'app/Providers/Filament/**'
---

# Waggies Frontend Skills: Selection, Scope, and Conflict Resolution

## Purpose and stack

Waggies uses a TALL frontend stack:

```text
Tailwind CSS + Alpine.js + Laravel + Livewire
```

Blade and Alpine are the default public rendering and interaction model.
Livewire is appropriate when server-side state materially improves correctness,
persistence, authorization, or stateful behavior. Filament and Livewire remain
valid for administration.

This rule coordinates the installed frontend and design skills. It does not
replace their instructions, and it does not turn them into competing product
design authorities. A skill is automatically selected when its description
matches the task, but availability alone is never a reason to use it.

React, Next.js, Vue, Inertia, and React Native instructions from a skill are not
applicable to Waggies unless the task explicitly names a separate surface that
uses them. Do not add a dependency or replace the TALL stack as an incidental
design decision. Flux is not part of the current Waggies frontend stack and
must not be introduced by this rule.

## Authority order

When guidance conflicts, use this order:

1. Functional correctness, security, accessibility, and explicit user intent.
2. Waggies project rules and current product decisions.
3. Evidence from the current Waggies implementation and its users.
4. The selected lead skill's relevant specialty.
5. Supporting skills' scoped observations and recommendations.
6. Personal aesthetic preference or speculative redesign.

The current implementation is evidence, not proof that the foundation is
correct. A repeated pattern may be intentional, defective, or simply
undocumented. Do not preserve a flawed pattern merely because it already
exists, and do not replace an established pattern merely because a skill
prefers a different aesthetic.

## Skill families and routing matrix

Use the exact installed skill names in the plan. The family label explains the
responsibility; the status explains when the member may participate.

| Family | Exact skill | Status and responsibility |
| --- | --- | --- |
| Impeccable | `impeccable` | Primary for visual diagnosis and refinement: hierarchy, typography, spacing, layout, density, clarity, responsive behavior, accessibility, and polish. |
| Taste | `design-taste-frontend` | Primary for a genuinely new public/marketing composition, landing page, portfolio-like surface, or explicitly requested redesign. Not for dashboards, tables, wizards, or routine app UI. |
| Emil / motion | `emil-design-eng` | Motion and interaction-craft principles: purpose, frequency, easing, timing, physicality, interruptibility, and reduced motion. |
| Emil / motion | `animate` | Implementation of a specific animation, transition, gesture, or interaction-feedback change. |
| Emil / motion | `review-animations` | Review of motion in a specific component or diff; motion-only review. |
| Emil / motion | `improve-animations` | Read-only audit and roadmap for motion across a feature area or product. Use only for an explicit audit request. |
| Emil / motion | `find-animation-opportunities` | Proposal-only search for places where purposeful motion could help. |
| Emil / motion | `animation-vocabulary` | Naming a described motion effect; it does not choose or implement the solution. |
| Conditional motion | `apple-design` | Spatial, gesture, spring, material, or Apple-like interaction guidance only when the brief or existing surface supports it. |
| Technical | `tailwindcss-development` | Tailwind utility, responsive layout, component styling, and Tailwind v4 work. |
| Technical | `livewire-development` | Livewire-specific work only. Do not introduce Livewire where Blade and Alpine are sufficient. |
| Technical | `filament-development` | Filament administration UI only. It does not govern public Waggies pages. |
| System | `frontend-system-redesign` | Deliberate system-wide frontend standardisation or redesign; requires the system-refactor process below. |
| Explicit-only | `prototype` | Use only when the user explicitly requests multiple live design variants. |
| Explicit-only | `pick-ui-library` | Use only when the user explicitly asks for a library recommendation. |
| Not applicable | `animate-expo` | React Native/Expo only; not applicable to Waggies web work. |

## Required skill plan

Before every frontend task, declare a concise plan in the conversation. For a
routine task, the plan may be brief. When a design family is involved, include
the full fields below so the user can challenge an incorrect selection.

```text
Task category:
Requested scope:
Evidence to inspect:

Lead skill (exact name):
Supporting skills (exact names):
Excluded skills and reason (only plausible alternatives):

Skill instructions loaded:
Confidence: High / Medium / Low
Revision type: None / Task clarification / Scope expansion
Conflicts:
Decision:
Action: Proceeding / Pausing
```

The agent should first show a tentative classification, read the complete
instructions for the selected skills, then confirm or revise the plan. Reading
skill instructions is not a permission gate. Pause only when the confirmed
choice is genuinely ambiguous, expands scope, or creates a material conflict.

Automatically revise the plan when inspection clarifies the task. Mark that as
`Task clarification` when the requested outcome is unchanged. Mark it as
`Scope expansion` and pause when the work now affects shared components,
multiple pages, global tokens, architecture, or the overall design language.

## Task categories

- **Routine implementation:** ordinary feature work or a narrow bug fix. Use
  existing Waggies conventions and only the required technical skills.
- **Technical refactor:** improve implementation structure without changing the
  product's visual direction. Use the relevant technical skill; do not invoke
  design skills just because UI files are involved.
- **Visual refinement:** improve an existing hierarchy, layout, typography,
  spacing, density, clarity, responsive behavior, or accessibility problem.
  Lead with `impeccable`.
- **New composition:** create a new public/marketing composition or a new
  visual direction. Lead with `design-taste-frontend`.
- **Redesign:** explicitly replace or substantially evolve an existing visual
  world. Use Taste for direction, Impeccable for refinement, and obtain
  agreement on the direction before implementation.
- **Motion implementation:** add or tune a specific animation or interaction.
  Lead with `animate` and use Emil principles as needed.
- **Motion review:** review a specific motion diff or component with
  `review-animations`.
- **Motion audit:** survey a feature area or product with `improve-animations`;
  it is read-only until selected findings are separately approved.
- **System refactor:** affect shared components, global tokens, multiple pages,
  motion conventions, or the design system. Follow the system-refactor process.
- **Mixed:** use only when the task genuinely spans categories, and state the
  ordered handoff between lead and supporting skills.

## How to use the three design families

### Impeccable: diagnose and refine

Use `impeccable` when the user asks to polish, clarify, simplify, audit,
improve, or refine an existing interface, or when a concrete visual problem is
found within scope.

1. Inspect the target, nearby pages, shared components, tokens, states, and
   responsive behavior.
2. Name the specific problem instead of requesting a general redesign.
3. Use the narrowest relevant guidance or command, such as `layout`,
   `typeset`, `audit`, `critique`, `polish`, or `harden`.
4. Make a local refinement that preserves Waggies content, behavior, and
   conventions.
5. Verify the affected states and accessibility behavior.

Impeccable's `polish`, `simplify`, `distill`, `bolder`, or `quieter` guidance
does not authorize replacing an established page. Impeccable refines the
chosen Waggies direction; it does not define Waggies' identity.

### Taste: establish a new composition

Use `design-taste-frontend` when a new public/marketing composition, landing
page, portfolio-like surface, or explicitly requested redesign needs a
deliberate point of view rather than a generic template.

1. Infer the page kind, audience, brief, brand assets, and constraints.
2. State the design read and choose a direction appropriate to the brief.
3. Inspect Waggies' existing visual language before extending or replacing it.
4. Use Taste to reject generic composition and choose a coherent direction.
5. Implement the direction with Blade, Alpine, Tailwind, existing Waggies
   components, and Waggies-owned assets.
6. Hand off to Impeccable for refinement, then to the motion family only when
   motion is part of the request.

Do not use Taste to redesign ordinary app screens, dashboards, data tables,
admin panels, multi-step forms, or established pages merely because they look
familiar. Do not copy Taste's React/Next.js, package, icon, or dependency
defaults into Waggies. For redesigns, preserve routes, factual copy, form
names, analytics hooks, brand assets, accessibility behavior, and product
function unless the user explicitly authorizes changing them.

### Emil: motion and interaction craft

Use the Emil family when motion, animation timing, easing, transitions,
micro-interactions, gesture feedback, or motion accessibility is part of the
task.

Before adding or changing motion, establish:

- what the motion communicates;
- how frequently the interaction occurs;
- whether it should animate at all;
- the appropriate easing, duration, origin, and interruptibility; and
- normal-motion and reduced-motion behavior.

Use `animate` for implementation, `review-animations` for a specific diff,
`improve-animations` for an explicit read-only audit, and
`find-animation-opportunities` for an explicit proposal request. Use
`emil-design-eng` for the underlying craft principles, not as a mandate to
audit unrelated UI.

Emil's guidance does not control Waggies' visual identity. Existing motion is
evidence, not automatic authority: classify it as known intentional, likely
intentional, possible defect, or unknown. Change it for a concrete defect,
accessibility or usability issue, performance problem, inconsistency, or
explicit product decision. Do not flatten unrelated UI simply because a motion
skill prefers less movement.

## Combining skills

Use a lead/supporting relationship rather than treating all selected skills as
equal decision-makers:

- **New public composition:** Taste → Waggies implementation → Impeccable
  refinement → Emil motion layer, if requested.
- **Existing visual refinement:** Impeccable → local implementation. Add Taste
  only if a new composition is genuinely in scope.
- **Specific motion problem:** Emil/`review-animations` → diagnose the actual
  defect → change only that motion → verify interaction and reduced motion.
- **Animation-only task:** `animate`/Emil only. Do not trigger a broad visual
  redesign.
- **Routine implementation or refactor:** no optional design family.

Supporting skills may observe and recommend, but they may not silently override
the lead or make decisions outside their specialty.

## Conflict resolution

Every relevant skill contribution must be classified as one of:

- **Observation:** evidence about the current code or rendered behavior; it
  does not authorize a change.
- **Recommendation:** a proposed change within the skill's scope; it is not an
  instruction.
- **Decision:** the agent's chosen action after applying the authority order,
  task scope, and lead/supporting roles.

When recommendations conflict, state the conflict and resolution before
editing:

```text
Conflict: Impeccable recommends simplifying the card layout; Taste recommends
preserving the distinctive composition.
Resolution: Taste owns the new composition. Apply Impeccable's clarity advice
only where it does not change that composition.
Reason: The conflict concerns visual direction, so the lead skill's scope wins.
```

Correctness, security, accessibility, and explicit user requirements always
outrank stylistic preferences, including the lead skill's preference.

## Existing UI and scope control

For a scoped task, preserve unrelated behavior when it is known or likely
intentional. If a concrete problem is found outside scope:

- fix it automatically only when it is necessary to complete the task, local,
  low-risk, and contract-preserving;
- mention adjacent low-risk observations without expanding scope; and
- pause before changing shared behavior, public content, accessibility
  strategy, component APIs, architecture, or design direction.

For an explicit redesign or system refactor, the current UI becomes the
subject of an audit and may be challenged. It is evidence and an anti-reference
where appropriate, not a veto. Do not silently turn a local request into that
larger mode.

## System-refactor process

Before a system refactor, provide an audit and agreement point containing:

- affected surfaces and shared foundations;
- current patterns and inconsistencies;
- known defects versus intentional decisions;
- lead and supporting skills;
- competing recommendations and tradeoffs;
- proposed direction and boundaries; and
- risks and verification strategy.

Implement only after the direction is agreed. Keep this plan in the
conversation unless the user explicitly requests a separate document.

## Reduced motion and accessibility

Respect Waggies' existing reduced-motion implementation. Preserve normal motion
for users who have not requested reduction. Prefer a gentler, understandable
alternative where appropriate; do not introduce blanket global motion removal
or `transition-none` rules as a shortcut.

Verify the states affected by the change: keyboard, focus, hover, active,
loading, error, responsive, pointer/touch, and reduced motion. Do not use a
design skill's aesthetic recommendation to regress accessibility or truthful
interaction behavior.

## Anti-patterns

Never:

- invoke every installed skill because it is available;
- let a supporting skill override the lead without explaining why;
- treat a recommendation as an instruction or decision;
- silently expand a local task into a system refactor;
- replace Blade/Alpine/Livewire/Tailwind with React or Next.js assumptions;
- add a component, motion, token, or state library because a skill assumes one;
- remove existing transitions, hover states, shadows, or interaction feedback
  solely to satisfy an external aesthetic;
- perform global cleanup such as flattening cards, replacing all easing, or
  removing all animations without explicit scope; or
- create or edit documentation for task-specific decisions unless requested.

## Final review and summary

After implementation, verify the requested scope, Waggies conventions,
responsive behavior, keyboard and pointer interactions, accessibility, and
reduced-motion behavior.

Summarize the result using:

```text
Adopted:
Adapted:
Rejected (only material recommendations):
Scope changes:
Verification:
```

The goal is better Waggies, not Waggies rewritten according to an external
skill's default aesthetic.
