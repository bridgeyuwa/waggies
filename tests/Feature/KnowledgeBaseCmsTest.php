<?php

use App\Models\KnowledgeArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('imports the knowledge-base seed fixture into the persisted source', function (): void {
    $configuredArticles = (require database_path('seeders/fixtures/knowledge_base.php'))['items'];
    $persistedArticles = KnowledgeArticle::query()->orderBy('sort_order')->get();

    expect($persistedArticles)->toHaveCount(count($configuredArticles))
        ->and($persistedArticles->pluck('slug')->all())->toBe(array_column($configuredArticles, 'slug'));

    foreach ($configuredArticles as $configuredArticle) {
        $article = $persistedArticles->firstWhere('slug', $configuredArticle['slug']);

        expect($article)->not->toBeNull()
            ->and($article->title)->toBe($configuredArticle['title'])
            ->and($article->excerpt)->toBe($configuredArticle['excerpt'])
            ->and($article->category)->toBe($configuredArticle['category'])
            ->and($article->content)->toBe($configuredArticle['content'])
            ->and($article->author)->toBe($configuredArticle['author'] ?? null)
            ->and($article->published_at?->toDateString())->toBe($configuredArticle['date'] ?? null)
            ->and($article->read_time)->toBe($configuredArticle['readTime'] ?? null)
            ->and($article->status)->toBe(KnowledgeArticle::STATUS_PUBLISHED);
    }
});

it('uses package-owned knowledge article slug uniqueness and protects status invariants', function (): void {
    $article = KnowledgeArticle::create(knowledge_article_payload(['slug' => 'understanding-common-dog-illnesses']));

    expect($article->slug)->toBe('understanding-common-dog-illnesses-1');
});

it('rejects an invalid knowledge article status before persistence', function (): void {
    $this->expectException(InvalidArgumentException::class);

    KnowledgeArticle::create(knowledge_article_payload(['status' => 'review']));
});

it('serves published articles from the database and hides unpublished articles', function (): void {
    $draft = KnowledgeArticle::create(knowledge_article_payload([
        'slug' => 'draft-knowledge-article',
        'title' => 'Draft Knowledge Article',
        'status' => KnowledgeArticle::STATUS_DRAFT,
    ]));
    $archived = KnowledgeArticle::create(knowledge_article_payload([
        'slug' => 'archived-knowledge-article',
        'title' => 'Archived Knowledge Article',
        'status' => KnowledgeArticle::STATUS_ARCHIVED,
    ]));
    $scheduled = KnowledgeArticle::create(knowledge_article_payload([
        'slug' => 'scheduled-knowledge-article',
        'title' => 'Scheduled Knowledge Article',
        'status' => KnowledgeArticle::STATUS_PUBLISHED,
        'published_at' => now()->addDay(),
    ]));

    $this->get(route('knowledge-base.show', ['slug' => 'understanding-common-dog-illnesses']))
        ->assertOk()
        ->assertSee('Understanding Common Dog Illnesses in Nigeria');

    $this->get(route('knowledge-base.show', ['slug' => $draft->slug]))->assertNotFound();
    $this->get(route('knowledge-base.show', ['slug' => $archived->slug]))->assertNotFound();
    $this->get(route('knowledge-base.show', ['slug' => $scheduled->slug]))->assertNotFound();
});

it('preserves published knowledge article canonical, search, and sitemap behavior', function (): void {
    $url = route('knowledge-base.show', ['slug' => 'understanding-common-dog-illnesses']);

    $this->get($url)
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.$url.'">', false)
        ->assertSee('<meta name="robots" content="index, follow">', false)
        ->assertSee('"@type":"Article"', false);

    $this->get(route('search', ['q' => 'tick-borne diseases']))
        ->assertOk()
        ->assertJsonFragment(['href' => $url]);

    expect($this->get(route('sitemap'))->getContent())->toContain($url);
});

it('keeps non-indexable knowledge articles out of search, schema, and the sitemap', function (): void {
    $article = KnowledgeArticle::query()->where('slug', 'understanding-common-dog-illnesses')->firstOrFail();
    $article->update(['is_indexable' => false]);

    $response = $this->get(route('knowledge-base.show', ['slug' => $article->slug]));
    $sitemap = $this->get(route('sitemap'));

    $response
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', false)
        ->assertDontSee('rel="canonical"', false)
        ->assertDontSee('"@type":"Article"', false);

    $this->get(route('search', ['q' => 'tick-borne diseases']))
        ->assertOk()
        ->assertJsonMissing(['href' => route('knowledge-base.show', ['slug' => $article->slug])]);

    expect($sitemap->getContent())->not->toContain(route('knowledge-base.show', ['slug' => $article->slug]));
});

it('preserves knowledge base listing pagination and search contracts', function (): void {
    $this->get(route('knowledge-base.index'))
        ->assertOk()
        ->assertSee('understanding-common-dog-illnesses')
        ->assertSee('waggiesKnowledgeBase');

    $this->get(route('knowledge-base.index', ['page' => 2]))
        ->assertOk()
        ->assertSee('aria-current="page"', false)
        ->assertSee('rel="prev"', false)
        ->assertSee('rel="next"', false);

    $this->get(route('knowledge-base.index', ['category' => 'Health', 'q' => 'vaccination']))
        ->assertOk()
        ->assertSee('waggiesKnowledgeBase');
});

it('uses UUIDv7 keys and generates a normalized knowledge article slug on creation', function (): void {
    $article = KnowledgeArticle::create(knowledge_article_payload([
        'title' => '  Caring for Dogs & Cats  ',
        'slug' => null,
    ]));

    expect($article->getKey())->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i')
        ->and($article->getKey())->not->toBeNumeric()
        ->and($article->getIncrementing())->toBeFalse()
        ->and($article->getKeyType())->toBe('string')
        ->and($article->slug)->toBe('caring-for-dogs-cats');
});

it('regenerates untouched knowledge article slugs and redirects multiple stale URLs', function (): void {
    $article = KnowledgeArticle::create(knowledge_article_payload([
        'title' => 'Original Knowledge Title',
        'slug' => null,
        'status' => KnowledgeArticle::STATUS_PUBLISHED,
    ]));
    $oldSlug = $article->slug;

    $article->update(['title' => 'Rewritten Knowledge Title']);
    $generatedSlug = $article->fresh()->slug;

    expect($generatedSlug)->toBe('rewritten-knowledge-title');

    $this->get(route('knowledge-base.show', ['slug' => $oldSlug]))
        ->assertPermanentRedirect(route('knowledge-base.show', ['slug' => $generatedSlug]));

    $article->update(['slug' => 'rewritten-knowledge']);

    $this->get(route('knowledge-base.show', ['slug' => $generatedSlug]))
        ->assertPermanentRedirect(route('knowledge-base.show', ['slug' => 'rewritten-knowledge']));
    $this->get(route('knowledge-base.show', ['slug' => $oldSlug]))
        ->assertPermanentRedirect(route('knowledge-base.show', ['slug' => 'rewritten-knowledge']));
    $this->get(route('knowledge-base.show', ['slug' => 'rewritten-knowledge']))->assertOk();
});

it('associates knowledge article media with the UUID model', function (): void {
    Storage::fake('public');

    $article = KnowledgeArticle::create(knowledge_article_payload(['slug' => null]));
    $article->addMedia(UploadedFile::fake()->image('cover.jpg'))->toMediaCollection('cover');

    expect($article->getRegisteredMediaCollections()->pluck('name')->all())
        ->toContain('cover', 'content-attachments');

    $this->assertDatabaseHas('media', [
        'model_type' => KnowledgeArticle::class,
        'model_id' => $article->getKey(),
        'collection_name' => 'cover',
    ]);
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function knowledge_article_payload(array $overrides = []): array
{
    return [
        'title' => 'A new knowledge article',
        'slug' => 'a-new-knowledge-article',
        'excerpt' => 'A useful answer summary.',
        'category' => 'Health',
        'author' => null,
        'image' => 'https://example.test/knowledge-article.jpg',
        'read_time' => '4 min read',
        'content' => '<h2>Knowledge article content</h2><p>Useful answer content.</p>',
        'sort_order' => 99,
        'status' => KnowledgeArticle::STATUS_DRAFT,
        ...$overrides,
    ];
}
