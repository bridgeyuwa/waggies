@props([
    'cta',
])

@php
    $headingAccent = $cta['headingAccent'] ?? 'Care They Deserve';
    $resolveHref = static fn (?string $href): ?string => !empty($href)
        ? (preg_match('/^[a-z][a-z0-9+.-]*:/i', $href) === 1 || str_starts_with($href, '/') ? $href : url($href))
        : null;
    $primaryDestination = !empty($cta['primaryRoute'])
        ? route($cta['primaryRoute'], $cta['primaryParams'] ?? [])
        : $resolveHref($cta['primaryHref'] ?? null);
    $secondaryDestination = !empty($cta['secondaryRoute'])
        ? route($cta['secondaryRoute'], $cta['secondaryParams'] ?? [])
        : $resolveHref($cta['secondaryHref'] ?? null);
    $actions = [
        [
            'href' => $primaryDestination,
            'label' => $cta['primaryLabel'],
            'tone' => 'dark',
            'iconAfter' => $cta['primaryIcon'] ?? 'arrow-forward',
            'iconSize' => 16,
        ],
    ];

    if (!empty($cta['secondaryLabel']) && $secondaryDestination) {
        $actions[] = [
            'href' => $secondaryDestination,
            'label' => $cta['secondaryLabel'],
            'variant' => 'secondary',
            'tone' => 'dark',
            'iconBefore' => $cta['secondaryIcon'] ?? null,
            'iconSize' => 16,
        ];
    }
@endphp

<div {{ $attributes->class(['w-full rounded-2xl bg-primary-dark']) }}>
    <div class="page-container py-12 md:py-16">
        <div class="mx-auto flex max-w-5xl flex-col items-center gap-10 md:flex-row md:items-center md:justify-between">
            <div class="flex max-w-xl flex-col items-center gap-5 text-center md:items-start md:text-left">
                @if(!empty($cta['eyebrow']))
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-secondary">{{ $cta['eyebrow'] }}</div>
                @endif
                <h2 class="font-serif text-3xl font-bold leading-tight text-white md:text-4xl">
                    {{ $cta['heading'] }}
                    @if($headingAccent)
                        <br /><span class="text-secondary italic">{{ $headingAccent }}</span>
                    @endif
                </h2>
                <p class="max-w-sm text-base leading-relaxed text-white/65 md:max-w-md">{{ $cta['body'] }}</p>
            </div>
            <x-waggies.cta-actions :actions="$actions" class="md:items-center md:justify-end" />
        </div>
    </div>
</div>
