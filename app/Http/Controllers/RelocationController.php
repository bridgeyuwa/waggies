<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class RelocationController extends Controller
{
    public function index(): View
    {
        $metadata = [
            'title' => 'Pet Relocation Import & Export - Waggies',
            'description' => 'Waggies coordinates custom-quoted import and export relocation requests for dogs and cats, including airline, airport, veterinary, and documentation coordination.',
            'canonical' => route('services.relocation'),
            'ogTitle' => 'Pet Relocation Import & Export - Waggies',
            'ogDescription' => 'Request a manually reviewed relocation plan for a dog or cat travelling into or out of Nigeria.',
        ];

        $this->setPageHead($metadata, [$this->serviceSchema('Pet Relocation', $metadata)]);

        return view('pages.services.relocation', $metadata + [
            'navSection' => 'services',
            'hero' => [
                'imageSrc' => '/media/services/relocation/hero.jpg',
                'imageAlt' => 'Pet travel preparation for relocation',
            ],
            'cards' => [
                ['title' => 'Pet Import', 'description' => 'Bring a dog or cat into Nigeria with route and document coordination.', 'route' => 'relocation.import', 'imageSrc' => '/media/services/relocation/card-import.jpg', 'imageAlt' => 'Pet arriving for import', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export', 'description' => 'Move a dog or cat abroad with veterinary, airline, and airport coordination.', 'route' => 'relocation.export', 'imageSrc' => '/media/services/relocation/card-export.jpg', 'imageAlt' => 'Pet prepared for export', 'icon' => 'airport-departure'],
            ],
            'faqs' => $this->faqs(),
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
        $metadata = [
            'title' => 'Pet Relocation Checklist - Waggies',
            'description' => 'A practical checklist for planning a dog or cat import or export relocation request.',
            'canonical' => route('relocation.checklist'),
            'ogTitle' => 'Pet Relocation Checklist - Waggies',
            'ogDescription' => 'Prepare the information and documents Waggies will need to review your pet relocation request.',
        ];

        $this->setPageHead($metadata);

        return view('pages.relocation.checklist', $metadata + [
            'navSection' => 'services',
            'page' => [
                'hero' => [
                    'eyebrow' => 'RELOCATION PLANNING',
                    'title' => 'Prepare for Your Pet\'s Move',
                    'description' => 'Use this checklist to gather the route, pet, document, airline, airport, and pickup details Waggies needs for a manual review.',
                    'imageSrc' => '/media/services/relocation/checklist/hero.jpg',
                    'imageAlt' => 'Pet relocation planning checklist',
                ],
                'phases' => [
                    ['icon' => 'calendar', 'heading' => 'Start with the route', 'items' => ['Choose import or export.', 'Share origin, destination, and preferred travel date.', 'Tell us about timing constraints and airport details.']],
                    ['icon' => 'medical', 'heading' => 'Prepare pet information', 'items' => ['Confirm that the pet is a dog or cat.', 'Share existing microchip status and available health records.', 'Tell us about handling, health, and special-care needs.']],
                    ['icon' => 'document', 'heading' => 'Gather original documents', 'items' => ['Keep original documents with the owner.', 'Share what is ready, in progress, or unclear.', 'Waggies can coordinate and facilitate required steps with third-party providers.']],
                    ['icon' => 'airport-departure', 'heading' => 'Plan the travel handoff', 'items' => ['Share airline, crate, airport, pickup, and destination details when known.', 'Allow time for veterinary steps, document review, and airline confirmation.', 'Wait for Waggies to confirm feasibility and the custom quote before committing to non-refundable bookings.']],
                ],
            ],
        ]);
    }

    private function detail(string $type): View
    {
        $isImport = $type === 'import';
        $title = $isImport ? 'Pet Import to Nigeria' : 'Pet Export from Nigeria';
        $route = $isImport ? 'relocation.import' : 'relocation.export';
        $metadata = [
            'title' => $title.' - Waggies',
            'description' => ($isImport ? 'Request' : 'Plan').' a custom-quoted dog or cat relocation with Waggies coordination for airline, airport, veterinary, and documentation steps.',
            'canonical' => route($route),
            'ogTitle' => $title.' - Waggies',
            'ogDescription' => 'Relocation requests are manually reviewed and custom quoted before Waggies commits to third-party bookings.',
        ];

        $this->setPageHead($metadata, [$this->serviceSchema($title, $metadata)]);

        $direction = $isImport ? 'into Nigeria' : 'out of Nigeria';

        return view('pages.relocation.'.$type, $metadata + [
            'navSection' => 'services',
            'page' => [
                'hero' => [
                    'eyebrow' => $isImport ? 'PET IMPORT' : 'PET EXPORT',
                    'title' => $title,
                    'description' => 'A request-led relocation service for dogs and cats travelling '.$direction.'. Waggies reviews the route, timing, documents, and third-party requirements before confirming a custom quote.',
                    'imageSrc' => $isImport ? '/media/services/relocation/import/hero.jpg' : '/media/services/relocation/export/hero.jpg',
                    'imageAlt' => $isImport ? 'Pet arriving for import' : 'Pet prepared for export',
                    'actions' => [['label' => 'Submit Booking Request', 'url' => route('book', ['service' => 'relocation', 'variant' => $type]), 'icon' => 'arrow-forward']],
                ],
                'sectionHeading' => [
                    'eyebrow' => 'WHAT WAGGIES CAN COORDINATE',
                    'title' => $isImport ? 'A Clear Import Review' : 'A Clear Export Review',
                    'subtitle' => 'The owner supplies original documents. Waggies helps coordinate and facilitate the required process with the relevant providers.',
                ],
                'features' => [
                    ['icon' => 'airport-departure', 'title' => 'Airline booking coordination', 'desc' => 'Waggies can coordinate airline details as part of the relocation request.'],
                    ['icon' => 'airport-arrival', 'title' => 'Airport transfers', 'desc' => 'Transport to or from the departure or arrival airport is handled only as part of the relocation request.'],
                    ['icon' => 'document', 'title' => 'Documents and veterinary steps', 'desc' => 'Waggies helps coordinate and facilitate required documentation and veterinary steps.'],
                    ['icon' => 'microchip', 'title' => 'Microchip checks or implantation', 'desc' => $isImport ? 'Microchipping is included only when required by the destination or route.' : 'If no chip exists, microchipping is included by default; existing chips are scanned and recorded.'],
                    ['icon' => 'partnership', 'title' => 'Third-party coordination', 'desc' => 'We coordinate with airlines, airports, veterinarians, and other relevant providers.'],
                ],
                'processHeading' => [
                    'eyebrow' => 'MANUAL REVIEW',
                    'title' => 'From first details to a confirmed plan',
                    'subtitle' => 'Relocation is custom quoted and remains subject to route feasibility and provider confirmation.',
                ],
                'processSteps' => [
                    ['step' => '01', 'title' => 'Share the route', 'description' => 'Tell us the direction, origin, destination, travel date, pet details, and any known airline or airport information.'],
                    ['step' => '02', 'title' => 'Review the requirements', 'description' => 'We review documentation, veterinary steps, chip status, timing, and third-party constraints with you.'],
                    ['step' => '03', 'title' => 'Receive an itemized quote', 'description' => 'Where practical, the quote separates airline, third-party, veterinary, crate, airport-transfer, and Waggies coordination costs.'],
                    ['step' => '04', 'title' => 'Confirm and coordinate', 'description' => 'Relocation commitments and non-refundable bookings proceed only after the required deposit and confirmation.'],
                ],
                'cta' => [
                    'heading' => 'Ready to plan the move?',
                    'headingAccent' => 'Start with a request',
                    'body' => 'Send the route and pet details. Waggies will review feasibility before confirming the next step.',
                    'primaryLabel' => 'Submit Booking Request',
                    'primaryRoute' => 'book',
                    'primaryParams' => ['service' => 'relocation', 'variant' => $type],
                    'secondaryLabel' => 'Open Relocation Policy',
                    'secondaryRoute' => 'relocation-policy',
                    'secondaryIcon' => 'document',
                ],
            ],
            'faqs' => $this->faqs($type),
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function faqs(?string $subcategory = null): array
    {
        return Faq::query()
            ->published()
            ->where('category', 'relocation')
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
