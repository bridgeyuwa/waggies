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
            ->where('category', '!=', 'services')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
        $categories = collect($faqs)->pluck('category')->unique()->values()->all();

        $categoryMeta = [
            'boarding' => ['title' => 'Boarding FAQs', 'description' => 'Frequently asked questions about pet boarding at Waggies Abuja — check-in requirements, daily updates, stay durations, shared boarding, and what happens if your pet becomes unwell.'],
            'grooming' => ['title' => 'Grooming FAQs', 'description' => 'Frequently asked questions about pet grooming at Waggies Abuja — cat and dog grooming, appointment booking, grooming duration, and available services.'],
            'vet-care' => ['title' => 'Veterinary Care FAQs', 'description' => 'Frequently asked questions about veterinary care at Waggies Abuja — appointments, vaccinations, microchipping, and emergency care.'],
            'training' => ['title' => 'Training FAQs', 'description' => 'Frequently asked questions about dog training at Waggies Abuja — training methods, programme structure, and behaviour modification.'],
            'relocation' => ['title' => 'Pet Relocation FAQs', 'description' => 'Frequently asked questions about pet relocation at Waggies Abuja — import and export permits, documentation, timelines, and airport pickup.'],
            'transport' => ['title' => 'Local Transport FAQs', 'description' => 'Frequently asked questions about local pet transport at Waggies Abuja — door-to-door pickup, vehicle standards, and booking.'],
            'general' => ['title' => 'General FAQs', 'description' => 'General frequently asked questions about Waggies Abuja — location, opening hours, booking process, and breed policies.'],
        ];
        $category = (string) $request->query('category', '');
        $category = $category === '' ? null : $category;
        $meta = $categoryMeta[$category] ?? ['title' => 'Frequently Asked Questions', 'description' => 'Quick answers to the questions we hear most often about Waggies pet boarding, grooming, vet care, training, relocation and local transport services in Abuja.'];
        $isPublishedCategory = isset($categoryMeta[$category]);
        $canonical = $isPublishedCategory ? route('faq', ['category' => $category]) : route('faq');
        $schemaFaqs = $isPublishedCategory
            ? array_values(array_filter($faqs, static fn (array $faq): bool => $faq['category'] === $category))
            : $faqs;
        $metadata = [
            'title' => $meta['title'],
            'description' => $meta['description'],
            'canonical' => $canonical,
            'ogTitle' => $meta['title'].' - Waggies',
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
                'title' => 'Frequently Asked Questions',
                'description' => "Quick answers to the questions we hear most often. Can't find what you need? Contact us directly.",
            ],
            'faqs' => $faqs,
            'categories' => $categories,
        ]);
    }
}
