<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class RelocationController extends Controller
{
    public function index(): View
    {
        $metadata = ['title' => 'Pet Relocation Services - Waggies', 'description' => 'Custom-quoted pet import and export coordination for dogs and cats, including document, veterinary, airline, and airport coordination.', 'canonical' => route('services.relocation'), 'ogTitle' => 'Pet Relocation Services Abuja - Waggies', 'ogDescription' => 'Custom-quoted pet import and export coordination for dogs and cats, including document, veterinary, airline, and airport coordination.'];
        $this->setPageHead($metadata, [$this->serviceSchema('Pet Relocation Services Abuja - Waggies', $metadata)]);

        return view('pages.services.relocation', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/services/relocation/hero.jpg', 'imageAlt' => 'International pet relocation service by Waggies'],
            'cards' => [
                ['title' => 'Pet Import to Nigeria', 'description' => 'Permits, health clearance, rabies titer verification, and Abuja airport pickup.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Export permits, rabies titers, IATA crates, and airline coordination.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
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
                'metaTitle' => 'Import a Pet to Nigeria',
                'description' => 'Full-service pet import to Nigeria — permits, health certificates, microchip verification, and airport collection in Abuja.',
                'ogTitle' => 'Import Pets to Nigeria - Waggies Abuja',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/import/hero.jpg',
                    'imageAlt' => 'Pet prepared for import travel',
                    'eyebrow' => 'Pet Import · Nigeria',
                    'title' => 'Bringing Your Pet<br/>to Nigeria?',
                    'description' => 'We manage every import requirement  -  from advance permits and health certificates to airport collection and quarantine coordination.',
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
                    'title' => 'What We Handle<br/>for You',
                    'subtitle' => 'Importing a pet to Nigeria involves strict documentation and coordination. We take care of every step.',
                ],
                'features' => [
                    0 => [
                        'icon' => 'document',
                        'title' => 'Import Permit',
                        'desc' => 'We obtain the required import permits from Nigerian veterinary and customs authorities on your behalf.',
                    ],
                    1 => [
                        'icon' => 'vaccination',
                        'title' => 'Health & Vaccination Checks',
                        'desc' => 'Review of your pet\'s vaccination records to confirm compliance with Nigerian import requirements.',
                    ],
                    2 => [
                        'icon' => 'microchip',
                        'title' => 'Microchip Verification',
                        'desc' => 'Confirmation that your pet\'s microchip meets the ISO standard required for entry.',
                    ],
                    3 => [
                        'icon' => 'airport-arrival',
                        'title' => 'Airport Collection',
                        'desc' => 'Our team collects your pet from the cargo terminal and handles all on-arrival documentation.',
                    ],
                    4 => [
                        'icon' => 'home',
                        'title' => 'Post-Arrival Care',
                        'desc' => 'Optional post-arrival boarding and health check while your pet settles into their new home.',
                    ],
                    5 => [
                        'icon' => 'coordinator',
                        'title' => 'Dedicated Coordinator',
                        'desc' => 'One point of contact throughout  -  keeping you informed every step of the way.',
                    ],
                ],
                'processHeading' => [
                    'eyebrow' => 'How It Works',
                    'title' => 'A clear path to arrival',
                    'subtitle' => 'We keep the moving parts visible, with a named next step at every stage.',
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
                        'description' => 'We coordinate import permits, vaccination records, microchip checks, and the required veterinary paperwork.',
                    ],
                    2 => [
                        'step' => '03',
                        'title' => 'Coordinate arrival',
                        'description' => 'We align airport collection, customs handling, and optional post-arrival care around your arrival plan.',
                    ],
                ],
                'cta' => [
                    'eyebrow' => 'Timeline assurance',
                    'heading' => 'Ready to start your pet\'s import process?',
                    'body' => 'We recommend reaching out at least 4-6 weeks before your intended travel date to ensure all Ministry import permits and rabies titer documentation are processed smoothly.',
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
                'metaTitle' => 'Export a Pet from Nigeria',
                'description' => 'Full-service pet export from Nigeria — health certificates, IATA crates, export permits, and airline coordination.',
                'ogTitle' => 'Export Pets from Nigeria - Waggies Abuja',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/export/hero.jpg',
                    'imageAlt' => 'Dog prepared for international export',
                    'eyebrow' => 'Pet Export · Nigeria',
                    'title' => 'Moving Abroad<br/>with Your Pet?',
                    'description' => 'We manage health certificates, export permits, IATA-approved crates, and airline coordination for a smooth international departure.',
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
                    'title' => 'Everything Covered<br/>Before Departure',
                    'subtitle' => 'Exporting a pet from Nigeria requires careful planning. We handle it all so your pet travels safely.',
                ],
                'features' => [
                    0 => [
                        'icon' => 'document',
                        'title' => 'Export Health Certificate',
                        'desc' => 'Our vet issues an official government-endorsed health certificate valid for international travel.',
                    ],
                    1 => [
                        'icon' => 'vaccination',
                        'title' => 'Vaccination Compliance',
                        'desc' => 'Review and update of vaccinations to meet the destination country\'s entry requirements.',
                    ],
                    2 => [
                        'icon' => 'supplies',
                        'title' => 'IATA-Approved Crate',
                        'desc' => 'Supply and sizing of an IATA-compliant travel crate appropriate for your pet and the airline.',
                    ],
                    3 => [
                        'icon' => 'airport-departure',
                        'title' => 'Airline Booking',
                        'desc' => 'We liaise with the airline to book your pet as cabin or cargo, and manage all airline paperwork.',
                    ],
                    4 => [
                        'icon' => 'microchip',
                        'title' => 'Microchip & Passport',
                        'desc' => 'Verification of microchip and preparation of a pet passport where required by the destination.',
                    ],
                    5 => [
                        'icon' => 'coordinator',
                        'title' => 'Destination Guidance',
                        'desc' => 'Guidance on entry requirements at your destination country  -  so there are no surprises on arrival.',
                    ],
                ],
                'processHeading' => [
                    'eyebrow' => 'How It Works',
                    'title' => 'A calmer route to departure',
                    'subtitle' => 'Your documents, crate, and airline handoff are coordinated against the travel date.',
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
                        'description' => 'We coordinate health certification, vaccination checks, microchip records, and export paperwork.',
                    ],
                    2 => [
                        'step' => '03',
                        'title' => 'Prepare for departure',
                        'description' => 'We arrange the IATA-compliant crate, airline booking, airport handoff, and destination guidance.',
                    ],
                ],
                'cta' => [
                    'eyebrow' => 'Global compliance',
                    'heading' => 'Planning an international move from Nigeria?',
                    'body' => 'Different destinations require varying preparation windows — for example, the UK/EU require a rabies blood titer test done 3+ months prior. Contact our team early to stay on schedule.',
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
                'description' => 'Comprehensive step-by-step checklist for relocating your pet internationally - from 3 months out to the day of travel.',
                'ogTitle' => 'Pet Relocation Checklist - Waggies',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/checklist/hero.jpg',
                    'imageAlt' => 'Pet relocation checklist',
                    'eyebrow' => 'Relocation Checklist',
                    'eyebrowIcon' => 'checklist',
                    'title' => 'Your Pet Relocation Checklist',
                    'description' => 'Stay on top of every requirement. Start early  -  some steps can take weeks to complete.',
                ],
                'phases' => [
                    0 => [
                        'heading' => '3+ Months Before Travel',
                        'icon' => 'calendar',
                        'items' => [
                            0 => 'Research the destination country\'s pet import requirements',
                            1 => 'Confirm your pet is microchipped (ISO 11784/11785 standard)',
                            2 => 'Check vaccination requirements  -  especially rabies',
                            3 => 'Contact Waggies to begin the relocation process',
                            4 => 'Book your travel and confirm airline pet policies',
                        ],
                    ],
                    1 => [
                        'heading' => '6-8 Weeks Before Travel',
                        'icon' => 'document',
                        'items' => [
                            0 => 'Apply for export permit from the Nigerian Federal Department of Livestock',
                            1 => 'Book health examination with our on-site vet',
                            2 => 'Order an IATA-approved travel crate (allow time for crate-training)',
                            3 => 'Confirm destination country quarantine requirements',
                            4 => 'Arrange travel insurance for your pet',
                        ],
                    ],
                    2 => [
                        'heading' => '2-4 Weeks Before Travel',
                        'icon' => 'checklist',
                        'items' => [
                            0 => 'Complete official veterinary health certificate (signed and endorsed)',
                            1 => 'Confirm all vaccinations are within the required validity windows',
                            2 => 'Confirm airline booking  -  cargo or cabin  -  with airline reference',
                            3 => 'Prepare a comfort pack: familiar toy, blanket, and food for travel',
                            4 => 'Check crate sizing meets airline and IATA requirements',
                        ],
                    ],
                    3 => [
                        'heading' => 'Day of Travel',
                        'icon' => 'airport-departure',
                        'items' => [
                            0 => 'Arrive at the airport early  -  cargo check-in takes longer than passenger',
                            1 => 'Carry all original documents in your hand luggage',
                            2 => 'Do not feed your pet within 4 hours of the flight (unless advised otherwise)',
                            3 => 'Attach a "Live Animal" label and your contact details to the crate',
                            4 => 'Confirm collection arrangements at the destination',
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
        $metadata = ['title' => $page['metaTitle'].' - Waggies', 'description' => $page['description'], 'canonical' => route($route), 'ogTitle' => $page['ogTitle'], 'ogDescription' => $page['description']];
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
