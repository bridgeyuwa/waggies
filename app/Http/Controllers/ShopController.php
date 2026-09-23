<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $sort = (string) $request->query('sort', 'featured');
        $sortOptions = [
            'featured' => ['sort_order', 'asc'],
            'newest' => ['created_at', 'desc'],
            'name' => ['name', 'asc'],
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
        ];
        $sort = array_key_exists($sort, $sortOptions) ? $sort : 'featured';

        $categories = Product::query()
            ->published()
            ->whereNotNull('category')
            ->pluck('category')
            ->unique()
            ->sort()
            ->values()
            ->all();
        $category = $request->query('category');
        $activeCategory = in_array($category, $categories, true) ? $category : 'All';

        if ($search !== '') {
            $productQuery = Product::search($search)
                ->query(static fn (Builder $query): Builder => $query
                    ->where('status', Product::STATUS_PUBLISHED)
                    ->where(function (Builder $query): void {
                        $query->whereNull('published_at')->orWhere('published_at', '<=', now());
                    })
                    ->with('media'));
        } else {
            $productQuery = Product::query()->published()->with('media');
        }

        if ($activeCategory !== 'All') {
            $productQuery->where('category', $activeCategory);
        }

        $publishedProducts = $productQuery
            ->orderBy($sortOptions[$sort][0], $sortOptions[$sort][1])
            ->when($sort !== 'name', fn ($query) => $query->orderBy('name'))
            ->get();
        $products = $publishedProducts->map->toPublicArray()->all();
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
            'search' => $search,
            'sort' => $sort,
            'sortOptions' => [
                'featured' => 'Featured',
                'newest' => 'Newest',
                'name' => 'Name',
                'price_asc' => 'Price: low to high',
                'price_desc' => 'Price: high to low',
            ],
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
