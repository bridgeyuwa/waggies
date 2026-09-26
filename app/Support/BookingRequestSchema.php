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
    public static function tierOptions(?string $service, ?string $variant = null): array
    {
        return app(BookingPricingCatalog::class)->tierOptions($service, $variant, availableOnly: true);
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
    public static function serviceFields(string $service, ?string $tier = null): array
    {
        return match ($service) {
            'boarding' => [
                self::dateField('check_in', 'Check-in date', 'When would your pet come to Waggies?', 'details'),
                self::dateField('check_out', 'Check-out date', 'When would your pet go home?', 'details'),
                self::textarea('feeding_requirements', 'Feeding routine', 'Tell us about meals, treats, and timing.'),
                self::textarea('medications', 'Medication or health notes', 'Include instructions we should know before confirming care.'),
                self::textarea('special_care_needs', 'Special care needs', 'Anything else that will help us prepare for your pet?'),
            ],
            'grooming' => [
                self::dateField('requested_date', 'Preferred grooming date', 'We will confirm the available appointment after reviewing your request.', 'service'),
                self::textarea('coat_and_grooming_notes', 'Coat or grooming notes', 'Share coat condition, sensitivities, or the look you prefer.'),
                self::textarea('handling_notes', 'Handling notes', 'Tell us about nervousness, sensitivities, or previous grooming experiences.'),
            ],
            'vet-care' => [
                self::dateField('requested_date', 'Preferred appointment date', 'The vet normally sets the exact consultation time.', 'service'),
                [
                    'key' => 'reason',
                    'label' => 'What does your pet need help with?',
                    'type' => 'textarea',
                    'required' => true,
                    'placeholder' => 'For example: wellness check, vaccination, or a symptom you have noticed.',
                ],
                [
                    'key' => 'urgency',
                    'label' => 'Urgency',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'routine' => 'Routine appointment',
                        'soon' => 'Needs attention soon',
                        'urgent' => 'Urgent — please contact me promptly',
                    ],
                ],
            ],
            'training' => [
                self::dateField('requested_date', 'Preferred programme start date', 'Choose the date you would ideally like the programme to begin.', 'service'),
                self::textarea('training_goals', 'Training goals', 'Tell us what you would like your dog to learn or improve.'),
                self::textarea('behaviour_notes', 'Behaviour notes', 'Include triggers, routines, or context that would help the trainer prepare.'),
            ],
            'local-transport' => [
                self::dateField('requested_date', 'Preferred transport date', 'We will confirm the route and availability with you.', 'service'),
                [
                    'key' => 'pickup',
                    'label' => 'Pickup point',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Where should we collect your pet?',
                ],
                [
                    'key' => 'dropoff',
                    'label' => 'Drop-off point',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Where should we take your pet?',
                ],
                [
                    'key' => 'trip_type',
                    'label' => 'Trip type',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'one-way' => 'One way',
                        'return' => 'Return trip',
                    ],
                ],
                self::textarea('special_requirements', 'Special transport requirements', 'Add carrier, accessibility, waiting, or handling details.'),
            ],
            'relocation' => [
                self::dateField('requested_date', 'Preferred travel date', 'We will review the route, documentation, and timing with you.', 'service'),
                [
                    'key' => 'origin_country',
                    'label' => 'Country your pet is coming from',
                    'type' => 'text',
                    'required' => true,
                    'when_tier' => 'import',
                    'placeholder' => 'For example: United Kingdom',
                ],
                [
                    'key' => 'destination_country',
                    'label' => 'Country your pet is going to',
                    'type' => 'text',
                    'required' => true,
                    'when_tier' => 'export',
                    'placeholder' => 'For example: Ghana',
                ],
                [
                    'key' => 'documentation_status',
                    'label' => 'Documentation status',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'ready' => 'Most documents are ready',
                        'in-progress' => 'Documents are in progress',
                        'not-sure' => 'I need help understanding the requirements',
                    ],
                ],
                self::textarea('relocation_notes', 'Relocation notes', 'Share timing, route, and any requirements you already know about.'),
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
    private static function textarea(string $key, string $label, string $placeholder): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => 'textarea',
            'required' => false,
            'placeholder' => $placeholder,
            'scope' => 'details',
        ];
    }
}
