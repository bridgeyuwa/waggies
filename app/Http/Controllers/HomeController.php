<?php

namespace App\Http\Controllers;

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
                'imageSrc' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&h=600&fit=crop',
                'imageAlt' => 'Happy dog sitting with their owner outdoors',
                'actions' => [
                    ['label' => 'Book Now', 'route' => 'contact', 'params' => ['intent' => 'booking']],
                    ['label' => 'View Services', 'route' => 'services.index', 'iconBefore' => 'pets'],
                ],
            ],
            'homeServiceCards' => [
                ['title' => 'Boarding', 'description' => 'Overnight stays in spacious, climate-controlled suites with 24/7 care.', 'route' => 'services.boarding', 'imageSrc' => 'https://images.unsplash.com/photo-1544568100-847a948585b9?w=600&auto=format&fit=crop&q=60', 'icon' => 'boarding'],
                ['title' => 'Grooming', 'description' => 'Breed-specific treatments, baths, and styling by experienced groomers.', 'route' => 'services.grooming', 'imageSrc' => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=600&h=400&fit=crop&q=80', 'icon' => 'grooming'],
                ['title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and wellness check-ups.', 'route' => 'services.vet-care', 'imageSrc' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=600&h=400&fit=crop&q=80', 'icon' => 'veterinary-care'],
                ['title' => 'Training', 'description' => 'Positive-reinforcement training programmes for puppies and adult dogs.', 'route' => 'services.training', 'imageSrc' => 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=600&h=400&fit=crop&q=80', 'icon' => 'training'],
                ['title' => 'Pet Relocation', 'description' => 'International pet moves with full documentation, import, export, and local transport.', 'route' => 'services.relocation', 'imageSrc' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=600&h=400&fit=crop&q=80', 'icon' => 'airport-departure'],
            ],
            'careStandardRows' => [
                ['title' => '24/7 Supervision', 'description' => 'Your pets are monitored around the clock, never left alone.'],
                ['title' => 'Climate-Controlled Suites', 'description' => 'Spacious suites with orthopedic bedding and temperature control.'],
                ['title' => 'Daily Updates', 'description' => 'Receive photos and updates on your pet every day.'],
                ['title' => 'Individual Care', 'description' => 'Every suite, meal, and exercise routine is structured around individual care requirements.'],
            ],
            'homeTestimonials' => [
                ['service' => 'Dog Boarding', 'quote' => 'Waggies is the only place I would trust with my dog. The daily updates give me real peace of mind.', 'initial' => 'A', 'name' => 'Adaeze O.', 'subtitle' => 'Dog owner, Maitama'],
                ['service' => 'Cat Grooming', 'quote' => 'The grooming team transformed my Persian cat. She looked like she had come straight from a pet show.', 'initial' => 'E', 'name' => 'Emeka N.', 'subtitle' => 'Cat owner, Wuse II'],
                ['service' => 'Pet Relocation', 'quote' => 'Our relocation from London was well-organised. Every document was sorted, zero stress on our end.', 'initial' => 'F', 'name' => 'Fatima M.', 'subtitle' => 'Relocation client, Asokoro'],
            ],
        ]);
    }
}
