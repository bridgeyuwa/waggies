<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->unsignedBigInteger('price');
            $table->string('currency', 3)->default('NGN');
            $table->string('category', 80);
            $table->text('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('badge', 40)->nullable();
            $table->json('features');
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['category', 'sort_order']);
        });

        $now = now();

        DB::table('products')->insert(array_map(
            static fn (array $product, int $index): array => [
                'id' => (string) Str::uuid7(),
                'name' => $product['name'],
                'slug' => $product['id'],
                'description' => $product['description'],
                'price' => $product['price'],
                'currency' => 'NGN',
                'category' => $product['category'],
                'image' => $product['image'],
                'image_alt' => $product['alt'],
                'badge' => $product['badge'],
                'features' => json_encode($product['features'], JSON_THROW_ON_ERROR),
                'status' => 'published',
                'sort_order' => $index,
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                ['id' => 'royal-canin-puppy', 'name' => 'Royal Canin Puppy Dry Food', 'description' => 'Complete and balanced dry food for puppies up to 12 months. Supports healthy growth with adapted protein, calcium, and phosphorus content.', 'price' => 18500, 'category' => 'Food', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'alt' => 'Royal Canin Puppy Dry Food', 'badge' => 'Bestseller', 'features' => ['Suitable for puppies up to 12 months', 'Supports immune system development', 'Highly digestible proteins', 'Contains DHA for brain development']],
                ['id' => 'whiskas-cat-food', 'name' => 'Whiskas Adult Cat Food', 'description' => 'Crunchy kibble with filled pockets for adult cats. Provides complete nutrition with the right balance of vitamins and minerals.', 'price' => 9200, 'category' => 'Food', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'alt' => 'Whiskas Adult Cat Food', 'badge' => null, 'features' => ['For adult cats aged 1-7 years', 'Contains essential fatty acids', 'Supports healthy skin and coat', 'Crunchy texture helps reduce plaque']],
                ['id' => 'chew-rope-toy', 'name' => 'Indestructible Chew Rope Toy', 'description' => 'Durable cotton rope toy for dogs of all sizes. Helps clean teeth during play and withstands aggressive chewing.', 'price' => 4500, 'category' => 'Toys', 'image' => '/media/editorial/photo-1535294435445-d7249524ef2e.jpg', 'alt' => 'Indestructible Chew Rope Toy', 'badge' => 'New', 'features' => ['100% natural cotton fibres', 'Helps clean teeth and massage gums', 'Suitable for medium to large dogs', 'Machine washable']],
                ['id' => 'interactive-puzzle-feeder', 'name' => 'Interactive Puzzle Feeder', 'description' => 'Mental stimulation toy that dispenses treats as your pet solves the puzzle. Slows down fast eaters and reduces boredom.', 'price' => 7800, 'category' => 'Toys', 'image' => '/media/editorial/photo-1601758228041-f3b2795255f1.jpg', 'alt' => 'Interactive Puzzle Feeder', 'badge' => null, 'features' => ['Adjustable difficulty levels', 'Works with most treat sizes', 'Non-slip rubber base', 'BPA-free food-grade materials']],
                ['id' => 'oatmeal-shampoo', 'name' => 'Oatmeal Soothing Pet Shampoo', 'description' => 'Gentle, soap-free shampoo with colloidal oatmeal. Relieves itching and dry skin while leaving the coat clean and soft.', 'price' => 6500, 'category' => 'Grooming', 'image' => '/media/editorial/photo-1516734212186-a967f81ad0d7.jpg', 'alt' => 'Oatmeal Soothing Pet Shampoo', 'badge' => 'Bestseller', 'features' => ['Soap-free and pH balanced', 'Colloidal oatmeal formula', 'Safe for puppies and kittens', 'Fresh lavender scent']],
                ['id' => 'deshedding-brush', 'name' => 'Professional Deshedding Brush', 'description' => 'Reduces shedding by up to 90% with stainless steel edge that reaches deep beneath the topcoat. Suitable for dogs and cats.', 'price' => 5800, 'category' => 'Grooming', 'image' => '/media/editorial/photo-1587300003388-59208cc962cb.jpg', 'alt' => 'Professional Deshedding Brush', 'badge' => null, 'features' => ['Stainless steel edge', 'Ergonomic handle', 'Reduces shedding up to 90%', 'For dogs and cats']],
                ['id' => 'tick-flea-collar', 'name' => 'Tick and Flea Prevention Collar', 'description' => '8-month continuous protection against ticks, fleas, and lice. Water-resistant collar with adjustable buckle for a secure fit.', 'price' => 11200, 'category' => 'Health', 'image' => '/media/editorial/photo-1548199973-03cce0bbc87b.jpg', 'alt' => 'Tick and Flea Prevention Collar', 'badge' => 'New', 'features' => ['8 months of continuous protection', 'Water-resistant design', 'Adjustable length for all breeds', 'Odourless and non-greasy']],
                ['id' => 'pet-first-aid-kit', 'name' => 'Pet First Aid Kit', 'description' => 'Compact first aid kit with essential supplies for minor pet injuries. Includes bandages, antiseptic wipes, tweezers, and a guide.', 'price' => 14500, 'category' => 'Health', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'alt' => 'Pet First Aid Kit', 'badge' => null, 'features' => ['25-piece essential supply set', 'Includes first aid guide', 'Compact travel-friendly case', 'Suitable for dogs, cats, and small pets']],
                ['id' => 'padded-dog-harness', 'name' => 'Padded No-Pull Dog Harness', 'description' => 'Breathable mesh harness with front clip to discourage pulling. Padded chest and belly straps for all-day comfort.', 'price' => 9800, 'category' => 'Accessories', 'image' => '/media/editorial/photo-1587300003388-59208cc962cb.jpg', 'alt' => 'Padded No-Pull Dog Harness', 'badge' => 'Bestseller', 'features' => ['No-pull front clip design', 'Breathable mesh padding', '4 adjustment points for custom fit', 'Reflective stitching for visibility']],
                ['id' => 'raised-pet-bowl', 'name' => 'Elevated Stainless Steel Pet Bowl', 'description' => 'Raised feeding bowl with stand to promote better posture and aid digestion. Detachable stainless steel bowl is easy to clean.', 'price' => 6200, 'category' => 'Accessories', 'image' => '/media/editorial/photo-1589924691995-400dc9ecc119.jpg', 'alt' => 'Elevated Stainless Steel Pet Bowl', 'badge' => null, 'features' => ['15-degree tilted design for digestion', 'Detachable stainless steel bowl', 'Anti-slip rubber base', 'Available in small and large sizes']],
            ],
            array_keys([
                'royal-canin-puppy',
                'whiskas-cat-food',
                'chew-rope-toy',
                'interactive-puzzle-feeder',
                'oatmeal-shampoo',
                'deshedding-brush',
                'tick-flea-collar',
                'pet-first-aid-kit',
                'padded-dog-harness',
                'raised-pet-bowl',
            ])
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
