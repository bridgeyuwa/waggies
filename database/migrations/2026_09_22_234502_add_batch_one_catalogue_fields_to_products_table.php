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
        Schema::table('products', function (Blueprint $table): void {
            $table->string('availability', 20)->default('available')->after('status');
            $table->string('seo_title', 160)->nullable()->after('published_at');
            $table->string('seo_description', 255)->nullable()->after('seo_title');
            $table->index(['availability', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex(['availability', 'status']);
            $table->dropColumn(['availability', 'seo_title', 'seo_description']);
        });
    }
};
