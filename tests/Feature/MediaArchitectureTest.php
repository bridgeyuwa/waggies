<?php

use App\Models\GalleryItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('renders managed gallery media from the public disk with a responsive srcset', function (): void {
    Storage::fake('public');

    $item = GalleryItem::factory()->create([
        'image' => null,
        'image_alt' => 'A locally managed gallery image',
        'status' => GalleryItem::STATUS_PUBLISHED,
    ]);
    $media = $item->addMedia(UploadedFile::fake()->image('gallery.jpg', 1600, 1200))
        ->toMediaCollection('image');

    $response = $this->get(route('about.gallery'))
        ->assertOk()
        ->assertSee('gallery-detail.jpg', false);

    expect($media->disk)->toBe('public')
        ->and($media->collection_name)->toBe('image')
        ->and($media->hasGeneratedConversion('thumb'))->toBeTrue()
        ->and($media->hasGeneratedConversion('detail'))->toBeTrue()
        ->and($media->getSrcset('detail'))->not->toBeEmpty()
        ->and($item->fresh()->toPublicArray()['alt'])->toBe('A locally managed gallery image')
        ->and($response->getContent())->toContain('gallery-detail.jpg');

    Storage::disk('public')->assertExists($media->getPathRelativeToRoot());
    Storage::disk('public')->assertExists($media->getPathRelativeToRoot('thumb'));
    Storage::disk('public')->assertExists($media->getPathRelativeToRoot('detail'));
});

it('makes a replacement authoritative and removes the previous media files', function (): void {
    Storage::fake('public');

    $product = Product::factory()->create(['image' => null]);
    $original = $product->addMedia(UploadedFile::fake()->image('original.jpg'))
        ->toMediaCollection('image');
    $originalPaths = [
        $original->getPathRelativeToRoot(),
        $original->getPathRelativeToRoot('thumb'),
        $original->getPathRelativeToRoot('detail'),
    ];

    $replacement = $product->addMedia(UploadedFile::fake()->image('replacement.jpg'))
        ->toMediaCollection('image');

    expect($product->fresh()->getMedia('image'))->toHaveCount(1)
        ->and($product->fresh()->getFirstMedia('image')->getKey())->toBe($replacement->getKey())
        ->and($product->fresh()->publicImageUrl())->toBe($replacement->getUrl('detail'));

    $this->assertDatabaseMissing('media', ['id' => $original->getKey()]);

    foreach ($originalPaths as $path) {
        Storage::disk('public')->assertMissing($path);
    }

    Storage::disk('public')->assertExists($replacement->getPathRelativeToRoot());
});

it('removes media records and generated files when a collection is cleared', function (): void {
    Storage::fake('public');

    $item = GalleryItem::factory()->create(['image' => null]);
    $media = $item->addMedia(UploadedFile::fake()->image('gallery.jpg'))
        ->toMediaCollection('image');
    $paths = [
        $media->getPathRelativeToRoot(),
        $media->getPathRelativeToRoot('thumb'),
        $media->getPathRelativeToRoot('detail'),
    ];

    $item->clearMediaCollection('image');

    expect($item->fresh()->getMedia('image'))->toBeEmpty();
    $this->assertDatabaseMissing('media', ['id' => $media->getKey()]);

    foreach ($paths as $path) {
        Storage::disk('public')->assertMissing($path);
    }
});

it('removes owned media when its domain record is deleted', function (): void {
    Storage::fake('public');

    $product = Product::factory()->create(['image' => null]);
    $media = $product->addMedia(UploadedFile::fake()->image('product.jpg'))
        ->toMediaCollection('image');
    $paths = [
        $media->getPathRelativeToRoot(),
        $media->getPathRelativeToRoot('thumb'),
        $media->getPathRelativeToRoot('detail'),
    ];

    $product->delete();

    $this->assertDatabaseMissing('media', ['id' => $media->getKey()]);

    foreach ($paths as $path) {
        Storage::disk('public')->assertMissing($path);
    }
});
