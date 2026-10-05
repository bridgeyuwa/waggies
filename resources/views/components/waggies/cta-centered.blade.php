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
    $headingClass = $size === 'sm' ? 'text-xl sm:text-2xl' : 'text-3xl md:text-4xl';
    $bodyClass = $size === 'sm' ? 'mt-2 text-sm sm:text-base' : 'mt-4 text-base';
    $actionsClass = $size === 'sm' ? 'mt-6' : 'mt-7';
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
            <br /><span class="italic {{ $isDark ? 'text-secondary' : 'text-primary' }}">{{ $cta['headingAccent'] }}</span>
        @endif
    </h2>
    <p class="mx-auto max-w-lg leading-relaxed {{ $bodyClass }} {{ $isDark ? 'text-white/65' : 'text-primary-dark/60' }}">
        {{ $cta['body'] }}
    </p>
    <x-waggies.cta-actions :actions="$actions" class="{{ $actionsClass }}" />
</div>
