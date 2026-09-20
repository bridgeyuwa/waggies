<?php

namespace App\Support;

final class PublicUrlCatalog
{
    /**
     * Return the canonical, public URLs intentionally published to crawlers.
     *
     * @return array<int, string>
     */
    public function urls(): array
    {
        $staticRoutes = [
            'home', 'services.index', 'services.boarding',
            'services.grooming', 'services.vet-care', 'services.training', 'services.pricing',
            'services.relocation', 'relocation.import', 'relocation.export',
            'relocation.transport', 'relocation.checklist', 'about', 'about.testimonials',
            'about.gallery', 'about.careers', 'about.partnerships', 'guides.index',
            'knowledge-base.index', 'tools.index', 'tools.symptom-checker', 'tools.pet-age',
            'tools.vaccination', 'tools.cost', 'tools.medication', 'tools.nutrition',
            'tools.emergency', 'tools.new-pet-checklist', 'tools.parasite', 'tools.behavior-tips',
            'tools.breed-finder', 'faq', 'shop.index', 'contact', 'loyalty', 'privacy-policy',
            'terms-of-service', 'cookies-policy',
        ];

        $urls = array_map(static fn (string $route): string => route($route), $staticRoutes);

        foreach (['dogs', 'cats', 'exotic'] as $species) {
            $urls[] = route('services.boarding.species', ['species' => $species]);
        }

        foreach (config('waggies_guides.items', []) as $guide) {
            $urls[] = route('guides.show', ['slug' => $guide['slug']]);
        }

        foreach (config('waggies_knowledge_base.items', []) as $article) {
            $urls[] = route('knowledge-base.show', ['slug' => $article['slug']]);
        }

        foreach (array_values(config('waggies_shop.products', [])) as $product) {
            $urls[] = route('shop.show', ['id' => $product['id']]);
        }

        return array_values(array_unique($urls));
    }
}
