<?php

use App\Models\Testimonial;
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
        Schema::create('testimonials_batch_25', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('service', 80);
            $table->string('title', 120)->nullable();
            $table->text('story');
            $table->string('author_name', 120);
            $table->string('author_location', 120)->nullable();
            $table->string('pet_name', 60)->nullable();
            $table->string('pet_type', 40)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('consented_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['service', 'sort_order']);
        });

        $now = now();

        foreach (DB::table('testimonials')->orderBy('id')->get() as $index => $testimonial) {
            DB::table('testimonials_batch_25')->insert([
                'id' => (string) Str::uuid7(),
                'rating' => $testimonial->rating,
                'service' => Testimonial::normalizeService($testimonial->service),
                'title' => $testimonial->title,
                'story' => $testimonial->story,
                'author_name' => $testimonial->author_name,
                'author_location' => $testimonial->author_location,
                'pet_name' => $testimonial->pet_name,
                'pet_type' => $testimonial->pet_type,
                'photo_path' => $testimonial->photo_path,
                'status' => $testimonial->status,
                'consented_at' => $testimonial->consented_at,
                'published_at' => $testimonial->published_at,
                'sort_order' => $index,
                'created_at' => $testimonial->created_at ?? $now,
                'updated_at' => $testimonial->updated_at ?? $now,
            ]);
        }

        $aboutPagesFixture = require database_path('seeders/fixtures/about_pages.php');

        foreach ($aboutPagesFixture['testimonials']['items'] ?? [] as $index => $item) {
            DB::table('testimonials_batch_25')->insert([
                'id' => (string) Str::uuid7(),
                'rating' => $item['stars'] ?? 5,
                'service' => $item['service'],
                'title' => null,
                'story' => $item['quote'],
                'author_name' => $item['authorName'],
                'author_location' => $item['authorSubtitle'] ?? null,
                'pet_name' => null,
                'pet_type' => null,
                'photo_path' => null,
                'status' => 'approved',
                'consented_at' => null,
                'published_at' => $now,
                'sort_order' => 100 + $index,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::drop('testimonials');
        Schema::rename('testimonials_batch_25', 'testimonials');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
