@props([
    'eyebrow'  => '',
    'title'    => '',
    'subtitle' => '',
    'features' => [],
    'cta'      => null,
])

<section class="w-full bg-primary-dark relative overflow-hidden text-white py-24">

    {{-- Dot grid --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none"
         style="background-image: radial-gradient(#E8D5B5 1px, transparent 1px); background-size: 40px 40px;">
    </div>

    {{-- Ambient glow (from A, controlled) --}}
    <div class="absolute top-0 right-0 w-[700px] h-[700px] bg-primary rounded-full blur-[120px] opacity-20 -translate-y-1/2 translate-x-1/3"></div>

    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT --}}
            <div class="flex flex-col gap-6 max-w-xl">
            {{-- <div class="flex flex-col gap-6 max-w-xl mx-auto lg:mx-0 text-center lg:text-left items-center lg:items-start"> --}}

                @if($eyebrow)
                    <p class="text-secondary font-bold tracking-widest uppercase text-xs">
                        {{ $eyebrow }}
                    </p>
                @endif

                <h2 class="font-serif text-3xl md:text-4xl font-bold leading-tight">
                    {!! $title !!}
                </h2>

                @if($subtitle)
                    <p class="text-white/70 leading-relaxed text-base">
                        {{ $subtitle }}
                    </p>
                @endif

                @if($cta)
                    <div class="pt-2">
                        <a href="{{ $cta['href'] }}"
                           class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary-hover
                                  text-primary-dark font-bold px-8 py-3.5 rounded-full transition
                                  shadow-glow hover:-translate-y-1
                                  focus:outline-none focus:ring-2 focus:ring-secondary/60 focus:ring-offset-2 focus:ring-offset-primary-dark">
                            {{ $cta['label'] }}
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                @endif

            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col gap-5">

                @if($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @foreach($features as $feature)
                        <div class="flex items-start gap-4 p-5 rounded-2xl
                                    bg-white/[0.03] backdrop-blur-sm
                                    hover:bg-white/[0.06]
                                    border border-white/10
                                    transition-all duration-200">

                            <div class="size-12 rounded-full bg-white/10 flex items-center justify-center
                                        text-secondary shrink-0">
                                <span class="material-symbols-outlined text-lg">
                                    {{ $feature['icon'] }}
                                </span>
                            </div>

                            <div>
                                <h3 class="font-serif font-bold text-white text-lg">
                                    {{ $feature['title'] }}
                                </h3>
                                <p class="text-white/60 text-sm mt-1 leading-relaxed">
                                    {{ $feature['description'] }}
                                </p>
                            </div>

                        </div>
                    @endforeach
                @endif

            </div>

        </div>
    </div>

</section>