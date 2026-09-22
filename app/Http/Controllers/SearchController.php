<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Guide;
use App\Models\KnowledgeArticle;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim($request->string('q')->toString());
        if ($query === '') {
            return response()->json(['query' => '', 'results' => []])->header('X-Robots-Tag', 'noindex, nofollow');
        }

        $items = [
            ['id' => 'page-home', 'type' => 'page', 'title' => 'Home', 'description' => 'Waggies - pet boarding, grooming, vet care, training, relocation and local transport in Abuja.', 'href' => route('home'), 'category' => 'Page', 'icon' => 'home'],
            ['id' => 'page-services', 'type' => 'page', 'title' => 'Services', 'description' => 'Browse every Waggies service - boarding, grooming, vet care, training, relocation and local transport.', 'href' => route('services.index'), 'category' => 'Page', 'icon' => 'services'],
            ['id' => 'page-faq', 'type' => 'page', 'title' => 'FAQ', 'description' => 'Quick answers to the questions we hear most about Waggies services.', 'href' => route('faq'), 'category' => 'Page', 'icon' => 'help'],
            ['id' => 'page-contact', 'type' => 'page', 'title' => 'Contact', 'description' => 'Request a service, request a quote, or send us a message.', 'href' => route('contact'), 'category' => 'Page', 'icon' => 'email'],
            ['id' => 'page-loyalty', 'type' => 'page', 'title' => 'Loyalty Programme', 'description' => 'Earn Waggies points on every booking and redeem them for discounts and perks.', 'href' => route('loyalty'), 'category' => 'Page', 'icon' => 'loyalty'],
            ['id' => 'service-boarding', 'type' => 'service', 'title' => 'Boarding', 'description' => 'Spacious, climate-controlled suites with 24/7 supervision and daily photo updates.', 'href' => route('services.boarding'), 'category' => 'Service', 'icon' => 'boarding'],
            ['id' => 'service-grooming', 'type' => 'service', 'title' => 'Grooming', 'description' => 'Breed-specific cuts, baths, and styling by experienced groomers using pet-safe products.', 'href' => route('services.grooming'), 'category' => 'Service', 'icon' => 'grooming'],
            ['id' => 'service-vet-care', 'type' => 'service', 'title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and routine wellness check-ups.', 'href' => route('services.vet-care'), 'category' => 'Service', 'icon' => 'veterinary-care'],
            ['id' => 'service-training', 'type' => 'service', 'title' => 'Dog Training', 'description' => 'Positive-reinforcement programmes for puppies and adult dogs of all breeds.', 'href' => route('services.training'), 'category' => 'Service', 'icon' => 'training'],
            ['id' => 'service-relocation', 'type' => 'service', 'title' => 'Pet Relocation', 'description' => 'International moves with full documentation and airline coordination.', 'href' => route('services.relocation'), 'category' => 'Service', 'icon' => 'airport-departure'],
        ];

        foreach (config('waggies_tools.catalogue', []) as $tool) {
            $items[] = [
                'id' => 'tool-'.$tool['id'],
                'type' => 'page',
                'title' => $tool['name'],
                'description' => $tool['description'],
                'href' => route($tool['route']),
                'category' => 'Tool',
                'icon' => $tool['icon'],
            ];
        }

        foreach (Guide::query()->indexable()->orderBy('id')->get() as $guide) {
            $items[] = [
                'id' => 'guide-'.$guide->slug,
                'type' => 'editorial',
                'title' => $guide->title,
                'description' => $guide->excerpt,
                'href' => route('guides.show', ['slug' => $guide->slug]),
                'category' => 'Guide',
                'icon' => 'guide',
            ];
        }

        foreach (KnowledgeArticle::query()->indexable()->orderBy('sort_order')->orderBy('id')->get() as $article) {
            $items[] = [
                'id' => 'knowledge-base-'.$article->slug,
                'type' => 'editorial',
                'title' => $article->title,
                'description' => $article->excerpt,
                'href' => route('knowledge-base.show', ['slug' => $article->slug]),
                'category' => 'Knowledge Base',
                'icon' => 'training',
            ];
        }

        foreach (Product::query()->published()->orderBy('sort_order')->orderBy('name')->get() as $product) {
            $items[] = [
                'id' => 'product-'.$product->slug,
                'type' => 'product',
                'title' => $product->name,
                'description' => $product->description,
                'href' => route('shop.show', ['product' => $product->slug]),
                'category' => 'Product',
                'icon' => 'shopping-bag',
            ];
        }

        foreach (Faq::query()
            ->published()
            ->where('category', '!=', 'services')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get() as $faq) {
            $items[] = [
                'id' => 'faq-'.$faq->id,
                'type' => 'faq',
                'title' => $faq->question,
                'description' => $faq->answer,
                'href' => route('faq', ['category' => $faq->category]),
                'category' => 'FAQ',
                'icon' => 'help',
            ];
        }

        $needle = mb_strtolower($query);
        $results = collect($items)
            ->map(function (array $item) use ($needle): array {
                $title = mb_strtolower($item['title']);
                $text = mb_strtolower($item['title'].' '.$item['description']);
                $score = str_starts_with($title, $needle) ? 100 : (str_contains($title, $needle) ? 60 : (str_contains($text, $needle) ? 20 : 0));

                return [...$item, 'score' => $score];
            })
            ->filter(fn (array $item): bool => $item['score'] > 0)
            ->sortByDesc('score')
            ->take(8)
            ->values()
            ->all();

        return response()->json(['query' => $query, 'results' => $results])->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
