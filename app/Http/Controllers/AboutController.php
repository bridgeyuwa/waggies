<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class AboutController extends Controller
{
    public function __invoke(): View
    {
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
                'primaryAction' => ['label' => 'Book a Free Consultation', 'href' => route('contact')],
                'secondaryAction' => ['label' => 'Explore Services', 'href' => route('services.index')],
                'imageSrc' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=1200&h=600&fit=crop&q=80',
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
                ['src' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?w=600&h=400&fit=crop&q=80', 'alt' => 'Veterinarian examining a dog in a clinic', 'offset' => true],
                ['src' => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=600&h=400&fit=crop&q=80', 'alt' => 'Dog being groomed at a spa'],
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
            'teamHeading' => ['eyebrow' => 'Our Team', 'title' => 'Meet the Waggies Team', 'intro' => 'The people behind Waggies who keep your pets healthy, safe, and happy.'],
            'team' => [
                ['name' => 'Dr. Nguhemen Doose Yuwa', 'role' => 'DVM, Head Veterinarian and Director', 'responsibility' => 'Leads all veterinary services and oversees clinical operations at Waggies.', 'imageSrc' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Dr. Nguhemen Doose Yuwa', 'verified' => true],
                ['name' => 'Aifuwa Bob Osaze', 'role' => 'Director', 'responsibility' => 'Oversees business strategy, partnerships, and overall operations at Waggies.', 'imageSrc' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Aifuwa Bob Osaze', 'verified' => true],
                ['name' => 'Oghomwen Oyuki Obaseki', 'role' => 'Secretary', 'responsibility' => 'Manages administrative operations, scheduling, and client communications.', 'imageSrc' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Oghomwen Oyuki Obaseki', 'verified' => true],
                ['name' => 'Chukwuma Eze', 'role' => 'Veterinary Support', 'imageSrc' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Chukwuma Eze', 'placeholder' => true],
                ['name' => 'Amina Bello', 'role' => 'Lead Groomer', 'imageSrc' => 'https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Amina Bello', 'placeholder' => true],
                ['name' => 'Dayo Adekunle', 'role' => 'Boarding Manager', 'imageSrc' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Dayo Adekunle', 'placeholder' => true],
                ['name' => 'Kemi Ogunleye', 'role' => 'Pet Trainer', 'imageSrc' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Kemi Ogunleye', 'placeholder' => true],
                ['name' => 'Suleiman Ahmadu', 'role' => 'Relocation & Transport Coordinator', 'imageSrc' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=400&h=400&fit=crop&crop=face&q=80', 'imageAlt' => 'Portrait of Suleiman Ahmadu', 'placeholder' => true],
            ],
            'address' => 'Life Camp, Efab City Estate, 65 1st Ave, Abuja 900108, Federal Capital Territory, Nigeria',
            'cta' => ['heading' => 'Explore Our Pet Care Services', 'body' => 'From grooming to veterinary care and boarding, discover everything we offer to keep your pet healthy and happy.', 'label' => 'View Services'],
        ]);
    }
}
