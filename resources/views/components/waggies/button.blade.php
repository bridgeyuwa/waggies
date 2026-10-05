@props([
    'href' => null,
    'variant' => 'primary',
    'tone' => 'light',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
])

@php
    $isDarkTone = $tone === 'dark';
    $variantClasses = match ($variant) {
        'secondary' => $isDarkTone ? 'w-cta w-cta--secondary-on-dark' : 'w-cta w-cta--secondary',
        'link' => $isDarkTone
            ? 'w-cta-link inline-flex min-h-11 items-center gap-1.5 font-semibold text-secondary transition-colors hover:text-white hover:underline'
            : 'w-cta-link inline-flex min-h-11 items-center gap-1.5 font-semibold text-primary transition-colors hover:text-primary-dark hover:underline',
        default => $isDarkTone ? 'w-cta w-cta--primary-on-dark' : 'w-cta w-cta--primary',
    };
    $sizeClasses = match ($size) {
        'sm' => 'w-cta--sm',
        default => '',
    };

    $isDisabled = $disabled || $loading;
    $isLink = $href !== null || $attributes->has('x-bind:href');
    $hrefAttribute = $href !== null ? 'href="'.e(htmlspecialchars_decode($href, ENT_QUOTES)).'"' : '';
@endphp

@if($isLink)
    <a{!! $hrefAttribute !== '' ? ' '.$hrefAttribute : '' !!} @if($isDisabled) aria-disabled="true" tabindex="-1" @endif @if($loading) aria-busy="true" @endif {{ $attributes->class([$variantClasses, $sizeClasses, 'pointer-events-none opacity-50' => $isDisabled]) }}>
        @if($loading)<span aria-hidden="true" class="animate-pulse">…</span>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($isDisabled) @if($loading) aria-busy="true" @endif {{ $attributes->class([$variantClasses, $sizeClasses]) }}>
        @if($loading)<span aria-hidden="true" class="animate-pulse">…</span>@endif
        {{ $slot }}
    </button>
@endif
