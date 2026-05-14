{{-- CTA Band — Compact Strip (Inline) tertiary.blade.php
|--------------------------------------------------------------------------
| Compact, single-CTA strip for service pages and newsletter-adjacent
| placements. Dark background, icon circle, one action.
|
| Usage:
|   <x-cta.tertiary
|       icon="volunteer_activism"
|       heading="Not sure what your pet needs?"
|       body="Our vets will guide you — no commitment required."
|       cta_label="Free Consultation"
|       cta_href="/consult"
|   />
|
| All props optional — sensible defaults provided.
--}}

@props([
    'icon' => 'volunteer_activism',
    'heading' => 'Not sure what your pet needs?',
    'body' => 'Our vets will guide you — no commitment required.',
    'cta_label' => 'Free Consultation',
    'cta_href' => '#',
])

<div class="w-full bg-primary-dark overflow-hidden relative rounded-2xl">

    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-8 md:py-10 relative">

        {{-- Decorative blob --}}
        <div class="absolute top-[-30%] right-[-3%] w-40 h-40 bg-secondary/10 rounded-full blur-3xl pointer-events-none"
            aria-hidden="true"></div>

        <div class="relative z-10 max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">

            {{-- Icon + copy --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center shrink-0"
                    aria-hidden="true">
                    <span
                        class="material-symbols-outlined text-secondary text-2xl icon-filled">{{ $icon }}</span>
                </div>
                <div>
                    <p class="font-serif font-bold text-white text-lg leading-tight">{{ $heading }}</p>
                    <p class="text-white/60 text-sm mt-0.5">{{ $body }}</p>
                </div>
            </div>

            {{-- CTA --}}
            <a href="{{ $cta_href }}"
                class="shrink-0 inline-flex items-center gap-2 bg-secondary hover:bg-secondary-hover text-primary-dark
                       px-7 py-3 rounded-full font-bold transition-all shadow-glow hover:-translate-y-0.5 whitespace-nowrap
                       focus:outline-none focus:ring-2 focus:ring-secondary/60 focus:ring-offset-2">
                {{ $cta_label }}
                <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
            </a>

        </div>
    </div>
</div>
