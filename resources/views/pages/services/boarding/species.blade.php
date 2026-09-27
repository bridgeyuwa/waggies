@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Boarding', 'route' => 'services.boarding'], ['label' => ucfirst($species).' boarding', 'route' => 'services.boarding.species', 'params' => ['species' => $species]]]" class="border-b border-primary/5 bg-white" />

    <section class="bg-primary-dark py-16 text-white md:py-24">
        <div class="page-container grid gap-10 lg:grid-cols-[minmax(0,1fr)_24rem] lg:items-center">
            <div>
                <p class="text-eyebrow text-secondary">{{ strtoupper($species) }} BOARDING</p>
                <h1 class="mt-3 font-serif text-4xl font-bold leading-tight text-white md:text-6xl">A considered stay for your {{ $species === 'dogs' ? 'dog' : 'cat' }}.</h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-white/75">Boarding is requested by the night and confirmed manually. Every boarded pet receives an individual enclosure, with care details agreed before the stay.</p>
                <div class="mt-7 flex flex-col gap-3 sm:flex-row"><x-waggies.button href="{{ route('book', ['service' => 'boarding', 'variant' => $variant]) }}" class="bg-secondary! text-primary-dark!">Submit Booking Request <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button><x-waggies.button href="{{ route('boarding-policy') }}" variant="secondary" class="border-white/20 bg-white/5 text-white!">Read boarding policy</x-waggies.button></div>
            </div>
            <div class="aspect-4/3 overflow-hidden rounded-3xl border border-white/10"><img src="{{ $species === 'dogs' ? '/media/services/boarding/hero-dogs.jpg' : '/media/services/boarding/hero-cats.jpg' }}" alt="{{ ucfirst($species) }} during a boarding stay" class="h-full w-full object-cover" /></div>
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="page-container grid gap-12 lg:grid-cols-2">
            <div>
                <p class="text-eyebrow">NIGHTLY REQUEST PATH</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">{{ ucfirst($species) }} boarding pricing</h2>
                @if($species === 'dogs')
                    <p class="mt-4 text-sm leading-relaxed text-primary-dark/70">Select the closest size in the request UI. The guidance is operational, not a medical or legal classification, and staff can correct it during review.</p>
                    <div class="mt-6 grid gap-3">
                        @foreach($sizeOptions as $key => $option)
                            <div class="rounded-xl border border-primary/10 bg-surface p-4"><div class="flex items-start justify-between gap-4"><h3 class="font-semibold text-primary-dark">{{ $option['label'] }}</h3><span class="text-right text-sm font-semibold text-primary">{{ isset($sizeRates[$key]['amount']) ? '₦'.number_format($sizeRates[$key]['amount']).(isset($sizeRates[$key]['max_amount']) && $sizeRates[$key]['max_amount'] !== $sizeRates[$key]['amount'] ? '–₦'.number_format($sizeRates[$key]['max_amount']) : '') : 'Staff review' }}</span></div><p class="mt-1 text-xs leading-relaxed text-primary-dark/60">{{ $option['guidance'] }}</p></div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 rounded-2xl border border-primary/10 bg-surface p-6"><p class="font-serif text-2xl font-bold text-primary-dark">Nightly rate confirmed by staff</p><p class="mt-3 text-sm leading-relaxed text-primary-dark/65">The current catalogue does not contain a safely authoritative cat rate. Submit the request and Waggies will confirm the applicable nightly price before confirmation.</p></div>
                @endif
            </div>
            <div>
                <p class="text-eyebrow">CORE INCLUSIONS</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">What the stay includes</h2>
                <ul class="mt-6 grid gap-3">
                    @foreach(config('waggies_pricing.services.boarding.inclusions', []) as $inclusion)
                        <li class="flex items-start gap-3 rounded-xl border border-primary/10 bg-surface p-4 text-sm leading-relaxed text-primary-dark/75"><x-waggies.icon name="check-circle" variant="filled" size="18" class="mt-0.5 shrink-0 text-primary" />{{ $inclusion }}</li>
                    @endforeach
                </ul>
                <p class="mt-5 text-sm leading-relaxed text-primary-dark/65">Medication, special handling, intensive supervision, late pickup, and other exceptions require staff review and may change the final quote.</p>
            </div>
        </div>
    </section>

    <section class="border-t border-primary/5 bg-surface py-16"><div class="page-container"><x-waggies.section-heading eyebrow="OTHER BOARDING OPTION" title="Choose the right request path" spacing="mb-8" /><div class="grid gap-4 md:grid-cols-2">@foreach($siblings as $key => $sibling)@if($key !== $species)<a href="{{ route('services.boarding.species', ['species' => $key]) }}" class="flex items-center gap-4 rounded-2xl border border-primary/10 bg-white p-4 transition hover:border-primary/40 hover:shadow-soft"><img src="{{ $sibling['image'] }}" alt="" class="size-20 rounded-xl object-cover" aria-hidden="true" /><span><span class="block font-serif text-lg font-bold text-primary-dark">{{ $sibling['label'] }}</span><span class="mt-1 block text-sm text-primary-dark/60">{{ $sibling['description'] }}</span></span><x-waggies.icon name="arrow-forward" size="18" class="ml-auto shrink-0 text-primary" /></a>@endif @endforeach</div></div></section>

    @if($faqs !== [])
        <section class="bg-white py-16"><div class="page-container"><x-waggies.section-heading title="{{ ucfirst($species) }} boarding questions" spacing="mb-8" /><x-waggies.faq-accordion :faqs="$faqs" /></div></section>
    @endif
@endsection
