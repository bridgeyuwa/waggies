{{--
    Hero — Split
    Two-column hero on a decorative purple-gradient background.
    Left: text content + CTAs. Right: image or slotted content.

    Props:
      title            — h1 text (HTML allowed for <br> / <em>)
      subtitle         — optional subheading text
      eyebrow          — optional small uppercase pill text
      eyebrow_icon     — Material Symbol inside the eyebrow pill (optional)
      image_src        — optional right-side image URL; falls back to $slot
      rating           — star rating shown in the stat badge. Default: '4.9'
      review_count     — review count shown in the stat badge. Default: '500+'
      primary_label    — primary CTA button text (omit to hide)
      primary_href     — primary CTA URL. Default: '#'
      primary_icon     — Material Symbol after the label. Default: 'arrow_forward'
      secondary_label  — secondary CTA button text (omit to hide)
      secondary_href   — secondary CTA URL. Default: '#'
      secondary_icon   — optional Material Symbol before the secondary label

    Slot:
      $slot — right-panel content when image_src is not provided
               (e.g. a booking widget, card, or form)
--}}

{{-- Hero — Split (Fixed Image Stability) --}}

@props([
    'title' => '',
    'subtitle' => null,
    'eyebrow' => null,
    'eyebrow_icon' => null,
    'image_src' => null,
    'rating' => '4.9',
    'review_count' => '500+',

    'primary_label' => null,
    'primary_href' => '#',
    'primary_icon' => 'arrow_forward',

    'secondary_label' => null,
    'secondary_href' => '#',
    'secondary_icon' => null,
])

<section class="w-full relative bg-surface-purple overflow-hidden">

    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-primary/15 blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"
        aria-hidden="true"></div>

    <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-secondary/20 blur-3xl translate-y-1/2 -translate-x-1/4 pointer-events-none"
        aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 relative z-10">

        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16 py-16 md:py-24">

            {{-- LEFT: text content --}}
            <div class="flex flex-col gap-6 max-w-md flex-1">

                @if ($eyebrow || $eyebrow_icon)
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full w-fit
                                 bg-white/20 border border-white/30 text-primary-dark
                                 text-xs font-bold uppercase tracking-widest">
                        @if ($eyebrow_icon)
                            <span class="material-symbols-outlined text-sm text-secondary icon-filled"
                                aria-hidden="true">{{ $eyebrow_icon }}</span>
                        @endif
                        {{ $eyebrow }}
                    </span>
                @endif

                <x-badge.stat :rating="$rating" :review-count="$review_count" />

                <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark leading-tight">
                    {!! $title !!}
                </h1>

                @if ($subtitle)
                    <p class="text-primary-dark/60 text-base leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif

                @if ($primary_label || $secondary_label)
                    <div class="flex flex-col items-start gap-3 ml-1">

                        @if ($primary_label)
                            <a href="{{ $primary_href }}"
                                class="inline-flex items-center gap-2
                                       bg-primary hover:bg-primary-dark text-white
                                       px-8 py-4 rounded-full font-bold
                                       transition shadow-glow hover:-translate-y-1
                                       focus:outline-none focus:ring-2 focus:ring-primary/60">
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
                                       border border-primary/30 text-primary-dark
                                       px-8 py-4 rounded-full font-semibold
                                       hover:bg-white/50 transition
                                       focus:outline-none focus:ring-2 focus:ring-primary/40">
                                @if ($secondary_icon)
                                    <span class="material-symbols-outlined text-base"
                                        aria-hidden="true">{{ $secondary_icon }}</span>
                                @endif
                                {{ $secondary_label }}
                            </a>
                        @endif

                    </div>
                @endif

            </div>

            {{-- RIGHT: image --}}
            <div class="flex-1 w-full max-w-lg lg:max-w-none">

                <div class="relative aspect-[4/3] lg:aspect-[5/4] rounded-2xl overflow-hidden shadow-lg">

                    @if ($image_src)
                        <img src="{{ $image_src }}" alt=""
                            class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            {{ $slot }}
                        </div>
                    @endif

                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-surface-purple/40 to-transparent pointer-events-none">
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
