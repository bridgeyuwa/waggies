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
                    {{-- Section heading — B13 pattern --}}
                    <div class="flex items-center gap-3 mb-4">
                        <span class="h-px w-8 bg-primary" aria-hidden="true"></span>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary">Our Philosophy</p>
                    </div>
                    {{-- Section h2 — font-serif text-4xl font-bold --}}
                    <h2 class="font-serif text-4xl font-bold text-primary-dark leading-tight mb-6">
                        Redefining Pet Care Standards in West Africa
                    </h2>
                    {{-- Body large — text-lg leading-relaxed --}}
                    <div class="space-y-5 text-lg leading-relaxed text-primary-dark/70">
                        <p>Founded to bridge the gap in luxury pet services, Waggies has evolved into Abuja's most
                            prestigious destination for animal wellness. We believe that unconditional love deserves
                            uncompromising care.</p>
                        <p>Our facility blends advanced medical technology with the comfort of a 5-star resort. Whether
                            it's a routine check-up, a grooming session, or international relocation, we handle every
                            detail with precision and grace.</p>
                    </div>

                    {{-- Feature rows — A12 icon container: w-8 h-8 rounded-lg bg-surface-purple --}}
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-surface-purple flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">diamond</span>
                            </div>
                            <div>
                                {{-- Card h3 — font-bold font-serif --}}
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

                {{-- Image mosaic --}}
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
                {{-- Card --}}
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>

                {{-- Card --}}
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>

                {{-- Card --}}
                <x-about.team-card name="Dr. Amara Okeke" role="Head Veterinarian"
                    image="https://lh3.googleusercontent.com/aida-public/AB6AXuAgWoocAfd8dbeiglF8t-YP3DrZ9K05COA2RiovxrhAO-TUUngB1JpLH6jTxb5iRiV3TXGzHWkZpkC6i5XPSDPGBHqQ6nrrauWhu8_vY_rlKNbWr0oAbVbJ8PhvCpeDTVwH2MlWYtJZZfKP5korMPIhPeEaPLjUd1yQc2P776NsK6BEkLYGE1PPRYDUnttzIX94gLA9gJ2nKsuBHbgg4NHn9ImoG3LF2oz9sTz78dL4-P-Ge6az336MMDYSi46u3gMq3-Dc4Kx0ewRl"
                    badge="Vet" :roles="['DVM', 'Surgery Cert']">
                    XXX Specialist in small animal surgery with over a decade of clinical experience.
                </x-about.team-card>
                {{-- Card --}}
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

            {{-- Trust (integrated, not floating) --}}
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

            {{-- MAIN CARD --}}
            <div class="grid lg:grid-cols-2 rounded-2xl overflow-hidden border border-primary/10 shadow-soft bg-white">

                {{-- INFO --}}
                <div class="flex flex-col">

                    <!-- HEADER BAND (full width, edge-to-edge inside card column) -->
                    <div class="px-8 md:px-10 py-6 bg-surface-purple border-b border-white/40">
                        <h3 class="font-serif text-2xl font-bold text-primary-dark">
                            Waggies HQ
                        </h3>
                    </div>

                    <!-- BODY (padded separately) -->
                    <div class="p-8 flex flex-col gap-6">

                        <!-- ADDRESS -->
                        <div>
                            <p class="text-xs uppercase text-primary/50 mb-1">Address</p>
                            <p class="text-primary-dark leading-relaxed">
                                Plot 1234, Ahmadu Bello Way,<br>
                                Garki 2, Abuja, Nigeria
                            </p>
                        </div>

                        <!-- HOURS -->
                        <div>
                            <p class="text-xs uppercase text-primary/50 mb-1">Hours</p>
                            <p class="text-primary-dark/80 text-sm">Mon–Fri: 9AM – 5PM</p>
                            <p class="text-primary-dark/80 text-sm">Sat–Sun: 10AM – 2PM</p>

                            <span
                                class="inline-block mt-2 text-xs font-semibold px-2 py-1 rounded bg-success-light text-success">
                                Open Now
                            </span>
                        </div>

                        <!-- CONTACT -->
                        <div>
                            <p class="text-xs uppercase text-primary/50 mb-1">Contact</p>

                            <a href="tel:+2348009244437" class="block font-semibold text-primary-dark">
                                +234 800 WAGGIES
                            </a>

                            <a href="mailto:hello@waggies.ng" class="text-sm text-primary underline">
                                hello@waggies.ng
                            </a>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-primary/10">

                            <!-- Primary CTA -->
                            <a href="https://maps.google.com/?q=Garki+2+Abuja+Nigeria" target="_blank"
                                class="flex-1 px-5 py-3 rounded-full bg-primary hover:bg-primary-dark text-white text-sm font-semibold flex items-center justify-center gap-2 transition-colors">

                                <span class="material-symbols-outlined text-base">directions</span>
                                Get Directions

                            </a>

                            <!-- Call (icon only) -->
                            <a href="tel:+2348009244437" aria-label="Call"
                                class="w-11 h-11 flex items-center justify-center rounded-full border border-primary/30 text-primary-dark hover:bg-surface-purple transition-colors">

                                <span class="material-symbols-outlined text-lg">call</span>

                            </a>

                            <!-- WhatsApp (icon only) -->
                            <a href="https://wa.me/2348009244437" aria-label="WhatsApp"
                                class="w-11 h-11 flex items-center justify-center rounded-full bg-whatsapp text-white hover:bg-whatsapp-hover transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                                    class="w-6 h-6 fill-current shrink-0 block" aria-hidden="true" focusable="false">
                                    <path
                                        d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-0 0-0.001 0-0.001 0-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353h-0zM22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z" />
                                </svg>

                            </a>

                        </div>

                    </div>
                </div>

                {{-- MAP --}}
                <div class="relative min-h-[420px] bg-surface-purple">

                    <iframe class="w-full h-full" loading="lazy"
                        src="https://maps.google.com/maps?q=Garki+2+Abuja+Nigeria&output=embed">
                    </iframe>

                    <div
                        class="absolute bottom-4 left-4 text-xs text-primary-dark/50 bg-white/70 px-3 py-1 rounded-full backdrop-blur">
                        Interactive map upgrade coming
                    </div>

                </div>

            </div>

        </div>
    </section>

    <section class="py-16 bg-white border-t border-surface-purple">
        <div class="max-w-6xl mx-auto px-4 md:px-8">

            <div
                class="rounded-2xl border border-primary/10 bg-surface-purple px-8 md:px-12 py-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-8">

                {{-- Copy --}}
                <div class="text-center md:text-left max-w-xl">
                    <h3 class="font-serif text-2xl md:text-3xl font-bold text-primary-dark leading-tight">
                        Explore Our Pet Care Services
                    </h3>

                    <p class="text-primary-dark/60 text-sm md:text-base mt-2 leading-relaxed">
                        From grooming to veterinary care and boarding, discover everything we offer to keep your pet
                        healthy and happy.
                    </p>
                </div>

                {{-- CTA --}}
                <a href="{{ route('services.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-primary hover:bg-primary-dark text-white text-sm font-semibold transition-colors shadow-soft focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">

                    <span class="material-symbols-outlined text-base">pets</span>
                    View Services

                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>

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
