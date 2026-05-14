{{--
    Hero — Plain
    White-background hero for instructional pages and conversion flows.
    No imagery; content and CTAs only.

    Props:
      title            — h1 text (HTML allowed for <br> / <em>)
      subtitle         — optional subheading text
      align            — content alignment: 'center' (default) | 'left'
      primary_label    — primary CTA button text (omit to hide)
      primary_href     — primary CTA URL. Default: '#'
      primary_icon     — Material Symbol after the label. Default: 'arrow_forward'
      secondary_label  — secondary CTA button text (omit to hide)
      secondary_href   — secondary CTA URL. Default: '#'
      secondary_icon   — optional Material Symbol before the secondary label
--}}
@props([
    'title' => '',
    'subtitle' => null,
    'align' => 'center',

    'primary_label' => null,
    'primary_href' => '#',
    'primary_icon' => 'arrow_forward',

    'secondary_label' => null,
    'secondary_href' => '#',
    'secondary_icon' => null,
])

<section class="w-full py-24 md:py-32 bg-white">

    <div class="max-w-2xl mx-auto px-4 md:px-6">

        <div class="flex flex-col gap-6 {{ $align === 'left' ? 'text-left items-start' : 'text-center items-center' }}">

            <h1 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight">
                {!! $title !!}
            </h1>

            @if ($subtitle)
                <p class="text-primary-dark/60 text-base leading-relaxed max-w-xl">
                    {{ $subtitle }}
                </p>
            @endif

            @if ($primary_label || $secondary_label)
                <div class="flex flex-col sm:flex-row gap-3 mt-4
                            {{ $align === 'left' ? 'sm:justify-start' : 'sm:justify-center' }}">

                    @if ($primary_label)
                        <a href="{{ $primary_href }}"
                            class="inline-flex items-center justify-center gap-2
                                   bg-primary hover:bg-primary-dark text-white
                                   px-8 py-4 rounded-full font-bold
                                   transition shadow-glow hover:-translate-y-1
                                   focus:outline-none focus:ring-2 focus:ring-primary/60">
                            {{ $primary_label }}
                            @if ($primary_icon)
                                <span class="material-symbols-outlined text-base" aria-hidden="true">{{ $primary_icon }}</span>
                            @endif
                        </a>
                    @endif

                    @if ($secondary_label && $secondary_href)
                        <a href="{{ $secondary_href }}"
                            class="inline-flex items-center justify-center gap-2
                                   border border-primary/30 text-primary-dark
                                   px-8 py-4 rounded-full font-semibold
                                   hover:bg-primary/5 transition
                                   focus:outline-none focus:ring-2 focus:ring-primary/40">
                            @if ($secondary_icon)
                                <span class="material-symbols-outlined text-base" aria-hidden="true">{{ $secondary_icon }}</span>
                            @endif
                            {{ $secondary_label }}
                        </a>
                    @endif

                </div>
            @endif

        </div>

    </div>

</section>
