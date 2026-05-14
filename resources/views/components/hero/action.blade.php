{{-- Action hero --}}
@props([
    'title' => '',
    'subtitle' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
    'align' => 'center', // center | left (optional escape hatch)
])

<section class="w-full py-24 md:py-32 bg-white">

    <div class="max-w-2xl mx-auto px-4 md:px-6">

        <div
            class="flex flex-col gap-6
            {{ $align === 'left' ? 'text-left items-start' : 'text-center items-center' }}">

            {{-- TITLE (instructional, not marketing) --}}
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight">
                {{ $title }}
            </h1>

            {{-- SUBTITLE (optional friction reducer) --}}
            @if ($subtitle)
                <p class="text-primary-dark/60 text-base leading-relaxed max-w-xl">
                    {{ $subtitle }}
                </p>
            @endif

            {{-- ACTIONS --}}
            <div
                class="w-full flex flex-col sm:flex-row gap-3 mt-4
                {{ $align === 'left' ? 'sm:justify-start' : 'sm:justify-center' }}">

                @if ($primaryCta)
                    <a href="{{ $primaryCta['href'] }}"
                        class="inline-flex items-center justify-center gap-2
                              bg-primary hover:bg-primary-dark text-white
                              px-8 py-4 rounded-full font-bold
                              transition shadow-glow hover:-translate-y-1
                              focus:outline-none focus:ring-2 focus:ring-primary/60">

                        {{ $primaryCta['label'] }}

                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                @endif

                @if ($secondaryCta)
                    <a href="{{ $secondaryCta['href'] }}"
                        class="inline-flex items-center justify-center gap-2
                              border border-primary/30 text-primary-dark
                              px-8 py-4 rounded-full font-semibold
                              hover:bg-primary/5 transition
                              focus:outline-none focus:ring-2 focus:ring-primary/40">

                        {{ $secondaryCta['label'] }}
                    </a>
                @endif

            </div>

        </div>

    </div>

</section>
