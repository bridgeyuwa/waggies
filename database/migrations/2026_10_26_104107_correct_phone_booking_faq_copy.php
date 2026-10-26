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
            ->where('category', 'general')
            ->whereIn('question', ['How do I make a booking?', 'How do I submit a booking request?'])
            ->where('answer', 'Call or WhatsApp us, or submit a request through the website. Waggies reviews availability, care needs, and pricing, then contacts you with a quote or next steps. A call or request does not confirm your booking; wait for confirmation from our team.')
            ->update([
                'answer' => 'Call us to discuss availability and your pet’s needs. If everything is in order, the team can confirm your booking during the call. You can also WhatsApp us or submit a request online; the team will review it and follow up with a quote or next steps.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('faqs')
            ->where('category', 'general')
            ->whereIn('question', ['How do I make a booking?', 'How do I submit a booking request?'])
            ->where('answer', 'Call us to discuss availability and your pet’s needs. If everything is in order, the team can confirm your booking during the call. You can also WhatsApp us or submit a request online; the team will review it and follow up with a quote or next steps.')
            ->update([
                'answer' => 'Call or WhatsApp us, or submit a request through the website. Waggies reviews availability, care needs, and pricing, then contacts you with a quote or next steps. A call or request does not confirm your booking; wait for confirmation from our team.',
                'updated_at' => now(),
            ]);
    }
};
