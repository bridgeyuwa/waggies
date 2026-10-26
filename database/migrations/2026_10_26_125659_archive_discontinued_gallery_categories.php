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
        DB::table('gallery_items')
            ->whereIn('category', ['Grooming', 'Training'])
            ->update([
                'status' => 'archived',
                'published_at' => null,
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('gallery_items')
            ->whereIn('category', ['Grooming', 'Training'])
            ->where('status', 'archived')
            ->update([
                'status' => 'published',
                'published_at' => now(),
                'updated_at' => now(),
            ]);
    }
};
