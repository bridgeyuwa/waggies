{{--
    Hero review stat badge — "4.9 · 500+ Reviews" with pulsing dot.

    Props:
      $rating       — e.g. "4.9"
      $reviewCount  — e.g. "500+"
--}}
@props([
    'rating'      => '4.9',
    'reviewCount' => '500+',
])

<div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-surface-purple shadow-sm w-fit">
    <span class="material-symbols-outlined icon-filled text-primary text-lg" aria-hidden="true">star</span>
    <span class="text-xs font-bold text-primary-dark">{{ $rating }} · {{ $reviewCount }} Reviews</span>
    <span class="w-2 h-2 rounded-full bg-primary animate-wag-pulse" aria-hidden="true"></span>
</div>
