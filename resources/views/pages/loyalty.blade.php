@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Loyalty Programme']]" class="border-b border-primary/5 bg-white" />

    <section class="w-full bg-surface-purple">
        <div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12">
            <div class="flex flex-col items-center gap-12 py-16 md:py-24 lg:flex-row lg:gap-16">
                <div class="flex max-w-md flex-1 flex-col gap-6">
                    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-surface-purple bg-white px-4 py-1.5 shadow-sm">
                        <x-waggies.icon name="star" variant="filled" size="18" class="text-gold" />
                        <span class="text-xs font-bold text-primary-dark">{{ $page['hero']['rating'] }} · {{ $page['hero']['reviewCount'] }} Reviews</span>
                        <span class="h-2 w-2 rounded-full bg-primary" aria-hidden="true"></span>
                    </div>

                    <h1 class="text-balance font-serif text-4xl font-bold leading-tight text-primary-dark md:text-5xl">{!! $page['hero']['title'] !!}</h1>
                    <p class="text-base leading-relaxed text-primary-dark/60">{{ $page['hero']['subtitle'] }}</p>

                    <div class="ml-1 flex flex-col items-start gap-3">
                        <a href="{{ route($page['hero']['primaryCta']['route']) }}" class="w-cta w-cta--primary">{{ $page['hero']['primaryCta']['label'] }}</a>
                        <a href="{{ route($page['hero']['secondaryCta']['route']) }}" class="w-cta w-cta--secondary">{{ $page['hero']['secondaryCta']['label'] }}</a>
                    </div>
                </div>

                <div class="w-full max-w-lg flex-1 lg:max-w-none"></div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12">
            <x-waggies.section-heading :eyebrow="$page['howItWorksHeading']['eyebrow']" :title="$page['howItWorksHeading']['title']" :subtitle="$page['howItWorksHeading']['subtitle']" />

            <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach($page['howItWorks'] as $item)
                    <div class="flex flex-col gap-4 rounded-2xl bg-surface-purple p-8">
                        <div class="flex items-center gap-4">
                            <span class="font-serif text-4xl font-bold text-primary/20">{{ $item['step'] }}</span>
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10">
                                <x-waggies.icon name="{{ $item['icon'] }}" variant="outlined" size="16" class="text-primary" />
                            </div>
                        </div>
                        <h3 class="font-serif text-xl font-bold text-primary-dark">{{ $item['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-primary-dark/60">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-surface-purple bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12">
            <x-waggies.section-heading :eyebrow="$page['tiersHeading']['eyebrow']" :title="$page['tiersHeading']['title']" />

            <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach($page['tiers'] as $tier)
                    <div class="rounded-2xl border border-surface-purple bg-white p-8 shadow-sm transition-shadow hover:shadow-soft">
                        <x-waggies.icon name="{{ $tier['icon'] }}" variant="filled" size="36" class="text-4xl {{ $tier['color'] }} mb-3 !block" />
                        <h3 class="mb-1 font-serif text-2xl font-bold text-primary-dark">{{ $tier['tier'] }}</h3>
                        <p class="mb-4 text-xs uppercase tracking-widest text-primary-dark/60">{{ $tier['threshold'] }}</p>
                        <ul class="flex flex-col gap-2">
                            @foreach($tier['perks'] as $perk)
                                <li class="flex items-center gap-2 text-sm text-primary-dark">
                                    <x-waggies.icon name="check-circle" variant="filled" size="16" class="text-success" />
                                    {{ $perk }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-surface-purple py-16">
        <div class="mx-auto max-w-3xl px-4 text-center">
            <h2 class="mb-3 font-serif text-3xl font-bold text-primary-dark">{{ $page['startEarningCta']['heading'] }}</h2>
            <p class="mb-6 text-primary-dark/60">{{ $page['startEarningCta']['body'] }}</p>
            <a href="{{ route($page['startEarningCta']['ctaRoute']) }}" class="w-cta w-cta--primary">
                {{ $page['startEarningCta']['ctaLabel'] }}
                <x-waggies.icon name="{{ $page['startEarningCta']['ctaIcon'] }}" size="16" />
            </a>
        </div>
    </section>

@endsection
