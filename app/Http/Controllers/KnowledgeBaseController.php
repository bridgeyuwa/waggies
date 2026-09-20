<?php

namespace App\Http\Controllers;

use App\Support\ArticleBodyProcessor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class KnowledgeBaseController extends Controller
{
    public function index(Request $request): View
    {
        $items = config('waggies_knowledge_base.items', []);
        $categories = config('waggies_knowledge_base.categories', []);
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

    public function show(string $slug): View
    {
        $article = collect(config('waggies_knowledge_base.items', []))
            ->first(static fn (array $item): bool => $item['slug'] === $slug);

        abort_if($article === null, 404);

        $articleBody = ArticleBodyProcessor::process($article['content']);

        $related = collect(config('waggies_knowledge_base.items', []))
            ->filter(static fn (array $candidate): bool => $candidate['category'] === $article['category'] && $candidate['id'] !== $article['id'])
            ->take(3)
            ->values()
            ->all();

        $metadata = [
            'title' => $article['title'].' - Waggies Knowledge Base - Waggies',
            'description' => $article['excerpt'],
            'canonical' => route('knowledge-base.show', ['slug' => $article['slug']]),
            'ogTitle' => $article['title'],
            'ogDescription' => $article['excerpt'],
            'ogImage' => $article['image'],
            'ogType' => 'article',
        ];
        $articleSchema = Schema::article()
            ->headline($article['title'])
            ->description($article['excerpt'])
            ->url($metadata['canonical'])
            ->publisher(Schema::organization()->name('Waggies')->url(route('home')))
            ->image($article['image']);
        if (! empty($article['author'])) {
            $articleSchema->author(Schema::person()->name($article['author']));
        }
        if (! empty($article['date'])) {
            $articleSchema->datePublished($article['date']);
        }
        $this->setPageHead($metadata, [$articleSchema->toArray()]);

        return view('pages.knowledge-base.show', $metadata + [
            'navSection' => 'resources',
            'article' => $article,
            'related' => $related,
            'headings' => $articleBody['headings'],
            'processedContent' => $articleBody['content'],
        ]);
    }
}
