<x-layouts.app title="Contact Us" nav-section="">

    <div class="bg-surface-purple border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-16 md:py-20">
            <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">Get in Touch</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-3">Contact Waggies</h1>
            <p class="text-primary-dark/60 max-w-xl">Have a question, want to make a booking, or need a quote? We'd love to hear from you.</p>
        </div>
    </div>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

                {{-- Contact form --}}
                <div>
                    <h2 class="font-serif text-2xl font-bold text-primary-dark mb-6">Send Us a Message</h2>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-success/10 border border-success/30 text-success rounded-xl text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-primary-dark mb-1.5">Your Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-primary/20 rounded-xl px-4 py-3 text-sm text-primary-dark
                                          placeholder-primary-dark/30 focus:outline-none focus:ring-2 focus:ring-primary/40
                                          @error('name') border-error @enderror"
                                   placeholder="Adaeze Okafor" />
                            @error('name')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-primary-dark mb-1.5">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-primary/20 rounded-xl px-4 py-3 text-sm text-primary-dark
                                          placeholder-primary-dark/30 focus:outline-none focus:ring-2 focus:ring-primary/40
                                          @error('email') border-error @enderror"
                                   placeholder="you@example.com" />
                            @error('email')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-primary-dark mb-1.5">Message</label>
                            <textarea id="message" name="message" rows="5" required
                                      class="w-full border border-primary/20 rounded-xl px-4 py-3 text-sm text-primary-dark
                                             placeholder-primary-dark/30 focus:outline-none focus:ring-2 focus:ring-primary/40 resize-none
                                             @error('message') border-error @enderror"
                                      placeholder="Tell us about your pet and how we can help…">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white
                                       px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1
                                       focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
                            Send Message
                            <span class="material-symbols-outlined">send</span>
                        </button>
                    </form>
                </div>

                {{-- Contact info --}}
                <div class="flex flex-col gap-8">
                    <h2 class="font-serif text-2xl font-bold text-primary-dark">Other Ways to Reach Us</h2>
                    <x-contact-info />

                    <div class="bg-surface-purple rounded-2xl p-6">
                        <h3 class="font-semibold text-primary-dark mb-3">Opening Hours</h3>
                        <ul class="flex flex-col gap-2 text-sm text-primary-dark/70">
                            <li class="flex justify-between"><span>Monday – Friday</span><span class="font-medium text-primary-dark">8:00am – 6:00pm</span></li>
                            <li class="flex justify-between"><span>Saturday</span><span class="font-medium text-primary-dark">9:00am – 5:00pm</span></li>
                            <li class="flex justify-between"><span>Sunday</span><span class="font-medium text-primary-dark">10:00am – 4:00pm</span></li>
                        </ul>
                        <p class="mt-4 text-xs text-primary-dark/50">Boarding guests receive 24/7 supervision regardless of office hours.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::contactPage()
    ->name('Contact Waggies — Abuja Pet Care')
    ->description("Get in touch with Waggies — book a service, request a quote, or ask a question. We'd love to hear from you.")
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
