<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Testimonial;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class AboutPagesController extends Controller
{
    public function testimonials(): View
    {
        $page = config('waggies_about_pages.testimonials');
        $items = Testimonial::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get()
            ->map(fn (Testimonial $testimonial): array => $testimonial->toPublicArray())
            ->all();
        $metadata = ['title' => 'Client Testimonials', 'description' => 'Read what pet owners across Abuja share about their Waggies boarding, grooming, vet care, training and relocation experience.', 'canonical' => route('about.testimonials'), 'ogTitle' => 'Client Testimonials - Waggies Pet Care Abuja', 'ogDescription' => 'Read what pet owners across Abuja share about their Waggies boarding, grooming, vet care, training and relocation experience.'];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.testimonials', $metadata + [
            'navSection' => 'about',
            ...$page,
            'items' => $items,
        ]);
    }

    public function gallery(): View
    {
        $page = config('waggies_about_pages.gallery');
        $images = GalleryItem::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (GalleryItem $item): array => $item->toPublicArray())
            ->all();
        $metadata = ['title' => 'Photo Gallery', 'description' => "Browse Waggies' boarding suites, grooming spa, veterinary clinic, training grounds, and happy guest photos.", 'canonical' => route('about.gallery'), 'ogTitle' => 'Photo Gallery - Waggies Pet Care Abuja', 'ogDescription' => "Browse Waggies' boarding suites, grooming spa, veterinary clinic, training grounds, and happy guest photos."];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.gallery', $metadata + [
            'navSection' => 'about',
            ...$page,
            'images' => $images,
            'categories' => collect($images)->pluck('category')->unique()->values()->all(),
        ]);
    }

    public function careers(): View
    {
        $page = config('waggies_about_pages.careers');
        $metadata = ['title' => 'Careers at Waggies', 'description' => 'Join the Waggies team in Abuja. We are hiring passionate pet care professionals - view current vacancies and apply today.', 'canonical' => route('about.careers'), 'ogTitle' => 'Careers at Waggies - Join Our Pet Care Team in Abuja', 'ogDescription' => 'Join the Waggies team in Abuja. We are hiring passionate pet care professionals - view current vacancies and apply today.'];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.careers', $metadata + [
            'navSection' => 'about',
            ...$page,
        ]);
    }

    public function partnerships(): View
    {
        $page = config('waggies_about_pages.partnerships');
        $metadata = ['title' => 'Partnerships', 'description' => 'Waggies partners with veterinary clinics, pet retailers, breeders, and corporate organisations who share our commitment to animal welfare.', 'canonical' => route('about.partnerships'), 'ogTitle' => 'Partnerships - Waggies Pet Care Abuja', 'ogDescription' => 'Waggies partners with veterinary clinics, pet retailers, breeders, and corporate organisations who share our commitment to animal welfare.'];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.partnerships', $metadata + [
            'navSection' => 'about',
            ...$page,
        ]);
    }

    private function webPageSchema(array $metadata): array
    {
        return Schema::webPage()
            ->name($metadata['ogTitle'])
            ->description($metadata['description'])
            ->url($metadata['canonical'])
            ->toArray();
    }
}
