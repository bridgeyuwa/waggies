# Refactoring UI — Modern Web Adaptation

The source material predates several current browser, CSS, accessibility, and responsive-design practices. Preserve its design logic while implementing it with modern standards.

## Accessibility baseline

Target **WCAG 2.2 AA** for all changed UI.

- Normal text: at least 4.5:1 contrast unless a valid WCAG exception applies.
- Large text and meaningful non-text UI graphics/boundaries: at least 3:1 where required.
- Never remove visible keyboard focus. Prefer a deliberate `:focus-visible` treatment.
- Interactive controls must remain operable with keyboard and assistive technology.
- Do not encode state with color alone; pair color with text, iconography, shape, pattern, position, or another non-color cue.
- Keep accessible names, labels, descriptions, errors, and relationships intact even when visual labels are reduced or hidden.
- Do not use placeholder text as the only label.
- Avoid hover-only discovery for essential actions.
- Respect `prefers-reduced-motion` and do not make motion necessary to understand or operate the interface.
- Ensure content reflows on narrow screens and remains usable under zoom/text enlargement.
- Use adequately sized pointer targets; prefer 44px-class touch targets for primary mobile controls where practical, and never violate WCAG target-size requirements.

## Waggies implementation boundary

Use the CSS-first Tailwind v4 system in `resources/css/app.css`. Do not create `tailwind.config.js` or move public page composition into another frontend framework. Preserve Blade-first rendering, use Alpine for local interaction, and introduce Livewire only when server state materially improves the experience.

## Color model

The book recommends HSL because it maps better to human reasoning than hex/RGB. For modern CSS, prefer the project's established **OKLCH/semantic token system** when available because it is more perceptually useful for building consistent scales. Do not convert a stable project palette merely for fashion.

Use raw color values only inside the canonical token definitions in `resources/css/app.css` or a clearly required technical exception. Waggies brand primitives are documented and implemented as OKLCH there; components, guidelines, and skills should consume semantic tokens.

## Typography

- Prefer `rem`-based type tokens for scalable UI typography.
- Avoid component-relative `em` for the core type scale when it causes nested computed sizes to drift off-scale.
- `em`/`ch` remain useful for context-specific measures such as line length and component-relative spacing when the relationship is intentional.
- Do not treat exact historical pixel examples as immutable requirements; preserve the relationship and readability principle.

## Responsive layout

- Prefer intrinsic layout, `min()`, `max()`, `clamp()`, flex/grid, container queries, and explicit `max-width` constraints over percentage grids used by habit.
- Use fixed/intrinsic widths for elements that should not grow indefinitely, and flexible tracks for content that should absorb remaining space.
- Make breakpoint changes because the content needs them, not because a device name says so.

## Images

- Prefer semantic `<img>`/framework image components for content images.
- Use fixed aspect-ratio containers plus `object-fit: cover`/`contain` for unpredictable media.
- Reserve space to prevent layout shift.
- Provide appropriate `alt` text for meaningful images; use empty alt for purely decorative images.
- Supply responsive sources/sizes where the framework supports them.

## Interaction states

Every interactive pattern changed in a refactor must be checked for: default, hover (when applicable), focus-visible, pressed/active, selected/current, disabled, loading/busy, error, and success states. State styling must remain distinguishable without relying on color alone.

