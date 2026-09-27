<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class AboutController extends Controller
{
    public function __invoke(): View
    {
        $businessProfile = BusinessProfile::current();

        $metadata = [
            'title' => 'About Us',
            'description' => 'Waggies is a pet care centre in Abuja offering boarding, veterinary care, and dog and cat relocation.',
            'canonical' => route('about'),
            'ogTitle' => 'About Waggies - Pet Care in Abuja',
            'ogDescription' => 'Waggies is a pet care centre in Abuja offering boarding, veterinary care, and dog and cat relocation.',
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
                'title' => 'Complete Veterinary & Pet Care in Abuja',
                'description' => 'Boarding, veterinary care, and dog and cat relocation - all coordinated through one clear request process.',
                'actions' => [
                    ['label' => 'Submit Booking Request', 'url' => route('book')],
                    ['label' => 'Explore Services', 'url' => route('services.index')],
                ],
                'imageSrc' => '/media/about/intro.jpg',
                'imageAlt' => 'Complete Veterinary & Pet Care in Abuja',
            ],
            'stats' => [
                ['value' => '500+', 'label' => 'Clients Served'],
                ['value' => '3', 'label' => 'Core Services'],
                ['value' => '15+', 'label' => 'Global Specialists'],
                ['value' => '100%', 'label' => '100% Protocol-Based Handling'],
            ],
            'philosophy' => [
                'eyebrow' => 'Our Philosophy', 'title' => 'Pet Care Standards in West Africa',
                'body' => [
                    "Founded to bridge the gap in dedicated pet services, Waggies has grown into Abuja's most comprehensive pet care centre. We believe thorough, consistent care matters.",
                    'Our facility combines veterinary care with comfortable, clean spaces. Whether it is a boarding request, a veterinary visit, or international relocation, we handle each request with care and precision.',
                    'Pet Care Should Never Be Reactive: We believe animals benefit from the same structure and planning as human healthcare - not rushed decisions, not inconsistent handling, but deliberate care built around long-term wellbeing.',
                ],
                'features' => [
                    ['icon' => 'values', 'title' => 'High Standards', 'description' => 'Clean enclosures and consistent care routines.'],
                    ['icon' => 'global', 'title' => 'Global Mobility', 'description' => 'Experienced in straightforward international pet travel.'],
                    ['icon' => 'verified', 'title' => 'Expert Review', 'description' => 'Care needs and final arrangements are reviewed before confirmation.'],
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
                    'Waggies was born from a personal experience  -  a pet owner in Abuja struggling to find a boarding facility that matched the standard of care they gave their own dog at home. What started as a small dog hotel has grown into Abuja\'s most comprehensive pet care centre.',
                    'Today, we offer boarding, veterinary care, and import and export relocation coordination for dogs and cats - all through one consistent request and review process.',
                    'Every member of our team is an animal lover first. We believe the best pet care comes from genuine passion  -  not just process.',
                    'Waggies began with a simple but frustrating reality - pet owners in Abuja needed clearer, more dependable coordination for boarding, veterinary care, and pet travel. Our work is focused on making those requests easier to understand and safer to review.',
                ],
                'stats' => [['value' => '500+', 'label' => 'Happy Clients'], ['value' => '7', 'label' => 'Years in Business'], ['value' => '4.9', 'label' => 'Average Rating'], ['value' => '15+', 'label' => 'Team Members']],
            ],
            'standards' => [
                'eyebrow' => 'Operating Standards', 'title' => 'Our Standard of Handling<br/><span class="text-secondary italic">Across All Services</span>',
                'subtitle' => 'Whether it is boarding, veterinary care, or relocation - our decisions follow one rule: what is best for the animal comes first.',
                'features' => [
                    ['icon' => 'transport', 'title' => 'Supervised Pet-First Care', 'description' => 'Every service is designed around comfort, safety, and wellbeing  -  never convenience or speed.'],
                    ['icon' => 'process', 'title' => 'Consistency Across Services', 'description' => 'Boarding, veterinary, and relocation requests follow clear review and communication standards.'],
                    ['icon' => 'security', 'title' => 'Controlled Safety Protocols', 'description' => 'Pets are separated by risk level, monitored during movement, and handled under strict safety procedures.'],
                    ['icon' => 'visibility', 'title' => 'Transparent Owner Communication', 'description' => 'Owners receive clear updates on condition, progress, and any incidents  -  with no hidden details.'],
                ],
            ],
            'teamHeading' => ['eyebrow' => 'Our Team', 'title' => 'Care shaped around your pet', 'intro' => 'Our current public team directory is being updated. Ask our team for the right point of contact for your pet’s care.'],
            'team' => [
                ['name' => 'Veterinary care', 'role' => 'Clinical support', 'responsibility' => 'Ask our team about current veterinary availability and the right next step for your pet.', 'icon' => 'veterinary-care'],
                ['name' => 'Boarding care', 'role' => 'Pet care team', 'responsibility' => 'We will help you choose a calm, suitable routine for your pet’s stay.', 'icon' => 'boarding'],
                ['name' => 'Relocation coordination', 'role' => 'Client support', 'responsibility' => 'Share your route and timeline with our team so we can explain the requirements that apply.', 'icon' => 'airport-departure'],
            ],
            'address' => $businessProfile->addressLine(),
            'cta' => ['heading' => 'Explore Our Pet Care Services', 'body' => 'Learn about boarding, veterinary care, and relocation for dogs and cats.', 'label' => 'View Services'],
        ]);
    }
}
