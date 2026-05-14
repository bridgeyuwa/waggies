{{--
    Hero — Overlay
    Full-bleed background image with gradient overlay.

    Props:
      image_src        — background image URL
      eyebrow          — small uppercase pill text (e.g. "Abuja's #1 Dog Boarding")
      eyebrow_icon     — Material Symbol inside the eyebrow pill (optional)
      title            — h1 text (HTML allowed for <br> / <em>); used as aria-label
      subtitle         — optional subheading text
      min_height       — CSS min-height value. Default: '560px'
      content_align    — vertical content position: 'bottom' (default) | 'center'
      primary_label    — primary CTA button text (omit to hide)
      primary_href     — primary CTA URL. Default: '#'
      primary_icon     — Material Symbol after the label. Default: 'arrow_forward'
      secondary_label  — secondary CTA button text (omit to hide)
      secondary_href   — secondary CTA URL. Default: '#'
      secondary_icon   — optional Material Symbol before the secondary label

    Slot:
      $slot — when provided, replaces the entire CTA block
               (use for custom forms, booking widgets, etc.)
--}}
@props([
    'image_src' => '',
    'eyebrow' => null,
    'eyebrow_icon' => null,
    'title' => '',
    'subtitle' => null,
    'min_height' => '560px',
    'content_align' => 'bottom',

    'primary_label' => null,
    'primary_href' => '#',
    'primary_icon' => 'arrow_forward',

    'secondary_label' => null,
    'secondary_href' => '#',
    'secondary_icon' => null,
])

<section
    class="relative flex flex-col  {{ $content_align === 'center' ? 'justify-center' : 'justify-end' }} bg-cover bg-center"
    style="min-height: {{ $min_height }}; background-image: linear-gradient(rgba(62,26,87,0.10) 0%, rgba(62,26,87,0.80) 100%), url('{{ $image_src }}');"
    aria-label="{{ strip_tags($title) }}">

    <div class="max-w-7xl mx-auto w-full px-4 md:px-10 lg:px-12 py-12 md:py-16">
        <div class="relative z-10 max-w-xl animate-fade-in-up">

            @if ($eyebrow || $eyebrow_icon)
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4
                             bg-white/15 border border-white/25 text-white
                             text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                    @if ($eyebrow_icon)
                        <span class="material-symbols-outlined text-sm text-secondary icon-filled"
                            aria-hidden="true">{{ $eyebrow_icon }}</span>
                    @endif
                    {{ $eyebrow }}
                </span>
            @endif

            <h1 class="font-serif text-4xl md:text-5xl font-bold text-white leading-tight mb-4">
                {!! $title !!}
            </h1>

            @if ($subtitle)
                <p class="text-white/75 text-base mb-6 leading-relaxed">{{ $subtitle }}</p>
            @endif

            @if ($primary_label || $secondary_label || $slot->isNotEmpty())
                <div class="flex flex-wrap gap-3">

                    @if ($slot->isNotEmpty())
                        {{-- Custom CTA block (form, widget, etc.) --}}
                        {{ $slot }}
                    @else
                        @if ($primary_label)
                            <a href="{{ $primary_href }}"
                                class="inline-flex items-center gap-2
                                       bg-secondary hover:bg-secondary-hover text-primary-dark
                                       font-bold px-8 py-3.5 rounded-full transition
                                       shadow-glow hover:-translate-y-1
                                       focus:outline-none focus:ring-2 focus:ring-secondary/60 focus:ring-offset-2">
                                {{ $primary_label }}
                                @if ($primary_icon)
                                    <span class="material-symbols-outlined text-base"
                                        aria-hidden="true">{{ $primary_icon }}</span>
                                @endif
                            </a>
                        @endif

                        @if ($secondary_label && $secondary_href)
                            <a href="{{ $secondary_href }}"
                                class="inline-flex items-center gap-2
                                       border border-white/30 text-white
                                       px-8 py-3.5 rounded-full font-semibold
                                       hover:bg-white/10 transition
                                       focus:outline-none focus:ring-2 focus:ring-white/60 focus:ring-offset-2">
                                @if ($secondary_icon)
                                    <span class="material-symbols-outlined text-base"
                                        aria-hidden="true">{{ $secondary_icon }}</span>
                                @endif
                                {{ $secondary_label }}
                            </a>
                        @endif
                    @endif

                </div>
            @endif

        </div>
    </div>

</section>
