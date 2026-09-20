@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
])

@php
    $classes = match ($variant) {
        'secondary' => 'w-cta w-cta--secondary',
        'outline' => 'w-cta w-cta--secondary bg-transparent',
        'link' => 'inline-flex min-h-11 items-center gap-1.5 font-semibold text-primary transition-colors hover:text-primary-dark hover:underline',
        default => 'w-cta w-cta--primary',
    };

    $isDisabled = $disabled || $loading;
@endphp

@if($href !== null)
    <a href="{{ $href }}" @if($isDisabled) aria-disabled="true" tabindex="-1" @endif @if($loading) aria-busy="true" @endif {{ $attributes->class([$classes, 'pointer-events-none opacity-50' => $isDisabled]) }}>
        @if($loading)<span aria-hidden="true" class="animate-pulse">…</span>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($isDisabled) @if($loading) aria-busy="true" @endif {{ $attributes->class([$classes]) }}>
        @if($loading)<span aria-hidden="true" class="animate-pulse">…</span>@endif
        {{ $slot }}
    </button>
@endif
