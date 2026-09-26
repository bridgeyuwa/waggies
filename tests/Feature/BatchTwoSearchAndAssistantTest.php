<?php

use App\AI\WaggiesAssistant;
use App\Models\Faq;
use App\Models\JobOpening;
use App\Models\Product;
use App\Models\SearchDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns projected public search results and excludes unpublished content', function (): void {
    Product::factory()->create([
        'name' => 'Cedar Mobility Harness',
        'description' => 'A supportive harness for comfortable pet movement.',
    ]);
    Product::factory()->create([
        'name' => 'Draft Mobility Harness',
        'description' => 'This draft must never be publicly searchable.',
        'status' => Product::STATUS_DRAFT,
    ]);

    $response = $this->getJson(route('search', ['q' => 'mobility harness']))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

    $response->assertJsonPath('query', 'mobility harness');
    expect(collect($response->json('results'))
        ->pluck('title')
        ->all())->toContain('Cedar Mobility Harness')
        ->not->toContain('Draft Mobility Harness');
    expect($response->json('results.0'))->toHaveKeys(['key', 'type', 'title', 'description', 'href', 'category']);
    expect($response->json('results.0.key'))->not->toBe((string) Product::query()->first()->getKey());
});

it('keeps the search projection synchronized when published content changes state', function (): void {
    $product = Product::factory()->create([
        'name' => 'Original Search Name',
        'description' => 'A searchable product.',
    ]);

    expect(SearchDocument::query()->where('source_key', $product->getKey())->value('searchable'))->toBeTrue();

    $product->update(['name' => 'Renamed Search Product']);
    expect(SearchDocument::query()->where('source_key', $product->getKey())->value('title'))->toBe('Renamed Search Product');

    $product->update(['status' => Product::STATUS_DRAFT]);
    expect(SearchDocument::query()->where('source_key', $product->getKey())->value('searchable'))->toBeFalse();

    $product->delete();
    expect(SearchDocument::query()->where('source_key', $product->getKey())->exists())->toBeFalse();
});

it('uses Scout-backed shop search without exposing draft products', function (): void {
    Product::factory()->create([
        'name' => 'Cedar Joint Support',
        'description' => 'A public supplement listing.',
    ]);
    Product::factory()->create([
        'name' => 'Draft Joint Support',
        'status' => Product::STATUS_DRAFT,
    ]);

    $this->get(route('shop.index', ['q' => 'joint support']))
        ->assertOk()
        ->assertSee('Cedar Joint Support')
        ->assertDontSee('Draft Joint Support');
});

it('rejects oversized public search and assistant messages', function (): void {
    $this->getJson(route('search', ['q' => str_repeat('x', 121)]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['q']);

    $this->postJson(route('assistant.store'), ['message' => str_repeat('x', 1201)])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});

it('fails safely when the assistant provider is not configured', function (): void {
    config()->set('services.waggies_ai.enabled', true);
    config()->set('services.waggies_ai.provider', 'openai');
    config()->set('ai.providers.openai.key', null);

    $this->postJson(route('assistant.store'), ['message' => 'What are your boarding hours?'])
        ->assertStatus(503)
        ->assertJsonStructure(['message']);

    $this->postJson(route('assistant.stream'), ['message' => 'What are your boarding hours?'])
        ->assertStatus(503)
        ->assertJsonStructure(['message']);
});

it('uses a faked assistant provider without making a live provider call', function (): void {
    config()->set('services.waggies_ai.enabled', true);
    config()->set('services.waggies_ai.provider', 'openai');
    config()->set('ai.providers.openai.key', 'test-key');

    WaggiesAssistant::fake(['Approved test answer'])->preventStrayPrompts();

    $this->postJson(route('assistant.store'), ['message' => 'What are your boarding hours?'])
        ->assertOk()
        ->assertJsonPath('message', 'Approved test answer');
});

it('does not include draft FAQs in a rebuilt public projection', function (): void {
    Faq::factory()->create([
        'question' => 'Draft question about a secret service',
        'status' => Faq::STATUS_DRAFT,
    ]);

    $this->artisan('waggies:search-rebuild')->assertSuccessful();

    expect(SearchDocument::query()->where('title', 'Draft question about a secret service')->exists())->toBeFalse();
});

it('keeps current job openings searchable without indexing drafts or future openings', function (): void {
    JobOpening::factory()->create([
        'title' => 'Searchable Care Role',
        'description' => 'A unique searchable role description.',
        'status' => JobOpening::STATUS_OPEN,
        'published_at' => now(),
    ]);
    JobOpening::factory()->create([
        'title' => 'Future Search Role',
        'description' => 'A future role that must remain hidden.',
        'status' => JobOpening::STATUS_OPEN,
        'published_at' => now()->addDay(),
    ]);
    JobOpening::factory()->create([
        'title' => 'Draft Search Role',
        'description' => 'A draft role that must remain hidden.',
        'status' => JobOpening::STATUS_DRAFT,
    ]);

    $this->getJson(route('search', ['q' => 'unique searchable role']))
        ->assertOk()
        ->assertJsonFragment(['title' => 'Searchable Care Role'])
        ->assertJsonMissing(['title' => 'Future Search Role'])
        ->assertJsonMissing(['title' => 'Draft Search Role']);
});
