{{-- CTA Band — Dark (Split Layout) primary.blade.php
|--------------------------------------------------------------------------
| Usage:
|   <x-cta.primary
|       eyebrow_icon="pets"
|       eyebrow_text="New Arrivals Welcome"
|       heading="Give Your Pet the"
|       heading_accent="Care They Deserve"
|       body="Book a consultation today."
|       primary_label="Book Now"
|       primary_href="/book"
|       primary_icon="arrow_forward"
|       secondary_label="View Services"
|       secondary_href="/services"
|       secondary_icon="call"
|   />
|
| All props optional except where defaults are set.
--}}

@props([
    'eyebrow_icon' => null,
    'eyebrow_text' => null,
    'heading' => 'Give Your Pet the',
    'heading_accent' => 'Care They Deserve',
    'body' =>
        'Book a consultation today. Our team is ready to create a personalised care plan for your furry family member.',

    'primary_label' => 'Book a Consultation',
    'primary_href' => '#',
    'primary_icon' => 'arrow_forward',

    'secondary_label' => null,
    'secondary_href' => null,
    'secondary_icon' => null,
])

<div class="w-full bg-primary-dark overflow-hidden relative rounded-2xl">

    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-12 md:py-16 relative">

        {{-- Decorative blobs --}}
        <div class="absolute top-[-20%] right-[-5%] w-72 h-72 bg-primary/40 rounded-full blur-3xl pointer-events-none"
            aria-hidden="true"></div>
        <div class="absolute bottom-[-20%] left-[-5%] w-56 h-56 bg-secondary/15 rounded-full blur-3xl pointer-events-none"
            aria-hidden="true"></div>

        {{-- Pattern overlay --}}
        <div class="absolute inset-0 opacity-10 mix-blend-overlay pointer-events-none" aria-hidden="true"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-10 max-w-5xl mx-auto">

            {{-- LEFT: copy --}}
            <div class="flex flex-col items-center md:items-start text-center md:text-left gap-5 max-w-xl">

                @if ($eyebrow_icon || $eyebrow_text)
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/20 bg-white/10
                                text-secondary text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                        @if ($eyebrow_icon)
                            <span class="material-symbols-outlined text-sm icon-filled"
                                aria-hidden="true">{{ $eyebrow_icon }}</span>
                        @endif
                        {{ $eyebrow_text }}
                    </div>
                @endif

                <h2 class="font-serif text-3xl md:text-4xl font-bold text-white leading-tight">
                    {{ $heading }}
                    @if ($heading_accent)
                        <br><span class="text-secondary italic">{{ $heading_accent }}</span>
                    @endif
                </h2>

                <p class="text-white/65 text-base leading-relaxed max-w-sm md:max-w-md">
                    {{ $body }}
                </p>

            </div>

            {{-- RIGHT: CTAs --}}
            <div class="flex flex-wrap gap-3 justify-center md:justify-end md:items-center">

                <a href="{{ $primary_href }}"
                    class="inline-flex items-center justify-center gap-2 bg-secondary hover:bg-secondary-hover
                           text-primary-dark px-8 py-3.5 rounded-full font-bold transition-all shadow-glow
                           hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-secondary/60 focus:ring-offset-2">
                    {{ $primary_label }}
                    <span class="material-symbols-outlined text-base" aria-hidden="true">{{ $primary_icon }}</span>
                </a>

                @if ($secondary_label && $secondary_href)
                    <a href="{{ $secondary_href }}"
                        class="inline-flex items-center justify-center gap-2 border border-white/30 text-white
                               px-8 py-3.5 rounded-full font-semibold hover:bg-white/10 transition-all
                               focus:outline-none focus:ring-2 focus:ring-white/40 focus:ring-offset-2">
                        @if ($secondary_icon)
                            <span class="material-symbols-outlined text-base"
                                aria-hidden="true">{{ $secondary_icon }}</span>
                        @endif
                        {{ $secondary_label }}
                    </a>
                @endif

            </div>

        </div>
    </div>
</div>
