<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;
use Spatie\SchemaOrg\Contracts\ThingContract;
use Spatie\SchemaOrg\Schema;

final class RelocationController extends Controller
{
    public function index(): View
    {
        $metadata = ['title' => 'Pet Relocation Services - Waggies', 'description' => 'Full-service international pet import, export, and local transit in Abuja. Complete permit handling, health certificates, IATA flight crates, and customs clearance.', 'canonical' => route('services.relocation'), 'ogTitle' => 'Pet Relocation Services Abuja - Waggies', 'ogDescription' => 'Full-service international pet import, export, and local transit in Abuja. Complete permit handling, health certificates, IATA flight crates, and customs clearance.'];
        $this->setPageHead($metadata, [$this->serviceSchema('Pet Relocation Services Abuja - Waggies', $metadata)]);

        return view('pages.services.relocation', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/services/relocation/hero.jpg', 'imageAlt' => 'International pet relocation service by Waggies'],
            'cards' => [
                ['title' => 'Pet Import to Nigeria', 'description' => 'Full-service pet import into Nigeria including Ministry import permits, rabies titer verification, veterinary health clearance, and Abuja airport pickup.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Seamless international pet export matching UK, EU, US, and global destination requirements — export permits, rabies titers, IATA crates, and flight bookings.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
                ['title' => 'Local Pet Transport', 'description' => 'Air-conditioned, door-to-door pet taxi service across Abuja with trained animal handlers, IATA-approved crates, and live GPS tracking.', 'route' => 'relocation.transport', 'imageSrc' => '/media/services/relocation/card-transport.jpg', 'imageAlt' => 'Pet transport service', 'icon' => 'transport'],
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

    public function transport(): View
    {
        $page = $this->relocationPages()['transport'];
        $metadata = ['title' => $page['metaTitle'].' - Waggies', 'description' => $page['description'], 'canonical' => route('relocation.transport'), 'ogTitle' => $page['ogTitle'], 'ogDescription' => $page['description']];
        $faqSchema = array_map(static fn (array $faq): ThingContract => Schema::question()
            ->name($faq['question'])
            ->acceptedAnswer(Schema::answer()->text($faq['answer'])), $this->faqs('transport'));
        $this->setPageHead($metadata, [$this->serviceSchema($page['ogTitle'], $metadata), Schema::faqPage()->mainEntity($faqSchema)->toArray()]);

        return view('pages.relocation.transport', $metadata + [
            'navSection' => 'services',
            'page' => $page,
            'faqs' => $this->faqs('transport'),
        ]);
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
                            'label' => 'Get Estimate',
                            'route' => 'services.pricing',
                            'params' => [
                                'service' => 'relocation',
                                'tier' => 'import',
                            ],
                            'icon' => 'calculator',
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
                        'service' => 'relocation-import',
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
                            'label' => 'Get Estimate',
                            'route' => 'services.pricing',
                            'params' => [
                                'service' => 'relocation',
                                'tier' => 'export',
                            ],
                            'icon' => 'calculator',
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
                        'service' => 'relocation-export',
                    ],
                    'secondaryLabel' => 'View Checklist',
                    'secondaryRoute' => 'relocation.checklist',
                ],
            ],
            'transport' => [
                'metaTitle' => 'Local Transport',
                'description' => 'Air-conditioned, door-to-door pet transport and pickup across Abuja by trained handlers. Part of Waggies relocation services.',
                'ogTitle' => 'Local Transport Abuja - Waggies',
                'hero' => [
                    'imageSrc' => '/media/services/relocation/transport/hero.jpg',
                    'imageAlt' => 'Pet transport service at Waggies Abuja',
                ],
                'ctaText' => 'Request Transport',
                'ctaRoute' => 'book',
                'ctaParams' => [
                    'service' => 'local-transport',
                ],
                'descriptionBlock' => 'Whether it\'s a trip to Waggies for boarding or grooming, a vet visit, or an airport transfer, our trained handlers and climate-controlled vehicles ensure your pet travels safely and comfortably — door to door.',
                'features' => [
                    0 => 'Door-to-door collection and return for all Waggies appointments',
                    1 => 'Air-conditioned, climate-controlled vehicles for comfort',
                    2 => 'Approved travel crates and harnesses for secure transit',
                    3 => 'Drivers trained in animal handling and pet first aid',
                    4 => 'Coverage across all major Abuja districts',
                    5 => 'Time slots booked in advance for reliable scheduling',
                    6 => 'Airport pickup and drop-off for arriving/departing pets',
                    7 => 'Same-day transport for urgent vet visits',
                    8 => 'GPS-tracked vehicles for real-time location updates',
                    9 => 'Pet travel health certificate assistance',
                    10 => 'Multi-pet transport for households with several animals',
                    11 => 'Emergency transport available 24/7',
                ],
                'benefits' => [
                    0 => 'Trained handlers — not just drivers. Our team knows how to keep pets calm and safe during transit.',
                    1 => 'Climate-controlled vehicles keep your pet comfortable in any Abuja weather — hot or rainy.',
                    2 => 'GPS tracking means you always know exactly where your pet is during the journey.',
                    3 => 'Approved crates and harnesses ensure your pet is safe and secure — never loose in a vehicle.',
                    4 => 'We cover all major districts — Maitama, Wuse, Asokoro, Garki, Gwarinpa, and beyond.',
                    5 => 'Punctual, reliable scheduling so you can plan your day around the pickup time.',
                ],
                'packages' => [
                    0 => [
                        'name' => 'City Pet Transfer',
                        'popular' => false,
                    ],
                    1 => [
                        'name' => 'Vet Transfer',
                        'popular' => false,
                    ],
                    2 => [
                        'name' => 'Airport Transfer',
                        'popular' => true,
                    ],
                ],
                'processSteps' => [
                    0 => [
                        'step' => '01',
                        'title' => 'Share the journey details',
                        'description' => 'Tell us the pickup address, destination, pet needs, and preferred time so we can confirm the route.',
                    ],
                    1 => [
                        'step' => '02',
                        'title' => 'We collect your pet',
                        'description' => 'A trained handler arrives in a climate-controlled vehicle and completes the handoff with care.',
                    ],
                    2 => [
                        'step' => '03',
                        'title' => 'Safe arrival and update',
                        'description' => 'We deliver your pet to the destination and keep you informed throughout the transfer.',
                    ],
                ],
                'safetyStandards' => [
                    0 => [
                        'icon' => 'transport',
                        'title' => 'Climate-Controlled Fleet',
                        'desc' => 'Every vehicle is fully air-conditioned and fitted specifically for comfortable, temperature-regulated pet travel in Abuja.',
                    ],
                    1 => [
                        'icon' => 'safety',
                        'title' => 'Approved Crates & Restraints',
                        'desc' => 'Pets travel securely in IATA-approved crates or crash-tested harnesses — never unrestrained in a moving vehicle.',
                    ],
                    2 => [
                        'icon' => 'medical',
                        'title' => 'Trained Pet Handlers',
                        'desc' => 'Our drivers are experienced animal handlers trained in low-stress transport, canine behaviour, and pet first aid.',
                    ],
                    3 => [
                        'icon' => 'location',
                        'title' => 'GPS Real-Time Tracking',
                        'desc' => 'Every transport vehicle is equipped with live GPS tracking so our care team and pet parents know location status.',
                    ],
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
        $category = $subcategory === 'transport' ? 'transport' : 'relocation';

        $query = Faq::query()
            ->published()
            ->where('category', $category);

        if ($category !== 'transport' && $subcategory !== null) {
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
