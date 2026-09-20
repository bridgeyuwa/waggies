<?php

namespace App\Http\Controllers;

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
            'hero' => ['imageSrc' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1600&auto=format&fit=crop&q=80', 'imageAlt' => 'International pet relocation service by Waggies'],
            'cards' => [
                ['title' => 'Pet Import to Nigeria', 'description' => 'Full-service pet import into Nigeria including Ministry import permits, rabies titer verification, veterinary health clearance, and Abuja airport pickup.', 'route' => 'relocation.import', 'imageSrc' => 'https://images.unsplash.com/photo-1544568100-847a948585b9?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Pet Import to Nigeria', 'icon' => 'airport-arrival'],
                ['title' => 'Pet Export from Nigeria', 'description' => 'Seamless international pet export matching UK, EU, US, and global destination requirements — export permits, rabies titers, IATA crates, and flight bookings.', 'route' => 'relocation.export', 'imageSrc' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Pet Export from Nigeria', 'icon' => 'airport-departure'],
                ['title' => 'Local Pet Transport', 'description' => 'Air-conditioned, door-to-door pet taxi service across Abuja with trained animal handlers, IATA-approved crates, and live GPS tracking.', 'route' => 'relocation.transport', 'imageSrc' => 'https://images.unsplash.com/photo-1606567595334-d39972c85dbe?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Pet transport service', 'icon' => 'transport'],
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
        $page = config('waggies_relocation.transport');
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
        $page = config('waggies_relocation.checklist');
        $metadata = ['title' => $page['metaTitle'].' - Waggies', 'description' => $page['description'], 'canonical' => route('relocation.checklist'), 'ogTitle' => $page['ogTitle'], 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [Schema::webPage()->name($metadata['title'])->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.relocation.checklist', $metadata + [
            'navSection' => 'services',
            'page' => $page,
        ]);
    }

    private function detail(string $type): View
    {
        $page = config("waggies_relocation.{$type}");
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

        $faqs = array_values(array_filter(
            config('waggies_faqs'),
            static fn (array $faq): bool => $faq['category'] === $category
                && ($category === 'transport' || $subcategory === null || ! isset($faq['subcategory']) || $faq['subcategory'] === $subcategory),
        ));

        usort($faqs, static function (array $left, array $right) use ($category): int {
            $sortOrder = static function (array $faq) use ($category): int {
                if ($category === 'transport') {
                    return $faq['id'] - 42;
                }

                if (isset($faq['subcategory'])) {
                    return 10 + (($faq['id'] - 30) % 3);
                }

                return $faq['id'] - 9;
            };

            return [$sortOrder($left), $left['id']] <=> [$sortOrder($right), $right['id']];
        });

        return $faqs;
    }

    private function serviceFaqs(): array
    {
        return [
            ['question' => 'How early should I start planning a pet relocation?', 'answer' => 'We recommend contacting us at least 3 months before your travel date. Some import permits can take 6-8 weeks to process.'],
            ['question' => 'Do you handle both import and export?', 'answer' => 'Yes  -  we manage both importing pets into Nigeria and exporting them to destinations worldwide.'],
            ['question' => 'What documents are required to import a pet?', 'answer' => 'At minimum: a valid health certificate, proof of vaccinations, and an import permit from the Nigerian NAQS. Requirements vary by species and country of origin.'],
            ['question' => 'Do you provide airport pickup and drop-off for pets?', 'answer' => 'Yes  -  we offer full door-to-door relocation including airport pickup/drop-off, customs clearance handling, and post-arrival monitoring.'],
            ['question' => 'What is the Nigerian import permit process?', 'answer' => 'An import permit must be obtained from the Nigerian Agricultural Quarantine Service (NAQS) before your pet travels. This typically takes 3-6 weeks. We apply on your behalf once you engage our services.'],
            ['question' => 'What health certificate do I need to export my pet from Nigeria?', 'answer' => 'An official veterinary health certificate endorsed by NAQS is required. Our on-site vet completes the examination and paperwork. The certificate is typically valid for 10 days from issue, so timing with your travel date is important.'],
            ['question' => 'Do you help with moving pets to other Nigerian cities?', 'answer' => 'Yes  -  we coordinate domestic pet relocation across Nigeria including Lagos, Port Harcourt, Kano, and other major cities, by road or domestic air cargo.'],
            ['question' => 'Is quarantine required when bringing a pet to Nigeria?', 'answer' => 'Nigeria does not mandate a fixed quarantine period, but all arriving pets undergo an inspection at the airport by NAQS officials. Having correct documentation ensures this is straightforward.'],
            ['question' => 'Do entry requirements vary by destination country?', 'answer' => 'Yes  -  requirements vary significantly. The EU, UK, US, and other countries each have different rules on vaccinations, titre tests, and waiting periods. We research your specific destination and build a compliance plan.'],
            ['question' => 'What documents are needed for domestic pet travel in Nigeria?', 'answer' => 'A veterinary health certificate and up-to-date vaccination record are recommended for domestic travel, and required by most domestic airlines for cargo shipment.'],
            ['question' => 'Can you collect my pet directly from the airport cargo terminal?', 'answer' => 'Yes  -  airport collection from Nnamdi Azikiwe International Airport is included in our full import service. We handle all customs and NAQS clearance on arrival.'],
            ['question' => 'Can my pet travel in the cabin with me?', 'answer' => 'This depends entirely on the airline and the size of your pet. Small pets may qualify for cabin travel on some airlines. We liaise with your airline to identify the best and safest option.'],
            ['question' => 'Can you handle door-to-door domestic relocation?', 'answer' => 'Yes  -  we offer end-to-end domestic relocation including collection, health certification, transport or flight coordination, and delivery to the destination address.'],
        ];
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
