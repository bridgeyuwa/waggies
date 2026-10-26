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
            ->whereIn('service', array_keys(Testimonial::serviceOptions()))
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get()
            ->map(fn (Testimonial $testimonial): array => $testimonial->toPublicArray())
            ->all();
        $metadata = ['title' => 'Waggies Client Testimonials | Pet Care in Abuja', 'description' => 'Read first-hand stories from pet owners who have shared their experience with Waggies in Abuja.', 'canonical' => route('about.testimonials'), 'ogTitle' => 'Waggies Client Testimonials | Pet Care in Abuja', 'ogDescription' => 'Read first-hand stories from pet owners who have shared their experience with Waggies in Abuja.'];
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
            ->whereIn('category', array_keys(GalleryItem::categoryOptions()))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (GalleryItem $item): array => $item->toPublicArray())
            ->filter(fn (array $image): bool => filled($image['src'] ?? null))
            ->all();
        $metadata = ['title' => 'Inside Waggies | Pet Care in Abuja', 'description' => 'Explore the Waggies gallery for a closer look at pet care in Abuja.', 'canonical' => route('about.gallery'), 'ogTitle' => 'Inside Waggies | Pet Care in Abuja', 'ogDescription' => 'Explore the Waggies gallery for a closer look at pet care in Abuja.'];
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
        $metadata = ['title' => 'Pet Care Partnerships in Abuja | Waggies', 'description' => 'Explore potential collaborations with Waggies in Abuja. Veterinary clinics, breeders, pet retailers, employers, property developers, and animal-welfare organisations can contact us to discuss fit and scope.', 'canonical' => route('about.partnerships'), 'ogTitle' => 'Pet Care Partnerships in Abuja | Waggies', 'ogDescription' => 'Explore potential collaborations with Waggies in Abuja. Veterinary clinics, breeders, pet retailers, employers, property developers, and animal-welfare organisations can contact us to discuss fit and scope.'];
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
                'title' => 'What Waggies Clients Say',
                'description' => 'Read client feedback on Waggies dog and cat boarding, veterinary care, and route-specific pet import and export through Nnamdi Azikiwe International Airport in Abuja.',
                'imageSrc' => '/media/about/testimonials/hero.jpg',
                'imageAlt' => 'Dog at Waggies boarding facility',
                'actions' => [
                    0 => [
                        'label' => 'Explore Services',
                        'route' => 'services.index',
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
                    'value' => 'vet-care',
                    'label' => 'Veterinary Care',
                ],
                4 => [
                    'value' => 'relocation-import',
                    'label' => 'Pet Import',
                ],
                5 => [
                    'value' => 'relocation-export',
                    'label' => 'Pet Export',
                ],
                6 => [
                    'value' => 'general',
                    'label' => 'General',
                ],
            ],
            'bottomCta' => [
                'heading' => 'See for yourself',
                'body' => 'Trusted by pet owners across Abuja for boarding, veterinary care, and relocation.',
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
                'title' => 'A Look Inside Waggies',
                'description' => 'Take a closer look at Waggies and the work we do with pets.',
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
                'title' => 'Explore Partnership Opportunities',
                'description' => 'Waggies is open to conversations with veterinary clinics, breeders, pet retailers, employers, property developers, and animal-welfare organisations in Abuja. Each collaboration is discussed individually; scope and terms depend on agreement.',
            ],
            'typesHeading' => [
                'eyebrow' => 'Partnership Types',
                'title' => 'How We Work<br/>With Partners',
                'subtitle' => 'These are potential areas for collaboration. We discuss scope and terms with each organisation individually.',
            ],
            'types' => [
                0 => [
                    'icon' => 'medical',
                    'title' => 'Veterinary Clinics',
                    'desc' => 'Discuss possible referrals or collaboration with clinics in Abuja. Any arrangement depends on agreed scope and responsibilities.',
                ],
                1 => [
                    'icon' => 'partnership',
                    'title' => 'Pet Retailers',
                    'desc' => 'Discuss ways to connect pet owners with relevant products or Waggies services. Discounts or bundles apply only if agreed in advance.',
                ],
                2 => [
                    'icon' => 'pets',
                    'title' => 'Breeders',
                    'desc' => 'Discuss responsible pet-care referrals and support that fits Waggies\' confirmed veterinary scope.',
                ],
                3 => [
                    'icon' => 'partnership',
                    'title' => 'Corporate Partners',
                    'desc' => 'Discuss possible pet-care information or employee benefit options; availability and terms depend on agreement.',
                ],
                4 => [
                    'icon' => 'boarding',
                    'title' => 'Property Developers',
                    'desc' => 'Discuss ways for residents to learn about Waggies\' services. Service availability depends on location and confirmation.',
                ],
                5 => [
                    'icon' => 'community',
                    'title' => 'NGOs & Rescue Orgs',
                    'desc' => 'Discuss possible animal-welfare support, subject to capacity and agreed scope.',
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
