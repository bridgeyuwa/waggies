<?php

return [
    'hero' => [
        'rating' => '4.9',
        'reviewCount' => '500+',
        'title' => 'Rewards for Every<br/><span class="text-primary italic">Waggies Visit</span>',
        'description' => 'Earn points every time you use Waggies. Redeem them for free services, discounts, and exclusive member perks.',
        'actions' => [
            ['label' => 'Join for Free', 'route' => 'contact'],
            ['label' => 'View Services', 'route' => 'services.index'],
        ],
    ],
    'howItWorksHeading' => [
        'eyebrow' => 'How It Works',
        'title' => 'Three Simple Steps<br/>to Earning Rewards',
        'subtitle' => 'Our loyalty programme is free to join and designed to reward you every time you choose Waggies.',
    ],
    'howItWorks' => [
        ['step' => '01', 'icon' => 'team-member', 'title' => 'Sign Up Free', 'desc' => 'Register in minutes at reception or by contacting us. No fees, no catches.'],
        ['step' => '02', 'icon' => 'star', 'title' => 'Earn Points', 'desc' => 'Earn points on every booking  -  boarding, grooming, vet visits, training, and transport.'],
        ['step' => '03', 'icon' => 'gift', 'title' => 'Redeem Rewards', 'desc' => 'Spend your points on free nights, free grooms, discounts, and members-only offers.'],
    ],
    'tiersHeading' => [
        'eyebrow' => 'Membership Tiers',
        'title' => 'The More You Visit,<br/>the More You Earn',
    ],
    'tiers' => [
        [
            'tier' => 'Silver',
            'icon' => 'achievement',
            'color' => 'text-primary-dark/60',
            'threshold' => 'From your first visit',
            'perks' => ['1 point per ₦1,000 spent', '5% birthday discount', 'Priority booking access'],
        ],
        [
            'tier' => 'Gold',
            'icon' => 'achievement',
            'color' => 'text-gold',
            'threshold' => 'After 10 visits',
            'perks' => ['1.5 points per ₦1,000 spent', '10% birthday discount', 'Free monthly nail trim', 'Early access to promotions'],
        ],
        [
            'tier' => 'Platinum',
            'icon' => 'achievement',
            'color' => 'text-primary',
            'threshold' => 'After 25 visits',
            'perks' => ['2 points per ₦1,000 spent', '15% birthday discount', 'Free monthly groom', 'Dedicated account manager', 'Exclusive member events'],
        ],
    ],
    'startEarningCta' => [
        'heading' => 'Start earning today',
        'body' => 'Join the Waggies Loyalty Programme for free and start earning rewards from your very first visit.',
        'ctaLabel' => 'Join Now',
        'ctaRoute' => 'contact',
        'ctaIcon' => 'arrow-forward',
    ],
];
