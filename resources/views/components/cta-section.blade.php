@props([
    'title' => 'Ready to get started?',
    'subtitle' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
])

<section class="w-full bg-surface">
    {{-- Container with responsive horizontal padding per system --}}
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-20">
        {{-- Gradient panel --}}
        <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden shadow-soft">

            {{-- Pattern overlay --}}
            <div class="absolute inset-0 opacity-10 mix-blend-overlay"
                 style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>

            {{-- Text --}}
            <div class="relative z-10 max-w-xl">
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                    {{ $title }}
                </h2>

                @if($subtitle)
                    <p class="text-white/70 font-medium text-lg leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            {{-- CTAs --}}
            <div class="relative z-10 flex flex-col sm:flex-row gap-4 w-full md:w-auto shrink-0">
                @if($primaryCta)
                    <x-button href="{{ $primaryCta['href'] }}" variant="secondary" size="lg" label="{{ $primaryCta['label'] }}" class="w-full sm:w-auto text-center" />
                @endif

                @if($secondaryCta)
                   
                    <x-button href="{{ $secondaryCta['href'] }}" variant="outline-secondary" size="lg" label="{{ $secondaryCta['label'] }}" class="w-full sm:w-auto text-center" />
                               @endif
            </div>

        </div>
    </div>
</section>