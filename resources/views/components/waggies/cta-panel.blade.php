@props([
    'cta',
    'variant' => 'split',
])

@php
    $resolveHref = static fn (?string $href): ?string => !empty($href)
        ? (preg_match('/^[a-z][a-z0-9+.-]*:/i', $href) === 1 || str_starts_with($href, '/') ? $href : url($href))
        : null;
    $primaryDestination = !empty($cta['primaryRoute'])
        ? route($cta['primaryRoute'], $cta['primaryParams'] ?? [])
        : $resolveHref($cta['primaryHref'] ?? null);
    $secondaryDestination = !empty($cta['secondaryRoute'])
        ? route($cta['secondaryRoute'], $cta['secondaryParams'] ?? [])
        : $resolveHref($cta['secondaryHref'] ?? null);
@endphp

@if($variant === 'centered')
    <div {{ $attributes->class(['relative overflow-hidden rounded-3xl bg-primary-dark px-8 py-16 md:px-16 md:py-20']) }}>
        <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-secondary/60 md:inset-x-16"></div>
        <x-waggies.cta-centered :cta="$cta" size="panel" />
    </div>
@elseif($variant === 'compact')
    <div {{ $attributes->class(['rounded-2xl bg-surface-purple px-6 py-7 sm:px-8 sm:py-8']) }}>
        <div class="mx-auto flex max-w-6xl flex-col gap-6 sm:flex-row sm:items-center sm:justify-between sm:gap-10">
            <div class="max-w-3xl">
                @if(!empty($cta['eyebrow']))
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-primary">{{ $cta['eyebrow'] }}</p>
                @endif
                <h2 class="mt-2 font-serif text-2xl font-bold leading-tight text-primary-dark sm:text-3xl">{{ $cta['heading'] }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-primary-dark/70">{{ $cta['body'] }}</p>
            </div>
            <div class="flex shrink-0 flex-col items-start gap-1 sm:items-end">
                <x-waggies.button href="{{ $primaryDestination }}">
                    {{ $cta['primaryLabel'] }}
                    <x-waggies.icon name="{{ $cta['primaryIcon'] ?? 'arrow-forward' }}" size="16" />
                </x-waggies.button>
                @if(!empty($cta['secondaryLabel']) && $secondaryDestination)
                    <x-waggies.button href="{{ $secondaryDestination }}" variant="link" class="text-sm">
                        {{ $cta['secondaryLabel'] }}
                    </x-waggies.button>
                @endif
            </div>
        </div>
    </div>
@else
    <div {{ $attributes->class(['rounded-2xl bg-primary-dark px-6 py-9 text-white sm:px-9 sm:py-11 lg:px-12']) }}>
        <div class="mx-auto flex max-w-6xl flex-col gap-8 md:flex-row md:items-center md:justify-between md:gap-12">
            <div class="max-w-2xl">
                @if(!empty($cta['eyebrow']))
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-secondary">{{ $cta['eyebrow'] }}</p>
                @endif
                <h2 class="mt-4 font-serif text-3xl font-bold leading-tight text-white sm:text-4xl">{{ $cta['heading'] }}</h2>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/75 sm:text-base">{{ $cta['body'] }}</p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-3 md:max-w-72 md:flex-col">
                <x-waggies.button href="{{ $primaryDestination }}" tone="dark" class="w-full px-1.5 text-sm sm:px-8 sm:text-base">
                    {{ $cta['primaryLabel'] }}
                    <x-waggies.icon name="{{ $cta['primaryIcon'] ?? 'arrow-forward' }}" size="16" />
                </x-waggies.button>
                @if(!empty($cta['secondaryLabel']) && $secondaryDestination)
                    <x-waggies.button href="{{ $secondaryDestination }}" variant="secondary" tone="dark" class="w-full">
                        {{ $cta['secondaryLabel'] }}
                    </x-waggies.button>
                @endif
            </div>
        </div>
    </div>
@endif
