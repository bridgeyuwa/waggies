<?php

use App\Actions\CreateBookingRequest;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestSchema;
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

    public bool $choosingService = false;

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
     * @param  array{service?: ?string, variant?: ?string, source?: ?string, whatsappUrl?: string}  $initialContext
     */
    public function mount(array $initialContext = []): void
    {
        $serviceOptions = BookingRequestSchema::allServiceOptions();
        $service = $initialContext['service'] ?? null;
        $service = is_string($service) && array_key_exists($service, $serviceOptions) ? $service : null;
        $variantOptions = BookingRequestSchema::variantOptions($service);
        $variant = $initialContext['variant'] ?? null;
        $variant = is_string($variant) && array_key_exists($variant, $variantOptions) ? $variant : null;

        $this->minimumDate = now()->toDateString();
        $this->draftContextKey = implode('|', [$service ?? '', $variant ?? '', $initialContext['source'] ?? '']);
        $this->source = $initialContext['source'] ?? null;
        $this->whatsappUrl = $initialContext['whatsappUrl'] ?? '#';
        $this->services = [$this->newService($service, $variant)];
        $this->pets = [$this->newPet()];
    }

    public function serviceChanged(int $index, ?string $service): void
    {
        if ($service === null || ! array_key_exists($service, BookingRequestSchema::serviceOptions())) {
            $this->services[$index] = $this->newService(null, null);
            $this->resetValidation();

            return;
        }

        $this->services[$index] = $this->newService($service, null);
        $this->resetValidation();
    }

    public function variantChanged(int $index, ?string $variant): void
    {
        $service = (string) ($this->services[$index]['service_key'] ?? '');
        $options = BookingRequestSchema::variantOptions($service);
        $this->services[$index]['service_variant'] = array_key_exists((string) $variant, $options) ? $variant : null;
        $this->services[$index]['assigned_pet_ids'] = [];
        $this->resetValidation();
    }

    public function addService(): void
    {
        if (count($this->services) >= $this->maxServiceItems()) {
            return;
        }

        $this->choosingService = true;
        $this->resetValidation();
    }

    public function chooseAdditionalService(string $service): void
    {
        if (! $this->serviceAvailable($service) || ! array_key_exists($service, $this->serviceOptions())) {
            return;
        }

        $this->services[] = $this->newService($service, null);
        $this->choosingService = false;
        $this->resetValidation();
    }

    public function cancelAddService(): void
    {
        $this->choosingService = false;
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
        if (count($this->pets) >= $this->maxPets()) {
            return;
        }

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
            $assignedPetIds = array_filter(
                array_map(static fn (mixed $petId): int => (int) $petId, $service['assigned_pet_ids'] ?? []),
                fn (int $petId): bool => $petId !== $index,
            );
            $service['assigned_pet_ids'] = array_values(array_map(
                fn (int $petId): int => $petId > $index ? $petId - 1 : $petId,
                $assignedPetIds,
            ));
        }
        unset($service);
        $this->resetValidation();
    }

    public function nextStep(): void
    {
        if ($this->step >= 5) {
            return;
        }

        try {
            $this->validate($this->rulesForStep($this->step));

            if ($this->step === 1) {
                $this->validateServiceAvailability();
            }

            if ($this->step === 3) {
                $this->validateAssignmentCompatibility();
                $this->validatePetAssignmentsComplete();
                $this->validateDuplicateServices();
            }
        } catch (ValidationException $exception) {
            $this->revealValidationStep();

            throw $exception;
        }

        $this->step++;
    }

    public function previousStep(): void
    {
        $this->resetValidation();
        $this->step = max(1, $this->step - 1);
    }

    public function goToStep(int $step): void
    {
        if ($step <= $this->step) {
            $this->resetValidation();
            $this->step = max(1, min(5, $step));
        }
    }

    /**
     * @return array<int, array{key: string, message: string}>
     */
    public function currentStepErrorEntries(): array
    {
        return collect($this->getErrorBag()->getMessages())
            ->filter(fn (array $messages, string $key): bool => $this->stepForErrorKey($key) === $this->step)
            ->map(fn (array $messages, string $key): array => ['key' => $key, 'message' => (string) ($messages[0] ?? 'Check this field.')])
            ->values()
            ->all();
    }

    public function currentStepErrorCount(): int
    {
        return count($this->currentStepErrorEntries());
    }

    public function errorAnchor(string $key): string
    {
        return 'booking-'.str_replace(['.', '*'], '-', $key);
    }

    public function serviceOptions(): array
    {
        return BookingRequestSchema::serviceOptions();
    }

    public function allServiceOptions(): array
    {
        return BookingRequestSchema::allServiceOptions();
    }

    public function maxServiceItems(): int
    {
        return max(1, (int) config('waggies_pricing.max_service_items', 12));
    }

    public function serviceAvailable(string $service): bool
    {
        return app(BookingPricingCatalog::class)->isAvailable(config("waggies_pricing.services.{$service}", []));
    }

    public function serviceLabel(?string $service): string
    {
        return $this->allServiceOptions()[$service ?? ''] ?? 'Choose a service';
    }

    public function variantOptions(?string $service): array
    {
        return BookingRequestSchema::variantOptions($service);
    }

    public function allVariantOptions(?string $service): array
    {
        return BookingRequestSchema::allVariantOptions($service);
    }

    public function maxPets(): int
    {
        return 8;
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'services.*.service_key' => 'service',
            'services.*.service_variant' => 'service option',
            'services.*.assigned_pet_ids' => 'assigned pet',
            'services.*.details.check_in' => 'check-in date',
            'services.*.details.check_out' => 'check-out date',
            'services.*.details.origin_country' => 'origin country',
            'services.*.details.destination_country' => 'destination country',
            'services.*.details.documentation_status' => 'documentation status',
            'pets.*.name' => 'pet name',
            'pets.*.species' => 'pet type',
            'pets.*.size' => 'dog size',
            'contact.name' => 'your name',
            'contact.email' => 'email address',
            'contact.phone' => 'phone or WhatsApp number',
        ];
    }

    /**
     * @return array<string, array{label: string, examples: string|null, guidance: string|null, manual_review: bool}>
     */
    public function petSizeOptions(): array
    {
        foreach ($this->services as $service) {
            $options = app(BookingPricingCatalog::class)->sizeOptions((string) ($service['service_key'] ?? ''), $service['service_variant'] ?? null);

            if ($options !== []) {
                return $options;
            }
        }

        return [];
    }

    public function serviceFields(array $service): array
    {
        return BookingRequestSchema::serviceFields((string) ($service['service_key'] ?? ''), $service['service_variant'] ?? null);
    }

    public function fieldModel(int $index, array $field): string
    {
        return ($field['scope'] ?? 'details') === 'service'
            ? "services.{$index}.{$field['key']}"
            : "services.{$index}.details.{$field['key']}";
    }

    public function fieldVisible(array $field, array $service): bool
    {
        return true;
    }

    public function serviceSummary(array $service): string
    {
        return implode(' · ', array_filter([
            $this->serviceLabel($service['service_key'] ?? null),
            $this->allVariantOptions($service['service_key'] ?? null)[$service['service_variant'] ?? ''] ?? null,
        ]));
    }

    public function serviceStatus(array $service): string
    {
        if (! ($service['service_key'] ?? null)) {
            return 'Choose a service';
        }

        if (! $this->serviceAvailable((string) $service['service_key'])) {
            return 'Unavailable';
        }

        if ($this->allVariantOptions($service['service_key']) !== [] && ! ($service['service_variant'] ?? null)) {
            return 'Choose a service option';
        }

        return $this->step >= 3 && count($service['assigned_pet_ids'] ?? []) === 0 ? 'Needs a compatible pet' : 'Ready for review';
    }

    public function serviceReviewDetails(array $service): array
    {
        $details = [];

        foreach ($this->serviceFields($service) as $field) {
            $value = ($field['scope'] ?? 'details') === 'service'
                ? ($service[$field['key']] ?? null)
                : ($service['details'][$field['key']] ?? null);

            if (! is_scalar($value) || trim((string) $value) === '') {
                continue;
            }

            $details[$field['label']] = (string) $value;
        }

        return $details;
    }

    public function serviceQuote(array $service): array
    {
        $pets = array_values(array_filter(array_map(
            fn (mixed $petId): ?array => isset($this->pets[(int) $petId]) ? $this->pets[(int) $petId] : null,
            $service['assigned_pet_ids'] ?? [],
        )));

        return app(BookingPricingCatalog::class)->quoteForService($service, $pets);
    }

    public function quoteDisplay(array $quote): string
    {
        if (($quote['status'] ?? null) === 'quote') {
            return 'Staff review required';
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
        return ['dog' => 'Dog', 'cat' => 'Cat'][$species] ?? 'Type not selected';
    }

    public function petCompatible(array $service, array $pet): bool
    {
        return app(BookingPricingCatalog::class)->isPetCompatible((string) ($service['service_key'] ?? ''), $service['service_variant'] ?? null, $pet['species'] ?? null);
    }

    public function petCompatibilityReason(array $service, array $pet): ?string
    {
        return app(BookingPricingCatalog::class)->petCompatibilityReason((string) ($service['service_key'] ?? ''), $service['service_variant'] ?? null, $pet['species'] ?? null);
    }

    public function petAssignedTo(int $petIndex): string
    {
        $labels = [];

        foreach ($this->services as $service) {
            if (in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true)) {
                $labels[] = $this->serviceSummary($service);
            }
        }

        return $labels === [] ? 'Not assigned yet' : implode(', ', $labels);
    }

    public function editService(int $index): void
    {
        if (array_key_exists($index, $this->services)) {
            $this->step = 3;
        }
    }

    public function submit(CreateBookingRequest $createBookingRequest): void
    {
        try {
            $this->validate($this->allRules());
            $this->validateServiceAvailability();
            $this->validateAssignmentCompatibility();
            $this->validatePetAssignmentsComplete();
            $this->validateDuplicateServices();
        } catch (ValidationException $exception) {
            $this->revealValidationStep();

            throw $exception;
        }

        $createBookingRequest->handle([
            'contact' => $this->contact,
            'pets' => $this->pets,
            'services' => $this->services,
            'source' => $this->source,
            'context' => ['submitted_from' => 'livewire-booking-wizard'],
        ]);

        $this->submitted = true;
    }

    /** @return array<string, array<int, mixed>> */
    private function allRules(): array
    {
        return [...$this->rulesForStep(1), ...$this->rulesForStep(2), ...$this->rulesForStep(3), ...$this->rulesForStep(4)];
    }

    /** @return array<string, array<int, mixed>> */
    private function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => $this->serviceRules(),
            2 => $this->petRules(),
            3 => [...$this->assignmentRules(), ...$this->serviceDetailRules()],
            4 => [
                'contact.name' => ['required', 'string', 'max:120'],
                'contact.email' => ['required', 'email', 'max:255'],
                'contact.phone' => ['required', 'string', 'max:40'],
                'contact.preferred_contact_method' => ['nullable', Rule::in(['phone', 'email', 'whatsapp'])],
            ],
            default => [],
        };
    }

    /** @return array<string, array<int, mixed>> */
    private function serviceRules(): array
    {
        $rules = [
            'services' => ['required', 'array', 'min:1', 'max:'.$this->maxServiceItems()],
            'services.*.service_key' => ['required', Rule::in(array_keys(BookingRequestSchema::allServiceOptions()))],
        ];

        foreach ($this->services as $index => $service) {
            $options = BookingRequestSchema::variantOptions($service['service_key'] ?? null);
            $rules["services.{$index}.service_variant"] = ['required', Rule::in(array_keys($options))];
        }

        return $rules;
    }

    /** @return array<string, array<int, mixed>> */
    private function petRules(): array
    {
        $rules = [
            'pets' => ['required', 'array', 'min:1', 'max:8'],
            'pets.*.name' => ['required', 'string', 'max:80'],
            'pets.*.species' => ['required', Rule::in(['dog', 'cat'])],
            'pets.*.size' => ['nullable', Rule::in(array_keys($this->petSizeOptions()))],
            'pets.*.breed' => ['nullable', 'string', 'max:120'],
            'pets.*.age' => ['nullable', 'string', 'max:80'],
            'pets.*.sex' => ['nullable', Rule::in(['male', 'female', 'unknown'])],
            'pets.*.notes' => ['nullable', 'string', 'max:1000'],
        ];

        return $rules;
    }

    /** @return array<string, array<int, mixed>> */
    private function assignmentRules(): array
    {
        $rules = [];

        foreach ($this->services as $index => $service) {
            $rules["services.{$index}.assigned_pet_ids"] = ['required', 'array', 'min:1'];
            $rules["services.{$index}.assigned_pet_ids.*"] = ['integer', Rule::in(array_keys($this->pets))];

            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                $petIndex = (int) $petIndex;

                if (! isset($this->pets[$petIndex])) {
                    continue;
                }

                if (app(BookingPricingCatalog::class)->requiresPetSize((string) ($service['service_key'] ?? ''), $service['service_variant'] ?? null)
                    && ($this->pets[$petIndex]['species'] ?? null) === 'dog'
                ) {
                    $rules["pets.{$petIndex}.size"] = ['required', Rule::in(array_keys($this->petSizeOptions()))];
                }
            }
        }

        return $rules;
    }

    /** @return array<string, array<int, mixed>> */
    private function serviceDetailRules(): array
    {
        $rules = [];

        foreach ($this->services as $index => $service) {
            foreach ($this->serviceFields($service) as $field) {
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
                    $fieldRules = [...$fieldRules, 'string', 'max:2000'];
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
                if (isset($this->pets[(int) $petIndex]) && ! $this->petCompatible($service, $this->pets[(int) $petIndex])) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"][] = $this->petCompatibilityReason($service, $this->pets[(int) $petIndex]) ?? 'Choose a pet that matches this service.';
                }
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function validatePetAssignmentsComplete(): void
    {
        $assigned = collect($this->services)->flatMap(fn (array $service): array => array_map('intval', $service['assigned_pet_ids'] ?? []))->unique()->all();
        $messages = [];

        foreach ($this->pets as $petIndex => $pet) {
            if (! in_array($petIndex, $assigned, true)) {
                $messages["pets.{$petIndex}.assignments"] = 'Assign this pet to at least one service or remove it.';
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function validateDuplicateServices(): void
    {
        $seen = [];
        $messages = [];

        foreach ($this->services as $index => $service) {
            $signature = $this->duplicateServiceSignature($service);

            if (isset($seen[$signature])) {
                $messages["services.{$index}.assigned_pet_ids"][] = 'This matches another service item. Combine the assigned pets or change the details.';
                continue;
            }

            $seen[$signature] = $index;
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function duplicateServiceSignature(array $service): string
    {
        $identity = [
            'service_key' => $service['service_key'] ?? null,
            'service_variant' => $service['service_variant'] ?? null,
            'requested_date' => $service['requested_date'] ?? null,
            'requested_time' => $service['requested_time'] ?? null,
            'location' => $service['location'] ?? null,
            'details' => $service['details'] ?? [],
        ];

        return json_encode($identity, JSON_THROW_ON_ERROR);
    }

    private function validateServiceAvailability(): void
    {
        $messages = [];

        foreach ($this->services as $index => $service) {
            if (! $this->serviceAvailable((string) ($service['service_key'] ?? ''))) {
                $messages["services.{$index}.service_key"][] = 'This service is temporarily unavailable. Choose another service.';
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function stepForErrorKey(string $key): int
    {
        $parts = explode('.', $key);

        if (($parts[0] ?? null) === 'contact') {
            return 4;
        }

        if (($parts[0] ?? null) === 'pets') {
            return ($parts[2] ?? null) === 'assignments' ? 3 : 2;
        }

        if (($parts[0] ?? null) === 'services') {
            return in_array($parts[2] ?? null, ['service_key', 'service_variant'], true) ? 1 : 3;
        }

        return 3;
    }

    private function revealValidationStep(): void
    {
        $firstErrorKey = array_key_first($this->getErrorBag()->getMessages());

        if ($firstErrorKey !== null) {
            $this->step = $this->stepForErrorKey($firstErrorKey);
        }
    }

    /** @return array<string, mixed> */
    private function newService(?string $service, ?string $variant): array
    {
        return [
            'service_key' => $service,
            'service_variant' => $variant,
            'pricing_tier' => null,
            'assigned_pet_ids' => [],
            'requested_date' => null,
            'requested_time' => null,
            'location' => null,
            'details' => [],
        ];
    }

    /** @return array<string, mixed> */
    private function newPet(): array
    {
        return [
            'name' => null,
            'species' => null,
            'size' => null,
            'breed' => null,
            'age' => null,
            'sex' => null,
            'notes' => null,
            'details' => [],
        ];
    }
};
?>

<div data-booking-draft="waggies-booking-request-v3" data-booking-context="{{ $draftContextKey }}" data-booking-draft-label="Booking request">
    @if($submitted)
        <div class="flex flex-col gap-5" role="status" tabindex="-1"><div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-light text-success"><x-waggies.icon name="check-circle" variant="filled" size="28" /></div><div><h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark sm:text-3xl">Your request was received</h2><p class="mt-3 max-w-xl text-sm leading-relaxed text-primary-dark/70">Waggies will review availability, pet details, special-care needs, veterinary or relocation requirements, and the final price. Your request is not confirmed until Waggies contacts you.</p></div><div class="flex flex-col gap-3 sm:flex-row"><x-waggies.button href="{{ $this->whatsappUrl }}" target="_blank" rel="noopener noreferrer">Continue on WhatsApp <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button><x-waggies.button href="{{ route('book') }}" variant="secondary">Send another request</x-waggies.button></div></div>
    @else
        @php($stepHeadings = [1 => ['eyebrow' => 'STEP 1 OF 5', 'title' => 'Choose your services', 'description' => 'Choose Boarding, Veterinary Care, or Relocation, then select the relevant request type.'], 2 => ['eyebrow' => 'STEP 2 OF 5', 'title' => 'Add your pet'.(count($pets) > 1 ? 's' : ''), 'description' => 'Dogs and cats only for the active Waggies service catalogue.'], 3 => ['eyebrow' => 'STEP 3 OF 5', 'title' => 'Match pets and add details', 'description' => 'Assign each pet and share the information staff need for review.'], 4 => ['eyebrow' => 'STEP 4 OF 5', 'title' => 'How should we contact you?', 'description' => 'We use these details to review the request and send the next steps.'], 5 => ['eyebrow' => 'STEP 5 OF 5', 'title' => 'Review your request', 'description' => 'Check the request before sending it. No payment is taken here.']])
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-12"><div class="min-w-0"><div class="mb-7 flex flex-col gap-2"><p class="text-eyebrow text-primary">{{ $stepHeadings[$step]['eyebrow'] }}</p><h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark sm:text-3xl">{{ $stepHeadings[$step]['title'] }}</h2><p class="max-w-2xl text-sm leading-relaxed text-primary-dark/60">{{ $stepHeadings[$step]['description'] }}</p><p class="max-w-2xl text-sm font-medium leading-relaxed text-primary-dark/75">This is a request, not a confirmed booking. Availability, final price, discounts, payment, and special care are handled manually.</p></div>

            @if($this->currentStepErrorCount() > 0)<div class="mb-6 rounded-xl border border-danger/30 bg-danger-light p-4" role="alert"><p class="text-sm font-semibold text-danger-dark">Please check the highlighted details.</p><ul class="mt-2 grid gap-1 text-sm text-danger-dark/80">@foreach($this->currentStepErrorEntries() as $error)<li><a href="#{{ $this->errorAnchor($error['key']) }}" class="underline">{{ $error['message'] }}</a></li>@endforeach</ul></div>@endif

            @if($step === 1)<div class="grid gap-6">@foreach($services as $index => $service)<div class="rounded-2xl border border-primary/10 bg-surface p-5" wire:key="service-{{ $index }}"><div class="flex items-start justify-between gap-4"><div><p class="text-eyebrow">SERVICE {{ $index + 1 }}</p><h3 class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h3><p class="mt-1 text-xs text-primary-dark/60">{{ $this->serviceStatus($service) }}</p></div>@if(count($services) > 1)<button type="button" wire:click="removeService({{ $index }})" class="text-sm font-semibold text-primary hover:text-primary-dark">Remove</button>@endif</div><div class="mt-5 grid gap-5 md:grid-cols-2"><x-waggies.select id="booking-{{ $index }}-service_key" label="Service" wire:model.live="services.{{ $index }}.service_key" wire:change="serviceChanged({{ $index }}, $event.target.value)" required><option value="">Choose a service</option>@foreach($this->allServiceOptions() as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</x-waggies.select><x-waggies.select id="booking-{{ $index }}-service_variant" label="Request type" wire:model.live="services.{{ $index }}.service_variant" wire:change="variantChanged({{ $index }}, $event.target.value)" required><option value="">Choose an option</option>@foreach($this->allVariantOptions($service['service_key'] ?? null) as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</x-waggies.select></div></div>@endforeach@if(! $choosingService)<button type="button" wire:click="addService" class="inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-primary">+ Add another service</button>@else<div class="rounded-2xl border border-primary/10 bg-surface p-5"><p class="text-sm font-semibold text-primary-dark">Add a service</p><div class="mt-3 flex flex-wrap gap-2">@foreach($this->serviceOptions() as $key => $label)<button type="button" wire:click="chooseAdditionalService('{{ $key }}')" class="rounded-full border border-primary/20 bg-white px-4 py-2 text-sm font-semibold text-primary-dark hover:border-primary">{{ $label }}</button>@endforeach<button type="button" wire:click="cancelAddService" class="rounded-full px-4 py-2 text-sm text-primary-dark/60">Cancel</button></div></div>@endif</div>
            @elseif($step === 2)<div class="grid gap-5">@foreach($pets as $index => $pet)<div class="rounded-2xl border border-primary/10 bg-surface p-5" wire:key="pet-{{ $index }}"><div class="flex items-center justify-between gap-4"><h3 class="font-serif text-xl font-bold text-primary-dark">Pet {{ $index + 1 }}</h3>@if(count($pets) > 1)<button type="button" wire:click="removePet({{ $index }})" class="text-sm font-semibold text-primary">Remove</button>@endif</div><div class="mt-5 grid gap-5 md:grid-cols-2"><x-waggies.field id="booking-pet-{{ $index }}-name" label="Pet name" required><input id="booking-pet-{{ $index }}-name" wire:model.blur="pets.{{ $index }}.name" class="contact-input"></x-waggies.field><x-waggies.select id="booking-pet-{{ $index }}-species" label="Pet type" wire:model.live="pets.{{ $index }}.species" required><option value="">Choose a type</option><option value="dog">Dog</option><option value="cat">Cat</option></x-waggies.select><x-waggies.field id="booking-pet-{{ $index }}-breed" label="Breed (optional)"><input id="booking-pet-{{ $index }}-breed" wire:model.blur="pets.{{ $index }}.breed" class="contact-input"></x-waggies.field><x-waggies.field id="booking-pet-{{ $index }}-age" label="Age or life stage (optional)"><input id="booking-pet-{{ $index }}-age" wire:model.blur="pets.{{ $index }}.age" class="contact-input"></x-waggies.field></div></div>@endforeach<button type="button" wire:click="addPet" class="inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-primary">+ Add another pet</button></div>
            @elseif($step === 3)<div class="grid gap-6">@foreach($services as $index => $service)<section class="rounded-2xl border border-primary/10 bg-surface p-5" wire:key="service-details-{{ $index }}"><div class="flex items-start justify-between gap-4"><div><p class="text-eyebrow">{{ $this->serviceSummary($service) }}</p><h3 class="mt-1 font-serif text-xl font-bold text-primary-dark">Who is this for?</h3></div><span class="text-xs font-semibold text-primary-dark/60">{{ $this->serviceStatus($service) }}</span></div><div class="mt-4 grid gap-2">@foreach($pets as $petIndex => $pet)<label class="flex items-start gap-3 rounded-xl border border-primary/10 bg-white p-3 text-sm {{ in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true) ? 'border-primary bg-surface-purple' : '' }}"><input type="checkbox" wire:model.live="services.{{ $index }}.assigned_pet_ids" value="{{ $petIndex }}" class="mt-1 rounded border-primary/30 text-primary focus:ring-primary"><span><span class="block font-semibold text-primary-dark">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }}</span><span class="block text-xs text-primary-dark/60">{{ $this->petSpeciesLabel($pet['species'] ?? null) }}@if(! $this->petCompatible($service, $pet)) — not compatible with this request type @endif</span></span></label>@endforeach</div><div class="mt-5 grid gap-5 md:grid-cols-2">@foreach($this->serviceFields($service) as $field)@php($model = $this->fieldModel($index, $field))@if($field['type'] === 'date')<x-waggies.field :id="'booking-'.$index.'-'.$field['key']" :label="$field['label']" :help="$field['help'] ?? null" :required="$field['required']"><input type="date" id="booking-{{ $index }}-{{ $field['key'] }}" wire:model.blur="{{ $model }}" min="{{ $minimumDate }}" class="contact-input"></x-waggies.field>@elseif($field['type'] === 'select')<x-waggies.select :id="'booking-'.$index.'-'.$field['key']" :label="$field['label']" wire:model.live="{{ $model }}" :required="$field['required']"><option value="">Choose an option</option>@foreach($field['options'] as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</x-waggies.select>@else<x-waggies.field :id="'booking-'.$index.'-'.$field['key']" :label="$field['label']" :required="$field['required']"><textarea id="booking-{{ $index }}-{{ $field['key'] }}" wire:model.blur="{{ $model }}" rows="3" class="contact-input" placeholder="{{ $field['placeholder'] ?? '' }}"></textarea></x-waggies.field>@endif @endforeach</div></section>@endforeach</div>
            @elseif($step === 4)<div class="grid gap-5 md:grid-cols-2"><x-waggies.field id="booking-contact-name" label="Your name" required><input id="booking-contact-name" wire:model.blur="contact.name" class="contact-input"></x-waggies.field><x-waggies.field id="booking-contact-email" label="Email address" required><input id="booking-contact-email" type="email" wire:model.blur="contact.email" class="contact-input"></x-waggies.field><x-waggies.field id="booking-contact-phone" label="Phone or WhatsApp number" required><input id="booking-contact-phone" wire:model.blur="contact.phone" class="contact-input"></x-waggies.field><x-waggies.select id="booking-contact-method" label="Preferred contact method" wire:model.live="contact.preferred_contact_method"><option value="">Choose one</option><option value="phone">Phone</option><option value="email">Email</option><option value="whatsapp">WhatsApp</option></x-waggies.select></div>
            @else<div class="grid gap-5">@foreach($services as $index => $service)<div class="rounded-2xl border border-primary/10 bg-surface p-5"><div class="flex items-start justify-between gap-4"><div><p class="text-eyebrow">SERVICE {{ $index + 1 }}</p><h3 class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h3></div><button type="button" wire:click="editService({{ $index }})" class="text-sm font-semibold text-primary">Edit</button></div><p class="mt-3 text-sm font-semibold text-primary-dark">Indicative guidance: {{ $this->quoteDisplay($this->serviceQuote($service)) }}</p><p class="mt-1 text-xs text-primary-dark/60">Final quote, discount, special-care charge, and payment instructions are confirmed by staff.</p><dl class="mt-4 grid gap-2 text-sm">@foreach($this->serviceReviewDetails($service) as $label => $value)<div class="flex flex-col gap-1 border-t border-primary/10 pt-2 sm:flex-row sm:justify-between"><dt class="font-medium text-primary-dark/60">{{ $label }}</dt><dd class="text-primary-dark/80 sm:text-right">{{ $value }}</dd></div>@endforeach</dl></div>@endforeach<div class="rounded-2xl border border-primary/10 bg-surface-purple/45 p-5"><p class="text-sm leading-relaxed text-primary-dark/70">By submitting, you acknowledge that Waggies will review the request manually. No slot is reserved and no payment is taken on this page.</p></div></div>@endif

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-primary/10 pt-5 sm:flex-row sm:items-center sm:justify-between"><x-waggies.button type="button" variant="secondary" wire:click="previousStep" :disabled="$step === 1">Back</x-waggies.button>@if($step < 5)<x-waggies.button type="button" wire:click="nextStep">Continue <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>@else<x-waggies.button type="button" wire:click="submit" wire:loading.attr="disabled" wire:target="submit">Submit Booking Request <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>@endif</div>
        </div><aside class="hidden lg:block"><div class="sticky top-24 rounded-2xl bg-primary-dark p-6 text-white"><p class="text-eyebrow text-secondary">WHAT HAPPENS NEXT?</p><ol class="mt-5 grid gap-4">@foreach([['Request received', 'We acknowledge the request.'], ['Under review', 'Staff check the details.'], ['Quote and confirmation', 'We send the final next steps manually.']] as $item)<li class="flex items-start gap-3"><span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">{{ $loop->iteration }}</span><span><strong class="block text-sm text-white">{{ $item[0] }}</strong><span class="mt-1 block text-xs leading-relaxed text-white/65">{{ $item[1] }}</span></span></li>@endforeach</ol></div></aside></div>
    @endif
</div>
