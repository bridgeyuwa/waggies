---
paths:
  - 'resources/views/components/waggies/**'
  - 'resources/views/layouts/**'
  - 'resources/views/pages/**'
  - 'resources/js/alpine/**'
---

# Waggies Interaction and Navigation

## Navigation state model

Desktop navigation, flyouts, mega menus, and nested mobile navigation must all expose coherent states:

- default,
- hover where applicable,
- focus-visible,
- current/active route,
- current parent when a descendant route is active,
- open/expanded for disclosures,
- pressed where relevant.

Do not make hover and current-route styles identical. A user must be able to tell where they are even when the pointer is elsewhere.

Parent items may show an active/current-parent state when a child route is current. Child links must also be able to show their own current state.

## Keyboard behavior

Navigation must remain keyboard usable:

- visible focus,
- logical tab order,
- Enter/Space behavior appropriate to links/buttons/disclosures,
- Escape closes dismissible overlays/menus when the pattern supports it,
- focus is not trapped in non-modal menus,
- hidden menu content is not focusable when closed.

## Mobile nested navigation

Nested mobile links need active/current states too. Do not treat mobile as a reduced-accessibility version of desktop.

Touch targets must remain comfortably tappable and nested disclosure indicators must not reduce the clickable label area unnecessarily.

## Sticky navigation

If the header is intended to be sticky, refactors must preserve sticky behavior and verify it against overflow/transform ancestors, stacking contexts, and mobile viewport behavior.

## Motion

Menu/flyout transitions should be brief and explanatory. Respect reduced-motion preferences. Do not use long elastic/spring effects for routine navigation.

## Make auto-rotating content deterministic and interruptible
Auto-rotating content must use one deterministic timer, pause while hovered or focused, never advance during a transition, and expose manual accessible controls. Keep target/selected state separate from displayed content until the transition completes; use transform/opacity transitions under 300ms, respect prefers-reduced-motion, and clean up timers when components are destroyed.

## Use Alpine UI patterns for reusable interaction behavior
Implement interaction contracts with reusable Alpine.data factories and focused helpers. Prefer x-id, x-ref, $nextTick, x-cloak, x-show/x-transition, and x-teleport where appropriate; use x-trap/$focus only when the Alpine Focus plugin is explicitly installed. Use Alpine UI's public component patterns for dropdowns, modals, accordions, carousels, tabs, notifications, radio groups, toggles, and tooltips as references, without adding React/Vue Headless UI or copying paid source without a valid license.
