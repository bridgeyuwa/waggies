{{--
    Hero — Plain (Upgraded)
    Clean category / hub hero with optional visual support.

    Props:
      eyebrow          — small uppercase context label (e.g. "Boarding Services")
      eyebrow_icon     — optional icon inside eyebrow
      title            — h1 text (HTML allowed)
      subtitle         — optional subheading
      align            — 'center' (default) | 'left'

      image_src        — optional supporting image (NOT dominant)

      primary_label    — optional CTA (use sparingly on hubs)
      primary_href     — CTA URL
      primary_icon     — optional icon

      secondary_label  — optional secondary CTA
      secondary_href   — optional secondary CTA URL
      secondary_icon   — optional icon
--}}

@props([
    'eyebrow' => null,
    'eyebrow_icon' => null,

    'title' => '',
    'subtitle' => null,
    'align' => 'center',

    'image_src' => null,

    'primary_label' => null,
    'primary_href' => '#',
    'primary_icon' => 'arrow_forward',

    'secondary_label' => null,
    'secondary_href' => '#',
    'secondary_icon' => null,
])

<section class="w-full py-20 md:py-28 bg-white">

    <div class="max-w-4xl mx-auto px-4 md:px-6">

        <div
            class="flex flex-col gap-6 
            {{ $align === 'left' ? 'text-left items-start' : 'text-center items-center' }}">

            {{-- Eyebrow (context anchor) --}}
            @if ($eyebrow || $eyebrow_icon)
                <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-primary/60">
                    @if ($eyebrow_icon)
                        <span class="material-symbols-outlined text-sm text-secondary">
                            {{ $eyebrow_icon }}
                        </span>
                    @endif
                    {{ $eyebrow }}
                </span>
            @endif

            {{-- Title --}}
            <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-bold text-primary-dark leading-tight">
                {!! $title !!}
            </h1>

            {{-- Subtitle --}}
            @if ($subtitle)
                <p class="text-primary-dark/60 text-base leading-relaxed max-w-2xl">
                    {{ $subtitle }}
                </p>
            @endif

            {{-- CTA (use sparingly for hubs) --}}
            @if ($primary_label || $secondary_label)
                <div
                    class="flex flex-col sm:flex-row gap-3 mt-4
                    {{ $align === 'left' ? 'sm:justify-start' : 'sm:justify-center' }}">

                    @if ($primary_label)
                        <a href="{{ $primary_href }}"
                            class="inline-flex items-center justify-center gap-2
                                   bg-primary hover:bg-primary-dark text-white
                                   px-8 py-4 rounded-full font-bold
                                   transition shadow-glow hover:-translate-y-1">
                            {{ $primary_label }}
                            @if ($primary_icon)
                                <span class="material-symbols-outlined text-base">
                                    {{ $primary_icon }}
                                </span>
                            @endif
                        </a>
                    @endif

                    @if ($secondary_label && $secondary_href)
                        <a href="{{ $secondary_href }}"
                            class="inline-flex items-center justify-center gap-2
                                   border border-primary/30 text-primary-dark
                                   px-8 py-4 rounded-full font-semibold
                                   hover:bg-primary/5 transition">
                            @if ($secondary_icon)
                                <span class="material-symbols-outlined text-base">
                                    {{ $secondary_icon }}
                                </span>
                            @endif
                            {{ $secondary_label }}
                        </a>
                    @endif

                </div>
            @endif

            {{-- Optional Visual Anchor (subtle, not dominant) --}}
            @if ($image_src)
                <div class="mt-10 w-full flex justify-center">
                    <img src="{{ $image_src }}" alt=""
                        class="w-full max-w-md md:max-w-lg rounded-2xl shadow-md object-cover">
                </div>
            @endif

        </div>

    </div>

</section>
