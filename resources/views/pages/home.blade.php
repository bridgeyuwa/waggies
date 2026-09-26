@extends('layouts.app')

@section('content')
    <x-waggies.cover-hero :hero="$homeHero" title-id="home-cover-hero-title">
        <x-slot:supporting>
            <span
                class="inline-flex max-w-full items-center gap-2 rounded-full border border-primary/10 bg-white px-3.5 py-1.5 shadow-sm">
                <x-waggies.icon name="verified" size="18" variant="filled" class="shrink-0 text-primary" />
                <span class="whitespace-nowrap text-xs font-semibold tracking-wide text-primary-dark/80 sm:text-sm">
                    Five services. One trusted team.
                </span>
            </span>
        </x-slot:supporting>
    </x-waggies.cover-hero>

    <section class="section-pad bg-surface">
        <div class="page-container">
            <div class="mb-12 max-w-xl"><span class="text-eyebrow mb-2 block">OUR SERVICES</span>
                <h2 class="text-h2">What We Offer</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-6">
                @foreach ($homeServiceCards as $index => $card)
                    @php($starts = ['lg:col-start-1', 'lg:col-start-3', 'lg:col-start-5', 'lg:col-start-2', 'lg:col-start-4'])
                    <a href="{{ route($card['route'], $card['params'] ?? []) }}"
                        class="w-card w-card-hover group block h-full overflow-hidden p-0 md:col-span-1 lg:col-span-2 {{ $starts[$index] }} {{ $index === 4 ? 'md:col-span-2 md:mx-auto md:max-w-md' : '' }}">
                        <div class="relative aspect-3/2 overflow-hidden"><img src="{{ $card['imageSrc'] }}"
                                alt="{{ $card['title'] }}" loading="lazy"
                                class="w-card-media w-card-media--zoom h-full w-full object-cover motion-reduce:transform-none">
                        </div>
                        <div class="p-6">
                            <div class="mb-2 flex items-center gap-2.5"><x-waggies.icon name="{{ $card['icon'] }}"
                                    size="20" class="text-primary" />
                                <h3 class="font-serif text-lg font-bold text-primary-dark">{{ $card['title'] }}</h3>
                            </div>
                            <p class="text-body-sm">{{ $card['description'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-pad border-t border-primary/5 bg-white">
        <div class="page-container">
            <div class="max-w-2xl">
                <p class="mb-3 font-serif text-xl italic text-primary">Our care standards</p>
                <h2 class="text-h2-feature">Why Pet Owners Choose Waggies</h2>
                <p class="mt-5 text-lg leading-relaxed text-primary-dark/75">Our care team combines consistent routines,
                    veterinary support, and clear updates so pet owners can make informed decisions about every stay.</p>
            </div>
            <div class="mt-12 grid grid-cols-1 gap-y-10 lg:mt-16 lg:grid-cols-12 lg:gap-x-14">
                <figure class="lg:col-span-5">
                    <div class="relative aspect-square overflow-hidden rounded-2xl shadow-soft lg:aspect-4/5"><img
                            src="/media/home/care-standards.jpg" alt="Puppy resting comfortably in a cozy boarding suite"
                            loading="lazy" class="h-full w-full object-cover"></div>
                    <figcaption class="mt-3 text-xs leading-relaxed text-primary-dark/70">A young guest settling in for a
                        rest in one of our boarding suites.</figcaption>
                </figure>
                <div class="flex flex-col lg:col-span-7">
                    <ul>
                        @foreach ($careStandardRows as $row)
                            <li
                                class="grid grid-cols-1 gap-x-10 gap-y-1.5 border-t border-primary/10 py-7 sm:grid-cols-[minmax(0,2fr)_minmax(0,3fr)] lg:py-8">
                                <h3 class="text-h3 text-primary-dark">{{ $row['title'] }}</h3>
                                <p class="text-[0.9375rem] leading-relaxed text-primary-dark/70 sm:pt-1.5">
                                    {{ $row['description'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <div class="border-t border-primary/10 pt-7 lg:pt-8"><x-waggies.button
                            href="{{ route('about.gallery') }}" variant="link"
                            class="text-primary px-0! py-0! hover:underline">See more inside our facilities <x-waggies.icon
                                name="arrow-forward" size="16" /></x-waggies.button></div>
                </div>
            </div>
        </div>
    </section>

    <section x-data="homeTestimonials(@js($homeTestimonials))" class="section-pad border-y border-primary/5 bg-surface-purple/50"
        aria-label="Client testimonials spotlight" @mouseenter="hovered = true" @mouseleave="hovered = false"
        @focusin="focused = true" @focusout="if (!$event.currentTarget.contains($event.relatedTarget)) focused = false">
        <div class="page-container">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div><span class="text-eyebrow mb-2 block">CLIENT EXPERIENCES</span>
                    <h2 class="text-h2">Trusted by Abuja Pet Parents</h2>
                </div>
                <p class="max-w-md text-body-sm text-primary-dark/70">Real stories from pet owners who entrust their
                    companions to Waggies for boarding, grooming, and relocation.</p>
            </div>
            <div class="grid grid-cols-1 items-stretch gap-8 lg:grid-cols-12">
                <div class="order-2 flex flex-col justify-center space-y-3 transition-[opacity,transform] duration-[280ms] ease-[cubic-bezier(0.23,1,0.32,1)] lg:order-1 lg:col-span-4 motion-reduce:transition-opacity motion-reduce:duration-180 motion-reduce:transform-none"
                    :class="transitioning ? 'translate-y-1 opacity-0' : 'translate-y-0 opacity-100'">
                    <p class="mb-1 px-1 text-xs font-semibold uppercase tracking-wider text-primary-dark/40">Select Client
                        Story</p>
                    <div role="tablist" aria-orientation="vertical" aria-label="Select client story"
                        class="flex flex-col gap-3" @keydown="tabKeydown($event)">
                        @foreach ($homeTestimonials as $index => $testimonial)
                            <button id="testimonial-tab-{{ $index }}" data-testimonial-index="{{ $index }}"
                                x-cloak x-show="isVisible({{ $index }})" type="button" role="tab"
                                aria-controls="testimonial-panel" :aria-selected="active === {{ $index }}"
                                :tabindex="active === {{ $index }} ? 0 : -1"
                                @click="select({{ $index }}, $event)"
                                class="group relative flex min-h-[52px] w-full items-center justify-between rounded-xl border p-4 text-left transition duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                :class="active === {{ $index }} ?
                                    'translate-x-1 border-primary/20 bg-white text-primary-dark shadow-sm' :
                                    'border-transparent bg-white/40 text-primary-dark/60 hover:bg-white/70 hover:text-primary-dark'"><span
                                    class="flex items-center gap-3"><span
                                        class="flex size-8 items-center justify-center rounded-full font-serif text-xs font-bold"
                                        :class="active === {{ $index }} ? 'bg-primary text-white' :
                                            'bg-primary/10 text-primary'">{{ $testimonial['initial'] }}</span><span><span
                                            class="block text-sm font-semibold leading-tight">{{ $testimonial['name'] }}</span><span
                                            class="block text-xs text-primary-dark/50">{{ $testimonial['service'] }}</span></span></span><x-waggies.icon
                                    name="chevron-right" size="16"
                                    class="shrink-0 text-primary opacity-0 transition group-hover:opacity-100" /></button>
                        @endforeach
                    </div>
                    @if (count($homeTestimonials) > 3)
                        <div class="flex flex-col items-start gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between"
                            role="group" aria-label="Testimonial story sets">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="previousBatch($event)"
                                    aria-label="Previous testimonial stories"
                                    class="grid size-11 place-items-center rounded-full border border-primary/20 text-primary transition-colors hover:bg-surface-purple focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                                    <x-waggies.icon name="arrow-back" size="16" />
                                </button>
                                <span class="min-w-16 text-center text-xs font-semibold text-primary-dark/55"
                                    aria-live="polite">Set <span x-text="batch + 1"></span> of <span
                                        x-text="batchCount()"></span></span>
                                <button type="button" @click="nextBatch($event)" aria-label="Next testimonial stories"
                                    class="grid size-11 place-items-center rounded-full border border-primary/20 text-primary transition-colors hover:bg-surface-purple focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                                    <x-waggies.icon name="arrow-forward" size="16" />
                                </button>
                            </div>
                            <a href="{{ route('about.testimonials') }}"
                                class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-primary underline-offset-4 hover:text-primary-dark hover:underline">
                                Read all testimonials
                                <x-waggies.icon name="arrow-forward" size="16" />
                            </a>
                        </div>
                    @endif
                </div>
                <div class="order-1 lg:order-2 lg:col-span-8">
                    <div
                        class="w-card relative flex h-full min-h-[30rem] flex-col justify-between overflow-hidden border border-primary/10 bg-white p-8 sm:min-h-[28rem] md:p-12">
                        <x-waggies.icon name="quotes" size="140"
                            class="pointer-events-none absolute -bottom-6 -right-6 text-primary/5" />
                        <div id="testimonial-panel" role="tabpanel" aria-live="polite"
                            :aria-labelledby="'testimonial-tab-' + displayed" data-testimonial-content
                            class="relative z-10 min-w-0 transition-[opacity,translate] duration-[280ms] ease-[cubic-bezier(0.23,1,0.32,1)] motion-reduce:transition-opacity motion-reduce:duration-180 motion-reduce:translate-y-0"
                            :class="transitioning ? 'translate-y-1 opacity-0' : 'translate-y-0 opacity-100'">
                            <div class="mb-6 flex flex-wrap items-center justify-between gap-4"><span
                                    class="rounded-full bg-surface-purple px-3 py-1 text-xs font-semibold tracking-wide text-primary"
                                    x-text="testimonials[displayed].service"></span>
                                <div class="flex items-center gap-1" role="img" :aria-label="ratingLabel()"><template
                                        x-for="i in 5" :key="i"><x-waggies.icon name="star"
                                            size="18"
                                            x-bind:class="i <= Number(testimonials[displayed].stars || 0) ? 'text-gold' :
                                                'text-primary/15'" /></template>
                                </div>
                            </div>
                            <div class="min-h-[16rem] sm:min-h-[15rem] lg:min-h-[13rem]">
                                <blockquote
                                    class="mb-8 break-words font-serif text-xl font-medium leading-relaxed text-primary-dark sm:text-2xl md:text-3xl"
                                    x-text="'“' + testimonials[displayed].quote + '”'"></blockquote>
                                <footer class="flex items-center gap-4 border-t border-primary/5 pt-4"><span
                                        class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary font-serif text-lg font-bold text-white shadow-sm"
                                        x-text="testimonials[displayed].initial" aria-hidden="true"></span>
                                    <div><cite class="block text-base font-bold leading-snug text-primary-dark"
                                            x-text="testimonials[displayed].name"></cite><span
                                            class="text-xs text-primary-dark/60"
                                            x-text="testimonials[displayed].subtitle"></span></div>
                                </footer>
                            </div>
                        </div>
                        <div class="mt-8 flex items-center gap-2 pt-4" role="group"
                            aria-label="Testimonial progress controls">
                            @foreach ($homeTestimonials as $index => $testimonial)
                                <button x-cloak x-show="isVisible({{ $index }})" type="button"
                                    :aria-pressed="active === {{ $index }}"
                                    :tabindex="isVisible({{ $index }}) ? 0 : -1"
                                    aria-label="Show testimonial {{ $index + 1 }}"
                                    @click="select({{ $index }}, $event)"
                                    class="h-2 min-w-[12px] rounded-full transition-[width,background-color] duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-1"
                                    :class="active === {{ $index }} ? 'w-8 bg-primary' :
                                        'w-3 bg-primary/20 hover:bg-primary/40'"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-pad bg-surface">
        <div class="page-container">
            @php($homeBookingCta = ['heading' => 'Ready to', 'headingAccent' => 'book?', 'body' => 'Start a booking request for your pet, or contact the care team if you have a general question.', 'primaryLabel' => 'Request a booking', 'primaryRoute' => 'book', 'secondaryLabel' => 'See Pricing', 'secondaryRoute' => 'services.pricing'])
            <div class="relative overflow-hidden rounded-3xl bg-primary-dark px-8 py-16 md:px-16 md:py-20">
                <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-secondary/60 md:inset-x-16"></div>
                <x-waggies.cta-centered :cta="$homeBookingCta" />
            </div>
        </div>
    </section>
@endsection
