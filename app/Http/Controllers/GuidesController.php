<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\GuideSlugHistory;
use App\Support\ArticleBodyProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class GuidesController extends Controller
{
    public function index(Request $request): View
    {
        $items = Guide::query()
            ->with('media')
            ->published()
            ->orderBy('id')
            ->get()
            ->map(static fn (Guide $guide): array => $guide->toPublicArray("/media/guides/card-{$guide->slug}.jpg"))
            ->all();
        $categories = array_values(array_unique(array_column($items, 'category')));
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

    public function show(Request $request, string $slug): View|RedirectResponse
    {
        $guideRecord = Guide::query()
            ->with('media')
            ->published()
            ->where('slug', $slug)
            ->first();

        if ($guideRecord === null) {
            $slugHistory = GuideSlugHistory::query()
                ->where('slug', $slug)
                ->first();

            if ($slugHistory !== null) {
                $guideRecord = Guide::query()
                    ->with('media')
                    ->published()
                    ->whereKey($slugHistory->guide_id)
                    ->first();
            }

            if ($guideRecord === null) {
                abort(404);
            }

            $canonical = route('guides.show', ['slug' => $guideRecord->slug]);

            if ($request->getQueryString() !== null) {
                $canonical .= '?'.$request->getQueryString();
            }

            return redirect()->to($canonical, 308);
        }
        $guide = $guideRecord->toPublicArray("/media/guides/{$guideRecord->slug}/cover.jpg");

        $articleBody = ArticleBodyProcessor::process($guide['content']);
        $isIndexable = $guideRecord->isIndexable();
        $canonical = $isIndexable ? route('guides.show', ['slug' => $guideRecord->slug]) : null;
        $title = $guideRecord->seo_title ?: $guideRecord->title.' - Waggies Guides - Waggies';
        $description = $guideRecord->seo_description ?: $guideRecord->excerpt;

        $metadata = [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $isIndexable ? ['index', 'follow'] : ['noindex', 'follow'],
            'ogTitle' => $guideRecord->seo_title ?: $guideRecord->title,
            'ogDescription' => $description,
            'ogImage' => $guide['image'],
            'ogType' => 'article',
        ];
        $schemas = [];

        if ($isIndexable) {
            $schemas[] = Schema::article()
                ->headline($guideRecord->title)
                ->description($description)
                ->url($canonical)
                ->publisher(Schema::organization()->name('Waggies')->url(route('home')))
                ->image($guide['image'])
                ->toArray();
        }
        $this->setPageHead($metadata, $schemas);

        return view('pages.guides.show', $metadata + [
            'navSection' => 'resources',
            'guide' => $guide,
            'headings' => $articleBody['headings'],
            'processedContent' => $articleBody['content'],
        ]);
    }
}
