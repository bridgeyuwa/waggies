<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = array_values(config('waggies_shop.products', []));
        $categories = config('waggies_shop.categories', []);
        $category = $request->query('category');
        $activeCategory = in_array($category, $categories, true) ? $category : 'All';

        $metadata = [
            'title' => 'Pet Shop - Supplies & Products', 'description' => 'Browse pet food, toys, grooming supplies, health products, and accessories at the Waggies pet shop in Abuja, Nigeria.',
            'canonical' => route('shop.index'), 'ogTitle' => 'Pet Shop - Supplies & Products | Waggies', 'ogDescription' => 'Pet food, toys, grooming supplies, health products, and accessories in Abuja.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata);

        return view('pages.shop.index', $metadata + [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'navSection' => 'shop',
        ]);
    }

    public function show(string $id)
    {
        $products = config('waggies_shop.products', []);
        abort_unless(isset($products[$id]), 404);

        $product = $products[$id];
        $relatedProducts = array_values(array_filter($products, static fn (array $item): bool => $item['id'] !== $id && $item['category'] === $product['category']));

        $metadata = ['title' => $product['name'].' - Waggies Shop', 'description' => $product['description'], 'canonical' => route('shop.show', ['id' => $product['id']]), 'ogTitle' => $product['name'].' - Waggies Shop', 'ogDescription' => $product['description'], 'ogImage' => $product['image']];
        $productSchema = Schema::product()
            ->name($product['name'])
            ->description($product['description'])
            ->image($product['image'])
            ->url($metadata['canonical'])
            ->offers(Schema::offer()->price($product['price'])->priceCurrency('NGN')->url($metadata['canonical']));
        $this->setPageHead($metadata, [$productSchema->toArray()]);

        return view('pages.shop.show', $metadata + [
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 3),
            'recentProducts' => array_values($products),
            'navSection' => 'shop',
        ]);
    }
}
