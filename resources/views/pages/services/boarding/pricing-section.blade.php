<section id="boarding-options" class="scroll-mt-24 border-y border-primary/5 bg-surface py-20">
    <div class="page-container">
        <x-waggies.section-heading :eyebrow="$page['pricingHeading']['eyebrow']" :title="$page['pricingHeading']['title']" :subtitle="$page['pricingHeading']['subtitle']" />

        @if($species === 'dogs')
            <div x-data="{ selected: null }" class="mx-auto mt-10 max-w-5xl overflow-hidden rounded-3xl border border-primary/15 bg-white shadow-soft">
                <div class="grid lg:grid-cols-12">
                    <fieldset class="bg-surface-purple/50 p-6 md:p-8 lg:col-span-5">
                        <legend class="font-serif text-2xl font-bold text-primary-dark">Pick your dog’s size</legend>
                        <p id="dog-size-guidance" class="mt-2 text-sm leading-relaxed text-primary-dark/70">Choose the closest match. You can confirm the details with the team during review.</p>

                        <div class="mt-6 space-y-3">
                            @foreach($pricing as $key => $rate)
                                @php($rateSummary = ! empty($rate['manual_review']) ? 'Staff review' : '₦'.number_format((int) $rate['amount']).'–₦'.number_format((int) ($rate['max_amount'] ?? $rate['amount'])))
                                <label class="block cursor-pointer">
                                    <input type="radio" name="boarding_pet_size" value="{{ $key }}" x-model="selected" class="peer sr-only" aria-describedby="dog-size-guidance" />
                                    <span class="group flex min-h-16 w-full items-center justify-between gap-4 rounded-2xl border border-primary/10 bg-white/60 p-4 text-left transition-colors peer-checked:border-primary peer-checked:bg-white peer-checked:shadow-subtle peer-focus-visible:outline-none peer-focus-visible:ring-2 peer-focus-visible:ring-focus peer-focus-visible:ring-offset-2 hover:border-primary/30">
                                        <span>
                                            <span class="block font-semibold leading-snug text-primary-dark">{{ $rate['booking_label'] ?? $rate['label'] }}</span>
                                            <span class="mt-1 block text-xs text-primary-dark/60">{{ $rate['guidance'] ?? '' }}</span>
                                            <span class="mt-2 block text-sm font-bold text-primary">{{ $rateSummary }} <span class="text-xs font-semibold text-primary-dark/55">{{ empty($rate['manual_review']) ? '/ night' : '' }}</span></span>
                                        </span>
                                        <span x-cloak x-show="selected === '{{ $key }}'" class="shrink-0 text-xs font-bold uppercase tracking-wider text-primary">Selected</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="min-h-64 p-6 md:p-10 lg:col-span-7" aria-live="polite">
                        <span class="text-eyebrow mb-2 block">Your indicative boarding rate</span>

                        <div x-cloak x-show="selected === null" class="mt-6 flex gap-4 rounded-2xl border border-primary/10 bg-surface-purple/30 p-5">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary"><x-waggies.icon name="pets" size="20" /></div>
                            <div>
                                <h3 class="font-serif text-2xl font-bold text-primary-dark">Choose your dog’s size</h3>
                                <p class="mt-2 max-w-prose text-sm leading-relaxed text-primary-dark/70">Select the closest match to see the estimate and carry that choice into the boarding request.</p>
                            </div>
                        </div>

                        @foreach($pricing as $key => $rate)
                            <div x-cloak x-show="selected === '{{ $key }}'">
                                <h3 class="mt-3 font-serif text-3xl font-bold text-primary-dark">{{ $rate['booking_label'] ?? $rate['label'] }}</h3>
                                <p class="mt-2 max-w-prose text-sm leading-relaxed text-primary-dark/70">{{ $rate['guidance'] ?? '' }}</p>
                                <div class="mt-8 rounded-2xl border {{ ! empty($rate['manual_review']) ? 'border-secondary/60 bg-secondary/15' : 'border-primary/10 bg-surface-purple/40' }} p-5">
                                    <p class="font-serif text-3xl font-bold text-primary-dark">{{ ! empty($rate['manual_review']) ? 'Custom quote' : '₦'.number_format((int) $rate['amount']).'–₦'.number_format((int) ($rate['max_amount'] ?? $rate['amount'])) }}</p>
                                    <p class="mt-1 text-sm font-semibold text-primary-dark/65">{{ ! empty($rate['manual_review']) ? 'The team will confirm the quote after review.' : 'Indicative per-pet nightly range.' }}</p>
                                </div>
                                <x-waggies.button href="{{ route('book', ['service' => 'boarding', 'pet_type' => 'dog', 'pet_size' => $key]) }}" variant="{{ ! empty($rate['manual_review']) ? 'secondary' : 'primary' }}" class="mt-8 w-full justify-center sm:w-auto">
                                    {{ ! empty($rate['manual_review']) ? 'Request a staff review' : 'Continue with this size' }}
                                    <x-waggies.icon name="arrow-forward" size="16" />
                                </x-waggies.button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="mx-auto mt-10 max-w-4xl overflow-hidden rounded-3xl border border-primary/15 bg-white shadow-soft">
                <div class="grid md:grid-cols-[0.9fr_1.1fr]">
                    <div class="relative overflow-hidden bg-primary-dark p-8 text-white md:p-10">
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-secondary">Cat boarding</span>
                        <p class="mt-10 font-serif text-5xl font-bold tracking-tight text-white">{{ is_numeric($fixedNightlyRate ?? null) ? '₦'.number_format((int) $fixedNightlyRate) : 'Staff review' }}</p>
                        <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em] text-white/70">Per cat / night</p>
                        <div class="pointer-events-none absolute -bottom-10 -right-6 text-white/10" aria-hidden="true"><x-waggies.icon name="pets" size="190" variant="filled" /></div>
                    </div>
                    <div class="flex flex-col justify-between gap-8 p-8 md:p-10">
                        <div>
                            <span class="text-eyebrow mb-2 block">Simple nightly pricing</span>
                            <h3 class="font-serif text-2xl font-bold text-primary-dark">One clear rate for every cat</h3>
                            <p class="mt-3 max-w-prose text-sm leading-relaxed text-primary-dark/70">Availability, dates, and any special-care needs are confirmed during review.</p>
                        </div>
                        <x-waggies.button href="{{ route('book', ['service' => 'boarding', 'pet_type' => 'cat']) }}" class="w-full justify-center sm:w-auto sm:self-start">Request cat boarding <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    </div>
                </div>
            </div>
        @endif

        @if($species === 'dogs')
            <p class="mt-8 text-center text-sm text-primary-dark/60">Need a longer stay or special care? <a href="{{ route('contact') }}" class="font-bold text-primary hover:underline">Contact us</a>. Final availability and pricing are confirmed manually.</p>
        @endif
    </div>
</section>
