<?php

namespace App\Support;

final class BookingRequestSchema
{
    /**
     * @return array<string, string>
     */
    public static function serviceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions(availableOnly: true);
    }

    /**
     * @return array<string, string>
     */
    public static function allServiceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions();
    }

    /**
     * @return array<string, string>
     */
    public static function variantOptions(?string $service): array
    {
        return app(BookingPricingCatalog::class)->variantOptions($service, availableOnly: true);
    }

    /**
     * @return array<string, string>
     */
    public static function allVariantOptions(?string $service): array
    {
        return app(BookingPricingCatalog::class)->variantOptions($service);
    }

    public static function serviceSelectionMode(?string $service): string
    {
        return app(BookingPricingCatalog::class)->selectionMode($service);
    }

    /**
     * @return array<string, string>
     */
    public static function tierOptions(?string $service, ?string $variant = null): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public static function allTierOptions(?string $service, ?string $variant = null): array
    {
        return [];
    }

    public static function defaultVariant(?string $service): ?string
    {
        return null;
    }

    public static function defaultTier(?string $service, ?string $variant = null): ?string
    {
        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function serviceFields(string $service, ?string $variant = null): array
    {
        return match ($service) {
            'boarding' => [
                self::dateField('check_in', 'Check-in date', 'The first overnight stay begins on this date.', 'details'),
                self::dateField('check_out', 'Check-out date', 'The checkout date is not another night unless the pet remains past the agreed cutoff.', 'details'),
                self::textarea('feeding_requirements', 'Feeding instructions', 'Tell us about owner-supplied food, meals, treats, and timing.'),
                self::textarea('medications', 'Medication or health notes', 'Medication and special handling are reviewed separately before confirmation.'),
                self::textarea('special_care_needs', 'Special care needs', 'Include supervision, handling, mobility, anxiety, or other care needs.'),
                self::textarea('behaviour_notes', 'Behaviour and safety disclosure', 'Tell us about aggression, escape behaviour, bite history, severe anxiety, or handling difficulties.'),
                self::textarea('emergency_contact_primary', 'Primary emergency contact', 'Name and phone number.', true),
                self::textarea('emergency_contact_secondary', 'Secondary emergency contact', 'Name and phone number.', true),
                self::selectField('emergency_vet_authorization', 'Emergency veterinary authorization', [
                    'authorized' => 'I authorize emergency veterinary care if needed',
                    'discuss' => 'Please discuss this with me during review',
                ], true),
            ],
            'vet-care' => [
                self::dateField('requested_date', 'Preferred appointment date', 'The veterinary team confirms the appointment after reviewing your request.', 'service'),
                self::textarea('reason', 'Additional notes about what your pet needs', 'Add symptoms, context, or details not covered by your selected care needs.', true),
                self::selectField('urgency', 'Urgency', [
                    'routine' => 'Routine appointment',
                    'soon' => 'Needs attention soon',
                    'urgent' => 'Urgent — please contact me promptly',
                ], true),
            ],
            'relocation' => [
                self::dateField('requested_date', 'Travel date', 'Import and export requests are reviewed for route, documentation, and timing.', 'service'),
                self::textField('origin_country', 'Origin country', 'Where the pet is travelling from.', true),
                self::textField('destination_country', 'Destination country', 'Where the pet is travelling to.', true),
                self::textField('airline_airport_details', 'Airline or airport details', 'Share any airline, airport, or flight information already known.'),
                self::textField('pickup_details', 'Pickup details', 'Where should the pet be collected, if applicable?'),
                self::textField('destination_details', 'Destination details', 'Where should the pet be delivered or collected, if applicable?'),
                self::selectField('microchip_status', 'Existing microchip status', [
                    'yes' => 'A chip already exists',
                    'no' => 'No chip is currently recorded',
                    'unknown' => 'I am not sure',
                ], true),
                self::selectField('documentation_status', 'Documentation status', [
                    'ready' => 'Most documents are ready',
                    'in-progress' => 'Documents are in progress',
                    'not-sure' => 'I need help understanding the requirements',
                ], true),
                self::textarea('relocation_notes', 'Route or timing notes', 'Add timing constraints, route notes, or other requirements.'),
            ],
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    private static function dateField(string $key, string $label, string $help, string $scope): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => 'date',
            'required' => true,
            'help' => $help,
            'scope' => $scope,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function textField(string $key, string $label, string $placeholder, bool $required = false): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => 'text',
            'required' => $required,
            'placeholder' => $placeholder,
            'scope' => 'details',
        ];
    }

    /**
     * @param  array<string, string>  $options
     * @return array<string, mixed>
     */
    private static function selectField(string $key, string $label, array $options, bool $required = false): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => 'select',
            'required' => $required,
            'options' => $options,
            'scope' => 'details',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function textarea(string $key, string $label, string $placeholder, bool $required = false): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => 'textarea',
            'required' => $required,
            'placeholder' => $placeholder,
            'scope' => 'details',
        ];
    }
}
