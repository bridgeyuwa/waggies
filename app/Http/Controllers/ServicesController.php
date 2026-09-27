<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\BookingPricingCatalog;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class ServicesController extends Controller
{
    public function index(): View
    {
        $metadata = [
            'title' => 'Pet Care Services in Abuja - Waggies',
            'description' => 'Waggies offers boarding, veterinary care, and dog and cat relocation in Abuja, Nigeria.',
            'canonical' => route('services.index'),
            'ogTitle' => 'Waggies Pet Care Services Abuja',
            'ogDescription' => 'Boarding, veterinary care, and dog and cat relocation with manual review at every important step.',
        ];

        $this->setPageHead($metadata, [$this->serviceSchema('Waggies Pet Care Services', $metadata)]);

        return view('pages.services.index', $metadata + [
            'navSection' => 'services',
            'hero' => [
                'eyebrow' => 'CARE THAT STARTS WITH A REVIEW',
                'title' => 'The Right Care<br><span class="text-secondary italic">For Every Stage</span>',
                'description' => 'Choose the service your pet needs, send the essentials, and let our team confirm availability, suitability, and the final arrangements with you.',
                'imageSrc' => '/media/services/boarding/hero-dogs.jpg',
                'imageAlt' => 'Dog resting during a Waggies boarding stay',
                'actions' => [
                    ['label' => 'Submit Booking Request', 'url' => route('book'), 'icon' => 'arrow-forward'],
                ],
            ],
            'cards' => [
                ['title' => 'Boarding', 'description' => 'Overnight boarding for dogs and cats, priced per pet per night.', 'route' => 'services.boarding', 'imageSrc' => '/media/home/services-boarding.jpg', 'imageAlt' => 'Dog relaxing during boarding', 'icon' => 'boarding'],
                ['title' => 'Veterinary Care', 'description' => 'Wellness consultations, examinations, vaccination requests, and microchipping.', 'route' => 'services.vet-care', 'imageSrc' => '/media/home/services-vet-care.jpg', 'imageAlt' => 'Veterinary care at Waggies', 'icon' => 'veterinary-care'],
                ['title' => 'Relocation', 'description' => 'Custom-quoted import and export coordination for dogs and cats.', 'route' => 'services.relocation', 'imageSrc' => '/media/home/services-relocation.jpg', 'imageAlt' => 'Pet travel preparation', 'icon' => 'relocation'],
            ],
            'faqs' => $this->publishedFaqs('services'),
        ]);
    }

    public function boarding(): View
    {
        $metadata = [
            'title' => 'Pet Boarding in Abuja - Waggies',
            'description' => 'Request overnight boarding for your dog or cat in Abuja. Boarding is priced per pet per night and confirmed manually.',
            'canonical' => route('services.boarding'),
            'ogTitle' => 'Dog and Cat Boarding in Abuja - Waggies',
            'ogDescription' => 'Individual boarding care for dogs and cats, with direct size guidance for dogs and manual review before confirmation.',
        ];

        $this->setPageHead($metadata, [$this->serviceSchema('Pet Boarding', $metadata)]);

        return view('pages.services.boarding.index', $metadata + [
            'navSection' => 'services',
            'hero' => [
                'eyebrow' => 'BOARDING FOR DOGS & CATS',
                'title' => 'A Calm Stay,<br><span class="text-secondary italic">Planned Around Your Pet</span>',
                'description' => 'Boarding is one core service: one pet, one enclosure, one nightly rate path, and a staff review before anything is confirmed.',
                'imageSrc' => '/media/services/boarding/hero-dogs.jpg',
                'imageAlt' => 'Dog resting during a boarding stay',
                'actions' => [
                    ['label' => 'Submit Booking Request', 'url' => route('book', ['service' => 'boarding']), 'icon' => 'arrow-forward'],
                    ['label' => 'Boarding policy', 'url' => route('boarding-policy'), 'variant' => 'secondary'],
                ],
            ],
            'inclusions' => config('waggies_pricing.services.boarding.inclusions', []),
            'notes' => [
                config('waggies_pricing.services.boarding.food_note'),
                config('waggies_pricing.services.boarding.special_care_note'),
                'Long stays are welcome to request, subject to manual availability review. The checkout date is not another night unless the pet remains past the agreed cutoff.',
            ],
            'faqs' => $this->publishedFaqs('boarding'),
        ]);
    }

    public function boardingSpecies(string $species): View|Response
    {
        abort_unless(in_array($species, ['dogs', 'cats'], true), 404);

        $variant = $species;
        $catalog = app(BookingPricingCatalog::class);
        $variantDefinition = $catalog->variants('boarding')[$variant] ?? [];
        $metadata = [
            'title' => ucfirst($species).' Boarding in Abuja - Waggies',
            'description' => ucfirst($species).' boarding requests in Abuja with individual enclosures, clear care expectations, and manual confirmation.',
            'canonical' => route('services.boarding.species', ['species' => $species]),
            'ogTitle' => ucfirst($species).' Boarding - Waggies',
            'ogDescription' => 'Request '.strtolower($species).' boarding with per-pet, per-night guidance and staff review.',
        ];

        $this->setPageHead($metadata, [$this->serviceSchema(ucfirst($species).' Boarding', $metadata)]);

        return view('pages.services.boarding.species', $metadata + [
            'navSection' => 'services',
            'species' => $species,
            'variant' => $variant,
            'variantDefinition' => $variantDefinition,
            'sizeOptions' => $catalog->sizeOptions('boarding', $variant),
            'sizeRates' => $catalog->sizeRates('boarding', $variant),
            'siblings' => [
                'dogs' => ['label' => 'Dog boarding', 'image' => '/media/services/boarding/hero-dogs.jpg', 'description' => 'Direct size guidance for dogs.'],
                'cats' => ['label' => 'Cat boarding', 'image' => '/media/services/boarding/hero-cats.jpg', 'description' => 'One nightly request path for cats.'],
            ],
            'faqs' => $this->publishedFaqs('boarding', $species),
        ]);
    }

    public function vetCare(): View
    {
        $metadata = [
            'title' => 'Veterinary Care in Abuja - Waggies',
            'description' => 'Request wellness consultations, comprehensive examinations, vaccination review, or standalone microchipping for dogs and cats in Abuja.',
            'canonical' => route('services.vet-care'),
            'ogTitle' => 'Veterinary Care in Abuja - Waggies',
            'ogDescription' => 'Veterinary requests are reviewed by staff before the appointment and final costs are confirmed.',
        ];

        $catalog = app(BookingPricingCatalog::class);
        $this->setPageHead($metadata, [$this->serviceSchema('Veterinary Care', $metadata)]);

        return view('pages.services.detail', $metadata + [
            'navSection' => 'services',
            'service' => 'vet-care',
            'page' => [
                'eyebrow' => 'VETERINARY CARE',
                'title' => 'Veterinary Care<br><span class="text-secondary italic">With a Proper Review</span>',
                'description' => 'Choose the type of veterinary help you are requesting. The veterinary team reviews the request, confirms suitability and timing, and provides any final product or treatment costs.',
                'hero' => ['description' => 'Waggies accepts veterinary care requests for dogs and cats, including routine care, examinations, vaccination requests, and standalone microchipping.', 'imageSrc' => '/media/services/vet-care/hero.jpg', 'imageAlt' => 'Veterinary consultation for a pet'],
                'features' => [
                    'Wellness consultation',
                    'Comprehensive examination',
                    'Vaccination request — vaccine selection and costs are confirmed after veterinary review',
                    'Microchip implantation as a standalone veterinary service',
                ],
                'benefits' => [
                    'Clinical review before confirmation',
                    'Dogs and cats only for this service catalogue',
                    'No generic vaccination product or price is presented before the vaccine list is curated',
                    'Microchip checks and implantation can also support relocation where applicable',
                ],
                'options' => collect($catalog->variants('vet-care', availableOnly: true, channel: 'pricing'))->map(fn (array $option, string $key): array => [
                    'key' => $key,
                    'label' => $option['label'] ?? $key,
                    'category' => $option['category'] ?? null,
                    'description' => $option['description'] ?? null,
                    'price' => ($option['type'] ?? 'quote') === 'quote' ? 'Request review' : '₦'.number_format((int) ($option['amount'] ?? 0)),
                ])->values()->all(),
                'ctaText' => 'Submit Veterinary Request',
                'ctaRoute' => 'book',
                'ctaParams' => ['service' => 'vet-care'],
            ],
            'faqs' => $this->publishedFaqs('vet-care'),
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function publishedFaqs(string $category, ?string $subcategory = null): array
    {
        return Faq::query()
            ->published()
            ->where('category', $category)
            ->when($subcategory !== null, fn ($query) => $query->where('subcategory', $subcategory))
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, mixed>
     */
    private function serviceSchema(string $name, array $metadata): array
    {
        return Schema::service()
            ->name($name)
            ->description((string) $metadata['description'])
            ->url((string) $metadata['canonical'])
            ->toArray();
    }
}
