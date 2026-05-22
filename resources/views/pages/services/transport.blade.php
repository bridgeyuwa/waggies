<x-layouts.app title="Pet Transport" nav-section="services">

    <x-hero.image
        image-src="/images/transport.jpg"
        eyebrow="Pet Transport · Abuja"
        title='Safe, Comfortable<br/>Door-to-Door Transport'
        subtitle="Air-conditioned, purpose-fitted vehicles for stress-free pet pickup and drop-off anywhere across Abuja."
        :primary-cta="['label' => 'Book a Transfer', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Transport Services"
                title="We Go Where<br/>Your Pet Needs to Go"
                subtitle="Whether it's a trip to Waggies for boarding or grooming, or a vet visit, we'll collect and return your pet safely."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'local_shipping',  'title' => 'Pickup & Drop-Off',        'desc' => 'Door-to-door collection and return for boarding, grooming, or vet appointments at Waggies.'],
                    ['icon' => 'air',             'title' => 'Air-Conditioned Vehicles',  'desc' => 'Climate-controlled vehicles to keep your pet comfortable whatever the Abuja weather.'],
                    ['icon' => 'lock',            'title' => 'Secure Crating',            'desc' => 'Approved travel crates and harnesses to keep pets safe and secure during transit.'],
                    ['icon' => 'person',          'title' => 'Experienced Handlers',      'desc' => 'Drivers trained in animal handling and pet first aid.'],
                    ['icon' => 'map',             'title' => 'All Areas of Abuja',        'desc' => 'We cover all major districts — Maitama, Wuse, Asokoro, Garki, Gwarinpa, and beyond.'],
                    ['icon' => 'schedule',        'title' => 'Punctual & Reliable',       'desc' => 'Time slots booked in advance so you can plan around your schedule.'],
                ] as $item)
                <div class="bg-surface-purple rounded-2xl p-6 flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary icon-filled">{{ $item['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-dark mb-1">{{ $item['title'] }}</h3>
                        <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Book a transport slot</h2>
                <p class="text-primary-dark/60">Contact us with your pickup address, destination, and preferred time.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('services.pricing') }}" class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition">View Pricing</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition shadow-glow hover:-translate-y-1">Book Now <span class="material-symbols-outlined text-base">arrow_forward</span></a>
            </div>
        </div>
    </section>


@if($faqs->isNotEmpty())
    <section class="py-16 bg-surface-purple/40">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions" class="mb-8" />
            <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => $f->answer])->all()" />
            <p class="mt-6 text-sm text-primary-dark/50">More questions? <a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.</p>
        </div>
    </section>
@endif

@push('head')
@php
echo \Spatie\SchemaOrg\Schema::service()
    ->name('Pet Transport Abuja — Waggies')
    ->description('Air-conditioned, door-to-door pet transport and pickup across all areas of Abuja by trained, experienced handlers.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
