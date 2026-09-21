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
                @foreach($openRoles as $role)
                    <div class="flex flex-col justify-between gap-5 rounded-2xl border border-primary/12 bg-white p-6 shadow-sm transition-[border-color,box-shadow,transform] duration-[180ms] hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-soft sm:flex-row sm:items-center">
                        <div><h3 class="font-semibold text-primary-dark">{{ $role['role'] }}</h3><p class="text-sm text-primary-dark/50">{{ $role['dept'] }} · {{ $role['type'] }}</p></div>
                        <x-waggies.button href="{{ route($role['applyRoute'], $role['applyParams'] ?? []) }}" variant="secondary" class="shrink-0">{{ $role['applyLabel'] }} <x-waggies.icon name="arrow-forward" size="17" /></x-waggies.button>
                    </div>
                @endforeach
            </div>
            <p class="mt-8 text-sm text-primary-dark/50">{{ $speculativeNote }}</p>
        </div>
    </section>

@endsection
