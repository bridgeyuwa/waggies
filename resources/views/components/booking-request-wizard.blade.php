<?php

use App\Actions\CreateBookingRequest;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestBuilder;
use App\Support\BookingRequestIntake;
use App\Support\BookingRequestSchema;
use App\Support\BookingRequestWizardRules;
use App\Support\CountryCatalog;
use App\Support\PhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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
        $builder = app(BookingRequestBuilder::class);
        $this->services = [$builder->newService($service, $variant)];
        $this->pets = [$builder->newPet()];
    }

    public function serviceChanged(int $index, ?string $service): void
    {
        if (! array_key_exists($index, $this->services)) {
            return;
        }

        $serviceOptions = BookingRequestSchema::serviceOptions();

        if ($service === null || ! array_key_exists($service, $serviceOptions)) {
            $this->services = app(BookingRequestBuilder::class)->changeService($this->services, $index, null);
            $this->resetValidation();

            return;
        }

        $this->services = app(BookingRequestBuilder::class)->changeService($this->services, $index, $service);
        $this->resetValidation();
    }

    public function toggleService(string $service): void
    {
        if (! $this->serviceAvailable($service) || ! array_key_exists($service, $this->serviceOptions())) {
            return;
        }

        $serviceIndex = collect($this->services)->search(
            fn (array $selectedService): bool => ($selectedService['service_key'] ?? null) === $service,
        );

        if ($serviceIndex !== false) {
            unset($this->services[$serviceIndex]);
            $this->services = array_values($this->services);

            if ($this->services === []) {
                $this->services = [app(BookingRequestBuilder::class)->newService(null, null)];
            }

            $this->dispatch('booking-wizard-announcement', message: $this->serviceLabel($service).' removed from your request.');
            $this->resetValidation();

            return;
        }

        if ($this->selectedServiceCount() >= $this->maxServiceItems()) {
            $this->dispatch('booking-wizard-announcement', message: 'You can include up to '.$this->maxServiceItems().' services in one request.');

            return;
        }

        $emptyIndex = collect($this->services)->search(
            fn (array $selectedService): bool => empty($selectedService['service_key']),
        );

        if ($emptyIndex !== false) {
            $this->services[$emptyIndex] = app(BookingRequestBuilder::class)->newService($service, null);
        } else {
            $this->services[] = app(BookingRequestBuilder::class)->newService($service, null);
        }

        $this->dispatch('booking-wizard-announcement', message: $this->serviceLabel($service).' added to your request.');
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

        if (Str::is('services.*.details.travel_timing', $property)) {
            $parts = explode('.', $property);
            $index = (int) ($parts[1] ?? 0);
            $timing = $this->services[$index]['details']['travel_timing'] ?? null;

            if ($timing === 'not_decided') {
                $this->services[$index]['requested_date'] = null;
                $this->services[$index]['requested_end_date'] = null;
            } elseif ($timing !== 'window') {
                $this->services[$index]['requested_end_date'] = null;
            }
        }

        $rules = app(BookingRequestWizardRules::class)->all($this->services, $this->pets, $this->contact);
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

        $this->services[$index] = app(BookingRequestBuilder::class)->changeVariant(
            $this->services[$index],
            $variant,
        );

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
            $this->services[$emptyIndex] = app(BookingRequestBuilder::class)->newService($service, null);
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

        $this->services[] = app(BookingRequestBuilder::class)->newService($service, null);
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

    public function serviceSelected(string $service): bool
    {
        return collect($this->services)->contains(
            fn (array $selectedService): bool => ($selectedService['service_key'] ?? null) === $service,
        );
    }

    public function selectedServiceCount(): int
    {
        return collect($this->services)->filter(
            fn (array $service): bool => filled($service['service_key'] ?? null),
        )->count();
    }

    public function addPet(): void
    {
        if (count($this->pets) >= 8) {
            $this->dispatch('booking-wizard-announcement', message: 'You can add up to 8 pets to one request.');

            return;
        }

        $this->pets[] = app(BookingRequestBuilder::class)->newPet();
        $index = count($this->pets) - 1;
        $this->dispatch('booking-wizard-focus-target', target: "booking-pet-{$index}-heading");
        $this->dispatch('booking-wizard-announcement', message: 'Pet '.($index + 1).' added.');
    }

    public function removePet(int $index): void
    {
        if (count($this->pets) <= 1 || ! array_key_exists($index, $this->pets)) {
            return;
        }

        $result = app(BookingRequestBuilder::class)->removePet($this->pets, $this->services, $index);
        $this->pets = $result['pets'];
        $this->services = $result['services'];

        $this->dispatch('booking-wizard-focus-target', target: "booking-pet-{$result['focus_index']}-heading");
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

            $this->validate(app(BookingRequestWizardRules::class)->forStep(
                $this->step,
                $this->services,
                $this->pets,
                $this->contact,
            ));

            if ($this->step === 1) {
                app(BookingRequestIntake::class)->validateServices($this->services);
            }

            if ($this->step === 3) {
                app(BookingRequestIntake::class)->validateAssignments($this->pets, $this->services);
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
        return app(BookingPricingCatalog::class)->maxServiceItems();
    }

    public function serviceAvailable(string $service): bool
    {
        return app(BookingPricingCatalog::class)->serviceIsAvailable($service);
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
        return app(BookingRequestWizardRules::class)->attributes($this->services);
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return app(BookingRequestWizardRules::class)->messages();
    }

    /**
     * @return array<string, array{label: string, examples: string|null}>
     */
    public function petSizeOptions(): array
    {
        return app(BookingRequestWizardRules::class)->petSizeOptions($this->services);
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
        $condition = $field['visible_when'] ?? null;

        if (! is_array($condition)) {
            return true;
        }

        $value = ($condition['scope'] ?? 'details') === 'service'
            ? ($service[$condition['key'] ?? ''] ?? null)
            : (($service['details'] ?? [])[$condition['key'] ?? ''] ?? null);

        return in_array($value, $condition['values'] ?? [], true);
    }

    public function fieldRequired(array $field, array $service): bool
    {
        if (($field['required'] ?? false) === true) {
            return true;
        }

        $condition = $field['required_when'] ?? null;

        if (! is_array($condition)) {
            return false;
        }

        $value = ($condition['scope'] ?? 'details') === 'service'
            ? ($service[$condition['key'] ?? ''] ?? null)
            : (($service['details'] ?? [])[$condition['key'] ?? ''] ?? null);

        return in_array($value, $condition['values'] ?? [], true);
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

    public function serviceAssignedCount(array $service): int
    {
        return count(array_unique(array_map('intval', $service['assigned_pet_ids'] ?? [])));
    }

    public function isDuplicateService(int $index): bool
    {
        if (! isset($this->services[$index])) {
            return false;
        }

        return app(BookingRequestIntake::class)->isDuplicateService(
            $this->services[$index],
            array_slice($this->services, 0, $index),
        );
    }

    public function petAssignedServiceCount(int $petIndex): int
    {
        return app(BookingRequestBuilder::class)->petAssignedServiceCount($this->services, $petIndex);
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
        return app(BookingPricingCatalog::class)->petAgeOptions();
    }

    /**
     * @return array<string, string>
     */
    public function petTypeOptions(): array
    {
        return app(BookingRequestWizardRules::class)->petTypeOptions($this->services);
    }

    public function petAgeRequired(int $petIndex): bool
    {
        return app(BookingRequestWizardRules::class)->requiresPetAge($this->services);
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
            $dateLabel = match ($details['travel_timing'] ?? null) {
                'exact' => $dateLabel,
                'window' => $dateLabel && ! empty($service['requested_end_date'])
                    ? $dateLabel.' → '.Carbon::parse($service['requested_end_date'])->format('D, M j, Y')
                    : $dateLabel,
                'not_decided' => 'Timing not decided',
                default => null,
            };

            return implode(' · ', array_filter([
                $dateLabel ?: 'Travel timing not added yet',
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

        if (($field['key'] ?? null) === 'requested_end_date' && ! empty($service['requested_date'])) {
            return Carbon::parse($service['requested_date'])->addDay()->toDateString();
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
        return app(BookingRequestBuilder::class)->assignedPetIndexes($this->services, $this->pets);
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
            $this->validate(app(BookingRequestWizardRules::class)->all(
                $this->services,
                $this->pets,
                $this->contact,
            ));
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
        } catch (ValidationException $exception) {
            $this->revealValidationStep($exception->errors());

            throw $exception;
        }

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
     * @param  array<string, mixed>  $service
     */
    public function serviceNeedsSelection(array $service): bool
    {
        return app(BookingRequestBuilder::class)->serviceNeedsSelection($service);
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
                    @include('components.booking-request-wizard.steps.services')

                    @include('components.booking-request-wizard.steps.pets')

                    @include('components.booking-request-wizard.steps.assignments')

                    @include('components.booking-request-wizard.steps.contact')

                    @include('components.booking-request-wizard.steps.review')

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
