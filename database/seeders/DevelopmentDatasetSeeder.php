<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use App\Models\BusinessProfile;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Guide;
use App\Models\JobOpening;
use App\Models\KnowledgeArticle;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class DevelopmentDatasetSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $this->seedBusinessProfile();
        $this->seedBusinessHours();
        $this->seedGuides();
        $this->seedKnowledgeArticles();
        $this->seedFaqs();
        $this->seedGallery();
        $this->seedTestimonials();
        $this->seedProducts();
        $this->seedJobOpenings();
    }

    private function seedBusinessProfile(): void
    {
        $address = config('waggies.address', []);
        $socials = config('waggies.socials', []);

        $profile = BusinessProfile::query()->first() ?? new BusinessProfile;
        $profile->fill([
            'business_name' => 'Waggies',
            'primary_email' => 'hello@waggies.ng',
            'phone' => config('waggies.phone'),
            'phone_international' => config('waggies.phone_international'),
            'whatsapp_url' => config('waggies.whatsapp'),
            'address_street' => $address['street'] ?? null,
            'address_city' => $address['city'] ?? null,
            'address_postal_code' => $address['postal_code'] ?? null,
            'address_state' => $address['state'] ?? null,
            'address_country' => $address['country'] ?? null,
            'map_url' => config('waggies.map_url'),
            'timezone' => 'Africa/Lagos',
            'instagram_url' => $socials['instagram'] ?? null,
            'facebook_url' => $socials['facebook'] ?? null,
            'x_url' => $socials['x'] ?? null,
            'linkedin_url' => $socials['linkedin'] ?? null,
            'tiktok_url' => $socials['tiktok'] ?? null,
            'youtube_url' => $socials['youtube'] ?? null,
        ]);
        $profile->save();
    }

    private function seedBusinessHours(): void
    {
        foreach ([
            0 => ['10:00', '14:00'],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['10:00', '14:00'],
        ] as $dayOfWeek => [$openTime, $closeTime]) {
            BusinessHour::query()->updateOrCreate(
                [
                    'kind' => BusinessHour::KIND_WEEKLY,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'date' => null,
                    'label' => null,
                    'is_closed' => false,
                    'open_time' => $openTime,
                    'close_time' => $closeTime,
                    'second_open_time' => null,
                    'second_close_time' => null,
                ],
            );
        }
    }

    private function seedGuides(): void
    {
        foreach (config('waggies_guides.items', []) as $guide) {
            $record = Guide::query()
                ->where('slug', $guide['slug'])
                ->orWhere('title', $guide['title'])
                ->first() ?? new Guide;

            if (! $record->exists) {
                $record->id = (string) Str::uuid7();
            }

            $record->forceFill([
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
            ])->saveQuietly();
        }
    }

    private function seedKnowledgeArticles(): void
    {
        foreach (config('waggies_knowledge_base.items', []) as $sortOrder => $article) {
            $record = KnowledgeArticle::query()
                ->where('slug', $article['slug'])
                ->orWhere('title', $article['title'])
                ->first() ?? new KnowledgeArticle;

            if (! $record->exists) {
                $record->id = (string) Str::uuid7();
            }

            $record->forceFill([
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
            ])->saveQuietly();
        }
    }

    private function seedFaqs(): void
    {
        foreach (config('waggies_faqs', []) as $sortOrder => $faq) {
            Faq::query()->updateOrCreate(
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

    private function seedGallery(): void
    {
        foreach (config('waggies_about_pages.gallery.images', []) as $sortOrder => $image) {
            GalleryItem::query()->updateOrCreate(
                ['image_alt' => $image['alt']],
                [
                    'category' => $image['category'],
                    'image' => $image['src'],
                    'caption' => $image['groupTitle'] ?? null,
                    'sort_order' => $sortOrder,
                    'status' => GalleryItem::STATUS_PUBLISHED,
                    'published_at' => now(),
                ],
            );
        }
    }

    private function seedTestimonials(): void
    {
        foreach (config('waggies_about_pages.testimonials.items', []) as $sortOrder => $testimonial) {
            Testimonial::query()->updateOrCreate(
                [
                    'author_name' => $testimonial['authorName'],
                    'story' => $testimonial['quote'],
                ],
                [
                    'rating' => $testimonial['stars'],
                    'service' => $testimonial['service'],
                    'title' => (Testimonial::serviceOptions()[$testimonial['service']] ?? 'Pet care').' experience',
                    'author_location' => $testimonial['authorSubtitle'],
                    'crm_match_status' => Testimonial::CRM_MATCHED,
                    'identity_verification_status' => Testimonial::VERIFICATION_VERIFIED,
                    'customer_relationship_status' => Testimonial::VERIFICATION_VERIFIED,
                    'verification_method' => 'development_seed',
                    'verified_at' => now(),
                    'verification_notes' => 'Development dataset record.',
                    'moderated_at' => now(),
                    'pet_name' => null,
                    'pet_type' => null,
                    'photo_path' => null,
                    'consented_at' => now(),
                    'status' => Testimonial::STATUS_APPROVED,
                    'published_at' => now(),
                    'sort_order' => $sortOrder,
                ],
            );
        }
    }

    private function seedProducts(): void
    {
        foreach ([
            ['slug' => 'royal-canin-puppy', 'name' => 'Royal Canin Puppy Dry Food', 'description' => 'Complete and balanced dry food for puppies up to 12 months. Supports healthy growth with adapted protein, calcium, and phosphorus content.', 'price' => 18500, 'category' => 'Food', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'badge' => 'Bestseller', 'features' => ['Suitable for puppies up to 12 months', 'Supports immune system development', 'Highly digestible proteins', 'Contains DHA for brain development']],
            ['slug' => 'whiskas-cat-food', 'name' => 'Whiskas Adult Cat Food', 'description' => 'Crunchy kibble with filled pockets for adult cats. Provides complete nutrition with the right balance of vitamins and minerals.', 'price' => 9200, 'category' => 'Food', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'badge' => null, 'features' => ['For adult cats aged 1-7 years', 'Contains essential fatty acids', 'Supports healthy skin and coat', 'Crunchy texture helps reduce plaque']],
            ['slug' => 'chew-rope-toy', 'name' => 'Indestructible Chew Rope Toy', 'description' => 'Durable cotton rope toy for dogs of all sizes. Helps clean teeth during play and withstands aggressive chewing.', 'price' => 4500, 'category' => 'Toys', 'image' => '/media/editorial/photo-1535294435445-d7249524ef2e.jpg', 'badge' => 'New', 'features' => ['100% natural cotton fibres', 'Helps clean teeth and massage gums', 'Suitable for medium to large dogs', 'Machine washable']],
            ['slug' => 'interactive-puzzle-feeder', 'name' => 'Interactive Puzzle Feeder', 'description' => 'Mental stimulation toy that dispenses treats as your pet solves the puzzle. Slows down fast eaters and reduces boredom.', 'price' => 7800, 'category' => 'Toys', 'image' => '/media/editorial/photo-1601758228041-f3b2795255f1.jpg', 'badge' => null, 'features' => ['Adjustable difficulty levels', 'Works with most treat sizes', 'Non-slip rubber base', 'BPA-free food-grade materials']],
            ['slug' => 'oatmeal-shampoo', 'name' => 'Oatmeal Soothing Pet Shampoo', 'description' => 'Gentle, soap-free shampoo with colloidal oatmeal. Relieves itching and dry skin while leaving the coat clean and soft.', 'price' => 6500, 'category' => 'Grooming', 'image' => '/media/editorial/photo-1516734212186-a967f81ad0d7.jpg', 'badge' => 'Bestseller', 'features' => ['Soap-free and pH balanced', 'Colloidal oatmeal formula', 'Safe for puppies and kittens', 'Fresh lavender scent']],
            ['slug' => 'deshedding-brush', 'name' => 'Professional Deshedding Brush', 'description' => 'Reduces shedding by up to 90% with stainless steel edge that reaches deep beneath the topcoat. Suitable for dogs and cats.', 'price' => 5800, 'category' => 'Grooming', 'image' => '/media/editorial/photo-1587300003388-59208cc962cb.jpg', 'badge' => null, 'features' => ['Stainless steel edge', 'Ergonomic handle', 'Reduces shedding up to 90%', 'For dogs and cats']],
            ['slug' => 'tick-flea-collar', 'name' => 'Tick and Flea Prevention Collar', 'description' => '8-month continuous protection against ticks, fleas, and lice. Water-resistant collar with adjustable buckle for a secure fit.', 'price' => 11200, 'category' => 'Health', 'image' => '/media/editorial/photo-1548199973-03cce0bbc87b.jpg', 'badge' => 'New', 'features' => ['8 months of continuous protection', 'Water-resistant design', 'Adjustable length for all breeds', 'Odourless and non-greasy']],
            ['slug' => 'pet-first-aid-kit', 'name' => 'Pet First Aid Kit', 'description' => 'Compact first aid kit with essential supplies for minor pet injuries. Includes bandages, antiseptic wipes, tweezers, and a guide.', 'price' => 14500, 'category' => 'Health', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'badge' => null, 'features' => ['25-piece essential supply set', 'Includes first aid guide', 'Compact travel-friendly case', 'Suitable for dogs, cats, and small pets']],
            ['slug' => 'padded-dog-harness', 'name' => 'Padded No-Pull Dog Harness', 'description' => 'Breathable mesh harness with front clip to discourage pulling. Padded chest and belly straps for all-day comfort.', 'price' => 9800, 'category' => 'Accessories', 'image' => '/media/editorial/photo-1587300003388-59208cc962cb.jpg', 'badge' => 'Bestseller', 'features' => ['No-pull front clip design', 'Breathable mesh padding', '4 adjustment points for custom fit', 'Reflective stitching for visibility']],
            ['slug' => 'raised-pet-bowl', 'name' => 'Elevated Stainless Steel Pet Bowl', 'description' => 'Raised feeding bowl with stand to promote better posture and aid digestion. Detachable stainless steel bowl is easy to clean.', 'price' => 6200, 'category' => 'Accessories', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'badge' => null, 'features' => ['15-degree tilted design for digestion', 'Detachable stainless steel bowl', 'Anti-slip rubber base', 'Available in small and large sizes']],
        ] as $sortOrder => $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'currency' => 'NGN',
                    'category' => $product['category'],
                    'image' => $product['image'],
                    'image_alt' => $product['name'],
                    'badge' => $product['badge'],
                    'features' => $product['features'],
                    'status' => Product::STATUS_PUBLISHED,
                    'availability' => Product::AVAILABILITY_AVAILABLE,
                    'sort_order' => $sortOrder,
                    'published_at' => now(),
                    'seo_title' => null,
                    'seo_description' => null,
                ],
            );
        }
    }

    private function seedJobOpenings(): void
    {
        foreach ([
            ['title' => 'Pet Care Coordinator', 'department' => 'Client Services', 'location' => 'Life Camp, Abuja', 'employment_type' => 'Full-time', 'summary' => 'Help pet owners plan thoughtful stays, grooming visits, transport, and care journeys at Waggies.', 'description' => 'You will welcome clients, coordinate service details with the care team, and keep each pet parent informed from first enquiry through collection.', 'requirements' => 'Experience in customer service or hospitality; clear written communication; calm organisation; genuine interest in companion animal care.', 'sort_order' => 0],
            ['title' => 'Veterinary Care Assistant', 'department' => 'Veterinary Care', 'location' => 'Life Camp, Abuja', 'employment_type' => 'Full-time', 'summary' => 'Support the veterinary team with compassionate, organised day-to-day patient care.', 'description' => 'You will assist with appointment preparation, gentle handling, care-area readiness, and clear handovers while maintaining a calm environment for pets and their families.', 'requirements' => 'Relevant veterinary or animal-care experience; safe animal handling; strong hygiene habits; willingness to learn within a supervised team.', 'sort_order' => 1],
        ] as $opening) {
            JobOpening::query()->updateOrCreate(
                ['title' => $opening['title']],
                [
                    ...$opening,
                    'status' => JobOpening::STATUS_OPEN,
                    'published_at' => now(),
                    'closing_date' => null,
                    'application_email' => 'hello@waggies.ng',
                    'application_url' => null,
                ],
            );
        }
    }
}
