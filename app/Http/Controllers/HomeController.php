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
            'description' => 'Waggies provides boarding, veterinary care, and dog and cat relocation services in Abuja, Nigeria.',
            'canonical' => route('home'),
            'ogTitle' => 'Waggies - Pet Care in Abuja',
            'ogDescription' => 'Waggies provides boarding, veterinary care, and dog and cat relocation services in Abuja, Nigeria.',
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
                'eyebrow' => "ABUJA'S TRUSTED PET CARE",
                'title' => "Your Pet's Home Away From Home",
                'description' => 'Boarding, veterinary care, and dog and cat relocation — all in one place, right here in Abuja.',
                'imageSrc' => '/media/home/hero.jpg',
                'imageAlt' => 'Happy dog sitting with their owner outdoors',
                'actions' => [
                    ['label' => 'Submit Booking Request', 'url' => route('book')],
                    ['label' => 'View Services', 'url' => route('services.index'), 'iconBefore' => 'pets'],
                ],
            ],
            'homeServiceCards' => [
                ['title' => 'Boarding', 'description' => 'Overnight boarding for dogs and cats with clear care expectations and manual confirmation.', 'route' => 'services.boarding', 'imageSrc' => '/media/home/services-boarding.jpg', 'icon' => 'boarding'],
                ['title' => 'Veterinary Care', 'description' => 'Wellness consultations, examinations, vaccination requests, and microchipping.', 'route' => 'services.vet-care', 'imageSrc' => '/media/home/services-vet-care.jpg', 'icon' => 'veterinary-care'],
                ['title' => 'Pet Relocation', 'description' => 'Custom-quoted import and export coordination for dogs and cats.', 'route' => 'services.relocation', 'imageSrc' => '/media/home/services-relocation.jpg', 'icon' => 'airport-departure'],
            ],
            'careStandardRows' => [
                ['title' => 'Manual review', 'description' => 'Availability, suitability, and final details are reviewed before confirmation.'],
                ['title' => 'Individual enclosures', 'description' => 'Every boarded pet receives its own enclosure.'],
                ['title' => 'Clear inclusions', 'description' => 'Boarding includes the core care essentials and owner-supplied feeding instructions.'],
                ['title' => 'Care coordination', 'description' => 'Veterinary and relocation requests are coordinated with the relevant providers.'],
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
