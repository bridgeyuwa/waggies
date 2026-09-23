<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_documents', function (Blueprint $table): void {
            $table->id();
            $table->string('source_type', 50);
            $table->string('source_key', 191);
            $table->string('title', 255);
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('url', 2048);
            $table->string('section', 80)->nullable();
            $table->string('category', 80)->nullable();
            $table->text('keywords')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('searchable')->default(true);
            $table->unsignedSmallInteger('boost')->default(1);
            $table->timestamps();

            $table->unique(['source_type', 'source_key']);
            $table->index(['searchable', 'published_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_documents');
    }
};
