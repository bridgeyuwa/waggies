{{--
    Glass panel (B11). Glassmorphism card — always placed over an image/dark background.
    Uses .glass-panel custom CSS class from waggies.css.

    Props:
      $rounded — Tailwind border-radius class. Default: rounded-xl
      $padding — Tailwind padding class. Default: p-4
--}}
@props([
    'rounded' => 'rounded-xl',
    'padding' => 'p-4',
])

<div {{ $attributes->class(['glass-panel text-primary-dark', $rounded, $padding]) }}>
    {{ $slot }}
</div>
