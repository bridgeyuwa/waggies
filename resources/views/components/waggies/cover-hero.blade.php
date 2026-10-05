@props([
    'hero',
    'titleId' => 'hero-image-title',
    'size' => 'standard',
    'contentPosition' => 'center',
    'imagePosition' => null,
    'scrim' => null,
])

@php
    $sizeClasses = match ($size) {
        'compact' => 'w-cover-hero--compact',
        default => '',
    };

    $contentPositionClasses = match ($contentPosition) {
        'bottom' => 'justify-end',
        default => 'justify-center',
    };

    $imagePositionClass = match ($imagePosition ?? $hero['imagePosition'] ?? 'center') {
        'top' => 'object-top',
        'bottom' => 'object-bottom',
        'left' => 'object-left',
        'right' => 'object-right',
        default => 'object-center',
    };

    $scrimClass = match ($scrim ?? $hero['scrim'] ?? 'standard') {
        'light' => 'bg-gradient-to-b from-primary-dark/5 via-primary-dark/15 to-primary-dark/45',
        'strong' => 'bg-gradient-to-b from-primary-dark/20 via-primary-dark/40 to-primary-dark/80',
        default => 'bg-gradient-to-b from-primary-dark/10 via-primary-dark/30 to-primary-dark/70',
    };
@endphp

<section {{ $attributes->class(['w-cover-hero relative isolate flex flex-col overflow-hidden bg-primary-dark', $sizeClasses, $contentPositionClasses]) }} aria-labelledby="{{ $titleId }}">
    <x-waggies.image
        :src="$hero['imageSrc']"
        :alt="$hero['imageAlt']"
        loading="eager"
        fetch-priority="high"
        class="absolute inset-0 h-full w-full object-cover {{ $imagePositionClass }}"
    />
    <div class="absolute inset-0 {{ $scrimClass }}" aria-hidden="true"></div>

    <div class="relative z-10 w-full">
        <div class="page-container w-full py-12 md:py-16">
            <div class="max-w-xl">
                @if(! empty($hero['eyebrow']))
                    <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-primary-dark/70 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-white">
                        <x-waggies.icon name="{{ $hero['eyebrowIcon'] ?? 'pets' }}" size="14" class="text-secondary" />
                        {{ $hero['eyebrow'] }}
                    </span>
                @endif

                <h1 id="{{ $titleId }}" class="text-h1 mb-4 text-balance text-white">{!! $hero['title'] !!}</h1>

                @if(isset($supporting) && $supporting->isNotEmpty())
                    <div class="mb-5">
                        {{ $supporting }}
                    </div>
                @endif

                @if(! empty($hero['description']))
                    <p class="mb-7 max-w-xl text-base leading-relaxed text-white/80 md:text-lg">{{ $hero['description'] }}</p>
                @endif

                @if(! empty($hero['actions']))
                    <x-waggies.hero-actions :actions="$hero['actions']" tone="image" />
                @endif
            </div>
        </div>
    </div>
</section>
