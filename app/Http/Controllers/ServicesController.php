<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\BookingPricingCatalog;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class ServicesController extends Controller
{
    public function index(): View
    {
        $comparison = $this->serviceComparison();
        $metadata = ['title' => 'Pet Boarding, Vet Care & Relocation in Abuja | Waggies', 'description' => 'Dog and cat boarding and veterinary care in Abuja. Pet import and export coordination for international journeys to or from Nigeria. Explore Waggies’ services.', 'canonical' => route('services.index'), 'ogTitle' => 'Pet Boarding, Vet Care & Relocation in Abuja | Waggies', 'ogDescription' => 'Dog and cat boarding and veterinary care in Abuja. Pet import and export coordination for international journeys to or from Nigeria. Explore Waggies’ services.'];
        $this->setPageHead($metadata, [Schema::webPage()->name('Pet Boarding, Vet Care & Relocation in Abuja | Waggies')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.services.index', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/services/hero.jpg', 'imageAlt' => 'Dog outdoors', 'eyebrow' => 'Everything Your Pet Needs', 'title' => 'Pet Boarding,<br/>Veterinary Care & Pet Relocation', 'description' => 'Explore dog boarding, cat boarding, veterinary care, pet import, and pet export. Boarding and veterinary care are in Abuja; import and export requests are reviewed and quoted for each route.', 'actions' => [['label' => 'Explore Services', 'url' => route('services.index').'#services']]],
            'cards' => [
                ['title' => 'Dog Boarding', 'description' => 'Owner-supplied food, routine cleaning, and welfare checks.', 'route' => 'services.boarding.species', 'params' => ['species' => 'dogs'], 'imageSrc' => '/media/services/boarding/card-dogs.jpg', 'imageAlt' => 'Happy dogs at Waggies', 'icon' => 'pets'],
                ['title' => 'Cat Boarding', 'description' => 'Owner-supplied food, routine cleaning, water, and welfare checks.', 'route' => 'services.boarding.species', 'params' => ['species' => 'cats'], 'imageSrc' => '/media/services/boarding/card-cats.jpg', 'imageAlt' => 'Cat at Waggies boarding facility', 'icon' => 'cat'],
                ['title' => 'Veterinary Care', 'description' => 'Wellness consultations, comprehensive examinations, vaccination requests, and microchipping.', 'route' => 'services.vet-care', 'imageSrc' => '/media/services/vet-care/hero.jpg', 'imageAlt' => 'Veterinarian with dog', 'icon' => 'veterinary-care'],
                ['title' => 'Pet Import to Nigeria', 'description' => 'Import permits, veterinary and health documents, customs coordination, and Abuja airport pickup, confirmed for your route and quote.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Export permits, veterinary documents, crate planning, airline booking, and departure airport transport, confirmed for your route and quote.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
            ],
            'band' => [
                'eyebrow' => 'The Waggies Standard',
                'title' => 'Why Our Care System<br/><span class="text-secondary italic">Works So Well</span>',
                'subtitle' => 'Every pet is handled through structured care protocols designed to ensure safety, comfort, and emotional wellbeing.',
                'stats' => [
                    ['icon' => 'veterinary-care', 'title' => 'Veterinary Care', 'subtitle' => 'Health-first handling'],
                    ['icon' => 'boarding', 'title' => 'Pet Boarding', 'subtitle' => 'Dogs and cats in Abuja'],
                    ['icon' => 'airport-departure', 'title' => 'Import & Export', 'subtitle' => 'Custom relocation review'],
                    ['icon' => 'verified', 'title' => 'Manual Confirmation', 'subtitle' => 'Clear next steps'],
                ],
                'features' => [
                    ['icon' => 'process', 'title' => 'Clear Request Process', 'description' => 'We review each service and pet detail before confirmation.'],
                    ['icon' => 'boarding', 'title' => 'Routine Boarding Care', 'description' => 'Water, owner-supplied food, cleaning, and basic welfare checks.'],
                    ['icon' => 'verified', 'title' => 'Manual Final Pricing', 'description' => 'Indicative figures never replace the staff-entered quotation.'],
                ],
            ],
            'comparisonServices' => $comparison['services'], 'comparisonFeatures' => $comparison['features'],
            'faqs' => $this->serviceIndexFaqs(),
        ]);
    }

    public function boarding(): View
    {
        $page = $this->boardingPages()['index'];
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $metadata = ['title' => 'Pet Boarding for Dogs and Cats in Abuja | Waggies', 'description' => 'See dog and cat boarding options in Abuja, with care details for each. Request a stay for your preferred dates.', 'canonical' => route('services.boarding'), 'ogTitle' => 'Pet Boarding for Dogs and Cats in Abuja | Waggies', 'ogDescription' => 'Request boarding for your dog or cat at Waggies in Abuja. Prices are per pet per night; staff review each request and confirm availability before the stay.'];
        $this->setPageHead($metadata, [$this->serviceSchema('Dog and Cat Boarding in Abuja', $metadata)]);

        return view('pages.services.boarding.index', $metadata + [
            'navSection' => 'services', 'page' => $page,
            'faqs' => $this->publishedFaqs('boarding', onlyGeneral: true),
        ]);
    }

    public function boardingSpecies(string $species): View
    {
        $boardingPages = $this->boardingPages();
        abort_unless(array_key_exists($species, $boardingPages['species']), 404);
        $pricingCatalog = app(BookingPricingCatalog::class);
        $fixedNightlyRate = $pricingCatalog->variants('boarding')[$species]['nightly_rate'] ?? null;
        $page = $boardingPages['species'][$species];
        if ($species === 'cats' && is_numeric($fixedNightlyRate)) {
            $formattedCatRate = '₦'.number_format((int) $fixedNightlyRate);
            $page['stats'][2] = [
                'value' => $formattedCatRate,
                'label' => 'Per Pet / Night',
            ];
            $page['pricingHeading'] = [
                'eyebrow' => 'Cat Boarding Pricing',
                'title' => 'One Fixed Nightly Rate',
                'subtitle' => 'A simple per-cat nightly rate. Availability and special-care needs are confirmed during review.',
            ];
        }
        $page['hero']['imageSrc'] = "/media/services/boarding/{$species}/hero.jpg?v=2";
        $page['daily']['images'] = array_map(
            fn (int $index): string => "/media/services/boarding/{$species}/daily-".str_pad((string) $index, 2, '0', STR_PAD_LEFT).'.jpg?v=2',
            [1, 2, 3, 4],
        );
        if ($species === 'cats') {
            $page['feline']['image'] = '/media/services/boarding/cats/feline.jpg';
        }
        if ($species === 'dogs') {
            $page['canine']['image'] = '/media/services/boarding/dogs/canine.jpg';
        }
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $faqs = $this->publishedFaqs('boarding', $species);
        $metadata = ['title' => ['dogs' => 'Dog Boarding in Abuja | Waggies', 'cats' => 'Cat Boarding in Abuja | Waggies'][$species], 'description' => $page['description'], 'canonical' => route('services.boarding.species', ['species' => $species]), 'ogTitle' => $page['title'].' Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($page['title'].' Abuja - Waggies', $metadata)]);

        return view('pages.services.boarding.species', $metadata + [
            'navSection' => 'services', 'species' => $species, 'page' => $page,
            'serviceKey' => 'boarding-'.$species,
            'pricing' => $pricingCatalog->sizeRates('boarding', $species),
            'fixedNightlyRate' => $fixedNightlyRate,
            'faqs' => $faqs,
        ]);
    }

    public function vetCare(): View
    {
        return $this->serviceDetail('vet-care');
    }

    private function serviceDetail(string $service): View
    {
        $page = $this->withCanonicalPackagePricing($service, $this->serviceDetails()[$service]);
        $faqs = $this->publishedFaqs($service);
        $metadata = ['title' => $page['meta']['title'].' | Waggies', 'description' => $page['meta']['description'], 'canonical' => route("services.{$service}"), 'ogTitle' => $page['meta']['title'].' | Waggies', 'ogDescription' => $page['meta']['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($metadata['ogTitle'], $metadata)]);

        return view('pages.services.detail', $metadata + [
            'navSection' => 'services', 'service' => $service, 'page' => $page, 'faqs' => $faqs,
        ]);
    }

    private function boardingPages(): array
    {
        return [
            'index' => [
                'title' => 'Pet Boarding',
                'description' => 'Pet boarding in Abuja for dogs and cats, priced per pet per night and reviewed before confirmation.',
                'hero' => [
                    'imageSrc' => '/media/services/boarding/hero.jpg',
                    'imageAlt' => 'Happy dog outdoors',
                    'eyebrow' => 'Pet Boarding in Abuja',
                    'eyebrowIcon' => 'pets',
                    'title' => 'Dog and Cat Boarding in Abuja',
                    'description' => 'Each pet has an assigned carrier for resting. Stays include water, routine cleaning, and basic welfare checks; our team follows your care instructions.',
                    'actions' => [
                        0 => [
                            'label' => 'Request boarding',
                            'route' => 'book',
                            'params' => [
                                'service' => 'boarding',
                            ],
                            'icon' => 'payments',
                        ],
                    ],
                ],
                'cards' => [
                    0 => [
                        'title' => 'Dog Boarding',
                        'description' => 'Owner-supplied food, routine cleaning, and welfare checks.',
                        'route' => 'services.boarding.species',
                        'params' => [
                            'species' => 'dogs',
                        ],
                        'imageSrc' => '/media/services/boarding/card-dogs.jpg',
                        'imageAlt' => 'Happy dogs at Waggies',
                        'icon' => 'pets',
                    ],
                    1 => [
                        'title' => 'Cat Boarding',
                        'description' => 'Owner-supplied food, routine cleaning, water, and welfare checks.',
                        'route' => 'services.boarding.species',
                        'params' => [
                            'species' => 'cats',
                        ],
                        'imageSrc' => '/media/services/boarding/card-cats.jpg',
                        'imageAlt' => 'Cat at Waggies boarding facility',
                        'icon' => 'cat',
                    ],
                ],
                'included' => [
                    ['icon' => 'boarding', 'title' => 'Resting Arrangement', 'desc' => 'Each pet has an assigned carrier for resting.'],
                    ['icon' => 'water', 'title' => 'Water', 'desc' => 'Fresh water is part of routine boarding care.'],
                    ['icon' => 'nutrition', 'title' => 'Owner-Supplied Food', 'desc' => 'We follow the food and feeding instructions you provide.'],
                    ['icon' => 'verified', 'title' => 'Basic Welfare Checks', 'desc' => 'Routine cleaning and basic welfare checks are included.'],
                    ['icon' => 'schedule', 'title' => 'Daily Drop-Off & Pick-Up', 'desc' => 'Drop-off and pick-up are available from 9:00am to 5:00pm daily.'],
                    ['icon' => 'verified', 'title' => 'Manual Review', 'desc' => 'Special care and final pricing are confirmed with you.'],
                ],
            ],
            'species' => [
                'dogs' => [
                    'title' => 'Dog Boarding',
                    'description' => 'Dog boarding in Abuja with individual enclosures and a secure outdoor yard. View nightly rates by dog size and request a stay.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/dogs/hero.jpg',
                        'imageAlt' => 'Dog resting at Waggies boarding facility',
                        'eyebrow' => 'Dog Boarding',
                        'eyebrowIcon' => 'pets',
                        'title' => 'A Safe, Structured Stay for Your Dog',
                        'description' => 'Your dog has an individual enclosure and access to a secure outdoor yard. We follow your feeding instructions and provide water, routine cleaning, and basic welfare checks.',
                        'actions' => [
                            0 => [
                                'label' => 'Request dog boarding',
                                'route' => 'book',
                                'params' => [
                                    'service' => 'boarding',
                                    'pet_type' => 'dog',
                                ],
                                'icon' => 'arrow-forward',
                            ],
                        ],
                    ],
                    'stats' => [
                        0 => [
                            'value' => '9am–5pm',
                            'label' => 'Drop-Off & Pick-Up, Daily',
                        ],
                        1 => [
                            'value' => 'Routine',
                            'label' => 'Care Reviewed',
                        ],
                        2 => [
                            'value' => 'By Size',
                            'label' => 'Nightly Pricing',
                        ],
                    ],
                    'pricingHeading' => [
                        'eyebrow' => 'Dog Boarding Pricing',
                        'title' => "Choose your dog's size",
                        'subtitle' => 'Select the closest size to see an indicative nightly range. Your choice carries into the request, and the team confirms final availability and pricing during review.',
                    ],
                    'daily' => [
                        'heading' => 'Routine Boarding Care',
                        'subtitle' => 'The team follows your care instructions while providing routine boarding care and basic welfare checks.',
                        'steps' => [
                            0 => [
                                'icon' => 'home',
                                'title' => 'Arrival & Settling',
                                'description' => 'Your dog’s care needs are reviewed before the stay is confirmed.',
                            ],
                            1 => [
                                'icon' => 'nutrition',
                                'title' => 'Food & Water',
                                'description' => 'Owner-supplied food is served according to your feeding instructions, with water provided.',
                            ],
                            2 => [
                                'icon' => 'verified',
                                'title' => 'Routine Cleaning',
                                'description' => 'The enclosure is cleaned as part of routine boarding care.',
                            ],
                            3 => [
                                'icon' => 'safety',
                                'title' => 'Basic Welfare Check',
                                'description' => 'Basic welfare checks are included; special supervision needs require staff review.',
                            ],
                            4 => [
                                'icon' => 'water',
                                'title' => 'Pick-Up Arrangements',
                                'description' => 'Drop-off and pick-up are available daily from 9:00am to 5:00pm.',
                            ],
                        ],
                        'images' => [
                            0 => '/media/services/boarding/dogs/daily-01.jpg',
                            1 => '/media/services/boarding/dogs/daily-02.jpg',
                            2 => '/media/services/boarding/dogs/daily-03.jpg',
                            3 => '/media/services/boarding/dogs/daily-04.jpg',
                        ],
                    ],
                    'canine' => [
                        'eyebrow' => 'WHY DOGS THRIVE AT WAGGIES',
                        'title' => 'Built for Safe, Structured Stays',
                        'body' => 'Each dog has individual space, a familiar care rhythm, and a clear review process before the stay is confirmed.',
                        'bullets' => [
                            0 => [
                                'icon' => 'boarding',
                                'bold' => 'Individual space',
                                'text' => 'Every boarding dog receives its own enclosure for a calmer stay.',
                            ],
                            1 => [
                                'icon' => 'nutrition',
                                'bold' => 'Familiar routines',
                                'text' => 'Owner-supplied food and feeding instructions help keep care recognizable.',
                            ],
                            2 => [
                                'icon' => 'verified',
                                'bold' => 'Clear safety review',
                                'text' => 'Health, behaviour, and care needs are reviewed before admission.',
                            ],
                        ],
                        'image' => '/media/services/boarding/dogs/canine.jpg',
                        'imageAlt' => 'Dog resting calmly in an individual boarding enclosure',
                    ],
                    'safety' => [
                        'title' => 'Safety Review Before Check-In',
                        'heading' => 'Clear requirements before every stay',
                        'intro' => 'Before a stay is confirmed, the team reviews your dog’s health information, behaviour, care needs, and the practical details of the request.',
                        'bullets' => [
                            0 => 'Health and parasite concerns are reviewed before admission',
                            1 => 'Behaviour, escape, bite, anxiety, and handling history must be disclosed',
                            2 => 'Admission may be declined when needs fall outside the agreed arrangement',
                            3 => 'Emergency veterinary authorization is collected as part of the boarding request',
                        ],
                        'linkLabel' => 'See boarding requirements',
                    ],
                    'cta' => [
                        'heading' => 'Ready to Request Your Dog\'s Stay?',
                        'headingAccent' => '',
                        'body' => 'Send a boarding request with your preferred dates and dog size. Waggies will confirm availability and details with you.',
                        'primaryLabel' => 'Request dog boarding',
                        'primaryRoute' => 'book',
                        'primaryParams' => [
                            'service' => 'boarding',
                            'pet_type' => 'dog',
                        ],
                    ],
                ],
                'cats' => [
                    'title' => 'Cat Boarding',
                    'description' => 'Cat boarding in Abuja with an individual enclosure and a fixed nightly rate per cat. Request a stay to check availability.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/cats/hero.jpg',
                        'imageAlt' => 'Relaxed cat at Waggies boarding facility',
                        'eyebrow' => 'Cat Boarding',
                        'eyebrowIcon' => 'pets',
                        'title' => 'Calm, Cosy Stays for Your Cat',
                        'description' => 'Each cat has an individual enclosure. We serve the food you provide on your cat’s usual schedule, with water, routine cleaning, and basic welfare checks.',
                        'actions' => [
                            0 => [
                                'label' => 'Request cat boarding',
                                'route' => 'book',
                                'params' => [
                                    'service' => 'boarding',
                                    'pet_type' => 'cat',
                                ],
                                'icon' => 'arrow-forward',
                            ],
                        ],
                    ],
                    'stats' => [
                        0 => [
                            'value' => '9am–5pm',
                            'label' => 'Drop-Off & Pick-Up, Daily',
                        ],
                        1 => [
                            'value' => 'Routine',
                            'label' => 'Care Reviewed',
                        ],
                        2 => [
                            'value' => 'Fixed',
                            'label' => 'Rate per Night',
                        ],
                    ],
                    'pricingHeading' => [
                        'eyebrow' => 'Cat Boarding Pricing',
                        'title' => 'One Fixed Nightly Rate',
                        'subtitle' => 'A simple per-cat nightly rate. Availability and special-care needs are confirmed during review.',
                    ],
                    'daily' => [
                        'heading' => 'A Day in the Life for Cats',
                        'subtitle' => 'A calm, predictable rhythm that follows your cat’s food and care instructions.',
                        'steps' => [
                            0 => [
                                'icon' => 'daylight',
                                'title' => 'Arrival & Settling',
                                'description' => 'Your cat’s care needs are reviewed before the stay is confirmed.',
                            ],
                            1 => [
                                'icon' => 'nutrition',
                                'title' => 'Food & Water',
                                'description' => 'Meals served on your cat\'s usual schedule.',
                            ],
                            2 => [
                                'icon' => 'verified',
                                'title' => 'Routine Cleaning',
                                'description' => 'The enclosure is cleaned as part of routine boarding care.',
                            ],
                            3 => [
                                'icon' => 'rest',
                                'title' => 'Basic Welfare Check',
                                'description' => 'Basic welfare checks are included; special handling or supervision needs require review.',
                            ],
                            4 => [
                                'icon' => 'night',
                                'title' => 'Evening Care',
                                'description' => 'Food and routine care continue according to your instructions.',
                            ],
                        ],
                        'images' => [
                            0 => '/media/services/boarding/cats/daily-01.jpg',
                            1 => '/media/services/boarding/cats/daily-02.jpg',
                            2 => '/media/services/boarding/cats/daily-03.jpg',
                            3 => '/media/services/boarding/cats/daily-04.jpg',
                        ],
                    ],
                    'feline' => [
                        'eyebrow' => 'Why Cats Love Waggies',
                        'title' => 'Built for Feline Instincts',
                        'body' => 'A quiet enclosure, familiar feeding instructions, and steady welfare checks give cats room to settle while they are away.',
                        'bullets' => [
                            0 => [
                                'icon' => 'boarding',
                                'bold' => 'Private space',
                                'text' => 'Each cat has its own enclosure for a calmer stay.',
                            ],
                            1 => [
                                'icon' => 'nutrition',
                                'bold' => 'Familiar feeding',
                                'text' => 'Owner-supplied food keeps the routine recognizable.',
                            ],
                            2 => [
                                'icon' => 'verified',
                                'bold' => 'Quiet observation',
                                'text' => 'Welfare checks respect each cat’s pace; special handling is reviewed before confirmation.',
                            ],
                        ],
                        'image' => '/media/services/boarding/cats/feline.jpg',
                        'imageAlt' => 'Cat relaxing in an individual boarding enclosure',
                    ],
                    'safety' => [
                        'title' => 'Feline Safety & Comfort Standards',
                        'intro' => 'Health information and special-care needs are reviewed before check-in, then the agreed care plan is followed during the stay.',
                        'bullets' => [
                            0 => 'Health information requested during review is checked before check-in',
                            1 => 'Medication and special handling are reviewed separately',
                            2 => 'Welfare concerns are escalated to the appropriate team',
                            3 => 'The team confirms arrangements before check-in',
                        ],
                        'linkLabel' => 'Ask about special care',
                    ],
                    'cta' => [
                        'heading' => 'Ready to Request Your Cat\'s Stay?',
                        'body' => 'Send a boarding request with your preferred dates. Waggies will confirm availability and details with you.',
                        'primaryLabel' => 'Request cat boarding',
                        'primaryRoute' => 'book',
                        'primaryParams' => [
                            'service' => 'boarding',
                            'pet_type' => 'cat',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function serviceDetails(): array
    {
        return [
            'vet-care' => [
                'title' => 'Veterinary Care',
                'eyebrow' => 'On-Site Vet Care · Abuja',
                'ctaText' => 'Request Appointment',
                'ctaRoute' => 'book',
                'ctaParams' => [
                    'service' => 'vet-care',
                ],
                'description' => 'Request veterinary care for your dog or cat. Waggies reviews the reason for care, timing, and any product or administration requirements before confirmation.',
                'hero' => [
                    'imageSrc' => '/media/services/vet-care/hero.jpg',
                    'imageAlt' => 'Veterinary care at Waggies Abuja',
                    'description' => 'Our Abuja veterinary team can help with wellness consultations, full examinations, vaccination requests, and microchipping. Request care for your dog or cat.',
                ],
                'features' => [
                    0 => 'Full nose-to-tail wellness examinations and health consultations',
                    1 => 'Vaccination requests reviewed against the appropriate vaccine and product requirements',
                    2 => 'Routine wellness consultations and comprehensive examinations',
                    3 => 'Microchip implantation as a standalone identification service',
                    4 => 'Existing microchips scanned and recorded rather than duplicated',
                    5 => 'Veterinary review determines assessment, administration, and record or certificate costs',
                    6 => 'Additional clinical needs discussed with the veterinary team',
                ],
                'benefits' => [
                    0 => 'Each request is reviewed by the veterinary team before an appointment or quote is confirmed.',
                    1 => 'Vaccination remains request-only until a confirmed vaccine catalogue exists.',
                    2 => 'Microchipping is available as a standalone service under Veterinary Care → Identification.',
                    3 => 'Clinical records and any applicable certificates are discussed during review.',
                    4 => 'Urgent or emergency needs should be identified clearly in the request so the team can advise on next steps.',
                ],
                'packages' => [
                    0 => [
                        'name' => 'Wellness Consultation',
                        'pricingKey' => 'consultation',
                        'duration' => '20-30 min',
                        'features' => [
                            0 => 'Full physical examination',
                            1 => 'Weight and temperature check',
                            2 => 'Health assessment report',
                            3 => 'Diet and exercise advice',
                        ],
                    ],
                    1 => [
                        'name' => 'Vaccination Visit',
                        'pricingKey' => 'vaccination',
                        'duration' => '30-45 min',
                        'popular' => true,
                        'features' => [
                            0 => 'Wellness check included',
                            1 => 'Core vaccines',
                            2 => 'Vaccination certificate',
                            3 => 'Booster reminders',
                        ],
                    ],
                    2 => [
                        'name' => 'Comprehensive Exam',
                        'pricingKey' => 'comprehensive-exam',
                        'duration' => '45-60 min',
                        'features' => [
                            0 => 'Extended physical exam',
                            1 => 'Blood work and lab tests',
                            2 => 'Dental assessment',
                            3 => 'Full health report',
                            4 => 'Follow-up consultation',
                        ],
                    ],
                    3 => [
                        'name' => 'Treatment Plan',
                        'price' => 'Custom quote',
                        'duration' => 'By assessment',
                        'features' => [
                            0 => 'Diagnosis and prescriptions',
                        ],
                    ],
                ],
                'standards' => [
                    'eyebrow' => 'VETERINARY STANDARDS',
                    'title' => 'Why Pet Parents Trust Our Vet Care',
                    'subtitle' => 'Professional clinical standards combined with compassionate, low-stress handling.',
                    'cards' => [
                        0 => [
                            'icon' => 'safety',
                            'title' => 'Veterinary appointments, daily',
                            'desc' => 'Appointments run from 9:00am to 5:00pm every day. Availability is confirmed after the team reviews your request.',
                        ],
                        1 => [
                            'icon' => 'veterinary-care',
                            'title' => 'Nose-to-Tail Exams',
                            'desc' => 'Comprehensive physical check-ups covering cardiac, dental, otic, ocular, and musculoskeletal health.',
                        ],
                        2 => [
                            'icon' => 'calendar',
                            'title' => 'Digital Records & Boosters',
                            'desc' => 'All health records are securely stored digitally with automated booster reminders so your pet stays protected.',
                        ],
                        3 => [
                            'icon' => 'hours',
                            'title' => 'Calm, Familiar Setting',
                            'desc' => 'Examinations take place in a quiet, low-stress environment without the chaos of a crowded waiting room.',
                        ],
                    ],
                ],
                'shareTitle' => 'Veterinary Care - Waggies',
                'shareDescription' => 'Request veterinary consultations, examinations, vaccination review, or standalone microchipping for dogs and cats at Waggies Abuja.',
                'meta' => [
                    'title' => 'Veterinary Care for Dogs and Cats in Abuja',
                    'description' => 'Veterinary care in Abuja for dogs and cats: consultations, examinations, vaccination requests, and microchipping. Request an appointment with Waggies.',
                ],
            ],
        ];
    }

    private function serviceComparisonData(): array
    {
        return [
            'services' => [
                ['key' => 'boarding', 'label' => 'Boarding', 'icon' => 'boarding', 'pricing' => ['type' => 'from', 'unit' => '/night']],
                ['key' => 'vet-care', 'label' => 'Veterinary Care', 'icon' => 'veterinary-care', 'pricing' => ['type' => 'editorial', 'label' => 'Request or estimate']],
                ['key' => 'relocation', 'label' => 'Relocation', 'icon' => 'airport-departure', 'pricing' => ['type' => 'editorial', 'label' => 'Custom quote']],
            ],
            'features' => [
                ['label' => 'Per-pet nightly pricing', 'supported' => ['boarding']],
                ['label' => 'Dog and cat care', 'supported' => ['boarding', 'vet-care', 'relocation']],
                ['label' => 'Import and export coordination', 'supported' => ['relocation']],
                ['label' => 'Manual review before confirmation', 'supported' => ['boarding', 'vet-care', 'relocation']],
                ['label' => 'Request-based service', 'supported' => ['boarding', 'vet-care', 'relocation']],
            ],
        ];
    }

    private function withCanonicalPackagePricing(string $service, array $page): array
    {
        $variants = app(BookingPricingCatalog::class)->variants($service);
        $variantKeys = [
            'consultation' => 'wellness-consultation',
            'vaccination' => 'vaccination-request',
            'comprehensive-exam' => 'comprehensive-examination',
        ];

        $page['packages'] = array_map(function (array $package) use ($variants, $variantKeys): array {
            if (! isset($package['pricingKey'])) {
                return $package;
            }

            $pricingKey = $variantKeys[$package['pricingKey']] ?? $package['pricingKey'];
            $variant = $variants[$pricingKey] ?? [];

            return array_merge($package, [
                'price' => ($variant['type'] ?? 'quote') === 'quote'
                    ? 'Request review'
                    : '₦'.number_format((int) ($variant['amount'] ?? 0)),
            ]);
        }, $page['packages']);

        if ($service === 'vet-care') {
            $page['packages'] = [
                ['name' => 'Wellness consultation', 'duration' => 'Request an appointment', 'price' => '₦12,000', 'bookingParams' => ['care_need' => 'wellness-consultation'], 'features' => ['Wellness consultation', 'Clinical review before confirmation']],
                ['name' => 'Comprehensive examination', 'duration' => 'Request an appointment', 'price' => '₦18,000', 'bookingParams' => ['care_need' => 'comprehensive-examination'], 'features' => ['Comprehensive examination', 'Clinical review before confirmation']],
                ['name' => 'Vaccination request', 'duration' => 'Request-only service', 'price' => 'Request review', 'bookingParams' => ['care_need' => 'vaccination-request'], 'features' => ['Appropriate vaccine selected during review', 'Assessment, administration, and records confirmed by the veterinary team']],
                ['name' => 'Microchip implantation', 'duration' => 'Identification service', 'price' => 'Request review', 'bookingParams' => ['care_need' => 'microchip'], 'features' => ['Standalone microchipping available', 'Existing chips are scanned and recorded rather than duplicated']],
            ];
        }

        return $page;
    }

    private function serviceComparison(): array
    {
        $comparison = $this->serviceComparisonData();
        $comparison['services'] = array_map(function (array $service): array {
            $pricing = $service['pricing'];

            if ($pricing['type'] === 'from') {
                $amounts = collect(app(BookingPricingCatalog::class)->sizeRates('boarding', 'dogs'))
                    ->pluck('amount')
                    ->filter(fn (mixed $amount): bool => is_numeric($amount))
                    ->map(fn (mixed $amount): int => (int) $amount)
                    ->all();
                $service['price'] = 'From ₦'.number_format(min($amounts)).$pricing['unit'];
            } else {
                $service['price'] = $pricing['label'];
            }

            unset($service['pricing']);

            return $service;
        }, $comparison['services']);

        return $comparison;
    }

    private function serviceIndexFaqs(): array
    {
        return Faq::query()
            ->published()
            ->where('category', 'general')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(4)
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function publishedFaqs(string $category, ?string $subcategory = null, bool $onlyGeneral = false): array
    {
        $query = Faq::query()
            ->published()
            ->where('category', $category);

        if ($onlyGeneral) {
            $query->whereNull('subcategory');
        } elseif ($subcategory !== null) {
            $query->where(function ($query) use ($subcategory): void {
                $query
                    ->whereNull('subcategory')
                    ->orWhere('subcategory', $subcategory);
            });
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
    }

    private function serviceSchema(string $name, array $metadata): array
    {
        return Schema::service()
            ->name($name)
            ->description($metadata['description'])
            ->url($metadata['canonical'])
            ->provider(Schema::organization()->name('Waggies')->url(route('home')))
            ->toArray();
    }
}
