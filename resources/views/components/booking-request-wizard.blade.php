<?php

use App\Actions\CreateBookingRequest;
use App\Rules\ValidPhoneNumber;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestSchema;
use App\Support\CountryCatalog;
use App\Support\PhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component
{
    public int $step = 1;

    public bool $submitted = false;

    public string $submissionToken = '';

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
        'phone_country' => 'NG',
        'phone_number' => null,
        'phone_other_country_code' => null,
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
        $this->submissionToken = (string) Str::uuid();
        $this->draftContextKey = implode('|', [$service ?? '', $variant ?? '', $initialContext['source'] ?? '']);
        $this->source = $initialContext['source'] ?? null;
        $this->whatsappUrl = $initialContext['whatsappUrl'] ?? '#';
        $this->services = [$this->newService($service, $variant)];
        $this->pets = [$this->newPet()];
    }

    public function serviceChanged(int $index, ?string $service): void
    {
        if (! array_key_exists($index, $this->services)) {
            return;
        }

        $serviceOptions = BookingRequestSchema::serviceOptions();

        if ($service === null || ! array_key_exists($service, $serviceOptions)) {
            $this->services[$index] = $this->newService(null, null);
            $this->resetValidation();

            return;
        }

        $this->services[$index] = $this->newService($service, null);
        $this->resetValidation();
    }

    public function updatedStep(int|string $step): void
    {
        $this->step = max(1, min(5, (int) $step));
    }

    public function updated(string $property): void
    {
        if ($property === 'contact.phone' && ! filled($this->contact['phone_number'] ?? null)) {
            $this->contact['phone_country'] = PhoneNumber::defaultCountryCode();
            $this->contact['phone_number'] = $this->contact['phone'];
        }

        if (Str::is('services.*.service_variant', $property)) {
            $parts = explode('.', $property);
            $index = (int) ($parts[1] ?? 0);
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
        if (! array_key_exists($index, $this->services)) {
            return;
        }

        $service = (string) ($this->services[$index]['service_key'] ?? '');

        if (BookingRequestSchema::serviceSelectionMode($service) !== 'single') {
            return;
        }

        $variantOptions = BookingRequestSchema::variantOptions($service);
        $this->services[$index]['service_variant'] = array_key_exists((string) $variant, $variantOptions) ? $variant : null;
        $this->services[$index]['assigned_pet_ids'] = [];

        if ($service === 'relocation') {
            $this->services[$index]['details'] = BookingRequestSchema::relocationDetails(
                $this->services[$index]['service_variant'],
                $this->services[$index]['details'] ?? [],
            );
        }

        $this->resetValidation();
    }

    public function addService(): void
    {
        if (count($this->services) >= $this->maxServiceItems()) {
            $this->dispatch('booking-wizard-announcement', message: 'You have reached the maximum number of services for one request.');

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

        $emptyIndex = collect($this->services)->search(
            fn (array $existing): bool => empty($existing['service_key']),
        );

        if ($emptyIndex !== false) {
            $this->services[$emptyIndex] = $this->newService($service, null);
            $this->choosingService = false;
            $this->dispatch('booking-wizard-focus-target', target: "booking-service-{$emptyIndex}");
            $this->dispatch('booking-wizard-announcement', message: $this->serviceLabel($service).' added to your request.');
            $this->resetValidation();

            return;
        }

        $existingIndex = collect($this->services)->search(
            fn (array $existing): bool => ($existing['service_key'] ?? null) === $service
                && $this->serviceNeedsSelection($existing),
        );

        if ($existingIndex !== false) {
            $this->choosingService = false;
            $this->dispatch('booking-wizard-announcement', message: $this->serviceLabel($service).' is already in your request. Configure that service before adding it again.');
            $this->dispatch('booking-wizard-focus-target', target: "booking-service-{$existingIndex}");

            return;
        }

        $this->services[] = $this->newService($service, null);
        $this->choosingService = false;
        $this->dispatch('booking-wizard-focus-target', target: 'booking-service-'.(count($this->services) - 1));
        $this->dispatch('booking-wizard-announcement', message: $this->serviceLabel($service).' added to your request.');
        $this->resetValidation();
    }

    public function cancelAddService(): void
    {
        $this->choosingService = false;
    }

    public function removeService(int $index): void
    {
        if (count($this->services) <= 1 || ! array_key_exists($index, $this->services)) {
            return;
        }

        unset($this->services[$index]);
        $this->services = array_values($this->services);
        $this->resetValidation();
    }

    public function addPet(): void
    {
        if (count($this->pets) >= 8) {
            $this->dispatch('booking-wizard-announcement', message: 'You can add up to 8 pets to one request.');

            return;
        }

        $this->pets[] = $this->newPet();
        $index = count($this->pets) - 1;
        $this->dispatch('booking-wizard-focus-target', target: "booking-pet-{$index}-heading");
        $this->dispatch('booking-wizard-announcement', message: 'Pet '.($index + 1).' added.');
    }

    public function removePet(int $index): void
    {
        if (count($this->pets) <= 1 || ! array_key_exists($index, $this->pets)) {
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

        $focusIndex = min($index, count($this->pets) - 1);
        $this->dispatch('booking-wizard-focus-target', target: "booking-pet-{$focusIndex}-heading");
        $this->dispatch('booking-wizard-announcement', message: 'Pet removed from this request.');
        $this->resetValidation();
    }

    public function nextStep(): void
    {
        if ($this->step >= 5) {
            return;
        }

        try {
            if ($this->step === 4) {
                $this->syncLegacyPhoneFields();
            }

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
            $this->revealValidationStep($exception->errors());

            throw $exception;
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
        if ($step > $this->step) {
            return;
        }

        $this->resetValidation();
        $this->step = max(1, min(5, $step));
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    /**
     * @return array<int, array{key: string, message: string}>
     */
    public function currentStepErrorEntries(): array
    {
        return collect($this->getErrorBag()->getMessages())
            ->filter(fn (array $messages, string $key): bool => $this->errorBelongsToStep($key, $this->step))
            ->map(fn (array $messages, string $key): array => [
                'key' => $key,
                'message' => $this->userFacingValidationMessage($key, (string) ($messages[0] ?? 'Check this field before continuing.')),
            ])
            ->values()
            ->all();
    }

    public function currentStepErrorCount(): int
    {
        return count($this->currentStepErrorEntries());
    }

    public function errorAnchor(string $key): string
    {
        $parts = explode('.', $key);

        if (($parts[0] ?? null) === 'services') {
            $index = $parts[1] ?? '0';

            return match ($parts[2] ?? null) {
                'service_variant' => "booking-service-{$index}-variant",
                'assigned_pet_ids' => "booking-service-{$index}-assignment",
                    'details' => ($parts[3] ?? null) === 'care_needs'
                        ? "booking-service-{$index}-care-needs"
                        : 'booking-'.$index.'-'.($parts[3] ?? 'service'),
                default => 'booking-'.$index.'-'.($parts[2] ?? 'service'),
            };
        }

        if (($parts[0] ?? null) === 'pets') {
            if (($parts[2] ?? null) === 'assignments') {
                return 'booking-pet-assignment-'.($parts[1] ?? '0');
            }

            return 'booking-pet-'.($parts[1] ?? '0').'-'.($parts[2] ?? 'name');
        }

        return match ($parts[0] ?? null) {
            'contact' => 'booking-contact-'.($parts[1] ?? 'name'),
            default => 'booking-'.str_replace(['.', '*'], '-', $key),
        };
    }

    private function userFacingValidationMessage(string $key, string $message): string
    {
        $attribute = null;

        foreach ($this->validationAttributes() as $pattern => $label) {
            $regex = str_replace('\\*', '[^.]+', preg_quote($pattern, '/'));

            if (preg_match('/^'.$regex.'$/', $key) === 1) {
                $attribute = $label;

                break;
            }
        }

        if ($attribute === null) {
            return $message;
        }

        return str_replace($key, $attribute, $message);
    }

    private function errorBelongsToStep(string $key, int $step): bool
    {
        return $this->stepForErrorKey($key) === $step;
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
            if (($parts[2] ?? null) === 'details' && ($parts[3] ?? null) === 'care_needs') {
                return 1;
            }

            return in_array($parts[2] ?? null, ['service_key', 'service_variant'], true) ? 1 : 3;
        }

        return 3;
    }

    /**
     * @param  array<string, array<int, string>>  $validationErrors
     */
    private function revealValidationStep(array $validationErrors = []): void
    {
        $errorKeys = array_keys($validationErrors !== []
            ? $validationErrors
            : $this->getErrorBag()->getMessages());

        if ($errorKeys !== []) {
            $this->step = min(array_map(
                fn (string $key): int => $this->stepForErrorKey($key),
                $errorKeys,
            ));
        }

        $this->dispatch('booking-wizard-validation-failed', step: $this->step);
    }

    public function serviceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions(availableOnly: false);
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
        return $this->serviceOptions()[$service ?? ''] ?? 'Choose a service';
    }

    public function variantOptions(?string $service): array
    {
        return BookingRequestSchema::variantOptions($service);
    }

    public function allVariantOptions(?string $service): array
    {
        return BookingRequestSchema::allVariantOptions($service);
    }

    public function variantAvailable(?string $service, string $variant): bool
    {
        return array_key_exists($variant, $this->variantOptions($service));
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
        $attributes = [
            'services.*.service_key' => 'service',
            'services.*.service_variant' => 'animal type',
            'services.*.details.care_needs' => 'veterinary care needs',
            'services.*.details.care_needs.*' => 'veterinary care need',
            'services.*.assigned_pet_ids' => 'assigned pet',
            'services.*.details.check_in' => 'check-in date',
            'services.*.details.check_out' => 'check-out date',
            'services.*.details.reason' => 'what your pet needs help with',
            'services.*.details.urgency' => 'urgency',
            'services.*.details.pickup' => 'pickup point',
            'services.*.details.dropoff' => 'drop-off point',
            'services.*.details.trip_type' => 'trip type',
            'services.*.details.origin_country' => 'country your pet is coming from',
            'services.*.details.destination_country' => 'country your pet is going to',
            'services.*.details.documentation_status' => 'documentation status',
            'services.*.details.emergency' => 'emergency veterinary authorization',
            'services.*.details.emergency_vet_authorization' => 'emergency veterinary authorization',
            'pets.*.name' => 'pet name',
            'pets.*.species' => 'pet type',
            'pets.*.size' => 'dog size',
            'pets.*.breed' => 'breed',
            'pets.*.age' => 'age or life stage',
            'pets.*.sex' => 'sex',
            'contact.name' => 'your name',
            'contact.email' => 'email address',
            'contact.phone_country' => 'country calling code',
            'contact.phone_number' => 'phone number',
            'contact.phone_other_country_code' => 'country calling code',
        ];

        foreach ($this->services as $index => $service) {
            $attributes["services.{$index}.service_variant"] = $this->serviceVariantAttribute($service['service_key'] ?? null);
        }

        return $attributes;
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'pets.*.species.in' => 'This pet type is not compatible with the selected services. Choose a compatible pet type or add a service that supports it.',
            'services.*.details.care_needs.required' => 'Select at least one veterinary care need.',
            'services.*.details.care_needs.min' => 'Select at least one veterinary care need.',
            'services.*.details.care_needs.*.in' => 'Choose a valid veterinary care need.',
        ];
    }

    /**
     * @return array<string, array{label: string, examples: string|null}>
     */
    public function petSizeOptions(): array
    {
        foreach ($this->services as $service) {
            $options = app(BookingPricingCatalog::class)->sizeOptions(
                (string) ($service['service_key'] ?? ''),
                $service['service_variant'] ?? null,
            );

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
        $scope = $field['scope'] ?? 'details';

        return $scope === 'service'
            ? "services.{$index}.{$field['key']}"
            : "services.{$index}.details.{$field['key']}";
    }

    public function fieldVisible(array $field, array $service): bool
    {
        return true;
    }

    public function serviceSummary(array $service): string
    {
        return app(BookingPricingCatalog::class)->serviceSummary($service, $this->petsForService($service));
    }

    public function serviceStatus(array $service): string
    {
        if (! ($service['service_key'] ?? null)) {
            return 'Choose a service';
        }

        if (! $this->serviceAvailable((string) $service['service_key'])) {
            return 'Unavailable';
        }

        if ($this->serviceNeedsSelection($service)) {
            return $this->serviceVariantStatus($service['service_key']);
        }

        if ($this->step >= 3 && count($service['assigned_pet_ids'] ?? []) === 0) {
            return 'Needs a compatible pet';
        }

        return $this->step >= 3 ? 'Ready' : 'Ready to match';
    }

    public function serviceVariantQuestion(?string $service): string
    {
        return match ($service) {
            'relocation' => 'Which relocation option applies?',
            default => 'Which option applies to this service?',
        };
    }

    public function serviceVariantStatus(?string $service): string
    {
        return match ($service) {
            'boarding' => 'Choose compatible pets',
            'vet-care' => 'Choose at least one care need',
            'relocation' => 'Choose a relocation option',
            default => 'Choose an option',
        };
    }

    public function serviceVariantAttribute(?string $service): string
    {
        return match ($service) {
            'boarding' => 'pet assignment',
            'vet-care' => 'veterinary care needs',
            'relocation' => 'relocation option',
            default => 'service option',
        };
    }

    public function serviceAssignedCount(array $service): int
    {
        return count(array_unique(array_map('intval', $service['assigned_pet_ids'] ?? [])));
    }

    public function isDuplicateService(int $index): bool
    {
        if (! isset($this->services[$index])) {
            return false;
        }

        $signature = $this->duplicateServiceSignature($this->services[$index]);

        foreach (array_slice($this->services, 0, $index) as $service) {
            if ($this->duplicateServiceSignature($service) === $signature) {
                return true;
            }
        }

        return false;
    }

    public function petAssignedServiceCount(int $petIndex): int
    {
        return collect($this->services)
            ->filter(fn (array $service): bool => in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true))
            ->count();
    }

    public function petNeedsSize(int $petIndex): bool
    {
        $pet = $this->pets[$petIndex] ?? [];

        if (($pet['species'] ?? null) !== 'dog') {
            return false;
        }

        return collect($this->services)->contains(
            fn (array $service): bool => app(BookingPricingCatalog::class)->requiresPetSize(
                (string) ($service['service_key'] ?? ''),
                $service['service_variant'] ?? null,
            ),
        );
    }

    public function petNeedsWeight(int $petIndex): bool
    {
        return false;
    }

    public function petRequiresBreed(int $petIndex): bool
    {
        return collect($this->services)->contains(
            fn (array $service): bool => ($service['service_key'] ?? null) === 'relocation'
                && in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true),
        );
    }

    public function petBreedHelp(int $petIndex): string
    {
        return $this->petRequiresBreed($petIndex)
            ? 'Required for this assigned service. Use Mixed breed or Unknown if needed.'
            : 'Optional. Use Mixed breed or Unknown if needed.';
    }

    /**
     * @return array<string, string>
     */
    public function petAgeOptions(): array
    {
        return config('waggies_pricing.pet_age_options', []);
    }

    /**
     * @return array<string, string>
     */
    public function petTypeOptions(): array
    {
        $allowedPetTypes = collect($this->services)
            ->filter(fn (array $service): bool => filled($service['service_key'] ?? null))
            ->flatMap(function (array $service): array {
                return app(BookingPricingCatalog::class)->allowedPetTypes(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                ) ?? ['dog', 'cat'];
            })
            ->unique()
            ->values()
            ->all();

        if ($allowedPetTypes === []) {
            $allowedPetTypes = ['dog', 'cat'];
        }

        return collect(['dog' => 'Dog', 'cat' => 'Cat'])
            ->filter(fn (string $label, string $type): bool => in_array($type, $allowedPetTypes, true))
            ->all();
    }

    public function petAgeRequired(int $petIndex): bool
    {
        return collect($this->services)->contains(
            fn (array $service): bool => in_array($service['service_key'] ?? null, ['vet-care', 'relocation'], true)
                && in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true),
        );
    }

    public function petAgeHelp(int $petIndex): string
    {
        return $this->petAgeRequired($petIndex)
            ? 'Required for veterinary care or relocation. Choose Not sure if you do not know.'
            : 'Optional. Choose the closest life stage; Not sure is okay.';
    }

    public function petAgeLabel(?string $age): ?string
    {
        return $this->petAgeOptions()[$age ?? ''] ?? $age;
    }

    public function servicePetRequirement(array $service): string
    {
        $allowedPetTypes = app(BookingPricingCatalog::class)->allowedPetTypes(
            (string) ($service['service_key'] ?? ''),
            $service['service_variant'] ?? null,
        );

        if ($allowedPetTypes === null) {
            return 'Any pet type can use this service.';
        }

        $labels = [
            'dog' => 'dogs',
            'cat' => 'cats',
            'other' => 'other pets',
        ];

        return 'For '.collect($allowedPetTypes)->map(fn (string $type): string => $labels[$type] ?? $type)->join(' or ').'.';
    }

    public function petCompatibilityReason(array $service, array $pet): ?string
    {
        return app(BookingPricingCatalog::class)->petCompatibilityReason(
            (string) ($service['service_key'] ?? ''),
            $service['service_variant'] ?? null,
            $pet['species'] ?? null,
        );
    }

    public function scheduleSummary(array $service): string
    {
        $details = $service['details'] ?? [];
        $date = $service['service_key'] === 'boarding' ? ($details['check_in'] ?? null) : ($service['requested_date'] ?? null);
        $dateLabel = $date ? Carbon::parse($date)->format('D, M j, Y') : null;

        if ($service['service_key'] === 'boarding' && ! empty($details['check_out'])) {
            $dateLabel .= ' → '.Carbon::parse($details['check_out'])->format('D, M j, Y');
            $dateLabel .= ' · '.Carbon::parse($details['check_in'])->diffInDays(Carbon::parse($details['check_out'])).' nights';
        }

        if ($service['service_key'] === 'relocation') {
            return implode(' · ', array_filter([
                $dateLabel,
                '1 relocation',
                app(CountryCatalog::class)->label($details['origin_country'] ?? null),
                app(CountryCatalog::class)->label($details['destination_country'] ?? null),
            ]));
        }

        $unit = $service['service_key'] === 'vet-care' ? '1 veterinary request' : null;

        return implode(' · ', array_filter([$dateLabel ?: 'Date not added yet', $unit]));
    }

    public function relocationEndpointLabel(array $service, string $side): string
    {
        $route = BookingRequestSchema::relocationRoute($service['service_variant'] ?? null);
        $endpoint = $route[$side] ?? [];

        if (($endpoint['fixed'] ?? false) === true) {
            $airport = $route['airport'];
            $airportName = (string) ($airport['name'] ?? 'Nnamdi Azikiwe International Airport');

            if (filled($airport['code'] ?? null)) {
                $airportName .= ' ('.$airport['code'].')';
            }

            return implode(', ', array_filter([
                $airportName,
                $airport['city'] ?? 'Abuja',
                $route['fixed_country_label'] ?? 'Nigeria',
            ]));
        }

        $countryCode = $service['details'][$side.'_country'] ?? null;

        return app(CountryCatalog::class)->label($countryCode)
            ?? ($side === 'origin' ? 'Choose origin country' : 'Choose destination country');
    }

    public function dateMinimum(array $service, array $field): string
    {
        if (($field['key'] ?? null) === 'check_out' && ! empty($service['details']['check_in'])) {
            return Carbon::parse($service['details']['check_in'])->addDay()->toDateString();
        }

        return $this->minimumDate;
    }

    public function quoteQuantitySummary(array $service, array $quote): ?string
    {
        if (($service['service_key'] ?? null) === 'boarding') {
            if (empty($service['details']['check_in']) || empty($service['details']['check_out'])) {
                return null;
            }

            $nights = (int) ($quote['nights'] ?? Carbon::parse($service['details']['check_in'])->diffInDays(Carbon::parse($service['details']['check_out'])));

            return $nights.' night'.($nights === 1 ? '' : 's');
        }

        return match ($service['service_key'] ?? null) {
            'vet-care' => '1 veterinary request',
            'relocation' => '1 relocation request',
            default => null,
        };
    }

    /**
     * @return array<string, string>
     */
    public function serviceReviewDetails(array $service): array
    {
        $details = [];

        if (($service['service_key'] ?? null) === 'vet-care') {
            $careNeeds = app(BookingPricingCatalog::class)->serviceSelectionSummary($service);

            if ($careNeeds !== null) {
                $details['Care requested'] = $careNeeds;
            }
        }

        foreach ($this->serviceFields($service) as $field) {
            if (! $this->fieldVisible($field, $service)) {
                continue;
            }

            $value = ($field['scope'] ?? 'details') === 'service'
                ? ($service[$field['key']] ?? null)
                : ($service['details'][$field['key']] ?? null);

            if (! is_scalar($value) || trim((string) $value) === '') {
                continue;
            }

            $details[$field['label']] = ($field['type'] ?? null) === 'country'
                ? (app(CountryCatalog::class)->label((string) $value) ?? (string) $value)
                : (string) $value;
        }

        return $details;
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

    public function quoteDisplay(array $quote, array $service = []): string
    {
        if (($quote['status'] ?? null) === 'quote') {
            return 'Quote required';
        }

        if (($quote['status'] ?? null) === 'needs_input') {
            return 'Complete details to estimate';
        }

        if (($quote['status'] ?? null) === 'unavailable') {
            return 'Unavailable';
        }

        $amount = number_format((int) ($quote['amount'] ?? 0));
        $maximum = number_format((int) ($quote['max_amount'] ?? $quote['amount'] ?? 0));
        $display = $amount === $maximum ? '₦'.$amount : '₦'.$amount.'–₦'.$maximum;

        if (($service['service_key'] ?? null) === 'boarding'
            && (blank($service['details']['check_in'] ?? null) || blank($service['details']['check_out'] ?? null))) {
            return $display.' per night';
        }

        return $display;
    }

    public function petSpeciesLabel(?string $species): string
    {
        return [
            'dog' => 'Dog',
            'cat' => 'Cat',
        ][$species] ?? 'Type not selected';
    }

    public function petCompatible(array $service, array $pet): bool
    {
        return app(BookingPricingCatalog::class)->isPetCompatible(
            (string) ($service['service_key'] ?? ''),
            $service['service_variant'] ?? null,
            $pet['species'] ?? null,
        );
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

    /**
     * @return list<int>
     */
    public function assignedPetIndexes(): array
    {
        $assignedPetIndexes = [];

        foreach ($this->services as $service) {
            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                $petIndex = (int) $petIndex;

                if (array_key_exists($petIndex, $this->pets)) {
                    $assignedPetIndexes[$petIndex] = true;
                }
            }
        }

        $assignedPetIndexes = array_keys($assignedPetIndexes);
        sort($assignedPetIndexes);

        return array_map('intval', $assignedPetIndexes);
    }

    public function editService(int $index): void
    {
        if (! array_key_exists($index, $this->services)) {
            return;
        }

        $this->resetValidation();
        $this->step = 3;
        $this->dispatch('booking-wizard-focus-target', target: "booking-service-details-heading-{$index}");
    }

    public function submit(CreateBookingRequest $createBookingRequest): void
    {
        $this->syncLegacyPhoneFields();

        try {
            $this->validate($this->allRules());
            $this->validateServiceAvailability();
            $this->validateAssignmentCompatibility();
            $this->validatePetAssignmentsComplete();
            $this->validateDuplicateServices();
        } catch (ValidationException $exception) {
            $this->revealValidationStep($exception->errors());

            throw $exception;
        }

        $createBookingRequest->handle([
            'contact' => $this->contact,
            'pets' => $this->pets,
            'services' => $this->services,
            'idempotency_key' => $this->submissionToken,
            'source' => $this->source,
            'context' => [
                'submitted_from' => 'livewire-booking-wizard',
            ],
        ]);

        $this->submitted = true;
        $this->dispatch('booking-request-submitted');
    }

    public function contactPhoneDisplay(): string
    {
        return PhoneNumber::normalizeContact($this->contact) ?? 'Phone not added';
    }

    /**
     * @return array<string, string>
     */
    public function phoneCountryOptions(): array
    {
        return PhoneNumber::countryOptions();
    }

    private function syncLegacyPhoneFields(): void
    {
        if (filled($this->contact['phone_number'] ?? null) || ! filled($this->contact['phone'] ?? null)) {
            return;
        }

        $this->contact['phone_country'] = PhoneNumber::defaultCountryCode();
        $this->contact['phone_number'] = $this->contact['phone'];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function allRules(): array
    {
        return [
            ...$this->rulesForStep(1),
            ...$this->petRules(false),
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
            2 => $this->petRules(true),
            3 => [
                ...$this->assignmentRules(),
                ...$this->serviceDetailRules(),
            ],
            4 => [
                'contact.name' => ['required', 'string', 'max:120'],
                'contact.email' => ['required', 'email', 'max:255'],
                'contact.phone_country' => ['required', Rule::in(array_keys(PhoneNumber::countryOptions()))],
                'contact.phone_other_country_code' => ['nullable', 'required_if:contact.phone_country,OTHER', 'regex:/^\+?[0-9]{1,3}$/'],
                'contact.phone_number' => [
                    'required',
                    'string',
                    'max:40',
                    new ValidPhoneNumber(
                        $this->contact['phone_country'] ?? null,
                        $this->contact['phone_other_country_code'] ?? null,
                    ),
                ],
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
            'services' => ['required', 'array', 'min:1', 'max:'.$this->maxServiceItems()],
            'services.*.service_key' => ['required', Rule::in(array_keys(BookingRequestSchema::allServiceOptions()))],
        ];

        foreach ($this->services as $index => $service) {
            $variantOptions = BookingRequestSchema::variantOptions($service['service_key'] ?? null);
            $selectionMode = BookingRequestSchema::serviceSelectionMode($service['service_key'] ?? null);
            $rules["services.{$index}.service_variant"] = [
                $selectionMode === 'single' && $variantOptions !== [] ? 'required' : 'nullable',
                Rule::in(array_keys($variantOptions)),
            ];

            if ($selectionMode === 'multiple') {
                $rules["services.{$index}.details.care_needs"] = ['required', 'array', 'min:1'];
                $rules["services.{$index}.details.care_needs.*"] = ['string', Rule::in(array_keys($variantOptions)), 'distinct'];
            }
        }

        return $rules;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function petRules(bool $enforceCompatibility): array
    {
        $rules = [
            'pets' => ['required', 'array', 'min:1', 'max:8'],
            'pets.*.name' => ['required', 'string', 'max:80'],
            'pets.*.species' => ['required', Rule::in($enforceCompatibility ? array_keys($this->petTypeOptions()) : ['dog', 'cat'])],
            'pets.*.size' => ['nullable', Rule::in(array_keys($this->petSizeOptions()))],
            'pets.*.breed' => ['nullable', 'string', 'max:120'],
            'pets.*.age' => ['nullable', Rule::in(array_keys($this->petAgeOptions()))],
            'pets.*.sex' => ['required', Rule::in(['male', 'female'])],
            'pets.*.notes' => ['nullable', 'string', 'max:1000'],
        ];

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

        foreach ($this->services as $service) {
            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                $petIndex = (int) $petIndex;

                if (! isset($this->pets[$petIndex])) {
                    continue;
                }

                if (app(BookingPricingCatalog::class)->requiresPetSize(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                ) && ($this->pets[$petIndex]['species'] ?? null) === 'dog') {
                    $rules["pets.{$petIndex}.size"] = [
                        'required',
                        Rule::in(array_keys($this->petSizeOptions())),
                    ];
                }

                if (($service['service_key'] ?? null) === 'relocation') {
                    $rules["pets.{$petIndex}.breed"] = ['required', 'string', 'max:120'];
                }

                if (in_array($service['service_key'] ?? null, ['vet-care', 'relocation'], true)) {
                    $rules["pets.{$petIndex}.age"] = ['required', Rule::in(array_keys($this->petAgeOptions()))];
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
                } elseif (in_array($field['type'], ['select', 'country'], true)) {
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

                $messages["services.{$serviceIndex}.assigned_pet_ids"][] = $this->petCompatibilityReason($service, $this->pets[(int) $petIndex]) ?? 'Choose a pet that matches this service.';
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function validatePetAssignmentsComplete(): void
    {
        $assignedPetIndexes = collect($this->services)
            ->flatMap(fn (array $service): array => array_map('intval', $service['assigned_pet_ids'] ?? []))
            ->unique()
            ->all();
        $messages = [];

        foreach ($this->pets as $petIndex => $pet) {
            if (in_array($petIndex, $assignedPetIndexes, true)) {
                continue;
            }

            $messages["pets.{$petIndex}.assignments"] = 'Assign this pet to at least one service or remove it.';
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
                $message = 'This matches another service item. Assign more pets to one item, or change the schedule or service details.';
                $messages["services.{$index}.assigned_pet_ids"][] = $message;
                $messages["services.{$seen[$signature]}.assigned_pet_ids"][] = $message;

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

        $normalise = function (mixed $value) use (&$normalise): mixed {
            if (is_array($value)) {
                if (array_is_list($value)) {
                    return array_map($normalise, $value);
                }

                $normalised = [];

                foreach ($value as $key => $item) {
                    $normalised[(string) $key] = $normalise($item);
                }

                ksort($normalised);

                return $normalised;
            }

            if (is_string($value) && trim($value) === '') {
                return null;
            }

            return $value;
        };

        return json_encode($normalise($identity), JSON_THROW_ON_ERROR);
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
     * @param  array<string, mixed>  $service
     */
    public function serviceNeedsSelection(array $service): bool
    {
        $serviceKey = $service['service_key'] ?? null;
        $selectionMode = BookingRequestSchema::serviceSelectionMode($serviceKey);

        return match ($selectionMode) {
            'multiple' => app(BookingPricingCatalog::class)->careNeeds($service['details']['care_needs'] ?? []) === [],
            'single' => app(BookingPricingCatalog::class)->serviceOptionRequired($serviceKey)
                && empty($service['service_variant']),
            default => false,
        };
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<int, array<string, mixed>>
     */
    private function petsForService(array $service): array
    {
        return collect($service['assigned_pet_ids'] ?? [])
            ->map(fn (mixed $petId): ?array => $this->pets[(int) $petId] ?? null)
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function newService(?string $service, ?string $variant): array
    {
        $selectionMode = BookingRequestSchema::serviceSelectionMode($service);
        $details = $selectionMode === 'multiple' ? ['care_needs' => []] : [];

        if ($selectionMode === 'multiple' && filled($variant)) {
            $details['care_needs'] = [$variant];
            $variant = null;
        }

        if ($selectionMode === 'pet_types') {
            $variant = null;
        }

        if ($service === 'relocation') {
            $details = BookingRequestSchema::relocationDetails($variant, $details);
        }

        return [
            'service_key' => $service,
            'service_variant' => $variant,
            'assigned_pet_ids' => [],
            'requested_date' => null,
            'requested_time' => null,
            'location' => null,
            'details' => $details,
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

<div data-booking-draft="waggies-booking-request-v2" data-booking-context="{{ $draftContextKey }}" data-booking-draft-label="Booking request">
    <input type="hidden" wire:model.live="submissionToken" data-booking-draft-model="submissionToken" tabindex="-1" aria-hidden="true">
    <div data-booking-announcement class="sr-only" aria-live="polite" aria-atomic="true"></div>
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
                1 => ['eyebrow' => 'STEP 1 OF 5', 'title' => 'Choose and configure services', 'description' => 'Choose each service you need, then select the options that apply.'],
                2 => ['eyebrow' => 'STEP 2 OF 5', 'title' => 'Add your pet'.(count($pets) > 1 ? 's' : ''), 'description' => 'Add each pet once. This is your pet list; we will match pets to services next.'],
                3 => ['eyebrow' => 'STEP 3 OF 5', 'title' => 'Assign pets to services', 'description' => 'Choose which pet receives each service, then add the details that service needs.'],
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

                @if($step > 1)
                    <section class="mb-7 rounded-xl border border-primary/10 bg-surface-purple/45 p-4" aria-labelledby="selected-services-heading">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-eyebrow text-primary-dark/50">YOUR REQUEST SO FAR</p>
                                <h3 id="selected-services-heading" class="mt-1 text-sm font-bold text-primary-dark">Selected services</h3>
                            </div>
                            <button type="button" wire:click="goToStep(1)" class="shrink-0 text-sm font-semibold text-primary underline underline-offset-4">Change</button>
                        </div>
                        <ul class="mt-3 space-y-2 text-sm text-primary-dark/75">
                            @foreach($services as $service)
                                <li class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-medium">{{ $this->serviceSummary($service) }}</span>
                                    <span class="text-xs text-primary-dark/55">{{ $this->serviceStatus($service) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($step === 2)
                            <p class="mt-3 text-xs leading-relaxed text-primary-dark/60">Add each animal once. The same pet can receive more than one service; you will choose those matches in the next step.</p>
                        @endif
                    </section>
                @endif

                <x-waggies.booking-progress :step="$step" :labels="$progressLabels" />

                @if($this->currentStepErrorCount() > 0)
                    <div id="booking-error-summary" data-booking-error-summary class="mb-6 rounded-xl border border-error/30 bg-error-light p-4 text-sm text-primary-dark" role="alert" tabindex="-1" aria-labelledby="booking-error-summary-heading">
                        <p id="booking-error-summary-heading" class="font-semibold">{{ $this->currentStepErrorCount() }} {{ $this->currentStepErrorCount() === 1 ? 'issue needs' : 'issues need' }} your attention.</p>
                        <ul class="mt-2 space-y-1">
                            @foreach($this->currentStepErrorEntries() as $error)
                                <li><a href="#{{ $this->errorAnchor($error['key']) }}" class="font-medium text-error underline decoration-error/40 underline-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-error">{{ $error['message'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div wire:loading class="sr-only" role="status" aria-live="polite">
                    Updating your request…
                </div>

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
                                            <p class="mt-1 text-sm text-primary-dark/60">Select the care your pet needs. You can add another service below.</p>
                                        </div>
                                        @if(count($services) > 1)
                                            <button type="button" wire:click="removeService({{ $index }})" aria-label="Remove {{ $this->serviceLabel($service['service_key'] ?? null) }} service" class="min-h-11 shrink-0 rounded-lg border border-transparent px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 transition-colors hover:border-error/30 hover:bg-error-light hover:text-error focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-error">Remove</button>
                                        @endif
                                    </div>

                                    <fieldset class="mt-5" aria-labelledby="booking-service-{{ $index }}-choice-heading">
                                        <legend id="booking-service-{{ $index }}-choice-heading" class="text-sm font-semibold text-primary-dark">Which service do you need?</legend>
                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                        @foreach($this->serviceOptions() as $serviceKey => $serviceLabel)
                                            @php $available = $this->serviceAvailable($serviceKey); @endphp
                                            <label class="group flex min-h-16 items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors {{ $service['service_key'] === $serviceKey ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'cursor-pointer' }}">
                                                <input type="radio" name="booking-service-{{ $index }}" value="{{ $serviceKey }}" @checked($service['service_key'] === $serviceKey) @disabled(! $available) wire:click="serviceChanged({{ $index }}, '{{ $serviceKey }}')" class="sr-only peer">
                                                <span>
                                                    <span class="block font-semibold text-primary-dark">{{ $serviceLabel }}</span>
                                                    @if(! $available)
                                                        <span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>
                                                    @endif
                                                </span>
                                                <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                            </label>
                                        @endforeach
                                        </div>
                                    </fieldset>

                                    @if($service['service_key'])
                                            @php
                                                $selectionMode = BookingRequestSchema::serviceSelectionMode($service['service_key']);
                                                $variantOptions = $this->allVariantOptions($service['service_key']);
                                            @endphp
                                            @if($selectionMode === 'pet_types')
                                                <div class="mt-6 rounded-xl border border-primary/10 bg-surface-purple/45 p-4">
                                                    <p class="text-eyebrow text-primary-dark/50">NEXT</p>
                                                    <h4 class="mt-1 text-base font-bold text-primary-dark">Add your pets next</h4>
                                                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/65">This boarding service can include dogs, cats, or both. Add each pet in the next step and we will match them to this stay.</p>
                                                </div>
                                            @elseif($selectionMode === 'multiple')
                                                @php
                                                    $careNeeds = is_array($service['details']['care_needs'] ?? null)
                                                        ? $service['details']['care_needs']
                                                        : [];
                                                @endphp
                                                <div class="mt-6 border-t border-primary/10 pt-5">
                                                    <p class="text-eyebrow text-primary-dark/50">NEXT</p>
                                                    <h4 class="mt-1 text-base font-bold text-primary-dark">Choose all care needs that apply</h4>
                                                    <p class="mt-1 text-sm leading-relaxed text-primary-dark/60">Select one or more reasons for the veterinary request. The team will review them together.</p>
                                                </div>
                                                <div class="mt-5">
                                                    <fieldset id="booking-service-{{ $index }}-care-needs" tabindex="-1" class="rounded-2xl border border-primary/15 bg-surface-purple/30 p-4 {{ $errors->has('services.'.$index.'.details.care_needs') || $errors->has('services.'.$index.'.details.care_needs.*') ? 'border-danger/60 ring-2 ring-danger/15' : '' }}">
                                                        <legend class="px-1 text-sm font-semibold text-primary-dark">What does your pet need help with? <span class="text-danger" aria-hidden="true">*</span></legend>
                                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                                            @foreach($variantOptions as $key => $label)
                                                                @php $available = $this->variantAvailable($service['service_key'], $key); @endphp
                                                                <label wire:key="booking-service-{{ $index }}-care-need-{{ $key }}" class="flex min-h-14 w-full items-center justify-between gap-3 rounded-xl border px-4 py-3 text-left text-sm font-semibold transition-colors {{ in_array($key, $careNeeds, true) ? 'border-primary bg-white ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'cursor-pointer' }}">
                                                                    <input type="checkbox" name="booking-service-{{ $index }}-care-needs" value="{{ $key }}" @checked(in_array($key, $careNeeds, true)) @disabled(! $available) wire:model.live="services.{{ $index }}.details.care_needs" class="sr-only peer">
                                                                    <span>{{ $label }}@if(! $available)<span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                                                    <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @if($errors->has('services.'.$index.'.details.care_needs') || $errors->has('services.'.$index.'.details.care_needs.*'))
                                                            <p class="mt-2 text-sm font-medium text-danger" role="alert">{{ $errors->first('services.'.$index.'.details.care_needs') ?: $errors->first('services.'.$index.'.details.care_needs.*') }}</p>
                                                        @endif
                                                    </fieldset>
                                                    @if($this->serviceNeedsSelection($service))
                                                        <p class="mt-4 rounded-xl bg-surface-purple/55 p-3 text-sm leading-relaxed text-primary-dark/65">Choose at least one care need to continue.</p>
                                                    @endif
                                                </div>
                                            @elseif($variantOptions)
                                                <div class="mt-6 border-t border-primary/10 pt-5">
                                                    <p class="text-eyebrow text-primary-dark/50">NEXT</p>
                                                    <h4 class="mt-1 text-base font-bold text-primary-dark">Choose a service option</h4>
                                                    <p class="mt-1 text-sm leading-relaxed text-primary-dark/60">Choose the option that best matches what your pet needs.</p>
                                                </div>
                                                <div class="mt-5">
                                                    <fieldset id="booking-service-{{ $index }}-variant" tabindex="-1" class="rounded-2xl border border-primary/15 bg-surface-purple/30 p-4 {{ $errors->has('services.'.$index.'.service_variant') ? 'border-danger/60 ring-2 ring-danger/15' : '' }}">
                                                        <legend class="px-1 text-sm font-semibold text-primary-dark">{{ $this->serviceVariantQuestion($service['service_key']) }} <span class="text-danger" aria-hidden="true">*</span></legend>
                                                        <div class="mt-3 flex flex-wrap gap-3">
                                                            @foreach($variantOptions as $key => $label)
                                                                @php $available = $this->variantAvailable($service['service_key'], $key); @endphp
                                                                <label class="flex min-h-14 w-full items-center justify-between gap-3 rounded-xl border px-4 py-3 text-left text-sm font-semibold transition-colors sm:w-[calc(50%-0.375rem)] lg:w-auto lg:min-w-40 lg:flex-1 {{ $service['service_variant'] === $key ? 'border-primary bg-white ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'cursor-pointer' }}">
                                                                    <input type="radio" name="booking-service-{{ $index }}-variant" value="{{ $key }}" @checked($service['service_variant'] === $key) @disabled(! $available) wire:click="variantChanged({{ $index }}, '{{ $key }}')" class="sr-only peer">
                                                                    <span>{{ $label }}@if(! $available)<span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                                                    <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @if($errors->has('services.'.$index.'.service_variant'))
                                                            <p class="mt-2 text-sm font-medium text-danger" role="alert">{{ $errors->first('services.'.$index.'.service_variant') }}</p>
                                                        @endif
                                                    </fieldset>
                                                    @if(! $service['service_variant'])
                                                        <p class="mt-4 rounded-xl bg-surface-purple/55 p-3 text-sm leading-relaxed text-primary-dark/65">Choose one option to continue.</p>
                                                    @endif
                                                </div>
                                            @endif
                                    @else
                                        <p class="mt-5 rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a service above to see the options and details it needs.</p>
                                    @endif
                                </div>
                            @endforeach

                            @if($choosingService)
                                <section class="rounded-2xl border-2 border-primary/20 bg-surface-purple/35 p-5 sm:p-6" aria-labelledby="add-service-heading">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">ADD TO YOUR REQUEST</p>
                                            <h3 id="add-service-heading" class="mt-1 font-serif text-xl font-bold text-primary-dark">Which service do you also need?</h3>
                                            <p class="mt-2 text-sm leading-relaxed text-primary-dark/65">Choose a service first. We will then show only the options and details that belong to it.</p>
                                        </div>
                                        <button type="button" wire:click="cancelAddService" class="min-h-11 shrink-0 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Cancel</button>
                                    </div>
                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        @foreach($this->serviceOptions() as $serviceKey => $serviceLabel)
                                            @php $available = $this->serviceAvailable($serviceKey); @endphp
                                            <button type="button" @if($available) wire:click="chooseAdditionalService('{{ $serviceKey }}')" @else disabled @endif class="flex min-h-16 items-center justify-between gap-3 rounded-xl border border-primary/15 bg-white p-4 text-left transition-colors hover:border-primary/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ ! $available ? 'cursor-not-allowed opacity-55' : '' }}">
                                                <span><span class="block font-semibold text-primary-dark">{{ $serviceLabel }}</span>@if(! $available)<span class="mt-1 block text-xs text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                                <x-waggies.icon name="arrow-forward" size="18" class="shrink-0 text-primary" />
                                            </button>
                                        @endforeach
                                    </div>
                                </section>
                            @else
                                <button type="button" wire:click="addService" @disabled(count($services) >= $this->maxServiceItems()) class="inline-flex min-h-12 w-fit items-center gap-2 rounded-xl border border-primary/25 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary disabled:cursor-not-allowed disabled:opacity-50">
                                    <span aria-hidden="true" class="text-lg leading-none">+</span> Add another service
                                </button>
                                @if(count($services) >= $this->maxServiceItems())
                                    <p class="text-xs font-medium text-primary-dark/60" role="status">You can include up to {{ $this->maxServiceItems() }} service items in one request.</p>
                                @endif
                            @endif
                        </fieldset>
                    @endif

                    @if($step === 2)
                        <fieldset class="flex flex-col gap-6">
                            <legend class="sr-only">Pet details</legend>
                            @foreach($pets as $index => $pet)
                                <div wire:key="booking-pet-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">PET PROFILE</p>
                                            <h3 id="booking-pet-{{ $index }}-heading" tabindex="-1" class="mt-1 font-serif text-xl font-bold text-primary-dark focus:outline-none">{{ $pet['name'] ? 'About '.$pet['name'] : 'Add a pet' }}</h3>
                                        </div>
                                        @if(count($pets) > 1)
                                            <button type="button" wire:click="removePet({{ $index }})" aria-label="Remove {{ $pet['name'] ?: 'pet '.($index + 1) }}" class="min-h-11 shrink-0 rounded-lg border border-transparent px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 transition-colors hover:border-error/30 hover:bg-error-light hover:text-error focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-error">Remove</button>
                                        @endif
                                    </div>

                                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                        <x-waggies.field id="booking-pet-{{ $index }}-name" label="Pet name" :error="$errors->first('pets.'.$index.'.name')" required>
                                            <input id="booking-pet-{{ $index }}-name" wire:model.live.blur="pets.{{ $index }}.name" type="text" maxlength="80" autocomplete="off" class="contact-input">
                                        </x-waggies.field>
                                        <fieldset class="sm:col-span-2" aria-labelledby="booking-pet-{{ $index }}-species-heading">
                                            <legend id="booking-pet-{{ $index }}-species-heading" class="text-sm font-medium text-primary-dark">Pet type <span class="text-danger" aria-hidden="true">*</span></legend>
                                            <div class="mt-2 grid gap-3 sm:grid-cols-2">
                                                @foreach($this->petTypeOptions() as $key => $label)
                                                    <label wire:key="booking-pet-{{ $index }}-species-{{ $key }}" class="flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-primary {{ ($pet['species'] ?? null) === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                        <input type="radio" name="booking-pet-{{ $index }}-species" value="{{ $key }}" @checked(($pet['species'] ?? null) === $key) wire:model.live="pets.{{ $index }}.species" class="sr-only peer">
                                                        <span class="font-semibold text-primary-dark">{{ $label }}</span>
                                                        <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('pets.'.$index.'.species') <p class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p> @enderror
                                        </fieldset>
                                        @if($pet['species'] === 'dog' && $this->petNeedsSize($index))
                                            <fieldset class="sm:col-span-2" aria-labelledby="booking-pet-{{ $index }}-size-heading">
                                                <legend id="booking-pet-{{ $index }}-size-heading" class="text-sm font-semibold text-primary-dark">Dog size <span class="text-danger" aria-hidden="true">*</span></legend>
                                                <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">Choose the closest size. You do not need to know your dog’s exact weight.</p>
                                                <div class="mt-3 grid gap-3 sm:grid-cols-3">
                                                    @foreach($this->petSizeOptions() as $size => $sizeOption)
                                                        <label class="cursor-pointer rounded-xl border p-4 transition-colors {{ ($pet['size'] ?? null) === $size ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                            <input type="radio" name="booking-pet-{{ $index }}-size" value="{{ $size }}" @checked(($pet['size'] ?? null) === $size) wire:model.live="pets.{{ $index }}.size" class="sr-only peer">
                                                            <span class="block font-semibold text-primary-dark">{{ $sizeOption['label'] }}</span>
                                                            @if($sizeOption['examples'])<span class="mt-1 block text-xs leading-relaxed text-primary-dark/60">{{ $sizeOption['examples'] }}</span>@endif
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('pets.'.$index.'.size') <p class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p> @enderror
                                            </fieldset>
                                        @endif
                                    </div>


                                    <div class="mt-6 border-t border-primary/10 pt-5">
                                        <p class="text-sm font-semibold text-primary-dark">Optional details about this pet</p>
                                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <x-waggies.field id="booking-pet-{{ $index }}-breed" label="Breed" :error="$errors->first('pets.'.$index.'.breed')" :help="$this->petBreedHelp($index)">
                                                <input id="booking-pet-{{ $index }}-breed" wire:model.live.blur="pets.{{ $index }}.breed" type="text" maxlength="120" class="contact-input">
                                            </x-waggies.field>
                                            <x-waggies.select id="booking-pet-{{ $index }}-age" label="Age or life stage" wire:model.live="pets.{{ $index }}.age" :error="$errors->first('pets.'.$index.'.age')" :help="$this->petAgeHelp($index)" :required="$this->petAgeRequired($index)">
                                                <option value="">Select life stage</option>
                                                @foreach($this->petAgeOptions() as $ageKey => $ageLabel)
                                                    <option value="{{ $ageKey }}">{{ $ageLabel }}</option>
                                                @endforeach
                                            </x-waggies.select>
                                            <fieldset aria-labelledby="booking-pet-{{ $index }}-sex-heading">
                                                <legend id="booking-pet-{{ $index }}-sex-heading" class="text-sm font-medium text-primary-dark">Sex <span class="text-danger" aria-hidden="true">*</span></legend>
                                                <div class="mt-2 grid gap-3 sm:grid-cols-2">
                                                    @foreach(['male' => 'Male', 'female' => 'Female'] as $key => $label)
                                                        <label wire:key="booking-pet-{{ $index }}-sex-{{ $key }}" class="flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-xl border p-3 text-left transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-primary {{ ($pet['sex'] ?? null) === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                            <input type="radio" name="booking-pet-{{ $index }}-sex" value="{{ $key }}" @checked(($pet['sex'] ?? null) === $key) wire:model.live="pets.{{ $index }}.sex" class="sr-only peer">
                                                            <span class="font-semibold text-primary-dark">{{ $label }}</span>
                                                            <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('pets.'.$index.'.sex') <p class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p> @enderror
                                            </fieldset>
                                            <x-waggies.field id="booking-pet-{{ $index }}-notes" label="Pet notes" :error="$errors->first('pets.'.$index.'.notes')" help="Optional. Share temperament, routines, or care notes." class="sm:col-span-2">
                                                <textarea id="booking-pet-{{ $index }}-notes" wire:model.live.blur="pets.{{ $index }}.notes" rows="3" maxlength="1000" class="contact-input resize-y"></textarea>
                                            </x-waggies.field>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addPet" @disabled(count($pets) >= $this->maxPets()) aria-describedby="booking-pet-limit" class="inline-flex min-h-12 w-fit items-center gap-2 rounded-xl border border-primary/25 px-4 text-sm font-semibold text-primary-dark transition-colors hover:border-primary hover:bg-surface-purple disabled:cursor-not-allowed disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                <span aria-hidden="true" class="text-lg leading-none">+</span> {{ count($pets) >= $this->maxPets() ? 'Maximum pets reached' : 'Add another pet' }}
                            </button>
                            <p id="booking-pet-limit" class="text-xs text-primary-dark/55">You can add up to {{ $this->maxPets() }} pets. Each pet can receive one or more of your selected services.</p>
                        </fieldset>
                    @endif

                    @if($step === 3)
                        <fieldset class="flex flex-col gap-6">
                            <legend class="sr-only">Match pets and service details</legend>
                            <section class="rounded-xl border border-primary/10 bg-surface-purple/45 p-4" aria-labelledby="assignment-overview-heading">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-eyebrow text-primary-dark/50">ASSIGNMENT CHECK</p>
                                        <h3 id="assignment-overview-heading" class="mt-1 text-sm font-bold text-primary-dark">Every pet needs a service</h3>
                                    </div>
                                    <span class="text-xs font-semibold text-primary-dark/55">{{ collect($services)->sum(fn (array $service): int => $this->serviceAssignedCount($service)) }} matches</span>
                                </div>
                                <ul class="mt-3 grid gap-2 text-sm sm:grid-cols-2">
                                    @foreach($pets as $petIndex => $pet)
                                        <li id="booking-pet-assignment-{{ $petIndex }}" class="flex items-start justify-between gap-3 rounded-lg bg-white/70 px-3 py-2 {{ $this->petAssignedServiceCount($petIndex) === 0 ? 'ring-1 ring-error/30' : '' }}">
                                            <span><span class="font-semibold text-primary-dark">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }}</span><span class="mt-0.5 block text-xs text-primary-dark/55">{{ $this->petAssignedServiceCount($petIndex) > 0 ? $this->petAssignedServiceCount($petIndex).' service'.($this->petAssignedServiceCount($petIndex) === 1 ? '' : 's') : 'Not assigned yet' }}</span></span>
                                            <span class="flex shrink-0 items-center gap-3">
                                                @if($this->petAssignedServiceCount($petIndex) > 0)
                                                    <x-waggies.icon name="check-circle" variant="filled" size="18" class="text-success" />
                                                @else
                                                    <span class="text-xs font-semibold text-error">Needs a match</span>
                                                @endif
                                                @if(count($pets) > 1)
                                                    <button type="button" wire:click="removePet({{ $petIndex }})" class="text-xs font-semibold text-primary underline decoration-primary/30 underline-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                                @endif
                                            </span>
                                            @error('pets.'.$petIndex.'.assignments') <span class="sr-only">{{ $message }}</span> @enderror
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                            @foreach($services as $index => $service)
                                @php $fields = $this->serviceFields($service); @endphp
                                <section wire:key="booking-service-details-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="booking-service-details-heading-{{ $index }}">
                                    <div>
                                        <p class="text-eyebrow text-primary-dark/50">SERVICE</p>
                                        <h3 id="booking-service-details-heading-{{ $index }}" class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h3>
                                        <p class="mt-1 text-sm text-primary-dark/60">{{ $this->servicePetRequirement($service) }} Assign at least one compatible pet.</p>
                                        @if($this->isDuplicateService($index))
                                            <p class="mt-3 rounded-lg border border-danger/25 bg-error-light p-3 text-xs font-medium leading-relaxed text-primary-dark" role="alert">This is an identical service item. Assign more pets to one service, or change this service’s schedule or details.</p>
                                        @endif
                                    </div>

                                    <div id="booking-service-{{ $index }}-assignment" class="mt-5 grid gap-3 sm:grid-cols-2">
                                        @foreach($pets as $petIndex => $pet)
                                            @php $compatible = $this->petCompatible($service, $pet); @endphp
                                            <label class="flex min-h-16 items-center gap-3 rounded-xl border p-4 transition-colors {{ in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true) ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15' }} {{ ! $compatible ? 'cursor-not-allowed bg-surface/70 opacity-60' : 'cursor-pointer hover:border-primary/40' }}">
                                                <input type="checkbox" value="{{ $petIndex }}" wire:model.live="services.{{ $index }}.assigned_pet_ids" @disabled(! $compatible) aria-describedby="booking-service-{{ $index }}-pet-{{ $petIndex }}-status" class="size-5 rounded border-primary/30 text-primary focus:ring-primary">
                                                <span class="min-w-0">
                                                    <span class="block font-semibold text-primary-dark">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }}</span>
                                                    <span id="booking-service-{{ $index }}-pet-{{ $petIndex }}-status" class="mt-1 block text-xs text-primary-dark/60">{{ $this->petSpeciesLabel($pet['species'] ?? null) }}{{ ! $compatible ? ' · '.$this->petCompatibilityReason($service, $pet) : '' }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="mt-3 text-xs font-semibold {{ $this->serviceAssignedCount($service) > 0 ? 'text-success' : 'text-error' }}">{{ $this->serviceAssignedCount($service) > 0 ? $this->serviceAssignedCount($service).' pet'.($this->serviceAssignedCount($service) === 1 ? '' : 's').' assigned' : 'Needs one compatible pet' }}</p>
                                    @error('services.'.$index.'.assigned_pet_ids') <p class="mt-2 text-sm text-error">{{ $message }}</p> @enderror

                                    @if($service['service_key'])
                                        <div class="mt-6 border-t border-primary/10 pt-5">
                                            <p class="text-sm font-semibold text-primary-dark">Details for this service</p>
                                            @if($service['service_key'] === 'relocation' && $service['service_variant'])
                                                <div class="mt-4 rounded-2xl border border-primary/15 bg-surface-purple/30 p-4" aria-labelledby="booking-service-{{ $index }}-route-heading">
                                                    <p id="booking-service-{{ $index }}-route-heading" class="text-eyebrow text-primary-dark/50">FLIGHT ROUTE</p>
                                                    <div class="mt-3 grid items-stretch gap-3 sm:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] sm:items-center">
                                                        <div class="rounded-xl border border-primary/10 bg-white p-3">
                                                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.12em] text-primary-dark/45">From</p>
                                                            <p class="mt-1 text-sm font-semibold leading-relaxed text-primary-dark">{{ $this->relocationEndpointLabel($service, 'origin') }}</p>
                                                        </div>
                                                        <span class="hidden text-xl font-semibold text-primary/55 sm:block" aria-hidden="true">→</span>
                                                        <span class="text-center text-xl font-semibold text-primary/55 sm:hidden" aria-hidden="true">↓</span>
                                                        <div class="rounded-xl border border-primary/10 bg-white p-3">
                                                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.12em] text-primary-dark/45">To</p>
                                                            <p class="mt-1 text-sm font-semibold leading-relaxed text-primary-dark">{{ $this->relocationEndpointLabel($service, 'destination') }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 text-xs leading-relaxed text-primary-dark/55">The Abuja airport endpoint is fixed. Choose the other country below, then add any flight details you already have.</p>
                                                </div>
                                            @endif
                                            @if($this->serviceAssignedCount($service) > 1)
                                                <p class="mt-2 rounded-lg bg-surface-purple/45 p-3 text-xs leading-relaxed text-primary-dark/65">This information applies to every pet assigned to this service. If their needs differ, mention each pet by name.</p>
                                            @endif
                                            @php $hasDateField = collect($fields)->contains(fn (array $field): bool => ($field['type'] ?? null) === 'date'); @endphp
                                            @if($hasDateField)
                                                <p class="mt-3 text-xs font-medium text-primary-dark/55">Dates and times use Africa/Lagos time — WAT (UTC+1).</p>
                                            @endif
                                            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                                @foreach($fields as $field)
                                                    @continue(! $this->fieldVisible($field, $service))
                                                    @continue(($field['fixed'] ?? false) === true)
                                                    @php
                                                        $model = $this->fieldModel($index, $field);
                                                        $fieldId = 'booking-'.$index.'-'.$field['key'];
                                                        $fieldValue = ($field['scope'] ?? 'details') === 'service' ? ($service[$field['key']] ?? null) : ($service['details'][$field['key']] ?? null);
                                                    @endphp
                                                    @if($field['type'] === 'textarea')
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['placeholder'] ?? null" :required="$field['required']" class="sm:col-span-2">
                                                            <textarea id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" rows="3" maxlength="2000" class="contact-input resize-y"></textarea>
                                                        </x-waggies.field>
                                                    @elseif($field['type'] === 'country')
                                                        <x-waggies.searchable-select :id="$fieldId" :label="$field['label']" :options="$field['options']" :placeholder="$field['placeholder']" wire:model.live="{{ $model }}" :error="$errors->first($model)" :required="$field['required']" />
                                                    @elseif($field['type'] === 'select' && count($field['options']) <= 3)
                                                        <fieldset id="{{ $fieldId }}" aria-labelledby="{{ $fieldId }}-label">
                                                            <legend id="{{ $fieldId }}-label" class="text-sm font-medium text-primary-dark">{{ $field['label'] }}@if($field['required']) <span class="text-danger" aria-hidden="true">*</span>@endif</legend>
                                                            <div class="mt-2 grid gap-3">
                                                                @foreach($field['options'] as $key => $label)
                                                                    <label wire:key="{{ $fieldId }}-{{ $key }}" class="flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-xl border p-3 text-left transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-primary {{ $fieldValue === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                                        <input type="radio" name="{{ $fieldId }}" value="{{ $key }}" @checked($fieldValue === $key) wire:model.live="{{ $model }}" class="sr-only peer">
                                                                        <span class="text-sm font-semibold text-primary-dark">{{ $label }}</span>
                                                                        <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                            @if($errors->first($model)) <p class="mt-2 text-sm text-danger" role="alert">{{ $errors->first($model) }}</p> @endif
                                                        </fieldset>
                                                    @elseif($field['type'] === 'select')
                                                        <x-waggies.select :id="$fieldId" :label="$field['label']" wire:model.live="{{ $model }}" :error="$errors->first($model)" :required="$field['required']">
                                                            <option value="">Select an option</option>
                                                            @foreach($field['options'] as $key => $label)
                                                                <option value="{{ $key }}">{{ $label }}</option>
                                                            @endforeach
                                                        </x-waggies.select>
                                                    @elseif($field['type'] === 'date')
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['help'] ?? null" :required="$field['required']">
                                                            @php $fieldMinimum = $this->dateMinimum($service, $field); @endphp
                                                            <div x-data="waggiesDatePicker({ value: @js($fieldValue), minimum: @js($fieldMinimum) })" @keydown.escape="open = false" class="relative">
                                                                <input id="{{ $fieldId }}-native" x-ref="native" wire:model.live="{{ $model }}" x-on:input="value = $event.target.value" x-on:change="value = $event.target.value" type="date" min="{{ $fieldMinimum }}" hidden aria-hidden="true" tabindex="-1">
                                                                <button id="{{ $fieldId }}" x-ref="trigger" type="button" @click="open = ! open" :aria-expanded="open" aria-haspopup="dialog" class="contact-input flex items-center justify-between gap-3 text-left focus-visible:outline-none" :class="open ? 'ring-2 ring-primary/40' : ''">
                                                                    <span class="min-w-0 flex-1 truncate" :class="value ? 'text-primary-dark' : 'text-primary-dark/45'" x-text="formattedValue() || 'Choose a date'"></span>
                                                                    <x-waggies.icon name="calendar" size="18" class="shrink-0 text-primary/65" />
                                                                </button>
                                                                <div x-show="open" x-cloak @click.outside="open = false" role="dialog" aria-modal="false" aria-label="Choose a date" class="absolute left-0 top-[calc(100%+0.5rem)] z-20 w-full min-w-[18rem] rounded-2xl border border-primary/15 bg-white p-4 shadow-lg">
                                                                    <div class="flex items-center justify-between gap-3">
                                                                        <button type="button" @click="changeMonth(-1)" :disabled="isBeforeMinimumMonth()" aria-label="Previous month" class="flex size-10 items-center justify-center rounded-lg text-primary transition-colors hover:bg-surface-purple disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"><x-waggies.icon name="arrow-back" size="18" /></button>
                                                                        <p class="text-sm font-bold text-primary-dark" aria-live="polite" x-text="monthLabel()"></p>
                                                                        <button type="button" @click="changeMonth(1)" aria-label="Next month" class="flex size-10 items-center justify-center rounded-lg text-primary transition-colors hover:bg-surface-purple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"><x-waggies.icon name="arrow-forward" size="18" /></button>
                                                                    </div>
                                                                    <div class="mt-3 grid grid-cols-7 gap-1 text-center text-[0.68rem] font-bold uppercase tracking-wide text-primary-dark/45" aria-hidden="true">
                                                                        <template x-for="weekday in weekdays()" :key="weekday"><span x-text="weekday"></span></template>
                                                                    </div>
                                                                    <div class="mt-2 grid grid-cols-7 gap-1" role="grid" aria-label="Calendar dates">
                                                                        <template x-for="(day, dayIndex) in days()" :key="day || `empty-${dayIndex}`">
                                                                            <span class="flex aspect-square items-center justify-center">
                                                                                <button x-show="day" type="button" @click="choose(day)" :disabled="isDisabled(day)" :aria-current="isToday(day) ? 'date' : null" :aria-pressed="isSelected(day)" :class="{ 'bg-primary text-white': isSelected(day), 'ring-1 ring-primary': isToday(day) && ! isSelected(day), 'text-primary-dark/30': isDisabled(day), 'text-primary-dark hover:bg-surface-purple': ! isDisabled(day) && ! isSelected(day) }" class="flex size-9 items-center justify-center rounded-lg text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" x-text="day ? Number(day.slice(-2)) : ''"></button>
                                                                            </span>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                                <p x-show="value" x-cloak class="mt-2 text-xs font-medium text-primary-dark/55" x-text="'Selected: ' + formattedValue()"></p>
                                                            </div>
                                                        </x-waggies.field>
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
                                            <p class="text-sm font-semibold text-primary-dark" wire:loading.remove>Current estimate</p>
                                            <p class="text-sm font-semibold text-primary-dark" wire:loading>Updating estimate…</p>
                                            <p class="font-semibold text-primary-dark" wire:loading.remove>{{ $this->quoteDisplay($quote, $service) }}</p>
                                        </div>
                                        @if($this->quoteQuantitySummary($service, $quote))
                                            <p class="mt-1 text-xs font-semibold text-primary-dark/60">{{ $this->quoteQuantitySummary($service, $quote) }}</p>
                                        @endif
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
                            <x-waggies.field id="booking-contact-phone" label="Phone or WhatsApp number" :error="$errors->first('contact.phone_number') ?: $errors->first('contact.phone_country')" required>
                                <div class="grid gap-3 sm:grid-cols-[minmax(0,15rem)_minmax(0,1fr)]">
                                    <div>
                                        <label for="booking-contact-phone-country" class="sr-only">Country calling code</label>
                                        <select id="booking-contact-phone-country" wire:model.live.blur="contact.phone_country" class="contact-input">
                                            @foreach($this->phoneCountryOptions() as $countryCode => $countryLabel)
                                                <option value="{{ $countryCode }}">{{ $countryLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="booking-contact-phone-number" class="sr-only">Phone number</label>
                                        <input id="booking-contact-phone-number" wire:model.live.blur="contact.phone_number" type="tel" autocomplete="tel-national" inputmode="tel" maxlength="40" placeholder="808 081 1902" class="contact-input">
                                    </div>
                                </div>
                                @if(($contact['phone_country'] ?? null) === 'OTHER')
                                    <div class="mt-3">
                                        <label for="booking-contact-other-country-code" class="text-xs font-semibold text-primary-dark">Country calling code</label>
                                        <input id="booking-contact-other-country-code" wire:model.live.blur="contact.phone_other_country_code" type="text" inputmode="numeric" autocomplete="tel-country-code" maxlength="4" placeholder="+___" class="contact-input mt-1">
                                    </div>
                                @endif
                                @if($errors->has('contact.phone_other_country_code'))
                                    <p class="mt-2 text-sm text-danger" role="alert">{{ $errors->first('contact.phone_other_country_code') }}</p>
                                @endif
                                <p class="mt-2 text-xs leading-relaxed text-primary-dark/60">Choose the country code, then enter the number without the country code. You can include spaces or a leading 0.</p>
                            </x-waggies.field>
                            <x-waggies.select id="booking-contact-method" label="Preferred contact method" wire:model.live="contact.preferred_contact_method" :error="$errors->first('contact.preferred_contact_method')">
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
                                            <p class="text-eyebrow text-primary-dark/50">SERVICE</p>
                                            <h4 class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h4>
                                            <p class="mt-1 text-sm text-primary-dark/60">{{ $this->scheduleSummary($service) }}</p>
                                        </div>
                                        <button type="button" wire:click="editService({{ $index }})" class="shrink-0 text-sm font-semibold text-primary underline underline-offset-4">Change</button>
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
                                            <p class="mt-2 text-sm font-semibold text-primary-dark">{{ $this->quoteDisplay($quote, $service) }}</p>
                                            @if($this->quoteQuantitySummary($service, $quote))<p class="mt-1 text-xs text-primary-dark/60">{{ $this->quoteQuantitySummary($service, $quote) }}</p>@endif
                                            @if(($quote['discount']['percentage'] ?? 0) > 0)
                                                <p class="mt-1 text-xs text-primary-dark/60">Includes {{ $quote['discount']['percentage'] }}% multiple-pet discount.</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($this->serviceReviewDetails($service) !== [])
                                        <dl class="mt-5 grid gap-3 border-t border-primary/10 pt-5 text-sm sm:grid-cols-2">
                                            @foreach($this->serviceReviewDetails($service) as $label => $value)
                                                <div>
                                                    <dt class="font-semibold text-primary-dark">{{ $label }}</dt>
                                                    <dd class="mt-1 text-primary-dark/70">{{ $value }}</dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    @endif
                                </article>
                            @endforeach

                            <section class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-4"><h4 class="font-serif text-xl font-bold text-primary-dark">Pets</h4><button type="button" wire:click="goToStep(2)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <ul class="mt-4 grid gap-4 text-sm text-primary-dark/75 sm:grid-cols-2">
                                    @foreach($this->assignedPetIndexes() as $petIndex)
                                        @php $pet = $pets[$petIndex]; @endphp
                                        <li wire:key="booking-review-pet-{{ $petIndex }}" class="rounded-xl bg-surface-purple/45 p-4">
                                            <p class="font-semibold text-primary-dark">{{ $pet['name'] ?: 'Unnamed pet' }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</p>
                                            <p class="mt-1 text-xs text-primary-dark/55">{{ $this->petAssignedTo($petIndex) }}</p>
                                            @if(($pet['size'] ?? null) || $pet['breed'] || $pet['age'] || $pet['sex'])
                                                <p class="mt-1">{{ implode(' · ', array_filter([$pet['size'] ? ucfirst($pet['size']).' size' : null, $pet['breed'], $this->petAgeLabel($pet['age'] ?? null), $pet['sex'] ? ucfirst($pet['sex']) : null])) }}</p>
                                            @endif
                                            @if($pet['notes'] || ($pet['details']['other_description'] ?? null))<p class="mt-2">{{ $pet['notes'] ?: $pet['details']['other_description'] }}</p>@endif
                                        </li>
                                    @endforeach
                                </ul>
                            </section>

                            <section class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-4"><h4 class="font-serif text-xl font-bold text-primary-dark">Contact</h4><button type="button" wire:click="goToStep(4)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <p class="mt-4 text-sm text-primary-dark/75">{{ $contact['name'] ?: 'Name not added' }} · {{ $contact['email'] ?: 'Email not added' }} · {{ $this->contactPhoneDisplay() }}</p>
                                @if($contact['preferred_contact_method'])<p class="mt-1 text-sm text-primary-dark/60">Preferred contact: {{ ucfirst($contact['preferred_contact_method']) }}</p>@endif
                            </section>
                        </section>
                    @endif

                    <div class="mt-8 border-t border-primary/10 pt-6">
                        <x-waggies.booking-progress :step="$step" :labels="$progressLabels" />
                    </div>

                    @if($this->currentStepErrorCount() > 0)
                        <div class="sticky bottom-3 z-10 flex items-center justify-between gap-3 rounded-xl border border-error/30 bg-error-light p-3 shadow-lg lg:hidden" role="status" aria-live="polite">
                            <span class="text-sm font-semibold text-primary-dark">{{ $this->currentStepErrorCount() }} {{ $this->currentStepErrorCount() === 1 ? 'issue' : 'issues' }} to fix</span>
                            <a href="#booking-error-summary" class="shrink-0 text-sm font-bold text-error underline underline-offset-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-error">Review errors</a>
                        </div>
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
                                    <span wire:loading.remove wire:target="submit">Submit Booking Request</span>
                                    <span wire:loading wire:target="submit">Submitting request...</span>
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
                                <div class="flex items-center justify-between gap-3"><p class="font-semibold text-primary-dark">{{ $service['service_key'] ? $this->serviceLabel($service['service_key']) : 'Service not chosen' }}</p><button type="button" wire:click="goToStep(1)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Change</button></div>
                                <p class="mt-1 font-medium text-primary-dark/80">{{ $this->serviceSummary($service) }}</p>
                                <p class="mt-1 text-primary-dark/60">{{ $this->scheduleSummary($service) }}</p>
                                <p class="mt-1 text-xs font-semibold {{ in_array($this->serviceStatus($service), ['Ready', 'Ready to match'], true) ? 'text-success' : 'text-error' }}">{{ $this->serviceStatus($service) }}</p>
                            </div>
                        @endforeach
                        <div class="py-4"><div class="flex items-center justify-between gap-3"><p class="font-semibold text-primary-dark">Pets</p><button type="button" wire:click="goToStep(2)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Change</button></div><ul class="mt-1 space-y-1 text-primary-dark/60">@foreach($this->assignedPetIndexes() as $petIndex) @php $pet = $pets[$petIndex]; @endphp<li wire:key="booking-sidebar-pet-{{ $petIndex }}">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}<span class="block text-xs text-primary-dark/45">{{ $this->petAssignedTo($petIndex) }}</span></li>@endforeach</ul></div>
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
