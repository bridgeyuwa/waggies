<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hero imagery fallbacks
    |--------------------------------------------------------------------------
    |
    | Used when a page or post has no uploaded image. Prefer stable CDN URLs
    | until local assets exist in public/images/.
    |
    */

    'hero_images' => [
        'default' => 'https://images.unsplash.com/photo-1650454027983-e2b8fe55b30b?w=1600&auto=format&fit=crop&q=80',
        'blog' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=1600&auto=format&fit=crop&q=80',
        'guide' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=1600&auto=format&fit=crop&q=80',
        'kb' => 'https://images.unsplash.com/photo-1628009365291-4e3c4b2e1b1e?w=1600&auto=format&fit=crop&q=80',
        'faq' => 'https://images.unsplash.com/photo-1628009365291-4e3c4b2e1b1e?w=1600&auto=format&fit=crop&q=80',
        'checklist' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1600&auto=format&fit=crop&q=80',
        'services' => 'https://images.unsplash.com/photo-1650454027983-e2b8fe55b30b?w=1600&auto=format&fit=crop&q=80',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing calculator (single source of truth)
    |--------------------------------------------------------------------------
    |
    | type: fixed | estimate | quote
    | fixed → price + Book Now | estimate → range + Proceed | quote → Request Consultation
    |
    */

    'pricing' => [
        'aliases' => [
            'boarding-dogs' => ['service' => 'boarding', 'variant' => 'dogs'],
            'boarding-cats' => ['service' => 'boarding', 'variant' => 'cats'],
            'boarding-exotic' => ['service' => 'boarding', 'variant' => 'exotic'],
            'vet' => ['service' => 'vet-care'],
        ],

        'services' => [
            'boarding' => [
                'label' => 'Boarding',
                'unit' => '/night',
                'quantity_label' => 'Nights',
                'quantity_min' => 1,
                'quantity_max' => 30,
                'variants' => [
                    'dogs' => [
                        'label' => 'Dogs',
                        'tiers' => [
                            'basic' => [
                                'label' => 'Basic',
                                'type' => 'fixed',
                                'amount' => 10000,
                                'featured' => false,
                                'features' => [
                                    ['label' => '1 dog boarding', 'included' => true],
                                    ['label' => 'Daily walks', 'included' => true],
                                    ['label' => '24/7 supervision', 'included' => false],
                                ],
                            ],
                            'premium' => [
                                'label' => 'Premium',
                                'type' => 'fixed',
                                'amount' => 18000,
                                'featured' => true,
                                'badge' => 'Most Popular',
                                'features' => [
                                    ['label' => '2 dogs boarding', 'included' => true],
                                    ['label' => 'Daily walks', 'included' => true],
                                    ['label' => '24/7 supervision', 'included' => true],
                                ],
                            ],
                            'deluxe' => [
                                'label' => 'Deluxe',
                                'type' => 'fixed',
                                'amount' => 25000,
                                'featured' => false,
                                'features' => [
                                    ['label' => '3+ dogs boarding', 'included' => true],
                                    ['label' => 'Grooming + spa', 'included' => true],
                                    ['label' => '24/7 supervision', 'included' => true],
                                ],
                            ],
                        ],
                    ],
                    'cats' => [
                        'label' => 'Cats',
                        'tiers' => [
                            'cozy' => [
                                'label' => 'Cozy',
                                'type' => 'fixed',
                                'amount' => 8000,
                                'featured' => false,
                                'features' => [
                                    ['label' => 'Quiet cat condo', 'included' => true],
                                    ['label' => 'Daily enrichment', 'included' => true],
                                ],
                            ],
                            'premium' => [
                                'label' => 'Premium',
                                'type' => 'fixed',
                                'amount' => 14000,
                                'featured' => true,
                                'badge' => 'Most Popular',
                                'features' => [
                                    ['label' => 'Private suite', 'included' => true],
                                    ['label' => 'Daily photo updates', 'included' => true],
                                ],
                            ],
                            'luxury' => [
                                'label' => 'Luxury',
                                'type' => 'fixed',
                                'amount' => 22000,
                                'featured' => false,
                                'features' => [
                                    ['label' => 'Luxury suite', 'included' => true],
                                    ['label' => 'Daily vet check', 'included' => true],
                                ],
                            ],
                        ],
                    ],
                    'exotic' => [
                        'label' => 'Exotic Pets',
                        'tiers' => [
                            'small' => [
                                'label' => 'Small Mammal',
                                'type' => 'estimate',
                                'amount' => 6000,
                                'amount_max' => 8000,
                                'featured' => false,
                                'features' => [
                                    ['label' => 'Rabbits, guinea pigs, hamsters', 'included' => true],
                                ],
                            ],
                            'specialist' => [
                                'label' => 'Specialist',
                                'type' => 'estimate',
                                'amount' => 12000,
                                'amount_max' => 15000,
                                'featured' => true,
                                'badge' => 'Most Popular',
                                'features' => [
                                    ['label' => 'Birds or reptiles', 'included' => true],
                                    ['label' => 'Climate monitoring', 'included' => true],
                                ],
                            ],
                            'premium' => [
                                'label' => 'Premium Habitat',
                                'type' => 'quote',
                                'amount' => 18000,
                                'featured' => false,
                                'features' => [
                                    ['label' => 'Complex multi-zone enclosures', 'included' => true],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'grooming' => [
                'label' => 'Grooming',
                'unit' => '/session',
                'quantity_label' => 'Sessions',
                'quantity_min' => 1,
                'quantity_max' => 12,
                'tiers' => [
                    'bath' => [
                        'label' => 'Bath & Brush',
                        'type' => 'fixed',
                        'amount' => 8000,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Luxury bath', 'included' => true],
                            ['label' => 'Blow-dry & brush', 'included' => true],
                        ],
                    ],
                    'full' => [
                        'label' => 'Full Groom',
                        'type' => 'fixed',
                        'amount' => 18000,
                        'featured' => true,
                        'badge' => 'Best Value',
                        'features' => [
                            ['label' => 'Breed-standard cut', 'included' => true],
                            ['label' => 'Nail trim & ear clean', 'included' => true],
                        ],
                    ],
                    'spa' => [
                        'label' => 'Luxury Spa',
                        'type' => 'fixed',
                        'amount' => 28000,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Premium bath & mask', 'included' => true],
                            ['label' => 'De-shed treatment', 'included' => true],
                        ],
                    ],
                ],
            ],
            'vet-care' => [
                'label' => 'Veterinary Care',
                'unit' => '',
                'quantity_label' => null,
                'tiers' => [
                    'consultation' => [
                        'label' => 'General Consultation',
                        'type' => 'fixed',
                        'amount' => 12000,
                        'featured' => true,
                        'badge' => 'From',
                        'features' => [
                            ['label' => 'Wellness examination', 'included' => true],
                            ['label' => 'Health advice', 'included' => true],
                        ],
                    ],
                    'vaccination' => [
                        'label' => 'Vaccination Visit',
                        'type' => 'estimate',
                        'amount' => 15000,
                        'amount_max' => 35000,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Vaccines priced per schedule', 'included' => true],
                        ],
                    ],
                    'treatment' => [
                        'label' => 'Treatment Plan',
                        'type' => 'quote',
                        'amount' => 0,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Diagnosis & prescriptions', 'included' => true],
                        ],
                    ],
                ],
            ],
            'training' => [
                'label' => 'Dog Training',
                'unit' => '/programme',
                'quantity_label' => null,
                'tiers' => [
                    'puppy' => [
                        'label' => 'Puppy Foundation',
                        'type' => 'estimate',
                        'amount' => 80000,
                        'amount_max' => 120000,
                        'featured' => false,
                        'features' => [
                            ['label' => '6-week foundation programme', 'included' => true],
                        ],
                    ],
                    'obedience' => [
                        'label' => 'Basic Obedience',
                        'type' => 'estimate',
                        'amount' => 100000,
                        'amount_max' => 150000,
                        'featured' => true,
                        'badge' => 'Popular',
                        'features' => [
                            ['label' => 'Structured weekly sessions', 'included' => true],
                        ],
                    ],
                    'behaviour' => [
                        'label' => 'Behaviour Modification',
                        'type' => 'quote',
                        'amount' => 0,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Tailored plan after assessment', 'included' => true],
                        ],
                    ],
                ],
            ],
            'relocation' => [
                'label' => 'Pet Relocation',
                'unit' => '',
                'quantity_label' => null,
                'tiers' => [
                    'local' => [
                        'label' => 'Local Transport',
                        'type' => 'estimate',
                        'amount' => 25000,
                        'amount_max' => 75000,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Abuja & surrounding routes', 'included' => true],
                        ],
                    ],
                    'import' => [
                        'label' => 'Import to Nigeria',
                        'type' => 'quote',
                        'amount' => 0,
                        'featured' => true,
                        'badge' => 'Custom quote',
                        'features' => [
                            ['label' => 'Permits, health certs & airport collection', 'included' => true],
                        ],
                    ],
                    'export' => [
                        'label' => 'Export from Nigeria',
                        'type' => 'quote',
                        'amount' => 0,
                        'featured' => false,
                        'features' => [
                            ['label' => 'Export permits & airline coordination', 'included' => true],
                        ],
                    ],
                ],
            ],
        ],
    ],

];
