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
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('guides.index', $request->query(), 301);
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
        $article = $articleRecord->toPublicArray("/media/knowledge-base/{$articleRecord->slug}/cover.jpg");

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
            ->map(static fn (KnowledgeArticle $relatedArticle): array => $relatedArticle->toPublicArray("/media/knowledge-base/{$articleRecord->slug}/related-{$relatedArticle->slug}.jpg") + ['contentType' => 'Answer'])
            ->all();

        $isIndexable = $articleRecord->isIndexable();
        $canonical = $isIndexable ? route('knowledge-base.show', ['slug' => $articleRecord->slug]) : null;
        $description = $articleRecord->seo_description ?: $articleRecord->excerpt;
        $metadata = [
            'title' => $articleRecord->seo_title ?: $articleRecord->title.' - Waggies Pet Handbook - Waggies',
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
                ->description($articleRecord->excerpt)
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
