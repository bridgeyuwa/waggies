<x-layouts.app title="Dog Boarding" nav-section="services">
    <x-hero.image
        image-src="https://images.unsplash.com/photo-1601758228041-f3b2795255f1?auto=format&fit=crop&w=1600&q=80"
        image-alt="Happy dog in a safe boarding environment at Waggies"
        eyebrow="Dog Boarding"
        eyebrow-icon="pets"
        title="A Safe, Structured Stay for Your Dog"
        variant="center"
        subtitle="Private suites, supervised play, and daily routines designed to keep your dog active, comfortable, and stress-free while you're away."
        :primary-cta="['label' => 'Book Boarding', 'href' => route('contact'), 'icon' => 'arrow_forward']"
        :secondary-cta="['label' => 'View Options', 'href' => '#boarding-options']"
    />

    {{-- Our Stats --}}
    <div
        class="relative z-30 -mt-8 -mb-8 mx-4 sm:mx-10 max-w-[1200px] lg:mx-auto bg-white rounded-2xl shadow-soft border border-surface-purple">
        <div class="px-6 py-8 sm:px-10 grid grid-cols-2 md:grid-cols-4 gap-8 md:divide-x md:divide-primary/10">
            <div class="flex flex-col items-center text-center">
                <span class="font-serif text-4xl font-bold text-primary mb-1">5k+</span>
                <span class="text-xs uppercase tracking-widest text-primary-dark/50 font-semibold">Premium
                    Clients</span>
            </div>
            <div class="flex flex-col items-center text-center">
                <span class="font-serif text-4xl font-bold text-primary mb-1">24/7</span>
                <span class="text-xs uppercase tracking-widest text-primary-dark/50 font-semibold">Concierge Vet
                    Care</span>
            </div>
            <div class="flex flex-col items-center text-center">
                <span class="font-serif text-4xl font-bold text-primary mb-1">15+</span>
                <span class="text-xs uppercase tracking-widest text-primary-dark/50 font-semibold">Global
                    Specialists</span>
            </div>
            <div class="flex flex-col items-center text-center">
                <span class="font-serif text-4xl font-bold text-primary mb-1">100%</span>
                <span class="text-xs uppercase tracking-widest text-primary-dark/50 font-semibold">Protocol-Based
                    Handling</span>
            </div>
        </div>
    </div>





    <section class="py-20 bg-secondary/20">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Dog Boarding Packages" title="Find the Right Stay for Your Dog"
                subtitle="From simple overnight stays to fully supervised care with extra comfort and attention, choose a package that matches your dog’s needs and routine." />
            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10"> --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 mx-auto">
                {{-- standard plan --}}
                <x-card.pricing variant="standard" tierLabel="Basic" price="₦10,000" unit="/night" :features="[
                    ['label' => '1 dog boarding', 'included' => true],
                    ['label' => 'Daily walks', 'included' => true],
                    ['label' => '24/7 supervision', 'included' => false],
                    ['label' => 'Grooming service', 'included' => false],
                ]"
                    ctaLabel="Choose Basic" ctaHref="/booking/basic" />

                {{-- Featured Plan --}}
                <x-card.pricing variant="featured" tierLabel="Premium" badgeLabel="Most Popular" price="₦18,000"
                    unit="/night" :features="[
                        ['label' => '2 dogs boarding', 'included' => true],
                        ['label' => 'Daily walks', 'included' => true],
                        ['label' => '24/7 supervision', 'included' => true],
                        ['label' => 'Grooming service', 'included' => true],
                    ]" ctaLabel="Choose Premium" ctaHref="/booking/premium" />

                {{-- Standard Plan --}}
                <x-card.pricing variant="standard" tierLabel="Deluxe" price="₦25,000" unit="/night" :features="[
                    ['label' => '3+ dogs boarding', 'included' => true],
                    ['label' => 'Daily walks', 'included' => true],
                    ['label' => '24/7 supervision', 'included' => true],
                    ['label' => 'Grooming + spa', 'included' => true],
                ]"
                    ctaLabel="Choose Deluxe" ctaHref="/booking/deluxe" />
            </div>
        </div>
    </section>

    <section aria-labelledby="daily-heading" class="py-20 w-full bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <div class="flex flex-col lg:flex-row gap-12 items-center">

                <!-- Text + Timeline -->
                <div class="flex-1 space-y-8">

                    <!-- Header (unchanged structure, cleaned typography) -->
                    <div>
                        <h2 id="daily-heading"
                            class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
                            A Day in the Life at Waggies
                        </h2>

                        <p class="text-primary-dark/60 text-lg max-w-prose">
                            We keep tails wagging from sunrise to sunset with a structured routine of fun, food, and
                            rest.
                        </p>
                    </div>

                    <!-- Timeline (stabilized, no absolute positioning) -->
                    <ol class="space-y-6" aria-label="Daily schedule">

                        <!-- Item -->
                        <li class="grid grid-cols-[32px_1fr] gap-4 items-start">

                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-primary border-4 border-white">
                                <span class="material-symbols-outlined text-sm text-white">wb_sunny</span>
                            </div>

                            <div>
                                <h3 class="font-serif font-bold text-primary-dark">
                                    7:00 AM — Rise & Shine
                                </h3>
                                <p class="text-sm text-primary-dark/60">
                                    First potty walk of the day and fresh water service.
                                </p>
                            </div>
                        </li>

                        <li class="grid grid-cols-[32px_1fr] gap-4 items-start">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/80 border-4 border-white">
                                <span class="material-symbols-outlined text-sm text-white">restaurant</span>
                            </div>

                            <div>
                                <h3 class="font-serif font-bold text-primary-dark">
                                    8:00 AM — Breakfast
                                </h3>
                                <p class="text-sm text-primary-dark/60">
                                    Premium kibble or owner-provided meals served individually.
                                </p>
                            </div>
                        </li>

                        <li class="grid grid-cols-[32px_1fr] gap-4 items-start">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/80 border-4 border-white">
                                <span class="material-symbols-outlined text-sm text-white">sports_baseball</span>
                            </div>

                            <div>
                                <h3 class="font-serif font-bold text-primary-dark">
                                    9:30 AM — Group Play
                                </h3>
                                <p class="text-sm text-primary-dark/60">
                                    Supervised socialisation in secure outdoor paddocks.
                                </p>
                            </div>
                        </li>

                        <li class="grid grid-cols-[32px_1fr] gap-4 items-start">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/80 border-4 border-white">
                                <span class="material-symbols-outlined text-sm text-white">bedtime</span>
                            </div>

                            <div>
                                <h3 class="font-serif font-bold text-primary-dark">
                                    12:00 PM — Siesta
                                </h3>
                                <p class="text-sm text-primary-dark/60">
                                    A well-deserved nap in climate-controlled suites.
                                </p>
                            </div>
                        </li>

                        <li class="grid grid-cols-[32px_1fr] gap-4 items-start">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-primary border-4 border-white">
                                <span class="material-symbols-outlined text-sm text-white">nights_stay</span>
                            </div>

                            <div>
                                <h3 class="font-serif font-bold text-primary-dark">
                                    8:00 PM — Tuck In
                                </h3>
                                <p class="text-sm text-primary-dark/60">
                                    Final potty break, bedtime treats, and soothing music.
                                </p>
                            </div>
                        </li>

                    </ol>
                </div>

                <!-- Photo Grid (UNCHANGED except consistency touch) -->
                <div class="flex-1 w-full" aria-hidden="true">
                    <div class="grid grid-cols-2 gap-4">

                        <div class="space-y-4 pt-8">
                            <img loading="eager" fetchpriority="high"
                                class="rounded-2xl w-full h-48 object-cover shadow-[var(--shadow-soft)]"
                                alt="Two happy golden retrievers smiling at the camera"
                                src="https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=800&h=600&fit=crop&q=80">

                            <img loading="eager"
                                class="rounded-2xl w-full h-64 object-cover shadow-[var(--shadow-soft)]"
                                alt="French bulldog puppy looking curious"
                                src="https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=800&h=600&fit=crop&q=80">
                        </div>

                        <div class="space-y-4">
                            <img loading="lazy"
                                class="rounded-2xl w-full h-64 object-cover shadow-[var(--shadow-soft)]"
                                alt="Border collie running through green grass"
                                src="https://images.unsplash.com/photo-1503256207526-0d5523284280?w=800&h=600&fit=crop&q=80">

                            <img loading="lazy" decoding="async"
                                class="rounded-2xl w-full h-48 object-cover shadow-[var(--shadow-soft)]"
                                alt="Dog sleeping comfortably in a bed"
                                src="https://images.unsplash.com/photo-1541599540903-216a46ca1dc0?w=800&h=600&fit=crop&q=80">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>



    <section class="w-full py-20 bg-surface border-y border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <div class="bg-white rounded-2xl p-6 md:p-12 shadow-sm border border-surface-purple">

                <h2 class="font-serif font-bold text-primary-dark mb-4 flex items-center gap-3 text-xl">
                    <span class="material-symbols-outlined text-primary">health_and_safety</span>
                    Safety First Policy
                </h2>

                <p class="text-primary-dark/70 mb-6 leading-relaxed max-w-prose">
                    Every dog undergoes a basic health check and vaccination verification before admission.
                    Dogs are grouped by size and temperament, and all play sessions are actively supervised by trained
                    staff.
                </p>

                <!-- trust bullets (this is the real upgrade) -->
                <ul class="space-y-2 text-sm text-primary-dark/70 mb-6">
                    <li>• Vaccination & flea/tick verification required on arrival</li>
                    <li>• Dogs separated by size, temperament, and energy level</li>
                    <li>• Staff-supervised play sessions throughout the day</li>
                    <li>• Emergency vet protocol in place for all boarding dogs</li>
                </ul>

                <a href="#" class="text-primary font-bold hover:underline inline-flex items-center gap-1">
                    Check boarding requirements
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>

            </div>

        </div>
    </section>


    <section class="py-16 bg-surface-purple border-t border-surface-purple">
        <div
            class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-6">

            <x-cta.primary heading="Ready to Book Your Dog’s Stay?"
                body="Secure a safe, structured boarding experience with real-time care and supervised routines."
                primary_label="Book Boarding" primary_href="{{ route('contact') }}" primary_icon="arrow_forward" />

        </div>
    </section>


    @if ($faqs->isNotEmpty())
        <section class="py-16 bg-surface-purple/40">
            <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
                <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions" class="mb-8" />
                <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => $f->answer])->all()" />
                <p class="mt-6 text-sm text-primary-dark/50">More questions? <a href="{{ route('faq') }}"
                        class="text-primary font-medium hover:underline">View all FAQs</a> or <a
                        href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.
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
