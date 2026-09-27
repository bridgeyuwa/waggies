<?php

$dogGroomingSizeRates = [
    'small' => [
        'label' => 'Small',
        'booking_label' => 'Small',
        'examples' => 'Chihuahua, Yorkshire Terrier, Toy Poodle',
        'min_weight_kg' => 0,
        'max_weight_kg' => 10,
        'amount' => 5000,
        'max_amount' => 8000,
    ],
    'medium' => [
        'label' => 'Medium',
        'booking_label' => 'Medium',
        'examples' => 'Beagle, Cocker Spaniel, Border Collie',
        'min_weight_kg' => 10,
        'max_weight_kg' => 25,
        'amount' => 8000,
        'max_amount' => 15000,
    ],
    'large' => [
        'label' => 'Large',
        'booking_label' => 'Large',
        'examples' => 'Labrador, German Shepherd, Great Dane',
        'min_weight_kg' => 25,
        'max_weight_kg' => null,
        'amount' => 15000,
        'max_amount' => 25000,
    ],
];

$dogBoardingSizeRates = [
    'small' => [
        'label' => 'Small',
        'booking_label' => 'Small',
        'examples' => 'Chihuahua, Yorkshire Terrier, Toy Poodle',
        'min_weight_kg' => 0,
        'max_weight_kg' => 10,
        'amount' => 8000,
        'max_amount' => 12000,
    ], 
    'medium' => [
        'label' => 'Medium',
        'booking_label' => 'Medium',
        'examples' => 'Beagle, Cocker Spaniel, Border Collie',
        'min_weight_kg' => 10,
        'max_weight_kg' => 25,
        'amount' => 12000,
        'max_amount' => 18000,
    ],
    'large' => [
        'label' => 'Large',
        'booking_label' => 'Large',
        'examples' => 'Labrador, German Shepherd, Great Dane',
        'min_weight_kg' => 25,
        'max_weight_kg' => null,
        'amount' => 18000,
        'max_amount' => 28000,
    ],
];

$multiplePetDiscount = [
    'enabled' => false,
    'percentage' => 10,
    'applies_to' => ['boarding'],
    'applies_from_pet' => 2,
];

return [
    'currency' => 'NGN',
    'max_service_items' => 12,
    'pet_age_options' => [
        'under-6-months' => 'Under 6 months',
        '6-12-months' => '6–12 months',
        '1-3-years' => '1–3 years',
        '4-7-years' => '4–7 years',
        '8-10-years' => '8–10 years',
        '11-plus-years' => '11+ years',
        'not-sure' => 'Not sure',
    ],
    'discounts' => [
        'multiple_pet' => $multiplePetDiscount,
    ],
    'services' => [
        'boarding' => [
            'enabled' => true, 'booking_enabled' => true, 'pricing_enabled' => true,
            'label' => 'Boarding', 'unit' => '/night', 'quantity_label' => 'Nights', 'min' => 1, 'max' => 30,
            'variants' => [
                'dogs' => ['label' => 'Dogs', 'pet_type' => 'dog', 'weight_required' => true, 'size_rates' => $dogBoardingSizeRates, 'tiers' => [
                    'basic' => ['enabled' => true, 'label' => 'Basic', 'type' => 'fixed', 'amount' => 10000, 'adjustment' => 0, 'capacity' => ['included_pet_count' => 1], 'features' => ['1 dog boarding suite', 'Daily outdoor walks', '24/7 supervision', ['label' => 'Grooming service', 'included' => false]]],
                    'premium' => ['enabled' => true, 'label' => 'Premium', 'type' => 'fixed', 'amount' => 18000, 'adjustment' => 6000, 'capacity' => ['included_pet_count' => 2], 'featured' => true, 'badge' => 'Most Popular', 'features' => ['2 dogs boarding suite', 'Daily outdoor walks', '24/7 supervision', 'Grooming service']],
                    'deluxe' => ['enabled' => true, 'label' => 'Deluxe', 'type' => 'fixed', 'amount' => 25000, 'adjustment' => 12000, 'capacity' => ['included_pet_count' => 3], 'features' => ['3+ dogs family suite', 'Daily outdoor walks', '24/7 supervision', 'Grooming + spa bath']],
                ]],
                'cats' => ['label' => 'Cats', 'pet_type' => 'cat', 'tiers' => [
                    'cozy' => ['enabled' => true, 'label' => 'Cozy', 'type' => 'fixed', 'amount' => 8000, 'capacity' => ['included_pet_count' => 1], 'features' => ['1 private cat condo', 'Daily enrichment & perches', 'Daily photo updates', '24/7 supervision', ['label' => 'Private playroom access', 'included' => false]]],
                    'premium' => ['enabled' => true, 'label' => 'Premium', 'type' => 'fixed', 'amount' => 14000, 'capacity' => ['included_pet_count' => 2], 'featured' => true, 'badge' => 'Most Popular', 'features' => ['2 cats (same condo)', 'Daily enrichment & perches', 'Daily photo & video updates', '24/7 supervision', 'Private playroom time']],
                    'deluxe' => ['enabled' => true, 'label' => 'Deluxe', 'type' => 'fixed', 'amount' => 22000, 'capacity' => ['included_pet_count' => 3], 'features' => ['Deluxe multi-level condo', '1-on-1 play & cuddle time', 'Daily vet health check', '24/7 supervision', 'Complimentary coat groom']],
                ]],
                'exotic' => ['label' => 'Exotic Pets', 'pet_type' => 'other', 'tiers' => [
                    'small' => ['enabled' => true, 'label' => 'Small Mammal', 'type' => 'estimate', 'amount' => 6000, 'max_amount' => 8000, 'capacity' => ['included_pet_count' => 1], 'features' => ['Rabbits, guinea pigs, hamsters', 'Species-appropriate enclosure', 'Daily health check', '24/7 supervision', ['label' => 'Climate-controlled reptile setup', 'included' => false]]],
                    'specialist' => ['enabled' => true, 'label' => 'Specialist', 'type' => 'estimate', 'amount' => 12000, 'max_amount' => 15000, 'capacity' => ['included_pet_count' => 1], 'featured' => true, 'badge' => 'Most Popular', 'features' => ['Birds or reptiles', 'Temp, humidity & UV monitoring', 'Custom feeding protocol', '24/7 supervision', 'Daily photo updates']],
                    'premium' => ['enabled' => true, 'label' => 'Premium Habitat', 'type' => 'quote', 'amount' => 18000, 'capacity' => ['included_pet_count' => 1], 'features' => ['Complex multi-zone enclosures', 'Live/frozen feeder handling', 'Exotic vet daily review', '24/7 supervision', 'Owner-supplied habitat setup']],
                ]],
            ],
        ],
        'grooming' => [
            'enabled' => true, 'booking_enabled' => true, 'pricing_enabled' => true,
            'label' => 'Grooming', 'unit' => '/session', 'quantity_label' => 'Sessions', 'min' => 1, 'max' => 12,
            'pricing' => [
                'model' => 'base_size_rate_plus_tier_adjustment',
                'pet_variants' => [
                    'dogs' => [
                        'label' => 'Dogs',
                        'pet_type' => 'dog',
                        'enabled' => true,
                        'weight_required' => true,
                        'size_rates' => $dogGroomingSizeRates,
                        'tiers' => [
                            'bath' => ['enabled' => true, 'adjustment' => 0],
                            'full' => ['enabled' => true, 'adjustment' => 8000],
                            'spa' => ['enabled' => true, 'adjustment' => 18000],
                        ],
                    ],
                    'cats' => [
                        'label' => 'Cats',
                        'pet_type' => 'cat',
                        'enabled' => true,
                        'weight_required' => false,
                        'tiers' => [
                            'bath' => ['enabled' => true, 'amount' => 10000],
                            'full' => ['enabled' => true, 'amount' => 18000],
                            'spa' => ['enabled' => true, 'amount' => 28000],
                        ],
                    ],
                ],
            ],
            'tiers' => [
                'bath' => ['enabled' => true, 'label' => 'Bath & Brush', 'type' => 'fixed', 'amount' => 10000, 'duration' => '45-60 min', 'features' => ['Shampoo bath', 'Blow-dry', 'Brush-out', 'Ear cleaning', 'Nail trim']],
                'full' => ['enabled' => true, 'label' => 'Full Groom', 'type' => 'fixed', 'amount' => 18000, 'duration' => '90-120 min', 'featured' => true, 'badge' => 'Best Value', 'features' => ['Bath & Brush', 'Breed-standard clip and styling', 'Teeth brushing', 'De-shed treatment', 'Finish']],
                'spa' => ['enabled' => true, 'label' => 'Luxury Spa', 'type' => 'fixed', 'amount' => 28000, 'duration' => '2-3 hours', 'features' => ['Full Groom', 'Conditioning treatment', 'Facial and paw treatment', 'Flea/tick inspection', 'Photo report']],
            ],
        ],
        'vet-care' => ['enabled' => true, 'booking_enabled' => true, 'pricing_enabled' => true, 'label' => 'Veterinary Care', 'tiers' => ['consultation' => ['enabled' => true, 'label' => 'Wellness Consultation', 'type' => 'fixed', 'amount' => 12000, 'features' => []], 'vaccination' => ['enabled' => true, 'label' => 'Vaccination Visit', 'type' => 'fixed', 'amount' => 15000, 'features' => []], 'comprehensive-exam' => ['enabled' => true, 'label' => 'Comprehensive Exam', 'type' => 'fixed', 'amount' => 18000, 'features' => []]]],
        'training' => ['enabled' => true, 'booking_enabled' => true, 'pricing_enabled' => true, 'label' => 'Dog Training', 'pet_types' => ['dog'], 'tiers' => ['puppy' => ['enabled' => true, 'label' => 'Puppy Foundation', 'type' => 'estimate', 'amount' => 80000, 'max_amount' => 120000, 'features' => []], 'obedience' => ['enabled' => true, 'label' => 'Basic Obedience', 'type' => 'estimate', 'amount' => 100000, 'max_amount' => 150000, 'features' => []], 'behaviour' => ['enabled' => true, 'label' => 'Behaviour Modification', 'type' => 'quote', 'amount' => 0, 'features' => []]]],
        'local-transport' => ['enabled' => true, 'booking_enabled' => true, 'pricing_enabled' => true, 'label' => 'Local Transport', 'unit' => '/trip', 'quantity_label' => null, 'tiers' => [
            'city' => ['enabled' => true, 'label' => 'City Pet Transfer', 'type' => 'quote', 'amount' => 0, 'features' => []],
            'vet' => ['enabled' => true, 'label' => 'Vet Transfer', 'type' => 'quote', 'amount' => 0, 'features' => []],
            'airport' => ['enabled' => true, 'label' => 'Airport Transfer', 'type' => 'quote', 'amount' => 0, 'featured' => true, 'features' => []],
        ]],
        'relocation' => ['enabled' => true, 'booking_enabled' => true, 'pricing_enabled' => true, 'label' => 'Pet Relocation', 'tiers' => ['import' => ['enabled' => true, 'label' => 'Import to Nigeria', 'type' => 'quote', 'amount' => 0, 'features' => []], 'export' => ['enabled' => true, 'label' => 'Export from Nigeria', 'type' => 'quote', 'amount' => 0, 'features' => []]]],
    ],
    'transport' => [
        'products' => [
            'transport-city-transfer' => ['tier' => 'city', 'pricing' => ['type' => 'distance', 'rates' => [['max_distance_km' => 10, 'amount' => 10000], ['max_distance_km' => 25, 'amount' => 15000], ['max_distance_km' => 40, 'amount' => 20000]]]],
            'transport-vet-transfer' => ['tier' => 'vet', 'pricing' => ['type' => 'distance', 'rates' => [['max_distance_km' => 10, 'amount' => 12500], ['max_distance_km' => 25, 'amount' => 17500], ['max_distance_km' => 40, 'amount' => 22500]]]],
            'transport-airport-transfer' => ['tier' => 'airport', 'pricing' => ['type' => 'fixed', 'amount' => 25000]],
        ],
        'rules' => [
            'additional_pet_multiplier' => 0.3,
            'waiting_free_minutes' => 30,
            'waiting_increment_minutes' => 30,
            'waiting_increment_amount' => 2500,
            'additional_stop_amount' => 3000,
            'return_multiplier_before_waiting_minutes' => 1.8,
            'return_multiplier_after_waiting_minutes' => 2,
            'same_day_multiplier' => 1.25,
        ],
        'messages' => [
            'customer_estimate' => 'Provisional estimate. Final route, availability, and charges are confirmed on WhatsApp.',
            'after_hours' => 'After-hours transport requires a confirmed quote.',
            'special_handling' => 'Special handling requires a confirmed quote.',
            'multiple_pets' => 'Multiple pets require a confirmed safety assessment.',
            'outside_corridor' => 'Additional stops outside the route corridor require a confirmed quote.',
            'city_waiting' => 'Waiting rules for City Pet Transfer require a confirmed quote.',
            'outside_service_area' => 'This route is outside the provisional service area.',
        ],
    ],
];
