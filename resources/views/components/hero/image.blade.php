{{--
    Hero — Image overlay.
    Full-bleed background image with gradient overlay.

    Props:
      $imageSrc      — background image URL
      $eyebrow       — small uppercase pill text (e.g. "Abuja's #1 Dog Boarding")
      $eyebrowIcon   — Material Symbol in the eyebrow pill. Default: star
      $title         — h1 text (HTML allowed for line breaks)
      $subtitle      — subheading text
      $primaryCta    — ['label' => '', 'href' => '']
      $secondaryCta  — ['label' => '', 'href' => ''] (optional)
      $minHeight     — CSS min-height value. Default: 560px
      $variant         — variant vertical content alignment: 'bottom' (default) | 'center'
--}}
@props([
    'imageSrc' => '',
    'eyebrow' => '',
    'eyebrowIcon' => 'star',
    'title' => '',
    'subtitle' => '',
    'primaryCta' => null,
    'secondaryCta' => null,
    'minHeight' => '560px',
    'variant' => 'bottom',
])

<section class="relative flex flex-col {{ $variant === 'center' ? 'justify-center' : 'justify-end' }} bg-cover bg-center"
    style="min-height: {{ $minHeight }}; background-image: linear-gradient(rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.65) 100%), url('{{ $imageSrc }}');"
    aria-label="{{ strip_tags($title) }}">

    <div class="max-w-7xl mx-auto w-full px-4 md:px-10 lg:px-12 py-12 md:py-16">
        <div class="relative z-10 max-w-xl animate-fade-in-up">

            {{-- Eyebrow pill --}}
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

            {{-- Title --}}
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-white leading-tight mb-4">
                {!! $title !!}
            </h1>

            {{-- Subtitle --}}
            @if ($subtitle)
                <p class="text-white/75 text-base mb-6 leading-relaxed">{{ $subtitle }}</p>
            @endif

            {{-- CTAs --}}
            @if ($primaryCta || $slot->isNotEmpty())
                <div class="flex flex-wrap gap-3">
                    @if ($slot->isNotEmpty())
                        {{ $slot }}
                    @else
                        @if ($primaryCta)
                            <a href="{{ $primaryCta['href'] }}"
                                class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary-hover
                                      text-primary-dark font-bold px-8 py-3.5 rounded-full transition
                                      shadow-glow hover:-translate-y-1
                                      focus:outline-none focus:ring-2 focus:ring-secondary/60 focus:ring-offset-2">
                                {{ $primaryCta['label'] }}
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        @endif
                        @if ($secondaryCta)
                            <a href="{{ $secondaryCta['href'] }}"
                                class="inline-flex items-center gap-2 border border-white/30 text-white
                                      px-8 py-3.5 rounded-full font-semibold hover:bg-white/10 transition
                                      focus:outline-none focus:ring-2 focus:ring-white/60 focus:ring-offset-2">
                                {{ $secondaryCta['label'] }}
                            </a>
                        @endif
                    @endif
                </div>
            @endif

        </div>
    </div>

</section>
