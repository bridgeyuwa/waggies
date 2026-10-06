@props([
    'cta',
    'tone' => 'dark',
    'size' => 'md',
])

@php
    $isDark = $tone === 'dark';
    $resolveHref = static fn (?string $href): ?string => !empty($href)
        ? (preg_match('/^[a-z][a-z0-9+.-]*:/i', $href) === 1 || str_starts_with($href, '/') ? $href : url($href))
        : null;
    $primaryDestination = !empty($cta['primaryRoute'])
        ? route($cta['primaryRoute'], $cta['primaryParams'] ?? [])
        : $resolveHref($cta['primaryHref'] ?? null);
    $secondaryDestination = !empty($cta['secondaryRoute'])
        ? route($cta['secondaryRoute'], $cta['secondaryParams'] ?? [])
        : $resolveHref($cta['secondaryHref'] ?? null);
    $headingClass = match ($size) {
        'sm' => 'text-xl sm:text-2xl',
        'panel' => 'mb-4 text-3xl text-balance md:text-[2.75rem] md:leading-[1.1]',
        default => 'text-3xl md:text-4xl',
    };
    $bodyWidthClass = $size === 'panel' ? 'max-w-md' : 'max-w-lg';
    $bodyClass = match ($size) {
        'sm' => 'mt-2 text-sm sm:text-base',
        'panel' => 'mb-9 text-base',
        default => 'mt-4 text-base',
    };
    $actionsClass = match ($size) {
        'sm' => 'mt-6',
        'panel' => 'mt-0',
        default => 'mt-7',
    };
    $actions = [
        [
            'href' => $primaryDestination,
            'label' => $cta['primaryLabel'],
            'tone' => $isDark ? 'dark' : 'light',
            'iconAfter' => $cta['primaryIcon'] ?? 'arrow-forward',
        ],
    ];

    if (!empty($cta['secondaryLabel']) && $secondaryDestination) {
        $actions[] = [
            'href' => $secondaryDestination,
            'label' => $cta['secondaryLabel'],
            'variant' => 'secondary',
            'tone' => $isDark ? 'dark' : 'light',
            'iconBefore' => $cta['secondaryIcon'] ?? null,
        ];
    }
@endphp

<div {{ $attributes->class(['text-center']) }}>
    <h2 class="font-serif font-bold leading-tight {{ $headingClass }} {{ $isDark ? 'text-white' : 'text-primary-dark' }}">
        {{ $cta['heading'] }}
        @if(!empty($cta['headingAccent']))
            @if($size === 'panel')
                <span class="italic {{ $isDark ? 'text-secondary' : 'text-primary' }}"> {{ $cta['headingAccent'] }}</span>
            @else
                <br /><span class="italic {{ $isDark ? 'text-secondary' : 'text-primary' }}">{{ $cta['headingAccent'] }}</span>
            @endif
        @endif
    </h2>
    <p class="mx-auto {{ $bodyWidthClass }} leading-relaxed {{ $bodyClass }} {{ $isDark ? 'text-white/65' : 'text-primary-dark/60' }}">
        {{ $cta['body'] }}
    </p>
    <x-waggies.cta-actions :actions="$actions" class="{{ $actionsClass }}" />
</div>
