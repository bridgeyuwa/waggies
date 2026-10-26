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
            ->where('question', 'Can you collect my pet directly from the airport cargo terminal?')
            ->update([
                'answer' => 'Airport support depends on the route. For imports through Nnamdi Azikiwe International Airport in Abuja, Waggies can arrange airport pickup where available. For exports, Waggies can coordinate transport to the departure airport. Your route-specific quote confirms the arrangements, providers, and costs. Waggies does not offer door-to-door delivery.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'general')
            ->where('question', 'How do I make a booking?')
            ->update([
                'answer' => 'Call or WhatsApp us, or submit a request through the website. Waggies reviews availability, care needs, and pricing, then contacts you with a quote or next steps. A call or request does not confirm your booking; wait for confirmation from our team.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'general')
            ->where('question', 'How do I submit a booking request?')
            ->update([
                'answer' => 'Call or WhatsApp us, or submit a request through the website. Waggies reviews availability, care needs, and pricing, then contacts you with a quote or next steps. A call or request does not confirm your booking; wait for confirmation from our team.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'general')
            ->where('question', 'What are your opening hours?')
            ->update([
                'answer' => 'Our general hours are 9am–5pm on workdays and 10am–2pm on weekends. Boarding drop-off and pick-up are available every day from 9am–5pm.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'vet-care')
            ->where('question', 'Do I need an appointment to see the veterinary team?')
            ->update([
                'answer' => 'Submit a veterinary request with your preferred appointment date and what your pet needs. The veterinary team reviews the request and confirms the appropriate next step.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'vet-care')
            ->where('question', 'Do I need an appointment to see the vet?')
            ->where('status', 'published')
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
        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'Can you collect my pet directly from the airport cargo terminal?')
            ->where('answer', 'Airport support depends on the route. For imports through Nnamdi Azikiwe International Airport in Abuja, Waggies can arrange airport pickup where available. For exports, Waggies can coordinate transport to the departure airport. Your route-specific quote confirms the arrangements, providers, and costs. Waggies does not offer door-to-door delivery.')
            ->update([
                'answer' => 'Yes  -  airport collection from Nnamdi Azikiwe International Airport is included in our full import service. We handle all customs and NAQS clearance on arrival.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'general')
            ->whereIn('question', ['How do I make a booking?', 'How do I submit a booking request?'])
            ->where('answer', 'Call or WhatsApp us, or submit a request through the website. Waggies reviews availability, care needs, and pricing, then contacts you with a quote or next steps. A call or request does not confirm your booking; wait for confirmation from our team.')
            ->update([
                'answer' => DB::raw("CASE question WHEN 'How do I make a booking?' THEN 'Contact us via phone, WhatsApp, or the contact form on this website. We''ll confirm availability and send all the details you need.' ELSE 'Submit a Booking Request through the website. Waggies will review availability, care needs, pricing, and next steps before sending a quote or confirmation.' END"),
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'general')
            ->where('question', 'What are your opening hours?')
            ->where('answer', 'Our general hours are 9am–5pm on workdays and 10am–2pm on weekends. Boarding drop-off and pick-up are available every day from 9am–5pm.')
            ->update([
                'answer' => 'We are open 7 days a week. Mon-Fri 9am-5pm, Saturday-Sunday 10am-2pm. Boarding guests receive 24/7 supervision.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'vet-care')
            ->where('question', 'Do I need an appointment to see the veterinary team?')
            ->where('answer', 'Submit a veterinary request with your preferred appointment date and what your pet needs. The veterinary team reviews the request and confirms the appropriate next step.')
            ->update([
                'answer' => 'Submit a veterinary request with your preferred timing and what your pet needs. The veterinary team reviews the request and confirms the appropriate next step.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'vet-care')
            ->where('question', 'Do I need an appointment to see the vet?')
            ->where('status', 'archived')
            ->update([
                'status' => 'published',
                'published_at' => '2026-09-26 00:05:41',
                'updated_at' => now(),
            ]);
    }
};
