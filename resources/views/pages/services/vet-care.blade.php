<x-layouts.app title="Veterinary Care" nav-section="services">

    <x-hero.image
        image-src="/images/vetcare.jpg"
        eyebrow="On-Site Vet Care · Abuja"
        title='On-Site Veterinary Care<br/>You Can Rely On'
        subtitle="Consultations, vaccinations, wellness checks, and minor treatments — delivered by our qualified on-site veterinarian every day of the week."
        :primary-cta="['label' => 'Book a Consultation', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Vet Services"
                title="Comprehensive Care<br/>for Every Pet"
                subtitle="From routine check-ups to treatment and advice, our vet is here to keep your pet healthy and thriving."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'stethoscope',       'title' => 'General Consultations',  'desc' => 'Full nose-to-tail wellness examinations and health consultations for all species.'],
                    ['icon' => 'vaccines',           'title' => 'Vaccinations',           'desc' => 'Core and non-core vaccinations administered on a personalised schedule.'],
                    ['icon' => 'monitor_heart',      'title' => 'Wellness Check-Ups',     'desc' => 'Routine health assessments to catch issues early and keep your pet in peak condition.'],
                    ['icon' => 'medication',         'title' => 'Prescription & Treatment','desc' => 'Diagnosis, prescriptions, and minor treatment for common health conditions.'],
                    ['icon' => 'pest_control',       'title' => 'Parasite Prevention',    'desc' => 'Flea, tick, and worm treatments and prevention advice tailored to your pet\'s lifestyle.'],
                    ['icon' => 'science',            'title' => 'Lab & Diagnostics',      'desc' => 'In-clinic diagnostic tests and coordination with external labs for detailed results.'],
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

    <x-feature-band
        eyebrow="Why Our Vet Care?"
        title='Qualified, Caring,<br/><span class="text-secondary italic">On-Site Daily</span>'
        subtitle="Having a vet on-site every day means your pet gets professional care without the stress of a separate vet visit."
        :cta="['label' => 'Book a Consultation', 'href' => route('contact')]"
        :features="[
            ['icon' => 'medical_services', 'title' => 'Qualified Vet Daily',      'description' => 'Our vet is present on-site every day of the week, including weekends.'],
            ['icon' => 'verified',         'title' => 'PCSA Registered',          'description' => 'All vet services are delivered to certified professional standards.'],
            ['icon' => 'lock_clock',       'title' => 'Immediate Response',       'description' => 'Boarding guests receive immediate veterinary attention if any concern arises.'],
            ['icon' => 'psychology',       'title' => 'Low-Stress Environment',   'description' => 'Consultations in a calm, familiar setting — less anxiety for your pet.'],
        ]"
    />


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
    ->name('On-Site Veterinary Care Abuja — Waggies')
    ->description('Veterinary consultations, vaccinations, wellness check-ups and minor treatments by a qualified on-site vet — every day at Waggies Abuja.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
