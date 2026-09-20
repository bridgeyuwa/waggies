@props(['items', 'filters'])

<section x-data="testimonialsGrid(@js($items))" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
        <div class="mb-10"><fieldset><legend class="sr-only">Filter testimonials by service</legend><div class="flex flex-wrap gap-2">
            @foreach($filters as $filter)
                <button type="button" @click="select('{{ $filter['value'] }}')" :aria-pressed="selected === '{{ $filter['value'] }}'" :class="selected === '{{ $filter['value'] }}' ? 'bg-primary text-white' : 'bg-surface-purple text-primary-dark/70 hover:bg-surface-purple/80 hover:text-primary'" class="px-4 py-2.5 rounded-full text-sm font-medium transition-colors min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-1">{{ $filter['label'] }}</button>
            @endforeach
        </div></fieldset></div>
        <p class="text-sm text-primary-dark/50 mb-6" aria-live="polite">Showing <span x-text="filteredCount()"></span> of {{ count($items) }} testimonials</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
                <div x-show="selected === 'all' || selected === '{{ $item['service'] }}'" x-transition.opacity class="w-card p-7 transition-shadow flex flex-col gap-4 h-full">
                    <div class="flex items-center gap-0.5" aria-label="{{ $item['stars'] }} out of 5 stars">@for($star = 1; $star <= 5; $star++)<x-waggies.icon name="star" variant="filled" size="18" class="text-gold" />@endfor</div>
                    <p class="italic text-[0.9375rem] leading-relaxed text-primary-dark/75 flex-1 text-pretty">"{{ $item['quote'] }}"</p>
                    <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-serif font-bold text-sm shrink-0">{{ strtoupper(substr($item['authorInitial'], 0, 1)) }}</div><div><p class="text-sm font-semibold text-primary-dark">{{ $item['authorName'] }}</p><p class="text-xs text-primary-dark/50">{{ $item['authorSubtitle'] }}</p></div></div>
                </div>
            @endforeach
        </div>
        <p x-show="filteredCount() === 0" x-cloak class="text-center text-primary-dark/50 py-12">No testimonials for this service yet. Check back soon.</p>
    </div>
</section>
