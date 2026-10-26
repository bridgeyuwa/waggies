<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class AboutController extends Controller
{
    private const REGISTRATION_YEAR = 2018;

    private const REGISTRATION_MONTH = 8;

    public function __invoke(): View
    {
        $businessProfile = BusinessProfile::current();
        $currentDate = now();
        $yearsInBusiness = $currentDate->year - self::REGISTRATION_YEAR;

        if ($currentDate->month < self::REGISTRATION_MONTH) {
            $yearsInBusiness--;
        }

        $metadata = [
            'title' => 'About Waggies | Pet Services in Abuja',
            'description' => 'Waggies Pet Services provides dog and cat boarding and veterinary care in Abuja, with route-specific pet import and export coordination.',
            'canonical' => route('about'),
            'ogTitle' => 'About Waggies | Pet Services in Abuja',
            'ogDescription' => 'Waggies Pet Services provides dog and cat boarding and veterinary care in Abuja, with route-specific pet import and export coordination.',
        ];

        $this->setPageHead($metadata, [
            Schema::aboutPage()
                ->name('About Waggies - Pet Care in Abuja')
                ->description($metadata['description'])
                ->url($metadata['canonical'])
                ->toArray(),
        ]);

        return view('pages.about', $metadata + [
            'navSection' => 'about',
            'hero' => [
                'title' => 'Meet the Waggies Team in Abuja',
                'description' => 'At our Abuja centre, we provide dog and cat boarding and veterinary care. We also coordinate pet import and export requests for routes through Nnamdi Azikiwe International Airport.',
                'actions' => [
                    ['label' => 'Submit Booking Request', 'url' => route('book')],
                    ['label' => 'Explore Services', 'url' => route('services.index')],
                ],
                'imageSrc' => '/media/about/intro.jpg',
                'imageAlt' => 'A woman in a white coat standing outdoors',
            ],
            'stats' => [
                ['value' => '500+', 'label' => 'Clients Served'],
                ['value' => $yearsInBusiness, 'label' => 'Years in Business'],
                ['value' => '15+', 'label' => 'Specialists'],
                ['value' => '100%', 'label' => 'Protocol-Based Handling'],
            ],
            'philosophy' => [
                'eyebrow' => 'Our Philosophy', 'title' => 'Clear Standards for Pet Care in Abuja',
                'body' => [
                    'We care for each pet as an individual and keep owners informed. We confirm availability and pricing after reviewing a request.',
                ],
                'features' => [
                    ['icon' => 'values', 'title' => 'High Standards', 'description' => 'Clean enclosures and consistent care routines.'],
                    ['icon' => 'global', 'title' => 'Pet Travel Coordination', 'description' => 'Request-based import and export coordination for dogs and cats.'],
                    ['icon' => 'verified', 'title' => 'Request Review', 'description' => 'Care needs and final arrangements are reviewed before confirmation.'],
                    ['icon' => 'hours', 'title' => 'Clear Communication', 'description' => 'We explain availability, pricing, and next steps before confirmation.'],
                ],
            ],
            'mosaicImages' => [
                ['src' => '/media/about/veterinary-care.jpg', 'alt' => 'Veterinarian examining a dog in a clinic', 'offset' => true],
                ['src' => '/media/home/care-standards.jpg', 'alt' => 'Pet care standards at Waggies'],
            ],
            'story' => [
                'title' => 'Built by Pet Lovers,<br />for Pet Owners',
                'paragraphs' => [
                    'Waggies began as a pet boarding service for dogs and cats in Abuja. Today, the team also offers veterinary care and import and export coordination.',
                    'We believe pet care should be attentive to each animal and clear for the people responsible for them.',
                ],
                'stats' => [['value' => 'Boarding', 'label' => 'For dogs and cats'], ['value' => 'Vet Care', 'label' => 'Veterinary requests'], ['value' => 'Import', 'label' => 'Pet travel to Nigeria'], ['value' => 'Export', 'label' => 'Pet travel from Nigeria']],
            ],
            'standards' => [
                'eyebrow' => 'Operating Standards', 'title' => 'Our Standard of Handling<br/><span class="text-secondary italic">Across All Services</span>',
                'subtitle' => 'Whether it is boarding, veterinary care, or relocation - our decisions follow one rule: what is best for the animal comes first.',
                'features' => [
                    ['icon' => 'transport', 'title' => 'Supervised Pet-First Care', 'description' => 'We follow handling protocols with each pet’s comfort and wellbeing in mind.'],
                    ['icon' => 'process', 'title' => 'Consistency Across Services', 'description' => 'Boarding, veterinary, and relocation requests follow clear review and communication standards.'],
                    ['icon' => 'security', 'title' => 'Controlled Safety Protocols', 'description' => 'Pets are separated by risk level, monitored during movement, and handled under strict safety procedures.'],
                    ['icon' => 'visibility', 'title' => 'Transparent Owner Communication', 'description' => 'We keep owners informed about their pet’s care and progress.'],
                ],
            ],
            'teamHeading' => ['eyebrow' => 'Our Team', 'title' => 'Meet the Waggies Team', 'intro' => 'The people behind Waggies who keep your pets healthy, safe, and happy.'],
            'team' => [
                ['name' => 'Dr. Nguhemen Doose Yuwa', 'role' => 'DVM, Head Veterinarian and Director', 'responsibility' => 'Leads all veterinary services and oversees clinical operations at Waggies.', 'imageSrc' => '/media/about/team/01-nguhemen-doose-yuwa.jpg', 'imageAlt' => 'Portrait of Dr. Nguhemen Doose Yuwa', 'verified' => true],
                ['name' => 'Aifuwa Bob Osaze', 'role' => 'Director', 'responsibility' => 'Oversees business strategy, partnerships, and overall operations at Waggies.', 'imageSrc' => '/media/about/team/02-aifuwa-bob-osaze.jpg', 'imageAlt' => 'Portrait of Aifuwa Bob Osaze', 'verified' => true],
                ['name' => 'Oghomwen Oyuki Obaseki', 'role' => 'Secretary', 'responsibility' => 'Manages administrative operations, scheduling, and client communications.', 'imageSrc' => '/media/about/team/03-oghomwen-oyuki-obaseki.jpg', 'imageAlt' => 'Portrait of Oghomwen Oyuki Obaseki', 'verified' => true],
                ['name' => 'Chukwuma Eze', 'role' => 'Veterinary Support', 'imageSrc' => '/media/about/team/04-chukwuma-eze.jpg', 'imageAlt' => 'Portrait of Chukwuma Eze'],
                ['name' => 'Amina Bello', 'role' => 'Lead Groomer', 'imageSrc' => '/media/about/team/05-amina-bello.jpg', 'imageAlt' => 'Portrait of Amina Bello'],
                ['name' => 'Dayo Adekunle', 'role' => 'Boarding Manager', 'imageSrc' => '/media/about/team/06-dayo-adekunle.jpg', 'imageAlt' => 'Portrait of Dayo Adekunle'],
                ['name' => 'Kemi Ogunleye', 'role' => 'Pet Trainer', 'imageSrc' => '/media/about/team/07-kemi-ogunleye.jpg', 'imageAlt' => 'Portrait of Kemi Ogunleye'],
                ['name' => 'Suleiman Ahmadu', 'role' => 'Relocation & Transport Coordinator', 'imageSrc' => '/media/about/team/08-suleiman-ahmadu.jpg', 'imageAlt' => 'Portrait of Suleiman Ahmadu'],
            ],
            'address' => $businessProfile->addressLine(),
            'cta' => ['heading' => 'Explore Our Pet Care Services', 'body' => 'Learn about boarding, veterinary care, and relocation for dogs and cats.', 'label' => 'View Services'],
        ]);
    }
}
