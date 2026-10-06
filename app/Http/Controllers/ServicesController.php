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
        $metadata = ['title' => 'Our Services - Waggies', 'description' => 'Pet services in Abuja: boarding, veterinary care, and dog and cat relocation.', 'canonical' => route('services.index'), 'ogTitle' => 'Our Services - Waggies Pet Care Abuja', 'ogDescription' => 'Pet services in Abuja: boarding, veterinary care, and dog and cat relocation.'];
        $this->setPageHead($metadata, [Schema::webPage()->name('Our Services - Waggies Pet Care Abuja')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.services.index', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/services/hero.jpg', 'imageAlt' => 'Dog outdoors', 'eyebrow' => 'Everything Your Pet Needs', 'title' => 'Pet Care Services,<br/>All Under One Roof', 'description' => 'From overnight boarding to veterinary care and international relocation - Waggies handles each request through a clear review process.', 'actions' => [['label' => 'View Our Services', 'url' => route('services.index').'#services']]],
            'cards' => [
                ['title' => 'Dog Boarding', 'description' => 'Individual enclosures, owner-supplied food, routine cleaning, and welfare checks.', 'route' => 'services.boarding.species', 'params' => ['species' => 'dogs'], 'imageSrc' => '/media/services/boarding/card-dogs.jpg', 'imageAlt' => 'Happy dogs at Waggies', 'icon' => 'pets'],
                ['title' => 'Cat Boarding', 'description' => 'Calm individual enclosures, owner-supplied food, routine cleaning, water, and welfare checks.', 'route' => 'services.boarding.species', 'params' => ['species' => 'cats'], 'imageSrc' => '/media/services/boarding/card-cats.jpg', 'imageAlt' => 'Cat settled into an individual boarding enclosure', 'icon' => 'cat'],
                ['title' => 'Veterinary Care', 'description' => 'Wellness consultations, comprehensive examinations, vaccination requests, and microchipping.', 'route' => 'services.vet-care', 'imageSrc' => '/media/services/vet-care/hero.jpg', 'imageAlt' => 'Veterinarian with dog', 'icon' => 'veterinary-care'],
                ['title' => 'Pet Import to Nigeria', 'description' => 'Permits, health clearance, rabies titer verification, and Abuja airport pickup.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Export permits, rabies titers, IATA crates, and airline coordination.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
            ],
            'band' => [
                'eyebrow' => 'The Waggies Standard',
                'title' => 'Why Our Care System<br/><span class="text-secondary italic">Works So Well</span>',
                'subtitle' => 'Every pet is handled through structured care protocols designed to ensure safety, comfort, and emotional wellbeing.',
                'stats' => [
                    ['icon' => 'veterinary-care', 'title' => 'Veterinary Care', 'subtitle' => 'Health-first handling'],
                    ['icon' => 'boarding', 'title' => 'Individual Enclosures', 'subtitle' => 'One enclosure per pet'],
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
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding'), 'ogTitle' => 'Pet Boarding Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema('Pet Boarding Abuja - Waggies', $metadata)]);

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
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding.species', ['species' => $species]), 'ogTitle' => $page['title'].' Abuja - Waggies', 'ogDescription' => $page['description']];
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
        $metadata = ['title' => $page['meta']['title'].' - Waggies', 'description' => $page['meta']['description'], 'canonical' => route("services.{$service}"), 'ogTitle' => $page['meta']['title'].' - Waggies', 'ogDescription' => $page['meta']['description']];
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
                    'title' => 'A Home Away From Home',
                    'description' => 'Each pet receives an individual enclosure, water, routine cleaning, basic welfare checks, and care based on your instructions.',
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
                        'description' => 'Individual enclosures, owner-supplied food, routine cleaning, and welfare checks.',
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
                        'description' => 'Calm individual enclosures, owner-supplied food, routine cleaning, water, and welfare checks.',
                        'route' => 'services.boarding.species',
                        'params' => [
                            'species' => 'cats',
                        ],
                        'imageSrc' => '/media/services/boarding/card-cats.jpg',
                        'imageAlt' => 'Cat settled into an individual boarding enclosure',
                        'icon' => 'cat',
                    ],
                ],
                'included' => [
                    ['icon' => 'boarding', 'title' => 'Individual Enclosure', 'desc' => 'Each boarding pet receives its own enclosure.'],
                    ['icon' => 'water', 'title' => 'Water', 'desc' => 'Fresh water is part of routine boarding care.'],
                    ['icon' => 'nutrition', 'title' => 'Owner-Supplied Food', 'desc' => 'We follow the food and feeding instructions you provide.'],
                    ['icon' => 'verified', 'title' => 'Basic Welfare Checks', 'desc' => 'Routine cleaning and basic welfare checks are included.'],
                    ['icon' => 'water', 'title' => 'Light Bath or Wash', 'desc' => 'For boarded dogs before pickup where safe and appropriate.'],
                    ['icon' => 'verified', 'title' => 'Manual Review', 'desc' => 'Special care and final pricing are confirmed with you.'],
                ],
            ],
            'species' => [
                'dogs' => [
                    'title' => 'Dog Boarding',
                    'description' => 'Dog boarding in Abuja with individual enclosures, owner-supplied food, routine cleaning, and basic welfare checks.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/dogs/hero.jpg',
                        'imageAlt' => 'Dog resting calmly in an individual boarding enclosure',
                        'eyebrow' => 'Dog Boarding',
                        'eyebrowIcon' => 'pets',
                        'title' => 'A Safe, Structured Stay for Your Dog',
                        'description' => 'Individual enclosures and predictable routines designed to keep your dog comfortable while you\'re away.',
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
                            'value' => '1',
                            'label' => 'Enclosure per Pet',
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
                                'description' => 'Your dog is settled into an individual enclosure after the request and care needs are reviewed.',
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
                                'title' => 'Pickup Preparation',
                                'description' => 'A light bath or wash may be provided before pickup where safe and appropriate.',
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
                    'description' => 'Calm cat boarding in Abuja with individual enclosures, owner-supplied food, routine cleaning, water, and basic welfare checks.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/cats/hero.jpg',
                        'imageAlt' => 'Relaxed cat settled into an individual boarding enclosure at Waggies',
                        'eyebrow' => 'Cat Boarding',
                        'eyebrowIcon' => 'pets',
                        'title' => 'Calm, Cosy Stays for Your Cat',
                        'description' => 'Individual enclosures, owner-supplied food, routine cleaning, water, and basic welfare checks for cats.',
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
                            'value' => '1',
                            'label' => 'Enclosure per Pet',
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
                                'description' => 'Your cat is settled into an individual enclosure and the requested care details are reviewed.',
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
                    'description' => 'Wellness consultations, comprehensive examinations, vaccination requests, and standalone microchipping, subject to veterinary review.',
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
                            'title' => 'On-Site Daily Doctor',
                            'desc' => 'A licensed veterinarian present 7 days a week, ensuring immediate attention for routine care or unexpected guest needs.',
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
                    'title' => 'Veterinary Care',
                    'description' => 'Request veterinary consultations, examinations, vaccination review, or standalone microchipping for dogs and cats at Waggies Abuja.',
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
