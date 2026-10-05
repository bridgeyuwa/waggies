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
    $primaryClass = $isDark ? 'bg-secondary! text-primary-dark! hover:bg-secondary-hover!' : '';
    $secondaryVariant = $isDark ? 'outline' : 'secondary';
    $secondaryClass = $isDark ? 'border-white/30 bg-transparent text-white hover:bg-white/10' : '';
    $headingClass = $size === 'sm' ? 'text-xl sm:text-2xl' : 'text-3xl md:text-4xl';
    $bodyClass = $size === 'sm' ? 'mt-2 text-sm sm:text-base' : 'mt-4 text-base';
    $actionsClass = $size === 'sm' ? 'mt-6' : 'mt-7';
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
    <div class="{{ $actionsClass }} flex flex-wrap justify-center gap-3">
        <x-waggies.button href="{{ $primaryDestination }}" class="{{ $primaryClass }}">
            {{ $cta['primaryLabel'] }}
            <x-waggies.icon name="{{ $cta['primaryIcon'] ?? 'arrow-forward' }}" size="18" />
        </x-waggies.button>
        @if(!empty($cta['secondaryLabel']) && $secondaryDestination)
            <x-waggies.button href="{{ $secondaryDestination }}" variant="{{ $secondaryVariant }}" class="{{ $secondaryClass }}">
                @if(!empty($cta['secondaryIcon']))
                    <x-waggies.icon name="{{ $cta['secondaryIcon'] }}" size="18" />
                @endif
                {{ $cta['secondaryLabel'] }}
            </x-waggies.button>
        @endif
    </div>
</div>
