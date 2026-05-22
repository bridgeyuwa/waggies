<x-layouts.app title="Dog Boarding" nav-section="services">
    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-hero.image
        image-src="https://images.unsplash.com/photo-1601758228041-f3b2795255f1?auto=format&fit=crop&w=1600&q=80"
        image-alt="Happy dog in a safe boarding environment at Waggies"
        eyebrow="Dog Boarding"
        eyebrow-icon="pets"
        title="A Safe, Structured Stay for Your Dog"
        variant="center"
        subtitle="Private suites, supervised play, and daily routines designed to keep your dog active, comfortable, and stress-free while you're away."
        :primary-cta="['label' => 'Book Boarding', 'href' => \App\Support\PricingQuote::contactUrl('book', ['service' => 'boarding', 'variant' => 'dogs']), 'icon' => 'arrow_forward']"
        :secondary-cta="['label' => 'Get Estimate', 'href' => \App\Support\PricingQuote::estimateUrl('boarding-dogs'), 'icon' => 'calculate']"
    />

    <x-boarding.stats-bar :stats="[
        ['value' => '5k+', 'label' => 'Happy Pet Guests'],
        ['value' => '24/7', 'label' => 'On-Site Supervision'],
        ['value' => '2×', 'label' => 'Daily Exercise Sessions'],
        ['value' => '100%', 'label' => 'Vaccination Verified'],
    ]" />

    <section id="boarding-options" class="py-20 bg-secondary/20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Dog Boarding Packages" title="Find the Right Stay for Your Dog"
                subtitle="From simple overnight stays to fully supervised care with extra comfort and attention — choose a package that matches your dog's needs and routine." />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 mx-auto">
                <x-card.pricing variant="standard" tierLabel="Basic" price="₦10,000" unit="/night" :features="[
                    ['label' => '1 dog boarding', 'included' => true],
                    ['label' => 'Daily walks', 'included' => true],
                    ['label' => '24/7 supervision', 'included' => false],
                    ['label' => 'Grooming service', 'included' => false],
                ]"
                    ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-dogs', null, 'basic')" />

                <x-card.pricing variant="featured" tierLabel="Premium" badgeLabel="Most Popular" price="₦18,000"
                    unit="/night" :features="[
                        ['label' => '2 dogs boarding', 'included' => true],
                        ['label' => 'Daily walks', 'included' => true],
                        ['label' => '24/7 supervision', 'included' => true],
                        ['label' => 'Grooming service', 'included' => true],
                    ]" ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-dogs', null, 'premium')" />

                <x-card.pricing variant="standard" tierLabel="Deluxe" price="₦25,000" unit="/night" :features="[
                    ['label' => '3+ dogs boarding', 'included' => true],
                    ['label' => 'Daily walks', 'included' => true],
                    ['label' => '24/7 supervision', 'included' => true],
                    ['label' => 'Grooming + spa', 'included' => true],
                ]"
                    ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-dogs', null, 'deluxe')" />
            </div>
            <p class="mt-8 text-center text-sm text-primary-dark/50">
                Need a custom stay or extended booking?
                <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">Contact us</a>
                or
                <a href="{{ route('services.pricing') }}" class="text-primary font-medium hover:underline">view full pricing</a>.
            </p>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What's Included" title="Everything Your Dog Needs"
                subtitle="Structured care, enrichment, and comfort — all handled by trained dog-care staff." />
            <x-boarding.feature-grid :features="[
                ['icon' => 'cottage', 'title' => 'Private Suites', 'desc' => 'Climate-controlled rooms sized for your dog, with bedding refreshed daily.'],
                ['icon' => 'sports_baseball', 'title' => 'Supervised Play', 'desc' => 'Group or solo sessions in secure paddocks, matched by size and temperament.'],
                ['icon' => 'restaurant', 'title' => 'Tailored Feeding', 'desc' => 'Meals on your schedule using your food or our premium kibble.'],
                ['icon' => 'medical_services', 'title' => 'Daily Vet Checks', 'desc' => 'On-site vet monitors all guests and responds quickly to any concerns.'],
                ['icon' => 'notifications', 'title' => 'Photo Updates', 'desc' => 'Daily photos and notes so you always know how your dog is doing.'],
                ['icon' => 'spa', 'title' => 'Optional Grooming', 'desc' => 'Add a bath, brush, or full groom during their stay on Premium and Deluxe packages.'],
            ]" />
        </div>
    </section>

    <x-boarding.day-in-life heading-id="dog-daily-heading" heading="A Day in the Life for Dogs"
        subtitle="We keep tails wagging from sunrise to sunset with a structured routine of fun, food, and rest."
        :schedule="[
            ['icon' => 'wb_sunny', 'title' => '7:00 AM — Rise & Shine', 'description' => 'First potty walk and fresh water service.'],
            ['icon' => 'restaurant', 'title' => '8:00 AM — Breakfast', 'description' => 'Premium kibble or owner-provided meals served individually.'],
            ['icon' => 'sports_baseball', 'title' => '9:30 AM — Group Play', 'description' => 'Supervised socialisation in secure outdoor paddocks.'],
            ['icon' => 'bedtime', 'title' => '12:00 PM — Siesta', 'description' => 'A well-deserved nap in climate-controlled suites.'],
            ['icon' => 'nights_stay', 'title' => '8:00 PM — Tuck In', 'description' => 'Final potty break, bedtime treats, and soothing music.'],
        ]"
        :images="[
            ['src' => 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=800&h=600&fit=crop&q=80', 'alt' => 'Two happy golden retrievers smiling at the camera', 'loading' => 'eager'],
            ['src' => 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=800&h=600&fit=crop&q=80', 'alt' => 'French bulldog puppy looking curious', 'loading' => 'eager'],
            ['src' => 'https://images.unsplash.com/photo-1503256207526-0d5523284280?w=800&h=600&fit=crop&q=80', 'alt' => 'Border collie running through green grass', 'loading' => 'lazy'],
            ['src' => 'https://images.unsplash.com/photo-1541599540903-216a46ca1dc0?w=800&h=600&fit=crop&q=80', 'alt' => 'Dog sleeping comfortably in a bed', 'loading' => 'lazy'],
        ]" />

    <x-boarding.safety-card title="Safety First Policy"
        intro="Every dog undergoes a health check and vaccination verification before admission. Dogs are grouped by size and temperament, and all play sessions are actively supervised by trained staff."
        :bullets="[
            'Vaccination and flea/tick verification required on arrival',
            'Dogs separated by size, temperament, and energy level',
            'Staff-supervised play sessions throughout the day',
            'Emergency vet protocol in place for all boarding dogs',
        ]"
        link-label="Ask about boarding requirements"
        :link-href="route('contact')" />

    <section class="py-16 bg-surface-purple border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-cta.primary heading="Ready to Book Your Dog's Stay?"
                body="Secure a safe, structured boarding experience with daily updates and supervised routines."
                primary_label="Book Boarding" :primary_href="route('contact')" primary_icon="arrow_forward"
                secondary_label="View All Pricing" :secondary_href="route('services.pricing')"
                secondary_icon="payments" />
        </div>
    </section>

    <x-boarding.sibling-links current="dogs" />

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
                ->name('Dog Boarding Abuja — Waggies')
                ->description(
                    'Premium dog boarding in Abuja with private suites, outdoor play areas, socialisation sessions, and 24/7 supervision.',
                )
                ->url(url()->current())
                ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
                ->areaServed('Abuja, Nigeria')
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
