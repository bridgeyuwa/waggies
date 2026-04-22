{{--
|--------------------------------------------------------------------------
| CTA Band — Variant 3: Split  (compact · service pages · newsletter adjacent)
|--------------------------------------------------------------------------
| B21 · Homepage · Service Pages
|
| Usage:
|   <x-cta-band-split />
|
| Props (all optional):
|   icon        string   Material Symbol name   Default: "volunteer_activism"
|   heading     string                          Default: "Not sure what your pet needs?"
|   body        string                          Default: "Our vets will guide you — no commitment required."
|   cta_label   string                          Default: "Free Consultation"
|   cta_href    string                          Default: "#"
--}}

@props([
    'icon'      => 'volunteer_activism',
    'heading'   => 'Not sure what your pet needs?',
    'body'      => 'Our vets will guide you — no commitment required.',
    'cta_label' => 'Free Consultation',
    'cta_href'  => '#',
])

<div class="bg-primary-dark rounded-2xl px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-6">

    {{-- Icon + copy --}}
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center shrink-0"
             aria-hidden="true">
            <span class="material-symbols-outlined text-secondary text-2xl">{{ $icon }}</span>
        </div>
        <div>
            <p class="font-serif font-bold text-white text-lg leading-tight">{{ $heading }}</p>
            <p class="text-white/55 text-sm mt-0.5">{{ $body }}</p>
        </div>
    </div>

    {{-- CTA --}}
    <a href="{{ $cta_href }}"
       class="shrink-0 inline-flex items-center gap-2 bg-secondary hover:bg-secondary-hover text-primary-dark
              px-7 py-3 rounded-full font-bold transition shadow-glow hover:-translate-y-0.5 whitespace-nowrap
              focus:outline-none focus:ring-2 focus:ring-secondary/60 focus:ring-offset-2">
        {{ $cta_label }}
        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
    </a>

</div>
