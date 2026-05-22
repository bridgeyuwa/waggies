@php
    use App\Support\PricingQuote;

    $services = config('waggies.pricing.services', []);
@endphp

<x-layouts.app title="Pricing" nav-section="services">

    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-ui.page-header
        align="center"
        eyebrow="Transparent Pricing"
        title="Simple, Honest Pricing"
        subtitle="No hidden fees. Use our estimator for a tailored quote, then book or speak with our team."
    />

    <x-pricing.calculator :calculator="$calculator" />

    @foreach (['boarding' => 'Overnight Boarding', 'grooming' => 'Grooming Packages'] as $serviceKey => $sectionTitle)
        @php
            $service = $services[$serviceKey] ?? null;
        @endphp
        @if ($service)
            <section class="py-20 {{ $loop->even ? 'bg-surface-purple' : 'bg-white' }}">
                <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
                    <x-section-heading :eyebrow="$service['label']" :title="$sectionTitle" />
                    @if (isset($service['variants']))
                        @foreach ($service['variants'] as $variantKey => $variant)
                            <h3 class="mt-10 mb-6 font-serif text-xl font-bold text-primary-dark">{{ $variant['label'] }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                @foreach ($variant['tiers'] as $tierKey => $tier)
                                    @include('components.pricing.tier-card', [
                                        'tier' => $tier,
                                        'unit' => $service['unit'] ?? '',
                                        'estimateUrl' => PricingQuote::estimateUrl($serviceKey, $variantKey, $tierKey),
                                    ])
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                            @foreach ($service['tiers'] as $tierKey => $tier)
                                @include('components.pricing.tier-card', [
                                    'tier' => $tier,
                                    'unit' => $service['unit'] ?? '',
                                    'estimateUrl' => PricingQuote::estimateUrl($serviceKey, null, $tierKey),
                                ])
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif
    @endforeach

    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-primary-dark mb-3">Training, vet care, or relocation?</h2>
            <p class="text-primary-dark/60 mb-6">Use the estimator above for training and vet packages, or relocation quotes.</p>
            <a href="#pricing-calculator"
                class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                Get Estimate <span class="material-symbols-outlined">calculate</span>
            </a>
        </div>
    </section>

    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::webPage()
                ->name('Pet Care Pricing Abuja — Waggies')
                ->description('Transparent, honest pricing for Waggies pet boarding, grooming, and vet care services in Abuja, Nigeria. No hidden fees.')
                ->url(url()->current())
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
