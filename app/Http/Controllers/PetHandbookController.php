<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\KnowledgeArticle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class PetHandbookController extends Controller
{
    public function index(Request $request): View
    {
        $guides = Guide::query()
            ->with('media')
            ->published()
            ->orderBy('id')
            ->get()
            ->map(static fn (Guide $guide): array => $guide->toPublicArray("/media/guides/card-{$guide->slug}.jpg") + [
                'contentType' => 'Guide',
                'routeName' => 'guides.show',
            ])
            ->all();

        $answers = KnowledgeArticle::query()
            ->with('media')
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(static fn (KnowledgeArticle $article): array => $article->toPublicArray("/media/knowledge-base/card-{$article->slug}.jpg") + [
                'contentType' => 'Answer',
                'routeName' => 'knowledge-base.show',
            ])
            ->all();

        $items = [...$guides, ...$answers];
        usort($items, static function (array $left, array $right): int {
            $categoryOrder = strcasecmp($left['category'], $right['category']);

            if ($categoryOrder !== 0) {
                return $categoryOrder;
            }

            return strcasecmp($left['title'], $right['title']);
        });
        $categories = array_values(array_unique(array_column($items, 'category')));
        sort($categories, SORT_NATURAL | SORT_FLAG_CASE);
        $requestedCategory = $request->string('category')->toString();
        $category = in_array($requestedCategory, $categories, true) ? $requestedCategory : 'All';
        $filteredItems = $category === 'All'
            ? $items
            : array_values(array_filter(
                $items,
                static fn (array $item): bool => $item['category'] === $category,
            ));
        $perPage = 9;
        $totalPages = max(1, (int) ceil(count($filteredItems) / $perPage));
        $requestedPage = max(1, $request->integer('page', 1));
        $currentPage = min($requestedPage, $totalPages);

        $metadata = [
            'title' => 'Waggies Pet Handbook | Pet Care Guides & Answers',
            'description' => 'Clear answers and practical guides for caring for dogs and cats.',
            'canonical' => route('guides.index'),
            'ogTitle' => 'Waggies Pet Handbook | Pet Care Guides & Answers',
            'ogDescription' => 'Clear answers and practical guides for caring for dogs and cats.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata, [Schema::collectionPage()->name('Waggies Pet Handbook')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.guides.index', $metadata + [
            'navSection' => 'resources',
            'categories' => $categories,
            'items' => $items,
            'filterItems' => array_map(static fn (array $item): array => [
                'title' => $item['title'],
                'excerpt' => $item['excerpt'],
                'category' => $item['category'],
            ], $items),
            'initialCategory' => $category,
            'initialSearch' => $request->string('q')->toString(),
            'currentPage' => $currentPage,
        ]);
    }
}
