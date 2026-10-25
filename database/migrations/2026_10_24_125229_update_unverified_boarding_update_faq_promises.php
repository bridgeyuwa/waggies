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
            ->where('question', 'How often will I receive updates on my pet?')
            ->where('answer', 'We send a photo update every day, along with a brief note on how your pet is doing. You can also message us directly at any time.')
            ->update([
                'answer' => 'Boarding updates are not a standard promise. If you need an update, mention it in your request so the team can confirm whether one can be arranged before your stay is confirmed.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'services')
            ->where('question', 'How do I get updates about my pet?')
            ->where('answer', 'You receive regular updates including photos and status reports depending on the service you choose.')
            ->update([
                'answer' => 'Update arrangements vary by service and are confirmed during request review. Boarding photos and status updates are not a standard promise.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not restore unverified update promises.
    }
};
