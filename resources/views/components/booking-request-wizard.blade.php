<?php

use App\Actions\CreateBookingRequest;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestSchema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component
{
    public int $step = 1;

    public bool $submitted = false;

    public string $minimumDate = '';

    public string $draftContextKey = '';

    public ?string $source = null;

    public string $whatsappUrl = '#';

    /** @var array<int, array<string, mixed>> */
    public array $services = [];

    /** @var array<int, array<string, mixed>> */
    public array $pets = [];

    /** @var array<string, string|null> */
    public array $contact = [
        'name' => null,
        'email' => null,
        'phone' => null,
        'preferred_contact_method' => null,
    ];

    /**
     * @param  array{service?: ?string, variant?: ?string, tier?: ?string, source?: ?string, whatsappUrl?: string}  $initialContext
     */
    public function mount(array $initialContext = []): void
    {
        $serviceOptions = BookingRequestSchema::allServiceOptions();
        $service = $initialContext['service'] ?? null;
        $service = is_string($service) && array_key_exists($service, $serviceOptions) ? $service : null;
        $variantOptions = BookingRequestSchema::variantOptions($service);
        $variant = $initialContext['variant'] ?? null;
        $variant = is_string($variant) && array_key_exists($variant, $variantOptions) ? $variant : null;
        $tierOptions = BookingRequestSchema::tierOptions($service, $variant);
        $tier = $initialContext['tier'] ?? null;
        $tier = is_string($tier) && array_key_exists($tier, $tierOptions) ? $tier : null;

        $this->minimumDate = now()->toDateString();
        $this->draftContextKey = implode('|', [$service ?? '', $variant ?? '', $tier ?? '', $initialContext['source'] ?? '']);
        $this->source = $initialContext['source'] ?? null;
        $this->whatsappUrl = $initialContext['whatsappUrl'] ?? '#';
        $this->services = [$this->newService($service, $variant, $tier)];
        $this->pets = [$this->newPet()];
    }

    public function serviceChanged(int $index, ?string $service): void
    {
        $serviceOptions = BookingRequestSchema::serviceOptions();

        if ($service === null || ! array_key_exists($service, $serviceOptions)) {
            $this->services[$index] = $this->newService(null, null, null);
            $this->resetValidation();

            return;
        }

        $this->services[$index]['service_key'] = $service;
        $this->services[$index]['service_variant'] = null;
        $this->services[$index]['pricing_tier'] = null;
        $this->services[$index]['assigned_pet_ids'] = [];
        $this->services[$index]['requested_date'] = null;
        $this->services[$index]['requested_time'] = null;
        $this->services[$index]['location'] = null;
        $this->services[$index]['details'] = [];
        $this->resetValidation();
    }

    public function updatedStep(int|string $step): void
    {
        $this->step = max(1, min(5, (int) $step));
    }

    public function updated(string $property): void
    {
        if (Str::is('services.*.service_variant', $property)) {
            $parts = explode('.', $property);
            $index = (int) ($parts[1] ?? 0);
            $this->services[$index]['pricing_tier'] = null;
            $this->services[$index]['assigned_pet_ids'] = [];
        }

        $rules = $this->allRules();
        $ruleKey = collect(array_keys($rules))->first(static fn (string $key): bool => $key === $property || Str::is($key, $property));

        if ($ruleKey === null) {
            return;
        }

        $this->validateOnly($property, $rules);
    }

    public function variantChanged(int $index, ?string $variant): void
    {
        $service = (string) ($this->services[$index]['service_key'] ?? '');
        $variantOptions = BookingRequestSchema::variantOptions($service);
        $this->services[$index]['service_variant'] = array_key_exists((string) $variant, $variantOptions) ? $variant : null;
        $this->services[$index]['pricing_tier'] = null;
        $this->services[$index]['assigned_pet_ids'] = [];
        $this->resetValidation();
    }

    public function tierChanged(int $index, ?string $tier): void
    {
        $service = (string) ($this->services[$index]['service_key'] ?? '');
        $variant = $this->services[$index]['service_variant'] ?? null;
        $tierOptions = BookingRequestSchema::tierOptions($service, $variant);

        $this->services[$index]['pricing_tier'] = array_key_exists((string) $tier, $tierOptions) ? $tier : null;
        $this->resetValidation();
    }

    public function addService(): void
    {
        $this->services[] = $this->newService(null, null, null);
        $this->resetValidation();
    }

    public function removeService(int $index): void
    {
        if (count($this->services) <= 1) {
            return;
        }

        unset($this->services[$index]);
        $this->services = array_values($this->services);
        $this->resetValidation();
    }

    public function addPet(): void
    {
        $this->pets[] = $this->newPet();
    }

    public function removePet(int $index): void
    {
        if (count($this->pets) <= 1) {
            return;
        }

        unset($this->pets[$index]);
        $this->pets = array_values($this->pets);

        foreach ($this->services as &$service) {
            $assignedPetIds = [];

            foreach ($service['assigned_pet_ids'] ?? [] as $petId) {
                $petId = (int) $petId;

                if ($petId === $index) {
                    continue;
                }

                $assignedPetIds[] = $petId > $index ? $petId - 1 : $petId;
            }

            $service['assigned_pet_ids'] = array_values(array_unique($assignedPetIds));
        }
        unset($service);

        $this->resetValidation();
    }

    public function nextStep(): void
    {
        if ($this->step >= 5) {
            return;
        }

        $this->validate($this->rulesForStep($this->step));

        if ($this->step === 1) {
            $this->validateServiceAvailability();
        }

        if ($this->step === 3) {
            $this->validateAssignmentCompatibility();
        }

        $this->step++;
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function previousStep(): void
    {
        $this->resetValidation();
        $this->step = max(1, $this->step - 1);
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function goToStep(int $step): void
    {
        $this->resetValidation();
        $this->step = max(1, min(5, $step));
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function errorAnchor(string $key): string
    {
        $parts = explode('.', $key);

        return match ($parts[0] ?? null) {
            'services' => 'booking-'.($parts[1] ?? '0').'-'.(($parts[2] ?? null) === 'details' ? ($parts[3] ?? 'service') : ($parts[2] ?? 'service')),
            'pets' => 'booking-pet-'.($parts[1] ?? '0').'-'.($parts[2] ?? 'name'),
            'contact' => 'booking-contact-'.($parts[1] ?? 'name'),
            default => 'booking-'.str_replace(['.', '*'], '-', $key),
        };
    }

    public function serviceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions(availableOnly: false);
    }

    public function serviceAvailable(string $service): bool
    {
        return app(BookingPricingCatalog::class)->isAvailable(config("waggies_pricing.services.{$service}", []));
    }

    public function serviceLabel(?string $service): string
    {
        return $this->serviceOptions()[$service ?? ''] ?? 'Choose a service';
    }

    public function variantOptions(?string $service): array
    {
        return BookingRequestSchema::variantOptions($service);
    }

    public function tierDefinitions(?string $service, ?string $variant): array
    {
        return app(BookingPricingCatalog::class)->tiers($service, $variant, availableOnly: true);
    }

    public function tierPriceLabel(string $service, ?string $variant, array $tier): string
    {
        return app(BookingPricingCatalog::class)->priceLabel($service, $variant, $tier);
    }

    public function serviceFields(array $service): array
    {
        return BookingRequestSchema::serviceFields((string) ($service['service_key'] ?? ''), $service['pricing_tier'] ?? null);
    }

    public function fieldModel(int $index, array $field): string
    {
        $scope = $field['scope'] ?? 'details';

        return $scope === 'service'
            ? "services.{$index}.{$field['key']}"
            : "services.{$index}.details.{$field['key']}";
    }

    public function fieldVisible(array $field, array $service): bool
    {
        return ! isset($field['when_tier']) || $field['when_tier'] === ($service['pricing_tier'] ?? null);
    }

    public function serviceSummary(array $service): string
    {
        $variantLabel = BookingRequestSchema::variantOptions($service['service_key'] ?? null)[$service['service_variant'] ?? ''] ?? null;
        $tierLabel = BookingRequestSchema::tierOptions($service['service_key'] ?? null, $service['service_variant'] ?? null)[$service['pricing_tier'] ?? ''] ?? null;

        return implode(' · ', array_filter([$this->serviceLabel($service['service_key'] ?? null), $variantLabel, $tierLabel]));
    }

    public function scheduleSummary(array $service): string
    {
        $details = $service['details'] ?? [];
        $date = $service['service_key'] === 'boarding' ? ($details['check_in'] ?? null) : ($service['requested_date'] ?? null);
        $dateLabel = $date ? Carbon::parse($date)->format('D, M j, Y') : null;

        if ($service['service_key'] === 'boarding' && ! empty($details['check_out'])) {
            $dateLabel .= ' → '.Carbon::parse($details['check_out'])->format('D, M j, Y');
        }

        if ($service['service_key'] === 'local-transport') {
            return implode(' · ', array_filter([$dateLabel, $details['pickup'] ?? null, $details['dropoff'] ?? null]));
        }

        if ($service['service_key'] === 'relocation') {
            return implode(' · ', array_filter([$dateLabel, $details['origin_country'] ?? null, $details['destination_country'] ?? null]));
        }

        return $dateLabel ?: 'Dates not added yet';
    }

    public function serviceQuote(array $service): array
    {
        $pets = [];

        foreach ($service['assigned_pet_ids'] ?? [] as $petId) {
            if (isset($this->pets[(int) $petId])) {
                $pets[] = $this->pets[(int) $petId];
            }
        }

        return app(BookingPricingCatalog::class)->quoteForService($service, $pets);
    }

    public function quoteDisplay(array $quote): string
    {
        if (($quote['status'] ?? null) === 'quote') {
            return 'Quote required';
        }

        if (($quote['status'] ?? null) === 'needs_input') {
            return 'Complete details to estimate';
        }

        $amount = number_format((int) ($quote['amount'] ?? 0));
        $maximum = number_format((int) ($quote['max_amount'] ?? $quote['amount'] ?? 0));

        return $amount === $maximum ? '₦'.$amount : '₦'.$amount.'–₦'.$maximum;
    }

    public function petSpeciesLabel(?string $species): string
    {
        return [
            'dog' => 'Dog',
            'cat' => 'Cat',
            'other' => 'Other',
        ][$species] ?? 'Type not selected';
    }

    public function petCompatible(array $service, array $pet): bool
    {
        if ($service['service_key'] === 'training') {
            return $pet['species'] === 'dog';
        }

        $variant = $service['service_variant'] ?? null;

        if ($variant === 'dogs') {
            return $pet['species'] === 'dog';
        }

        if ($variant === 'cats') {
            return $pet['species'] === 'cat';
        }

        if ($variant === 'exotic') {
            return $pet['species'] === 'other';
        }

        return true;
    }

    public function petAssignedTo(int $petIndex): string
    {
        $labels = [];

        foreach ($this->services as $service) {
            if (in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true)) {
                $labels[] = $this->serviceLabel($service['service_key'] ?? null);
            }
        }

        return implode(', ', $labels);
    }

    public function submit(CreateBookingRequest $createBookingRequest): void
    {
        $this->validate($this->allRules());
        $this->validateServiceAvailability();
        $this->validateAssignmentCompatibility();

        $createBookingRequest->handle([
            'contact' => $this->contact,
            'pets' => $this->pets,
            'services' => $this->services,
            'source' => $this->source,
            'context' => [
                'submitted_from' => 'livewire-booking-wizard',
            ],
        ]);

        $this->submitted = true;
        $this->dispatch('booking-request-submitted');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function allRules(): array
    {
        return [
            ...$this->rulesForStep(1),
            ...$this->rulesForStep(2),
            ...$this->rulesForStep(3),
            ...$this->rulesForStep(4),
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => $this->serviceRules(),
            2 => $this->petRules(),
            3 => [
                ...$this->assignmentRules(),
                ...$this->serviceDetailRules(),
            ],
            4 => [
                'contact.name' => ['required', 'string', 'max:120'],
                'contact.email' => ['required', 'email', 'max:255'],
                'contact.phone' => ['required', 'string', 'max:40'],
                'contact.preferred_contact_method' => ['nullable', Rule::in(['phone', 'email', 'whatsapp'])],
            ],
            default => [],
        };
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function serviceRules(): array
    {
        $rules = [
            'services' => ['required', 'array', 'min:1', 'max:6'],
            'services.*.service_key' => ['required', Rule::in(array_keys(BookingRequestSchema::allServiceOptions()))],
        ];

        foreach ($this->services as $index => $service) {
            $variantOptions = BookingRequestSchema::variantOptions($service['service_key'] ?? null);
            $tierOptions = BookingRequestSchema::tierOptions($service['service_key'] ?? null, $service['service_variant'] ?? null);

            $rules["services.{$index}.service_variant"] = [
                empty($variantOptions) ? 'nullable' : 'required',
                Rule::in(array_keys($variantOptions)),
            ];
            $rules["services.{$index}.pricing_tier"] = [
                empty($tierOptions) ? 'nullable' : 'required',
                Rule::in(array_keys($tierOptions)),
            ];
        }

        return $rules;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function petRules(): array
    {
        $rules = [
            'pets' => ['required', 'array', 'min:1', 'max:8'],
            'pets.*.name' => ['required', 'string', 'max:80'],
            'pets.*.species' => ['required', Rule::in(['dog', 'cat', 'other'])],
            'pets.*.weight_kg' => ['nullable', 'numeric', 'min:0', 'max:300'],
            'pets.*.breed' => ['nullable', 'string', 'max:120'],
            'pets.*.age' => ['nullable', 'string', 'max:40'],
            'pets.*.sex' => ['nullable', Rule::in(['female', 'male', 'unknown'])],
            'pets.*.notes' => ['nullable', 'string', 'max:1000'],
            'pets.*.details.other_description' => ['nullable', 'string', 'max:2000'],
        ];

        foreach ($this->pets as $index => $pet) {
            $rules["pets.{$index}.details.other_description"] = [
                $pet['species'] === 'other' ? 'required' : 'nullable',
                'string',
                'max:2000',
            ];
        }

        return $rules;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function assignmentRules(): array
    {
        $rules = [];

        foreach ($this->services as $index => $service) {
            $rules["services.{$index}.assigned_pet_ids"] = ['required', 'array', 'min:1'];
            $rules["services.{$index}.assigned_pet_ids.*"] = ['integer', Rule::in(array_keys($this->pets))];
        }

        foreach ($this->services as $serviceIndex => $service) {
            if (($service['service_key'] ?? null) !== 'grooming' || ($service['service_variant'] ?? null) !== 'dogs') {
                continue;
            }

            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                if (($this->pets[(int) $petIndex]['species'] ?? null) === 'dog') {
                    $rules["pets.{$petIndex}.weight_kg"] = ['required', 'numeric', 'min:0', 'max:300'];
                }
            }
        }

        return $rules;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function serviceDetailRules(): array
    {
        $rules = [];

        foreach ($this->services as $index => $service) {
            foreach ($this->serviceFields($service) as $field) {
                if (! $this->fieldVisible($field, $service)) {
                    continue;
                }

                $model = $this->fieldModel($index, $field);
                $fieldRules = [$field['required'] ? 'required' : 'nullable'];

                if ($field['type'] === 'date') {
                    $fieldRules = [...$fieldRules, 'date_format:Y-m-d', 'after_or_equal:today'];

                    if ($field['key'] === 'check_out') {
                        $fieldRules[] = "after:services.{$index}.details.check_in";
                    }
                } elseif ($field['type'] === 'select') {
                    $fieldRules[] = Rule::in(array_keys($field['options'] ?? []));
                } else {
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:2000';
                }

                $rules[$model] = $fieldRules;
            }
        }

        return $rules;
    }

    private function validateAssignmentCompatibility(): void
    {
        $messages = [];

        foreach ($this->services as $serviceIndex => $service) {
            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                if (! isset($this->pets[(int) $petIndex]) || $this->petCompatible($service, $this->pets[(int) $petIndex])) {
                    continue;
                }

                $messages["services.{$serviceIndex}.assigned_pet_ids"][] = 'Choose a pet that matches this service.';
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function validateServiceAvailability(): void
    {
        $messages = [];

        foreach ($this->services as $index => $service) {
            if ($this->serviceAvailable((string) ($service['service_key'] ?? ''))) {
                continue;
            }

            $messages["services.{$index}.service_key"][] = 'This service is temporarily unavailable. Choose another service.';
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function newService(?string $service, ?string $variant, ?string $tier): array
    {
        return [
            'service_key' => $service,
            'service_variant' => $variant,
            'pricing_tier' => $tier,
            'assigned_pet_ids' => [],
            'requested_date' => null,
            'requested_time' => null,
            'location' => null,
            'details' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function newPet(): array
    {
        return [
            'name' => null,
            'species' => null,
            'weight_kg' => null,
            'breed' => null,
            'age' => null,
            'sex' => null,
            'notes' => null,
            'details' => [],
        ];
    }
};
?>

<div data-booking-draft="waggies-booking-request-v2" data-booking-context="{{ $draftContextKey }}" data-booking-draft-label="Booking request">
    @if($submitted)
        <div class="flex flex-col gap-5" role="status" tabindex="-1" data-booking-success>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-light text-success">
                <x-waggies.icon name="check-circle" variant="filled" size="28" />
            </div>
            <div>
                <h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark sm:text-3xl">Your request was received</h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-primary-dark/70">Our team will review each service, pet assignment, and date, then contact you to confirm the next steps. Your requested dates are not reserved until Waggies confirms them.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <x-waggies.button href="{{ $this->whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                    Continue on WhatsApp <x-waggies.icon name="arrow-forward" size="16" />
                </x-waggies.button>
                <x-waggies.button href="{{ route('book') }}" variant="secondary" class="w-full sm:w-auto">Send another request</x-waggies.button>
            </div>
        </div>
    @else
        @php
            $stepHeadings = [
                1 => ['eyebrow' => 'STEP 1 OF 5', 'title' => 'What service do you need?', 'description' => 'Choose every service you are considering. Nothing is preselected.'],
                2 => ['eyebrow' => 'STEP 2 OF 5', 'title' => 'Tell us about your pet'.(count($pets) > 1 ? 's' : ''), 'description' => 'Add each pet once. We will match them to the services in the next step.'],
                3 => ['eyebrow' => 'STEP 3 OF 5', 'title' => 'Match pets and add service details', 'description' => 'Choose which pet receives each service, then add only the details that service needs.'],
                4 => ['eyebrow' => 'STEP 4 OF 5', 'title' => 'How should we contact you?', 'description' => 'Give us enough information to confirm availability and clarify anything important.'],
                5 => ['eyebrow' => 'STEP 5 OF 5', 'title' => 'Review your request', 'description' => 'Every service, pet assignment, date, and price input is shown before you send it.'],
            ][$step];
            $progressLabels = ['Services', 'Pets', 'Match & details', 'Contact', 'Review'];
        @endphp

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-12">
            <div class="min-w-0">
                <div class="mb-7 flex flex-col gap-2">
                    <p class="text-eyebrow text-primary">{{ $stepHeadings['eyebrow'] }}</p>
                    <h2 id="booking-form-title" data-booking-step-heading tabindex="-1" class="font-serif text-2xl font-bold text-primary-dark focus:outline-none sm:text-3xl">{{ $stepHeadings['title'] }}</h2>
                    <p class="max-w-2xl text-sm leading-relaxed text-primary-dark/60">{{ $stepHeadings['description'] }}</p>
                    <p class="max-w-2xl text-sm font-medium leading-relaxed text-primary-dark/75">This is a request, not a confirmed booking. It does not reserve a slot or confirm an appointment.</p>
                </div>

                <nav class="mb-8" aria-label="Request progress">
                    <ol class="grid grid-cols-5 gap-1.5 sm:gap-3">
                        @foreach($progressLabels as $progressIndex => $label)
                            @php $progressStep = $progressIndex + 1; @endphp
                            <li class="min-w-0">
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    @if($step > $progressStep)
                                        <button type="button" wire:click="goToStep({{ $progressStep }})" aria-label="Edit {{ $label }}" class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary text-white transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                            <x-waggies.icon name="check" size="16" />
                                        </button>
                                    @elseif($step === $progressStep)
                                        <span aria-current="step" class="flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-secondary text-sm font-bold text-primary-dark">{{ $progressStep }}</span>
                                    @else
                                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full border border-primary/20 bg-white text-sm font-semibold text-primary-dark/45">{{ $progressStep }}</span>
                                    @endif
                                    @if($progressStep < 5)
                                        <span class="h-px min-w-1 flex-1 bg-primary/15" aria-hidden="true"></span>
                                    @endif
                                </div>
                                <span class="mt-2 block text-[0.68rem] font-semibold leading-tight {{ $step >= $progressStep ? 'text-primary-dark' : 'text-primary-dark/45' }}">{{ $label }}</span>
                            </li>
                        @endforeach
                    </ol>
                </nav>

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-error/30 bg-error-light p-4 text-sm text-primary-dark" role="alert">
                        <p class="font-semibold">Please check the highlighted details before continuing.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form wire:submit="submit" novalidate>
                    @if($step === 1)
                        <fieldset class="flex flex-col gap-5">
                            <legend class="sr-only">Services</legend>
                            @foreach($services as $index => $service)
                                <div wire:key="booking-service-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">SERVICE {{ $index + 1 }}</p>
                                            <h3 class="mt-1 font-serif text-xl font-bold text-primary-dark">Choose a service</h3>
                                        </div>
                                        @if(count($services) > 1)
                                            <button type="button" wire:click="removeService({{ $index }})" class="min-h-11 shrink-0 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                        @endif
                                    </div>

                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        @foreach($this->serviceOptions() as $serviceKey => $serviceLabel)
                                            @php $available = $this->serviceAvailable($serviceKey); @endphp
                                            <button type="button" @if($available) wire:click="serviceChanged({{ $index }}, '{{ $serviceKey }}')" @else disabled @endif class="flex min-h-16 items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ $service['service_key'] === $serviceKey ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : '' }}">
                                                <span>
                                                    <span class="block font-semibold text-primary-dark">{{ $serviceLabel }}</span>
                                                    @if(! $available)
                                                        <span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>
                                                    @endif
                                                </span>
                                                @if($service['service_key'] === $serviceKey)
                                                    <x-waggies.icon name="check-circle" variant="filled" size="20" class="shrink-0 text-primary" />
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>

                                    @if($service['service_key'])
                                        @php
                                            $variantOptions = $this->variantOptions($service['service_key']);
                                            $tierDefinitions = $this->tierDefinitions($service['service_key'], $service['service_variant']);
                                        @endphp
                                        <div class="mt-6 grid gap-5 border-t border-primary/10 pt-5 sm:grid-cols-2">
                                            @if($variantOptions)
                                                <x-waggies.select id="booking-service-{{ $index }}-variant" label="Who is this service for?" wire:model.live="services.{{ $index }}.service_variant" :error="$errors->first('services.'.$index.'.service_variant')" :plain="true" required>
                                                    <option value="">Choose a pet type</option>
                                                    @foreach($variantOptions as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </x-waggies.select>
                                            @endif

                                            @if($tierDefinitions && (! $variantOptions || $service['service_variant']))
                                                <x-waggies.select id="booking-service-{{ $index }}-tier" label="Choose a package" wire:model.live="services.{{ $index }}.pricing_tier" :error="$errors->first('services.'.$index.'.pricing_tier')" :plain="true" required>
                                                    <option value="">Choose a package</option>
                                                    @foreach($tierDefinitions as $key => $tier)
                                                        <option value="{{ $key }}">{{ $tier['label'] ?? Str::headline($key) }} — {{ $this->tierPriceLabel($service['service_key'], $service['service_variant'], $tier) }}</option>
                                                    @endforeach
                                                </x-waggies.select>
                                            @elseif($variantOptions && ! $service['service_variant'])
                                                <p class="self-end rounded-xl bg-surface-purple/55 p-3 text-sm leading-relaxed text-primary-dark/65">Choose a pet type first to see the packages and prices for that pet.</p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="mt-5 rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a service above to see the package options and details it needs.</p>
                                    @endif
                                </div>
                            @endforeach

                            <button type="button" wire:click="addService" class="inline-flex min-h-12 w-fit items-center gap-2 rounded-xl border border-primary/25 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                <span aria-hidden="true" class="text-lg leading-none">+</span> Add another service
                            </button>
                        </fieldset>
                    @endif

                    @if($step === 2)
                        <fieldset class="flex flex-col gap-6">
                            <legend class="sr-only">Pet details</legend>
                            @foreach($pets as $index => $pet)
                                <div wire:key="booking-pet-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">PET {{ $index + 1 }}</p>
                                            <h3 class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $pet['name'] ? 'About '.$pet['name'] : 'Add a pet' }}</h3>
                                        </div>
                                        @if(count($pets) > 1)
                                            <button type="button" wire:click="removePet({{ $index }})" class="min-h-11 shrink-0 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                        @endif
                                    </div>

                                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                        <x-waggies.field id="booking-pet-{{ $index }}-name" label="Pet name" :error="$errors->first('pets.'.$index.'.name')" required>
                                            <input id="booking-pet-{{ $index }}-name" wire:model.live.blur="pets.{{ $index }}.name" type="text" maxlength="80" autocomplete="off" class="contact-input">
                                        </x-waggies.field>
                                        <x-waggies.select id="booking-pet-{{ $index }}-species" label="Pet type" wire:model.live="pets.{{ $index }}.species" :error="$errors->first('pets.'.$index.'.species')" :plain="true" required>
                                            <option value="">Choose a type</option>
                                            @foreach(['dog' => 'Dog', 'cat' => 'Cat', 'other' => 'Other'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </x-waggies.select>
                                        @if($pet['species'] === 'dog')
                                            <x-waggies.field id="booking-pet-{{ $index }}-weight" label="Weight in kilograms" :error="$errors->first('pets.'.$index.'.weight_kg')" help="Needed to estimate dog grooming prices. Leave blank if you do not know it yet.">
                                                <input id="booking-pet-{{ $index }}-weight" wire:model.live.blur="pets.{{ $index }}.weight_kg" type="number" min="0" max="300" step="0.1" inputmode="decimal" class="contact-input">
                                            </x-waggies.field>
                                        @endif
                                    </div>

                                    @if($pet['species'] === 'other')
                                        <div class="mt-5 rounded-xl border border-primary/10 bg-surface-purple/45 p-4">
                                            <x-waggies.field id="booking-pet-{{ $index }}-other" label="What kind of pet is this?" :error="$errors->first('pets.'.$index.'.details.other_description')" help="We do not use a fixed exotic-pet list. A free-form description helps us prepare a quote." required>
                                                <textarea id="booking-pet-{{ $index }}-other" wire:model.live.blur="pets.{{ $index }}.details.other_description" rows="3" maxlength="2000" class="contact-input resize-y"></textarea>
                                            </x-waggies.field>
                                        </div>
                                    @endif

                                    <div class="mt-6 border-t border-primary/10 pt-5">
                                        <p class="text-sm font-semibold text-primary-dark">Optional details about this pet</p>
                                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <x-waggies.field id="booking-pet-{{ $index }}-breed" label="Breed" :error="$errors->first('pets.'.$index.'.breed')" help="Optional">
                                                <input id="booking-pet-{{ $index }}-breed" wire:model.live.blur="pets.{{ $index }}.breed" type="text" maxlength="120" class="contact-input">
                                            </x-waggies.field>
                                            <x-waggies.field id="booking-pet-{{ $index }}-age" label="Age or life stage" :error="$errors->first('pets.'.$index.'.age')" help="Optional">
                                                <input id="booking-pet-{{ $index }}-age" wire:model.live.blur="pets.{{ $index }}.age" type="text" maxlength="40" placeholder="For example: 3 years" class="contact-input">
                                            </x-waggies.field>
                                        <x-waggies.select id="booking-pet-{{ $index }}-sex" label="Sex" wire:model.live="pets.{{ $index }}.sex" :error="$errors->first('pets.'.$index.'.sex')" :plain="true">
                                                <option value="">Not specified</option>
                                                <option value="female">Female</option>
                                                <option value="male">Male</option>
                                                <option value="unknown">Prefer not to say</option>
                                            </x-waggies.select>
                                            <x-waggies.field id="booking-pet-{{ $index }}-notes" label="Pet notes" :error="$errors->first('pets.'.$index.'.notes')" help="Optional. Share temperament, routines, or care notes." class="sm:col-span-2">
                                                <textarea id="booking-pet-{{ $index }}-notes" wire:model.live.blur="pets.{{ $index }}.notes" rows="3" maxlength="1000" class="contact-input resize-y"></textarea>
                                            </x-waggies.field>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addPet" class="inline-flex min-h-12 w-fit items-center gap-2 rounded-xl border border-primary/25 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                <span aria-hidden="true" class="text-lg leading-none">+</span> Add another pet
                            </button>
                        </fieldset>
                    @endif

                    @if($step === 3)
                        <fieldset class="flex flex-col gap-6">
                            <legend class="sr-only">Match pets and service details</legend>
                            @foreach($services as $index => $service)
                                @php $fields = $this->serviceFields($service); @endphp
                                <section wire:key="booking-service-details-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="booking-service-details-heading-{{ $index }}">
                                    <div>
                                        <p class="text-eyebrow text-primary-dark/50">SERVICE {{ $index + 1 }}</p>
                                        <h3 id="booking-service-details-heading-{{ $index }}" class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h3>
                                        <p class="mt-1 text-sm text-primary-dark/60">Assign at least one pet to this service.</p>
                                    </div>

                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        @foreach($pets as $petIndex => $pet)
                                            @php $compatible = $this->petCompatible($service, $pet); @endphp
                                            <label class="flex min-h-16 items-center gap-3 rounded-xl border p-4 transition-colors {{ in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true) ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15' }} {{ ! $compatible ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:border-primary/40' }}">
                                                <input type="checkbox" value="{{ $petIndex }}" wire:model.live="services.{{ $index }}.assigned_pet_ids" @disabled(! $compatible) class="size-5 rounded border-primary/30 text-primary focus:ring-primary">
                                                <span class="min-w-0">
                                                    <span class="block font-semibold text-primary-dark">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }}</span>
                                                    <span class="mt-1 block text-xs text-primary-dark/60">{{ $this->petSpeciesLabel($pet['species'] ?? null) }}{{ ! $compatible ? ' · Does not match this service' : '' }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('services.'.$index.'.assigned_pet_ids') <p class="mt-3 text-sm text-error">{{ $message }}</p> @enderror

                                    @if($service['service_key'])
                                        <div class="mt-6 border-t border-primary/10 pt-5">
                                            <p class="text-sm font-semibold text-primary-dark">Details for this service</p>
                                            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                                @foreach($fields as $field)
                                                    @continue(! $this->fieldVisible($field, $service))
                                                    @php $model = $this->fieldModel($index, $field); $fieldId = 'booking-'.$index.'-'.$field['key']; @endphp
                                                    @if($field['type'] === 'textarea')
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['placeholder'] ?? null" :required="$field['required']" class="sm:col-span-2">
                                                            <textarea id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" rows="3" maxlength="2000" class="contact-input resize-y"></textarea>
                                                        </x-waggies.field>
                                                    @elseif($field['type'] === 'select')
                                                        <x-waggies.select :id="$fieldId" :label="$field['label']" wire:model.live="{{ $model }}" :error="$errors->first($model)" :required="$field['required']" :plain="true">
                                                            <option value="">Choose an option</option>
                                                            @foreach($field['options'] as $key => $label)
                                                                <option value="{{ $key }}">{{ $label }}</option>
                                                            @endforeach
                                                        </x-waggies.select>
                                                    @else
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['help'] ?? null" :required="$field['required']">
                                                            <input id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" type="{{ $field['type'] }}" @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif class="contact-input">
                                                        </x-waggies.field>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @php $quote = $this->serviceQuote($service); @endphp
                                    <div class="mt-6 rounded-xl bg-surface-purple/55 p-4" aria-live="polite">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <p class="text-sm font-semibold text-primary-dark">Current estimate</p>
                                            <p class="font-semibold text-primary-dark">{{ $this->quoteDisplay($quote) }}</p>
                                        </div>
                                        @if(($quote['status'] ?? null) === 'needs_input')
                                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">{{ $quote['reason'] ?? 'Complete the assigned pets and service details to see an estimate.' }}</p>
                                        @elseif(($quote['discount']['percentage'] ?? 0) > 0)
                                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">Includes {{ $quote['discount']['percentage'] }}% multiple-pet boarding discount.</p>
                                        @else
                                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">This is an estimate or starting price. Waggies confirms final availability and pricing with you.</p>
                                        @endif
                                    </div>
                                </section>
                            @endforeach
                        </fieldset>
                    @endif

                    @if($step === 4)
                        <fieldset class="flex max-w-xl flex-col gap-5">
                            <legend class="sr-only">Contact information</legend>
                            <x-waggies.field id="booking-contact-name" label="Your name" :error="$errors->first('contact.name')" required>
                                <input id="booking-contact-name" wire:model.live.blur="contact.name" type="text" autocomplete="name" maxlength="120" class="contact-input">
                            </x-waggies.field>
                            <x-waggies.field id="booking-contact-email" label="Email address" :error="$errors->first('contact.email')" required>
                                <input id="booking-contact-email" wire:model.live.blur="contact.email" type="email" autocomplete="email" maxlength="255" class="contact-input">
                            </x-waggies.field>
                            <x-waggies.field id="booking-contact-phone" label="Phone or WhatsApp number" :error="$errors->first('contact.phone')" required>
                                <input id="booking-contact-phone" wire:model.live.blur="contact.phone" type="tel" autocomplete="tel" maxlength="40" class="contact-input">
                            </x-waggies.field>
                            <x-waggies.select id="booking-contact-method" label="Preferred contact method" wire:model.live="contact.preferred_contact_method" :error="$errors->first('contact.preferred_contact_method')" :plain="true">
                                <option value="">No preference</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </x-waggies.select>
                        </fieldset>
                    @endif

                    @if($step === 5)
                        <section class="flex flex-col gap-6" aria-labelledby="booking-review-heading">
                            <h3 id="booking-review-heading" class="sr-only">Review your request</h3>
                            @foreach($services as $index => $service)
                                @php $quote = $this->serviceQuote($service); @endphp
                                <article class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">SERVICE {{ $index + 1 }}</p>
                                            <h4 class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h4>
                                            <p class="mt-1 text-sm text-primary-dark/60">{{ $this->scheduleSummary($service) }}</p>
                                        </div>
                                        <button type="button" wire:click="goToStep(3)" class="shrink-0 text-sm font-semibold text-primary underline underline-offset-4">Edit</button>
                                    </div>
                                    <div class="mt-5 grid gap-4 border-t border-primary/10 pt-5 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary-dark/50">Assigned pets</p>
                                            <ul class="mt-2 space-y-1 text-sm text-primary-dark/75">
                                                @foreach($service['assigned_pet_ids'] ?? [] as $petIndex)
                                                    <li>{{ $pets[(int) $petIndex]['name'] ?? 'Pet '.((int) $petIndex + 1) }} · {{ $this->petSpeciesLabel($pets[(int) $petIndex]['species'] ?? null) }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary-dark/50">Estimate</p>
                                            <p class="mt-2 text-sm font-semibold text-primary-dark">{{ $this->quoteDisplay($quote) }}</p>
                                        </div>
                                    </div>
                                    @if(($service['details'] ?? []) !== [])
                                        <dl class="mt-5 grid gap-3 border-t border-primary/10 pt-5 text-sm sm:grid-cols-2">
                                            @foreach($service['details'] as $key => $value)
                                                @if($value)
                                                    <div>
                                                        <dt class="font-semibold text-primary-dark">{{ Str::headline($key) }}</dt>
                                                        <dd class="mt-1 text-primary-dark/70">{{ $value }}</dd>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </dl>
                                    @endif
                                </article>
                            @endforeach

                            <section class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-4"><h4 class="font-serif text-xl font-bold text-primary-dark">Pets</h4><button type="button" wire:click="goToStep(2)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <ul class="mt-4 grid gap-4 text-sm text-primary-dark/75 sm:grid-cols-2">
                                    @foreach($pets as $pet)
                                        <li class="rounded-xl bg-surface-purple/45 p-4">
                                            <p class="font-semibold text-primary-dark">{{ $pet['name'] ?: 'Unnamed pet' }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</p>
                                            @if($pet['weight_kg'] || $pet['breed'] || $pet['age'] || $pet['sex'])
                                                <p class="mt-1">{{ implode(' · ', array_filter([$pet['weight_kg'] ? $pet['weight_kg'].'kg' : null, $pet['breed'], $pet['age'], $pet['sex'] ? ucfirst($pet['sex']) : null])) }}</p>
                                            @endif
                                            @if($pet['notes'] || ($pet['details']['other_description'] ?? null))<p class="mt-2">{{ $pet['notes'] ?: $pet['details']['other_description'] }}</p>@endif
                                        </li>
                                    @endforeach
                                </ul>
                            </section>

                            <section class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-4"><h4 class="font-serif text-xl font-bold text-primary-dark">Contact</h4><button type="button" wire:click="goToStep(4)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <p class="mt-4 text-sm text-primary-dark/75">{{ $contact['name'] ?: 'Name not added' }} · {{ $contact['email'] ?: 'Email not added' }} · {{ $contact['phone'] ?: 'Phone not added' }}</p>
                                @if($contact['preferred_contact_method'])<p class="mt-1 text-sm text-primary-dark/60">Preferred contact: {{ ucfirst($contact['preferred_contact_method']) }}</p>@endif
                            </section>
                        </section>
                    @endif

                    <div class="mt-8 flex flex-col gap-3 border-t border-primary/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="max-w-sm text-xs leading-relaxed text-primary-dark/50">Submitting sends a request to Waggies. It does not reserve a slot or confirm an appointment.</p>
                        <div class="flex flex-col-reverse gap-3 sm:flex-row">
                            @if($step > 1)
                                <x-waggies.button type="button" variant="secondary" wire:click="previousStep" wire:loading.attr="disabled" wire:target="previousStep" class="w-full sm:w-auto">Back</x-waggies.button>
                            @endif
                            @if($step < 5)
                                <x-waggies.button type="button" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" class="w-full sm:w-auto">Continue <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                            @else
                                <x-waggies.button type="submit" wire:loading.attr="disabled" wire:target="submit" class="w-full sm:w-auto">
                                    <span wire:loading.remove wire:target="submit">Send request</span>
                                    <span wire:loading wire:target="submit">Sending request...</span>
                                    <x-waggies.icon name="arrow-forward" size="16" />
                                </x-waggies.button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <aside class="hidden min-w-0 flex-col gap-5 lg:sticky lg:top-24 lg:flex" aria-label="Your request and booking reassurance">
                <section class="rounded-2xl border border-primary/10 bg-surface-purple/40 p-5" aria-labelledby="booking-summary-heading">
                    <div class="flex items-start justify-between gap-4">
                        <h3 id="booking-summary-heading" class="font-serif text-xl font-bold text-primary-dark">Your request</h3>
                        <span class="text-label shrink-0 text-primary-dark/50">STEP {{ $step }} OF 5</span>
                    </div>
                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">Nothing is charged yet. We will confirm availability with you.</p>
                    <div class="mt-5 divide-y divide-primary/10 text-sm">
                        @foreach($services as $index => $service)
                            <div class="py-4 first:pt-0">
                                <div class="flex items-center justify-between gap-3"><p class="font-semibold text-primary-dark">Service {{ $index + 1 }}</p><button type="button" wire:click="goToStep(1)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <p class="mt-1 font-medium text-primary-dark/80">{{ $this->serviceSummary($service) }}</p>
                                <p class="mt-1 text-primary-dark/60">{{ $this->scheduleSummary($service) }}</p>
                            </div>
                        @endforeach
                        <div class="py-4"><div class="flex items-center justify-between gap-3"><p class="font-semibold text-primary-dark">Pets</p><button type="button" wire:click="goToStep(2)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button></div><ul class="mt-1 space-y-1 text-primary-dark/60">@foreach($pets as $pet)<li>{{ $pet['name'] ?: 'Pet '.$loop->iteration }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</li>@endforeach</ul></div>
                        <div class="pt-4"><div class="flex items-center justify-between gap-3"><p class="font-semibold text-primary-dark">Contact</p><button type="button" wire:click="goToStep(4)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button></div><p class="mt-1 text-primary-dark/60">{{ $contact['name'] ?: 'Contact details not added yet' }}</p></div>
                    </div>
                </section>
                <section class="rounded-2xl bg-primary-dark p-5 text-white shadow-sm" aria-labelledby="booking-next-heading">
                    <p id="booking-next-heading" class="text-eyebrow mb-3 text-secondary">WHAT HAPPENS NEXT</p>
                    <ol class="flex flex-col gap-3">
                        <li class="flex items-start gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">1</span><span class="pt-1 text-sm leading-relaxed text-white/80">We receive and review your request.</span></li>
                        <li class="flex items-start gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">2</span><span class="pt-1 text-sm leading-relaxed text-white/80">Our team checks each service and pet detail.</span></li>
                        <li class="flex items-start gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">3</span><span class="pt-1 text-sm leading-relaxed text-white/80">We confirm arrangements and final pricing with you.</span></li>
                    </ol>
                </section>
                <section class="rounded-2xl border border-primary/10 bg-white p-5" aria-labelledby="booking-whatsapp-heading">
                    <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-surface-purple text-primary"><x-waggies.brand-icon name="whatsapp" size="18" /></div>
                    <h3 id="booking-whatsapp-heading" class="font-serif text-xl font-bold text-primary-dark">Prefer to talk now?</h3>
                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">After sending your request, you can continue the conversation on WhatsApp.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark">Open WhatsApp <x-waggies.icon name="arrow-forward" size="16" /></a>
                </section>
            </aside>
        </div>
    @endif
</div>
