<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knowledge_article_slug_histories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('knowledge_article_id');
            $table->string('slug')->unique();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('knowledge_article_id')->references('id')->on('knowledge_articles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_article_slug_histories');
    }
};
