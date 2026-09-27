<?php

return [
    'navigation' => [
        'services' => [
            'label' => 'Services',
            'route' => 'services.index',
            'kind' => 'mega',
            'hubLabel' => 'View All Services',
            'columns' => [
                [
                    'label' => 'Boarding',
                    'items' => [
                        ['label' => 'Boarding', 'route' => 'services.boarding', 'description' => 'Overnight stays for dogs and cats', 'icon' => 'boarding', 'variant' => 'overview'],
                        ['label' => 'Dog Boarding', 'route' => 'services.boarding.species', 'params' => ['species' => 'dogs'], 'description' => 'Direct size guidance', 'icon' => 'dog', 'variant' => 'child'],
                        ['label' => 'Cat Boarding', 'route' => 'services.boarding.species', 'params' => ['species' => 'cats'], 'description' => 'One nightly request path', 'icon' => 'cat', 'variant' => 'child'],
                    ],
                ],
                [
                    'label' => 'Veterinary Care',
                    'items' => [
                        ['label' => 'Veterinary Care', 'route' => 'services.vet-care', 'description' => 'Consultations, examinations, vaccines and microchipping', 'icon' => 'veterinary-care'],
                        ['label' => 'Pricing guide', 'route' => 'services.pricing', 'description' => 'Indicative guidance and request paths', 'icon' => 'receipt'],
                    ],
                ],
                [
                    'label' => 'Relocation',
                    'items' => [
                        ['label' => 'Relocation', 'route' => 'services.relocation', 'description' => 'Import and export coordination', 'icon' => 'relocation', 'variant' => 'overview'],
                        ['label' => 'Pet Import', 'route' => 'relocation.import', 'description' => 'Bring a pet into Nigeria', 'icon' => 'airport-arrival', 'variant' => 'child'],
                        ['label' => 'Pet Export', 'route' => 'relocation.export', 'description' => 'Move a pet abroad', 'icon' => 'airport-departure', 'variant' => 'child'],
                    ],
                ],
            ],
        ],
        'about' => [
            'label' => 'About',
            'route' => 'about',
            'kind' => 'stacked',
            'flyoutLabel' => 'About Waggies',
            'items' => [
                ['label' => 'About Us', 'route' => 'about', 'description' => 'Our story and mission', 'icon' => 'info'],
                ['label' => 'Testimonials', 'route' => 'about.testimonials', 'description' => 'What pet owners say', 'icon' => 'contact'],
                ['label' => 'Gallery', 'route' => 'about.gallery', 'description' => 'Moments at Waggies', 'icon' => 'photo'],
                ['label' => 'Careers', 'route' => 'about.careers', 'description' => 'Join our team', 'icon' => 'career'],
                ['label' => 'Partnerships', 'route' => 'about.partnerships', 'description' => 'Grow with us', 'icon' => 'partnership'],
            ],
        ],
        'tools' => [
            'label' => 'Tools',
            'route' => 'tools.index',
            'kind' => 'mega',
            'hubLabel' => 'View All Tools',
            'flyoutLabel' => 'Pet Care Tools',
            'columns' => [
                ['label' => 'Pet Health', 'items' => [
                    ['label' => 'Symptom Checker', 'route' => 'tools.symptom-checker', 'description' => 'Check symptoms and get guidance', 'icon' => 'medical'],
                    ['label' => 'Vaccination Schedule', 'route' => 'tools.vaccination', 'description' => 'Educational vaccination timeline', 'icon' => 'vaccination'],
                    ['label' => 'Parasite & Deworming', 'route' => 'tools.parasite', 'description' => 'Prevention schedule', 'icon' => 'parasite'],
                    ['label' => 'Emergency & Poison Guide', 'route' => 'tools.emergency', 'description' => 'Urgent care reference', 'icon' => 'emergency'],
                ]],
                ['label' => 'Pet Care', 'items' => [
                    ['label' => 'Pet Age Calculator', 'route' => 'tools.pet-age', 'description' => 'Pet-to-human age conversion', 'icon' => 'pets'],
                    ['label' => 'Diet & Nutrition Calculator', 'route' => 'tools.nutrition', 'description' => 'Diet and feeding estimates', 'icon' => 'nutrition'],
                    ['label' => 'Behavior & Training Tips', 'route' => 'tools.behavior-tips', 'description' => 'General pet behavior advice', 'icon' => 'behavior'],
                    ['label' => 'Breed Info Finder', 'route' => 'tools.breed-finder', 'description' => 'Explore dog and cat breeds', 'icon' => 'search'],
                ]],
                ['label' => 'Planning', 'items' => [
                    ['label' => 'New Pet Checklist', 'route' => 'tools.new-pet-checklist', 'description' => 'Everything for a new pet', 'icon' => 'checklist'],
                ]],
            ],
        ],
        'resources' => [
            'label' => 'Resources',
            'route' => 'guides.index',
            'kind' => 'stacked',
            'flyoutLabel' => 'Resources',
            'items' => [
                ['label' => 'FAQ', 'route' => 'faq', 'description' => 'Quick answers', 'icon' => 'help'],
                ['label' => 'Policies', 'route' => 'policies', 'description' => 'Service requirements and terms', 'icon' => 'document'],
                ['label' => 'Guides', 'route' => 'guides.index', 'description' => 'In-depth pet care guides', 'icon' => 'guide'],
                ['label' => 'Knowledge Base', 'route' => 'knowledge-base.index', 'description' => 'Pet care answers', 'icon' => 'knowledge-base'],
            ],
        ],
    ],
];
