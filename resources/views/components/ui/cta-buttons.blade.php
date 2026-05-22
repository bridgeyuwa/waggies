{{--
    CTA button group — shared by hero variants.

    Props:
      $primary — ['label' => '', 'href' => '', 'icon' => 'arrow_forward']|null
      $secondary — same shape|null
      $variant — overlay | plain (controls button styles)
--}}
@props([
    'primary' => null,
    'secondary' => null,
    'variant' => 'overlay',
])

@php
    $primaryClasses = $variant === 'overlay'
        ? 'bg-secondary hover:bg-secondary-hover text-primary-dark shadow-glow focus:ring-secondary/60 focus:ring-offset-2'
        : 'bg-primary hover:bg-primary-dark text-white shadow-glow focus:ring-primary/60 focus:ring-offset-2';

    $secondaryClasses = $variant === 'overlay'
        ? 'border border-white/30 text-white hover:bg-white/10 focus:ring-white/60 focus:ring-offset-2'
        : 'border border-primary/30 text-primary-dark hover:bg-primary/5 focus:ring-primary/40';
@endphp

<div {{ $attributes->class(['flex flex-wrap gap-3']) }}>
    @if ($primary)
        <a href="{{ $primary['href'] ?? '#' }}"
           class="inline-flex items-center gap-2 font-bold px-8 py-3.5 rounded-full transition hover:-translate-y-1 focus:outline-none focus:ring-2 {{ $primaryClasses }}">
            {{ $primary['label'] }}
            @if (! empty($primary['icon']))
                <span class="material-symbols-outlined text-base" aria-hidden="true">{{ $primary['icon'] }}</span>
            @endif
        </a>
    @endif

    @if ($secondary)
        <a href="{{ $secondary['href'] ?? '#' }}"
           class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-semibold transition focus:outline-none focus:ring-2 {{ $secondaryClasses }}">
            @if (! empty($secondary['iconBefore']))
                <span class="material-symbols-outlined text-base" aria-hidden="true">{{ $secondary['iconBefore'] }}</span>
            @endif
            {{ $secondary['label'] }}
        </a>
    @endif
</div>
