<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\JobOpening;
use App\Models\Testimonial;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class AboutPagesController extends Controller
{
    public function testimonials(): View
    {
        $page = $this->testimonialsPage();
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $items = Testimonial::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get()
            ->map(fn (Testimonial $testimonial): array => $testimonial->toPublicArray())
            ->all();
        $metadata = ['title' => 'Client Testimonials', 'description' => 'Read what pet owners across Abuja share about their Waggies boarding, grooming, vet care, training and relocation experience.', 'canonical' => route('about.testimonials'), 'ogTitle' => 'Client Testimonials - Waggies Pet Care Abuja', 'ogDescription' => 'Read what pet owners across Abuja share about their Waggies boarding, grooming, vet care, training and relocation experience.'];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.testimonials', $metadata + [
            'navSection' => 'about',
            ...$page,
            'items' => $items,
        ]);
    }

    public function gallery(): View
    {
        $page = $this->galleryPage();
        $images = GalleryItem::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (GalleryItem $item): array => $item->toPublicArray())
            ->filter(fn (array $image): bool => filled($image['src'] ?? null))
            ->all();
        $metadata = ['title' => 'Photo Gallery', 'description' => "Browse Waggies' boarding suites, grooming spa, veterinary clinic, training grounds, and happy guest photos.", 'canonical' => route('about.gallery'), 'ogTitle' => 'Photo Gallery - Waggies Pet Care Abuja', 'ogDescription' => "Browse Waggies' boarding suites, grooming spa, veterinary clinic, training grounds, and happy guest photos."];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.gallery', $metadata + [
            'navSection' => 'about',
            ...$page,
            'images' => $images,
            'categories' => collect($images)->pluck('category')->unique()->values()->all(),
        ]);
    }

    public function careers(): View
    {
        $page = $this->careersPage();
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $openRoles = JobOpening::query()->open()->orderBy('sort_order')->orderBy('title')->get();
        $hasOpenRoles = $openRoles->isNotEmpty();
        $metadata = [
            'title' => 'Careers at Waggies',
            'description' => $hasOpenRoles
                ? 'Explore current opportunities with the Waggies pet care team in Abuja.'
                : 'Learn about working at Waggies and check back when new pet care opportunities open.',
            'canonical' => route('about.careers'),
            'ogTitle' => 'Careers at Waggies',
            'ogDescription' => $hasOpenRoles
                ? 'Explore current opportunities with the Waggies pet care team in Abuja.'
                : 'There are no current openings at Waggies right now.',
        ];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.careers', $metadata + [
            'navSection' => 'about',
            ...$page,
            'openRoles' => $openRoles,
            'hasOpenRoles' => $hasOpenRoles,
        ]);
    }

    public function partnerships(): View
    {
        $page = $this->partnershipsPage();
        $metadata = ['title' => 'Partnerships', 'description' => 'Waggies partners with veterinary clinics, pet retailers, breeders, and corporate organisations who share our commitment to animal welfare.', 'canonical' => route('about.partnerships'), 'ogTitle' => 'Partnerships - Waggies Pet Care Abuja', 'ogDescription' => 'Waggies partners with veterinary clinics, pet retailers, breeders, and corporate organisations who share our commitment to animal welfare.'];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.partnerships', $metadata + [
            'navSection' => 'about',
            ...$page,
        ]);
    }

    private function testimonialsPage(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'What Clients Say',
                'eyebrowIcon' => 'reviews',
                'title' => 'Trusted by Pet Owners<br />Across Abuja',
                'description' => 'Read what clients share about their Waggies experience.',
                'imageSrc' => '/media/about/testimonials/hero.jpg',
                'imageAlt' => 'Dog in a boarding suite',
                'actions' => [
                    0 => [
                        'label' => 'Book a Stay',
                        'route' => 'services.boarding',
                    ],
                    1 => [
                        'label' => 'Contact Us',
                        'route' => 'contact',
                    ],
                ],
            ],
            'filters' => [
                0 => [
                    'value' => 'all',
                    'label' => 'All Services',
                ],
                1 => [
                    'value' => 'boarding-dogs',
                    'label' => 'Dog Boarding',
                ],
                2 => [
                    'value' => 'boarding-cats',
                    'label' => 'Cat Boarding',
                ],
                3 => [
                    'value' => 'boarding-exotic',
                    'label' => 'Exotic Pet Boarding',
                ],
                4 => [
                    'value' => 'grooming',
                    'label' => 'Grooming',
                ],
                5 => [
                    'value' => 'vet-care',
                    'label' => 'Veterinary Care',
                ],
                6 => [
                    'value' => 'training',
                    'label' => 'Training',
                ],
                7 => [
                    'value' => 'relocation-import',
                    'label' => 'Pet Import',
                ],
                8 => [
                    'value' => 'relocation-export',
                    'label' => 'Pet Export',
                ],
                9 => [
                    'value' => 'local-transport',
                    'label' => 'Local Transport',
                ],
                10 => [
                    'value' => 'general',
                    'label' => 'General',
                ],
            ],
            'bottomCta' => [
                'heading' => 'See for yourself',
                'body' => 'Trusted by pet owners across Abuja for boarding, grooming, vet care, training and relocation.',
                'ctaLabel' => 'Get in Touch',
                'ctaRoute' => 'contact',
            ],
        ];
    }

    private function galleryPage(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Photo Gallery',
                'title' => 'Take a Look Inside',
                'description' => 'Our facilities speak for themselves. Browse our boarding suites, grooming spa, veterinary clinic, training grounds, and the happy faces of our guests.',
            ],
        ];
    }

    private function careersPage(): array
    {
        return [
            'hero' => [
                'imageSrc' => '/media/about/careers/team.jpg',
                'imageAlt' => 'A diverse pet-care team',
                'eyebrow' => 'Careers',
                'title' => 'Join the Waggies Pack',
                'description' => 'Build a career doing what you love — caring for pets in Abuja\'s premier pet care facility.',
                'actions' => [
                    0 => [
                        'label' => 'View Open Roles',
                        'href' => '#open-roles',
                    ],
                ],
            ],
            'perksHeading' => [
                'eyebrow' => 'Why Waggies',
                'title' => 'Perks & Benefits',
                'subtitle' => 'We invest in our team because our people are the heart of everything we do.',
            ],
            'perks' => [
                0 => [
                    'icon' => 'training',
                    'title' => 'Paid Training',
                    'desc' => 'Fully funded professional development and certification for all team members.',
                ],
                1 => [
                    'icon' => 'favorite',
                    'title' => 'Do What You Love',
                    'desc' => 'Spend your working day doing something that genuinely matters to you.',
                ],
                2 => [
                    'icon' => 'team',
                    'title' => 'Great Team Culture',
                    'desc' => 'A tight-knit, supportive team that looks out for each other.',
                ],
                3 => [
                    'icon' => 'progress',
                    'title' => 'Room to Grow',
                    'desc' => 'We promote from within and invest in the long-term careers of our staff.',
                ],
            ],
            'openRolesHeading' => [
                'eyebrow' => 'Open Roles',
                'title' => 'Current Vacancies',
            ],
        ];
    }

    private function partnershipsPage(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Partnerships',
                'title' => 'Better Together',
                'description' => 'We partner with aligned organisations  -  vets, breeders, pet retailers, and businesses  -  who share our commitment to animal welfare and quality care.',
            ],
            'typesHeading' => [
                'eyebrow' => 'Partnership Types',
                'title' => 'How We Work<br/>With Partners',
                'subtitle' => 'Whether you\'re a veterinary clinic, a pet shop, or a corporate with pet-owning employees, we have a partnership model that works.',
            ],
            'types' => [
                0 => [
                    'icon' => 'medical',
                    'title' => 'Veterinary Clinics',
                    'desc' => 'Cross-referral partnerships with vet clinics across Abuja  -  ensuring continuity of care for shared patients.',
                ],
                1 => [
                    'icon' => 'partnership',
                    'title' => 'Pet Retailers',
                    'desc' => 'Partner with pet shops and suppliers to offer our clients exclusive product discounts and bundled services.',
                ],
                2 => [
                    'icon' => 'pets',
                    'title' => 'Breeders',
                    'desc' => 'We work with licensed breeders to provide early socialisation and health checks for new litters.',
                ],
                3 => [
                    'icon' => 'partnership',
                    'title' => 'Corporate Partners',
                    'desc' => 'Corporate employee benefit packages  -  discounted Waggies services for your pet-owning staff.',
                ],
                4 => [
                    'icon' => 'boarding',
                    'title' => 'Property Developers',
                    'desc' => 'Partner with residential developers to offer Waggies services to residents as a convenient amenity.',
                ],
                5 => [
                    'icon' => 'community',
                    'title' => 'NGOs & Rescue Orgs',
                    'desc' => 'We support animal rescue and welfare NGOs with discounted services and pro bono care where we can.',
                ],
            ],
            'cta' => [
                'heading' => 'Interested in partnering with us?',
                'body' => 'We\'d love to explore how we can work together. Get in touch to start the conversation.',
                'ctaLabel' => 'Get in Touch',
                'ctaRoute' => 'contact',
                'ctaIcon' => 'arrow-forward',
            ],
        ];
    }

    private function webPageSchema(array $metadata): array
    {
        return Schema::webPage()
            ->name($metadata['ogTitle'])
            ->description($metadata['description'])
            ->url($metadata['canonical'])
            ->toArray();
    }
}
