<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('guides')
            ->where('slug', 'grooming-services-explained')
            ->where('title', 'Understanding Our Grooming Services')
            ->where('status', 'published')
            ->update([
                'status' => 'archived',
                'is_indexable' => false,
                'include_in_sitemap' => false,
            ]);

        DB::table('guides')
            ->where('slug', 'pet-transport-what-to-know')
            ->where('status', 'published')
            ->update([
                'title' => 'Pet Travel: Arranging a Route-Specific Journey',
                'excerpt' => 'Learn what to confirm before pet travel, including carrier requirements, provider arrangements, and route-specific rules for your destination.',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('guides')
            ->where('slug', 'grooming-services-explained')
            ->where('title', 'Understanding Our Grooming Services')
            ->where('status', 'archived')
            ->update([
                'status' => 'published',
                'is_indexable' => true,
                'include_in_sitemap' => true,
            ]);

        DB::table('guides')
            ->where('slug', 'pet-transport-what-to-know')
            ->where('title', 'Pet Travel: Arranging a Route-Specific Journey')
            ->update([
                'title' => 'Pet Transport: What You Need to Know',
                'excerpt' => 'Whether you\'re moving across Abuja or booking door-to-door service, here\'s what to expect from professional pet transport and how to prepare your pet for the journey.',
            ]);
    }
};
