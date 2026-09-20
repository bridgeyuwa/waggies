@props(['eyebrow' => null, 'eyebrowIcon' => null, 'title', 'subtitle' => null])

<section class="w-page-intro section-pad">
    <div class="w-page-intro__content mx-auto max-w-5xl px-4 md:px-10 lg:px-12">
        <div class="mx-auto flex max-w-3xl flex-col items-center gap-5 text-center">
            @if($eyebrow || $eyebrowIcon)
                <span class="text-eyebrow inline-flex items-center gap-2">
                    @if($eyebrowIcon)<x-waggies.icon name="{{ $eyebrowIcon }}" size="14" class="text-secondary" />@endif
                    {{ $eyebrow }}
                </span>
            @endif
            <h1 class="text-display text-primary-dark">{!! $title !!}</h1>
            @if($subtitle)<p class="text-body max-w-2xl text-lg leading-relaxed">{{ $subtitle }}</p>@endif
        </div>
    </div>
</section>
