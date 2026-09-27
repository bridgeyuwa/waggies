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
            'description' => 'Waggies is a full-service pet care centre in Abuja offering boarding, grooming, vet care, training, and relocation under one roof.',
            'canonical' => route('about'),
            'ogTitle' => 'About Waggies - Pet Care in Abuja',
            'ogDescription' => 'Waggies is a full-service pet care centre in Abuja offering boarding, grooming, vet care, training, and relocation under one roof.',
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
                'description' => 'Vet care, boarding, grooming, training, and relocation - all in one place.',
                'actions' => [
                    ['label' => 'Book a Free Consultation', 'url' => route('book')],
                    ['label' => 'Explore Services', 'url' => route('services.index')],
                ],
                'imageSrc' => '/media/about/intro.jpg',
                'imageAlt' => 'Complete Veterinary & Pet Care in Abuja',
            ],
            'stats' => [
                ['value' => '500+', 'label' => 'Clients Served'],
                ['value' => '24/7', 'label' => '24/7 Concierge Vet Care'],
                ['value' => '15+', 'label' => 'Global Specialists'],
                ['value' => '100%', 'label' => '100% Protocol-Based Handling'],
            ],
            'philosophy' => [
                'eyebrow' => 'Our Philosophy', 'title' => 'Pet Care Standards in West Africa',
                'body' => [
                    "Founded to bridge the gap in dedicated pet services, Waggies has grown into Abuja's most comprehensive pet care centre. We believe thorough, consistent care matters.",
                    'Our facility combines proper medical equipment with comfortable, clean spaces. Whether it is a routine check-up, a grooming session, or international relocation, we handle every detail with care and precision.',
                    'Pet Care Should Never Be Reactive: We believe animals benefit from the same structure and planning as human healthcare - not rushed decisions, not inconsistent handling, but deliberate care built around long-term wellbeing.',
                ],
                'features' => [
                    ['icon' => 'values', 'title' => 'High Standards', 'description' => 'Climate-controlled suites & quality amenities.'],
                    ['icon' => 'global', 'title' => 'Global Mobility', 'description' => 'Experienced in straightforward international pet travel.'],
                    ['icon' => 'verified', 'title' => 'Expert Supervision', 'description' => 'Veterinary staff on site during all operating hours.'],
                    ['icon' => 'hours', 'title' => '24/7 Concierge Care', 'description' => 'We never sleep so they can.'],
                ],
            ],
            'mosaicImages' => [
                ['src' => '/media/about/veterinary-care.jpg', 'alt' => 'Veterinarian examining a dog in a clinic', 'offset' => true],
                ['src' => '/media/about/grooming.jpg', 'alt' => 'Dog being groomed at a spa'],
            ],
            'story' => [
                'title' => 'Built by Pet Lovers,<br />for Pet Owners',
                'paragraphs' => [
                    'Waggies was born from a personal experience  -  a pet owner in Abuja struggling to find a boarding facility that matched the standard of care they gave their own dog at home. What started as a small dog hotel has grown into Abuja\'s most comprehensive pet care centre.',
                    'Today, we offer boarding, grooming, vet care, training, and relocation services (including local transport)  -  all under one roof, all to the same consistent standard.',
                    'Every member of our team is an animal lover first. We believe the best pet care comes from genuine passion  -  not just process.',
                    'Waggies began with a simple but frustrating reality - pet owners in Abuja had to choose between basic boarding facilities or expensive, fragmented services with no continuity of care. The idea was not to “start a pet business.” It was to remove the inconsistency between grooming, boarding, and veterinary handling by putting everything under one controlled system. What exists today is not a collection of separate services - but a unified care environment designed to reduce the risks created by fragmentation.',
                ],
                'stats' => [['value' => '500+', 'label' => 'Happy Clients'], ['value' => '7', 'label' => 'Years in Business'], ['value' => '4.9', 'label' => 'Average Rating'], ['value' => '15+', 'label' => 'Team Members']],
            ],
            'standards' => [
                'eyebrow' => 'Operating Standards', 'title' => 'Our Standard of Handling<br/><span class="text-secondary italic">Across All Services</span>',
                'subtitle' => 'Whether it is boarding, grooming, vet care, training, or relocation - our decisions follow one rule: what is best for the animal comes first.',
                'features' => [
                    ['icon' => 'transport', 'title' => 'Supervised Pet-First Care', 'description' => 'Every service is designed around comfort, safety, and wellbeing  -  never convenience or speed.'],
                    ['icon' => 'process', 'title' => 'Consistency Across Services', 'description' => 'Boarding, grooming, and medical care follow the same documented handling standards and staff procedures.'],
                    ['icon' => 'security', 'title' => 'Controlled Safety Protocols', 'description' => 'Pets are separated by risk level, monitored during movement, and handled under strict safety procedures.'],
                    ['icon' => 'visibility', 'title' => 'Transparent Owner Communication', 'description' => 'Owners receive clear updates on condition, progress, and any incidents  -  with no hidden details.'],
                ],
            ],
            'teamHeading' => ['eyebrow' => 'Our Team', 'title' => 'Care shaped around your pet', 'intro' => 'Our current public team directory is being updated. Ask our team for the right point of contact for your pet’s care.'],
            'team' => [
                ['name' => 'Veterinary care', 'role' => 'Clinical support', 'responsibility' => 'Ask our team about current veterinary availability and the right next step for your pet.', 'icon' => 'veterinary-care'],
                ['name' => 'Boarding and grooming', 'role' => 'Pet care team', 'responsibility' => 'We will help you choose a calm, suitable routine for your pet’s stay or appointment.', 'icon' => 'boarding'],
                ['name' => 'Relocation and transport', 'role' => 'Client support', 'responsibility' => 'Share your route and timeline with our team so we can explain the requirements that apply.', 'icon' => 'transport'],
            ],
            'address' => $businessProfile->addressLine(),
            'cta' => ['heading' => 'Explore Our Pet Care Services', 'body' => 'From grooming to veterinary care and boarding, discover everything we offer to keep your pet healthy and happy.', 'label' => 'View Services'],
        ]);
    }
}
