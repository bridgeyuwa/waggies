<?php

use App\Models\Guide;
use App\Support\ArticleBodyProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('imports the current guide configuration into the persisted guide source', function (): void {
    $configuredGuides = config('waggies_guides.items');
    $persistedGuides = Guide::query()->orderBy('id')->get();

    expect($persistedGuides)->toHaveCount(count($configuredGuides))
        ->and($persistedGuides->pluck('slug')->all())->toBe(array_column($configuredGuides, 'slug'));

    foreach ($configuredGuides as $configuredGuide) {
        $guide = $persistedGuides->firstWhere('slug', $configuredGuide['slug']);

        expect($guide)->not->toBeNull()
            ->and($guide->title)->toBe($configuredGuide['title'])
            ->and($guide->excerpt)->toBe($configuredGuide['excerpt'])
            ->and($guide->category)->toBe($configuredGuide['category'])
            ->and($guide->image_alt)->toBe($configuredGuide['imageAlt'])
            ->and($guide->content)->toBe($configuredGuide['content'])
            ->and($guide->status)->toBe(Guide::STATUS_PUBLISHED);
    }
});

it('falls back to the guide title when a source image alt description is unavailable', function (): void {
    $guide = Guide::query()->where('slug', 'pet-transport-what-to-know')->firstOrFail();

    $guide->update(['image_alt' => null]);

    $this->get(route('guides.show', ['slug' => $guide->slug]))
        ->assertOk()
        ->assertSee('alt="Pet Transport: What You Need to Know"', false)
        ->assertDontSee('Pet receiving attentive grooming care');
});

it('uses package-owned guide slug uniqueness and protects status invariants', function (): void {
    $guide = Guide::create(guide_payload(['slug' => 'preparing-pet-boarding']));

    expect($guide->slug)->toBe('preparing-pet-boarding-1');
});

it('rejects an invalid guide status before persistence', function (): void {
    $this->expectException(InvalidArgumentException::class);

    Guide::create(guide_payload(['status' => 'review']));
});

it('serves published guides and hides drafts and archived guides', function (): void {
    $draft = Guide::create(guide_payload([
        'slug' => 'draft-guide',
        'title' => 'Draft Guide',
        'status' => Guide::STATUS_DRAFT,
    ]));
    $archived = Guide::create(guide_payload([
        'slug' => 'archived-guide',
        'title' => 'Archived Guide',
        'status' => Guide::STATUS_ARCHIVED,
    ]));

    $this->get(route('guides.show', ['slug' => 'preparing-pet-boarding']))
        ->assertOk()
        ->assertSee('How to Prepare Your Pet for Boarding');

    $this->get(route('guides.show', ['slug' => $draft->slug]))->assertNotFound();
    $this->get(route('guides.show', ['slug' => $archived->slug]))->assertNotFound();
});

it('keeps non-indexable published guides out of search, schema, and the sitemap', function (): void {
    $guide = Guide::query()->where('slug', 'preparing-pet-boarding')->firstOrFail();
    $guide->update(['is_indexable' => false]);

    $response = $this->get(route('guides.show', ['slug' => $guide->slug]));
    $sitemap = $this->get(route('sitemap'));

    $response
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', false)
        ->assertDontSee('rel="canonical"', false)
        ->assertDontSee('"@type":"Article"', false);

    $this->get(route('search', ['q' => 'comfortable and stress-free']))
        ->assertOk()
        ->assertJsonMissing(['href' => route('guides.show', ['slug' => $guide->slug])]);

    expect($sitemap->getContent())->not->toContain(route('guides.show', ['slug' => $guide->slug]));
});

it('preserves published guide canonical and search behavior', function (): void {
    $url = route('guides.show', ['slug' => 'preparing-pet-boarding']);

    $this->get($url)
        ->assertOk()
        ->assertSee('<title>How to Prepare Your Pet for Boarding - Waggies Guides - Waggies</title>', false)
        ->assertSee('<link rel="canonical" href="'.$url.'">', false)
        ->assertSee('<meta name="robots" content="index, follow">', false)
        ->assertSee('"@type":"Article"', false);

    $this->get(route('search', ['q' => 'stress-free boarding']))
        ->assertOk()
        ->assertJsonFragment(['href' => $url]);
});

it('sanitizes article body HTML while preserving the existing heading contract', function (): void {
    $result = ArticleBodyProcessor::process('<h2 onclick="alert(1)">A <strong>safe</strong> heading</h2><script>alert(1)</script>');

    expect($result['headings'])->toBe([
        ['level' => 2, 'text' => 'A safe heading', 'id' => 'heading-0'],
    ])
        ->and($result['content'])->toContain('id="heading-0"')
        ->and($result['content'])->not->toContain('<script')
        ->and($result['content'])->not->toContain('onclick');
});

it('preserves supported rich editorial content while removing unsafe markup', function (): void {
    $result = ArticleBodyProcessor::process(<<<'HTML'
        <h2>Travel checklist</h2>
        <ul><li>Food</li><li>Medication</li></ul>
        <table><thead><tr><th>Item</th></tr></thead><tbody><tr><td>Carrier</td></tr></tbody></table>
        <p><a href="https://example.com">Read more</a></p>
        <img src="https://example.com/carrier.jpg" alt="Pet carrier" onerror="alert(1)">
        <script>alert(1)</script>
    HTML);

    expect($result['content'])
        ->toContain('<ul>')
        ->toContain('<table>')
        ->toContain('https://example.com')
        ->toContain('carrier.jpg')
        ->not->toContain('<script')
        ->not->toContain('onerror');
});

it('uses UUIDv7 keys and generates a normalized guide slug on creation', function (): void {
    config()->set('waggies_guides.items', []);

    $guide = Guide::create(guide_payload([
        'title' => '  Caring for Dogs & Cats  ',
        'slug' => null,
    ]));

    expect($guide->getKey())->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i')
        ->and($guide->getKey())->not->toBeNumeric()
        ->and($guide->getIncrementing())->toBeFalse()
        ->and($guide->getKeyType())->toBe('string')
        ->and($guide->slug)->toBe('caring-for-dogs-cats');
});

it('regenerates untouched guide slugs and redirects multiple stale URLs', function (): void {
    config()->set('waggies_guides.items', []);

    $guide = Guide::create(guide_payload([
        'title' => 'Original Guide Title',
        'slug' => null,
        'status' => Guide::STATUS_PUBLISHED,
    ]));
    $oldSlug = $guide->slug;

    $guide->update(['title' => 'Rewritten Guide Title']);
    $generatedSlug = $guide->fresh()->slug;

    expect($generatedSlug)->toBe('rewritten-guide-title');

    $this->get(route('guides.show', ['slug' => $oldSlug]))
        ->assertPermanentRedirect(route('guides.show', ['slug' => $generatedSlug]));

    $guide->update(['slug' => 'rewritten-guide']);

    $this->get(route('guides.show', ['slug' => $generatedSlug]).'?ref=legacy')
        ->assertPermanentRedirect(route('guides.show', ['slug' => 'rewritten-guide']).'?ref=legacy');
    $this->get(route('guides.show', ['slug' => $oldSlug]))
        ->assertPermanentRedirect(route('guides.show', ['slug' => 'rewritten-guide']));
    $this->get(route('guides.show', ['slug' => 'rewritten-guide']))->assertOk();
});

it('associates guide media with the UUID model and keeps rich content media separate', function (): void {
    config()->set('waggies_guides.items', []);
    Storage::fake('public');

    $guide = Guide::create(guide_payload(['slug' => null]));
    $guide->addMedia(UploadedFile::fake()->image('cover.jpg'))->toMediaCollection('cover');

    expect($guide->getRegisteredMediaCollections()->pluck('name')->all())
        ->toContain('cover', 'content-attachments');

    $this->assertDatabaseHas('media', [
        'model_type' => Guide::class,
        'model_id' => $guide->getKey(),
        'collection_name' => 'cover',
    ]);
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function guide_payload(array $overrides = []): array
{
    return [
        'title' => 'A new guide',
        'slug' => 'a-new-guide',
        'excerpt' => 'A useful guide summary.',
        'category' => 'General',
        'image' => 'https://example.test/guide.jpg',
        'image_alt' => 'A pet receiving care',
        'read_time' => '4 min read',
        'content' => '<h2>Guide content</h2><p>Useful guide content.</p>',
        'status' => Guide::STATUS_DRAFT,
        ...$overrides,
    ];
}
