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
            ->where('question', 'How do I submit a booking request?')
            ->delete();

        DB::table('faqs')
            ->where('category', 'general')
            ->where('question', 'Is my booking confirmed when I submit the request?')
            ->update([
                'question' => 'Is my booking confirmed when I submit an online request?',
                'answer' => 'No. An online request only confirms that Waggies has received it. The team reviews availability and final pricing, then shares payment instructions and confirms the booking.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'Do you handle both import and export?')
            ->update([
                'answer' => 'Yes. Waggies coordinates pet imports into Nigeria and exports from Nigeria, with each request reviewed for the route through Nnamdi Azikiwe International Airport in Abuja. Requests can come from elsewhere in Nigeria; available arrangements are confirmed in the quote.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'How early should I start planning a pet relocation?')
            ->update([
                'answer' => 'Start as early as possible, ideally several months before your planned travel date. Requirements and processing times vary by route, so Waggies reviews the timeline for your request.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'What documents are required to import a pet?')
            ->update([
                'answer' => 'Requirements depend on the country your pet is travelling from and the route. Waggies reviews the applicable documents for your request and confirms what will be needed for that journey.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'What is the Nigerian import permit process?')
            ->update([
                'answer' => 'Permit requirements and timing depend on your pet’s origin and route. Waggies reviews the applicable requirements and confirms the steps and timeline for your request.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->whereIn('category', ['grooming', 'training', 'transport', 'services'])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $legacyFaqs = [
            ['id' => '01a0dad1-4079-7128-a5a9-dc9472306016', 'category' => 'grooming', 'question' => 'Do you groom cats as well as dogs?', 'answer' => 'Yes, we groom both dogs and cats. We also offer grooming for rabbits and some other small animals  -  contact us to discuss your pet\'s needs.', 'sort_order' => 6],
            ['id' => '01a0dad1-4079-7128-a5a9-dc94732836a0', 'category' => 'grooming', 'question' => 'How long does a full groom take?', 'answer' => 'A full groom typically takes 2-4 hours depending on the breed, coat condition, and the specific services booked.', 'sort_order' => 7],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9473433c62', 'category' => 'grooming', 'question' => 'Do I need to book grooming in advance?', 'answer' => 'Yes  -  we recommend booking at least 48 hours in advance to secure your preferred time slot. Weekend slots fill up quickly.', 'sort_order' => 8],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9473a667a6', 'category' => 'grooming', 'question' => 'What grooming services do you offer?', 'answer' => 'We offer full baths, breed-specific haircuts, nail trimming, ear cleaning, teeth brushing, de-shedding treatments, and more. Ask us about a bespoke package.', 'sort_order' => 9],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9487cbe452', 'category' => 'services', 'question' => 'How do I know which service my pet needs?', 'answer' => 'If you are unsure, start with a consultation or contact us. Our team will recommend the right service based on your pet’s age, health, and behaviour.', 'sort_order' => 47],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9488a991ec', 'category' => 'services', 'question' => 'Are all services safe for my pet?', 'answer' => 'Yes. Every service follows strict vet-supervised safety standards and trained handlers. Safety is built into every part of our system.', 'sort_order' => 48],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9488ea26a0', 'category' => 'services', 'question' => 'Can I switch or combine services later?', 'answer' => 'Yes. Many clients combine grooming, boarding, and vet care depending on their pet’s needs. We can adjust plans anytime.', 'sort_order' => 49],
            ['id' => '01a0dad1-4079-7128-a5a9-dc94898f92ea', 'category' => 'services', 'question' => 'Do I need a consultation before booking?', 'answer' => 'Not always. Some services can be booked directly, but consultations help us recommend the safest and most effective care plan.', 'sort_order' => 50],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9489e6a85b', 'category' => 'services', 'question' => 'How do I get updates about my pet?', 'answer' => 'Update arrangements vary by service and are confirmed during request review. Boarding photos and status updates are not a standard promise.', 'sort_order' => 51],
            ['id' => '01a0dad1-d12a-72c6-ab87-009aaff3a9cd', 'category' => 'services', 'question' => 'What happens after I book a service?', 'answer' => 'Our team contacts you to confirm details, prepare your pet’s care plan, and guide you through the next steps.', 'sort_order' => 52],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9483fa01f8', 'category' => 'training', 'question' => 'What age can I start training my puppy?', 'answer' => 'Puppies can begin gentle, positive training from 8 weeks old. Early socialisation and foundation skills are the most important investment you can make in your puppy.', 'sort_order' => 39],
            ['id' => '01a0dad1-4079-7128-a5a9-dc948448d61a', 'category' => 'training', 'question' => 'How many sessions will my dog need?', 'answer' => 'It depends on your goals and your dog\'s starting point. Most dogs see clear progress after 4-6 sessions. Behaviour modification cases may take longer  -  we\'ll give you an honest assessment at the start.', 'sort_order' => 40],
            ['id' => '01a0dad1-4079-7128-a5a9-dc94853036f3', 'category' => 'training', 'question' => 'Do you use punishment or aversive tools?', 'answer' => 'Never. We use exclusively reward-based, force-free methods. No shock collars, prong collars, or intimidation-based techniques.', 'sort_order' => 41],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9485e93f0a', 'category' => 'training', 'question' => 'Can I be present during training sessions?', 'answer' => 'Yes  -  and for most programmes we strongly encourage owner participation. Training works best when you understand the techniques and can reinforce them consistently at home.', 'sort_order' => 42],
            ['id' => '01a0dad1-4079-7128-a5a9-dc94869dec0c', 'category' => 'transport', 'question' => 'Which areas of Abuja do you cover?', 'answer' => 'We cover all major districts including Maitama, Wuse, Asokoro, Garki, Gwarinpa, Jabi, Life Camp, and surrounding areas. Contact us if you are unsure whether your location is covered.', 'sort_order' => 43],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9487485a45', 'category' => 'transport', 'question' => 'How far in advance should I book transport?', 'answer' => 'We recommend booking at least 24 hours in advance to secure your preferred time slot. Same-day bookings are subject to availability.', 'sort_order' => 44],
            ['id' => '01a0dad1-4079-7128-a5a9-dc94877a5756', 'category' => 'transport', 'question' => 'Are the vehicles air-conditioned?', 'answer' => 'Yes  -  all our transport vehicles are fully air-conditioned and fitted with approved pet crates and harnesses for your pet\'s safety and comfort during transit.', 'sort_order' => 45],
            ['id' => '01a0dad1-4079-7128-a5a9-dc9487afdede', 'category' => 'transport', 'question' => 'Can I track my pet during transport?', 'answer' => 'Yes  -  our drivers send a WhatsApp update when they collect your pet and again on arrival at the destination. You can also call our team at any point during transit.', 'sort_order' => 46],
            ['id' => '01a107ab-cd6f-710f-a47d-9725b95ff325', 'category' => 'general', 'question' => 'How do I submit a booking request?', 'answer' => 'Call us to discuss availability and your pet’s needs. If everything is in order, the team can confirm your booking during the call. You can also WhatsApp us or submit a request online; the team will review it and follow up with a quote or next steps.', 'sort_order' => 17],
        ];

        $now = now();
        $faqsToRestore = array_map(static fn (array $faq): array => [
            ...$faq,
            'subcategory' => null,
            'status' => 'published',
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ], $legacyFaqs);

        DB::table('faqs')->insertOrIgnore($faqsToRestore);

        DB::table('faqs')
            ->where('category', 'general')
            ->where('question', 'Is my booking confirmed when I submit an online request?')
            ->update([
                'question' => 'Is my booking confirmed when I submit the request?',
                'answer' => 'No. Submission acknowledges that Waggies received your request. Availability, final price, payment instructions, and confirmation are handled manually.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'Do you handle both import and export?')
            ->update([
                'answer' => 'Yes  -  we manage both importing pets into Nigeria and exporting them to destinations worldwide.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'How early should I start planning a pet relocation?')
            ->update([
                'answer' => 'We recommend contacting us at least 3 months before your travel date. Some import permits can take 6-8 weeks to process.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'What documents are required to import a pet?')
            ->update([
                'answer' => 'At minimum: a valid health certificate, proof of vaccinations, and an import permit from the Nigerian NAQS. Requirements vary by species and country of origin.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'What is the Nigerian import permit process?')
            ->update([
                'answer' => 'An import permit must be obtained from the Nigerian Agricultural Quarantine Service (NAQS) before your pet travels. This typically takes 3-6 weeks. We apply on your behalf once you engage our services.',
                'updated_at' => now(),
            ]);
    }
};
