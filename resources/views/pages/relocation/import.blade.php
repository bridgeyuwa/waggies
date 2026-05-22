<x-layouts.app title="Import a Pet to Nigeria" nav-section="relocation">

    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-hero.image
        image-src="/images/relocation-import.jpg"
        eyebrow="Pet Import · Nigeria"
        title='Bringing Your Pet<br/>to Nigeria?'
        subtitle="We manage every import requirement — from advance permits and health certificates to airport collection and quarantine coordination."
        :primary-cta="['label' => 'Get Estimate', 'href' => \App\Support\PricingQuote::estimateUrl('relocation', null, 'import'), 'icon' => 'calculate']"
        :secondary-cta="['label' => 'View Checklist', 'href' => route('relocation.checklist')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Import Process"
                title="What We Handle<br/>for You"
                subtitle="Importing a pet to Nigeria involves strict documentation and coordination. We take care of every step."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'description',    'title' => 'Import Permit',              'desc' => 'We obtain the required import permits from Nigerian veterinary and customs authorities on your behalf.'],
                    ['icon' => 'vaccines',        'title' => 'Health & Vaccination Checks','desc' => 'Review of your pet\'s vaccination records to confirm compliance with Nigerian import requirements.'],
                    ['icon' => 'qr_code_scanner','title' => 'Microchip Verification',     'desc' => 'Confirmation that your pet\'s microchip meets the ISO standard required for entry.'],
                    ['icon' => 'flight_land',     'title' => 'Airport Collection',         'desc' => 'Our team collects your pet from the cargo terminal and handles all on-arrival documentation.'],
                    ['icon' => 'home',            'title' => 'Post-Arrival Care',          'desc' => 'Optional post-arrival boarding and health check while your pet settles into their new home.'],
                    ['icon' => 'support_agent',   'title' => 'Dedicated Coordinator',      'desc' => 'One point of contact throughout — keeping you informed every step of the way.'],
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
                <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Ready to start your import?</h2>
                <p class="text-primary-dark/60">Contact us as early as possible — import permits can take several weeks to process.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('relocation.checklist') }}" class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition">View Checklist</a>
                <a href="{{ \App\Support\PricingQuote::estimateUrl('relocation', null, 'import') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition shadow-glow hover:-translate-y-1">Get Estimate <span class="material-symbols-outlined text-base">calculate</span></a>
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
    ->name('Import Pets to Nigeria — Waggies Abuja')
    ->description('Full-service pet import to Nigeria — permits, health certificates, microchip verification, and airport collection in Abuja.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
