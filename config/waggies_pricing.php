<?php

return [
    'currency' => 'NGN',
    'services' => [
        'boarding' => [
            'label' => 'Boarding', 'unit' => '/night', 'quantity_label' => 'Nights', 'min' => 1, 'max' => 30,
            'variants' => [
                'dogs' => ['label' => 'Dogs', 'tiers' => [
                    'basic' => ['label' => 'Basic', 'type' => 'fixed', 'amount' => 10000, 'features' => ['1 dog boarding suite', 'Daily outdoor walks', '24/7 supervision', ['label' => 'Grooming service', 'included' => false]]],
                    'premium' => ['label' => 'Premium', 'type' => 'fixed', 'amount' => 18000, 'featured' => true, 'badge' => 'Most Popular', 'features' => ['2 dogs boarding suite', 'Daily outdoor walks', '24/7 supervision', 'Grooming service']],
                    'deluxe' => ['label' => 'Deluxe', 'type' => 'fixed', 'amount' => 25000, 'features' => ['3+ dogs family suite', 'Daily outdoor walks', '24/7 supervision', 'Grooming + spa bath']],
                ]],
                'cats' => ['label' => 'Cats', 'tiers' => [
                    'cozy' => ['label' => 'Cozy', 'type' => 'fixed', 'amount' => 8000, 'features' => ['1 private cat condo', 'Daily enrichment & perches', 'Daily photo updates', '24/7 supervision', ['label' => 'Private playroom access', 'included' => false]]],
                    'premium' => ['label' => 'Premium', 'type' => 'fixed', 'amount' => 14000, 'featured' => true, 'badge' => 'Most Popular', 'features' => ['2 cats (same condo)', 'Daily enrichment & perches', 'Daily photo & video updates', '24/7 supervision', 'Private playroom time']],
                    'deluxe' => ['label' => 'Deluxe', 'type' => 'fixed', 'amount' => 22000, 'features' => ['Deluxe multi-level condo', '1-on-1 play & cuddle time', 'Daily vet health check', '24/7 supervision', 'Complimentary coat groom']],
                ]],
                'exotic' => ['label' => 'Exotic Pets', 'tiers' => [
                    'small' => ['label' => 'Small Mammal', 'type' => 'estimate', 'amount' => 6000, 'max_amount' => 8000, 'features' => ['Rabbits, guinea pigs, hamsters', 'Species-appropriate enclosure', 'Daily health check', '24/7 supervision', ['label' => 'Climate-controlled reptile setup', 'included' => false]]],
                    'specialist' => ['label' => 'Specialist', 'type' => 'estimate', 'amount' => 12000, 'max_amount' => 15000, 'featured' => true, 'badge' => 'Most Popular', 'features' => ['Birds or reptiles', 'Temp, humidity & UV monitoring', 'Custom feeding protocol', '24/7 supervision', 'Daily photo updates']],
                    'premium' => ['label' => 'Premium Habitat', 'type' => 'quote', 'amount' => 18000, 'features' => ['Complex multi-zone enclosures', 'Live/frozen feeder handling', 'Exotic vet daily review', '24/7 supervision', 'Owner-supplied habitat setup']],
                ]],
            ],
        ],
        'grooming' => ['label' => 'Grooming', 'unit' => '/session', 'quantity_label' => 'Sessions', 'min' => 1, 'max' => 12, 'tiers' => [
            'bath' => ['label' => 'Bath & Brush', 'type' => 'fixed', 'amount' => 10000, 'duration' => '45-60 min', 'features' => ['Shampoo bath', 'Blow-dry', 'Brush-out', 'Ear cleaning', 'Nail trim']],
            'full' => ['label' => 'Full Groom', 'type' => 'fixed', 'amount' => 18000, 'duration' => '90-120 min', 'featured' => true, 'badge' => 'Best Value', 'features' => ['Bath & Brush', 'Breed-standard clip and styling', 'Teeth brushing', 'De-shed treatment', 'Finish']],
            'spa' => ['label' => 'Luxury Spa', 'type' => 'fixed', 'amount' => 28000, 'duration' => '2-3 hours', 'features' => ['Full Groom', 'Conditioning treatment', 'Facial and paw treatment', 'Flea/tick inspection', 'Photo report']],
        ]],
        'vet-care' => ['label' => 'Veterinary Care', 'tiers' => ['consultation' => ['label' => 'Wellness Consultation', 'type' => 'fixed', 'amount' => 12000, 'features' => []], 'vaccination' => ['label' => 'Vaccination Visit', 'type' => 'fixed', 'amount' => 15000, 'features' => []], 'comprehensive-exam' => ['label' => 'Comprehensive Exam', 'type' => 'fixed', 'amount' => 18000, 'features' => []]]],
        'training' => ['label' => 'Dog Training', 'tiers' => ['puppy' => ['label' => 'Puppy Foundation', 'type' => 'estimate', 'amount' => 80000, 'max_amount' => 120000, 'features' => []], 'obedience' => ['label' => 'Basic Obedience', 'type' => 'estimate', 'amount' => 100000, 'max_amount' => 150000, 'features' => []], 'behaviour' => ['label' => 'Behaviour Modification', 'type' => 'quote', 'amount' => 0, 'features' => []]]],
        'local-transport' => ['label' => 'Local Transport', 'unit' => '/trip', 'quantity_label' => null, 'tiers' => [
            'city' => ['label' => 'City Pet Transfer', 'type' => 'quote', 'amount' => 0, 'features' => []],
            'vet' => ['label' => 'Vet Transfer', 'type' => 'quote', 'amount' => 0, 'features' => []],
            'airport' => ['label' => 'Airport Transfer', 'type' => 'quote', 'amount' => 0, 'featured' => true, 'features' => []],
        ]],
        'relocation' => ['label' => 'Pet Relocation', 'tiers' => ['import' => ['label' => 'Import to Nigeria', 'type' => 'quote', 'amount' => 0, 'features' => []], 'export' => ['label' => 'Export from Nigeria', 'type' => 'quote', 'amount' => 0, 'features' => []]]],
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
