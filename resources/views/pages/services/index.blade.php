@extends('layouts.app')

@section('content')
<x-waggies.breadcrumb-strip class="border-b border-primary/5 bg-white" :items="[['label' => 'Services', 'route' => 'services.index']]" />

<x-waggies.cover-hero :hero="$hero" />

<section id="services" class="bg-white py-20">
    <div class="page-container">
        <div class="mb-12 max-w-2xl"><span class="text-eyebrow mb-2 block">What We Offer</span><h2 class="text-h2">Services Built Around<br/>Your Pet’s Wellbeing</h2><p class="mt-3 text-primary-dark/60">Every service is designed with your pet's comfort, health, and happiness at the centre.</p></div>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-6">
            @foreach($cards as $i => $card)
                <div class="lg:col-span-2 {{ ['lg:col-start-1','lg:col-start-3','lg:col-start-5','lg:col-start-2','lg:col-start-4'][$i] }} {{ $i === 4 ? 'sm:col-span-2 sm:mx-auto sm:max-w-sm' : '' }}"><x-waggies.service-card :card="$card" /></div>
            @endforeach
        </div>
    </div>
</section>

<x-waggies.service-comparison :services="$comparisonServices" :features="$comparisonFeatures" />

<section id="standards" class="w-full border-t border-white/10 bg-primary-dark py-20 text-white"><div class="page-container"><div class="mb-16 grid grid-cols-2 gap-6 border-b border-white/10 pb-14 text-center md:grid-cols-4">@foreach($stats as $stat)<div class="flex flex-col items-center gap-2"><div class="flex size-10 items-center justify-center rounded-full bg-white/10 text-secondary"><x-waggies.icon name="{{ $stat['icon'] }}" size="20" /></div><p class="text-sm font-bold text-white">{{ $stat['title'] }}</p><p class="text-xs text-white/60">{{ $stat['subtitle'] }}</p></div>@endforeach</div><div class="grid items-center gap-12 lg:grid-cols-2"><div class="max-w-xl"><span class="text-label mb-2 block text-secondary">The Waggies Standard</span><h2 class="text-h2 leading-tight text-white">Why Our Care System<br/><span class="text-secondary italic">Works So Well</span></h2><p class="mt-4 text-sm leading-relaxed text-white/70 md:text-base">Every pet is handled through structured care protocols designed to ensure safety, comfort, and emotional wellbeing.</p></div><div class="flex flex-col gap-4">@foreach($standards as $standard)<div class="rounded-2xl border border-white/10 bg-white/5 p-5"><h3 class="mb-1 text-base font-bold text-white">{{ $standard['title'] }}</h3><p class="text-sm text-white/65">{{ $standard['desc'] }}</p></div>@endforeach</div></div></div></section>

<x-waggies.proof-band />

<section class="bg-surface py-20"><div class="page-container"><x-waggies.section-heading title="Before You Book, Here&apos;s What You Should Know" subtitle="Quick answers to help you choose the right service for your pet with confidence." spacing="mb-14" /><x-waggies.faq-accordion :faqs="$faqs" /></div></section>

@php($servicesCta = ['heading' => 'Request Your Pet\'s', 'headingAccent' => 'Next Visit', 'body' => 'Send a service request or speak with our care team today.', 'primaryLabel' => 'Request a Service', 'primaryRoute' => 'book', 'secondaryLabel' => 'Call Us Now', 'secondaryHref' => 'tel:+2349080811902', 'secondaryIcon' => 'phone'])
<section class="w-full py-20"><div class="page-container"><x-waggies.cta-primary :cta="$servicesCta" /></div></section>

@endsection
