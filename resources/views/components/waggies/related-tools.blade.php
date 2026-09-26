@props(['currentRoute'])
@php
    $all = collect(\App\Http\Controllers\ToolData::catalogue());
    $labels = ['health' => 'Health Tools', 'calculator' => 'Calculators', 'reference' => 'Reference', 'utility' => 'Utilities'];
    $relatedIds = [
        'tools.symptom-checker' => ['emergency-guide', 'parasite-schedule', 'vaccination-schedule', 'pet-age-calculator', 'nutrition-calculator', 'breed-finder', 'behavior-tips', 'new-pet-checklist'],
        'tools.vaccination' => ['parasite-schedule', 'symptom-checker', 'new-pet-checklist', 'breed-finder', 'behavior-tips', 'emergency-guide', 'nutrition-calculator', 'pet-age-calculator'],
        'tools.parasite' => ['vaccination-schedule', 'symptom-checker', 'emergency-guide', 'nutrition-calculator', 'pet-age-calculator', 'breed-finder', 'behavior-tips', 'new-pet-checklist'],
        'tools.emergency' => ['symptom-checker', 'parasite-schedule', 'vaccination-schedule', 'nutrition-calculator', 'pet-age-calculator', 'breed-finder', 'behavior-tips', 'new-pet-checklist'],
        'tools.pet-age' => ['breed-finder', 'nutrition-calculator', 'behavior-tips', 'new-pet-checklist', 'symptom-checker', 'emergency-guide', 'parasite-schedule', 'vaccination-schedule'],
        'tools.nutrition' => ['pet-age-calculator', 'breed-finder', 'behavior-tips', 'symptom-checker', 'emergency-guide', 'parasite-schedule', 'vaccination-schedule', 'new-pet-checklist'],
        'tools.breed-finder' => ['pet-age-calculator', 'behavior-tips', 'nutrition-calculator', 'vaccination-schedule', 'new-pet-checklist', 'symptom-checker', 'emergency-guide', 'parasite-schedule'],
        'tools.behavior-tips' => ['breed-finder', 'new-pet-checklist', 'pet-age-calculator', 'vaccination-schedule', 'symptom-checker', 'emergency-guide', 'parasite-schedule', 'nutrition-calculator'],
        'tools.new-pet-checklist' => ['vaccination-schedule', 'behavior-tips', 'pet-age-calculator', 'breed-finder', 'symptom-checker', 'emergency-guide', 'parasite-schedule', 'nutrition-calculator'],
    ][$currentRoute] ?? [];
    $tools = collect($relatedIds)->map(fn ($id) => $all->firstWhere('id', $id))->filter();
@endphp
@if($tools->isNotEmpty())
<section x-data="{ canBack: false, canForward: true, update() { this.canBack = $refs.rail.scrollLeft > 4; this.canForward = $refs.rail.scrollLeft + $refs.rail.clientWidth < $refs.rail.scrollWidth - 4 }, move(direction) { $refs.rail.scrollBy({ left: direction === 'forward' ? $refs.rail.clientWidth * .82 : -$refs.rail.clientWidth * .82, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' }); setTimeout(() => this.update(), 250) } }" x-init="update()" class="relative left-1/2 mt-14 w-[100vw] max-w-[100vw] -translate-x-1/2 overflow-hidden border-y border-primary/10 bg-white py-12" aria-labelledby="related-tools-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-5"><div><p class="text-eyebrow text-primary/60">Recommended next</p><h2 id="related-tools-heading" class="mt-1 font-serif text-2xl font-bold text-primary-dark">More useful tools</h2><p class="mt-2 max-w-xl text-sm leading-relaxed text-primary-dark/60">Follow the most relevant next step, or browse the full tool collection.</p></div><div class="flex shrink-0 items-center gap-2"><button type="button" @click="move('back')" :disabled="!canBack" aria-label="Previous related tools" class="grid min-h-[44px] min-w-[44px] place-items-center rounded-full border border-primary/20 text-primary hover:bg-surface-purple disabled:pointer-events-none disabled:opacity-35"><x-waggies.icon name="arrow-back" size="18" /></button><button type="button" @click="move('forward')" :disabled="!canForward" aria-label="Next related tools" class="grid min-h-[44px] min-w-[44px] place-items-center rounded-full border border-primary/20 text-primary hover:bg-surface-purple disabled:pointer-events-none disabled:opacity-35"><x-waggies.icon name="arrow-forward" size="18" /></button></div></div>
        <div x-ref="rail" @scroll="update()" tabindex="0" role="region" aria-roledescription="carousel" aria-label="Related tools. Use the arrow keys or buttons to browse." class="mt-6 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain pb-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
            @foreach($tools as $index => $tool)
                <a href="{{ route($tool['route']) }}" aria-label="{{ $tool['name'] }}. {{ $tool['description'] }}" class="group relative flex w-[min(82vw,20rem)] min-w-[min(82vw,20rem)] snap-start flex-col rounded-2xl border border-primary/12 bg-surface p-5 transition-[border-color,box-shadow,transform] duration-[180ms] ease-out hover:-translate-y-0.5 hover:border-primary/35 hover:shadow-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
                    @if($index < 3)<span class="absolute right-4 top-4 rounded-full bg-secondary/60 px-2.5 py-1 text-xs font-bold uppercase tracking-[0.12em] text-primary-dark">Best next step</span>@endif
                    <div class="flex items-start gap-3"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-surface-purple text-primary transition-colors duration-[180ms] group-hover:bg-primary group-hover:text-white"><x-waggies.icon name="{{ $tool['icon'] }}" size="20" /></span><div class="min-w-0 pr-20"><h3 class="font-serif text-lg font-semibold leading-tight text-primary-dark group-hover:text-primary">{{ $tool['name'] }}</h3><p class="mt-1 text-xs font-semibold uppercase tracking-[0.12em] text-primary/55">{{ $labels[$tool['category']] }}</p></div></div>
                    <p class="mt-5 line-clamp-2 text-sm leading-relaxed text-primary-dark/65">{{ $tool['description'] }}</p><span class="mt-auto inline-flex min-h-[44px] items-center gap-1 pt-4 text-sm font-semibold text-primary">Explore <x-waggies.icon name="arrow-forward" size="15" /></span>
                </a>
            @endforeach
        </div><a href="{{ route('tools.index') }}" class="mt-4 inline-flex min-h-[44px] items-center gap-1 text-sm font-semibold text-primary hover:underline">View all tools <x-waggies.icon name="arrow-forward" size="16" /></a>
    </div>
</section>
@endif
