<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $publishedProducts = Product::query()
            ->published()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $products = $publishedProducts->map->toPublicArray()->all();
        $categories = $publishedProducts
            ->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
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

    public function show(Product $product): View
    {
        abort_unless($product->isPublished(), 404);

        $product->loadMissing('media');
        $productData = $product->toPublicArray();
        $relatedProducts = Product::query()
            ->published()
            ->with('media')
            ->where('category', $product->category)
            ->where('id', '!=', $product->getKey())
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map
            ->toPublicArray()
            ->take(3)
            ->all();
        $recentProducts = Product::query()
            ->published()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map
            ->toPublicArray()
            ->all();

        $metadata = ['title' => $productData['name'].' - Waggies Shop', 'description' => $productData['description'], 'canonical' => route('shop.show', ['product' => $product]), 'ogTitle' => $productData['name'].' - Waggies Shop', 'ogDescription' => $productData['description'], 'ogImage' => $productData['image']];
        $productSchema = Schema::product()
            ->name($productData['name'])
            ->description($productData['description'])
            ->image($productData['image'])
            ->url($metadata['canonical'])
            ->offers(Schema::offer()->price($productData['price'])->priceCurrency($productData['currency'])->url($metadata['canonical']));
        $this->setPageHead($metadata, [$productSchema->toArray()]);

        return view('pages.shop.show', $metadata + [
            'product' => $productData,
            'relatedProducts' => $relatedProducts,
            'recentProducts' => $recentProducts,
            'navSection' => 'shop',
        ]);
    }
}
