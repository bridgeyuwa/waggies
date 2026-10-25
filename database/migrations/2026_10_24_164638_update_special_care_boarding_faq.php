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
            ->where('category', 'boarding')
            ->where('question', 'What if my dog has a special diet or health condition?')
            ->update([
                'answer' => 'Please share your dog’s dietary, health, and medication needs in your request. The team reviews these case by case and confirms what they can support before the stay. Any medication accepted by the team must be in its original labelled container with written dosage and timing instructions.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not restore unsupported care and medication guarantees.
    }
};
