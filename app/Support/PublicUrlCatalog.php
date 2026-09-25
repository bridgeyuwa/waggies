<?php

namespace App\Support;

use App\Models\Guide;
use App\Models\KnowledgeArticle;
use App\Models\Product;

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
            'tools.vaccination', 'tools.nutrition',
            'tools.emergency', 'tools.new-pet-checklist', 'tools.parasite', 'tools.behavior-tips',
            'tools.breed-finder', 'faq', 'shop.index', 'contact', 'book', 'loyalty', 'privacy-policy',
            'terms-of-service', 'cookies-policy',
        ];

        $urls = array_map(static fn (string $route): string => route($route), $staticRoutes);

        foreach (['dogs', 'cats', 'exotic'] as $species) {
            $urls[] = route('services.boarding.species', ['species' => $species]);
        }

        foreach (Guide::query()->sitemapEligible()->pluck('slug') as $slug) {
            $urls[] = route('guides.show', ['slug' => $slug]);
        }

        foreach (KnowledgeArticle::query()->sitemapEligible()->orderBy('sort_order')->pluck('slug') as $slug) {
            $urls[] = route('knowledge-base.show', ['slug' => $slug]);
        }

        foreach (Product::query()->sitemapEligible()->orderBy('sort_order')->pluck('slug') as $slug) {
            $urls[] = route('shop.show', ['product' => $slug]);
        }

        return array_values(array_unique($urls));
    }
}
