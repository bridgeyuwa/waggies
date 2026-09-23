@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Careers']]" class="bg-white border-b border-primary/5" />
    <x-waggies.cover-hero :hero="$hero" />

    <section class="section-pad bg-white">
        <div class="page-container">
            <x-waggies.section-heading :eyebrow="$perksHeading['eyebrow']" :title="$perksHeading['title']" :subtitle="$perksHeading['subtitle']" />
            <div class="mt-10 grid grid-cols-1 gap-0 divide-y divide-primary/12 sm:grid-cols-2 sm:gap-x-10 sm:divide-y-0 lg:grid-cols-4">
                @foreach($perks as $perk)
                    <div class="flex flex-col gap-3 py-6 sm:border-t sm:border-primary/12 lg:border-t-0">
                        <div class="flex size-10 items-center justify-center rounded-lg bg-surface-purple text-primary"><x-waggies.icon name="{{ $perk['icon'] }}" size="20" /></div>
                        <h3 class="font-semibold text-primary-dark">{{ $perk['title'] }}</h3>
                        <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $perk['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="open-roles" class="section-pad border-t border-surface-purple bg-surface">
        <div class="page-container">
            <x-waggies.section-heading :eyebrow="$openRolesHeading['eyebrow']" :title="$openRolesHeading['title']" />
            <div class="mt-10 grid gap-4 lg:grid-cols-2">
                @forelse($openRoles as $role)
                    <div class="flex flex-col justify-between gap-5 rounded-2xl border border-primary/12 bg-white p-6 shadow-sm transition-[border-color,box-shadow,transform] duration-[180ms] hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-soft sm:flex-row sm:items-center">
                        <div><h3 class="font-semibold text-primary-dark">{{ $role->title }}</h3><p class="text-sm text-primary-dark/50">{{ collect([$role->department, $role->employment_type, $role->location])->filter()->implode(' · ') }}</p>@if($role->summary)<p class="mt-2 text-sm leading-relaxed text-primary-dark/60">{{ $role->summary }}</p>@endif</div>
                        <x-waggies.button href="{{ route('contact', ['intent' => 'careers', 'source' => 'job-opening', 'job' => $role->id]) }}" variant="secondary" class="shrink-0">Ask about this role <x-waggies.icon name="arrow-forward" size="17" /></x-waggies.button>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-primary/20 bg-white p-8 text-center lg:col-span-2">
                        <x-waggies.icon name="career" size="32" class="mx-auto text-primary/50" />
                        <h3 class="mt-4 font-serif text-xl font-bold text-primary-dark">No current openings</h3>
                        <p class="mx-auto mt-2 max-w-xl text-sm leading-relaxed text-primary-dark/60">We are not advertising an active vacancy at the moment. Please check back later for genuine opportunities with the Waggies team.</p>
                    </div>
                @endforelse
            </div>
            @if($hasOpenRoles)<p class="mt-8 text-sm text-primary-dark/50">Applications are reviewed by the Waggies team. Contact us if you need more information about an open role.</p>@endif
        </div>
    </section>

@endsection
