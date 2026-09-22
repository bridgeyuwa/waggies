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
        $this->convertLegacyArticleTables();

        Schema::create('guide_slug_histories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('guide_id');
            $table->string('slug')->unique();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('guide_id')->references('id')->on('guides')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_slug_histories');
    }

    private function convertLegacyArticleTables(): void
    {
        if ($this->hasLegacyIntegerKey('guides')) {
            $this->rebuildGuidesTable();
        }

        if ($this->hasLegacyIntegerKey('knowledge_articles')) {
            $this->rebuildKnowledgeArticlesTable();
        }
    }

    private function hasLegacyIntegerKey(string $table): bool
    {
        if (! Schema::hasTable($table)) {
            return false;
        }

        return in_array(Schema::getColumnType($table, 'id'), [
            'bigint',
            'integer',
            'int8',
            'serial',
            'bigserial',
        ], true);
    }

    private function rebuildGuidesTable(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::rename('guides', 'guides_legacy');
        DB::statement('DROP INDEX IF EXISTS guides_slug_unique');
        DB::statement('DROP INDEX IF EXISTS guides_category_index');
        DB::statement('DROP INDEX IF EXISTS guides_status_index');
        DB::statement('DROP INDEX IF EXISTS guides_published_at_index');

        Schema::create('guides', function (Blueprint $table): void {
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

        foreach (DB::table('guides_legacy')->get() as $guide) {
            $attributes = (array) $guide;
            unset($attributes['id']);
            $attributes['id'] = (string) Str::uuid7();
            DB::table('guides')->insert($attributes);
        }

        Schema::drop('guides_legacy');
        Schema::enableForeignKeyConstraints();
    }

    private function rebuildKnowledgeArticlesTable(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::rename('knowledge_articles', 'knowledge_articles_legacy');
        DB::statement('DROP INDEX IF EXISTS knowledge_articles_slug_unique');
        DB::statement('DROP INDEX IF EXISTS knowledge_articles_category_index');
        DB::statement('DROP INDEX IF EXISTS knowledge_articles_sort_order_index');
        DB::statement('DROP INDEX IF EXISTS knowledge_articles_status_index');
        DB::statement('DROP INDEX IF EXISTS knowledge_articles_published_at_index');

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

        foreach (DB::table('knowledge_articles_legacy')->get() as $article) {
            $attributes = (array) $article;
            unset($attributes['id']);
            $attributes['id'] = (string) Str::uuid7();
            $attributes['image_alt'] ??= $attributes['title'];
            DB::table('knowledge_articles')->insert($attributes);
        }

        Schema::drop('knowledge_articles_legacy');
        Schema::enableForeignKeyConstraints();
    }
};
