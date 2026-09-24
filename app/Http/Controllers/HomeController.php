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
                'imageSrc' => '/media/home/hero.jpg',
                'imageAlt' => 'Happy dog sitting with their owner outdoors',
                'actions' => [
                    ['label' => 'Request a booking', 'url' => route('book')],
                    ['label' => 'View Services', 'url' => route('services.index'), 'iconBefore' => 'pets'],
                ],
            ],
            'homeServiceCards' => [
                ['title' => 'Boarding', 'description' => 'Overnight stays in spacious, climate-controlled suites with 24/7 care.', 'route' => 'services.boarding', 'imageSrc' => '/media/home/services-boarding.jpg', 'icon' => 'boarding'],
                ['title' => 'Grooming', 'description' => 'Breed-specific treatments, baths, and styling by experienced groomers.', 'route' => 'services.grooming', 'imageSrc' => '/media/home/services-grooming.jpg', 'icon' => 'grooming'],
                ['title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and wellness check-ups.', 'route' => 'services.vet-care', 'imageSrc' => '/media/home/services-vet-care.jpg', 'icon' => 'veterinary-care'],
                ['title' => 'Training', 'description' => 'Positive-reinforcement training programmes for puppies and adult dogs.', 'route' => 'services.training', 'imageSrc' => '/media/home/services-training.jpg', 'icon' => 'training'],
                ['title' => 'Pet Relocation', 'description' => 'International pet moves with full documentation, import, export, and local transport.', 'route' => 'services.relocation', 'imageSrc' => '/media/home/services-relocation.jpg', 'icon' => 'airport-departure'],
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
