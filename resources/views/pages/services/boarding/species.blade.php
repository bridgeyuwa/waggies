@extends('layouts.app')

@section('content')
@php
    $serviceKey = 'boarding-'.$species;
    $dailyBadges = match ($species) {
        'dogs' => [['badge' => 'Settling', 'bg' => 'bg-purple-50 text-primary-dark border-purple-200'], ['badge' => 'Nutrition', 'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200'], ['badge' => 'Cleaning', 'bg' => 'bg-amber-50 text-amber-800 border-amber-200'], ['badge' => 'Welfare Check', 'bg' => 'bg-blue-50 text-blue-800 border-blue-200'], ['badge' => 'Pickup Prep', 'bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200']],
        'cats' => [['badge' => 'Settling', 'bg' => 'bg-purple-50 text-primary-dark border-purple-200'], ['badge' => 'Nutrition', 'bg' => 'bg-amber-50 text-amber-800 border-amber-200'], ['badge' => 'Cleaning', 'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200'], ['badge' => 'Welfare Check', 'bg' => 'bg-blue-50 text-blue-800 border-blue-200'], ['badge' => 'Evening Care', 'bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200']],
    };
    $dailySurface = $species === 'cats' ? 'bg-white' : 'bg-surface-purple/60';
    $safetyDark = $species === 'dogs';
    $shareTitle = ['dogs' => 'Dog Boarding - Waggies', 'cats' => 'Cat Boarding - Waggies'][$species];
    $shareDescription = $page['description'];
    $dailyHeadingId = $species === 'dogs' ? 'dog-daily-heading' : 'cat-daily-heading';
    $statGrid = 'md:grid-cols-3';
    $care = $species === 'cats' ? $page['feline'] : $page['canine'];
    $careHeadingId = $species === 'cats' ? 'cat-care-heading' : 'dog-care-heading';
    $safetyHref = $species === 'dogs' ? route('boarding-policy') : route('contact');
    $siblings = collect([
        ['key' => 'dogs', 'title' => 'Dog Boarding', 'desc' => 'Individual enclosures, routine care, and manual review.', 'icon' => 'pets'],
        ['key' => 'cats', 'title' => 'Cat Boarding', 'desc' => 'Calm individual enclosures with routine care and manual review.', 'icon' => 'pets'],
    ])->map(fn (array $sibling): array => $sibling + ['image' => "/media/services/boarding/{$species}/related-{$sibling['key']}.jpg"])->all();
@endphp

<x-waggies.breadcrumb-strip class="border-b border-primary/5 bg-white" :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Boarding', 'route' => 'services.boarding'], ['label' => $page['hero']['eyebrow'], 'route' => 'services.boarding.species', 'params' => ['species' => $species]]]" />
<x-waggies.cover-hero :hero="$page['hero']" size="compact" />
<div class="relative z-30 -mt-8 -mb-8 mx-4 max-w-[1200px] rounded-2xl border border-surface-purple bg-white shadow-soft sm:mx-10 lg:mx-auto"><div class="grid grid-cols-1 gap-8 px-6 py-8 sm:grid-cols-3 {{ $statGrid }} md:divide-x md:divide-primary/10 md:px-10">@foreach($page['stats'] as $stat)<div class="flex flex-col items-center text-center"><span class="mb-1 font-serif text-4xl font-bold text-primary">{{ $stat['value'] }}</span><span class="text-xs font-semibold uppercase tracking-widest text-primary-dark/50">{{ $stat['label'] }}</span></div>@endforeach</div></div>

<section aria-labelledby="{{ $careHeadingId }}" class="border-t border-primary/5 bg-surface-purple/40 py-16 md:py-20">
    <div class="page-container">
        <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-7 lg:col-start-6 lg:row-start-1">
                <div class="relative aspect-4/3 w-full overflow-hidden rounded-3xl border border-primary/10 bg-surface-raised shadow-soft">
                    <img src="{{ $care['image'] }}" alt="{{ $care['imageAlt'] }}" class="h-full w-full object-cover" loading="lazy" />
                </div>
            </div>
            <div class="lg:col-span-5 lg:col-start-1 lg:row-start-1">
                <span class="text-eyebrow mb-2 block">{{ $care['eyebrow'] }}</span>
                <h2 id="{{ $careHeadingId }}" class="mb-4 font-serif text-3xl font-bold leading-tight text-primary-dark md:text-4xl">{{ $care['title'] }}</h2>
                <p class="max-w-prose text-base leading-relaxed text-primary-dark/70">{{ $care['body'] }}</p>

                <div class="mt-8 space-y-3">
                    @foreach($care['bullets'] as $index => $bullet)
                        <article class="group relative overflow-hidden rounded-2xl border border-primary/10 bg-white/90 p-4 shadow-subtle transition-shadow hover:shadow-soft">
                            <div class="flex items-start gap-3.5">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-dark text-secondary shadow-subtle">
                                    <x-waggies.icon name="{{ $bullet['icon'] }}" size="20" variant="filled" />
                                </div>
                                <div class="min-w-0">
                                    <div class="mb-1 flex items-baseline gap-2">
                                        <span class="text-xs font-bold uppercase tracking-widest text-primary/55" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                        <h3 class="font-serif text-base font-bold text-primary-dark">{{ $bullet['bold'] }}</h3>
                                    </div>
                                    <p class="text-sm leading-relaxed text-primary-dark/70">{{ $bullet['text'] }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section aria-labelledby="{{ $dailyHeadingId }}" class="{{ $dailySurface }} w-full border-t border-primary/5 py-20">
    <div class="page-container">
        <div class="flex flex-col items-center gap-10 lg:flex-row lg:gap-12">
            <div class="w-full flex-1 space-y-8">
                <div>
                    <span class="text-eyebrow mb-2 block">{{ $species === 'cats' ? 'LOW-STRESS DAILY ROUTINE' : 'STRUCTURED CARE SCHEDULE' }}</span>
                    <h2 id="{{ $dailyHeadingId }}" class="mb-3 font-serif text-3xl font-bold leading-tight text-primary-dark md:text-4xl">{{ $page['daily']['heading'] }}</h2>
                    <p class="max-w-prose text-base leading-relaxed text-primary-dark/70">{{ $page['daily']['subtitle'] }}</p>
                </div>

                <ol class="{{ $species === 'dogs' ? 'space-y-3 rounded-3xl border border-primary/10 bg-white/50 p-3 md:p-4' : 'space-y-3' }}" aria-label="Daily schedule">
                    @foreach($page['daily']['steps'] as $i => $step)
                        @php($tag = $dailyBadges[$i % count($dailyBadges)])
                        <li class="group flex flex-col justify-between gap-4 rounded-2xl border border-primary/10 {{ $species === 'cats' ? 'bg-white shadow-subtle transition-shadow hover:shadow-soft p-5' : 'bg-white/90 p-4 shadow-subtle transition-shadow hover:shadow-soft md:p-5' }} sm:flex-row sm:items-center">
                            <div class="flex items-start gap-4">
                        <div class="mt-0.5 flex size-10 shrink-0 items-center justify-center {{ $species === 'cats' ? 'rounded-xl bg-primary-dark text-secondary' : 'rounded-full bg-primary/10 text-primary' }}">
                                    <x-waggies.icon name="{{ $step['icon'] }}" size="18" />
                                </div>
                                <div class="min-w-0">
                                    <div class="mb-1 flex items-baseline gap-2">
                                        <span class="text-xs font-bold uppercase tracking-widest text-primary/55" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                        <h3 class="font-serif text-base font-bold text-primary-dark">{{ $step['title'] }}</h3>
                                    </div>
                                    <p class="text-sm leading-relaxed text-primary-dark/65">{{ $step['description'] }}</p>
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 self-start items-center rounded-full border {{ $species === 'dogs' ? 'border-primary/10 bg-surface-purple text-primary-dark/70' : $tag['bg'] }} px-3 py-1 text-xs font-semibold sm:self-center">{{ $tag['badge'] }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="w-full flex-1" aria-hidden="true">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4 pt-6">
                        <img src="{{ $page['daily']['images'][0] }}" alt="" class="h-48 w-full rounded-2xl border border-primary/10 object-cover shadow-soft" loading="lazy" />
                        <img src="{{ $page['daily']['images'][1] }}" alt="" class="h-64 w-full rounded-2xl border border-primary/10 object-cover shadow-soft" loading="lazy" />
                    </div>
                    <div class="space-y-4">
                        <img src="{{ $page['daily']['images'][2] }}" alt="" class="h-64 w-full rounded-2xl border border-primary/10 object-cover shadow-soft" loading="lazy" />
                        <img src="{{ $page['daily']['images'][3] }}" alt="" class="h-48 w-full rounded-2xl border border-primary/10 object-cover shadow-soft" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('pages.services.boarding.pricing-section')


<section class="w-full border-t border-primary/5 py-20 {{ $safetyDark ? 'bg-white' : 'bg-surface' }}"><div class="page-container"><div class="relative overflow-hidden rounded-3xl {{ $safetyDark ? 'bg-primary-dark text-white shadow-md' : 'border border-primary/10 bg-white text-primary-dark shadow-soft' }} p-8 md:p-12">@if($safetyDark)<div class="pointer-events-none absolute -bottom-12 -right-12 text-white opacity-10"><x-waggies.icon name="verified" size="260" variant="filled" /></div>@endif<div class="{{ $safetyDark ? 'relative z-10' : '' }} max-w-3xl">@if($safetyDark)<div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-secondary"><x-waggies.icon name="verified" size="16" variant="filled" />{{ $page['safety']['title'] }}</div>@else<span class="text-eyebrow mb-2 block">{{ $species === 'cats' ? 'CAT SAFETY & COMFORT' : 'DOG SAFETY & HANDLING' }}</span>@endif<h2 class="mb-4 font-serif text-2xl font-bold leading-tight {{ $safetyDark ? 'text-white' : 'text-primary-dark' }} md:text-3xl">{{ $species === 'dogs' ? $page['safety']['heading'] : $page['safety']['title'] }}</h2><p class="mb-8 text-sm leading-relaxed {{ $safetyDark ? 'text-white/80' : 'text-primary-dark/70' }} md:text-base">{{ $page['safety']['intro'] }}</p><div class="mb-8 grid gap-4 sm:grid-cols-2">@foreach($page['safety']['bullets'] as $bullet)<div class="flex items-start gap-3 rounded-xl border p-4 {{ $safetyDark ? 'border-white/10 bg-white/5 text-white/90' : 'border-primary/10 bg-surface-purple/40 text-primary-dark/85' }}"><x-waggies.icon name="check-circle" size="20" variant="filled" class="shrink-0 {{ $safetyDark ? 'text-secondary' : 'text-primary' }}" /><span class="text-sm font-medium">{{ $bullet }}</span></div>@endforeach</div><x-waggies.button href="{{ $safetyHref }}" :tone="$safetyDark ? 'dark' : 'light'">{{ $page['safety']['linkLabel'] }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button></div></div></div></section>

<section class="border-t border-primary/5 bg-surface-purple/50 py-16"><div class="page-container"><x-waggies.cta-split :cta="$page['cta']" /></div></section>

@php($sibling = collect($siblings)->first(fn (array $option): bool => $option['key'] !== $species))
<section class="border-t border-surface-purple bg-white py-12" aria-label="Other boarding options"><div class="page-container"><div class="flex flex-col gap-5 rounded-2xl border border-surface-purple bg-surface-purple/30 p-6 sm:flex-row sm:items-center sm:justify-between sm:gap-8"><div><span class="text-eyebrow mb-2 block">MORE BOARDING</span><h2 class="font-serif text-2xl font-bold text-primary-dark">Looking for a different boarding stay?</h2><p class="mt-2 max-w-2xl text-sm leading-relaxed text-primary-dark/60">Every species gets a dedicated environment. Explore the other boarding option when your pet’s needs call for it.</p></div><a href="{{ route('services.boarding.species', ['species' => $sibling['key']]) }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-full border border-primary/30 bg-white px-5 py-3 text-sm font-bold text-primary-dark transition hover:border-primary/50 hover:bg-surface-raised hover:text-primary focus:outline-none focus:ring-2 focus:ring-focus focus:ring-offset-2">Explore {{ $sibling['title'] }} <x-waggies.icon name="arrow-forward" size="16" /></a></div></div></section>

<x-waggies.proof-band variant="boarding-{{ $species }}" :hide-current-service-link="$species === 'dogs'" />
<section class="bg-surface border-t border-primary/5 py-16"><div class="page-container"><x-waggies.section-heading eyebrow="FAQs" title="Frequently Asked Questions" spacing="mb-8"/><x-waggies.faq-accordion :faqs="$faqs" /><p class="mt-6 text-center text-sm text-primary-dark/60">More questions? <a href="{{ route('faq') }}" class="font-bold text-primary hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="font-bold text-primary hover:underline">contact us</a>.</p></div></section>
<div class="page-container py-4"><x-waggies.share-row :title="$shareTitle" :description="$shareDescription" /></div>

@endsection
