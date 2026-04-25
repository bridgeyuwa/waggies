<x-layouts.app title="About Us" nav-section="about">
    <x-hero.gradient title='Complete Veterinary & Pet Care in Abuja' badge="Veterinary-led care system"
        subtitle="A fully integrated veterinary, boarding, grooming, training, relocation, and identification service designed for consistent, professional pet care in one place."
        :primary-cta="['label' => 'Book a Free Consultation', 'href' => route('contact')]" :secondary-cta="['label' => 'Explore Services', 'href' => route('services.index')]"
        image-src="https://images.unsplash.com/photo-1650454027983-e2b8fe55b30b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGx1eHVyeSUyMGRvZyUyMGJvYXJkaW5nJTIwZmFjaWxpdHklMjBpbmRvb3IlMjBtb2Rlcm4lMjBjbGVhbiUyMGRvZyUyMHN1aXRlfGVufDB8fDB8fHww" />



    {{-- Our Stats --}}
    <div
        class="relative z-30 -mt-8 mx-4 sm:mx-10 max-w-[1200px] lg:mx-auto bg-white rounded-2xl shadow-soft border border-surface-purple">
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
                <span class="text-xs uppercase tracking-widest text-primary-dark/50 font-semibold">Eco-Powered</span>
            </div>
        </div>
    </div>


    {{-- Our Philosophy --}}
    <section class="py-20 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-center">

                <div class="lg:w-1/2">
                    <!-- Section heading — B13 pattern -->
                    <div class="flex items-center gap-3 mb-4">
                        <span class="h-px w-8 bg-primary" aria-hidden="true"></span>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary">Our Philosophy</p>
                    </div>
                    <!-- Section h2 — font-serif text-4xl font-bold -->
                    <h2 class="font-serif text-4xl font-bold text-primary-dark leading-tight mb-6">
                        Redefining Pet Care Standards in West Africa
                    </h2>
                    <!-- Body large — text-lg leading-relaxed -->
                    <div class="space-y-5 text-lg leading-relaxed text-primary-dark/70">
                        <p>Founded to bridge the gap in luxury pet services, Waggies has evolved into Abuja's most
                            prestigious destination for animal wellness. We believe that unconditional love deserves
                            uncompromising care.</p>
                        <p>Our facility blends advanced medical technology with the comfort of a 5-star resort. Whether
                            it's a routine check-up, a grooming session, or international relocation, we handle every
                            detail with precision and grace.</p>
                    </div>

                    <!-- Feature rows — A12 icon container: w-8 h-8 rounded-lg bg-surface-purple -->
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-surface-purple flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">diamond</span>
                            </div>
                            <div>
                                <!-- Card h3 — font-bold font-serif -->
                                <h3 class="font-bold font-serif text-primary-dark text-base">Luxury Standards</h3>
                                <p class="text-sm text-primary-dark/55 mt-0.5">Climate-controlled suites &amp; premium
                                    amenities.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-surface-purple flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">public</span>
                            </div>
                            <div>
                                <h3 class="font-bold font-serif text-primary-dark text-base">Global Mobility</h3>
                                <p class="text-sm text-primary-dark/55 mt-0.5">Specialized in seamless international pet
                                    travel.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-surface-purple flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">verified_user</span>
                            </div>
                            <div>
                                <h3 class="font-bold font-serif text-primary-dark text-base">Expert Supervision</h3>
                                <p class="text-sm text-primary-dark/55 mt-0.5">Qualified vets on site, always.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-surface-purple flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">schedule</span>
                            </div>
                            <div>
                                <h3 class="font-bold font-serif text-primary-dark text-base">24/7 Concierge Care</h3>
                                <p class="text-sm text-primary-dark/55 mt-0.5">We never sleep so they can.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image mosaic -->
                <div class="lg:w-1/2 w-full">
                    <div class="relative">
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-primary/10 rounded-full blur-2xl"
                            aria-hidden="true"></div>
                        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-secondary/25 rounded-full blur-3xl"
                            aria-hidden="true"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="img-zoom-wrap rounded-2xl shadow-sm hover:shadow-soft transition mt-8">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDxxpHCeKkAUCKA6_u37LZYgD7eiEysnjMJ1_XhBMszI7ivmpwLXlnqS3L557Cv5skfAwssIbZGT0IceX5KRt_rceRZ89rLsI5pGut0K0r2jdnoqwALENhMXtAo-LrZV8ogODbPQfS7qxO-7aVlUg3TovbZwdqTUuBzu2dPwC0K-3v7NAZQ6FFUCiF3eXuZhVkGKR_iLGr0T6zhuA3b3SV8KAklensa67V92-4sKh9lJwXQxs4ZCoH8TFB0GpR6nX5-AXFKHOmRcVg2"
                                    alt="Modern Waggies clinic interior" class="w-full h-64 object-cover rounded-2xl">
                            </div>
                            <div class="img-zoom-wrap rounded-2xl shadow-sm hover:shadow-soft transition">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAvPqf9jVGR9bPOPiqoaoHk8-YNEWWJ_bY0Zr7q5x44WaVdOGEAaYDKKL2iSl0u8DdIEN8XNkxu0YtXzhkFzgsBRlTmoN6Mf4EK7F2BUgrYKdM-UpH7rmJbQdyMTbtNDQaNjPZZC6stF0JNDLC-rIQHTp3BbyozfmR5EKn8G285B8FaqR59x9Y4nsflOrZIDfugoUzs6SabeAg-UluIt08DVPhlGQrYDP30Ngwh1ek2O6BkIGAyvHWw7UX4T7nRjhSEB_igKwi0EoyP"
                                    alt="Pet grooming session" class="w-full h-64 object-cover rounded-2xl">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Our Story --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">Our
                        Story</span>
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark mb-5 leading-tight">
                        Built by Pet Lovers,<br />for Pet Owners
                    </h2>
                    <p class="text-primary-dark/60 leading-relaxed mb-4">
                        Waggies was born from a personal experience — a pet owner in Abuja struggling to find a boarding
                        facility that matched the standard of care they gave their own dog at home. What started as a
                        small dog hotel has grown into Abuja's most comprehensive luxury pet care centre.
                    </p>
                    <p class="text-primary-dark/60 leading-relaxed mb-4">
                        Today, we offer boarding, grooming, vet care, training, transport, and international relocation
                        — all under one roof, all to the same uncompromising standard.
                    </p>
                    <p class="text-primary-dark/60 leading-relaxed">
                        Every member of our team is an animal lover first. We believe the best pet care comes from
                        genuine passion — not just process.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ([['value' => '500+', 'label' => 'Happy Clients'], ['value' => '7', 'label' => 'Years in Business'], ['value' => '4.9', 'label' => 'Average Rating'], ['value' => '15+', 'label' => 'Team Members']] as $stat)
                        <div class="bg-surface-purple rounded-2xl p-6 text-center">
                            <span
                                class="font-serif text-4xl font-bold text-primary block mb-1">{{ $stat['value'] }}</span>
                            <span
                                class="text-xs font-semibold uppercase tracking-widest text-primary-dark/50">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Our Values --}}
    <x-feature-band eyebrow="Our Values"
        title='What Makes Waggies<br/><span class="text-secondary italic">Different</span>'
        subtitle="We hold ourselves to a higher standard — because your pets deserve nothing less.
        
        Whether it’s boarding, grooming, veterinary care, training, or relocation — our decisions follow one standard: what is best for the animal always comes first.
        "
        :cta="['label' => 'Meet the Team', 'href' => route('about.index')]" :features="[
            [
                'icon' => 'volunteer_activism',
                'title' => 'Animal-First Decisions',
                'description' => 'Every service is designed around comfort, safety, and wellbeing — never convenience or
                                                                                                                                                                                                                                                                                                                                                                                                                            speed.',
            ],
            [
                'icon' => 'sync',
                'title' => 'Consistency Across Services',
                'description' =>
                    'Whether a pet is boarding or receiving medical care, the same standards of attention apply.',
            ],
            [
                'icon' => 'shield',
                'title' => 'Safety Without Exception',
                'description' => 'From transport to grooming to recovery care, safety protocols are never compromised.',
            ],
            [
                'icon' => 'visibility',
                'title' => 'Transparency With Owners',
                'description' => 'We communicate clearly and honestly — even when situations are imperfect.',
            ],
        ]" />

    {{-- Meet the Specialists --}}
    <section class="py-20 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary block mb-3">Expertise</p>
                    <h2 class="font-serif text-4xl font-bold text-primary-dark">Meet the Specialists</h2>
                </div>
                <p class="text-primary-dark/55 max-w-md leading-relaxed">
                    Our team is composed of passionate professionals, certified internationally to ensure your pet
                    receives the highest standard of care.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card -->
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>

                <!-- Card -->
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>

                <!-- Card -->
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>
                <!-- Card -->
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>
            </div>
        </div>
    </section>


    <section class="py-20 bg-white border-t border-surface-purple">
        <div class="max-w-6xl mx-auto px-4 md:px-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <span class="text-primary font-bold tracking-widest uppercase text-xs mb-3 block">
                    Visit Us
                </span>

                <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
                    Visit Waggies HQ
                </h2>

                <p class="text-primary-dark/50 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                    Come see us in Abuja for premium pet care services.
                </p>
            </div>

            <!-- Trust (integrated, not floating) -->
            <div class="flex flex-wrap justify-center gap-6 text-sm text-primary-dark/70 mb-10">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-primary">star</span>
                    <span>4.9 Rating</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-primary">verified</span>
                    <span>Certified Care</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-primary">schedule</span>
                    <span>Open Today</span>
                </div>
            </div>

            <!-- Card -->
            <div class="grid lg:grid-cols-2 rounded-2xl overflow-hidden border border-primary/10 shadow-soft">

                <!-- Info -->
                <div class="p-8 md:p-10 bg-white flex flex-col gap-8">

                    <!-- Title -->
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-primary-dark">
                            Waggies HQ
                        </h3>
                        <p class="text-sm text-primary/60 mt-1">Abuja</p>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start gap-4">
                        <div
                            class="w-9 h-9 rounded-full bg-surface-purple flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-xl">location_on</span>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-primary/50 mb-1">Address</p>
                            <p class="text-primary-dark leading-relaxed">
                                Plot 1234, Ahmadu Bello Way,<br>
                                Garki 2, Abuja, Nigeria
                            </p>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="flex items-start gap-4">
                        <div
                            class="w-9 h-9 rounded-full bg-surface-purple flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-xl">schedule</span>
                        </div>

                        <div>
                            <p class="text-xs uppercase text-primary/50 mb-1">Opening Hours</p>

                            <p class="text-primary-dark/80 text-sm">Mon – Fri: 9:00 AM – 5:00 PM</p>
                            <p class="text-primary-dark/80 text-sm">Sat – Sun: 10:00 AM – 2:00 PM</p>

                            <!-- SYSTEM STATUS BADGE -->
                            <span
                                class="inline-block mt-2 text-xs font-semibold px-2 py-1 rounded bg-success-light text-success">
                                Open Now
                            </span>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="flex items-start gap-4">
                        <div
                            class="w-9 h-9 rounded-full bg-surface-purple flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-xl">call</span>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-primary/50 mb-1">Contact</p>

                            <a href="tel:+2348009244437" class="block font-semibold text-lg text-primary-dark">
                                +234 800 WAGGIES
                            </a>

                            <a href="mailto:hello@waggies.ng"
                                class="text-sm text-primary underline decoration-primary/30 underline-offset-4">
                                hello@waggies.ng
                            </a>
                        </div>
                    </div>

                    <!-- Actions (fixed hierarchy) -->
                    <div class="flex flex-wrap gap-3 pt-6 border-t border-primary/10">

                        <!-- Primary CTA -->
                        <a href="https://maps.google.com/?q=Garki+2+Abuja+Nigeria" target="_blank"
                            class="flex-1 px-5 py-3 rounded-full bg-primary hover:bg-primary-dark text-white text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                            Get Directions
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>

                        <!-- Secondary -->
                        <a href="tel:+2348009244437"
                            class="px-5 py-3 rounded-full border border-primary/30 text-sm font-semibold text-primary-dark hover:bg-surface-purple transition-colors">
                            Call
                        </a>

                        <!-- Tertiary (de-emphasized, not competing) -->
                        <a href="https://wa.me/2348009244437"
                            class="px-5 py-3 rounded-full bg-whatsapp/90 hover:bg-whatsapp text-white text-sm font-semibold transition-colors">
                            WhatsApp
                        </a>

                    </div>

                </div>

                <!-- Map -->
                <div class="relative min-h-[400px] bg-surface-purple">

                    <iframe class="w-full h-full" loading="lazy"
                        src="https://maps.google.com/maps?q=Garki+2+Abuja+Nigeria&output=embed">
                    </iframe>

                </div>

            </div>
        </div>
    </section>

    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::aboutPage()
                ->name('About Waggies — Abuja\'s Most Trusted Luxury Pet Care')
                ->description(
                    'Founded on a belief that every pet deserves to be treated like family. Waggies brings world-class facilities and genuine expertise to Abuja.',
                )
                ->url(url()->current())
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
