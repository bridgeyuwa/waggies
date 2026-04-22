<x-layouts.app title="Export a Pet from Nigeria" nav-section="relocation">

    <x-hero.image
        image-src="/images/relocation-export.jpg"
        eyebrow="Pet Export · Nigeria"
        title='Moving Abroad<br/>with Your Pet?'
        subtitle="We manage health certificates, export permits, IATA-approved crates, and airline coordination for a smooth international departure."
        :primary-cta="['label' => 'Get an Export Quote', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Checklist', 'href' => route('relocation.checklist')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Export Process"
                title="Everything Covered<br/>Before Departure"
                subtitle="Exporting a pet from Nigeria requires careful planning. We handle it all so your pet travels safely."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'description',    'title' => 'Export Health Certificate', 'desc' => 'Our vet issues an official government-endorsed health certificate valid for international travel.'],
                    ['icon' => 'vaccines',        'title' => 'Vaccination Compliance',    'desc' => 'Review and update of vaccinations to meet the destination country\'s entry requirements.'],
                    ['icon' => 'inventory_2',     'title' => 'IATA-Approved Crate',       'desc' => 'Supply and sizing of an IATA-compliant travel crate appropriate for your pet and the airline.'],
                    ['icon' => 'flight_takeoff',  'title' => 'Airline Booking',           'desc' => 'We liaise with the airline to book your pet as cabin or cargo, and manage all airline paperwork.'],
                    ['icon' => 'qr_code_scanner','title' => 'Microchip & Passport',      'desc' => 'Verification of microchip and preparation of a pet passport where required by the destination.'],
                    ['icon' => 'support_agent',   'title' => 'Destination Guidance',      'desc' => 'Guidance on entry requirements at your destination country — so there are no surprises on arrival.'],
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
                <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Planning a move abroad?</h2>
                <p class="text-primary-dark/60">Contact us at least 6–8 weeks before your travel date to allow sufficient processing time.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('relocation.checklist') }}" class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition">View Checklist</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition shadow-glow hover:-translate-y-1">Get a Quote <span class="material-symbols-outlined text-base">arrow_forward</span></a>
            </div>
        </div>
    </section>


@if($faqs->isNotEmpty())
    <section class="py-16 bg-surface-purple/40">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions" class="mb-8" />
            <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => e($f->answer)])->all()" />
            <p class="mt-6 text-sm text-primary-dark/50">More questions? <a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.</p>
        </div>
    </section>
@endif

@push('head')
@php
echo \Spatie\SchemaOrg\Schema::service()
    ->name('Export Pets from Nigeria — Waggies Abuja')
    ->description('Full-service pet export from Nigeria — health certificates, IATA crates, export permits, and airline coordination.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
