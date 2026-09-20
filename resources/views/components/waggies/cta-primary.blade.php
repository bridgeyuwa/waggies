@props(['cta'])

@php($headingAccent = $cta['headingAccent'] ?? 'Care They Deserve')
@php($primaryDestination = !empty($cta['primaryRoute']) ? route($cta['primaryRoute'], $cta['primaryParams'] ?? []) : url($cta['primaryHref']))

<div class="w-full rounded-2xl bg-primary-dark">
    <div class="mx-auto max-w-7xl px-4 py-12 md:px-10 md:py-16 lg:px-12"><div class="mx-auto flex max-w-5xl flex-col items-center gap-10 md:flex-row md:items-center md:justify-between">
        <div class="flex max-w-xl flex-col items-center gap-5 text-center md:items-start md:text-left">
            @if(!empty($cta['eyebrow']))<div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-secondary">{{ $cta['eyebrow'] }}</div>@endif
            <h2 class="font-serif text-3xl font-bold leading-tight text-white md:text-4xl">{{ $cta['heading'] }}@if($headingAccent)<br /><span class="text-secondary italic">{{ $headingAccent }}</span>@endif</h2>
            <p class="max-w-sm text-base leading-relaxed text-white/65 md:max-w-md">{{ $cta['body'] }}</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 md:items-center md:justify-end"><x-waggies.button href="{{ $primaryDestination }}" class="!bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $cta['primaryLabel'] }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>@if(!empty($cta['secondaryLabel']) && (!empty($cta['secondaryRoute']) || !empty($cta['secondaryHref'])))<x-waggies.button href="{{ !empty($cta['secondaryRoute']) ? route($cta['secondaryRoute'], $cta['secondaryParams'] ?? []) : url($cta['secondaryHref']) }}" variant="outline" class="border-white/30 bg-transparent text-white hover:bg-white/10">{{ $cta['secondaryLabel'] }}</x-waggies.button>@endif</div>
    </div></div>
</div>
