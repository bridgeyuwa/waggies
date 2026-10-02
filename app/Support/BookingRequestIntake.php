<?php

namespace App\Support;

use App\Rules\ValidPhoneNumber;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

final class BookingRequestIntake
{
    public function __construct(private readonly BookingPricingCatalog $pricingCatalog) {}

    /**
     * Normalize and validate a canonical booking-request payload.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function validate(array $data): array
    {
        $data = $this->normalize($data);
        $validator = ValidatorFactory::make(
            $data,
            $this->rules($data),
            $this->messages(),
            $this->attributes($data),
        );

        $validator->after(function (Validator $validator) use ($data): void {
            foreach ($this->domainErrors($data) as $key => $messages) {
                foreach ($messages as $message) {
                    if (in_array($message, $validator->errors()->get($key), true)) {
                        continue;
                    }

                    $validator->errors()->add($key, $message);
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Validate the service-level rules needed before moving past step one.
     *
     * @param  array<int, array<string, mixed>>  $services
     */
    public function validateServices(array $services): void
    {
        $this->throwIfErrors($this->serviceErrors(array_values($services)));
    }

    /**
     * Validate assignment and cross-service rules needed before moving past step three.
     *
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     */
    public function validateAssignments(array $pets, array $services): void
    {
        $this->throwIfErrors($this->assignmentErrors(array_values($pets), array_values($services)));
    }

    /**
     * @param  array<string, mixed>  $service
     * @param  array<int, array<string, mixed>>  $previousServices
     */
    public function isDuplicateService(array $service, array $previousServices): bool
    {
        $signature = $this->duplicateServiceSignature($service);

        foreach ($previousServices as $previousService) {
            if ($this->duplicateServiceSignature($previousService) === $signature) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalize(array $data): array
    {
        $contact = is_array($data['contact'] ?? null) ? $data['contact'] : [];
        $contact['name'] = is_string($contact['name'] ?? null) ? trim($contact['name']) : ($contact['name'] ?? null);
        $contact['email'] = is_string($contact['email'] ?? null) ? Str::lower(trim($contact['email'])) : ($contact['email'] ?? null);
        $contact['phone_country'] = is_string($contact['phone_country'] ?? null) && $contact['phone_country'] !== ''
            ? strtoupper(trim($contact['phone_country']))
            : PhoneNumber::defaultCountryCode();
        $contact['phone_number'] = $contact['phone_number'] ?? $contact['phone'] ?? null;
        $contact['phone_other_country_code'] = is_string($contact['phone_other_country_code'] ?? null)
            ? trim($contact['phone_other_country_code'])
            : ($contact['phone_other_country_code'] ?? null);
        $contact['phone'] = PhoneNumber::normalizeContact($contact);

        $pets = array_values(array_filter(
            is_array($data['pets'] ?? null) ? $data['pets'] : [],
            static fn (mixed $pet): bool => is_array($pet),
        ));
        $pets = array_map(function (array $pet): array {
            foreach (['name', 'species', 'breed', 'age', 'sex', 'notes'] as $key) {
                if (is_string($pet[$key] ?? null)) {
                    $pet[$key] = trim($pet[$key]);
                }
            }

            return $pet;
        }, $pets);

        $services = array_values(array_filter(
            is_array($data['services'] ?? null) ? $data['services'] : [],
            static fn (mixed $service): bool => is_array($service),
        ));
        $services = array_map(fn (array $service): array => $this->normalizeService($service), $services);

        $idempotencyKey = is_string($data['idempotency_key'] ?? null)
            ? trim($data['idempotency_key'])
            : ($data['idempotency_key'] ?? null);

        return [
            ...$data,
            'contact' => $contact,
            'pets' => $pets,
            'services' => $services,
            'idempotency_key' => $idempotencyKey !== '' ? $idempotencyKey : null,
            'source' => is_string($data['source'] ?? null) ? trim($data['source']) : ($data['source'] ?? null),
            'context' => is_array($data['context'] ?? null) ? $data['context'] : [],
        ];
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<string, mixed>
     */
    private function normalizeService(array $service): array
    {
        foreach (['service_key', 'service_variant', 'requested_date', 'requested_end_date', 'requested_time', 'location'] as $key) {
            if (is_string($service[$key] ?? null)) {
                $service[$key] = trim($service[$key]);
            }
        }

        if (($service['service_variant'] ?? null) === '') {
            $service['service_variant'] = null;
        }

        $service['assigned_pet_ids'] = is_array($service['assigned_pet_ids'] ?? null)
            ? array_values(array_map(
                static fn (mixed $index): mixed => is_string($index) && ctype_digit($index) ? (int) $index : $index,
                $service['assigned_pet_ids'],
            ))
            : ($service['assigned_pet_ids'] ?? null);

        $details = is_array($service['details'] ?? null) ? $service['details'] : [];

        if (($service['service_key'] ?? null) === 'boarding') {
            unset($details['emergency_contact_secondary']);
        }

        if (($service['service_key'] ?? null) === 'vet-care'
            && empty($details['care_needs'])
            && is_string($service['service_variant'] ?? null)
            && $service['service_variant'] !== '') {
            $details['care_needs'] = [$service['service_variant']];
        }

        if (($service['service_key'] ?? null) === 'relocation') {
            $details = BookingRequestSchema::relocationDetails($service['service_variant'] ?? null, $details);
        }

        $service['details'] = $details;

        return $service;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, array<int, mixed>>
     */
    private function rules(array $data): array
    {
        $contact = is_array($data['contact'] ?? null) ? $data['contact'] : [];
        $rules = [
            'contact' => ['required', 'array'],
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
            'pets' => ['required', 'array', 'min:1', 'max:8'],
            'pets.*' => ['required', 'array'],
            'pets.*.name' => ['required', 'string', 'max:80'],
            'pets.*.species' => ['required', 'string', 'max:40'],
            'services' => ['required', 'array', 'min:1', 'max:'.$this->pricingCatalog->maxServiceItems()],
            'services.*' => ['required', 'array'],
            'services.*.service_key' => ['required', 'string', 'max:80'],
            'services.*.service_variant' => ['nullable', 'string', 'max:80'],
            'services.*.assigned_pet_ids' => ['required', 'array', 'min:1'],
            'services.*.assigned_pet_ids.*' => ['integer'],
            'services.*.details' => ['nullable', 'array'],
            'idempotency_key' => ['nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9._:-]+$/'],
            'source' => ['nullable', 'string', 'max:120'],
            'context' => ['nullable', 'array'],
        ];

        foreach (array_keys($data['services'] ?? []) as $index) {
            $rules["services.{$index}.assigned_pet_ids.*"] = ['integer', Rule::in(array_keys($data['pets'] ?? []))];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'services.*.assigned_pet_ids.required' => 'Assign at least one pet to this service.',
            'services.*.assigned_pet_ids.array' => 'Assign at least one pet to this service.',
            'services.*.assigned_pet_ids.min' => 'Assign at least one pet to this service.',
            'services.*.assigned_pet_ids.*.integer' => 'Choose a pet that exists in this request.',
            'services.*.assigned_pet_ids.*.in' => 'Choose a pet that exists in this request.',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    private function attributes(array $data): array
    {
        $attributes = [
            'contact.name' => 'your name',
            'contact.email' => 'email address',
            'contact.phone_country' => 'country calling code',
            'contact.phone_number' => 'phone number',
            'contact.phone_other_country_code' => 'country calling code',
            'services.*.service_key' => 'service',
            'services.*.service_variant' => 'service option',
            'services.*.assigned_pet_ids' => 'assigned pet',
            'pets.*.name' => 'pet name',
            'pets.*.species' => 'pet type',
        ];

        foreach ($data['services'] ?? [] as $index => $service) {
            $attributes["services.{$index}.service_variant"] = $this->serviceVariantAttribute($service['service_key'] ?? null);
        }

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, list<string>>
     */
    private function domainErrors(array $data): array
    {
        return $this->mergeErrors(
            $this->serviceErrors($data['services'] ?? []),
            $this->assignmentErrors($data['pets'] ?? [], $data['services'] ?? []),
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, list<string>>
     */
    private function serviceErrors(array $services): array
    {
        $messages = [];
        $catalog = $this->pricingCatalog;

        foreach ($services as $serviceIndex => $service) {
            $serviceKey = (string) ($service['service_key'] ?? '');
            $serviceDefinition = $catalog->service($serviceKey);

            if ($serviceDefinition === [] || ! $catalog->isAvailable($serviceDefinition)) {
                $messages["services.{$serviceIndex}.service_key"][] = 'Choose an available service.';

                continue;
            }

            $variantOptions = $catalog->variantOptions($serviceKey, availableOnly: true);
            $variant = $service['service_variant'] ?? null;
            $selectionMode = $catalog->selectionMode($serviceKey);

            if ($catalog->serviceOptionRequired($serviceKey)
                && (! is_string($variant) || ! array_key_exists($variant, $variantOptions))) {
                $messages["services.{$serviceIndex}.service_variant"][] = 'Choose an active service option.';

                continue;
            }

            if (in_array($selectionMode, ['pet_types', 'multiple'], true)
                && filled($variant)
                && (! is_string($variant) || ! array_key_exists($variant, $variantOptions))) {
                $messages["services.{$serviceIndex}.service_variant"][] = 'Choose a valid service option.';
            }

            if ($selectionMode === 'multiple'
                && ! $catalog->careNeedsAreValid($service['details']['care_needs'] ?? [])) {
                $messages["services.{$serviceIndex}.details.care_needs"][] = 'Choose at least one veterinary care need.';
            }
        }

        return $messages;
    }

    /**
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, list<string>>
     */
    private function assignmentErrors(array $pets, array $services): array
    {
        $messages = [];
        $assignedPetIndexes = [];
        $catalog = $this->pricingCatalog;

        foreach ($services as $serviceIndex => $service) {
            $petIndexes = $service['assigned_pet_ids'] ?? [];

            if (! is_array($petIndexes) || $petIndexes === []) {
                $messages["services.{$serviceIndex}.assigned_pet_ids"][] = 'Assign at least one compatible pet to this service.';

                continue;
            }

            $normalisedPetIndexes = [];

            foreach ($petIndexes as $petIndex) {
                if (! $this->isValidPetIndex($petIndex)) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"][] = 'Choose a pet that exists in this request.';

                    continue;
                }

                $petIndex = (int) $petIndex;

                if (in_array($petIndex, $normalisedPetIndexes, true)) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"][] = 'Choose each pet only once for this service.';

                    continue;
                }

                $normalisedPetIndexes[] = $petIndex;

                if (! array_key_exists($petIndex, $pets)) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"][] = 'Choose a pet that exists in this request.';

                    continue;
                }

                $assignedPetIndexes[$petIndex] = true;

                if ($catalog->isPetCompatible(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                    $pets[$petIndex]['species'] ?? null,
                )) {
                    continue;
                }

                $messages["services.{$serviceIndex}.assigned_pet_ids"][] = $catalog->petCompatibilityReason(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                    $pets[$petIndex]['species'] ?? null,
                ) ?? 'Choose a pet that matches this service.';
            }
        }

        foreach ($pets as $petIndex => $pet) {
            if (! isset($assignedPetIndexes[$petIndex])) {
                $messages["pets.{$petIndex}.assignments"][] = 'Assign this pet to at least one service or remove it.';
            }
        }

        foreach ($this->duplicateServiceErrors($services) as $key => $duplicateMessages) {
            $messages[$key] = [...($messages[$key] ?? []), ...$duplicateMessages];
        }

        return $messages;
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<string, list<string>>
     */
    private function duplicateServiceErrors(array $services): array
    {
        $seen = [];
        $messages = [];

        foreach ($services as $index => $service) {
            $signature = $this->duplicateServiceSignature($service);

            if (! isset($seen[$signature])) {
                $seen[$signature] = $index;

                continue;
            }

            $message = 'This matches another service item. Assign more pets to one item, or change the schedule or service details.';
            $messages["services.{$index}.assigned_pet_ids"][] = $message;
            $messages["services.{$seen[$signature]}.assigned_pet_ids"][] = $message;
        }

        return $messages;
    }

    /**
     * @param  array<string, mixed>  $service
     */
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

    /**
     * @param  array<string, list<string>>  ...$errorSets
     * @return array<string, list<string>>
     */
    private function mergeErrors(array ...$errorSets): array
    {
        $messages = [];

        foreach ($errorSets as $errorSet) {
            foreach ($errorSet as $key => $keyMessages) {
                $messages[$key] = [...($messages[$key] ?? []), ...$keyMessages];
            }
        }

        return $messages;
    }

    /**
     * @param  array<string, list<string>>  $errors
     */
    private function throwIfErrors(array $errors): void
    {
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function serviceVariantAttribute(mixed $service): string
    {
        return match ($service) {
            'boarding' => 'animal type',
            'relocation' => 'relocation option',
            'vet-care' => 'veterinary care needs',
            default => 'service option',
        };
    }

    private function isValidPetIndex(mixed $petIndex): bool
    {
        return (is_int($petIndex) && $petIndex >= 0)
            || (is_string($petIndex) && ctype_digit($petIndex));
    }
}
