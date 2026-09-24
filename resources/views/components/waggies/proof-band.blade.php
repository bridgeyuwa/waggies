@props(['variant' => 'overview'])

@php($proofTestimonials = $testimonials ?? [])

@if($proofTestimonials !== [])
    <section class="border-y border-primary/5 bg-surface-purple/30 py-20">
        <div class="page-container">
            <div class="mx-auto mb-14 max-w-2xl text-center"><span class="text-eyebrow mb-2 block">PROVEN CARE OUTCOMES</span><h2 class="text-h2 mb-3">What Pet Owners Experience</h2><p class="text-body-sm text-primary-dark/70">Real feedback from pet owners across Abuja who count on Waggies for boarding, grooming, and relocation.</p></div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ($proofTestimonials as $testimonial)
                    <x-waggies.card hover class="flex flex-col justify-between border border-primary/10 bg-white p-7">
                        @php($rating = (int) ($testimonial['stars'] ?? 5))
                        <div><div class="mb-4 flex items-center justify-between gap-2 border-b border-primary/5 pb-3"><span class="text-xs font-bold uppercase tracking-wider text-primary">{{ $testimonial['serviceName'] }}</span><div class="flex gap-0.5" role="img" aria-label="{{ $rating > 0 ? $rating.' out of 5 stars' : 'No rating provided' }}">@for($i = 1; $i <= 5; $i++)<x-waggies.icon name="star" size="16" variant="{{ $i <= $rating ? 'filled' : 'outlined' }}" class="{{ $i <= $rating ? 'text-gold' : 'text-primary/15' }}" />@endfor</div></div><blockquote class="mb-6 text-sm font-medium leading-relaxed text-primary-dark/85">“{{ $testimonial['quote'] }}”</blockquote></div>
                        <div><div class="mb-4 flex items-center gap-3 border-t border-primary/5 pt-3"><div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 font-serif text-xs font-bold text-primary" aria-hidden="true">{{ $testimonial['initial'] }}</div><div><cite class="not-italic block text-sm font-bold leading-tight text-primary-dark">{{ $testimonial['name'] }}</cite><span class="block text-xs text-primary-dark/50">{{ $testimonial['subtitle'] }}</span></div></div><a href="{{ $testimonial['serviceHref'] }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-primary-dark">Explore {{ $testimonial['serviceName'] }} <x-waggies.icon name="arrow-forward" size="14" /></a></div>
                    </x-waggies.card>
                @endforeach
            </div>
        </div>
    </section>
@endif
