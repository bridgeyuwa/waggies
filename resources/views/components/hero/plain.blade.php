{{--
    Hero — light background (hubs, instructional pages).

    Props:
      eyebrow, eyebrowIcon, title, subtitle, align (center|left),
      imageSrc, imageAlt, primaryCta, secondaryCta
      Legacy: primary_label, primary_href, image_src, etc.
--}}
@props([
    'eyebrow' => null,
    'eyebrowIcon' => null,
    'title' => '',
    'subtitle' => null,
    'align' => 'center',
    'imageSrc' => null,
    'imageAlt' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
])

@php
    $eyebrow = $eyebrow ?? $attributes->get('eyebrow');
    $eyebrowIcon = $eyebrowIcon ?? $attributes->get('eyebrow_icon');
    $imageSrc = $imageSrc ?? $attributes->get('image_src');
    $imageAlt = $imageAlt ?? strip_tags($title);
    $align = $attributes->get('align') ?? $align;

    if (! $primaryCta && $attributes->get('primary_label')) {
        $primaryCta = [
            'label' => $attributes->get('primary_label'),
            'href' => $attributes->get('primary_href', '#'),
            'icon' => $attributes->get('primary_icon', 'arrow_forward'),
        ];
    }

    if (! $secondaryCta && $attributes->get('secondary_label')) {
        $secondaryCta = [
            'label' => $attributes->get('secondary_label'),
            'href' => $attributes->get('secondary_href', '#'),
            'iconBefore' => $attributes->get('secondary_icon'),
        ];
    }
@endphp

<section class="w-full py-20 md:py-28 bg-white">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        <div class="flex flex-col gap-6 {{ $align === 'left' ? 'text-left items-start' : 'text-center items-center' }}">

            @if ($eyebrow || $eyebrowIcon)
                <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-primary/60">
                    @if ($eyebrowIcon)
                        <span class="material-symbols-outlined text-sm text-secondary" aria-hidden="true">{{ $eyebrowIcon }}</span>
                    @endif
                    {{ $eyebrow }}
                </span>
            @endif

            <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-bold text-primary-dark leading-tight">
                {!! $title !!}
            </h1>

            @if ($subtitle)
                <p class="text-primary-dark/60 text-base leading-relaxed max-w-2xl">{{ $subtitle }}</p>
            @endif

            @if ($primaryCta || $secondaryCta)
                <x-ui.cta-buttons
                    :primary="$primaryCta"
                    :secondary="$secondaryCta"
                    variant="plain"
                    class="{{ $align === 'left' ? 'sm:justify-start' : 'sm:justify-center' }} mt-4"
                />
            @endif

            @if ($imageSrc)
                <div class="mt-10 w-full flex justify-center">
                    <img src="{{ $imageSrc }}" alt="{{ $imageAlt }}"
                         class="w-full max-w-md md:max-w-lg rounded-2xl shadow-md object-cover" loading="lazy" />
                </div>
            @endif

        </div>
    </div>
</section>
