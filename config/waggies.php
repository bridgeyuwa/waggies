<?php

return [
    'navigation' => [
        'services' => ['label' => 'Services', 'route' => 'services.index', 'kind' => 'mega', 'hubLabel' => 'View All Services', 'columns' => [
            ['label' => 'Boarding', 'items' => [
                ['label' => 'Boarding', 'route' => 'services.boarding', 'description' => 'Overnight stays', 'icon' => 'boarding', 'variant' => 'overview'],
                ['label' => 'Dog Boarding', 'route' => 'services.boarding.species', 'params' => ['species' => 'dogs'], 'description' => 'Tailored for your dog', 'icon' => 'dog', 'variant' => 'child'],
                ['label' => 'Cat Boarding', 'route' => 'services.boarding.species', 'params' => ['species' => 'cats'], 'description' => 'Calm feline retreat', 'icon' => 'cat', 'variant' => 'child'],
                ['label' => 'Exotic Pets', 'route' => 'services.boarding.species', 'params' => ['species' => 'exotic'], 'description' => 'Specialist care', 'icon' => 'exotic-pet', 'variant' => 'child'],
            ]],
            ['label' => 'Wellness', 'items' => [
                ['label' => 'Grooming', 'route' => 'services.grooming', 'description' => 'Breed-specific treatments', 'icon' => 'grooming'],
                ['label' => 'Vet Care', 'route' => 'services.vet-care', 'description' => 'On-site veterinary support', 'icon' => 'veterinary-care'],
                ['label' => 'Dog Training', 'route' => 'services.training', 'description' => 'Positive-reinforcement methods', 'icon' => 'training'],
            ]],
            ['label' => 'Relocation', 'items' => [
                ['label' => 'Relocation', 'route' => 'services.relocation', 'description' => 'International pet moves', 'icon' => 'relocation', 'variant' => 'overview'],
                ['label' => 'Pet Import', 'route' => 'relocation.import', 'description' => 'Bringing pets into Nigeria', 'icon' => 'airport-arrival', 'variant' => 'child'],
                ['label' => 'Pet Export', 'route' => 'relocation.export', 'description' => 'Moving pets abroad', 'icon' => 'airport-departure', 'variant' => 'child'],
                ['label' => 'Local Transport', 'route' => 'relocation.transport', 'description' => 'Door-to-door pickup', 'icon' => 'transport', 'variant' => 'child'],
            ]],
        ]],
        'about' => ['label' => 'About', 'route' => 'about', 'kind' => 'stacked', 'flyoutLabel' => 'About Waggies', 'items' => [
            ['label' => 'About Us', 'route' => 'about', 'description' => 'Our story and mission', 'icon' => 'info'],
            ['label' => 'Testimonials', 'route' => 'about.testimonials', 'description' => 'What pet owners say', 'icon' => 'contact'],
            ['label' => 'Gallery', 'route' => 'about.gallery', 'description' => 'Moments at Waggies', 'icon' => 'photo'],
            ['label' => 'Careers', 'route' => 'about.careers', 'description' => 'Join our team', 'icon' => 'career'],
            ['label' => 'Partnerships', 'route' => 'about.partnerships', 'description' => 'Grow with us', 'icon' => 'partnership'],
        ]],
        'tools' => ['label' => 'Tools', 'route' => 'tools.index', 'kind' => 'mega', 'hubLabel' => 'View All Tools', 'flyoutLabel' => 'Pet Care Tools', 'columns' => [
            ['label' => 'Pet Health', 'items' => [
                ['label' => 'Symptom Checker', 'route' => 'tools.symptom-checker', 'description' => 'Check symptoms and get guidance', 'icon' => 'medical'],
                ['label' => 'Vaccination Schedule', 'route' => 'tools.vaccination', 'description' => 'Recommended vaccination timeline', 'icon' => 'vaccination'],
                ['label' => 'Parasite & Deworming', 'route' => 'tools.parasite', 'description' => 'Prevention schedule', 'icon' => 'parasite'],
                ['label' => 'Emergency & Poison Guide', 'route' => 'tools.emergency', 'description' => 'Urgent care reference', 'icon' => 'emergency'],
            ]],
            ['label' => 'Pet Care', 'items' => [
                ['label' => 'Pet Age Calculator', 'route' => 'tools.pet-age', 'description' => 'Pet-to-human age conversion', 'icon' => 'pets'],
                ['label' => 'Diet & Nutrition Calculator', 'route' => 'tools.nutrition', 'description' => 'Diet & feeding estimates', 'icon' => 'nutrition'],
                ['label' => 'Behavior & Training Tips', 'route' => 'tools.behavior-tips', 'description' => 'Practical pet behavior advice', 'icon' => 'behavior'],
                ['label' => 'Breed Info Finder', 'route' => 'tools.breed-finder', 'description' => 'Explore dog & cat breeds', 'icon' => 'search'],
            ]],
            ['label' => 'Planning', 'items' => [
                ['label' => 'New Pet Checklist', 'route' => 'tools.new-pet-checklist', 'description' => 'Everything for a new pet', 'icon' => 'checklist'],
            ]],
        ]],
        'resources' => ['label' => 'Resources', 'route' => 'guides.index', 'kind' => 'stacked', 'flyoutLabel' => 'Resources', 'items' => [
            ['label' => 'FAQ', 'route' => 'faq', 'description' => 'Quick answers', 'icon' => 'help'],
            ['label' => 'Guides', 'route' => 'guides.index', 'description' => 'In-depth pet care guides', 'icon' => 'guide'],
            ['label' => 'Knowledge Base', 'route' => 'knowledge-base.index', 'description' => 'Pet care answers', 'icon' => 'knowledge-base'],
        ]],
    ],
];
