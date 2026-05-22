{{--
    Hero — full-bleed background image with gradient overlay.

    Props (camelCase — preferred):
      imageSrc, imageAlt, eyebrow, eyebrowIcon, title, subtitle,
      primaryCta, secondaryCta, minHeight, variant (bottom|center), overlay (light|dark)

    Legacy snake_case aliases (image_src, primary_label, etc.) are still accepted.
--}}
@props([
    'imageSrc' => '',
    'imageAlt' => null,
    'eyebrow' => '',
    'eyebrowIcon' => 'star',
    'title' => '',
    'subtitle' => '',
    'primaryCta' => null,
    'secondaryCta' => null,
    'minHeight' => '560px',
    'variant' => 'bottom',
    'overlay' => 'dark',
])

@php
    $imageSrc = $imageSrc ?: ($attributes->get('image_src') ?? '');
    $eyebrow = $eyebrow ?: ($attributes->get('eyebrow') ?? '');
    $eyebrowIcon = $eyebrowIcon !== 'star' ? $eyebrowIcon : ($attributes->get('eyebrow_icon') ?? 'star');
    $minHeight = $attributes->get('min_height') ?? $minHeight;
    $variant = $attributes->get('content_align') ?? $variant;
    $imageAlt = $imageAlt ?? strip_tags($title);

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
        ];
    }

    $gradient = $overlay === 'light'
        ? 'linear-gradient(rgba(62,26,87,0.10) 0%, rgba(62,26,87,0.80) 100%)'
        : 'linear-gradient(rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.65) 100%)';
@endphp

<section
    class="relative flex flex-col bg-cover bg-center {{ $variant === 'center' ? 'justify-center' : 'justify-end' }}"
    style="min-height: {{ $minHeight }}; background-image: {{ $gradient }}, url('{{ $imageSrc }}');"
    aria-label="{{ strip_tags($title) }}"
    role="img"
    aria-roledescription="hero"
>
    <div class="max-w-7xl mx-auto w-full px-4 md:px-10 lg:px-12 py-12 md:py-16">
        <div class="relative z-10 max-w-xl animate-fade-in-up">

            @if ($eyebrow)
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4
                             bg-white/15 border border-white/25 text-white text-xs font-bold
                             uppercase tracking-widest backdrop-blur-sm">
                    <span class="material-symbols-outlined text-sm text-secondary icon-filled"
                        aria-hidden="true">{{ $eyebrowIcon }}</span>
                    {{ $eyebrow }}
                </span>
            @endif

            <h1 class="font-serif text-4xl md:text-5xl font-bold text-white leading-tight mb-4">
                {!! $title !!}
            </h1>

            @if ($subtitle)
                <p class="text-white/75 text-base mb-6 leading-relaxed">{{ $subtitle }}</p>
            @endif

            @if ($primaryCta || $secondaryCta || $slot->isNotEmpty())
                @if ($slot->isNotEmpty())
                    <div class="flex flex-wrap gap-3">{{ $slot }}</div>
                @else
                    <x-ui.cta-buttons :primary="$primaryCta" :secondary="$secondaryCta" variant="overlay" />
                @endif
            @endif

        </div>
    </div>
</section>
