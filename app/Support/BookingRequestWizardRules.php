<?php

namespace App\Support;

use App\Rules\ValidPhoneNumber;
use Illuminate\Validation\Rule;

final class BookingRequestWizardRules
{
    public function __construct(private readonly BookingPricingCatalog $pricingCatalog) {}

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<string, mixed>  $contact
     * @return array<string, array<int, mixed>>
     */
    public function all(array $services, array $pets, array $contact): array
    {
        return [
            ...$this->forStep(1, $services, $pets, $contact),
            ...$this->petRules(false, $services),
            ...$this->forStep(3, $services, $pets, $contact),
            ...$this->forStep(4, $services, $pets, $contact),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<string, mixed>  $contact
     * @return array<string, array<int, mixed>>
     */
    public function forStep(int $step, array $services, array $pets, array $contact): array
    {
        return match ($step) {
            1 => $this->serviceRules($services),
            2 => $this->petRules(true, $services),
            3 => [
                ...$this->assignmentRules($services, $pets),
                ...$this->serviceDetailRules($services),
            ],
            4 => $this->contactRules($contact),
            default => [],
        };
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, string>
     */
    public function attributes(array $services): array
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

        foreach ($services as $index => $service) {
            $attributes["services.{$index}.service_variant"] = $this->serviceVariantAttribute($service['service_key'] ?? null);
        }

        return $attributes;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pets.*.species.in' => 'This pet type is not compatible with the selected services. Choose a compatible pet type or add a service that supports it.',
            'services.*.details.care_needs.required' => 'Select at least one veterinary care need.',
            'services.*.details.care_needs.min' => 'Select at least one veterinary care need.',
            'services.*.details.care_needs.*.in' => 'Choose a valid veterinary care need.',
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, array{label: string, examples: string|null}>
     */
    public function petSizeOptions(array $services): array
    {
        foreach ($services as $service) {
            $options = $this->pricingCatalog->sizeOptions(
                (string) ($service['service_key'] ?? ''),
                $service['service_variant'] ?? null,
            );

            if ($options !== []) {
                return $options;
            }
        }

        return [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, string>
     */
    public function petTypeOptions(array $services): array
    {
        $allowedPetTypes = collect($services)
            ->filter(fn (array $service): bool => filled($service['service_key'] ?? null))
            ->flatMap(function (array $service): array {
                return $this->pricingCatalog->allowedPetTypes(
                    (string) $service['service_key'],
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

    /**
     * @param  array<string, mixed>  $contact
     * @return array<string, array<int, mixed>>
     */
    private function contactRules(array $contact): array
    {
        return [
            'contact.name' => ['required', 'string', 'max:120'],
            'contact.email' => ['required', 'email', 'max:255'],
            'contact.phone_country' => ['required', Rule::in(array_keys(PhoneNumber::countryOptions()))],
            'contact.phone_other_country_code' => ['nullable', 'required_if:contact.phone_country,OTHER', 'regex:/^\+?[0-9]{1,3}$/'],
            'contact.phone_number' => [
                'required',
                'string',
                'max:40',
                new ValidPhoneNumber(
                    $contact['phone_country'] ?? null,
                    $contact['phone_other_country_code'] ?? null,
                ),
            ],
            'contact.preferred_contact_method' => ['nullable', Rule::in(['phone', 'email', 'whatsapp'])],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, array<int, mixed>>
     */
    private function serviceRules(array $services): array
    {
        $rules = [
            'services' => ['required', 'array', 'min:1', 'max:'.$this->pricingCatalog->maxServiceItems()],
            'services.*.service_key' => ['required', Rule::in(array_keys(BookingRequestSchema::allServiceOptions()))],
        ];

        foreach ($services as $index => $service) {
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
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, array<int, mixed>>
     */
    private function petRules(bool $enforceCompatibility, array $services): array
    {
        return [
            'pets' => ['required', 'array', 'min:1', 'max:8'],
            'pets.*.name' => ['required', 'string', 'max:80'],
            'pets.*.species' => ['required', Rule::in($enforceCompatibility ? array_keys($this->petTypeOptions($services)) : ['dog', 'cat'])],
            'pets.*.size' => ['nullable', Rule::in(array_keys($this->petSizeOptions($services)))],
            'pets.*.breed' => ['nullable', 'string', 'max:120'],
            'pets.*.age' => ['nullable', Rule::in(array_keys($this->pricingCatalog->petAgeOptions()))],
            'pets.*.sex' => ['required', Rule::in(['male', 'female'])],
            'pets.*.notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @param  array<int, array<string, mixed>>  $pets
     * @return array<string, array<int, mixed>>
     */
    private function assignmentRules(array $services, array $pets): array
    {
        $rules = [];

        foreach ($services as $index => $service) {
            $rules["services.{$index}.assigned_pet_ids"] = ['required', 'array', 'min:1'];
            $rules["services.{$index}.assigned_pet_ids.*"] = ['integer', Rule::in(array_keys($pets))];
        }

        foreach ($services as $service) {
            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                $petIndex = (int) $petIndex;

                if (! isset($pets[$petIndex])) {
                    continue;
                }

                if ($this->pricingCatalog->requiresPetSize(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                ) && ($pets[$petIndex]['species'] ?? null) === 'dog') {
                    $rules["pets.{$petIndex}.size"] = [
                        'required',
                        Rule::in(array_keys($this->petSizeOptions($services))),
                    ];
                }

                if (($service['service_key'] ?? null) === 'relocation') {
                    $rules["pets.{$petIndex}.breed"] = ['required', 'string', 'max:120'];
                }

                if (in_array($service['service_key'] ?? null, ['vet-care', 'relocation'], true)) {
                    $rules["pets.{$petIndex}.age"] = ['required', Rule::in(array_keys($this->pricingCatalog->petAgeOptions()))];
                }
            }
        }

        return $rules;
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, array<int, mixed>>
     */
    private function serviceDetailRules(array $services): array
    {
        $rules = [];

        foreach ($services as $index => $service) {
            foreach (BookingRequestSchema::serviceFields(
                (string) ($service['service_key'] ?? ''),
                $service['service_variant'] ?? null,
            ) as $field) {
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

    /**
     * @param  array<string, mixed>  $field
     */
    private function fieldModel(int $index, array $field): string
    {
        $scope = $field['scope'] ?? 'details';

        return $scope === 'service'
            ? "services.{$index}.{$field['key']}"
            : "services.{$index}.details.{$field['key']}";
    }

    private function serviceVariantAttribute(?string $service): string
    {
        return match ($service) {
            'boarding' => 'pet assignment',
            'vet-care' => 'veterinary care needs',
            'relocation' => 'relocation option',
            default => 'service option',
        };
    }
}
