<x-layouts.app title="Luxury Pet Boarding" nav-section="services">

    <x-hero.image
        image-src="/images/boarding.jpg"
        eyebrow="Abuja's #1 Pet Boarding"
        title='A Home Away<br/>From Home'
        subtitle="Spacious, climate-controlled suites with 24/7 supervision, orthopedic bedding, and daily updates for total peace of mind."
        :primary-cta="['label' => 'Contact Us to Book', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Boarding Options"
                title="Choose the Right Stay<br/>for Your Pet"
                subtitle="We offer tailored boarding for dogs, cats, and exotic animals — each in a dedicated, species-appropriate environment."
            />
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <x-card.service title="Dog Boarding"    description="Private suites with outdoor play areas, socialisation sessions, and daily grooming."      href="{{ route('services.boarding.dogs') }}"   image-src="/images/boarding-dogs.jpg"   icon="pets" />
                <x-card.service title="Cat Boarding"    description="Calm, quiet cat condos away from dogs — with enrichment toys and cosy resting nooks."      href="{{ route('services.boarding.cats') }}"   image-src="/images/boarding-cats.jpg"   icon="heart_plus" />
                <x-card.service title="Exotic Boarding" description="Specialist care for birds, reptiles, rabbits and small mammals in custom enclosures."        href="{{ route('services.boarding.exotic') }}" image-src="/images/boarding-exotic.jpg" icon="nest_eco_leaf" />
            </div>
        </div>
    </section>

    <x-feature-band
        eyebrow="What's Included"
        title='Everything Covered,<br/><span class="text-secondary italic">Every Day</span>'
        subtitle="Every boarding stay includes premium amenities and attentive care as standard — no hidden extras."
        :features="[
            ['icon' => 'hotel',            'title' => 'Luxury Suites',        'description' => 'Climate-controlled rooms with orthopedic bedding.'],
            ['icon' => 'restaurant',       'title' => 'Premium Meals',        'description' => 'Nutritious food served on your pet\'s usual schedule.'],
            ['icon' => 'directions_run',   'title' => 'Daily Exercise',       'description' => 'Structured play and exercise sessions every day.'],
            ['icon' => 'medical_services', 'title' => 'Vet on Site',          'description' => 'A qualified vet monitors all boarding guests daily.'],
            ['icon' => 'notifications',    'title' => 'Daily Photo Updates',  'description' => 'Photo and report sent to you every single day.'],
            ['icon' => 'lock_clock',       'title' => '24/7 Supervision',     'description' => 'Staff present around the clock — never unsupervised.'],
        ]"
    />

    <section class="py-20 bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What Clients Say" title="Trusted by Abuja's Pet Owners" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card.testimonial stars="5" quote="My dog came back happy and healthy. The daily photos made it so easy to relax while I was away." author-initial="C" author-name="Chidi A." author-subtitle="Dog owner · Gwarinpa" />
                <x-card.testimonial stars="5" quote="Excellent facilities — clean, spacious and clearly well-managed. My cat was in great spirits on pickup." author-initial="N" author-name="Ngozi E." author-subtitle="Cat owner · Maitama" />
                <x-card.testimonial stars="5" quote="I was nervous leaving my rabbit but the team clearly knew exactly how to care for him. Will return." author-initial="T" author-name="Tunde B." author-subtitle="Rabbit owner · Wuse II" />
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
    ->name('Pet Boarding Abuja — Waggies')
    ->description('Luxury pet boarding in Abuja with 24/7 supervision, private suites, on-site vet, and daily photo updates.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
