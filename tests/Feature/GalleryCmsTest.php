<?php

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('imports the current gallery content into persisted UUIDv7 records', function (): void {
    $items = GalleryItem::query()->orderBy('sort_order')->get();

    expect($items)->toHaveCount(count(config('waggies_about_pages.gallery.images')));

    foreach ($items as $item) {
        expect($item->getKey())
            ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i');
    }

    expect($items->first()->getIncrementing())->toBeFalse()
        ->and($items->first()->getKeyType())->toBe('string');
});

it('renders published gallery records from the database and hides drafts', function (): void {
    $published = GalleryItem::factory()->create([
        'image_alt' => 'A database-managed gallery image',
        'image' => 'https://example.test/gallery.jpg',
        'sort_order' => 0,
        'status' => GalleryItem::STATUS_PUBLISHED,
    ]);
    $draft = GalleryItem::factory()->create([
        'image_alt' => 'A hidden gallery image',
        'image' => 'https://example.test/hidden.jpg',
        'status' => GalleryItem::STATUS_DRAFT,
    ]);

    $response = $this->get(route('about.gallery'));

    $response
        ->assertOk()
        ->assertSee($published->image_alt)
        ->assertDontSee($draft->image_alt);
});

it('does not render published gallery records that have no usable image', function (): void {
    $item = GalleryItem::factory()->create([
        'image' => null,
        'image_alt' => 'Gallery item without an image',
        'status' => GalleryItem::STATUS_PUBLISHED,
    ]);

    $response = $this->get(route('about.gallery'))
        ->assertOk();

    expect($response->getContent())->not->toContain('Gallery item without an image')
        ->not->toContain('src="'.route('about.gallery').'"')
        ->and($item->toPublicArray()['src'])->toBe('');
});

it('attaches gallery media to the local public disk and exposes it publicly', function (): void {
    Storage::fake('public');

    $item = GalleryItem::factory()->create([
        'status' => GalleryItem::STATUS_PUBLISHED,
        'image_alt' => 'A locally stored gallery image',
    ]);
    $item->addMedia(UploadedFile::fake()->image('gallery.jpg'))->toMediaCollection('image');
    $media = $item->fresh()->getFirstMedia('image');

    expect($media)->not->toBeNull()
        ->and($media->disk)->toBe('public')
        ->and($item->fresh()->publicImageUrl())->toBe($media->getUrl('detail'));

    $this->assertDatabaseHas('media', [
        'model_type' => GalleryItem::class,
        'model_id' => $item->getKey(),
        'collection_name' => 'image',
        'disk' => 'public',
    ]);

    Storage::disk('public')->assertExists($media->getPathRelativeToRoot());
});

it('preserves persisted gallery ordering in the public lightbox data', function (): void {
    $first = GalleryItem::factory()->create([
        'image_alt' => 'First ordered gallery image',
        'image' => 'https://example.test/first.jpg',
        'sort_order' => 1,
        'status' => GalleryItem::STATUS_PUBLISHED,
    ]);
    $second = GalleryItem::factory()->create([
        'image_alt' => 'Second ordered gallery image',
        'image' => 'https://example.test/second.jpg',
        'sort_order' => 2,
        'status' => GalleryItem::STATUS_PUBLISHED,
    ]);

    $content = $this->get(route('about.gallery'))->getContent();

    expect(strpos($content, $first->image_alt))->toBeLessThan(strpos($content, $second->image_alt));
});
