<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Guide;
use App\Models\KnowledgeArticle;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use RuntimeException;
use Spatie\MediaLibrary\HasMedia;

final class WaggiesContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBusinessProfile();
        $this->seedFaqs();
        $this->seedGuides();
        $this->seedKnowledgeArticles();
        $this->seedTestimonials();
        $this->seedGallery();
    }

    private function seedBusinessProfile(): void
    {
        $businessProfile = require database_path('seeders/fixtures/business_profile.php');

        $profile = BusinessProfile::query()->first() ?? new BusinessProfile;
        $profile->fill([
            'business_name' => $businessProfile['business_name'],
            'primary_email' => $businessProfile['primary_email'],
            'phone' => $businessProfile['phone'],
            'phone_international' => $businessProfile['phone_international'],
            'whatsapp_url' => $businessProfile['whatsapp_url'],
            'address_street' => $businessProfile['address']['street'],
            'address_city' => $businessProfile['address']['city'],
            'address_postal_code' => $businessProfile['address']['postal_code'],
            'address_state' => $businessProfile['address']['state'],
            'address_country' => $businessProfile['address']['country'],
            'map_url' => $businessProfile['map_url'],
            'timezone' => $businessProfile['timezone'],
            'instagram_url' => $businessProfile['socials']['instagram'],
            'facebook_url' => $businessProfile['socials']['facebook'],
            'x_url' => $businessProfile['socials']['x'],
            'linkedin_url' => $businessProfile['socials']['linkedin'],
            'tiktok_url' => $businessProfile['socials']['tiktok'],
            'youtube_url' => $businessProfile['socials']['youtube'],
        ]);
        $profile->save();
    }

    private function seedFaqs(): void
    {
        $faqs = require database_path('seeders/fixtures/faqs.php');

        foreach ($faqs as $sortOrder => $faq) {
            Faq::query()->firstOrCreate(
                ['question' => $faq['question']],
                [
                    'category' => $faq['category'],
                    'subcategory' => $faq['subcategory'] ?? null,
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['id'] ?? $sortOrder,
                    'status' => Faq::STATUS_PUBLISHED,
                    'published_at' => now(),
                ],
            );
        }
    }

    private function seedGuides(): void
    {
        $guides = require database_path('seeders/fixtures/guides.php');

        foreach ($guides['items'] ?? [] as $guide) {
            $record = Guide::query()->firstOrCreate(
                ['slug' => $guide['slug']],
                [
                    'title' => $guide['title'],
                    'slug' => $guide['slug'],
                    'excerpt' => $guide['excerpt'],
                    'category' => $guide['category'],
                    'image' => $guide['image'] ?? null,
                    'image_alt' => $guide['imageAlt'] ?? null,
                    'read_time' => $guide['readTime'] ?? null,
                    'content' => $guide['content'],
                    'status' => Guide::STATUS_PUBLISHED,
                    'published_at' => now(),
                    'seo_title' => null,
                    'seo_description' => null,
                    'is_indexable' => true,
                    'include_in_sitemap' => true,
                ],
            );

            $this->attachInitialMediaIfMissing($record, 'cover', $guide['image'] ?? null);
        }
    }

    private function seedKnowledgeArticles(): void
    {
        $knowledgeBase = require database_path('seeders/fixtures/knowledge_base.php');

        foreach ($knowledgeBase['items'] ?? [] as $sortOrder => $article) {
            $record = KnowledgeArticle::query()->firstOrCreate(
                ['slug' => $article['slug']],
                [
                    'title' => $article['title'],
                    'slug' => $article['slug'],
                    'excerpt' => $article['excerpt'],
                    'category' => $article['category'],
                    'author' => $article['author'] ?? null,
                    'image' => $article['image'] ?? null,
                    'image_alt' => $article['imageAlt'] ?? null,
                    'read_time' => $article['readTime'] ?? null,
                    'content' => $article['content'],
                    'sort_order' => $sortOrder,
                    'status' => KnowledgeArticle::STATUS_PUBLISHED,
                    'published_at' => $article['date'] ?? now(),
                    'seo_title' => null,
                    'seo_description' => null,
                    'is_indexable' => true,
                    'include_in_sitemap' => true,
                ],
            );

            $this->attachInitialMediaIfMissing($record, 'cover', $article['image'] ?? null);
        }
    }

    private function seedTestimonials(): void
    {
        $aboutPages = require database_path('seeders/fixtures/about_pages.php');

        foreach ($aboutPages['testimonials']['items'] ?? [] as $sortOrder => $testimonial) {
            Testimonial::query()->firstOrCreate(
                [
                    'author_name' => $testimonial['authorName'],
                    'service' => $testimonial['service'],
                    'author_location' => $testimonial['authorSubtitle'],
                ],
                [
                    'rating' => $testimonial['stars'],
                    'story' => $testimonial['quote'],
                    'consented_at' => now(),
                    'status' => Testimonial::STATUS_APPROVED,
                    'published_at' => now(),
                    'sort_order' => $sortOrder,
                ],
            );
        }
    }

    private function seedGallery(): void
    {
        $aboutPages = require database_path('seeders/fixtures/about_pages.php');

        foreach ($aboutPages['gallery']['images'] ?? [] as $sortOrder => $image) {
            $record = GalleryItem::query()->firstOrCreate(
                ['image' => $image['src']],
                [
                    'category' => $image['category'],
                    'image' => $image['src'],
                    'image_alt' => $image['alt'],
                    'caption' => $image['groupTitle'] ?? null,
                    'sort_order' => $sortOrder,
                    'status' => GalleryItem::STATUS_PUBLISHED,
                    'published_at' => now(),
                ],
            );

            $this->attachInitialMediaIfMissing($record, 'image', $image['src']);
        }
    }

    private function attachInitialMediaIfMissing(HasMedia $record, string $collection, ?string $relativePath): void
    {
        if ($record->getFirstMedia($collection) !== null || $relativePath === null || $relativePath === '') {
            return;
        }

        $sourcePath = $this->resolveFixtureMediaSource($relativePath);

        $record->addMedia($sourcePath)->preservingOriginal()->toMediaCollection($collection);
    }

    private function resolveFixtureMediaSource(string $relativePath): string
    {
        $sourcePath = public_path(ltrim($relativePath, '/'));

        if (is_file($sourcePath)) {
            return $sourcePath;
        }

        if (preg_match('#^/media/(guides|knowledge-base)/([^/]+)/cover\.jpg$#', $relativePath, $matches) === 1) {
            $cardPath = public_path("media/{$matches[1]}/card-{$matches[2]}.jpg");

            if (is_file($cardPath)) {
                return $cardPath;
            }
        }

        $fallbackPath = public_path('media/home/care-standards.jpg');

        if (is_file($fallbackPath)) {
            return $fallbackPath;
        }

        throw new RuntimeException('Seed media source does not exist for fixture path: '.$relativePath);
    }
}
