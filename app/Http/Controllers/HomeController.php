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
            'title' => 'Pet Boarding, Grooming & Vet Care in Abuja',
            'description' => 'Waggies provides boarding, grooming, vet care, training, relocation and local transport for pets in Abuja, Nigeria.',
            'canonical' => route('home'),
            'ogTitle' => 'Waggies - Pet Care in Abuja',
            'ogDescription' => 'Waggies provides boarding, grooming, vet care, training, relocation and local transport for pets in Abuja, Nigeria.',
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
                'description' => 'Boarding, grooming, vet care, training, relocation and local transport — all in one place, right here in Abuja.',
                'imageSrc' => '/media/editorial/photo-1587300003388-59208cc962cb.jpg',
                'imageAlt' => 'Happy dog sitting with their owner outdoors',
                'actions' => [
                    ['label' => 'Request a booking', 'route' => 'book'],
                    ['label' => 'View Services', 'route' => 'services.index', 'iconBefore' => 'pets'],
                ],
            ],
            'homeServiceCards' => [
                ['title' => 'Boarding', 'description' => 'Overnight stays in spacious, climate-controlled suites with 24/7 care.', 'route' => 'services.boarding', 'imageSrc' => '/media/editorial/photo-1544568100-847a948585b9.jpg', 'icon' => 'boarding'],
                ['title' => 'Grooming', 'description' => 'Breed-specific treatments, baths, and styling by experienced groomers.', 'route' => 'services.grooming', 'imageSrc' => '/media/editorial/photo-1516734212186-a967f81ad0d7.jpg', 'icon' => 'grooming'],
                ['title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and wellness check-ups.', 'route' => 'services.vet-care', 'imageSrc' => '/media/editorial/photo-1514888286974-6c03e2ca1dba.jpg', 'icon' => 'veterinary-care'],
                ['title' => 'Training', 'description' => 'Positive-reinforcement training programmes for puppies and adult dogs.', 'route' => 'services.training', 'imageSrc' => '/media/editorial/photo-1583511655857-d19b40a7a54e.jpg', 'icon' => 'training'],
                ['title' => 'Pet Relocation', 'description' => 'International pet moves with full documentation, import, export, and local transport.', 'route' => 'services.relocation', 'imageSrc' => '/media/editorial/photo-1436491865332-7a61a109cc05.jpg', 'icon' => 'airport-departure'],
            ],
            'careStandardRows' => [
                ['title' => '24/7 Supervision', 'description' => 'Your pets are monitored around the clock, never left alone.'],
                ['title' => 'Climate-Controlled Suites', 'description' => 'Spacious suites with orthopedic bedding and temperature control.'],
                ['title' => 'Daily Updates', 'description' => 'Receive photos and updates on your pet every day.'],
                ['title' => 'Individual Care', 'description' => 'Every suite, meal, and exercise routine is structured around individual care requirements.'],
            ],
            'homeTestimonials' => Testimonial::query()
                ->published()
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->limit(3)
                ->get()
                ->map(fn (Testimonial $testimonial): array => $testimonial->toHomeArray())
                ->all(),
        ]);
    }
}
