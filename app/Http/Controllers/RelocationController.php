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
