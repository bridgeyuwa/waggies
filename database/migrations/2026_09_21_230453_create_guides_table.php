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
        Schema::create('guides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->string('category')->index();
            $table->text('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('read_time')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_indexable')->default(true);
            $table->boolean('include_in_sitemap')->default(true);
            $table->timestamps();
        });

        $now = now();
        $guides = array_map(
            static fn (array $guide): array => [
                'id' => (string) Str::uuid7(),
                'title' => $guide['title'],
                'slug' => $guide['slug'],
                'excerpt' => $guide['excerpt'],
                'category' => $guide['category'],
                'image' => $guide['image'],
                'image_alt' => $guide['imageAlt'],
                'read_time' => $guide['readTime'],
                'content' => $guide['content'],
                'status' => 'published',
                'published_at' => $now,
                'seo_title' => null,
                'seo_description' => null,
                'is_indexable' => true,
                'include_in_sitemap' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            config('waggies_guides.items', []),
        );

        if ($guides !== []) {
            DB::table('guides')->insert($guides);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
