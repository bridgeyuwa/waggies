@extends('layouts.app')

@section('content')
<x-waggies.breadcrumb-strip class="border-b border-primary/5 bg-white" :items="[['label' => 'Services', 'route' => 'services.index']]" />

<x-waggies.cover-hero :hero="$hero" />

<section id="services" class="bg-white py-20">
    <div class="page-container">
        <div class="mb-12 max-w-2xl"><span class="text-eyebrow mb-2 block">What We Offer</span><h2 class="text-h2">Services Built Around<br/>Your Pet’s Wellbeing</h2><p class="mt-3 text-primary-dark/60">Every service is designed with your pet's comfort, health, and happiness at the centre.</p></div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-6">
            @foreach($cards as $i => $card)
                <div class="h-full lg:col-span-2 {{ ['lg:col-start-1', 'lg:col-start-3', 'lg:col-start-5'][$i] ?? '' }}"><x-waggies.service-card :card="$card" /></div>
            @endforeach
        </div>
    </div>
</section>

<x-waggies.service-comparison :services="$comparisonServices" :features="$comparisonFeatures" />

<div id="standards" class="scroll-mt-24">
    <x-waggies.feature-band :band="$band" />
</div>

<x-waggies.proof-band />

<section class="bg-surface py-16"><div class="page-container"><x-waggies.section-heading title="A Few Questions Before You Choose" subtitle="Quick answers to help you choose the right service for your pet with confidence." spacing="mb-8" /><x-waggies.faq-accordion :faqs="$faqs" /><p class="mt-6 text-center text-sm text-primary-dark/60">Have another question? <a href="{{ route('faq') }}" class="font-semibold text-primary hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="font-semibold text-primary hover:underline">contact us</a>.</p></div></section>

@php($servicesCta = ['heading' => 'Request Your Pet\'s', 'headingAccent' => 'Next Visit', 'body' => 'Send a service request or speak with our care team today.', 'primaryLabel' => 'Request a Service', 'primaryRoute' => 'book', 'secondaryLabel' => 'Call Us Now', 'secondaryHref' => 'tel:+2349080811902', 'secondaryIcon' => 'phone'])
<section class="w-full py-20"><div class="page-container"><x-waggies.cta-split :cta="$servicesCta" /></div></section>

@endsection
