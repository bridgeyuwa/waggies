@props([
    'layout'       => 'split', // split | centered
    'rating'       => '4.9',
    'reviewCount'  => '500+',
    'imageSrc'     => null,
    'title'        => '',
    'subtitle'     => '',
    'primaryCta'   => null,
    'secondaryCta' => null,
])

<section class="w-full relative bg-surface-purple overflow-hidden">

    {{-- Decorative blobs (global B8 identity) --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-primary/15 blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-secondary/20 blur-3xl translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

    @if($layout === 'centered')

        {{-- =========================
            CENTERED FULL-BLEED HERO
        ========================== --}}
        <section class="w-full relative overflow-hidden bg-cover bg-center"
                 style="background-image: url('{{ $imageSrc }}');">

            {{-- overlays --}}
            <div class="absolute inset-0 bg-primary-dark/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-primary-dark/80 via-transparent to-transparent"></div>

            <div class="relative z-10 flex min-h-[520px] items-center justify-center text-center px-4 py-20">

                <div class="max-w-3xl flex flex-col items-center gap-6">

                    {{-- Eyebrow --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                        <span class="w-2 h-2 rounded-full bg-primary-light"></span>
                        Established 2020
                    </div>

                    {{-- Title --}}
                    <h1 class="font-serif text-4xl md:text-5xl font-bold text-white leading-tight">
                        {!! $title !!}
                    </h1>

                    {{-- Subtitle --}}
                    @if($subtitle)
                        <p class="text-white/85 text-lg max-w-2xl leading-relaxed">
                            {{ $subtitle }}
                        </p>
                    @endif

                    {{-- CTAs --}}
                    <div class="flex flex-col sm:flex-row gap-4 mt-4">

                        @if($primaryCta)
                            <a href="{{ $primaryCta['href'] }}"
                               class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-3 rounded-full font-bold hover:bg-surface transition shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/60">
                                {{ $primaryCta['label'] }}
                            </a>
                        @endif

                        @if($secondaryCta)
                            <a href="{{ $secondaryCta['href'] }}"
                               class="inline-flex items-center justify-center gap-2 border border-white/40 text-white px-8 py-3 rounded-full font-bold hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/40">
                                {{ $secondaryCta['label'] }}
                            </a>
                        @endif

                    </div>

                </div>

            </div>
        </section>

    @else

        {{-- =========================
            SPLIT HERO (CONSTRAINED)
        ========================== --}}
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 relative z-10">

            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16 py-16 md:py-24">

                {{-- LEFT CONTENT --}}
                <div class="flex flex-col gap-6 max-w-lg flex-1">

                    {{-- rating badge --}}
                    <x-badge.stat :rating="$rating" :review-count="$reviewCount" />

                    {{-- title --}}
                    <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark leading-tight">
                        {!! $title !!}
                    </h1>

                    {{-- subtitle --}}
                    @if($subtitle)
                        <p class="text-primary-dark/60 text-base leading-relaxed">
                            {{ $subtitle }}
                        </p>
                    @endif

                    {{-- CTAs --}}
                    @if($primaryCta || $secondaryCta)
                        <div class="flex flex-wrap gap-3">

                            @if($primaryCta)
                                <a href="{{ $primaryCta['href'] }}"
                                   class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                                    {{ $primaryCta['label'] }}
                                </a>
                            @endif

                            @if($secondaryCta)
                                <a href="{{ $secondaryCta['href'] }}"
                                   class="inline-flex items-center gap-2 border border-primary/30 text-primary-dark px-8 py-4 rounded-full font-semibold hover:bg-white/50 transition">
                                    {{ $secondaryCta['label'] }}
                                </a>
                            @endif

                        </div>
                    @endif

                </div>

                {{-- RIGHT SLOT --}}
                <div class="flex-1 w-full max-w-lg lg:max-w-none">
                    @if($imageSrc)
                        <img 
                            src="{{ $imageSrc }}" 
                            alt="" 
                            class="w-full h-full object-cover rounded-2xl shadow-xl"
                        >
                    @else
                        {{ $slot }}
                    @endif
                </div>

            </div>

        </div>

    @endif

</section>