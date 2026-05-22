{{--
    Page header band (title + optional eyebrow/subtitle).

    Props: eyebrow, title, subtitle, variant (default|purple)
--}}
@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'variant' => 'purple',
])

@php
    $wrapper = $variant === 'purple'
        ? 'bg-surface-purple border-b border-primary/10'
        : 'bg-white border-b border-primary/10';
@endphp

<div {{ $attributes->class([$wrapper]) }}>
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-16 md:py-20">
        @if ($eyebrow)
            <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">{{ $eyebrow }}</span>
        @endif

        <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-3">{{ $title }}</h1>

        @if ($subtitle)
            <p class="text-primary-dark/60 max-w-xl">{{ $subtitle }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="mt-6">{{ $slot }}</div>
        @endif
    </div>
</div>
