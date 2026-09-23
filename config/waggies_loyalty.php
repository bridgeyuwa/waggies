<?php

return [
    'hero' => [
        'rating' => '4.9',
        'reviewCount' => '500+',
        'title' => 'A loyalty programme managed by<br/><span class="text-primary italic">the Waggies team</span>',
        'description' => 'Loyalty is a manual programme managed through our team and SuiteCRM. Ask us about current benefits and eligibility; the website does not create accounts or track points.',
        'actions' => [
            ['label' => 'Ask our team about Loyalty', 'route' => 'contact', 'params' => ['intent' => 'loyalty', 'source' => 'loyalty']],
            ['label' => 'View Services', 'route' => 'services.index'],
        ],
    ],
    'howItWorksHeading' => [
        'eyebrow' => 'How It Works',
        'title' => 'A personal programme<br/>for Waggies customers',
        'subtitle' => 'Our team explains the current programme, confirms eligibility, and manages enrolment and benefits directly.',
    ],
    'howItWorks' => [
        ['step' => '01', 'icon' => 'chat', 'title' => 'Ask our team', 'desc' => 'Contact Waggies and tell us that you would like to learn about the loyalty programme.'],
        ['step' => '02', 'icon' => 'verified', 'title' => 'We confirm eligibility', 'desc' => 'The Waggies team checks your customer relationship and explains the benefits currently available.'],
        ['step' => '03', 'icon' => 'gift', 'title' => 'We manage the benefits', 'desc' => 'Enrolment and programme records are managed by the team through SuiteCRM, not through a website account.'],
    ],
    'tiersHeading' => [
        'eyebrow' => 'Programme boundary',
        'title' => 'What the website does not do',
    ],
    'tiers' => [
        [
            'tier' => 'No online account',
            'icon' => 'lock',
            'color' => 'text-primary-dark/60',
            'threshold' => 'Managed by the Waggies team',
            'perks' => ['No self-service account', 'No points ledger', 'No online redemption'],
        ],
        [
            'tier' => 'Manual enrolment',
            'icon' => 'team-member',
            'color' => 'text-gold',
            'threshold' => 'Ask about current benefits',
            'perks' => ['Customer relationship checked by staff', 'Benefits explained directly', 'SuiteCRM remains the external authority'],
        ],
    ],
    'startEarningCta' => [
        'heading' => 'Want to know more?',
        'body' => 'Ask the Waggies team about the current loyalty programme and how it is managed.',
        'ctaLabel' => 'Ask about Loyalty',
        'ctaRoute' => 'contact',
        'ctaParams' => ['intent' => 'loyalty', 'source' => 'loyalty'],
        'ctaIcon' => 'arrow-forward',
    ],
];
