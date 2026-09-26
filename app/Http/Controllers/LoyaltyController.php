<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class LoyaltyController extends Controller
{
    public function __invoke(): View
    {
        $page = $this->pageData();
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);

        $metadata = [
            'title' => 'Loyalty Programme - Waggies',
            'description' => 'Learn how Waggies manages customer loyalty benefits through a manual team-led programme.',
            'canonical' => route('loyalty'),
            'ogTitle' => 'Loyalty Programme - Waggies Pet Care',
            'ogDescription' => 'Learn how Waggies manages customer loyalty benefits through a manual team-led programme.',
        ];
        $this->setPageHead($metadata, [Schema::webPage()->name('Loyalty Programme - Waggies Pet Care')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.loyalty', $metadata + [
            'navSection' => '',
            'page' => $page,
        ]);
    }

    private function pageData(): array
    {
        return [
            'hero' => [
                'rating' => '4.9',
                'reviewCount' => '500+',
                'title' => 'A loyalty programme managed by<br/><span class="text-primary italic">the Waggies team</span>',
                'description' => 'Loyalty is a manual programme managed through our team and SuiteCRM. Ask us about current benefits and eligibility; the website does not create accounts or track points.',
                'actions' => [
                    0 => [
                        'label' => 'Ask our team about Loyalty',
                        'route' => 'contact',
                        'params' => [
                            'intent' => 'loyalty',
                            'source' => 'loyalty',
                        ],
                    ],
                    1 => [
                        'label' => 'View Services',
                        'route' => 'services.index',
                    ],
                ],
            ],
            'howItWorksHeading' => [
                'eyebrow' => 'How It Works',
                'title' => 'A personal programme<br/>for Waggies customers',
                'subtitle' => 'Our team explains the current programme, confirms eligibility, and manages enrolment and benefits directly.',
            ],
            'howItWorks' => [
                0 => [
                    'step' => '01',
                    'icon' => 'chat',
                    'title' => 'Ask our team',
                    'desc' => 'Contact Waggies and tell us that you would like to learn about the loyalty programme.',
                ],
                1 => [
                    'step' => '02',
                    'icon' => 'verified',
                    'title' => 'We confirm eligibility',
                    'desc' => 'The Waggies team checks your customer relationship and explains the benefits currently available.',
                ],
                2 => [
                    'step' => '03',
                    'icon' => 'gift',
                    'title' => 'We manage the benefits',
                    'desc' => 'Enrolment and programme records are managed by the team through SuiteCRM, not through a website account.',
                ],
            ],
            'tiersHeading' => [
                'eyebrow' => 'Programme boundary',
                'title' => 'What the website does not do',
            ],
            'tiers' => [
                0 => [
                    'tier' => 'No online account',
                    'icon' => 'lock',
                    'color' => 'text-primary-dark/60',
                    'threshold' => 'Managed by the Waggies team',
                    'perks' => [
                        0 => 'No self-service account',
                        1 => 'No points ledger',
                        2 => 'No online redemption',
                    ],
                ],
                1 => [
                    'tier' => 'Manual enrolment',
                    'icon' => 'team-member',
                    'color' => 'text-gold',
                    'threshold' => 'Ask about current benefits',
                    'perks' => [
                        0 => 'Customer relationship checked by staff',
                        1 => 'Benefits explained directly',
                        2 => 'SuiteCRM remains the external authority',
                    ],
                ],
            ],
            'startEarningCta' => [
                'heading' => 'Want to know more?',
                'body' => 'Ask the Waggies team about the current loyalty programme and how it is managed.',
                'ctaLabel' => 'Ask about Loyalty',
                'ctaRoute' => 'contact',
                'ctaParams' => [
                    'intent' => 'loyalty',
                    'source' => 'loyalty',
                ],
                'ctaIcon' => 'arrow-forward',
            ],
        ];
    }
}
