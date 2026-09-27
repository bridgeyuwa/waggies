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
    public ?int $quantity = null;

    #[Url]
    public ?string $size = null;

    #[Url]
    public ?string $source = null;

    public string $step = 'form';

    /** @var array<string, mixed> */
    public array $result = [];

    /**
     * @param  array{service?: ?string, variant?: ?string, source?: ?string}  $initialContext
     */
    public function mount(array $initialContext = []): void
    {
        $service = $this->service ?: ($initialContext['service'] ?? null);

        $this->service = $service ?: null;
        $this->variant = $this->variant ?: ($initialContext['variant'] ?? null);
        $this->source = $this->source ?: ($initialContext['source'] ?? null);
        $this->quantity = $this->quantity ?: 1;
        $this->normaliseSelection();
    }

    public function updatedService(): void
    {
        $this->variant = null;
        $this->step = 'form';
        $this->result = [];
        $this->normaliseSelection();
    }

    public function updatedVariant(): void
    {
        $this->size = null;
        $this->step = 'form';
        $this->result = [];
        $this->normaliseSelection();
    }

    public function selectService(string $service): void
    {
        if (! array_key_exists($service, $this->serviceOptions()) || ! $this->serviceAvailable($service)) {
            return;
        }

        $this->service = $service;
        $this->variant = null;
        $this->size = null;
        $this->step = 'form';
        $this->result = [];
        $this->normaliseSelection();
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

    public function allVariantOptions(): array
    {
        return app(BookingPricingCatalog::class)->variantOptions($this->service, availableOnly: false, channel: 'pricing');
    }

    public function hasVariants(): bool
    {
        return app(BookingPricingCatalog::class)->variants($this->service ?? '', availableOnly: false, channel: 'pricing') !== [];
    }

    public function needsSize(): bool
    {
        return app(BookingPricingCatalog::class)->requiresPetSize($this->service ?? '', $this->variant);
    }

    /**
     * @return array<string, array{label: string, examples: string|null}>
     */
    public function sizeOptions(): array
    {
        return app(BookingPricingCatalog::class)->sizeOptions($this->service ?? '', $this->variant);
    }

    public function quantityLabel(): string
    {
        return 'Overnight stays (per pet per night)';
    }

    public function variantPricingNote(): ?string
    {
        $variant = app(BookingPricingCatalog::class)->variants($this->service ?? '', availableOnly: false, channel: 'pricing')[$this->variant ?? ''] ?? [];

        return $variant['pricing_note'] ?? null;
    }

    public function actionLabel(): string
    {
        $variant = app(BookingPricingCatalog::class)->variants($this->service ?? '', availableOnly: false, channel: 'pricing')[$this->variant ?? ''] ?? [];

        return ($variant['type'] ?? 'fixed') === 'quote' ? 'Request a quote' : 'Calculate estimate';
    }

    public function canCalculate(): bool
    {
        if (! $this->service || ! $this->serviceAvailable($this->service)) {
            return false;
        }

        if ($this->hasVariants() && ! $this->variant) {
            return false;
        }

        return ! $this->needsSize() || $this->size !== null;
    }

    public function calculate(): void
    {
        if (! $this->canCalculate()) {
            return;
        }

        $quantity = max(1, (int) ($this->quantity ?? 1));
        $pet = [
            'species' => $this->variant === 'cats' ? 'cat' : 'dog',
            'size' => $this->size,
        ];
        $quote = app(BookingPricingCatalog::class)->quote($this->service, $this->variant, null, $pet, $quantity, 'pricing');
        $variantDefinition = app(BookingPricingCatalog::class)->variants($this->service ?? '', availableOnly: false, channel: 'pricing')[$this->variant ?? ''] ?? config("waggies_pricing.services.{$this->service}", []);
        $serviceLabel = $this->serviceOptions()[$this->service] ?? 'Selected service';
        $variantLabel = app(BookingPricingCatalog::class)->variantOptions($this->service, availableOnly: false, channel: 'pricing')[$this->variant ?? ''] ?? null;

        $params = array_filter([
            'service' => $this->service,
            'variant' => $this->variant,
            'source' => 'pricing',
        ]);

        $this->result = [
            'status' => $quote['status'] ?? 'quote',
            'display' => $this->quoteDisplay($quote),
            'service' => implode(' · ', array_filter([$serviceLabel, $variantLabel])),
            'option' => $variantDefinition['label'] ?? 'Service option',
            'features' => $variantDefinition['features'] ?? [],
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
        $this->quantity = 1;
        $this->size = null;
        $this->step = 'form';
        $this->result = [];
    }

    private function normaliseSelection(): void
    {
        if ($this->service && ! array_key_exists($this->service, $this->serviceOptions())) {
            $this->service = null;
            $this->variant = null;

            return;
        }

        if ($this->variant && ! array_key_exists($this->variant, $this->allVariantOptions())) {
            $this->variant = null;
            $this->size = null;
        }

        if ($this->size && ! array_key_exists($this->size, $this->sizeOptions())) {
            $this->size = null;
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
    <div wire:loading wire:target="service,variant,size,quantity" class="mb-4 rounded-xl border border-primary/15 bg-surface-purple/45 px-4 py-3 text-sm font-medium text-primary-dark/70" role="status" aria-live="polite">
        Updating your estimate…
    </div>
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
                        <x-waggies.select id="pricing-variant" label="2. Who is this for?" wire:model.live="variant" required>
                            <option value="">Choose a pet type</option>
                            @foreach($this->allVariantOptions() as $key => $label)<option value="{{ $key }}" @selected($variant === $key) @disabled(! array_key_exists($key, $this->variantOptions()))>{{ $label }}{{ ! array_key_exists($key, $this->variantOptions()) ? ' — Temporarily unavailable' : '' }}</option>@endforeach
                        </x-waggies.select>
                    @endif

                    @if($this->hasVariants() && ! $variant)
                        <p class="rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a pet type first to see the service option and any guidance that applies to that pet.</p>
                    @endif

                    @if($this->variantPricingNote())
                        <p class="rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">{{ $this->variantPricingNote() }}</p>
                    @endif

                    @if($this->needsSize())
                        <fieldset aria-labelledby="pricing-size-heading">
                            <legend id="pricing-size-heading" class="text-sm font-semibold text-primary-dark">Dog size <span class="text-danger" aria-hidden="true">*</span></legend>
                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">Choose the closest size. You do not need to know your dog’s exact weight.</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-3">
                                @foreach($this->sizeOptions() as $key => $sizeOption)
                                    <label class="cursor-pointer rounded-xl border p-4 transition-colors {{ $size === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                        <input type="radio" name="pricing-size" value="{{ $key }}" wire:model.live="size" class="sr-only peer">
                                        <span class="block font-semibold text-primary-dark">{{ $sizeOption['label'] }}</span>
                                        @if($sizeOption['examples'])<span class="mt-1 block text-xs leading-relaxed text-primary-dark/60">{{ $sizeOption['examples'] }}</span>@endif
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endif

                    @if($service === 'boarding')
                        <x-waggies.field id="pricing-quantity" :label="$this->quantityLabel()">
                            <input id="pricing-quantity" wire:model.live="quantity" type="number" min="1" class="contact-input">
                        </x-waggies.field>
                    @endif

                    <div class="flex flex-col gap-3 border-t border-primary/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-relaxed text-primary-dark/55">Prices come from the same configuration used by booking. Final availability and quote confirmation come from Waggies.</p>
                        <x-waggies.button type="button" wire:click="calculate" wire:loading.attr="disabled" wire:target="calculate" :disabled="! $this->canCalculate()" class="w-full justify-center sm:w-auto">{{ $this->actionLabel() }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    </div>
                @else
                    <p class="rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a service to see the pet type, size, or overnight-stay fields that apply to it.</p>
                @endif
            </div>
        </div>
    @else
        <div class="mt-10 overflow-hidden rounded-2xl border-2 border-primary bg-white shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_1fr]">
                <div class="border-b border-primary/10 p-6 md:p-8 lg:border-b-0 lg:border-r">
                    <p class="mb-2 text-eyebrow text-primary-dark/55">YOUR SELECTION</p>
                    <p class="text-sm text-primary-dark/60">{{ $result['service'] ?? '' }}</p>
                    <p class="mb-6 text-sm text-primary-dark/50">{{ $result['option'] ?? '' }}</p>
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
                    <x-waggies.button :href="$result['href'] ?? route('book')" class="w-full justify-center">Request this service <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                </div>
            </div>
        </div>
    @endif
</div>
