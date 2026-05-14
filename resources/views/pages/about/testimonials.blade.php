<x-layouts.app title="Client Testimonials" nav-section="about">

    <div class="bg-surface-purple border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-16 md:py-20">
            <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">What Clients Say</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-4">Trusted by 500+<br />Abuja Pet
                Owners</h1>
            <p class="text-primary-dark/60 max-w-xl">Don't take our word for it — here's what our clients have to say
                about their Waggies experience.</p>
        </div>
    </div>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-card.testimonial stars="5"
                    quote="Waggies is the only place I'd trust with my dog. The daily photo updates gave me real peace of mind while I was travelling."
                    author-initial="A" author-name="Adaeze O." author-subtitle="Dog owner · Maitama" />
                <x-card.testimonial stars="5"
                    quote="The grooming team transformed my Persian cat. She looked like she'd come straight from a pet show!"
                    author-initial="E" author-name="Emeka N." author-subtitle="Cat owner · Wuse II" />
                <x-card.testimonial stars="5"
                    quote="Our relocation from London was seamless. Every document was sorted — zero stress on our end."
                    author-initial="F" author-name="Fatima M." author-subtitle="Relocation client · Asokoro" />
                <x-card.testimonial stars="5"
                    quote="The facilities are genuinely world-class. I've boarded dogs in Dubai and London — Waggies is right up there."
                    author-initial="C" author-name="Chukwudi B." author-subtitle="Dog owner · Garki" />
                <x-card.testimonial stars="5"
                    quote="My puppy finished the training programme a completely different dog. Patient, focused and brilliant with him."
                    author-initial="N" author-name="Ngozi T." author-subtitle="Training client · Gwarinpa" />
                <x-card.testimonial stars="5"
                    quote="Brought my cat in for grooming after she got into a mess outside. Came back looking and smelling amazing. Great service."
                    author-initial="S" author-name="Sola A." author-subtitle="Cat owner · Jabi" />
                <x-card.testimonial stars="5"
                    quote="The vet saw my dog the same day I called. Thorough, professional and really genuinely caring. Exactly what we needed."
                    author-initial="K" author-name="Kemi L." author-subtitle="Dog owner · Utako" />
                <x-card.testimonial stars="5"
                    quote="Transport service is brilliant — on time, air-conditioned and my dogs arrive calm every single time."
                    author-initial="Y" author-name="Yemi F." author-subtitle="Dog owner · Wuse" />
                <x-card.testimonial stars="5"
                    quote="We've used Waggies for boarding, grooming, and transport. Consistently excellent. Our pets love it."
                    author-initial="O" author-name="Obiageli R." author-subtitle="Multi-service client · Maitama" />
            </div>
        </div>
    </section>

    <section class="py-20 bg-surface-purple">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-primary-dark mb-3">Experience it for yourself</h2>
            <p class="text-primary-dark/60 mb-6">Join hundreds of satisfied Abuja pet owners who trust Waggies with the
                animals they love most.</p>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                Get in Touch <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <x-cta.tertiary heading="Experience it for yourself" primaryCta="Book a Tour"
                body="Join hundreds of satisfied Abuja pet owners who trust Waggies with the animals they love most."
                secondaryCta="Get in Touch" />
        </div>


    </section>


    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::webPage()
                ->name('Client Testimonials — Waggies Pet Care Abuja')
                ->description(
                    'Read what 500+ satisfied pet owners across Abuja say about their Waggies boarding, grooming, vet care, and relocation experience.',
                )
                ->url(url()->current())
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
