<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_articles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->string('category')->index();
            $table->string('author')->nullable();
            $table->text('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('read_time')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_indexable')->default(true);
            $table->boolean('include_in_sitemap')->default(true);
            $table->timestamps();
        });

        $now = now();
        $configuredArticles = config('waggies_knowledge_base.items', []);
        $articles = array_map(
            static fn (array $article, int $index): array => [
                'id' => (string) Str::uuid7(),
                'title' => $article['title'],
                'slug' => $article['slug'],
                'excerpt' => $article['excerpt'],
                'category' => $article['category'],
                'author' => $article['author'] ?? null,
                'image' => $article['image'],
                'image_alt' => $article['imageAlt'] ?? $article['title'],
                'read_time' => $article['readTime'] ?? null,
                'content' => $article['content'],
                'sort_order' => $index,
                'status' => 'published',
                'published_at' => $article['date'] ?? null,
                'seo_title' => null,
                'seo_description' => null,
                'is_indexable' => true,
                'include_in_sitemap' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $configuredArticles,
            array_keys($configuredArticles),
        );

        if ($articles !== []) {
            DB::table('knowledge_articles')->insert($articles);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
    }
};
