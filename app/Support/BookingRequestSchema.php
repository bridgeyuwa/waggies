<?php

namespace App\Support;

use App\Models\BookingRequest;
use Illuminate\Support\Str;

class BookingRequestSchema
{
    /**
     * @return array<string, string>
     */
    public static function serviceOptions(): array
    {
        return BookingRequest::serviceOptions();
    }

    /**
     * @return array<string, string>
     */
    public static function variantOptions(?string $service): array
    {
        $variants = config("waggies_pricing.services.{$service}.variants", []);

        return collect($variants)
            ->mapWithKeys(static fn (array $variant, string $key): array => [
                $key => $variant['label'] ?? Str::headline($key),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function tierOptions(?string $service, ?string $variant = null): array
    {
        $tiers = $variant
            ? config("waggies_pricing.services.{$service}.variants.{$variant}.tiers", [])
            : config("waggies_pricing.services.{$service}.tiers", []);

        return collect($tiers)
            ->mapWithKeys(static fn (array $tier, string $key): array => [
                $key => $tier['label'] ?? Str::headline($key),
            ])
            ->all();
    }

    public static function defaultVariant(?string $service): ?string
    {
        return array_key_first(self::variantOptions($service));
    }

    public static function defaultTier(?string $service, ?string $variant = null): ?string
    {
        return array_key_first(self::tierOptions($service, $variant));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function serviceFields(string $service): array
    {
        $common = [
            [
                'key' => 'requested_date',
                'label' => 'Preferred date',
                'type' => 'date',
                'required' => true,
                'help' => 'We will confirm availability after reviewing your request.',
            ],
            [
                'key' => 'requested_time',
                'label' => 'Preferred time',
                'type' => 'time',
                'required' => false,
            ],
            [
                'key' => 'location',
                'label' => 'Location or address',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Area, estate, or address',
            ],
        ];

        return match ($service) {
            'boarding' => [
                ...$common,
                self::textarea('feeding_requirements', 'Feeding routine', 'Tell us about meals, treats, and timing.'),
                self::textarea('medications', 'Medication or health notes', 'Include instructions we should know before confirming care.'),
                self::textarea('special_care_needs', 'Special care needs', 'Anything else that will help us prepare for your pet?'),
            ],
            'grooming' => [
                ...$common,
                self::textarea('coat_and_grooming_notes', 'Coat or grooming notes', 'Share coat condition, sensitivities, or the look you prefer.'),
                self::textarea('handling_notes', 'Handling notes', 'Tell us about nervousness, sensitivities, or previous grooming experiences.'),
            ],
            'vet-care' => [
                ...$common,
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
                ...$common,
                self::textarea('training_goals', 'Training goals', 'Tell us what you would like your pet to learn or improve.'),
                self::textarea('behaviour_notes', 'Behaviour notes', 'Include triggers, routines, or context that would help the trainer prepare.'),
            ],
            'local-transport' => [
                ...$common,
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
                ...$common,
                [
                    'key' => 'origin',
                    'label' => 'Origin',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Where is your pet travelling from?',
                ],
                [
                    'key' => 'destination',
                    'label' => 'Destination',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Where is your pet travelling to?',
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
            default => $common,
        };
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
        ];
    }
}
