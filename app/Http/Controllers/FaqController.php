<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Contracts\ThingContract;
use Spatie\SchemaOrg\Schema;

final class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $faqs = Faq::query()
            ->published()
            ->whereIn('category', ['boarding', 'vet-care', 'relocation', 'general'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
        $categories = collect($faqs)->pluck('category')->unique()->values()->all();
        $faqsByCategory = collect($faqs)->groupBy('category')->all();

        $categoryMeta = [
            'boarding' => ['title' => 'Boarding FAQs', 'description' => 'Frequently asked questions about pet boarding at Waggies Abuja — admission, feeding, stay durations, special care, and what happens if your pet becomes unwell.'],
            'vet-care' => ['title' => 'Veterinary Care FAQs', 'description' => 'Frequently asked questions about veterinary care at Waggies Abuja — appointments, vaccinations, microchipping, and emergency care.'],
            'relocation' => ['title' => 'Pet Relocation FAQs', 'description' => 'Frequently asked questions about pet relocation at Waggies Abuja — import and export permits, documentation, timelines, and airport pickup.'],
            'general' => ['title' => 'General FAQs', 'description' => 'General frequently asked questions about Waggies Abuja — location, opening hours, booking process, and breed policies.'],
        ];
        $category = (string) $request->query('category', '');
        $category = $category === '' ? null : $category;
        $meta = $categoryMeta[$category] ?? ['title' => 'Pet Boarding, Veterinary Care & Relocation FAQs | Waggies', 'description' => 'Planning care or travel for your pet? Find practical answers about boarding and veterinary care in Abuja, and how Waggies reviews each relocation route.'];
        $isPublishedCategory = isset($categoryMeta[$category]);
        $canonical = $isPublishedCategory ? route('faq', ['category' => $category]) : route('faq');
        $schemaFaqs = $isPublishedCategory
            ? array_values(array_filter($faqs, static fn (array $faq): bool => $faq['category'] === $category))
            : $faqs;
        $metadata = [
            'title' => $meta['title'],
            'description' => $meta['description'],
            'canonical' => $canonical,
            'ogTitle' => str_ends_with($meta['title'], 'Waggies') ? $meta['title'] : $meta['title'].' - Waggies',
            'ogDescription' => $meta['description'],
            'robots' => $category === null || $isPublishedCategory ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $faqSchema = array_map(static fn (array $faq): ThingContract => Schema::question()
            ->name($faq['question'])
            ->acceptedAnswer(Schema::answer()->text($faq['answer'])), $schemaFaqs);
        $this->setPageHead($metadata, [
            Schema::faqPage()->mainEntity($faqSchema)->toArray(),
        ]);

        return view('pages.faq', $metadata + [
            'navSection' => 'resources',
            'hero' => [
                'imageSrc' => '/media/faq/hero.jpg',
                'imageAlt' => 'Pets relaxing together',
                'eyebrow' => 'Help Centre',
                'eyebrowIcon' => 'help',
                'title' => 'FAQs About Pet Boarding, Veterinary Care & Pet Relocation',
                'description' => 'Find out what to expect when arranging care or travel for your pet. If the details depend on your pet or route, the Waggies team will review them with you.',
            ],
            'faqs' => $faqs,
            'faqsByCategory' => $faqsByCategory,
            'categories' => $categories,
        ]);
    }
}
