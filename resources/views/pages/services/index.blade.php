<x-layouts.app title="Our Services" nav-section="services">

    <x-hero.image
        image-src="/images/services.jpg"
        eyebrow="Everything Your Pet Needs"
        title='Luxury Pet Care,<br/>All Under One Roof'
        subtitle="From overnight boarding to international relocation — Waggies offers a full suite of premium services tailored to your pet."
        :primary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="What We Offer"
                title='Services Built Around<br/>Your Pet&rsquo;s Wellbeing'
                subtitle="Every service is designed with your pet's comfort, health, and happiness at the centre."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-card.service title="Luxury Boarding"   description="Spacious, climate-controlled suites with 24/7 supervision and daily photo updates."    href="{{ route('services.boarding.index') }}" image-src="/images/boarding.jpg"   icon="apartment" />
                <x-card.service title="Grooming Spa"      description="Breed-specific baths, trims, and styling by certified groomers in a calm spa environment." href="{{ route('services.grooming') }}"        image-src="/images/grooming.jpg"   icon="spa" />
                <x-card.service title="Vet Care"          description="On-site veterinary consultations, vaccinations, and routine wellness check-ups."           href="{{ route('services.vet-care') }}"        image-src="/images/vetcare.jpg"    icon="medical_services" />
                <x-card.service title="Dog Training"      description="Positive-reinforcement programmes for puppies and adult dogs of all breeds."               href="{{ route('services.training') }}"        image-src="/images/training.jpg"   icon="school" />
                <x-card.service title="Pet Transport"     description="Air-conditioned door-to-door pickup and drop-off anywhere across Abuja."                   href="{{ route('services.transport') }}"       image-src="/images/transport.jpg"  icon="local_shipping" />
                <x-card.service title="Pet Relocation"    description="Stress-free international moves with full documentation and airline coordination."          href="{{ route('relocation.index') }}"         image-src="/images/relocation.jpg" icon="flight_takeoff" />
            </div>
        </div>
    </section>

    <x-feature-band
        eyebrow="The Waggies Standard"
        title='Why Families Trust<br/><span class="text-secondary italic">Waggies</span>'
        subtitle="Every service is backed by certified professionals, luxury facilities, and a genuine love for animals."
        :cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
        :features="[
            ['icon' => 'verified',         'title' => 'PCSA Certified',       'description' => 'Fully licensed by the Pet Care Services Association of Nigeria.'],
            ['icon' => 'medical_services', 'title' => 'On-site Vet Daily',    'description' => 'A qualified vet is present every single day of the week.'],
            ['icon' => 'lock_clock',       'title' => '24/7 Supervision',     'description' => 'Pets are never left unsupervised — day or night.'],
            ['icon' => 'notifications',    'title' => 'Daily Photo Updates',  'description' => 'Receive photos and updates on your pet every day.'],
            ['icon' => 'star',             'title' => '500+ Happy Clients',   'description' => 'Trusted by hundreds of pet owners across Abuja.'],
        ]"
    />


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Our Services — Waggies Luxury Pet Care Abuja')
    ->description('Full suite of premium pet services in Abuja: boarding, grooming, vet care, training, transport and relocation.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
