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

<section class="w-full py-20"><div class="page-container"><div class="w-full rounded-2xl bg-primary-dark"><div class="mx-auto flex max-w-5xl flex-col gap-10 px-4 py-12 md:flex-row md:items-center md:justify-between md:px-10 md:py-16 lg:px-12"><div class="flex max-w-xl flex-col items-center gap-5 text-center md:items-start md:text-left"><h2 class="font-serif text-3xl font-bold leading-tight text-white md:text-4xl">Request Your Pet&apos;s<br/><span class="text-secondary italic">Next Visit</span></h2><p class="max-w-sm text-base leading-relaxed text-white/65 md:max-w-md">Send a service request or speak with our care team today.</p></div><div class="flex flex-wrap justify-center gap-3 md:items-center md:justify-end"><x-waggies.button href="{{ route('book') }}" class="bg-secondary! text-primary-dark!">Request a Service <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button><x-waggies.button href="tel:+2349080811902" variant="secondary" class="border-white/30 bg-transparent text-white hover:bg-white/10"><x-waggies.icon name="phone" size="16" />Call Us Now</x-waggies.button></div></div></div></div></section>

@endsection
