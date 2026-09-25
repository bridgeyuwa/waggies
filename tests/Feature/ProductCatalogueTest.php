<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('imports the current shop products as published UUIDv7 records', function (): void {
    $products = Product::query()->orderBy('sort_order')->get();

    expect($products)->toHaveCount(10);

    foreach ($products as $product) {
        expect($product->id)
            ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i')
            ->and($product->status)->toBe(Product::STATUS_PUBLISHED)
            ->and($product->price)->toBeInt();
    }

    expect($products->first()->getIncrementing())->toBeFalse()
        ->and($products->first()->getKeyType())->toBe('string');
});

it('renders published products from the database and hides unpublished products', function (): void {
    $product = Product::query()->firstOrFail();
    $product->update([
        'name' => 'Database Managed Product',
        'price' => 19999,
    ]);

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertSeeText('Database Managed Product')
        ->assertSeeText('₦19,999');

    $product->update(['status' => Product::STATUS_DRAFT]);

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertDontSeeText('Database Managed Product');

    $this->get(route('shop.show', ['product' => $product]))
        ->assertNotFound();
});

it('keeps product indexability and sitemap inclusion independent', function (): void {
    $product = Product::query()->firstOrFail();
    $url = route('shop.show', ['product' => $product]);

    $product->update(['is_indexable' => false]);

    $this->get($url)
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', false)
        ->assertDontSee('rel="canonical"', false)
        ->assertDontSee('"@type":"Product"', false);

    $this->get($url.'?utm_source=external')
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', false)
        ->assertDontSee('rel="canonical"', false);

    expect($this->get(route('sitemap'))->getContent())->not->toContain($url);

    $product->update(['is_indexable' => true, 'include_in_sitemap' => false]);

    $this->get($url)
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.$url.'">', false)
        ->assertSee('<meta name="robots" content="index, follow">', false)
        ->assertSee('"@type":"Product"', false);

    expect($this->get(route('sitemap'))->getContent())->not->toContain($url);
});

it('uses product SEO fields for copy without changing URL or schema policy', function (): void {
    $product = Product::query()->firstOrFail();
    $product->update([
        'seo_title' => 'A deliberately specific product title',
        'seo_description' => 'A deliberately specific product description.',
    ]);

    $this->get(route('shop.show', ['product' => $product]))
        ->assertOk()
        ->assertSee('<title>A deliberately specific product title</title>', false)
        ->assertSee('<meta name="description" content="A deliberately specific product description.">', false)
        ->assertSee('property="og:title" content="A deliberately specific product title"', false)
        ->assertSee('"@type":"Product"', false)
        ->assertSee('"description":'.json_encode($product->description), false)
        ->assertDontSee('"description":"A deliberately specific product description."', false);
});

it('generates unique slug-only product URLs', function (): void {
    $first = Product::factory()->create(['name' => 'Same Product']);
    $second = Product::factory()->create(['name' => 'Same Product']);

    expect($first->slug)->toBe('same-product')
        ->and($second->slug)->not->toBe($first->slug);

    $this->get(route('shop.show', ['product' => $first]))
        ->assertOk()
        ->assertSeeText($first->name);

    $first->update(['name' => 'Renamed Product']);

    expect($first->fresh()->slug)->toBe('same-product');
});

it('attaches product media to the local public disk', function (): void {
    Storage::fake('public');

    $product = Product::factory()->create();
    $product->addMedia(UploadedFile::fake()->image('product.jpg'))->toMediaCollection('image');
    $media = $product->fresh()->getFirstMedia('image');

    expect($media)->not->toBeNull()
        ->and($media->disk)->toBe('public')
        ->and($product->fresh()->publicImageUrl())->toBe($media->getUrl('detail'));

    expect($product->fresh()->toPublicArray('/fallback.jpg')['image'])->toBe($media->getUrl('detail'));

    $this->assertDatabaseHas('media', [
        'model_type' => Product::class,
        'model_id' => $product->getKey(),
        'collection_name' => 'image',
        'disk' => 'public',
    ]);

    Storage::disk('public')->assertExists($media->getPathRelativeToRoot());
});

it('renders every product image as a navigable gallery item in media order', function (): void {
    Storage::fake('public');

    $product = Product::factory()->create(['name' => 'Gallery Product']);
    $product->addMedia(UploadedFile::fake()->image('first.jpg'))->toMediaCollection('images');
    $product->addMedia(UploadedFile::fake()->image('second.jpg'))->toMediaCollection('images');
    $product->addMedia(UploadedFile::fake()->image('third.jpg'))->toMediaCollection('images');

    $response = $this->get(route('shop.show', ['product' => $product]));

    $response->assertOk()
        ->assertSee('waggiesProductGallery', false)
        ->assertSee(":aria-label=\"'Show image ' + (index + 1) + ': ' + image.alt\"", false)
        ->assertSee(':aria-current="activeIndex === index ? \'true\' : \'false\'"', false)
        ->assertSee('Previous image', false)
        ->assertSee('Next image', false)
        ->assertSee('Product image lightbox', false);
});

it('renders a single usable gallery item when a product has no media', function (): void {
    $product = Product::factory()->create(['name' => 'No Media Product']);

    $this->get(route('shop.show', ['product' => $product]))
        ->assertOk()
        ->assertSee('waggiesProductGallery', false)
        ->assertSee('Photo <span x-text="activeIndex + 1"></span> of <span x-text="images.length"></span>', false);
});

it('exposes the product resource to authenticated staff', function (): void {
    config()->set('app.env', 'local');
    $this->actingAs(User::factory()->create());

    $this->get('/admin/products')->assertOk();
});
