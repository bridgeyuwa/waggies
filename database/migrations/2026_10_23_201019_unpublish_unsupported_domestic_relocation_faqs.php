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
        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('subcategory', 'local')
            ->whereIn('question', [
                'Do you help with moving pets to other Nigerian cities?',
                'What documents are needed for domestic pet travel in Nigeria?',
            ])
            ->where('status', 'published')
            ->update([
                'status' => 'draft',
                'published_at' => null,
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not republish FAQs for a service Waggies does not offer.
    }
};
