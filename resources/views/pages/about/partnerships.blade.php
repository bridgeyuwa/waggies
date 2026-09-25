@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Partnerships']]" class="bg-white border-b border-primary/5" />
    <x-waggies.page-header :eyebrow="$hero['eyebrow']" :title="$hero['title']" :description="$hero['description']" alignment="center" />

    <section class="section-pad bg-white">
        <div class="page-container">
            <x-waggies.section-heading :eyebrow="$typesHeading['eyebrow']" :title="$typesHeading['title']" :subtitle="$typesHeading['subtitle']" />
            <div class="mt-10 grid grid-cols-1 gap-0 divide-y divide-primary/12 sm:grid-cols-2 sm:gap-x-10 sm:divide-y-0 lg:grid-cols-3">
                @foreach($types as $type)
                    <div class="flex gap-4 py-6 sm:border-t sm:border-primary/12 lg:border-t-0"><div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-surface-purple text-primary"><x-waggies.icon name="{{ $type['icon'] }}" size="20" /></div><div><h3 class="font-semibold text-primary-dark mb-1">{{ $type['title'] }}</h3><p class="text-sm text-primary-dark/60 leading-relaxed">{{ $type['desc'] }}</p></div></div>
                @endforeach
            </div>
        </div>
    </section>

    @php($partnershipCta = ['heading' => $cta['heading'], 'body' => $cta['body'], 'primaryLabel' => $cta['ctaLabel'], 'primaryRoute' => $cta['ctaRoute'], 'primaryParams' => $cta['ctaParams'] ?? [], 'primaryIcon' => $cta['ctaIcon'] ?? 'arrow-forward'])
    <section class="section-pad border-t border-primary/10 bg-surface-purple"><div class="mx-auto max-w-3xl px-4"><x-waggies.cta-centered :cta="$partnershipCta" tone="light" /></div></section>

@endsection
