<?php

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('imports the FAQ seed fixture into the persisted source with UUIDv7 keys', function (): void {
    $faqs = Faq::query()->orderBy('sort_order')->get();
    $faqFixture = require database_path('seeders/fixtures/faqs.php');

    expect($faqs)->toHaveCount(count($faqFixture));

    foreach ($faqs as $faq) {
        expect($faq->getKey())
            ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i');
    }

    expect($faqs->first()->getIncrementing())->toBeFalse()
        ->and($faqs->first()->getKeyType())->toBe('string');
});

it('renders published FAQs from the database and hides drafts', function (): void {
    $published = Faq::factory()->create([
        'question' => 'Which database question is public?',
        'answer' => 'This answer is sourced from the database.',
        'sort_order' => 0,
        'status' => Faq::STATUS_PUBLISHED,
    ]);
    $draft = Faq::factory()->create([
        'question' => 'Which draft question is private?',
        'answer' => 'This answer must not be shown.',
        'status' => Faq::STATUS_DRAFT,
    ]);

    $response = $this->get(route('faq'));

    $response
        ->assertOk()
        ->assertSee($published->question)
        ->assertDontSee($draft->question);
});

it('limits category FAQ structured data to the selected category', function (): void {
    $boarding = Faq::factory()->create([
        'category' => 'boarding',
        'question' => 'What should I pack for boarding?',
        'answer' => 'Pack the essentials listed in your confirmation.',
        'status' => Faq::STATUS_PUBLISHED,
        'sort_order' => 0,
    ]);
    Faq::factory()->create([
        'category' => 'vet-care',
        'question' => 'How are vaccination requests handled?',
        'answer' => 'The veterinary team reviews the requested vaccine and confirms the appropriate quote.',
        'status' => Faq::STATUS_PUBLISHED,
        'sort_order' => 1,
    ]);

    $html = $this->get(route('faq', ['category' => 'boarding']))->getContent();
    preg_match_all('/<script[^>]*type="application\/ld\+json"[^>]*>(.*?)<\/script>/s', $html, $matches);
    $faqSchema = collect($matches[1] ?? [])
        ->map(fn (string $script): mixed => json_decode($script, true))
        ->filter(fn (mixed $schema): bool => is_array($schema) && ($schema['@type'] ?? null) === 'FAQPage')
        ->first();
    $questions = collect($faqSchema['mainEntity'] ?? [])->pluck('name')->all();

    expect($questions)
        ->toContain($boarding->question)
        ->not->toContain('How are vaccination requests handled?');
});

it('preserves persisted FAQ ordering in service compositions', function (): void {
    $first = Faq::factory()->create([
        'category' => 'boarding',
        'question' => 'First persisted boarding question',
        'sort_order' => 1,
        'status' => Faq::STATUS_PUBLISHED,
    ]);
    $second = Faq::factory()->create([
        'category' => 'boarding',
        'question' => 'Second persisted boarding question',
        'sort_order' => 2,
        'status' => Faq::STATUS_PUBLISHED,
    ]);

    $content = $this->get(route('services.boarding'))->getContent();

    expect(strpos($content, $first->question))->toBeLessThan(strpos($content, $second->question));
});

it('clears publication dates when an FAQ is hidden', function (): void {
    $faq = Faq::factory()->create([
        'status' => Faq::STATUS_PUBLISHED,
        'published_at' => now(),
    ]);

    $faq->update(['status' => Faq::STATUS_DRAFT]);

    expect($faq->fresh()->published_at)->toBeNull()
        ->and($faq->fresh()->isPublished())->toBeFalse();
});
