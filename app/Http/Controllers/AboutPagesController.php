<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class AboutPagesController extends Controller
{
    public function testimonials(): View
    {
        $page = config('waggies_about_pages.testimonials');
        $metadata = ['title' => 'Client Testimonials', 'description' => 'Read what pet owners across Abuja share about their Waggies boarding, grooming, vet care, training and relocation experience.', 'canonical' => route('about.testimonials'), 'ogTitle' => 'Client Testimonials - Waggies Pet Care Abuja', 'ogDescription' => 'Read what pet owners across Abuja share about their Waggies boarding, grooming, vet care, training and relocation experience.'];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.testimonials', $metadata + [
            'navSection' => 'about',
            ...$page,
        ]);
    }

    public function gallery(): View
    {
        $page = config('waggies_about_pages.gallery');
        $metadata = ['title' => 'Photo Gallery', 'description' => "Browse Waggies' boarding suites, grooming spa, veterinary clinic, training grounds, and happy guest photos.", 'canonical' => route('about.gallery'), 'ogTitle' => 'Photo Gallery - Waggies Pet Care Abuja', 'ogDescription' => "Browse Waggies' boarding suites, grooming spa, veterinary clinic, training grounds, and happy guest photos."];
        $this->setPageHead($metadata, [$this->webPageSchema($metadata)]);

        return view('pages.about.gallery', $metadata + [
            'navSection' => 'about',
            ...$page,
            'categories' => collect($page['images'])->pluck('category')->unique()->values()->all(),
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
