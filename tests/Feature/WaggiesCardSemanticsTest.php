<?php

namespace Tests\Feature;

use DOMDocument;
use Tests\TestCase;

final class WaggiesCardSemanticsTest extends TestCase
{
    public function test_product_card_separates_navigation_from_add_to_cart_action(): void
    {
        $view = $this->blade(
            '<x-waggies.shop-product-card :product="$product" />',
            ['product' => $this->product()],
        );

        $html = (string) $view;

        $this->assertNoNestedInteractiveElements($html);
        $this->assertStringContainsString('href="'.url('/shop/royal-canin-puppy').'"', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('waggies:add-item', $html);
        $this->assertStringContainsString('data-shop-product-category="Food"', $html);
    }

    public function test_card_pages_render_without_nested_interactive_elements(): void
    {
        $paths = ['/shop', '/services', '/guides', '/services/pricing'];

        foreach ($paths as $path) {
            $response = $this->get($path);

            $this->assertNoNestedInteractiveElements($response->getContent() ?: '');
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function product(): array
    {
        return [
            'id' => 'royal-canin-puppy',
            'name' => 'Royal Canin Puppy Dry Food',
            'price' => 18500,
            'image' => 'https://example.test/royal-canin-puppy.jpg',
            'alt' => 'Royal Canin Puppy Dry Food bag',
            'category' => 'Food',
            'badge' => 'Bestseller',
            'description' => 'Complete and balanced dry food for puppies.',
        ];
    }

    private function assertNoNestedInteractiveElements(string $html): void
    {
        $document = new DOMDocument;
        @$document->loadHTML('<!doctype html><html><body>'.$html.'</body></html>');

        foreach ($document->getElementsByTagName('a') as $anchor) {
            $this->assertSame(0, $anchor->getElementsByTagName('button')->length, 'A link contains a button.');
        }

        foreach ($document->getElementsByTagName('button') as $button) {
            $this->assertSame(0, $button->getElementsByTagName('a')->length, 'A button contains a link.');
        }
    }
}
