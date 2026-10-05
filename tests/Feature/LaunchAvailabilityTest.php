<?php

use AppModels\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('services.waggies_ai.enabled', false);
    config()->set('services.waggies_shop.enabled', false);
});

it('shows a neutral support page when the shop is hidden', function (): void {
    $response = $this->get(route('shop.index'));

    $response->assertOk()
        ->assertSeeText('Waggies services and support are available')
        ->assertSeeText('Contact Waggies')
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
        ->assertDontSeeText('Pet Shop')
        ->assertDontSeeText('Waggies AI Assistant');
});

it('shows the same neutral page for old product URLs while the shop is hidden', function (): void {
    $product = Product::factory()->create();

    $this->get(route('shop.show', ['product' => $product]))
        ->assertOk()
        ->assertSeeText('Waggies services and support are available')
        ->assertDontSeeText($product->name);
});

it('removes hidden shop and product content from public search and sitemap', function (): void {
    Product::factory()->create(['name' => 'Hidden Launch Product']);

    $this->artisan('waggies:search-rebuild')->assertSuccessful();

    $this->getJson(route('search', ['q' => 'Hidden Launch Product']))
        ->assertOk()
        ->assertJsonPath('results', []);

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertDontSee('/shop');
});

it('hides the assistant endpoints while the assistant is disabled', function (): void {
    $payload = ['message' => 'What are your opening hours?'];

    $this->postJson(route('assistant.store'), $payload)->assertNotFound();
    $this->postJson(route('assistant.stream'), $payload)->assertNotFound();
});

it('removes hidden shop and assistant controls from the public layout', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('id="global-cart-dialog"', false)
        ->assertDontSee('Open Waggies AI Assistant')
        ->assertDontSee('href="'.route('shop.index').'"', false)
        ->assertSeeText('Subscribe');

    $this->get(route('contact'))
        ->assertOk()
        ->assertDontSeeText('SHOP & PRODUCTS')
        ->assertDontSeeText('Ask about a product');

    $this->get(route('contact', ['intent' => 'product-inquiry']))
        ->assertNotFound();
});
