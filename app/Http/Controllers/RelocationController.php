<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class RelocationController extends Controller
{
    public function index(): View
    {
        $description = 'Request pet relocation via Abuja airport (ABV). Waggies reviews route requirements and providers, then confirms scope and costs in a custom quote.';
        $metadata = ['title' => 'Pet Relocation via Abuja Airport (ABV) | Waggies', 'description' => $description, 'canonical' => route('services.relocation'), 'ogTitle' => 'Pet Relocation via Abuja Airport (ABV) | Waggies', 'ogDescription' => $description];
        $this->setPageHead($metadata, [$this->serviceSchema('Pet Relocation to and from Nigeria', $metadata)]);

        return view('pages.services.relocation', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/services/relocation/hero.jpg', 'imageAlt' => 'International pet relocation service by Waggies'],
            'cards' => [
                ['title' => 'Pet Import to Nigeria', 'description' => 'Import permits, veterinary and health documents, customs clearance, and Abuja airport pickup. Scope depends on route and quote.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Export permits, veterinary documents, crate planning, airline booking, and departure airport transport. Scope depends on route and quote.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
            ],
            'faqs' => $this->serviceFaqs(),
        ]);
    }

    public function import(): View
    {
        return $this->detail('import');
    }

    public function export(): View
    {
        return $this->detail('export');
    }

    public function checklist(): View
    {
        $page = $this->relocationPages()['checklist'];
        $metadata = ['title' => $page['metaTitle'].' - Waggies', 'description' => $page['description'], 'canonical' => route('relocation.checklist'), 'ogTitle' => $page['ogTitle'], 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [Schema::webPage()->name($metadata['title'])->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.relocation.checklist', $metadata + [
            'navSection' => 'services',
            'page' => $page,
        ]);
    }

    private function relocationPages(): array
    {
        return [
            'import' => [
                'metaTitle' => 'Pet Import to Nigeria',
                'description' => 'Bringing a pet into Nigeria? Waggies coordinates route-specific permits, health documents, customs steps, and pickup at Abuja Airport (ABV). Request a quote.',
                'ogTitle' => 'Import Pets to Nigeria - Waggies Abuja',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/import/hero.jpg',
                    'imageAlt' => 'Pet prepared for import travel',
                    'eyebrow' => 'Pet Import · Nigeria',
                    'title' => 'Bringing Your Pet<br/>to Nigeria?',
                    'description' => 'Pet import requirements depend on your origin and travel date. Share your route with us; we’ll confirm the steps Waggies can coordinate and the costs in your quote.',
                    'actions' => [
                        0 => [
                            'label' => 'Request Quote',
                            'route' => 'book',
                            'params' => [
                                'service' => 'relocation',
                                'direction' => 'import',
                            ],
                            'icon' => 'arrow-forward',
                        ],
                        1 => [
                            'label' => 'View Checklist',
                            'route' => 'relocation.checklist',
                        ],
                    ],
                ],
                'sectionHeading' => [
                    'eyebrow' => 'Import Process',
                    'title' => 'Import Support We Can Coordinate',
                    'subtitle' => 'We coordinate permits, veterinary and health documents, customs clearance, and airport pickup, with the route-specific scope confirmed in your quote.',
                ],
                'features' => [
                    0 => [
                        'icon' => 'document',
                        'title' => 'Permits & Customs Clearance',
                        'desc' => 'We coordinate required import permits and customs-clearance steps for your route. Your quote confirms the scope and costs.',
                    ],
                    1 => [
                        'icon' => 'vaccination',
                        'title' => 'Veterinary & Health Documents',
                        'desc' => 'We coordinate required veterinary checks, vaccinations, health records, and rabies-titer steps for your route.',
                    ],
                    2 => [
                        'icon' => 'microchip',
                        'title' => 'Microchip Verification',
                        'desc' => 'We check the available microchip details against the requirements for your route and flag anything to confirm.',
                    ],
                    3 => [
                        'icon' => 'airport-arrival',
                        'title' => 'Airport Pickup',
                        'desc' => 'Waggies arranges airport pickup for imports. Route feasibility, provider availability, and related costs are confirmed in your quote.',
                    ],
                    4 => [
                        'icon' => 'home',
                        'title' => 'Post-Arrival Care',
                        'desc' => 'Boarding or veterinary care after arrival may be available by request and is confirmed with your service enquiry.',
                    ],
                    5 => [
                        'icon' => 'coordinator',
                        'title' => 'Request Review',
                        'desc' => 'The team reviews your route and timing, then explains the available service options and next steps.',
                    ],
                ],
                'processHeading' => [
                    'eyebrow' => 'How It Works',
                    'title' => 'A clear path to arrival',
                    'subtitle' => 'We review your route and timing before confirming the next steps for your request.',
                ],
                'processSteps' => [
                    0 => [
                        'step' => '01',
                        'title' => 'Confirm entry requirements',
                        'description' => 'We review your destination, timeline, and the documents needed before your pet travels to Nigeria.',
                    ],
                    1 => [
                        'step' => '02',
                        'title' => 'Prepare permits and health records',
                        'description' => 'We coordinate import permits, vaccination and health records, microchip checks, and required veterinary documents for your route.',
                    ],
                    2 => [
                        'step' => '03',
                        'title' => 'Coordinate arrival',
                        'description' => 'We coordinate customs clearance and Abuja airport pickup. Airport pickup is not door-to-door delivery; any post-arrival care is arranged separately.',
                    ],
                ],
                'cta' => [
                    'eyebrow' => 'Travel planning',
                    'heading' => 'Ready to start your pet\'s import process?',
                    'body' => 'Start as early as you can. Permit, veterinary, and destination requirements vary by route and can take time. Share your destination and travel date so the team can review the next steps.',
                    'primaryLabel' => 'Request Quote',
                    'primaryRoute' => 'book',
                    'primaryParams' => [
                        'service' => 'relocation',
                        'direction' => 'import',
                    ],
                    'secondaryLabel' => 'View Checklist',
                    'secondaryRoute' => 'relocation.checklist',
                ],
            ],
            'export' => [
                'metaTitle' => 'Pet Export from Nigeria',
                'description' => 'Planning to travel abroad with your pet? Waggies coordinates route-specific documents, crate and airline arrangements, and transport to Abuja Airport (ABV). Request a quote.',
                'ogTitle' => 'Export Pets from Nigeria - Waggies Abuja',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/export/hero.jpg',
                    'imageAlt' => 'Dog prepared for international export',
                    'eyebrow' => 'Pet Export · Nigeria',
                    'title' => 'Moving Abroad<br/>with Your Pet?',
                    'description' => 'Export requirements vary by destination, airline, and travel date. Share your plans with us so we can confirm the documents and arrangements for your pet’s journey.',
                    'actions' => [
                        0 => [
                            'label' => 'Request Quote',
                            'route' => 'book',
                            'params' => [
                                'service' => 'relocation',
                                'direction' => 'export',
                            ],
                            'icon' => 'arrow-forward',
                        ],
                        1 => [
                            'label' => 'View Checklist',
                            'route' => 'relocation.checklist',
                        ],
                    ],
                ],
                'sectionHeading' => [
                    'eyebrow' => 'Export Process',
                    'title' => 'Export Support We Can Coordinate',
                    'subtitle' => 'We coordinate export permits, veterinary documents, crate and airline arrangements, customs clearance, and departure transport, with route-specific scope confirmed in your quote.',
                ],
                'features' => [
                    0 => [
                        'icon' => 'document',
                        'title' => 'Veterinary & Health Documents',
                        'desc' => 'We coordinate required veterinary checks, health certificates, and export endorsements for your destination.',
                    ],
                    1 => [
                        'icon' => 'vaccination',
                        'title' => 'Vaccination Compliance',
                        'desc' => 'We check vaccination records against destination requirements and coordinate any required veterinary steps.',
                    ],
                    2 => [
                        'icon' => 'supplies',
                        'title' => 'Crate Planning',
                        'desc' => 'We coordinate crate sizing and sourcing based on your pet, route, and airline requirements.',
                    ],
                    3 => [
                        'icon' => 'airport-departure',
                        'title' => 'Airline Booking',
                        'desc' => 'Waggies coordinates airline booking and transport to the departure airport. Carrier, provider availability, and costs are confirmed in your quote.',
                    ],
                    4 => [
                        'icon' => 'microchip',
                        'title' => 'Microchip & Travel Documents',
                        'desc' => 'For export requests, Waggies arranges microchipping if no chip is found; existing chips are scanned and recorded. Passport documents are prepared where required.',
                    ],
                    5 => [
                        'icon' => 'coordinator',
                        'title' => 'Destination Guidance',
                        'desc' => 'We help you identify destination entry requirements to review before confirming travel arrangements.',
                    ],
                ],
                'processHeading' => [
                    'eyebrow' => 'How It Works',
                    'title' => 'A calmer route to departure',
                    'subtitle' => 'We review the destination, timeline, and arrangements available for your pet\'s journey.',
                ],
                'processSteps' => [
                    0 => [
                        'step' => '01',
                        'title' => 'Plan the route',
                        'description' => 'We review the destination rules, airline options, and the lead time needed for your travel date.',
                    ],
                    1 => [
                        'step' => '02',
                        'title' => 'Complete vet and travel documents',
                        'description' => 'We coordinate veterinary checks, health certificates, and export documents for your destination requirements.',
                    ],
                    2 => [
                        'step' => '03',
                        'title' => 'Prepare for departure',
                        'description' => 'We coordinate the crate, airline booking, and departure airport transport. Carrier, provider availability, and costs are confirmed in your quote.',
                    ],
                ],
                'cta' => [
                    'eyebrow' => 'Travel planning',
                    'heading' => 'Planning an international move from Nigeria?',
                    'body' => 'Preparation time and documents vary by destination and airline. Contact the team early with your route and travel date so current requirements can be reviewed.',
                    'primaryLabel' => 'Request Quote',
                    'primaryRoute' => 'book',
                    'primaryParams' => [
                        'service' => 'relocation',
                        'direction' => 'export',
                    ],
                    'secondaryLabel' => 'View Checklist',
                    'secondaryRoute' => 'relocation.checklist',
                ],
            ],
            'checklist' => [
                'metaTitle' => 'Pet Relocation Checklist',
                'description' => 'A practical checklist for planning your pet\'s international relocation around your destination requirements and travel date.',
                'ogTitle' => 'Pet Relocation Checklist - Waggies',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/checklist/hero.jpg',
                    'imageAlt' => 'Pet relocation checklist',
                    'eyebrow' => 'Relocation Checklist',
                    'eyebrowIcon' => 'checklist',
                    'title' => 'Your Pet Relocation Checklist',
                    'description' => 'Start early. Permit and veterinary requirements vary by route and can take time to complete.',
                ],
                'phases' => [
                    0 => [
                        'heading' => 'Start Planning',
                        'icon' => 'calendar',
                        'items' => [
                            0 => 'Research your destination country\'s pet entry requirements',
                            1 => 'Confirm your pet\'s microchip details against your route requirements',
                            2 => 'Check vaccination and rabies requirements for your route',
                            3 => 'Contact Waggies to review the relocation request',
                            4 => 'Check airline pet policies before booking travel',
                        ],
                    ],
                    1 => [
                        'heading' => 'Arrange Permits and Veterinary Checks',
                        'icon' => 'document',
                        'items' => [
                            0 => 'Coordinate the required import or export permits with Waggies',
                            1 => 'Arrange any required health examination with a veterinarian',
                            2 => 'Ask Waggies about crate arrangements that meet your airline\'s requirements',
                            3 => 'Confirm destination country quarantine requirements',
                            4 => 'Consider travel insurance for your pet',
                        ],
                    ],
                    2 => [
                        'heading' => 'Confirm Travel and Prepare Documents',
                        'icon' => 'checklist',
                        'items' => [
                            0 => 'Confirm which veterinary health certificates and endorsements your route requires',
                            1 => 'Check that vaccinations meet the requirements for your route and travel date',
                            2 => 'Confirm the airline booking and travel arrangements with Waggies',
                            3 => 'Prepare a comfort pack with familiar items and food for travel',
                            4 => 'Confirm crate sizing against your airline and route requirements',
                        ],
                    ],
                    3 => [
                        'heading' => 'Day of Travel',
                        'icon' => 'airport-departure',
                        'items' => [
                            0 => 'Follow your airline\'s airport arrival and cargo check-in instructions',
                            1 => 'Carry all original documents in your hand luggage',
                            2 => 'Follow your veterinarian\'s and airline\'s guidance on feeding before travel',
                            3 => 'Attach a "Live Animal" label and your contact details to the crate',
                            4 => 'Confirm any arrival collection with the destination contact; Waggies does not offer door-to-door delivery',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function detail(string $type): View
    {
        $page = $this->relocationPages()[$type];
        $page['hero']['imageSrc'] = "/media/services/relocation/{$type}/hero.jpg";
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $route = "relocation.{$type}";
        $titleSuffix = in_array($type, ['import', 'export'], true) ? ' | Waggies' : ' - Waggies';
        $metadata = ['title' => "{$page['metaTitle']}{$titleSuffix}", 'description' => $page['description'], 'canonical' => route($route), 'ogTitle' => $page['ogTitle'], 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($page['ogTitle'], $metadata)]);

        return view("pages.relocation.{$type}", $metadata + [
            'navSection' => 'services',
            'page' => $page,
            'faqs' => $this->faqs($type),
        ]);
    }

    private function faqs(?string $subcategory = null): array
    {
        $query = Faq::query()
            ->published()
            ->where('category', 'relocation');

        if ($subcategory !== null) {
            $query->where(function ($query) use ($subcategory): void {
                $query
                    ->whereNull('subcategory')
                    ->orWhere('subcategory', $subcategory);
            });
        } else {
            $query->where(function ($query): void {
                $query
                    ->whereNull('subcategory')
                    ->orWhereIn('subcategory', ['import', 'export']);
            });
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
    }

    private function serviceFaqs(): array
    {
        return $this->faqs();
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
