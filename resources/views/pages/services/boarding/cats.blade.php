<x-layouts.app title="Cat Boarding" nav-section="services">
    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-hero.image
        image-src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=1600&q=80"
        image-alt="Relaxed cat in a calm boarding condo at Waggies"
        eyebrow="Cat Boarding"
        eyebrow-icon="emoticon"
        title="Calm, Cosy Stays for Your Cat"
        variant="center"
        subtitle="Quiet cat condos in a dog-free wing — with enrichment, cosy nooks, and attentive care from cat-specialist staff."
        :primary-cta="['label' => 'Book a Stay', 'href' => \App\Support\PricingQuote::contactUrl('book', ['service' => 'boarding', 'variant' => 'cats']), 'icon' => 'arrow_forward']"
        :secondary-cta="['label' => 'Get Estimate', 'href' => \App\Support\PricingQuote::estimateUrl('boarding-cats'), 'icon' => 'calculate']"
    />

    <x-boarding.stats-bar :stats="[
        ['value' => '100%', 'label' => 'Dog-Free Cat Wing'],
        ['value' => '24/7', 'label' => 'Quiet Monitoring'],
        ['value' => 'Daily', 'label' => 'Photo Updates'],
        ['value' => '3', 'label' => 'Condo Size Options'],
    ]" />

    <section id="boarding-options" class="py-20 bg-secondary/20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Cat Boarding Packages" title="Find the Right Condo for Your Cat"
                subtitle="From a peaceful overnight to premium enrichment and vet monitoring — every package respects how cats like to live." />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 mx-auto">
                <x-card.pricing variant="standard" tierLabel="Cozy" price="₦8,000" unit="/night" :features="[
                    ['label' => '1 cat condo', 'included' => true],
                    ['label' => 'Daily enrichment', 'included' => true],
                    ['label' => 'Photo updates', 'included' => true],
                    ['label' => 'Private playroom', 'included' => false],
                ]"
                    ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-cats', null, 'cozy')" />

                <x-card.pricing variant="featured" tierLabel="Premium" badgeLabel="Most Popular" price="₦14,000"
                    unit="/night" :features="[
                        ['label' => '2 cats (same condo)', 'included' => true],
                        ['label' => 'Daily enrichment', 'included' => true],
                        ['label' => 'Daily photo updates', 'included' => true],
                        ['label' => 'Private playroom time', 'included' => true],
                    ]" ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-cats', null, 'premium')" />

                <x-card.pricing variant="standard" tierLabel="Luxury" price="₦22,000" unit="/night" :features="[
                    ['label' => 'Luxury multi-level condo', 'included' => true],
                    ['label' => '1-on-1 play sessions', 'included' => true],
                    ['label' => 'Daily vet check', 'included' => true],
                    ['label' => 'Complimentary groom', 'included' => true],
                ]"
                    ctaLabel="Get Estimate" :ctaHref="\App\Support\PricingQuote::estimateUrl('boarding-cats', null, 'luxury')" />
            </div>
            <p class="mt-8 text-center text-sm text-primary-dark/50">
                Bringing bonded cats or need a longer stay?
                <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">Contact us</a>
                for a tailored quote.
            </p>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Cat Boarding Includes" title="A Stress-Free Environment for Cats"
                subtitle="Cats are sensitive travellers. Our cat wing is calm, dog-free, and designed around feline behaviour." />
            <x-boarding.feature-grid :features="[
                ['icon' => 'cabin', 'title' => 'Private Cat Condos', 'desc' => 'Multi-level condos with hammocks, perches, and cosy hideaway nooks.'],
                ['icon' => 'do_not_disturb', 'title' => 'Dog-Free Zone', 'desc' => 'Our cat wing is fully separate — cats never share corridors or outdoor areas with dogs.'],
                ['icon' => 'toys', 'title' => 'Daily Enrichment', 'desc' => 'Puzzle feeders, interactive toys, and gentle one-on-one play tailored to your cat.'],
                ['icon' => 'restaurant', 'title' => 'Tailored Feeding', 'desc' => 'Meals on your cat\'s usual schedule using your preferred brand or our premium food.'],
                ['icon' => 'medical_services', 'title' => 'Vet Monitoring', 'desc' => 'On-site vet checks all cat guests daily and addresses health concerns immediately.'],
                ['icon' => 'notifications', 'title' => 'Daily Photo Updates', 'desc' => 'Photos and a brief note every day so you always know how your cat is settling in.'],
            ]" />
        </div>
    </section>

    <x-boarding.day-in-life heading-id="cat-daily-heading" heading="A Day in the Life for Cats"
        subtitle="A calm, predictable rhythm that helps even shy cats feel secure — never rushed, never forced."
        :schedule="[
            ['icon' => 'wb_sunny', 'title' => '7:30 AM — Gentle Start', 'description' => 'Quiet check-in, fresh water, and litter refresh without disruption.'],
            ['icon' => 'restaurant', 'title' => '8:30 AM — Breakfast', 'description' => 'Meals served in-suite on your cat\'s usual schedule.'],
            ['icon' => 'toys', 'title' => '11:00 AM — Enrichment', 'description' => 'Puzzle feeders, wand play, or solo exploration in the private playroom.'],
            ['icon' => 'bedtime', 'title' => '2:00 PM — Rest Period', 'description' => 'Lights dimmed for afternoon naps — staff keep noise levels low.'],
            ['icon' => 'nights_stay', 'title' => '8:00 PM — Evening Settle', 'description' => 'Dinner, final litter check, and a calm goodnight routine.'],
        ]"
        :images="[
            ['src' => 'https://images.unsplash.com/photo-1574158622682-e40e69881006?w=800&h=600&fit=crop&q=80', 'alt' => 'Ginger cat resting comfortably on a soft blanket', 'loading' => 'eager'],
            ['src' => 'https://images.unsplash.com/photo-1495360010541-f48722b34f5d?w=800&h=600&fit=crop&q=80', 'alt' => 'Cat playing with a feather toy', 'loading' => 'eager'],
            ['src' => 'https://images.unsplash.com/photo-1513244893130-4f411fd114fe?w=800&h=600&fit=crop&q=80', 'alt' => 'Cat perched on a climbing tree in a condo', 'loading' => 'lazy'],
            ['src' => 'https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?w=800&h=600&fit=crop&q=80', 'alt' => 'Sleeping cat curled up peacefully', 'loading' => 'lazy'],
        ]" />

    <x-boarding.safety-card title="Feline Safety & Comfort Standards"
        intro="We never force interaction. Shy cats get extra settling time, familiar scents from home are welcome, and every condo has elevated perches and hiding spots."
        :bullets="[
            'Vaccination records verified before check-in',
            'Fully dog-free wing with separate air circulation',
            'Medication administered precisely to your instructions',
            'Immediate vet assessment if appetite or behaviour changes',
        ]"
        link-label="Discuss your cat's needs"
        :link-href="route('contact')" />

    <section class="py-20 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="flex flex-col lg:flex-row gap-12 items-center">
                <div class="flex-1">
                    <span class="text-primary font-bold tracking-widest uppercase text-xs mb-3 block">Why Cats Love Waggies</span>
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
                        Built for Feline Instincts
                    </h2>
                    <p class="text-primary-dark/60 text-lg max-w-prose mb-8">
                        Vertical space, privacy, and predictable routines — the three things cats need most when away from home.
                    </p>
                    <ul class="space-y-4 text-primary-dark/70">
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-primary shrink-0 icon-filled"
                                aria-hidden="true">check_circle</span>
                            <span><strong class="text-primary-dark">No dog noise or smells</strong> — reduces stress for even the most anxious cats.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-primary shrink-0 icon-filled"
                                aria-hidden="true">check_circle</span>
                            <span><strong class="text-primary-dark">Bring bedding and toys</strong> — familiar scents make settling in much faster.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-primary shrink-0 icon-filled"
                                aria-hidden="true">check_circle</span>
                            <span><strong class="text-primary-dark">Cat-specialist staff</strong> — trained in low-stress handling and reading feline body language.</span>
                        </li>
                    </ul>
                </div>
                <div class="flex-1 w-full">
                    <img src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?auto=format&fit=crop&w=900&q=80"
                        alt="Cat relaxing in a multi-level boarding condo"
                        class="rounded-2xl w-full aspect-[4/3] object-cover shadow-[var(--shadow-soft)]"
                        loading="lazy" decoding="async">
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-surface-purple border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-cta.primary heading="Ready to Book Your Cat's Stay?"
                body="Reserve a calm, dog-free condo with daily updates and care from staff who understand cats."
                primary_label="Book Cat Boarding" :primary_href="route('contact')" primary_icon="arrow_forward"
                secondary_label="View All Pricing" :secondary_href="route('services.pricing')"
                secondary_icon="payments" />
        </div>
    </section>

    <x-boarding.sibling-links current="cats" />

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
                ->name('Cat Boarding Abuja — Waggies')
                ->description(
                    'Luxury cat boarding in Abuja in a calm, dog-free environment with private condos, enrichment, and daily photo updates.',
                )
                ->url(url()->current())
                ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
                ->areaServed('Abuja, Nigeria')
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
