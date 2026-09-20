@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Partnerships']]" class="bg-white border-b border-primary/5" />
    <x-waggies.page-header :eyebrow="$hero['eyebrow']" :title="$hero['title']" :description="$hero['description']" alignment="center" />

    <section class="section-pad bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-waggies.section-heading :eyebrow="$typesHeading['eyebrow']" :title="$typesHeading['title']" :subtitle="$typesHeading['subtitle']" />
            <div class="mt-10 grid grid-cols-1 gap-0 divide-y divide-primary/12 sm:grid-cols-2 sm:gap-x-10 sm:divide-y-0 lg:grid-cols-3">
                @foreach($types as $type)
                    <div class="flex gap-4 py-6 sm:border-t sm:border-primary/12 lg:border-t-0"><div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-surface-purple text-primary"><x-waggies.icon name="{{ $type['icon'] }}" size="20" /></div><div><h3 class="font-semibold text-primary-dark mb-1">{{ $type['title'] }}</h3><p class="text-sm text-primary-dark/60 leading-relaxed">{{ $type['desc'] }}</p></div></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-pad border-t border-primary/10 bg-surface-purple"><div class="max-w-3xl mx-auto px-4 text-center"><h2 class="font-serif text-3xl font-bold text-primary-dark mb-3">{{ $cta['heading'] }}</h2><p class="text-primary-dark/60 mb-6">{{ $cta['body'] }}</p><a href="{{ route($cta['ctaRoute'], $cta['ctaParams'] ?? []) }}" class="w-cta w-cta--primary">{{ $cta['ctaLabel'] }} <x-waggies.icon name="{{ $cta['ctaIcon'] }}" size="18" /></a></div></section>

@endsection
