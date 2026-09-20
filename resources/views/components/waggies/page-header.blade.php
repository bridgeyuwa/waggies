@props([
    'eyebrow' => null,
    'eyebrowIcon' => null,
    'title',
    'description' => null,
    'alignment' => 'left',
    'titleId' => 'page-header-title',
])

@php
    $alignmentClasses = $alignment === 'center'
        ? 'items-center text-center'
        : 'items-start text-left';
@endphp

<section {{ $attributes->class(['w-page-intro border-b border-primary/10 bg-white']) }} aria-labelledby="{{ $titleId }}">
    <div class="w-page-intro__content page-container flex flex-col py-16 md:py-20">
        <div class="flex max-w-3xl flex-col gap-4 {{ $alignmentClasses }}">
            @if(isset($supporting) && $supporting->isNotEmpty())
                <div>
                    {{ $supporting }}
                </div>
            @endif

            @if($eyebrow || $eyebrowIcon)
                <span class="text-eyebrow inline-flex items-center gap-2">
                    @if($eyebrowIcon)
                        <x-waggies.icon name="{{ $eyebrowIcon }}" size="14" class="text-secondary" />
                    @endif
                    {{ $eyebrow }}
                </span>
            @endif

            <h1 id="{{ $titleId }}" class="text-display text-primary-dark">{!! $title !!}</h1>

            @if($description)
                <p class="text-body max-w-2xl text-pretty">{{ $description }}</p>
            @endif

            @if(isset($actions) && $actions->isNotEmpty())
                <div>
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</section>
