@props(['band'])

@php($ctaDestination = static fn (array $cta): string => isset($cta['route']) ? route($cta['route'], $cta['params'] ?? []) : url($cta['href']))

<section class="w-full bg-primary-dark py-24 text-white">
    <div class="page-container"><div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
        <div class="flex max-w-xl flex-col gap-6">
            @if(!empty($band['eyebrow']))<p class="text-xs font-bold uppercase tracking-widest text-secondary">{{ $band['eyebrow'] }}</p>@endif
            <h2 class="font-serif text-3xl font-bold leading-tight text-white md:text-4xl">{!! $band['title'] !!}</h2>
            @if(!empty($band['subtitle']))<p class="text-base leading-relaxed text-white/70">{{ $band['subtitle'] }}</p>@endif
            @if(!empty($band['cta']))<div class="pt-2"><x-waggies.button href="{{ $ctaDestination($band['cta']) }}" class="!bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $band['cta']['label'] }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button></div>@endif
        </div>
        <div class="flex flex-col gap-5">
            @foreach($band['features'] ?? [] as $feature)<div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.03] p-5 transition hover:bg-white/[0.06]"><div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-white/10 text-secondary"><x-waggies.icon name="{{ $feature['icon'] }}" size="18" /></div><div><h3 class="font-serif text-lg font-bold text-white">{{ $feature['title'] }}</h3><p class="mt-1 text-sm leading-relaxed text-white/60">{{ $feature['description'] }}</p></div></div>@endforeach
        </div>
    </div></div>
</section>
