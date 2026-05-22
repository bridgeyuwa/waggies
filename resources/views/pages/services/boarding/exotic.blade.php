<x-layouts.app title="Exotic Pet Boarding" nav-section="services">
    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-hero.image
        image-src="https://images.unsplash.com/photo-1548767797-d9f163aedbf1?auto=format&fit=crop&w=1600&q=80"
        image-alt="Colourful parrot in a specialist exotic pet care environment"
        eyebrow="Exotic Pet Boarding"
        eyebrow-icon="bug_report"
        title="Specialist Care for Exotic Pets"
        variant="center"
        subtitle="Birds, reptiles, rabbits, guinea pigs, and small mammals — cared for by specialist staff in custom-built, species-appropriate enclosures."
        :primary-cta="['label' => 'Enquire Now', 'href' => \App\Support\PricingQuote::contactUrl('consult', ['service' => 'boarding', 'variant' => 'exotic']), 'icon' => 'arrow_forward']"
        :secondary-cta="['label' => 'Get Estimate', 'href' => \App\Support\PricingQuote::estimateUrl('boarding-exotic'), 'icon' => 'calculate']"
    />

    <x-boarding.stats-bar :stats="[
        ['value' => '50+', 'label' => 'Species Experience'],
        ['value' => '24/7', 'label' => 'Climate Monitoring'],
        ['value' => '100%', 'label' => 'Custom Enclosures'],
        ['value' => 'Daily', 'label' => 'Care Updates'],
    ]" />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="We Board" title="Which Exotic Pets Do We Care For?"
                subtitle="Our exotic wing is staffed by specialists trained for the unique needs of non-traditional pets." />
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-10">
                @foreach ([
                    ['label' => 'Birds', 'icon' => 'flutter'],
                    ['label' => 'Reptiles', 'icon' => 'pest_control'],
                    ['label' => 'Rabbits', 'icon' => 'cruelty_free'],
                    ['label' => 'Guinea Pigs', 'icon' => 'nest_eco_leaf'],
                    ['label' => 'Hamsters', 'icon' => 'pets'],
                    ['label' => 'Fish', 'icon' => 'water'],
                ] as $pet)
                    <div
                        class="bg-surface-purple rounded-2xl p-5 flex flex-col items-center gap-2 text-center hover:shadow-soft transition-shadow">
                        <span class="material-symbols-outlined text-3xl text-primary icon-filled"
                            aria-hidden="true">{{ $pet['icon'] }}</span>
                        <span class="text-sm font-semibold text-primary-dark">{{ $pet['label'] }}</span>
                    </div>
                @endforeach
            </div>
            <p class="mt-8 text-center text-sm text-primary-dark/50 max-w-2xl mx-auto">
                Unsure if we can board your pet? We do not accept venomous species, very large constrictors, or wild/protected animals —
                <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a> and we'll advise honestly.
            </p>
        </div>
    </section>

    <section id="boarding-options" class="py-20 bg-secondary/20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Exotic Boarding Packages" title="Care Matched to Your Species"
                subtitle="Pricing reflects enclosure complexity and specialist handling — we'll confirm exact rates when you enquire." />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 mx-auto">
                <x-card.pricing variant="standard" tierLabel="Small Mammal" price="₦6,000" unit="/night"
                    :features="[
                        ['label' => 'Rabbits, guinea pigs, hamsters', 'included' => true],
                        ['label' => 'Species-appropriate enclosure', 'included' => true],
                        ['label' => 'Daily health check', 'included' => true],
                        ['label' => 'Climate-controlled reptile setup', 'included' => false],
                    ]" ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-exotic', null, 'small')" />

                <x-card.pricing variant="featured" tierLabel="Specialist" badgeLabel="Most Popular" price="₦12,000"
                    unit="/night" :features="[
                        ['label' => 'Birds or reptiles', 'included' => true],
                        ['label' => 'Temp, humidity & UV monitoring', 'included' => true],
                        ['label' => 'Custom feeding protocol', 'included' => true],
                        ['label' => 'Daily photo updates', 'included' => true],
                    ]" ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-exotic', null, 'specialist')" />

                <x-card.pricing variant="standard" tierLabel="Premium Habitat" price="₦18,000" unit="/night"
                    :features="[
                        ['label' => 'Complex multi-zone enclosures', 'included' => true],
                        ['label' => 'Live/frozen feeder handling', 'included' => true],
                        ['label' => 'Exotic vet daily review', 'included' => true],
                        ['label' => 'Owner-supplied habitat setup', 'included' => true],
                    ]" ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-exotic', null, 'premium')" />
            </div>
        </div>
    </section>

    <section class="py-20 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What's Included" title="Tailored Care for Every Species" />
            <x-boarding.feature-grid :features="[
                ['icon' => 'home', 'title' => 'Species-Specific Enclosures', 'desc' => 'Custom housing with correct temperature, humidity, and lighting for each species.'],
                ['icon' => 'restaurant', 'title' => 'Specialist Diet', 'desc' => 'Feeding according to each animal\'s dietary needs and your usual routine — including live feeders when required.'],
                ['icon' => 'medical_services', 'title' => 'Exotic Vet Access', 'desc' => 'Our vet has experience with exotic and small animals and reviews guests daily.'],
                ['icon' => 'notifications', 'title' => 'Regular Updates', 'desc' => 'Photo updates so you can check in on your pet throughout their stay.'],
                ['icon' => 'lock_clock', 'title' => '24/7 Monitoring', 'desc' => 'Staff check on exotic guests throughout the day and night, including climate systems.'],
                ['icon' => 'psychology', 'title' => 'Experienced Handlers', 'desc' => 'Trained staff who understand handling, stress signals, and species-specific behaviour.'],
            ]" />
        </div>
    </section>

    <x-boarding.day-in-life heading-id="exotic-daily-heading" heading="A Day in the Life for Exotic Guests"
        subtitle="Precise routines for temperature, feeding, and handling — because exotic pets thrive on consistency."
        :schedule="[
            ['icon' => 'wb_sunny', 'title' => '7:00 AM — Habitat Check', 'description' => 'Temperature, humidity, UV, and water systems verified for each enclosure.'],
            ['icon' => 'restaurant', 'title' => '8:00 AM — Morning Feed', 'description' => 'Meals or feeders administered per your written protocol.'],
            ['icon' => 'visibility', 'title' => '12:00 PM — Health Observation', 'description' => 'Behaviour, appetite, and enclosure condition logged by specialist staff.'],
            ['icon' => 'thermostat', 'title' => '4:00 PM — Environment Review', 'description' => 'Climate adjustments and enrichment rotation as species require.'],
            ['icon' => 'nights_stay', 'title' => '8:00 PM — Evening Round', 'description' => 'Final checks, secure enclosures, and overnight monitoring handover.'],
        ]"
        :images="[
            ['src' => 'https://images.unsplash.com/photo-1452571296672-f48ee9998218?w=800&h=600&fit=crop&q=80', 'alt' => 'Colourful parrot on a perch', 'loading' => 'eager'],
            ['src' => 'https://images.unsplash.com/photo-1559251606-4facabe8f63c?w=800&h=600&fit=crop&q=80', 'alt' => 'Rabbit in a clean enclosure', 'loading' => 'eager'],
            ['src' => 'https://images.unsplash.com/photo-1585110390000-470d0fbcaee0?w=800&h=600&fit=crop&q=80', 'alt' => 'Bearded dragon in a terrarium', 'loading' => 'lazy'],
            ['src' => 'https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?w=800&h=600&fit=crop&q=80', 'alt' => 'Small mammal cared for in a cosy habitat', 'loading' => 'lazy'],
        ]" />

    <x-boarding.safety-card title="Exotic Care & Safety Protocols"
        intro="Every exotic booking starts with a pre-arrival consultation. We confirm species requirements, feeding instructions, and enclosure specifications before your pet arrives."
        :bullets="[
            'Pre-stay consultation to confirm habitat and dietary needs',
            'Owner-supplied feeders or equipment welcomed and labelled',
            'Separate exotic wing — no contact with dogs or cats',
            'Immediate vet escalation if behaviour or environment deviates',
        ]"
        link-label="Start an exotic boarding enquiry"
        :link-href="route('contact')" />

    <x-feature-band eyebrow="Before You Book" title="What to Prepare for Check-In"
        subtitle="A little preparation helps exotic pets settle faster and keeps their routine intact."
        :cta="['label' => 'Discuss Your Pet', 'href' => route('contact')]"
        :features="[
            ['icon' => 'description', 'title' => 'Written Care Sheet', 'description' => 'Feeding times, portion sizes, temperature ranges, and any handling preferences.'],
            ['icon' => 'inventory_2', 'title' => 'Supplies from Home', 'description' => 'Food, substrates, feeders, or habitat items your pet is used to — clearly labelled.'],
            ['icon' => 'verified', 'title' => 'Health Records', 'description' => 'Recent vet notes or vaccination records where applicable for your species.'],
        ]" />

    <section class="py-16 bg-surface-purple border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-cta.primary heading="Enquire About Exotic Boarding"
                body="Tell us your species, setup, and dates — we'll confirm availability and build a care plan before check-in."
                primary_label="Get in Touch" :primary_href="\App\Support\PricingQuote::contactUrl('consult', ['service' => 'boarding', 'variant' => 'exotic'])" primary_icon="arrow_forward"
                secondary_label="Get Estimate" :secondary_href="\App\Support\PricingQuote::estimateUrl('boarding-exotic')"
                secondary_icon="payments" />
        </div>
    </section>

    <x-boarding.sibling-links current="exotic" />

    @if ($faqs->isNotEmpty())
        <section class="py-16 bg-surface-purple/40">
            <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
                <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions" class="mb-8" />
                <x-faq-accordion :faqs="$faqs->map(fn ($f) => ['question' => $f->question, 'answer' => $f->answer])->all()" />
                <p class="mt-6 text-sm text-primary-dark/50">More questions?
                    <a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">View all FAQs</a>
                    or
                    <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.
                </p>
            </div>
        </section>
    @endif

    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::service()
                ->name('Exotic Pet Boarding Abuja — Waggies')
                ->description(
                    'Specialist boarding for birds, reptiles, rabbits, guinea pigs, and small mammals in custom enclosures with experienced handlers.',
                )
                ->url(url()->current())
                ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
                ->areaServed('Abuja, Nigeria')
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
