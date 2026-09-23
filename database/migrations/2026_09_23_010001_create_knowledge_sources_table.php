<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('source_key', 191)->unique();
            $table->string('title', 255);
            $table->string('source_type', 80);
            $table->string('source_reference', 255);
            $table->string('public_url', 2048)->nullable();
            $table->string('content_hash', 64);
            $table->string('status', 40)->default('approved');
            $table->timestamp('synced_at')->nullable();
            $table->string('provider_file_id', 255)->nullable();
            $table->string('vector_store_id', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'source_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_sources');
    }
};
