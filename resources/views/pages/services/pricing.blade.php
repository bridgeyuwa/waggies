<x-layouts.app title="Pricing" nav-section="services">

    <div class="bg-surface-purple border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-16 md:py-24 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-primary/60 mb-3">Transparent Pricing</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-4">Simple, Honest Pricing</h1>
            <p class="text-primary-dark/60 max-w-xl mx-auto">No hidden fees. No surprises. Just straightforward pricing for world-class pet care.</p>
        </div>
    </div>

    {{-- Boarding --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Boarding" title="Overnight Boarding" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                <x-card.pricing
                    tier-label="Standard"
                    price="₦15,000"
                    unit="/night"
                    :features="[
                        ['label' => 'Shared suite',           'included' => true],
                        ['label' => 'Daily meals',            'included' => true],
                        ['label' => 'Daily exercise',         'included' => true],
                        ['label' => 'Photo update',           'included' => true],
                        ['label' => 'Private suite',          'included' => false],
                        ['label' => 'Priority vet check',     'included' => false],
                    ]"
                    cta-label="Book Now"
                    cta-href="{{ route('contact') }}"
                />
                <x-card.pricing
                    variant="featured"
                    tier-label="Premium"
                    badge-label="Most Popular"
                    price="₦25,000"
                    unit="/night"
                    :features="[
                        ['label' => 'Private suite',          'included' => true],
                        ['label' => 'Daily meals',            'included' => true],
                        ['label' => 'Daily exercise',         'included' => true],
                        ['label' => 'Daily photo update',     'included' => true],
                        ['label' => 'Daily vet check',        'included' => true],
                        ['label' => 'Complimentary bath',     'included' => false],
                    ]"
                    cta-label="Book Now"
                    cta-href="{{ route('contact') }}"
                />
                <x-card.pricing
                    tier-label="Luxury"
                    price="₦40,000"
                    unit="/night"
                    :features="[
                        ['label' => 'Luxury private suite',   'included' => true],
                        ['label' => 'Premium meals',          'included' => true],
                        ['label' => 'Daily exercise',         'included' => true],
                        ['label' => 'Video updates',          'included' => true],
                        ['label' => 'Daily vet check',        'included' => true],
                        ['label' => 'Complimentary bath',     'included' => true],
                    ]"
                    cta-label="Book Now"
                    cta-href="{{ route('contact') }}"
                />
            </div>
        </div>
    </section>

    {{-- Grooming --}}
    <section class="py-20 bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Grooming" title="Grooming Packages" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                <x-card.pricing
                    tier-label="Bath & Brush"
                    price="₦8,000"
                    unit="/session"
                    :features="[
                        ['label' => 'Luxury bath',            'included' => true],
                        ['label' => 'Blow-dry',               'included' => true],
                        ['label' => 'Brush out',              'included' => true],
                        ['label' => 'Nail trim',              'included' => false],
                        ['label' => 'Full haircut',           'included' => false],
                    ]"
                    cta-label="Book Now"
                    cta-href="{{ route('contact') }}"
                />
                <x-card.pricing
                    variant="featured"
                    tier-label="Full Groom"
                    badge-label="Best Value"
                    price="₦18,000"
                    unit="/session"
                    :features="[
                        ['label' => 'Luxury bath',            'included' => true],
                        ['label' => 'Blow-dry',               'included' => true],
                        ['label' => 'Breed-standard cut',     'included' => true],
                        ['label' => 'Nail trim & grind',      'included' => true],
                        ['label' => 'Ear clean',              'included' => true],
                    ]"
                    cta-label="Book Now"
                    cta-href="{{ route('contact') }}"
                />
                <x-card.pricing
                    tier-label="Luxury Spa"
                    price="₦28,000"
                    unit="/session"
                    :features="[
                        ['label' => 'Premium bath & mask',    'included' => true],
                        ['label' => 'Blow-dry',               'included' => true],
                        ['label' => 'Breed-standard cut',     'included' => true],
                        ['label' => 'Nail trim & grind',      'included' => true],
                        ['label' => 'De-shed treatment',      'included' => true],
                    ]"
                    cta-label="Book Now"
                    cta-href="{{ route('contact') }}"
                />
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-primary-dark mb-3">Not sure which package is right for you?</h2>
            <p class="text-primary-dark/60 mb-6">Get in touch and we'll help you choose the best option for your pet's needs and your budget.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                Contact Us <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Pet Care Pricing Abuja — Waggies')
    ->description('Transparent, honest pricing for Waggies pet boarding, grooming, and vet care services in Abuja, Nigeria. No hidden fees.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
