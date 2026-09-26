<?php

use App\Support\BookingPricingCatalog;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    #[Url]
    public ?string $service = null;

    #[Url]
    public ?string $variant = null;

    #[Url]
    public ?string $tier = null;

    #[Url]
    public ?int $quantity = null;

    #[Url]
    public ?float $weightKg = null;

    #[Url]
    public ?string $source = null;

    public string $step = 'form';

    /** @var array<string, mixed> */
    public array $result = [];

    /** @var array<string, string|null> */
    public array $transport = [
        'pickup' => null,
        'dropoff' => null,
        'distance_km' => null,
        'pet_species' => null,
        'pet_count' => '1',
        'journey_type' => 'one-way',
        'airport_details' => null,
    ];

    /**
     * @param  array{service?: ?string, variant?: ?string, tier?: ?string, source?: ?string}  $initialContext
     */
    public function mount(array $initialContext = []): void
    {
        $service = $this->service ?: ($initialContext['service'] ?? null);

        if ($service === 'transport') {
            $service = 'local-transport';
        }

        $this->service = $service ?: null;
        $this->variant = $this->variant ?: ($initialContext['variant'] ?? null);
        $this->tier = $this->tier ?: ($initialContext['tier'] ?? null);
        $this->source = $this->source ?: ($initialContext['source'] ?? null);
        $this->quantity = $this->quantity ?: 1;
        $this->normaliseSelection();
    }

    public function updatedService(): void
    {
        $this->variant = null;
        $this->tier = null;
        $this->step = 'form';
        $this->result = [];
        $this->normaliseSelection();
    }

    public function updatedVariant(): void
    {
        $this->tier = null;
        $this->step = 'form';
        $this->result = [];
        $this->normaliseSelection();
    }

    public function updatedTier(): void
    {
        $this->step = 'form';
        $this->result = [];
    }

    public function selectService(string $service): void
    {
        if (! array_key_exists($service, $this->serviceOptions()) || ! $this->serviceAvailable($service)) {
            return;
        }

        $this->service = $service;
        $this->variant = null;
        $this->tier = null;
        $this->step = 'form';
        $this->result = [];
        $this->normaliseSelection();
    }

    public function selectTier(string $tier): void
    {
        if (! array_key_exists($tier, $this->tierDefinitions())) {
            return;
        }

        $this->tier = $tier;
        $this->step = 'form';
        $this->result = [];
    }

    public function serviceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions(availableOnly: false, channel: 'pricing');
    }

    public function serviceAvailable(string $service): bool
    {
        return app(BookingPricingCatalog::class)->isAvailable(config("waggies_pricing.services.{$service}", []), 'pricing');
    }

    public function variantOptions(): array
    {
        return app(BookingPricingCatalog::class)->variantOptions($this->service, availableOnly: true, channel: 'pricing');
    }

    public function tierDefinitions(): array
    {
        return app(BookingPricingCatalog::class)->tiers($this->service, $this->variant, availableOnly: true, channel: 'pricing');
    }

    public function allTierDefinitions(): array
    {
        return app(BookingPricingCatalog::class)->tiers($this->service, $this->variant, availableOnly: false, channel: 'pricing');
    }

    public function tierPriceLabel(array $tier): string
    {
        return app(BookingPricingCatalog::class)->priceLabel($this->service ?? '', $this->variant, $tier);
    }

    public function hasVariants(): bool
    {
        return app(BookingPricingCatalog::class)->variants($this->service ?? '', availableOnly: true, channel: 'pricing') !== [];
    }

    public function needsWeight(): bool
    {
        return $this->service === 'grooming' && $this->variant === 'dogs';
    }

    public function quantityLabel(): string
    {
        return match ($this->service) {
            'boarding' => 'Nights',
            'grooming' => 'Sessions',
            default => 'Quantity',
        };
    }

    public function actionLabel(): string
    {
        $tier = $this->tierDefinitions()[$this->tier ?? ''] ?? [];

        return ($tier['type'] ?? 'fixed') === 'quote' ? 'Request a quote' : 'Calculate price';
    }

    public function canCalculate(): bool
    {
        if (! $this->service || ! $this->serviceAvailable($this->service)) {
            return false;
        }

        if ($this->hasVariants() && ! $this->variant) {
            return false;
        }

        if (! $this->tier || ! array_key_exists($this->tier, $this->tierDefinitions())) {
            return false;
        }

        return ! $this->needsWeight() || ($this->weightKg !== null && $this->weightKg >= 0);
    }

    public function calculate(): void
    {
        if (! $this->canCalculate()) {
            return;
        }

        $quantity = max(1, min($this->quantity ?? 1, $this->service === 'boarding' ? 30 : 12));
        $pet = [
            'species' => $this->variant === 'cats' ? 'cat' : 'dog',
            'weight_kg' => $this->weightKg,
        ];
        $quote = app(BookingPricingCatalog::class)->quote($this->service, $this->variant, $this->tier, $pet, $quantity, 'pricing');
        $tier = $this->allTierDefinitions()[$this->tier] ?? [];
        $serviceLabel = $this->serviceOptions()[$this->service] ?? 'Selected service';
        $variantLabel = app(BookingPricingCatalog::class)->variantOptions($this->service, availableOnly: false, channel: 'pricing')[$this->variant ?? ''] ?? null;

        $params = array_filter([
            'service' => $this->service,
            'variant' => $this->variant,
            'tier' => $this->tier,
            'source' => 'pricing',
        ]);

        $this->result = [
            'status' => $quote['status'] ?? 'quote',
            'display' => $this->quoteDisplay($quote),
            'service' => implode(' · ', array_filter([$serviceLabel, $variantLabel])),
            'tier' => $tier['label'] ?? $this->tier,
            'features' => $tier['features'] ?? [],
            'href' => route('book', $params),
            'notice' => ($quote['status'] ?? null) === 'quote'
                ? 'Waggies will review the route or care details and confirm the final quote with you.'
                : 'This is an estimate or starting price. Waggies confirms final availability and pricing with you.',
        ];
        $this->step = 'result';
    }

    public function resetCalculator(): void
    {
        $this->service = null;
        $this->variant = null;
        $this->tier = null;
        $this->quantity = 1;
        $this->weightKg = null;
        $this->step = 'form';
        $this->result = [];
    }

    private function normaliseSelection(): void
    {
        if ($this->service && ! array_key_exists($this->service, $this->serviceOptions())) {
            $this->service = null;
            $this->variant = null;
            $this->tier = null;

            return;
        }

        if ($this->variant && ! array_key_exists($this->variant, $this->variantOptions())) {
            $this->variant = null;
            $this->tier = null;
        }

        if ($this->tier && ! array_key_exists($this->tier, $this->tierDefinitions())) {
            $this->tier = null;
        }
    }

    private function quoteDisplay(array $quote): string
    {
        if (($quote['status'] ?? null) === 'quote') {
            return 'Custom quote';
        }

        if (($quote['status'] ?? null) === 'needs_input') {
            return 'Complete the missing details';
        }

        $amount = number_format((int) ($quote['amount'] ?? 0));
        $maximum = number_format((int) ($quote['max_amount'] ?? $quote['amount'] ?? 0));

        return $amount === $maximum ? '₦'.$amount : '₦'.$amount.'–₦'.$maximum;
    }
};
?>

<div>
    @if($step === 'form')
        <div class="mt-10 rounded-2xl border border-primary/10 bg-white p-6 shadow-sm md:p-8">
            <div class="flex flex-col gap-6">
                <div>
                    <p class="text-sm font-semibold text-primary-dark">1. Choose a service</p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        @foreach($this->serviceOptions() as $key => $label)
                            @php $available = $this->serviceAvailable($key); @endphp
                            <button type="button" @if($available) wire:click="selectService('{{ $key }}')" @else disabled @endif class="flex min-h-16 items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ $service === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'hover:border-primary/40' }}">
                                <span><span class="block font-semibold text-primary-dark">{{ $label }}</span>@if(! $available)<span class="mt-1 block text-xs text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                @if($service === $key)<x-waggies.icon name="check-circle" variant="filled" size="20" class="shrink-0 text-primary" />@endif
                            </button>
                        @endforeach
                    </div>
                </div>

                @if($service)
                    @if($this->hasVariants())
                        <x-waggies.select id="pricing-variant" label="2. Who is this for?" wire:model.live="variant" :plain="true" required>
                            <option value="">Choose a pet type</option>
                            @foreach($this->variantOptions() as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                        </x-waggies.select>
                    @endif

                    @if($service === 'local-transport')
                        <div class="rounded-xl border border-primary/10 bg-surface-purple/45 p-4">
                            <p class="text-sm font-semibold text-primary-dark">Route details</p>
                            <div class="mt-4 grid gap-5 sm:grid-cols-2">
                                <x-waggies.field id="pricing-pickup" label="Pickup location"><input id="pricing-pickup" wire:model.live="transport.pickup" class="contact-input"></x-waggies.field>
                                <x-waggies.field id="pricing-dropoff" label="Drop-off location"><input id="pricing-dropoff" wire:model.live="transport.dropoff" class="contact-input"></x-waggies.field>
                                <x-waggies.field id="pricing-distance" label="Distance (km)"><input id="pricing-distance" wire:model.live="transport.distance_km" type="number" min="1" class="contact-input"></x-waggies.field>
                                <x-waggies.field id="pricing-pet-species" label="Pet species"><input id="pricing-pet-species" wire:model.live="transport.pet_species" class="contact-input"></x-waggies.field>
                                <x-waggies.field id="pricing-pet-count" label="Number of pets"><input id="pricing-pet-count" wire:model.live="transport.pet_count" type="number" min="1" class="contact-input"></x-waggies.field>
                                <x-waggies.select id="pricing-journey" label="Journey type" wire:model.live="transport.journey_type" :plain="true"><option value="one-way">One way</option><option value="return">Return</option></x-waggies.select>
                                @if($tier === 'airport')
                                    <x-waggies.field id="pricing-airport-details" label="Airport details"><input id="pricing-airport-details" wire:model.live="transport.airport_details" class="contact-input"></x-waggies.field>
                                @endif
                            </div>
                            <p class="mt-4 text-xs leading-relaxed text-primary-dark/60">Special handling, extra stops, waiting, urgency, and after-hours requests may require a confirmed quote.</p>
                        </div>
                    @endif

                    @if($this->tierDefinitions() && (! $this->hasVariants() || $variant))
                        <div>
                            <p class="text-sm font-semibold text-primary-dark">{{ $this->hasVariants() ? '3' : '2' }}. Choose a package</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach($this->tierDefinitions() as $key => $tier)
                                    <button type="button" wire:click="selectTier('{{ $key }}')" aria-pressed="{{ $this->tier === $key ? 'true' : 'false' }}" class="relative min-h-24 rounded-xl border-2 p-4 text-left transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ $this->tier === $key ? 'border-primary bg-surface-purple shadow-sm' : 'border-primary/10 hover:border-primary/40' }}">
                                        <span class="block pr-7 text-sm font-bold text-primary-dark">{{ $tier['label'] ?? $key }}</span>
                                        <span class="mt-1 block text-sm text-primary-dark/60">{{ $this->tierPriceLabel($tier) }}</span>
                                        @if($this->tier === $key)<span class="absolute right-3 top-3 text-primary"><x-waggies.icon name="check-circle" variant="filled" size="18" /></span>@endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @elseif($this->hasVariants() && ! $variant)
                        <p class="rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a pet type first to see the packages and size-aware prices for that pet.</p>
                    @endif

                    @if($this->needsWeight())
                        <x-waggies.field id="pricing-weight" label="Dog weight in kilograms" help="Dog grooming prices use weight bands. Cats do not need a size selection.">
                            <input id="pricing-weight" wire:model.live="weightKg" type="number" min="0" max="300" step="0.1" inputmode="decimal" class="contact-input">
                        </x-waggies.field>
                    @endif

                    @if(in_array($service, ['boarding', 'grooming'], true))
                        <x-waggies.field id="pricing-quantity" :label="$this->quantityLabel()">
                            <input id="pricing-quantity" wire:model.live="quantity" type="number" min="1" max="{{ $service === 'boarding' ? 30 : 12 }}" class="contact-input">
                        </x-waggies.field>
                    @endif

                    <div class="flex flex-col gap-3 border-t border-primary/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-relaxed text-primary-dark/55">Prices come from the same configuration used by booking. Final availability and quote confirmation come from Waggies.</p>
                        <x-waggies.button type="button" wire:click="calculate" wire:loading.attr="disabled" wire:target="calculate" :disabled="! $this->canCalculate()" class="w-full justify-center sm:w-auto">{{ $this->actionLabel() }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    </div>
                @else
                    <p class="rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a service to see the right pet type, package, size, or quantity fields. Nothing is preselected.</p>
                @endif
            </div>
        </div>
    @else
        <div class="mt-10 overflow-hidden rounded-2xl border-2 border-primary bg-white shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_1fr]">
                <div class="border-b border-primary/10 p-6 md:p-8 lg:border-b-0 lg:border-r">
                    <p class="mb-2 text-eyebrow text-primary-dark/55">YOUR SELECTION</p>
                    <p class="text-sm text-primary-dark/60">{{ $result['service'] ?? '' }}</p>
                    <p class="mb-6 text-sm text-primary-dark/50">{{ $result['tier'] ?? '' }}</p>
                    <h3 class="mb-4 flex items-center gap-2 font-serif text-lg font-bold text-primary-dark"><x-waggies.icon name="checklist" size="20" class="text-primary" />What's included</h3>
                    <ul class="space-y-2.5">
                        @foreach($result['features'] ?? [] as $feature)
                            <li class="flex items-start gap-2.5 text-sm text-primary-dark/80"><span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-success/15 text-success"><x-waggies.icon name="check" size="14" /></span><span>{{ is_string($feature) ? $feature : ($feature['label'] ?? '') }}</span></li>
                        @endforeach
                    </ul>
                    <button type="button" wire:click="resetCalculator" class="mt-6 inline-flex min-h-11 items-center gap-1 text-sm font-medium text-primary hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"><x-waggies.icon name="arrow-back" size="16" />Start over</button>
                </div>
                <div class="bg-surface-purple/40 p-6 md:p-8 lg:self-start">
                    <p class="mb-3 text-eyebrow text-primary-dark/55">PRICE SUMMARY</p>
                    <p class="mb-2 font-serif text-3xl font-bold text-primary-dark md:text-4xl">{{ $result['display'] ?? 'Custom quote' }}</p>
                    <p class="mb-5 text-xs leading-relaxed text-primary-dark/60">{{ $result['notice'] ?? '' }}</p>
                    <x-waggies.button href="{{ $result['href'] ?? route('book') }}" class="w-full justify-center">Request this service <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                </div>
            </div>
        </div>
    @endif
</div>
