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
            ->where('answer', "Current vaccination records must be reviewed before the stay is confirmed. If they are not ready with your request, let the team know so they can follow up through the existing channel. Medication is reviewed case by case; if accepted, provide it in its original labelled container with written dosage and timing instructions. Bring your pet's usual food if preferred, clear feeding instructions, and emergency contact details.")
            ->update([
                'answer' => 'Current vaccination records must be reviewed before we confirm a stay. If you don’t have them yet, mention this in your request so the team can follow up. Medication is reviewed case by case; if accepted, bring it in its original labelled container with written dosage and timing instructions. Owner-supplied food is the default, so bring your pet’s usual food and feeding instructions, along with an emergency contact.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'What do I need to bring for check-in?')
            ->where('answer', 'Current vaccination records must be reviewed before we confirm a stay. If you don’t have them yet, mention this in your request so the team can follow up. Medication is reviewed case by case; if accepted, bring it in its original labelled container with written dosage and timing instructions. Owner-supplied food is the default, so bring your pet’s usual food and feeding instructions, along with an emergency contact.')
            ->update([
                'answer' => "Current vaccination records must be reviewed before the stay is confirmed. If they are not ready with your request, let the team know so they can follow up through the existing channel. Medication is reviewed case by case; if accepted, provide it in its original labelled container with written dosage and timing instructions. Bring your pet's usual food if preferred, clear feeding instructions, and emergency contact details.",
                'updated_at' => now(),
            ]);
    }
};
