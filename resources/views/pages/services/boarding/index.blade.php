<x-layouts.app title="Luxury Pet Boarding" nav-section="services">

    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-hero.image
        image-src="/images/boarding.jpg"
        image-alt="Luxury pet boarding suites at Waggies"
        eyebrow="Abuja's #1 Pet Boarding"
        eyebrow-icon="pets"
        title="A Home Away From Home"
        variant="center"
        subtitle="Spacious, climate-controlled suites with 24/7 supervision, orthopedic bedding, and daily updates for total peace of mind."
        :primary-cta="['label' => 'Book Appointment', 'href' => \App\Support\PricingQuote::contactUrl('book', ['service' => 'boarding']), 'icon' => 'arrow_forward']"
        :secondary-cta="['label' => 'Get Estimate', 'href' => \App\Support\PricingQuote::estimateUrl('boarding'), 'icon' => 'calculate']"
    />

    <section class="py-20 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Boarding Options" title="Choose the Right Stay<br/>for Your Pet"
                subtitle="We offer tailored boarding for dogs, cats, and exotic animals — each in a dedicated, species-appropriate environment." />
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <x-card.service title="Dog Boarding"
                    description="Private suites with outdoor play areas, socialisation sessions, and daily grooming."
                    href="{{ route('services.boarding.dogs') }}" image-src="/images/boarding-dogs.jpg" icon="pets" />
                <x-card.service title="Cat Boarding"
                    description="Calm, quiet cat condos away from dogs — with enrichment toys and cosy resting nooks."
                    href="{{ route('services.boarding.cats') }}" image-src="/images/boarding-cats.jpg"
                    icon="heart_plus" />
                <x-card.service title="Exotic Boarding"
                    description="Specialist care for birds, reptiles, rabbits and small mammals in custom enclosures."
                    href="{{ route('services.boarding.exotic') }}" image-src="/images/boarding-exotic.jpg"
                    icon="nest_eco_leaf" />
            </div>
        </div>
    </section>


    <section class="py-20 bg-primary-dark">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Text Content -->
                <div class="space-y-6">

                    <div class="flex flex-col gap-2">
                        <p class="text-secondary font-bold tracking-widest uppercase text-xs">
                            What's Included
                        </p>

                        <h2 class="font-serif text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                            Everything Covered, <br> <span class="text-secondary italic">Every Day</span>
                        </h2>

                        <p class="text-white/70 text-base max-w-xl">
                            Every boarding stay includes premium amenities and attentive care as standard — no hidden
                            extras.
                        </p>
                    </div>

                    <!-- Feature Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">hotel</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-white mb-1">Luxury Suites</h3>
                                <p class="text-sm text-white/60">Climate-controlled rooms with orthopedic bedding.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">restaurant</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-white mb-1">Premium Meals</h3>
                                <p class="text-sm text-white/60">Nutritious food served on your pet's usual schedule.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">directions_run</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-white mb-1">Daily Exercise</h3>
                                <p class="text-sm text-white/60">Structured play and exercise sessions every day.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">medical_services</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-white mb-1">Vet on Site</h3>
                                <p class="text-sm text-white/60">A qualified vet monitors all boarding guests daily.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">notifications</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-white mb-1">Daily Photo Updates</h3>
                                <p class="text-sm text-white/60">Photo and report sent to you every single day.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">lock_clock</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-white mb-1">24/7 Supervision</h3>
                                <p class="text-sm text-white/60">Staff present around the clock — never unsupervised.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Image -->
                <div class="relative h-[500px] w-full rounded-2xl overflow-hidden shadow-glow">

                    <img loading="lazy" alt="Modern pet boarding facility interior"
                        class="absolute inset-0 w-full h-full object-cover"
                        src="https://images.unsplash.com/photo-1631248055855-a0f3d5f3ece5?w=800&h=600&fit=crop&q=80"
                        decoding="async">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary-dark/80 via-primary-dark/20 to-transparent">
                    </div>

                    <div
                        class="absolute bottom-6 left-6 right-6 bg-primary-dark/60 backdrop-blur p-6 rounded-2xl border border-white/10 shadow-soft">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-bold text-white">Certified Safe Space</p>

                                <div class="flex text-gold text-sm mt-1">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-bold text-secondary">1,000+</p>
                                <p class="text-xs text-white/60 uppercase font-semibold">Happy Guests</p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>


    <section class="py-20 bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What Clients Say" title="Trusted by Abuja's Pet Owners" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card.testimonial stars="5"
                    quote="My dog came back happy and healthy. The daily photos made it so easy to relax while I was away."
                    author-initial="C" author-name="Chidi A." author-subtitle="Dog owner · Gwarinpa" />
                <x-card.testimonial stars="5"
                    quote="Excellent facilities — clean, spacious and clearly well-managed. My cat was in great spirits on pickup."
                    author-initial="N" author-name="Ngozi E." author-subtitle="Cat owner · Maitama" />
                <x-card.testimonial stars="5"
                    quote="I was nervous leaving my rabbit but the team clearly knew exactly how to care for him. Will return."
                    author-initial="T" author-name="Tunde B." author-subtitle="Rabbit owner · Wuse II" />
            </div>
        </div>
    </section>


    <section class="py-20 bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            {{-- Heading --}}
            <div class="text-center mb-14">
                <span class="text-primary font-bold tracking-widest uppercase text-xs block mb-3">
                    Need Clarity?
                </span>

                <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
                    Before You Book, Here’s What You Should Know
                </h2>

                <p class="text-primary-dark/60 max-w-2xl mx-auto">
                    Quick answers to help you choose the right service for your pet with confidence.
                </p>
            </div>

            {{-- FAQ --}}
            <div class="max-w-3xl mx-auto space-y-3">

                {{-- 1 --}}
                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        How do I know which service my pet needs?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>
                    <p class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        If you're unsure, start with a consultation or contact us. Our team will recommend the right
                        service based on your pet’s age, health, and behavior.
                    </p>
                </details>

                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        Are all services safe for my pet?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>

                    <div class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        Yes. Every service follows strict vet-supervised safety standards and trained handlers.
                    </div>
                </details>

                {{-- 2 --}}
                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        Are all services safe for my pet?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>
                    <p class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        Yes. Every service follows strict vet-supervised safety standards and trained handlers. Safety
                        is built into every part of our system.
                    </p>
                </details>

                {{-- 3 --}}
                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        Can I switch or combine services later?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>
                    <p class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        Yes. Many clients combine grooming, boarding, and vet care depending on their pet’s needs. We
                        can adjust plans anytime.
                    </p>
                </details>

                {{-- 4 --}}
                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        Do I need a consultation before booking?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>
                    <p class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        Not always. Some services can be booked directly, but consultations help us recommend the safest
                        and most effective care plan.
                    </p>
                </details>

                {{-- 5 --}}
                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        How do I get updates about my pet?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>
                    <p class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        You receive regular updates including photos and status reports depending on the service you
                        choose.
                    </p>
                </details>

                {{-- 6 --}}
                <details
                    class="group bg-white border border-primary/10 rounded-2xl hover:border-primary/30 hover:shadow-sm transition">
                    <summary
                        class="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">
                        What happens after I book a service?
                        <span
                            class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                            expand_more
                        </span>
                    </summary>
                    <p class="px-6 pb-5 pt-3 text-sm text-primary-dark/60 leading-relaxed">
                        Our team contacts you to confirm details, prepare your pet’s care plan, and guide you through
                        the next steps.
                    </p>
                </details>

            </div>

        </div>
    </section>


    <section class="w-full py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-cta.primary heading="Secure Your Pet’s Stay" heading_accent="While You're Away"
                body="Reserve your pet’s suite today and enjoy complete peace of mind with daily updates, expert care, and 24/7 supervision."
                primary_label="Book Boarding" :primary_href="\App\Support\PricingQuote::contactUrl('book', ['service' => 'boarding'])" primary_icon="arrow_forward"
                secondary_label="Get Estimate" :secondary_href="\App\Support\PricingQuote::estimateUrl('boarding')" secondary_icon="calculate" />
        </div>
    </section>






    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::service()
                ->name('Pet Boarding Abuja — Waggies')
                ->description(
                    'Luxury pet boarding in Abuja with 24/7 supervision, private suites, on-site vet, and daily photo updates.',
                )
                ->url(url()->current())
                ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
                ->areaServed('Abuja, Nigeria')
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
