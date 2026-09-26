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
        Schema::create('gallery_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('category', 80);
            $table->text('image')->nullable();
            $table->string('image_alt');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'sort_order']);
            $table->index(['status', 'published_at']);
        });

        $now = now();
        $aboutPagesFixture = require database_path('seeders/fixtures/about_pages.php');
        $galleryImages = $aboutPagesFixture['gallery']['images'] ?? [];
        $items = array_map(
            static fn (array $image, int $index): array => [
                'id' => (string) Str::uuid7(),
                'category' => $image['category'],
                'image' => $image['src'] ?? null,
                'image_alt' => $image['alt'],
                'caption' => $image['groupTitle'] ?? null,
                'sort_order' => $index,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $galleryImages,
            array_keys($galleryImages),
        );

        if ($items !== []) {
            DB::table('gallery_items')->insert($items);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
