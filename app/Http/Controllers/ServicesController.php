<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;
use LogicException;
use Spatie\SchemaOrg\Schema;

final class ServicesController extends Controller
{
    public function index(): View
    {
        $comparison = $this->serviceComparison();
        $metadata = ['title' => 'Our Services - Waggies', 'description' => 'Pet services in Abuja: boarding, grooming, vet care, training, relocation and local transport.', 'canonical' => route('services.index'), 'ogTitle' => 'Our Services - Waggies Pet Care Abuja', 'ogDescription' => 'Pet services in Abuja: boarding, grooming, vet care, training, relocation and local transport.'];
        $this->setPageHead($metadata, [Schema::webPage()->name('Our Services - Waggies Pet Care Abuja')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.services.index', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/services/hero.jpg', 'imageAlt' => 'Dog outdoors', 'eyebrow' => 'Everything Your Pet Needs', 'title' => 'Pet Care Services,<br/>All Under One Roof', 'description' => 'From overnight boarding to international relocation - Waggies handles it all from one facility.', 'actions' => [['label' => 'View Our Services', 'url' => route('services.index').'#services']]],
            'cards' => [
                ['title' => 'Boarding', 'description' => 'Spacious, climate-controlled suites with 24/7 supervision and daily photo updates.', 'href' => route('services.boarding'), 'imageSrc' => '/media/services/boarding/card-dogs.jpg', 'imageAlt' => 'Boarding', 'icon' => 'grooming'],
                ['title' => 'Grooming', 'description' => 'Breed-specific cuts, baths, and styling by experienced groomers using pet-safe products.', 'href' => route('services.grooming'), 'imageSrc' => '/media/services/grooming/hero.jpg', 'imageAlt' => 'Pet being groomed', 'icon' => 'grooming'],
                ['title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and routine wellness check-ups.', 'href' => route('services.vet-care'), 'imageSrc' => '/media/services/vet-care/hero.jpg', 'imageAlt' => 'Veterinarian with dog', 'icon' => 'veterinary-care'],
                ['title' => 'Dog Training', 'description' => 'Positive-reinforcement programmes for puppies and adult dogs of all breeds.', 'href' => route('services.training'), 'imageSrc' => '/media/services/training/hero.jpg', 'imageAlt' => 'Dog training session', 'icon' => 'training'],
                ['title' => 'Pet Relocation', 'description' => 'International moves with full documentation and airline coordination.', 'route' => 'services.relocation', 'imageSrc' => '/media/services/relocation/hero.jpg', 'imageAlt' => 'Pet travel', 'icon' => 'airport-departure'],
            ],
            'stats' => [['icon' => 'veterinary-care', 'title' => 'Vet-Supervised Care', 'subtitle' => 'Health-first handling'], ['icon' => 'hours', 'title' => '24/7 Monitoring', 'subtitle' => 'Constant supervision'], ['icon' => 'boarding', 'title' => 'Climate-Controlled Suites', 'subtitle' => 'Comfort in every season'], ['icon' => 'verified', 'title' => 'Safety-First Routine', 'subtitle' => 'Consistent care standards']],
            'standards' => [['title' => 'Structured Daily Routines', 'desc' => 'Professionally trained handlers and protocols.'], ['title' => '24/7 Supervision', 'desc' => 'Continuous monitoring, day and night.'], ['title' => 'Daily Updates', 'desc' => 'Owners receive real-time pet updates.']],
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
        $page = $boardingPages['species'][$species];
        $page['hero']['imageSrc'] = "/media/services/boarding/{$species}/hero.jpg";
        $page['daily']['images'] = array_map(
            static fn (int $index): string => "/media/services/boarding/{$species}/daily-".str_pad((string) $index, 2, '0', STR_PAD_LEFT).'.jpg',
            [1, 2, 3, 4],
        );
        if ($species === 'cats') {
            $page['feline']['image'] = '/media/services/boarding/cats/feline.jpg';
        }
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $faqs = $this->publishedFaqs('boarding', $species);
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding.species', ['species' => $species]), 'ogTitle' => $page['title'].' Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($page['title'].' Abuja - Waggies', $metadata)]);

        return view('pages.services.boarding.species', $metadata + [
            'navSection' => 'services', 'species' => $species, 'page' => $page,
            'serviceKey' => 'boarding-'.$species, 'tiers' => $this->boardingTiers($species), 'faqs' => $faqs,
        ]);
    }

    public function grooming(): View
    {
        return $this->serviceDetail('grooming');
    }

    public function training(): View
    {
        return $this->serviceDetail('training');
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
                'description' => 'Pet boarding in Abuja with 24/7 supervision, private suites, on-site vet, and daily photo updates.',
                'hero' => [
                    'imageSrc' => '/media/services/boarding/hero.jpg',
                    'imageAlt' => 'Happy dog outdoors',
                    'eyebrow' => 'Pet Boarding in Abuja',
                    'eyebrowIcon' => 'pets',
                    'title' => 'A Home Away From Home',
                    'description' => 'Spacious, climate-controlled suites with 24/7 supervision, orthopedic bedding, and daily updates for total peace of mind.',
                    'actions' => [
                        0 => [
                            'label' => 'View Pricing & Estimates',
                            'route' => 'services.pricing',
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
                        'description' => 'Private suites with outdoor play areas, socialisation sessions, and daily grooming.',
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
                        'description' => 'Calm, quiet cat condos away from dogs - with enrichment toys and cosy resting nooks.',
                        'route' => 'services.boarding.species',
                        'params' => [
                            'species' => 'cats',
                        ],
                        'imageSrc' => '/media/services/boarding/card-cats.jpg',
                        'imageAlt' => 'Cat in a boarding condo',
                        'icon' => 'cat',
                    ],
                    2 => [
                        'title' => 'Exotic Boarding',
                        'description' => 'Specialist care for birds, reptiles, rabbits and small mammals in custom enclosures.',
                        'route' => 'services.boarding.species',
                        'params' => [
                            'species' => 'exotic',
                        ],
                        'imageSrc' => '/media/services/boarding/card-exotic.jpg',
                        'imageAlt' => 'Exotic pet in a specialist enclosure',
                        'icon' => 'exotic-pet',
                    ],
                ],
                'included' => [
                    0 => [
                        'icon' => 'boarding',
                        'title' => 'Boarding Suites',
                        'desc' => 'Climate-controlled rooms with orthopedic bedding.',
                    ],
                    1 => [
                        'icon' => 'nutrition',
                        'title' => 'Quality Meals',
                        'desc' => 'Nutritious food served on your pet\'s usual schedule.',
                    ],
                    2 => [
                        'icon' => 'exercise',
                        'title' => 'Daily Exercise',
                        'desc' => 'Structured play and exercise sessions every day.',
                    ],
                    3 => [
                        'icon' => 'veterinary-care',
                        'title' => 'Vet on Site',
                        'desc' => 'Our on-site vet monitors all boarding guests daily.',
                    ],
                    4 => [
                        'icon' => 'notifications',
                        'title' => 'Daily Photo Updates',
                        'desc' => 'Photo and report sent to you every single day.',
                    ],
                    5 => [
                        'icon' => 'safety',
                        'title' => '24/7 Supervision',
                        'desc' => 'Staff present around the clock - never unsupervised.',
                    ],
                ],
            ],
            'species' => [
                'dogs' => [
                    'title' => 'Dog Boarding',
                    'description' => 'Premium dog boarding in Abuja with private suites, outdoor play areas, socialisation sessions, and 24/7 supervision.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/dogs/hero.jpg',
                        'imageAlt' => 'Happy dogs socialising in the secure outdoor paddock at Waggies',
                        'eyebrow' => 'Dog Boarding',
                        'eyebrowIcon' => 'pets',
                        'title' => 'A Safe, Structured Stay for Your Dog',
                        'description' => 'Private suites, supervised play, and daily routines designed to keep your dog active and comfortable while you\'re away.',
                        'actions' => [
                            0 => [
                                'label' => 'Request boarding',
                                'route' => 'book',
                                'params' => [
                                    'service' => 'boarding',
                                ],
                                'icon' => 'arrow-forward',
                            ],
                            1 => [
                                'label' => 'Get Estimate',
                                'route' => 'services.pricing',
                                'params' => [
                                    'service' => 'boarding-dogs',
                                ],
                                'iconBefore' => 'calculator',
                            ],
                        ],
                    ],
                    'stats' => [
                        0 => [
                            'value' => '5k+',
                            'label' => 'Happy Pet Guests',
                        ],
                        1 => [
                            'value' => '24/7',
                            'label' => 'On-Site Supervision',
                        ],
                        2 => [
                            'value' => '2×',
                            'label' => 'Daily Exercise Sessions',
                        ],
                        3 => [
                            'value' => '100%',
                            'label' => 'Vaccination Verified',
                        ],
                    ],
                    'activityHighlights' => [
                        0 => [
                            'icon' => 'dog',
                            'title' => 'Outdoor Paddocks',
                            'desc' => 'Grassy yards with secure double-gated fencing.',
                        ],
                        1 => [
                            'icon' => 'energy',
                            'title' => 'Matched Playgroups',
                            'desc' => 'Grouped by size, energy, and temperament.',
                        ],
                        2 => [
                            'icon' => 'hours',
                            'title' => '2x Daily Walks',
                            'desc' => 'Structured routine exercise every morning & evening.',
                        ],
                        3 => [
                            'icon' => 'verified',
                            'title' => '24/7 Supervision',
                            'desc' => 'On-site canine care staff and daily vet checks.',
                        ],
                    ],
                    'packageHeading' => [
                        'eyebrow' => 'Dog Boarding Packages',
                        'title' => 'Find the Right Stay for Your Dog',
                        'subtitle' => 'From simple overnight stays to fully supervised care with extra comfort and attention - choose a package that matches your dog\'s needs and routine.',
                    ],
                    'featuresHeading' => [
                        'eyebrow' => 'What\'s Included',
                        'title' => 'Everything Your Dog Needs',
                        'subtitle' => 'Structured care, enrichment, and comfort - all handled by trained dog-care staff.',
                    ],
                    'features' => [
                        0 => [
                            'icon' => 'boarding',
                            'title' => 'Private Suites',
                            'desc' => 'Climate-controlled rooms sized for your dog, with bedding refreshed daily.',
                        ],
                        1 => [
                            'icon' => 'play',
                            'title' => 'Supervised Play',
                            'desc' => 'Group or solo sessions in secure paddocks, matched by size and temperament.',
                        ],
                        2 => [
                            'icon' => 'nutrition',
                            'title' => 'Tailored Feeding',
                            'desc' => 'Meals on your schedule using your food or our premium kibble.',
                        ],
                        3 => [
                            'icon' => 'veterinary-care',
                            'title' => 'Daily Vet Checks',
                            'desc' => 'On-site vet monitors all guests and responds quickly to any concerns.',
                        ],
                        4 => [
                            'icon' => 'notifications',
                            'title' => 'Photo Updates',
                            'desc' => 'Daily photos and notes so you always know how your dog is doing.',
                        ],
                        5 => [
                            'icon' => 'grooming',
                            'title' => 'Optional Grooming',
                            'desc' => 'Add a bath, brush, or full groom during their stay on Premium and Deluxe packages.',
                        ],
                    ],
                    'daily' => [
                        'heading' => 'A Day in the Life for Dogs',
                        'subtitle' => 'We keep tails wagging from sunrise to sunset with a structured routine of fun, food, and rest.',
                        'steps' => [
                            0 => [
                                'icon' => 'daylight',
                                'title' => '7:00 AM - Rise & Shine',
                                'description' => 'First potty walk and fresh water service.',
                            ],
                            1 => [
                                'icon' => 'nutrition',
                                'title' => '8:00 AM - Breakfast',
                                'description' => 'Premium kibble or owner-provided meals served individually.',
                            ],
                            2 => [
                                'icon' => 'play',
                                'title' => '9:30 AM - Group Play',
                                'description' => 'Supervised socialisation in secure outdoor paddocks.',
                            ],
                            3 => [
                                'icon' => 'rest',
                                'title' => '12:00 PM - Rest Time',
                                'description' => 'Quiet rest in climate-controlled suites.',
                            ],
                            4 => [
                                'icon' => 'night',
                                'title' => '8:00 PM - Tuck In',
                                'description' => 'Final potty break, bedtime treats, and soothing music.',
                            ],
                        ],
                        'images' => [
                            0 => '/media/services/boarding/dogs/daily-01.jpg',
                            1 => '/media/services/boarding/dogs/daily-02.jpg',
                            2 => '/media/services/boarding/dogs/daily-03.jpg',
                            3 => '/media/services/boarding/dogs/daily-04.jpg',
                        ],
                    ],
                    'safety' => [
                        'title' => 'Safety First Policy',
                        'intro' => 'Every dog undergoes a health check and vaccination verification before admission. Dogs are grouped by size and temperament, and all play sessions are actively supervised by trained staff.',
                        'bullets' => [
                            0 => 'Vaccination and flea/tick verification required on arrival',
                            1 => 'Dogs separated by size, temperament, and energy level',
                            2 => 'Staff-supervised play sessions throughout the day',
                            3 => 'Emergency vet protocol in place for all boarding dogs',
                        ],
                        'linkLabel' => 'Ask about boarding requirements',
                    ],
                    'cta' => [
                        'heading' => 'Ready to Request Your Dog\'s Stay?',
                        'body' => 'Send a boarding request with your preferred dates. Waggies will confirm availability and details with you.',
                        'primaryLabel' => 'Request boarding',
                        'primaryRoute' => 'book',
                        'primaryParams' => [
                            'service' => 'boarding',
                        ],
                        'secondaryLabel' => 'View All Pricing',
                        'secondaryRoute' => 'services.pricing',
                    ],
                ],
                'cats' => [
                    'title' => 'Cat Boarding',
                    'description' => 'Calm cat boarding in Abuja with private condos, a dog-free wing, enrichment, and attentive daily care.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/cats/hero.jpg',
                        'imageAlt' => 'Relaxed cat in a calm, dog-free boarding condo at Waggies',
                        'eyebrow' => 'Cat Boarding',
                        'eyebrowIcon' => 'pets',
                        'title' => 'Calm, Cosy Stays for Your Cat',
                        'description' => 'Quiet cat condos in a dog-free wing - with enrichment, cosy nooks, and attentive care from cat-specialist staff.',
                        'actions' => [
                            0 => [
                                'label' => 'Request a stay',
                                'route' => 'book',
                                'params' => [
                                    'service' => 'boarding',
                                ],
                                'icon' => 'arrow-forward',
                            ],
                            1 => [
                                'label' => 'Get Estimate',
                                'route' => 'services.pricing',
                                'params' => [
                                    'service' => 'boarding-cats',
                                ],
                                'iconBefore' => 'calculator',
                            ],
                        ],
                    ],
                    'stats' => [
                        0 => [
                            'value' => '100%',
                            'label' => 'Dog-Free Cat Wing',
                        ],
                        1 => [
                            'value' => '24/7',
                            'label' => 'Quiet Monitoring',
                        ],
                        2 => [
                            'value' => 'Daily',
                            'label' => 'Photo Updates',
                        ],
                        3 => [
                            'value' => '3',
                            'label' => 'Condo Size Options',
                        ],
                    ],
                    'sanctuary' => [
                        'eyebrow' => '100% Dog-Free Sanctuary Guarantee',
                        'title' => 'A Quiet, Stress-Free Environment for Felines',
                        'body' => 'Our cat wing is completely separated from dog areas with dedicated air circulation, soft lighting, and zero canine sight or sound exposure.',
                        'badges' => [
                            0 => [
                                'icon' => 'privacy',
                                'label' => 'No Barking Sounds',
                            ],
                            1 => [
                                'icon' => 'home',
                                'label' => 'Multi-Level Condos',
                            ],
                        ],
                    ],
                    'packageHeading' => [
                        'eyebrow' => 'Cat Boarding Packages',
                        'title' => 'Find the Right Condo for Your Cat',
                        'subtitle' => 'From a peaceful overnight to extra enrichment and vet monitoring - every package respects how cats like to live.',
                    ],
                    'featuresHeading' => [
                        'eyebrow' => 'Cat Boarding Includes',
                        'title' => 'A Calm Environment for Cats',
                        'subtitle' => 'Cats are sensitive travellers. Our cat wing is calm, dog-free, and designed around feline behaviour.',
                    ],
                    'features' => [
                        0 => [
                            'icon' => 'boarding',
                            'title' => 'Private Cat Condos',
                            'desc' => 'Multi-level condos with hammocks, perches, and cosy hideaway nooks.',
                        ],
                        1 => [
                            'icon' => 'privacy',
                            'title' => 'Dog-Free Zone',
                            'desc' => 'Our cat wing is fully separate - cats never share corridors or outdoor areas with dogs.',
                        ],
                        2 => [
                            'icon' => 'play',
                            'title' => 'Daily Enrichment',
                            'desc' => 'Puzzle feeders, interactive toys, and gentle one-on-one play tailored to your cat.',
                        ],
                        3 => [
                            'icon' => 'nutrition',
                            'title' => 'Tailored Feeding',
                            'desc' => 'Meals on your cat\'s usual schedule using your preferred brand or our quality food.',
                        ],
                        4 => [
                            'icon' => 'veterinary-care',
                            'title' => 'Vet Monitoring',
                            'desc' => 'On-site vet checks all cat guests daily and addresses health concerns immediately.',
                        ],
                        5 => [
                            'icon' => 'notifications',
                            'title' => 'Daily Photo Updates',
                            'desc' => 'Photos and a brief note every day so you always know how your cat is settling in.',
                        ],
                    ],
                    'daily' => [
                        'heading' => 'A Day in the Life for Cats',
                        'subtitle' => 'A calm, predictable rhythm that helps even shy cats feel secure - never rushed, never forced.',
                        'steps' => [
                            0 => [
                                'icon' => 'daylight',
                                'title' => '7:30 AM - Gentle Start',
                                'description' => 'Quiet check-in, fresh water, and litter refresh without disruption.',
                            ],
                            1 => [
                                'icon' => 'nutrition',
                                'title' => '8:30 AM - Breakfast',
                                'description' => 'Meals served in-suite on your cat\'s usual schedule.',
                            ],
                            2 => [
                                'icon' => 'play',
                                'title' => '11:00 AM - Enrichment',
                                'description' => 'Puzzle feeders, wand play, or solo exploration in the private playroom.',
                            ],
                            3 => [
                                'icon' => 'rest',
                                'title' => '2:00 PM - Rest Period',
                                'description' => 'Lights dimmed for afternoon naps - staff keep noise levels low.',
                            ],
                            4 => [
                                'icon' => 'night',
                                'title' => '8:00 PM - Evening Settle',
                                'description' => 'Dinner, final litter check, and a calm goodnight routine.',
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
                        'body' => 'Vertical space, privacy, and predictable routines - the three things cats need most when away from home.',
                        'bullets' => [
                            0 => [
                                'bold' => 'No dog noise or smells',
                                'text' => ' - reduces stress for even the most anxious cats.',
                            ],
                            1 => [
                                'bold' => 'Bring bedding and toys',
                                'text' => ' - familiar scents make settling in much faster.',
                            ],
                            2 => [
                                'bold' => 'Cat-specialist staff',
                                'text' => ' - trained in low-stress handling and reading feline body language.',
                            ],
                        ],
                        'image' => '/media/services/boarding/cats/feline.jpg',
                        'imageAlt' => 'Cat relaxing in a multi-level boarding condo',
                    ],
                    'safety' => [
                        'title' => 'Feline Safety & Comfort Standards',
                        'intro' => 'We never force interaction. Shy cats get extra settling time, familiar scents from home are welcome, and every condo has elevated perches and hiding spots.',
                        'bullets' => [
                            0 => 'Vaccination records verified before check-in',
                            1 => 'Fully dog-free wing with separate air circulation',
                            2 => 'Medication administered precisely to your instructions',
                            3 => 'Immediate vet assessment if appetite or behaviour changes',
                        ],
                        'linkLabel' => 'Discuss your cat\'s needs',
                    ],
                    'cta' => [
                        'heading' => 'Ready to Request Your Cat\'s Stay?',
                        'body' => 'Send a boarding request with your preferred dates. Waggies will confirm availability and details with you.',
                        'primaryLabel' => 'Request cat boarding',
                        'primaryRoute' => 'book',
                        'primaryParams' => [
                            'service' => 'boarding',
                        ],
                        'secondaryLabel' => 'View All Pricing',
                        'secondaryRoute' => 'services.pricing',
                    ],
                ],
                'exotic' => [
                    'title' => 'Exotic Pet Boarding',
                    'description' => 'Specialist exotic pet boarding in Abuja with custom enclosures, climate monitoring, and species-appropriate care.',
                    'hero' => [
                        'imageSrc' => '/media/services/boarding/exotic/hero.jpg',
                        'imageAlt' => 'Colourful parrots in a specialist exotic pet care environment at Waggies',
                        'eyebrow' => 'Exotic Pet Boarding',
                        'eyebrowIcon' => 'exotic-pet',
                        'title' => 'Specialist Care for Exotic Pets',
                        'description' => 'Birds, reptiles, rabbits, guinea pigs, and small mammals - cared for by specialist staff in custom-built, species-appropriate enclosures.',
                        'actions' => [
                            0 => [
                                'label' => 'Enquire Now',
                                'route' => 'contact',
                                'params' => [
                                    'intent' => 'consult',
                                    'service' => 'boarding',
                                    'variant' => 'exotic',
                                ],
                                'icon' => 'arrow-forward',
                            ],
                            1 => [
                                'label' => 'Get Estimate',
                                'route' => 'services.pricing',
                                'params' => [
                                    'service' => 'boarding-exotic',
                                ],
                                'iconBefore' => 'calculator',
                            ],
                        ],
                    ],
                    'stats' => [
                        0 => [
                            'value' => '50+',
                            'label' => 'Species Experience',
                        ],
                        1 => [
                            'value' => '24/7',
                            'label' => 'Climate Monitoring',
                        ],
                        2 => [
                            'value' => '100%',
                            'label' => 'Custom Enclosures',
                        ],
                        3 => [
                            'value' => 'Daily',
                            'label' => 'Care Updates',
                        ],
                    ],
                    'environment' => [
                        'eyebrow' => 'ENVIRONMENTAL PRECISION',
                        'title' => 'Species-Specific Climate & Care Protocols',
                        'badge' => 'Specialist Handlers & Vet Supervision',
                        'features' => [
                            0 => [
                                'icon' => 'climate',
                                'title' => 'Thermal Gradients',
                                'desc' => 'Basking spots & cool retreat zones logged twice daily.',
                            ],
                            1 => [
                                'icon' => 'daylight',
                                'title' => 'UVB & Lighting',
                                'desc' => 'Strict photoperiod timers matched to native habitat needs.',
                            ],
                            2 => [
                                'icon' => 'water',
                                'title' => 'Humidity Control',
                                'desc' => 'Misting & misting cycles tailored for tropical or arid species.',
                            ],
                            3 => [
                                'icon' => 'medical',
                                'title' => 'Live Feeder Handling',
                                'desc' => 'Live or frozen feeder protocols executed to exact instructions.',
                            ],
                        ],
                    ],
                    'packageHeading' => [
                        'eyebrow' => 'Exotic Boarding Packages',
                        'title' => 'Care Matched to Your Species',
                        'subtitle' => 'Pricing reflects enclosure complexity and specialist handling - we\'ll confirm exact rates when you enquire.',
                    ],
                    'featuresHeading' => [
                        'eyebrow' => 'What\'s Included',
                        'title' => 'Tailored Care for Every Species',
                        'subtitle' => null,
                    ],
                    'features' => [
                        0 => [
                            'icon' => 'home',
                            'title' => 'Species-Specific Enclosures',
                            'desc' => 'Custom housing with correct temperature, humidity, and lighting for each species.',
                        ],
                        1 => [
                            'icon' => 'nutrition',
                            'title' => 'Specialist Diet',
                            'desc' => 'Feeding according to each animal\'s dietary needs and your usual routine - including live feeders when required.',
                        ],
                        2 => [
                            'icon' => 'veterinary-care',
                            'title' => 'Exotic Vet Access',
                            'desc' => 'Our vet has experience with exotic and small animals and reviews guests daily.',
                        ],
                        3 => [
                            'icon' => 'notifications',
                            'title' => 'Regular Updates',
                            'desc' => 'Photo updates so you can check in on your pet throughout their stay.',
                        ],
                        4 => [
                            'icon' => 'safety',
                            'title' => '24/7 Monitoring',
                            'desc' => 'Staff check on exotic guests throughout the day and night, including climate systems.',
                        ],
                        5 => [
                            'icon' => 'behavior',
                            'title' => 'Experienced Handlers',
                            'desc' => 'Trained staff who understand handling, stress signals, and species-specific behaviour.',
                        ],
                    ],
                    'species' => [
                        0 => [
                            'label' => 'Birds',
                            'icon' => 'exotic-pet',
                            'desc' => 'High-volume flight space, perches & daily fresh seed/fruit feeds.',
                        ],
                        1 => [
                            'label' => 'Reptiles',
                            'icon' => 'parasite',
                            'desc' => 'Thermal gradient zones, UVB photoperiods & humidity logging.',
                        ],
                        2 => [
                            'label' => 'Rabbits',
                            'icon' => 'exotic-pet',
                            'desc' => 'Spacious exercise runs, unlimited timothy hay & solid flooring.',
                        ],
                        3 => [
                            'label' => 'Guinea Pigs',
                            'icon' => 'exotic-pet',
                            'desc' => 'Quiet habitats, Vitamin C enriched diet & deep bedding.',
                        ],
                        4 => [
                            'label' => 'Hamsters',
                            'icon' => 'pets',
                            'desc' => 'Secure escape-proof habitats with quiet nocturnal rest areas.',
                        ],
                        5 => [
                            'label' => 'Aquatic / Others',
                            'icon' => 'water',
                            'desc' => 'Stable temperature control & custom feeding protocols.',
                        ],
                    ],
                    'featureBand' => [
                        'eyebrow' => 'Before You Book',
                        'title' => 'What to Prepare for Check-In',
                        'subtitle' => 'A little preparation helps exotic pets settle faster and keeps their routine intact.',
                        'cta' => [
                            'label' => 'Discuss Your Pet',
                            'route' => 'contact',
                        ],
                        'features' => [
                            0 => [
                                'icon' => 'document',
                                'title' => 'Written Care Sheet',
                                'description' => 'Feeding times, portion sizes, temperature ranges, and any handling preferences.',
                            ],
                            1 => [
                                'icon' => 'supplies',
                                'title' => 'Supplies from Home',
                                'description' => 'Food, substrates, feeders, or habitat items your pet is used to - clearly labelled.',
                            ],
                            2 => [
                                'icon' => 'verified',
                                'title' => 'Health Records',
                                'description' => 'Recent vet notes or vaccination records where applicable for your species.',
                            ],
                        ],
                    ],
                    'safety' => [
                        'title' => 'Exotic Care & Safety Protocols',
                        'intro' => 'Every exotic booking starts with a pre-arrival consultation. We confirm species requirements, feeding instructions, and enclosure specifications before your pet arrives.',
                        'bullets' => [
                            0 => 'Pre-stay consultation to confirm habitat and dietary needs',
                            1 => 'Owner-supplied feeders or equipment welcomed and labelled',
                            2 => 'Separate exotic wing - no contact with dogs or cats',
                            3 => 'Immediate vet escalation if behaviour or environment deviates',
                        ],
                        'linkLabel' => 'Start an exotic boarding enquiry',
                    ],
                    'cta' => [
                        'heading' => 'Enquire About Exotic Boarding',
                        'body' => 'Tell us your species, setup, and dates - we\'ll confirm availability and build a care plan before check-in.',
                        'primaryLabel' => 'Start a boarding request',
                        'primaryRoute' => 'book',
                        'primaryParams' => [
                            'service' => 'boarding',
                            'variant' => 'exotic',
                        ],
                        'secondaryLabel' => 'Get Estimate',
                        'secondaryRoute' => 'services.pricing',
                        'secondaryParams' => [
                            'service' => 'boarding-exotic',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function serviceDetails(): array
    {
        return [
            'grooming' => [
                'title' => 'Pet Grooming Spa',
                'eyebrow' => 'Grooming Spa · Abuja',
                'ctaText' => 'Request Grooming',
                'ctaRoute' => 'book',
                'ctaParams' => [
                    'service' => 'grooming',
                ],
                'description' => 'Our groomers use gentle handling and non-toxic, pet-safe products in a peaceful setting designed to keep your pet relaxed from drop-off to pickup.',
                'hero' => [
                    'imageSrc' => '/media/services/grooming/hero.jpg',
                    'imageAlt' => 'Pet grooming spa at Waggies Abuja',
                    'description' => 'Breed-specific treatments, baths, and precision styling by experienced groomers in a calm, purpose-built spa.',
                ],
                'features' => [
                    0 => 'Bath with breed-specific shampoo and conditioner',
                    1 => 'Professional blow-dry and brush-out for a flawless finish',
                    2 => 'Breed-standard precision cut and styling by experienced groomers',
                    3 => 'Deep de-shed treatment to dramatically reduce loose fur',
                    4 => 'Gentle facial wash, eye cleaning, and ear inspection',
                    5 => 'Safe nail trim and grind to a comfortable length',
                    6 => 'Teeth brushing with pet-safe enzymatic toothpaste',
                    7 => 'Light cologne spritz and bandana finishing touch',
                    8 => 'Anal gland expression (upon request)',
                    9 => 'Sanitary trim and paw pad tidy-up',
                    10 => 'Flea and tick bath treatment available',
                    11 => 'Blueberry facial for tear stain removal',
                ],
                'benefits' => [
                    0 => 'All groomers are professionally trained and experienced — your pet is in expert hands every time.',
                    1 => 'We use only quality, non-toxic, pet-safe shampoos and conditioners — no harsh chemicals.',
                    2 => 'Patient, low-stress handling techniques especially for nervous or anxious pets.',
                    3 => 'We respect your time — drop-off and collection slots run to schedule, every single day.',
                    4 => 'Full photo report sent after every groom so you can see the results before pickup.',
                    5 => 'Breed-specific expertise means your pet gets a cut that suits their coat type and breed standard.',
                ],
                'packages' => [
                    0 => [
                        'name' => 'Bath & Brush',
                        'pricingKey' => 'bath',
                        'duration' => '45-60 min',
                        'features' => [
                            0 => 'Shampoo bath',
                            1 => 'Blow-dry',
                            2 => 'Brush-out',
                            3 => 'Ear cleaning',
                            4 => 'Nail trim',
                        ],
                    ],
                    1 => [
                        'name' => 'Full Groom',
                        'pricingKey' => 'full',
                        'duration' => '90-120 min',
                        'popular' => true,
                        'features' => [
                            0 => 'Bath & Brush',
                            1 => 'Breed-standard clip and styling',
                            2 => 'Teeth brushing',
                            3 => 'De-shed treatment',
                            4 => 'Finish',
                        ],
                    ],
                    2 => [
                        'name' => 'Luxury Spa',
                        'pricingKey' => 'spa',
                        'duration' => '2-3 hours',
                        'features' => [
                            0 => 'Full Groom',
                            1 => 'Conditioning treatment',
                            2 => 'Facial and paw treatment',
                            3 => 'Flea/tick inspection',
                            4 => 'Photo report',
                        ],
                    ],
                ],
                'standards' => [
                    'eyebrow' => 'THE SPA EXPERIENCE',
                    'title' => 'How Every Groom Works',
                    'subtitle' => 'Four deliberate steps to ensure your pet leaves clean, relaxed, and looking their best.',
                    'cards' => [
                        0 => [
                            'number' => '01',
                            'title' => 'Coat & Skin Check',
                            'desc' => 'Personalised consultation to inspect coat condition, skin sensitivity, and breed styling requirements.',
                        ],
                        1 => [
                            'number' => '02',
                            'title' => 'Hydrobath & Massage',
                            'desc' => 'Relaxing warm bath using pet-safe, natural shampoos and deep conditioning treatments.',
                        ],
                        2 => [
                            'number' => '03',
                            'title' => 'Precision Styling & Dry',
                            'desc' => 'Gentle blow-dry, hand brush-out, and breed-standard scissors or clipper styling by expert groomers.',
                        ],
                        3 => [
                            'number' => '04',
                            'title' => 'Finishing & Photo Report',
                            'desc' => 'Nail grind, ear tidy, light fragrance spritz, and a photo report sent straight to your phone.',
                        ],
                    ],
                ],
                'shareTitle' => 'Pet Grooming Spa - Waggies',
                'shareDescription' => 'Breed-specific pet grooming in Abuja: baths, precision styling, nail trims, and de-shed treatments by experienced groomers.',
                'meta' => [
                    'title' => 'Pet Grooming Spa',
                    'description' => 'Breed-specific pet grooming in Abuja: baths, precision styling, nail trims, and de-shed treatments by experienced groomers.',
                ],
            ],
            'training' => [
                'title' => 'Dog Training',
                'eyebrow' => 'Dog Training · Abuja',
                'ctaText' => 'Request Training',
                'ctaRoute' => 'book',
                'ctaParams' => [
                    'service' => 'training',
                ],
                'description' => 'Our trainers use only reward-based, force-free methods. Whether you\'re starting with a new puppy or working through behavioural challenges, we design a programme that fits your dog\'s temperament and your goals.',
                'hero' => [
                    'imageSrc' => '/media/services/training/hero.jpg',
                    'imageAlt' => 'Dog training session at Waggies Abuja',
                    'description' => 'Science-based, positive-reinforcement training programmes for puppies and adult dogs — building confidence, manners, and a stronger bond.',
                ],
                'features' => [
                    0 => 'Puppy socialisation, recall, and bite inhibition (8-16 weeks)',
                    1 => 'Basic obedience — sit, stay, come, heel, and leash manners',
                    2 => 'Advanced obedience — off-lead reliability and distance commands',
                    3 => 'Behaviour modification for reactivity, anxiety, and aggression',
                    4 => 'Small group classes for socialisation and real-world practice',
                    5 => '1-to-1 private sessions tailored to your dog\'s specific needs',
                    6 => 'Clicker training and marker-based positive reinforcement',
                    7 => 'Loose-lead walking and polite greeting training',
                    8 => 'Separation anxiety management programmes',
                    9 => 'Trick training for mental stimulation and fun',
                    10 => 'Follow-up sessions and progress tracking included',
                    11 => 'Written training plan and homework for between sessions',
                ],
                'benefits' => [
                    0 => 'Reward-based, force-free methods only — we never use aversive tools or punishment-based techniques.',
                    1 => 'All trainers are experienced in canine behaviour and positive-reinforcement training methods.',
                    2 => 'Training that strengthens the relationship between you and your dog — bond-building focus.',
                    3 => 'Clear goals set at the outset and tracked throughout the programme — measurable progress.',
                    4 => 'Flexible scheduling — weekday, weekend, and evening session slots available.',
                    5 => 'Small class sizes (max 6 dogs) ensure every dog gets personal attention.',
                ],
                'packages' => [
                    0 => [
                        'name' => 'Puppy Foundation',
                        'pricingKey' => 'puppy',
                        'duration' => '6 sessions · 45 min each',
                        'features' => [
                            0 => 'Socialisation',
                            1 => 'Sit, stay, recall foundation',
                            2 => 'Bite inhibition and toilet training',
                            3 => 'Leash introduction',
                            4 => 'Puppy manual and homework',
                        ],
                    ],
                    1 => [
                        'name' => 'Basic Obedience',
                        'pricingKey' => 'obedience',
                        'duration' => '8 sessions · 60 min each',
                        'popular' => true,
                        'features' => [
                            0 => 'Basic commands',
                            1 => 'Leash manners and heel',
                            2 => 'Polite greetings',
                            3 => 'Distraction proofing',
                            4 => 'Written training plan',
                            5 => 'Progress tracking',
                        ],
                    ],
                    2 => [
                        'name' => 'Behaviour Modification',
                        'price' => 'Custom quote',
                        'duration' => 'Assessment-led',
                        'features' => [
                            0 => '1-to-1 behavioural assessment',
                            1 => 'Custom modification plan',
                            2 => 'Reactivity and anxiety work',
                            3 => 'Environmental management',
                            4 => 'Ongoing trainer support',
                        ],
                    ],
                ],
                'standards' => [
                    'eyebrow' => 'OUR METHODOLOGY',
                    'title' => 'Science-Based Dog Training',
                    'subtitle' => 'Positive reinforcement techniques that build trust, clear communication, and reliable manners.',
                    'cards' => [
                        0 => [
                            'icon' => 'achievement',
                            'title' => 'Reward-Based & Force-Free',
                            'desc' => 'We use science-backed positive reinforcement — no choke chains, prong collars, or punitive techniques.',
                        ],
                        1 => [
                            'icon' => 'behavior',
                            'title' => 'Temperament-Tailored',
                            'desc' => 'Every dog\'s learning style is unique. We adapt exercises to your dog\'s motivation, energy, and confidence level.',
                        ],
                        2 => [
                            'icon' => 'team',
                            'title' => 'Small Class Sizes',
                            'desc' => 'Group classes are capped at 6 dogs to ensure personalized trainer feedback and safe socialisation.',
                        ],
                        3 => [
                            'icon' => 'training',
                            'title' => 'Homework & Written Plans',
                            'desc' => 'Every session includes clear action steps, video recaps, and homework guides to ensure lasting results at home.',
                        ],
                    ],
                ],
                'shareTitle' => 'Dog Training - Waggies',
                'shareDescription' => 'Science-based, positive-reinforcement dog training in Abuja for puppies and adults - obedience, behaviour modification, and group classes.',
                'meta' => [
                    'title' => 'Dog Training',
                    'description' => 'Science-based, positive-reinforcement dog training in Abuja for puppies and adults - obedience, behaviour modification, and group classes.',
                ],
            ],
            'vet-care' => [
                'title' => 'Veterinary Care',
                'eyebrow' => 'On-Site Vet Care · Abuja',
                'ctaText' => 'Request Appointment',
                'ctaRoute' => 'book',
                'ctaParams' => [
                    'service' => 'vet-care',
                ],
                'description' => 'Having a veterinarian on-site every day gives pet parents total confidence. Whether your pet needs a routine check-up, vaccination booster, or medical attention while boarding, our care team is ready.',
                'hero' => [
                    'imageSrc' => '/media/services/vet-care/hero.jpg',
                    'imageAlt' => 'Veterinary care at Waggies Abuja',
                    'description' => 'Consultations, vaccinations, wellness checks, and minor treatments — delivered by our on-site veterinarian every day of the week.',
                ],
                'features' => [
                    0 => 'Full nose-to-tail wellness examinations and health consultations',
                    1 => 'Core and non-core vaccinations on a personalised schedule',
                    2 => 'Routine wellness check-ups to catch issues early',
                    3 => 'Diagnosis, prescriptions, and minor treatment for common conditions',
                    4 => 'Flea, tick, and worm treatments and prevention advice',
                    5 => 'In-clinic diagnostic tests and external lab coordination',
                    6 => 'Dental examination, scaling, and minor dental procedures',
                    7 => 'Microchipping for permanent pet identification',
                    8 => 'Pre-travel health certificates and documentation',
                    9 => 'Weight monitoring and nutritional counselling',
                    10 => 'Spay/neuter consultations and scheduling',
                    11 => 'Emergency first aid and stabilisation',
                ],
                'benefits' => [
                    0 => 'Our vet is present on-site every day of the week, including weekends — no separate trip needed.',
                    1 => 'Boarding guests receive immediate veterinary attention if any concern arises during their stay.',
                    2 => 'Consultations happen in a calm, familiar setting — significantly less anxiety for your pet.',
                    3 => 'Full medical records maintained digitally for easy access and continuity of care.',
                    4 => 'Vaccination reminders sent automatically so you never miss a booster.',
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
                'shareDescription' => 'Veterinary consultations, vaccinations, wellness check-ups and minor treatments from our on-site vet - every day at Waggies Abuja.',
                'meta' => [
                    'title' => 'Veterinary Care',
                    'description' => 'Veterinary consultations, vaccinations, wellness check-ups and minor treatments from our on-site vet - every day at Waggies Abuja.',
                ],
            ],
        ];
    }

    private function serviceComparisonData(): array
    {
        return [
            'services' => [
                0 => [
                    'key' => 'boarding',
                    'label' => 'Boarding',
                    'icon' => 'boarding',
                    'pricing' => [
                        'type' => 'from',
                        'unit' => '/night',
                    ],
                ],
                1 => [
                    'key' => 'grooming',
                    'label' => 'Grooming',
                    'icon' => 'grooming',
                    'pricing' => [
                        'type' => 'from',
                        'unit' => '/session',
                    ],
                ],
                2 => [
                    'key' => 'vet-care',
                    'label' => 'Vet Care',
                    'icon' => 'veterinary-care',
                    'pricing' => [
                        'type' => 'from',
                        'unit' => '/visit',
                    ],
                ],
                3 => [
                    'key' => 'training',
                    'label' => 'Training',
                    'icon' => 'training',
                    'pricing' => [
                        'type' => 'from',
                        'unit' => '/programme',
                    ],
                ],
                4 => [
                    'key' => 'relocation',
                    'label' => 'Relocation',
                    'icon' => 'airport-departure',
                    'pricing' => [
                        'type' => 'editorial',
                        'label' => 'Custom quote',
                    ],
                ],
                5 => [
                    'key' => 'transport',
                    'label' => 'Local Transport',
                    'icon' => 'transport',
                    'pricing' => [
                        'type' => 'editorial',
                        'label' => 'Route estimate/trip',
                    ],
                ],
            ],
            'features' => [
                0 => [
                    'label' => '24/7 Care',
                    'supported' => [
                        0 => 'boarding',
                    ],
                ],
                1 => [
                    'label' => 'On-site Vet',
                    'supported' => [
                        0 => 'boarding',
                        1 => 'vet-care',
                    ],
                ],
                2 => [
                    'label' => 'Home Visit',
                    'supported' => [
                        0 => 'vet-care',
                        1 => 'transport',
                    ],
                ],
                3 => [
                    'label' => 'International',
                    'supported' => [
                        0 => 'relocation',
                    ],
                ],
                4 => [
                    'label' => 'Group Sessions',
                    'supported' => [
                        0 => 'training',
                    ],
                ],
                5 => [
                    'label' => 'Pickup & Dropoff',
                    'supported' => [
                        0 => 'transport',
                        1 => 'relocation',
                    ],
                ],
                6 => [
                    'label' => 'Daily Updates',
                    'supported' => [
                        0 => 'boarding',
                        1 => 'grooming',
                    ],
                ],
                7 => [
                    'label' => 'Booking Request',
                    'supported' => [
                        0 => 'boarding',
                        1 => 'grooming',
                        2 => 'vet-care',
                        3 => 'training',
                        4 => 'relocation',
                        5 => 'transport',
                    ],
                ],
            ],
        ];
    }

    private function boardingTiers(string $species): array
    {
        $tiers = config("waggies_pricing.services.boarding.variants.{$species}.tiers", []);

        return collect($tiers)->map(function (array $tier, string $key): array {
            return array_merge($tier, [
                'key' => $key,
                'price' => $this->formatTierPrice($tier),
                'unit' => config('waggies_pricing.services.boarding.unit', '/night'),
                'featured' => $tier['featured'] ?? false,
            ]);
        })->values()->all();
    }

    private function withCanonicalPackagePricing(string $service, array $page): array
    {
        $tiers = config("waggies_pricing.services.{$service}.tiers", []);

        $page['packages'] = array_map(function (array $package) use ($service, $tiers): array {
            if (! isset($package['pricingKey'])) {
                return $package;
            }

            $pricingKey = $package['pricingKey'];
            $tier = $tiers[$pricingKey] ?? null;

            if ($tier === null) {
                throw new LogicException("Missing canonical pricing tier [{$service}.{$pricingKey}].");
            }

            return array_merge($package, ['price' => $this->formatTierPrice($tier)]);
        }, $page['packages']);

        return $page;
    }

    private function serviceComparison(): array
    {
        $comparison = $this->serviceComparisonData();
        $services = config('waggies_pricing.services');

        $comparison['services'] = array_map(function (array $service) use ($services): array {
            $pricing = $service['pricing'];

            if ($pricing['type'] === 'from') {
                $amounts = $this->serviceAmounts($service['key'], $services);
                $service['price'] = 'From ₦'.number_format(min($amounts)).$pricing['unit'];
            } else {
                $service['price'] = $pricing['label'];
            }

            unset($service['pricing']);

            return $service;
        }, $comparison['services']);

        return $comparison;
    }

    private function serviceAmounts(string $service, array $services): array
    {
        $tiers = $service === 'boarding'
            ? collect($services['boarding']['variants'])->flatMap(fn (array $variant): array => $variant['tiers'])->all()
            : ($services[$service]['tiers'] ?? []);
        $amounts = array_values(array_filter(array_map(
            fn (array $tier): ?int => ($tier['type'] ?? 'fixed') === 'quote' ? null : ($tier['amount'] ?? null),
            $tiers,
        ), fn (?int $amount): bool => $amount !== null));

        if ($amounts === []) {
            throw new LogicException("Missing canonical comparison pricing for [{$service}].");
        }

        return $amounts;
    }

    private function formatTierPrice(array $tier): string
    {
        if (($tier['type'] ?? 'fixed') === 'quote') {
            return 'Quote';
        }

        $amount = '₦'.number_format($tier['amount']);

        if (($tier['type'] ?? 'fixed') === 'estimate' && isset($tier['max_amount']) && $tier['max_amount'] !== $tier['amount']) {
            return $amount.' - ₦'.number_format($tier['max_amount']);
        }

        return $amount;
    }

    private function serviceIndexFaqs(): array
    {
        return $this->publishedFaqs('services');
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
