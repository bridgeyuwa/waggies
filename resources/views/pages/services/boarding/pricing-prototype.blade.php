@php
    /**
     * Local-only prototype: three different ways to present the existing dog-size pricing data.
     * The prototype is intentionally throwaway and is rendered only for local `?variant=` previews.
     */
    $variantKeys = ['table', 'selector', 'ladder'];
    $currentIndex = array_search($variant, $variantKeys, true);
    $currentIndex = $currentIndex === false ? 0 : $currentIndex;
    $previousVariant = $variantKeys[($currentIndex + count($variantKeys) - 1) % count($variantKeys)];
    $nextVariant = $variantKeys[($currentIndex + 1) % count($variantKeys)];
    $variantLabels = [
        'table' => 'Wide rate table',
        'selector' => 'Size selector',
        'ladder' => 'Rate ladder',
    ];
    $switchUrl = fn (string $key): string => request()->fullUrlWithQuery(['variant' => $key]);
    $bookingUrl = fn (string $key): string => route('book', [
        'service' => 'boarding',
        'pet_type' => 'dog',
        'pet_size' => $key,
    ]);
    $prototypeRows = collect($pricing)
        ->map(fn (array $rate, string $key): array => [
            'key' => $key,
            'label' => $rate['booking_label'] ?? $rate['label'] ?? str($key)->headline()->toString(),
            'guidance' => $rate['guidance'] ?? '',
            'manual_review' => ! empty($rate['manual_review']),
            'price' => ! empty($rate['manual_review'])
                ? 'Custom quote'
                : '₦'.number_format((int) $rate['amount']).'–₦'.number_format((int) ($rate['max_amount'] ?? $rate['amount'])),
        ])
        ->values()
        ->all();
@endphp

<section id="pricing-prototype" class="border-y border-primary/5 bg-surface py-20" aria-labelledby="pricing-prototype-heading" data-prototype="dog-boarding-pricing">
    <div class="page-container">
        <div class="mx-auto mb-10 max-w-3xl text-center">
            <span class="text-eyebrow mb-4 block">Prototype · Dog boarding pricing</span>
            <h2 id="pricing-prototype-heading" class="text-h2 text-primary-dark text-balance">Choose a nightly rate by size</h2>
            <p class="mx-auto mt-4 max-w-2xl text-pretty text-primary-dark/70">Indicative nightly ranges, with your size choice carried into the boarding request so you can start from the right option.</p>
        </div>

        @if($variant === 'table')
            <div class="mx-auto max-w-5xl overflow-hidden rounded-3xl border border-primary/15 bg-white shadow-soft">
                <div class="hidden border-b border-primary/10 bg-surface-purple/45 px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary-dark/60 md:grid md:grid-cols-12 md:gap-4">
                    <span class="md:col-span-6">Dog size</span>
                    <span class="md:col-span-3">Indicative range</span>
                    <span class="text-right md:col-span-3">Next step</span>
                </div>

                @foreach($prototypeRows as $row)
                    <div class="grid gap-5 border-b border-primary/10 p-6 last:border-b-0 md:grid-cols-12 md:items-center md:gap-4">
                        <div class="md:col-span-6">
                            <p class="text-lg font-bold text-primary-dark">{{ $row['label'] }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-primary-dark/65">{{ $row['guidance'] }}</p>
                        </div>
                        <div class="md:col-span-3">
                            <p class="font-serif text-2xl font-bold {{ $row['manual_review'] ? 'text-primary' : 'text-primary-dark' }}">{{ $row['price'] }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-primary-dark/55">{{ $row['manual_review'] ? 'Confirmed after review' : 'Per pet · per night' }}</p>
                        </div>
                        <div class="md:col-span-3 md:flex md:justify-end">
                            <x-waggies.button href="{{ $bookingUrl($row['key']) }}" variant="secondary" size="sm" class="w-full md:w-auto">
                                {{ $row['manual_review'] ? 'Request review' : 'Start with this size' }}
                                <x-waggies.icon name="arrow-forward" size="16" />
                            </x-waggies.button>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="mx-auto mt-6 max-w-3xl text-center text-sm leading-relaxed text-primary-dark/65">Final availability and pricing are confirmed manually. Need special care or a longer stay? <a href="{{ route('contact') }}" class="font-bold text-primary underline-offset-4 hover:underline">Contact us</a>.</p>
        @elseif($variant === 'selector')
            <div x-data="{ selected: 'medium' }" class="mx-auto max-w-5xl overflow-hidden rounded-3xl border border-primary/15 bg-white shadow-soft">
                <div class="grid lg:grid-cols-12">
                    <div class="bg-surface-purple/50 p-6 md:p-8 lg:col-span-5">
                        <span class="text-eyebrow mb-2 block">Step 1</span>
                        <h3 class="font-serif text-2xl font-bold text-primary-dark">Pick your dog’s size</h3>
                        <p class="mt-2 text-sm leading-relaxed text-primary-dark/70">Choose the closest match. You can confirm the details with the team during review.</p>

                        <div class="mt-6 space-y-3">
                            @foreach($prototypeRows as $row)
                                <button type="button" @click="selected = '{{ $row['key'] }}'" :aria-pressed="selected === '{{ $row['key'] }}'" :class="selected === '{{ $row['key'] }}' ? 'border-primary bg-white shadow-subtle' : 'border-primary/10 bg-white/60 hover:border-primary/30'" class="group flex min-h-16 w-full items-center justify-between gap-4 rounded-2xl border p-4 text-left transition-colors focus-visible:outline-none">
                                    <span>
                                        <span class="block font-semibold text-primary-dark">{{ $row['label'] }}</span>
                                        <span class="mt-1 block text-xs text-primary-dark/60">{{ $row['guidance'] }}</span>
                                    </span>
                                    <span x-cloak x-show="selected === '{{ $row['key'] }}'" class="shrink-0 text-xs font-bold uppercase tracking-wider text-primary">Selected</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 md:p-10 lg:col-span-7">
                        <span class="text-eyebrow mb-2 block">Step 2</span>
                        <p class="text-sm font-semibold text-primary-dark/60">Your indicative boarding rate</p>

                        @foreach($prototypeRows as $row)
                            <div x-cloak x-show="selected === '{{ $row['key'] }}'" x-transition.opacity>
                                <h3 class="mt-3 font-serif text-3xl font-bold text-primary-dark">{{ $row['label'] }}</h3>
                                <p class="mt-2 max-w-prose text-sm leading-relaxed text-primary-dark/70">{{ $row['guidance'] }}</p>
                                <div class="mt-8 rounded-2xl border {{ $row['manual_review'] ? 'border-accent-warm bg-accent-warm/15' : 'border-primary/10 bg-surface-purple/40' }} p-5">
                                    <p class="font-serif text-3xl font-bold text-primary-dark">{{ $row['price'] }}</p>
                                    <p class="mt-1 text-sm font-semibold text-primary-dark/65">{{ $row['manual_review'] ? 'The team will confirm the quote after review.' : 'Indicative per-pet nightly range.' }}</p>
                                </div>
                                <x-waggies.button href="{{ $bookingUrl($row['key']) }}" variant="{{ $row['manual_review'] ? 'secondary' : 'primary' }}" class="mt-8 w-full justify-center sm:w-auto">
                                    {{ $row['manual_review'] ? 'Request a staff review' : 'Continue with this size' }}
                                    <x-waggies.icon name="arrow-forward" size="16" />
                                </x-waggies.button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="mx-auto max-w-4xl rounded-3xl bg-primary-dark p-6 text-white shadow-soft md:p-10">
                <div class="mb-8 max-w-2xl">
                    <span class="mb-3 block text-xs font-bold uppercase tracking-widest text-secondary">One simple rule</span>
                    <h3 class="font-serif text-3xl font-bold leading-tight text-white md:text-4xl">Your dog’s size sets the starting point</h3>
                    <p class="mt-3 text-sm leading-relaxed text-white/80 md:text-base">Choose the closest size band below. Each option opens a boarding request with that choice already selected.</p>
                </div>

                <div class="space-y-3">
                    @foreach($prototypeRows as $index => $row)
                        <a href="{{ $bookingUrl($row['key']) }}" class="group flex flex-col gap-4 rounded-2xl border {{ $row['manual_review'] ? 'border-secondary/40 bg-secondary/10' : 'border-white/10 bg-white/5 hover:border-white/25 hover:bg-white/10' }} p-5 transition-colors focus-visible:outline-none sm:flex-row sm:items-center sm:justify-between">
                            <span class="flex min-w-0 items-start gap-4">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-full {{ $row['manual_review'] ? 'bg-secondary text-primary-dark' : 'bg-white/10 text-secondary' }} text-sm font-bold">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="min-w-0">
                                    <span class="block font-semibold text-white">{{ $row['label'] }}</span>
                                    <span class="mt-1 block text-sm leading-relaxed text-white/65">{{ $row['guidance'] }}</span>
                                </span>
                            </span>
                            <span class="flex shrink-0 items-center justify-between gap-4 sm:justify-end">
                                <span class="text-right">
                                    <span class="block font-serif text-2xl font-bold text-white">{{ $row['price'] }}</span>
                                    <span class="mt-1 block text-xs font-semibold uppercase tracking-wider text-white/60">{{ $row['manual_review'] ? 'Confirmed after review' : 'Per pet · per night' }}</span>
                                </span>
                                <span class="w-cta shrink-0 {{ $row['manual_review'] ? 'bg-secondary text-primary-dark hover:bg-secondary-hover' : 'border border-white/20 bg-white/10 text-white hover:bg-white/15' }}">Open request <x-waggies.icon name="arrow-forward" size="16" /></span>
                            </span>
                        </a>
                    @endforeach
                </div>

                <p class="mt-8 max-w-2xl text-sm leading-relaxed text-white/70">Availability, final pricing, and any special-care needs are confirmed during review. <a href="{{ route('contact') }}" class="font-bold text-secondary underline-offset-4 hover:underline">Talk to the team</a>.</p>
            </div>
        @endif
    </div>
</section>

<div x-data @keydown.window="if (!['INPUT', 'TEXTAREA', 'SELECT'].includes($event.target.tagName)) { if ($event.key === 'ArrowLeft') { $event.preventDefault(); window.location.href = @js($switchUrl($previousVariant)); } if ($event.key === 'ArrowRight') { $event.preventDefault(); window.location.href = @js($switchUrl($nextVariant)); } }" class="fixed inset-x-0 bottom-4 z-[70] flex justify-center px-4" aria-label="Pricing prototype switcher">
    <div class="flex items-center gap-3 rounded-full border border-primary/15 bg-white px-3 py-2 text-sm text-primary-dark shadow-floating">
        <a href="{{ $switchUrl($previousVariant) }}" class="flex size-9 items-center justify-center rounded-full border border-primary/10 text-lg font-bold text-primary transition-colors hover:bg-surface-purple focus-visible:outline-none" aria-label="Previous pricing concept">←</a>
        <span class="min-w-40 text-center text-xs font-bold uppercase tracking-wider">{{ $variantLabels[$variant] ?? 'Pricing concept' }}</span>
        <a href="{{ $switchUrl($nextVariant) }}" class="flex size-9 items-center justify-center rounded-full border border-primary/10 text-lg font-bold text-primary transition-colors hover:bg-surface-purple focus-visible:outline-none" aria-label="Next pricing concept">→</a>
    </div>
</div>
