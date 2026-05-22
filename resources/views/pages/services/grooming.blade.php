<x-layouts.app title="Pet Grooming Spa" nav-section="services">

    <x-hero.image
        image-src="/images/grooming.jpg"
        eyebrow="Grooming Spa · Abuja"
        title='The Grooming Spa<br/>Your Pet Deserves'
        subtitle="Breed-specific treatments, luxury baths, and precision styling by certified groomers in a calm, purpose-built spa."
        :primary-cta="['label' => 'Book a Groom', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Our Grooming Services"
                title="A Full Menu of<br/>Grooming Treatments"
                subtitle="Whether it's a quick bath and brush or a full breed-standard groom, we have a treatment to suit every pet."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'water_drop',  'title' => 'Luxury Bath & Dry',         'desc' => 'Premium shampoo and conditioner suited to your pet\'s coat type, followed by a professional blow-dry.'],
                    ['icon' => 'content_cut', 'title' => 'Breed-Standard Styling',    'desc' => 'Full cuts and styling to breed standard by certified groomers with years of experience.'],
                    ['icon' => 'spa',         'title' => 'De-shed Treatment',          'desc' => 'A deep deshedding treatment that dramatically reduces loose fur and keeps coats healthy.'],
                    ['icon' => 'face',        'title' => 'Facial & Eye Clean',         'desc' => 'Gentle face wash, eye cleaning, and ear inspection to keep your pet fresh and comfortable.'],
                    ['icon' => 'back_hand',   'title' => 'Nail Trim & Grind',          'desc' => 'Safe nail trimming and grinding to a comfortable, healthy length.'],
                    ['icon' => 'star',        'title' => 'Full Groom Package',         'desc' => 'Bath, dry, cut, nails, ears, and a light spritz — the complete luxury treatment.'],
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
        eyebrow="Our Standards"
        title='Grooming Done Right,<br/><span class="text-secondary italic">Every Time</span>'
        subtitle="We use only premium, pet-safe products and handle every animal with patience and care."
        :cta="['label' => 'Book a Groom', 'href' => route('contact')]"
        :features="[
            ['icon' => 'verified',    'title' => 'Certified Groomers',    'description' => 'All groomers are professionally trained and certified.'],
            ['icon' => 'eco',         'title' => 'Pet-Safe Products',     'description' => 'We use only premium, non-toxic, pet-safe shampoos and conditioners.'],
            ['icon' => 'psychology',  'title' => 'Calm Handling',         'description' => 'Patient, low-stress handling techniques — especially for nervous pets.'],
            ['icon' => 'schedule',    'title' => 'Punctual Service',      'description' => 'We respect your time. Drop-off and collection slots run to schedule.'],
        ]"
    />


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
    ->name('Pet Grooming Spa Abuja — Waggies')
    ->description('Breed-specific pet grooming in Abuja — luxury baths, precision styling, nail trims, and de-shed treatments by certified groomers.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
