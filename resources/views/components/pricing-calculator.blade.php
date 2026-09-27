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
    public ?int $quantity = 1;

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
        $this->service ??= $initialContext['service'] ?? null;
        $this->variant ??= $initialContext['variant'] ?? null;
        $this->source ??= $initialContext['source'] ?? null;
        $this->quantity = max(1, $this->quantity ?? 1);
        $this->normaliseSelection();
    }

    public function updatedService(): void
    {
        $this->variant = null;
        $this->size = null;
        $this->resetResult();
        $this->normaliseSelection();
    }

    public function updatedVariant(): void
    {
        $this->size = null;
        $this->resetResult();
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
        $this->resetResult();
        $this->normaliseSelection();
    }

    public function serviceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions(availableOnly: true, channel: 'pricing');
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

    public function needsSize(): bool
    {
        return app(BookingPricingCatalog::class)->requiresPetSize($this->service ?? '', $this->variant);
    }

    /**
     * @return array<string, array{label: string, examples: string|null, guidance: string|null, manual_review: bool}>
     */
    public function sizeOptions(): array
    {
        return app(BookingPricingCatalog::class)->sizeOptions($this->service ?? '', $this->variant);
    }

    public function quantityLabel(): string
    {
        return $this->service === 'boarding' ? 'Overnight stays' : 'Quantity';
    }

    public function canCalculate(): bool
    {
        if ($this->service === null || ! $this->serviceAvailable($this->service)) {
            return false;
        }

        if ($this->variant === null || ! array_key_exists($this->variant, $this->variantOptions())) {
            return false;
        }

        return ! $this->needsSize() || $this->size !== null;
    }

    public function calculate(): void
    {
        if (! $this->canCalculate()) {
            return;
        }

        $quantity = $this->service === 'boarding' ? max(1, (int) ($this->quantity ?? 1)) : 1;
        $species = $this->service === 'boarding' ? ($this->variant === 'cats' ? 'cat' : 'dog') : 'dog';
        $quote = app(BookingPricingCatalog::class)->quote($this->service, $this->variant, null, ['species' => $species, 'size' => $this->size], $quantity, 'pricing');
        $serviceLabel = $this->serviceOptions()[$this->service] ?? 'Selected service';
        $variantLabel = $this->allVariantOptions()[$this->variant] ?? null;
        $params = array_filter(['service' => $this->service, 'variant' => $this->variant, 'source' => $this->source ?: 'pricing']);

        $this->result = [
            'status' => $quote['status'] ?? 'quote',
            'display' => $this->quoteDisplay($quote),
            'service' => implode(' · ', array_filter([$serviceLabel, $variantLabel])),
            'href' => route('book', $params),
            'notice' => ($quote['status'] ?? null) === 'quote'
                ? ($quote['reason'] ?? 'Waggies will review the request and confirm the final quote with you.')
                : 'This is indicative guidance only. Waggies confirms availability and the final quote manually.',
        ];
        $this->step = 'result';
    }

    public function resetCalculator(): void
    {
        $this->service = null;
        $this->variant = null;
        $this->quantity = 1;
        $this->size = null;
        $this->resetResult();
    }

    private function normaliseSelection(): void
    {
        if ($this->service !== null && ! array_key_exists($this->service, $this->serviceOptions())) {
            $this->service = null;
            $this->variant = null;
            $this->size = null;

            return;
        }

        if ($this->variant !== null && ! array_key_exists($this->variant, $this->allVariantOptions())) {
            $this->variant = null;
            $this->size = null;
        }

        if ($this->size !== null && ! array_key_exists($this->size, $this->sizeOptions())) {
            $this->size = null;
        }
    }

    private function resetResult(): void
    {
        $this->step = 'form';
        $this->result = [];
    }

    private function quoteDisplay(array $quote): string
    {
        if (($quote['status'] ?? null) === 'quote') {
            return 'Staff review required';
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
    <div wire:loading wire:target="service,variant,size,quantity" class="mb-4 rounded-xl border border-primary/15 bg-surface-purple/45 px-4 py-3 text-sm font-medium text-primary-dark/70" role="status" aria-live="polite">Updating the guidance…</div>
    @if($step === 'form')
        <div class="mt-10 rounded-2xl border border-primary/10 bg-white p-6 shadow-sm md:p-8">
            <div class="flex flex-col gap-7">
                <div>
                    <p class="text-sm font-semibold text-primary-dark">1. Choose a service</p>
                    <div class="mt-3 grid gap-3 md:grid-cols-3">@foreach($this->serviceOptions() as $key => $label)<button type="button" wire:click="selectService('{{ $key }}')" class="flex min-h-16 items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ $service === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 hover:border-primary/40' }}"><span class="font-semibold text-primary-dark">{{ $label }}</span>@if($service === $key)<x-waggies.icon name="check-circle" variant="filled" size="20" class="shrink-0 text-primary" />@endif</button>@endforeach</div>
                </div>

                @if($service)
                    <x-waggies.select id="pricing-variant" label="2. Choose the request type" wire:model.live="variant" required><option value="">Choose an option</option>@foreach($this->allVariantOptions() as $key => $label)<option value="{{ $key }}" @selected($variant === $key)>{{ $label }}</option>@endforeach</x-waggies.select>

                    @if($this->needsSize())
                        <fieldset><legend class="text-sm font-semibold text-primary-dark">3. Choose the dog size <span class="text-danger" aria-hidden="true">*</span></legend><p class="mt-1 text-xs leading-relaxed text-primary-dark/60">This is customer guidance only. Select directly; Waggies does not ask for weight or calculate size automatically.</p><div class="mt-3 grid gap-3 sm:grid-cols-2">@foreach($this->sizeOptions() as $key => $option)<label class="cursor-pointer rounded-xl border p-4 transition-colors {{ $size === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 hover:border-primary/40' }}"><input type="radio" name="pricing-size" value="{{ $key }}" wire:model.live="size" class="sr-only"><span class="block font-semibold text-primary-dark">{{ $option['label'] }}</span><span class="mt-1 block text-xs leading-relaxed text-primary-dark/60">{{ $option['guidance'] }}</span></label>@endforeach</div></fieldset>
                    @endif

                    @if($service === 'boarding')<x-waggies.field id="pricing-quantity" :label="$this->quantityLabel()" help="There is no artificial 30-night maximum. Long stays remain subject to manual availability review."><input id="pricing-quantity" wire:model.live="quantity" type="number" min="1" class="contact-input"></x-waggies.field>@endif

                    <div class="flex flex-col gap-3 border-t border-primary/10 pt-5 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs leading-relaxed text-primary-dark/55">Guidance is indicative only. The staff-entered quote remains authoritative, including any discount or special-care charge.</p><x-waggies.button type="button" wire:click="calculate" wire:loading.attr="disabled" wire:target="calculate" :disabled="! $this->canCalculate()" class="w-full justify-center sm:w-auto">Show guidance <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button></div>
                @endif
            </div>
        </div>
    @else
        <div class="mt-10 rounded-2xl border border-primary/10 bg-white p-6 shadow-sm md:p-8" role="status"><p class="text-eyebrow">INDICATIVE GUIDANCE</p><h3 class="mt-2 font-serif text-2xl font-bold text-primary-dark">{{ $result['service'] ?? 'Selected service' }}</h3><p class="mt-5 font-serif text-4xl font-bold text-primary">{{ $result['display'] ?? 'Staff review required' }}</p><p class="mt-4 max-w-2xl text-sm leading-relaxed text-primary-dark/65">{{ $result['notice'] ?? 'Waggies confirms the final details manually.' }}</p><div class="mt-7 flex flex-col gap-3 sm:flex-row"><x-waggies.button href="{{ $result['href'] ?? route('book') }}">Submit Booking Request <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button><x-waggies.button type="button" variant="secondary" wire:click="resetCalculator">Start again</x-waggies.button></div></div>
    @endif
</div>
