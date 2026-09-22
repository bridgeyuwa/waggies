<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeArticle;
use App\Models\KnowledgeArticleSlugHistory;
use App\Support\ArticleBodyProcessor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class KnowledgeBaseController extends Controller
{
    public function index(Request $request): View
    {
        $items = KnowledgeArticle::query()
            ->with('media')
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(static fn (KnowledgeArticle $article): array => $article->toPublicArray())
            ->all();
        $categories = array_values(array_unique(array_column($items, 'category')));
        $requestedCategory = $request->string('category')->toString();
        $category = in_array($requestedCategory, $categories, true) ? $requestedCategory : 'All';
        $requestedPage = max(1, $request->integer('page', 1));

        $metadata = [
            'title' => 'Knowledge Base - Pet Care Answers - Waggies',
            'description' => 'Quick answers to common questions about pet boarding, grooming, health, and general pet care from Waggies.',
            'canonical' => route('knowledge-base.index'),
            'ogTitle' => 'Knowledge Base - Pet Care Answers - Waggies',
            'ogDescription' => 'Quick answers to common questions about pet boarding, grooming, health, and general pet care from Waggies.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata, [Schema::collectionPage()->name('Knowledge Base')->description('Pet care answers from Waggies')->url($metadata['canonical'])->toArray()]);

        return view('pages.knowledge-base.index', $metadata + [
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
            'currentPage' => $requestedPage,
        ]);
    }

    public function show(Request $request, string $slug): View|RedirectResponse
    {
        $articleRecord = KnowledgeArticle::query()
            ->with('media')
            ->published()
            ->where('slug', $slug)
            ->first();

        if ($articleRecord === null) {
            $slugHistory = KnowledgeArticleSlugHistory::query()
                ->where('slug', $slug)
                ->first();

            if ($slugHistory !== null) {
                $articleRecord = KnowledgeArticle::query()
                    ->with('media')
                    ->published()
                    ->whereKey($slugHistory->knowledge_article_id)
                    ->first();
            }

            if ($articleRecord === null) {
                abort(404);
            }

            $canonical = route('knowledge-base.show', ['slug' => $articleRecord->slug]);

            if ($request->getQueryString() !== null) {
                $canonical .= '?'.$request->getQueryString();
            }

            return redirect()->to($canonical, 308);
        }
        $article = $articleRecord->toPublicArray();

        $articleBody = ArticleBodyProcessor::process($article['content']);

        $related = KnowledgeArticle::query()
            ->with('media')
            ->published()
            ->where('category', $articleRecord->category)
            ->where('id', '!=', $articleRecord->getKey())
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take(3)
            ->get()
            ->map(static fn (KnowledgeArticle $relatedArticle): array => $relatedArticle->toPublicArray())
            ->all();

        $isIndexable = $articleRecord->isIndexable();
        $canonical = $isIndexable ? route('knowledge-base.show', ['slug' => $articleRecord->slug]) : null;
        $description = $articleRecord->seo_description ?: $articleRecord->excerpt;
        $metadata = [
            'title' => $articleRecord->seo_title ?: $articleRecord->title.' - Waggies Knowledge Base - Waggies',
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $isIndexable ? ['index', 'follow'] : ['noindex', 'follow'],
            'ogTitle' => $articleRecord->seo_title ?: $articleRecord->title,
            'ogDescription' => $description,
            'ogImage' => $article['image'],
            'ogType' => 'article',
        ];

        $schemas = [];

        if ($isIndexable) {
            $articleSchema = Schema::article()
                ->headline($articleRecord->title)
                ->description($description)
                ->url($canonical)
                ->publisher(Schema::organization()->name('Waggies')->url(route('home')))
                ->image($article['image']);
            if (! empty($articleRecord->author)) {
                $articleSchema->author(Schema::person()->name($articleRecord->author));
            }
            if ($articleRecord->published_at !== null) {
                $articleSchema->datePublished(Carbon::parse($articleRecord->published_at));
            }
            $schemas[] = $articleSchema->toArray();
        }
        $this->setPageHead($metadata, $schemas);

        return view('pages.knowledge-base.show', $metadata + [
            'navSection' => 'resources',
            'article' => $article,
            'related' => $related,
            'headings' => $articleBody['headings'],
            'processedContent' => $articleBody['content'],
        ]);
    }
}
