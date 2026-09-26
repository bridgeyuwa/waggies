@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Pricing', 'route' => 'services.pricing']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.page-header alignment="center" eyebrow="Transparent Pricing" title="Simple, Honest Pricing" description="No hidden fees. Use our estimator for a tailored quote, then book or speak with our team." />

    <section id="pricing-calculator" class="scroll-mt-24 bg-surface-purple py-16">
        <div class="mx-auto max-w-5xl px-4 md:px-10 lg:px-12">
            <x-waggies.section-heading eyebrow="Pricing Tool" title="Get Your Estimate" subtitle="Select your service and package — we will show a clear price, range, or quote path." />
            @livewire('pricing-calculator', ['initialContext' => $resolved])
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-3xl px-4 text-center">
            <h2 class="mb-3 font-serif text-3xl font-bold text-primary-dark md:text-4xl">Training, vet care, or relocation?</h2>
            <p class="mb-8 text-primary-dark/60">Jump straight to the service you need. The calculator keeps your selection in the URL so you can share or revisit it without losing context.</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('services.pricing', ['service' => 'training']) }}" class="group flex flex-col items-center gap-3 rounded-2xl border-2 border-surface-purple bg-white p-6 transition hover:border-primary hover:shadow-sm"><span class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><x-waggies.icon name="dog" size="24" /></span><span class="text-sm font-bold">Estimate Training Cost</span></a>
                <a href="{{ route('book', ['service' => 'vet-care', 'source' => 'pricing']) }}" class="group flex flex-col items-center gap-3 rounded-2xl border-2 border-surface-purple bg-white p-6 transition hover:border-primary hover:shadow-sm"><span class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><x-waggies.icon name="favorite" size="24" /></span><span class="text-sm font-bold">Request Vet Care Quote</span></a>
                <a href="{{ route('services.pricing', ['service' => 'relocation']) }}" class="group flex flex-col items-center gap-3 rounded-2xl border-2 border-surface-purple bg-white p-6 transition hover:border-primary hover:shadow-sm"><span class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><x-waggies.icon name="relocation" size="24" /></span><span class="text-sm font-bold">Estimate Relocation Cost</span></a>
            </div>
        </div>
    </section>
@endsection
