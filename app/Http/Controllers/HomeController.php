<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class HomeController extends Controller
{
    public function __invoke(): View
    {
        $metadata = [
            'title' => 'Pet Boarding, Veterinary Care & Relocation in Abuja',
            'description' => 'Dog and cat boarding and veterinary care in Abuja. Pet import and export coordination for international journeys to or from Nigeria. Explore Waggies’ services.',
            'canonical' => route('home'),
            'ogTitle' => 'Waggies - Pet Care in Abuja',
            'ogDescription' => 'Waggies provides boarding, veterinary care, and dog and cat relocation for pets in Abuja, Nigeria.',
        ];

        $this->setPageHead($metadata, [
            Schema::webSite()
                ->name('Waggies')
                ->description($metadata['description'])
                ->url($metadata['canonical'])
                ->toArray(),
        ]);

        return view('pages.home', $metadata + [
            'navSection' => 'home',
            'homeHero' => [
                'eyebrow' => 'PET CARE IN ABUJA',
                'title' => 'Boarding, Veterinary Care & Pet Relocation',
                'description' => 'Boarding, veterinary care, and dog and cat relocation — all handled through a clear request and review process in Abuja.',
                'imageSrc' => '/media/home/hero.jpg',
                'imageAlt' => 'Happy dog sitting with their owner outdoors',
                'actions' => [
                    ['label' => 'Submit Booking Request', 'url' => route('book')],
                    ['label' => 'View Services', 'url' => route('services.index'), 'iconBefore' => 'pets'],
                ],
            ],
            'homeServiceCards' => [
                ['title' => 'Dog Boarding', 'description' => 'A structured stay shaped around your dog’s routine.', 'route' => 'services.boarding.species', 'params' => ['species' => 'dogs'], 'imageSrc' => '/media/services/boarding/card-dogs.jpg', 'imageAlt' => 'Happy dogs at Waggies', 'icon' => 'pets'],
                ['title' => 'Cat Boarding', 'description' => 'A dedicated boarding space where your cat can settle in.', 'route' => 'services.boarding.species', 'params' => ['species' => 'cats'], 'imageSrc' => '/media/services/boarding/card-cats.jpg', 'imageAlt' => 'Cat at Waggies boarding facility', 'icon' => 'cat'],
                ['title' => 'Veterinary Care', 'description' => 'Wellness and preventive veterinary care in Abuja.', 'route' => 'services.vet-care', 'imageSrc' => '/media/home/services-vet-care.jpg', 'imageAlt' => 'Veterinarian caring for a dog', 'icon' => 'veterinary-care'],
                ['title' => 'Pet Import to Nigeria', 'description' => 'Bringing your pet to Nigeria? We’ll check your route and quote the support available.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Taking your pet abroad? We’ll check your route and quote the support available.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
            ],
            'careStandardRows' => [
                ['title' => 'On-Site Overnight Team', 'description' => 'A Waggies staff member and veterinarian are on site overnight.'],
                ['title' => 'Air-Conditioned Boarding Areas', 'description' => 'The boarding facility has air conditioning.'],
                ['title' => 'Care Review', 'description' => 'Care instructions are reviewed before a boarding stay is confirmed.'],
            ],
            'homeTestimonials' => Testimonial::query()
                ->published()
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->limit(6)
                ->get()
                ->map(fn (Testimonial $testimonial): array => $testimonial->toHomeArray())
                ->all() ?: [[
                    'service' => 'Client stories',
                    'stars' => null,
                    'quote' => 'Verified client stories will appear here as Waggies pet parents share their experience.',
                    'initial' => 'W',
                    'name' => 'Waggies pet parents',
                    'subtitle' => 'Verified stories coming soon',
                ]],
        ]);
    }
}
