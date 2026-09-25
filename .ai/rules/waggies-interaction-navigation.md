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

