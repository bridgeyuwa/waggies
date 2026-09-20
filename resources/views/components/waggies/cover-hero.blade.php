@props([
    'hero',
    'titleId' => 'hero-image-title',
    'size' => 'standard',
    'contentPosition' => 'center',
])

@php
    $sizeClasses = match ($size) {
        'compact' => 'min-h-[26.25rem] md:min-h-[28rem]',
        default => 'min-h-[35rem]',
    };

    $contentPositionClasses = $contentPosition === 'bottom'
        ? 'justify-end'
        : 'justify-center';

    $primaryAction = $hero['primaryAction'] ?? null;
    $secondaryAction = $hero['secondaryAction'] ?? null;

    $actionDestination = static function (array $action): string {
        if (isset($action['route'])) {
            return route($action['route'], $action['params'] ?? []);
        }

        return $action['href'];
    };
@endphp

<section {{ $attributes->class(['relative isolate flex flex-col overflow-hidden bg-primary-dark', $sizeClasses, $contentPositionClasses]) }} aria-labelledby="{{ $titleId }}">
    <x-waggies.image
        :src="$hero['imageSrc']"
        :alt="$hero['imageAlt']"
        loading="eager"
        fetch-priority="high"
        class="absolute inset-0 h-full w-full object-cover"
    />
    <div class="absolute inset-0 bg-[linear-gradient(to_bottom,rgba(0,0,0,0.10)_0%,rgba(0,0,0,0.28)_52%,rgba(0,0,0,0.70)_100%)]" aria-hidden="true"></div>

    <div class="relative z-10 w-full">
        <div class="mx-auto w-full max-w-7xl px-4 py-12 md:px-10 md:py-16 lg:px-12">
            <div class="max-w-xl">
                @if(! empty($hero['eyebrow']))
                    <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-primary-dark/70 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-white">
                        <x-waggies.icon name="{{ $hero['eyebrowIcon'] ?? 'pets' }}" size="14" class="text-secondary" />
                        {{ $hero['eyebrow'] }}
                    </span>
                @endif

                <h1 id="{{ $titleId }}" class="mb-4 text-balance font-serif text-4xl font-bold leading-tight text-white md:text-5xl lg:text-[3.25rem] lg:leading-[1.08]">{!! $hero['title'] !!}</h1>

                @if(isset($supporting) && $supporting->isNotEmpty())
                    <div class="mb-5">
                        {{ $supporting }}
                    </div>
                @endif

                @if(! empty($hero['description']))
                    <p class="mb-7 max-w-xl text-base leading-relaxed text-white/80 md:text-lg">{{ $hero['description'] }}</p>
                @endif

                @if($primaryAction || $secondaryAction)
                    <div class="flex flex-wrap gap-3">
                        @if($primaryAction)
                            <x-waggies.button href="{{ $actionDestination($primaryAction) }}" class="!bg-secondary !text-primary-dark hover:!bg-secondary-hover">
                                {{ $primaryAction['label'] }}
                                <x-waggies.icon name="{{ $primaryAction['icon'] ?? 'arrow-forward' }}" size="18" />
                            </x-waggies.button>
                        @endif

                        @if($secondaryAction)
                            <x-waggies.button href="{{ $actionDestination($secondaryAction) }}" variant="outline" class="border-white/30 bg-transparent text-white hover:bg-white/10">
                                @if(! empty($secondaryAction['iconBefore']))
                                    <x-waggies.icon name="{{ $secondaryAction['iconBefore'] }}" size="18" class="text-white" />
                                @endif
                                {{ $secondaryAction['label'] }}
                            </x-waggies.button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
