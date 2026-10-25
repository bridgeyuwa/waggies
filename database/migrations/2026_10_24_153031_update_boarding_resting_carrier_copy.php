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
            ->where('question', 'What do I need to bring for check-in?')
            ->update([
                'answer' => 'Current vaccination records must be reviewed before the stay is confirmed. If they are not ready with your request, let the team know so they can follow up through the existing channel. Medication is reviewed case by case; if accepted, provide it in its original labelled container with written dosage and timing instructions. Bring your pet\'s usual food if preferred, clear feeding instructions, and emergency contact details.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'How are pets cared for during boarding?')
            ->update([
                'answer' => 'Each boarding pet has an assigned carrier for resting. Boarding also includes water, routine cleaning, basic welfare checks, and care based on the owner-supplied feeding instructions.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'Can my dogs stay together?')
            ->update([
                'answer' => 'Shared stays are not guaranteed. If you have more than one dog, mention it in your request so staff can review the arrangements available before confirming.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'How much exercise will my dog get each day?')
            ->update([
                'answer' => 'Boarding dogs have access to Waggies’ secure outdoor yard. Specific exercise arrangements depend on your dog’s needs and what staff can support during the stay. Share your dog’s exercise needs in your request so staff can review them before confirmation.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'Can I bring my cat\'s own bedding and toys?')
            ->update([
                'answer' => 'Please mention any bedding or toys you would like to bring when you submit your request. Staff will confirm whether they can be accommodated before your cat’s stay.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'What happens if my pet becomes unwell during their stay?')
            ->update([
                'answer' => 'Waggies will contact the owner or emergency contacts and coordinate veterinary assessment as appropriate. Emergency veterinary authorization is part of the boarding request process.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'What are your minimum and maximum stay durations?')
            ->update([
                'answer' => 'Boarding is charged by overnight stay. There is no artificial 30-night maximum; longer stays remain subject to manual availability review.',
                'updated_at' => now(),
            ]);

        DB::table('guides')
            ->where('slug', 'what-to-expect-boarding')
            ->where('content', 'like', '%Each boarding pet receives an individual enclosure%')
            ->update([
                'content' => DB::raw("REPLACE(content, 'Each boarding pet receives an individual enclosure, water, routine cleaning, basic welfare checks, and care based on the feeding instructions supplied by the owner.', 'Each boarding pet has an assigned carrier for resting, with water, routine cleaning, basic welfare checks, and care based on the feeding instructions supplied by the owner.')"),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not restore outdated accommodation claims.
    }
};
