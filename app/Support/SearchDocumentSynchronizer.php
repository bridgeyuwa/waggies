<?php

namespace App\Support;

use App\Models\Faq;
use App\Models\Guide;
use App\Models\JobOpening;
use App\Models\KnowledgeArticle;
use App\Models\Product;
use App\Models\SearchDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class SearchDocumentSynchronizer
{
    public function __construct(private readonly SearchCatalog $catalog) {}

    public function rebuild(): int
    {
        SearchDocument::query()->delete();

        $count = 0;

        foreach ($this->catalog->entries() as $entry) {
            SearchDocument::query()->create([
                'source_type' => $entry['type'],
                'source_key' => $entry['key'],
                'title' => $entry['title'],
                'excerpt' => $entry['excerpt'],
                'body' => $entry['excerpt'],
                'url' => route($entry['route']),
                'section' => $entry['section'],
                'category' => $entry['category'],
                'keywords' => $entry['keywords'],
                'published_at' => now(),
                'searchable' => true,
                'boost' => $entry['boost'],
            ]);
            $count++;
        }

        foreach ($this->publicModels() as $model) {
            $this->syncModel($model);
            $count++;
        }

        return $count;
    }

    public function syncModel(Model $model): void
    {
        $document = $this->documentFor($model);

        if ($document === null) {
            return;
        }

        SearchDocument::query()->updateOrCreate(
            ['source_type' => $document['source_type'], 'source_key' => $document['source_key']],
            $document,
        );
    }

    public function removeModel(Model $model): void
    {
        $document = $this->documentFor($model);

        if ($document === null) {
            return;
        }

        SearchDocument::query()
            ->where('source_type', $document['source_type'])
            ->where('source_key', $document['source_key'])
            ->delete();
    }

    /**
     * @return array<int, Model>
     */
    private function publicModels(): array
    {
        return [
            ...Guide::query()->indexable()->get()->all(),
            ...KnowledgeArticle::query()->indexable()->get()->all(),
            ...Product::query()->indexable()->get()->all(),
            ...Faq::query()->published()->get()->all(),
            ...JobOpening::query()->open()->get()->all(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function documentFor(Model $model): ?array
    {
        if ($model instanceof Guide) {
            return $this->editorialDocument('guide', $model->getKey(), $model->title, $model->excerpt, $model->content, route('guides.show', ['slug' => $model->slug]), 'Guides', 'Guide', $model->published_at, $model->isIndexable());
        }

        if ($model instanceof KnowledgeArticle) {
            return $this->editorialDocument('knowledge-base', $model->getKey(), $model->title, $model->excerpt, $model->content, route('knowledge-base.show', ['slug' => $model->slug]), 'Knowledge Base', 'Knowledge Base', $model->published_at, $model->isIndexable());
        }

        if ($model instanceof Product) {
            $features = collect((array) $model->getAttribute('features'))->map(function (mixed $feature): string {
                return is_array($feature) ? (string) ($feature['label'] ?? '') : (string) $feature;
            })->filter()->implode(', ');

            return [
                'source_type' => 'product',
                'source_key' => (string) $model->getKey(),
                'title' => $model->name,
                'excerpt' => $model->description,
                'body' => $model->description,
                'url' => route('shop.show', ['product' => $model->slug]),
                'section' => 'Shop',
                'category' => 'Product',
                'keywords' => collect([$model->category, $features])->filter()->implode(', '),
                'published_at' => $model->published_at,
                'searchable' => $model->isIndexable(),
                'boost' => 4,
            ];
        }

        if ($model instanceof Faq) {
            return [
                'source_type' => 'faq',
                'source_key' => (string) $model->getKey(),
                'title' => $model->question,
                'excerpt' => $model->answer,
                'body' => $model->answer,
                'url' => route('faq', ['category' => $model->category]),
                'section' => 'Support',
                'category' => 'FAQ',
                'keywords' => (string) $model->category,
                'published_at' => $model->published_at,
                'searchable' => $model->isPublished(),
                'boost' => 3,
            ];
        }

        if ($model instanceof JobOpening) {
            $publishedAt = $model->getAttribute('published_at');
            $searchable = $model->status === JobOpening::STATUS_OPEN
                && ($publishedAt === null || $publishedAt->isPast());

            return [
                'source_type' => 'job',
                'source_key' => (string) $model->getKey(),
                'title' => $model->title,
                'excerpt' => $model->summary,
                'body' => collect([$model->description, $model->requirements])->filter()->implode("\n\n"),
                'url' => route('about.careers').'#open-roles',
                'section' => 'Careers',
                'category' => 'Job Opening',
                'keywords' => collect([$model->department, $model->location, $model->employment_type])->filter()->implode(', '),
                'published_at' => $model->published_at,
                'searchable' => $searchable,
                'boost' => 2,
            ];
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function editorialDocument(string $type, string|int $key, string $title, ?string $excerpt, ?string $content, string $url, string $section, string $category, mixed $publishedAt, bool $searchable): array
    {
        return [
            'source_type' => $type,
            'source_key' => (string) $key,
            'title' => $title,
            'excerpt' => $excerpt,
            'body' => Str::of((string) $content)->stripTags()->squish()->toString(),
            'url' => $url,
            'section' => $section,
            'category' => $category,
            'keywords' => $title,
            'published_at' => $publishedAt,
            'searchable' => $searchable,
            'boost' => 2,
        ];
    }
}
