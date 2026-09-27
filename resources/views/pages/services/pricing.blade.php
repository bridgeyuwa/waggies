@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Pricing', 'route' => 'services.pricing']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.page-header alignment="center" eyebrow="PRICING GUIDANCE" title="Start with the right request" description="Use the guidance below for boarding and ordinary veterinary requests. Relocation, vaccination, microchipping, cat rates, and special care remain subject to staff quotation." />

    <section id="pricing-calculator" class="scroll-mt-24 bg-surface-purple py-16">
        <div class="mx-auto max-w-5xl px-4 md:px-10 lg:px-12">
            <x-waggies.section-heading eyebrow="PRICING TOOL" title="See indicative guidance" subtitle="Select a service option. This is not checkout and does not replace the staff-entered final quote." />
            @livewire('pricing-calculator', ['initialContext' => $resolved])
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-3xl px-4 text-center">
            <h2 class="mb-3 font-serif text-3xl font-bold text-primary-dark md:text-4xl">Need a custom quote?</h2>
            <p class="mb-8 text-primary-dark/60">Submit a request for relocation, vaccination, microchipping, cat boarding, or any special-care need. Waggies will review the details and confirm the price manually.</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('book', ['service' => 'boarding', 'variant' => 'cats', 'source' => 'pricing']) }}" class="group flex flex-col items-center gap-3 rounded-2xl border-2 border-surface-purple bg-white p-6 text-center transition hover:border-primary hover:shadow-sm"><span class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><x-waggies.icon name="cat" size="24" /></span><span class="text-sm font-bold">Request Cat Boarding</span></a>
                <a href="{{ route('book', ['service' => 'vet-care', 'variant' => 'vaccination-request', 'source' => 'pricing']) }}" class="group flex flex-col items-center gap-3 rounded-2xl border-2 border-surface-purple bg-white p-6 text-center transition hover:border-primary hover:shadow-sm"><span class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><x-waggies.icon name="medical" size="24" /></span><span class="text-sm font-bold">Request Vaccination Review</span></a>
                <a href="{{ route('book', ['service' => 'relocation', 'source' => 'pricing']) }}" class="group flex flex-col items-center gap-3 rounded-2xl border-2 border-surface-purple bg-white p-6 text-center transition hover:border-primary hover:shadow-sm"><span class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><x-waggies.icon name="relocation" size="24" /></span><span class="text-sm font-bold">Request Relocation Quote</span></a>
            </div>
        </div>
    </section>
@endsection
