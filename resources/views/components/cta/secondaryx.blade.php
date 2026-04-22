{{-- CTA Band — Purple (Split Layout Fixed) --}}

@props([
    'badge_icon'      => null, //'pets'
    'badge_text'      => null, //'New Arrivals Welcome'
    'heading'         => 'Ready to Get Started?',
    'heading_accent'  => '',
    'body'            => 'Join thousands of pet owners in Abuja who trust Waggies for world-class care. Your first visit is on us.',
    'primary_label'   => 'Book Now',
    'primary_href'    => '#',
    'secondary_label' => 'View Services',
    'secondary_href'  => '#',
])

<div class="w-full bg-surface-purple relative overflow-hidden rounded-2xl">

    {{-- Container (system-consistent like A/B fixed) --}}
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-12 md:py-16 relative">

        {{-- Decorative blob --}}
        <div class="absolute top-[-10%] right-[-5%] w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Content wrapper --}}
        <div class="relative z-10 max-w-5xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-10">

            {{-- LEFT --}}
            <div class="flex flex-col items-center md:items-start text-center md:text-left gap-5 max-w-xl">

                {{-- Badge --}}
                 @if($badge_icon || $badge_text)
                <span class="inline-flex items-center gap-2 bg-primary text-white text-xs font-bold
                             px-4 py-1.5 rounded-full uppercase tracking-wider shadow-glow">
                    <span class="material-symbols-outlined text-sm">{{ $badge_icon }}</span>
                    {{ $badge_text }}
                </span>
                @endif

                {{-- Heading --}}
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight">
                    {{ $heading }}
                    @if ($heading_accent)
                        <br><span class="text-primary italic">{{ $heading_accent }}</span>
                    @endif
                </h2>

                {{-- Body --}}
                <p class="text-primary-dark/55 text-base leading-relaxed max-w-sm md:max-w-md">
                    {{ $body }}
                </p>

            </div>

            {{-- RIGHT --}}
            <div class="flex flex-wrap md:flex-row gap-3 justify-center md:justify-end md:items-center">

                <a href="{{ $primary_href }}"
                   class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white
                          px-8 py-3.5 rounded-full font-bold transition shadow-glow hover:-translate-y-1
                          focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
                    {{ $primary_label }}
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>

                <a href="{{ $secondary_href }}"
                   class="inline-flex items-center justify-center gap-2 border border-primary/30 text-primary
                          px-8 py-3.5 rounded-full font-semibold hover:bg-white transition-colors
                          focus:outline-none focus:ring-2 focus:ring-primary/60">
                    {{ $secondary_label }}
                </a>

            </div>

        </div>
    </div>
</div>