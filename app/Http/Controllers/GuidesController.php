<?php

namespace App\Http\Controllers;

use App\Support\ArticleBodyProcessor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class GuidesController extends Controller
{
    public function index(Request $request): View
    {
        $items = config('waggies_guides.items', []);
        $categories = config('waggies_guides.categories', []);
        $requestedCategory = $request->string('category')->toString();
        $category = in_array($requestedCategory, $categories, true) ? $requestedCategory : null;
        $filteredItems = $category === null
            ? $items
            : array_values(array_filter(
                $items,
                static fn (array $guide): bool => $guide['category'] === $category,
            ));
        $perPage = 9;
        $totalPages = max(1, (int) ceil(count($filteredItems) / $perPage));
        $requestedPage = max(1, $request->integer('page', 1));
        $currentPage = min($requestedPage, $totalPages);

        $metadata = [
            'title' => 'Guides - Pet Care Guides - Waggies',
            'description' => 'In-depth pet care guides from Waggies covering getting started, everyday care, and training for dogs and cats.',
            'canonical' => route('guides.index'),
            'ogTitle' => 'Guides - Pet Care Guides - Waggies',
            'ogDescription' => 'In-depth pet care guides from Waggies covering getting started, everyday care, and training for dogs and cats.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata, [Schema::collectionPage()->name('Guides')->description('In-depth pet care guides from Waggies')->url($metadata['canonical'])->toArray()]);

        return view('pages.guides.index', $metadata + [
            'navSection' => 'resources',
            'categories' => $categories,
            'category' => $category,
            'initialCategory' => $category ?? 'All',
            'items' => $items,
            'filteredCount' => count($filteredItems),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
        ]);
    }

    public function show(string $slug): View
    {
        $guide = collect(config('waggies_guides.items', []))
            ->first(static fn (array $item): bool => $item['slug'] === $slug);

        abort_if($guide === null, 404);

        $articleBody = ArticleBodyProcessor::process($guide['content']);

        $metadata = [
            'title' => $guide['title'].' - Waggies Guides - Waggies',
            'description' => $guide['excerpt'],
            'canonical' => route('guides.show', ['slug' => $guide['slug']]),
            'ogTitle' => $guide['title'],
            'ogDescription' => $guide['excerpt'],
            'ogImage' => $guide['image'],
            'ogType' => 'article',
        ];
        $article = Schema::article()
            ->headline($guide['title'])
            ->description($guide['excerpt'])
            ->url($metadata['canonical'])
            ->publisher(Schema::organization()->name('Waggies')->url(route('home')))
            ->image($guide['image']);
        if (! empty($guide['date'])) {
            $article->datePublished($guide['date']);
        }
        $this->setPageHead($metadata, [$article->toArray()]);

        return view('pages.guides.show', $metadata + [
            'navSection' => 'resources',
            'guide' => $guide,
            'headings' => $articleBody['headings'],
            'processedContent' => $articleBody['content'],
        ]);
    }
}
