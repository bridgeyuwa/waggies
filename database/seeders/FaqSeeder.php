<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Boarding
            ['category' => 'boarding', 'sort_order' => 1, 'question' => 'What do I need to bring for check-in?', 'answer' => "Your pet's vaccination records, any regular medication, their usual food if preferred, and a comfort item such as a blanket or toy."],
            ['category' => 'boarding', 'sort_order' => 2, 'question' => 'How often will I receive updates on my pet?', 'answer' => 'We send a photo update every day, along with a brief note on how your pet is doing. You can also message us directly at any time.'],
            ['category' => 'boarding', 'sort_order' => 3, 'question' => 'Can my dogs stay together?', 'answer' => 'Yes — if your dogs are known to each other and you request shared boarding, we can accommodate this. Otherwise, pets are housed individually.'],
            ['category' => 'boarding', 'sort_order' => 4, 'question' => 'What happens if my pet becomes unwell during their stay?', 'answer' => 'Our on-site vet will assess your pet immediately. We will contact you as soon as possible and follow your instructions on next steps.'],
            ['category' => 'boarding', 'sort_order' => 5, 'question' => 'What are your minimum and maximum stay durations?', 'answer' => 'We accommodate stays from a single overnight to several weeks. For extended stays, we schedule weekly check-in calls with owners.'],

            // Grooming
            ['category' => 'grooming', 'sort_order' => 1, 'question' => 'Do you groom cats as well as dogs?', 'answer' => "Yes, we groom both dogs and cats. We also offer grooming for rabbits and some other small animals — contact us to discuss your pet's needs."],
            ['category' => 'grooming', 'sort_order' => 2, 'question' => 'How long does a full groom take?', 'answer' => 'A full groom typically takes 2–4 hours depending on the breed, coat condition, and the specific services booked.'],
            ['category' => 'grooming', 'sort_order' => 3, 'question' => 'Do I need to book grooming in advance?', 'answer' => 'Yes — we recommend booking at least 48 hours in advance to secure your preferred time slot. Weekend slots fill up quickly.'],
            ['category' => 'grooming', 'sort_order' => 4, 'question' => 'What grooming services do you offer?', 'answer' => 'We offer full baths, breed-specific haircuts, nail trimming, ear cleaning, teeth brushing, de-shedding treatments, and more. Ask us about a bespoke package.'],

            // Relocation
            ['category' => 'relocation', 'sort_order' => 1, 'question' => 'How early should I start planning a pet relocation?', 'answer' => 'We recommend contacting us at least 3 months before your travel date. Some import permits can take 6–8 weeks to process.'],
            ['category' => 'relocation', 'sort_order' => 2, 'question' => 'Do you handle both import and export?', 'answer' => 'Yes — we manage both importing pets into Nigeria and exporting them to destinations worldwide.'],
            ['category' => 'relocation', 'sort_order' => 3, 'question' => 'What documents are required to import a pet?', 'answer' => 'At minimum: a valid health certificate, proof of vaccinations, and an import permit from the Nigerian NAQS. Requirements vary by species and country of origin.'],
            ['category' => 'relocation', 'sort_order' => 4, 'question' => 'Do you provide airport pickup and drop-off for pets?', 'answer' => 'Yes — we offer full door-to-door relocation including airport pickup/drop-off, customs clearance handling, and post-arrival monitoring.'],

            // Vet Care
            ['category' => 'vet-care', 'sort_order' => 1, 'question' => 'Do I need an appointment to see the vet?', 'answer' => 'Routine check-ups require an appointment. For emergencies, we see all pets as quickly as possible — call us immediately and we will triage your case.'],
            ['category' => 'vet-care', 'sort_order' => 2, 'question' => 'What vaccinations do you administer?', 'answer' => 'We administer all core vaccines for dogs and cats (rabies, parvovirus, distemper, etc.) as well as lifestyle vaccines. We will advise the right schedule for your pet.'],
            ['category' => 'vet-care', 'sort_order' => 3, 'question' => 'Do you offer pet microchipping?', 'answer' => 'Yes — microchipping is available at the clinic and takes just a few minutes. It is also a legal requirement for certain types of travel.'],

            // General
            ['category' => 'general', 'sort_order' => 1, 'question' => 'Where are you located?', 'answer' => 'We are based in Abuja, Nigeria. Contact us for our exact address and directions.'],
            ['category' => 'general', 'sort_order' => 2, 'question' => 'What are your opening hours?', 'answer' => 'Our facility is open 7 days a week. Office hours are 8am–6pm. Boarding guests receive 24/7 supervision.'],
            ['category' => 'general', 'sort_order' => 3, 'question' => 'How do I make a booking?', 'answer' => "Contact us via phone, WhatsApp, or the contact form on this website. We'll confirm availability and send all the details you need."],
            ['category' => 'general', 'sort_order' => 4, 'question' => 'Do you accept all dog breeds?', 'answer' => 'Yes — we welcome all breeds. For very large or high-energy breeds, please mention this when booking so we can ensure the right space and staffing.'],
        ];

        // Boarding — Dogs specific
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'dogs', 'sort_order' => 10, 'question' => 'How much exercise will my dog get each day?', 'answer' => 'Dogs receive at least two structured outdoor exercise sessions per day, plus free-play time. High-energy breeds receive additional activity tailored to their needs.'];
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'dogs', 'sort_order' => 11, 'question' => 'Can my dog socialise with other dogs?', 'answer' => 'Yes — we run carefully supervised group play sessions for dogs that enjoy company, matched by size and temperament. You can opt your dog out of group play if you prefer individual sessions only.'];
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'dogs', 'sort_order' => 12, 'question' => 'What if my dog has a special diet or health condition?', 'answer' => 'Please let us know at booking. We follow all dietary instructions precisely and can administer medication if required. Our on-site vet is available daily for any health concerns.'];

        // Boarding — Cats specific
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'cats', 'sort_order' => 10, 'question' => 'Is the cat area completely separate from the dogs?', 'answer' => 'Yes — our cat wing is a fully separate, dog-free zone. Cats never share corridors or outdoor areas with dogs, reducing stress significantly.'];
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'cats', 'sort_order' => 11, 'question' => 'My cat is very shy — will she be okay?', 'answer' => 'Absolutely. Shy cats are given extra time to settle at their own pace. We never force interaction and provide plenty of hiding spots and elevated perches in every condo.'];
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'cats', 'sort_order' => 12, 'question' => 'Can I bring my cat\'s own bedding and toys?', 'answer' => 'Yes — we encourage it. Familiar scents are very comforting for cats in a new environment. Label all items with your cat\'s name.'];

        // Boarding — Exotic specific
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'exotic', 'sort_order' => 10, 'question' => 'Do you have experience with reptiles?', 'answer' => 'Yes — our exotic team includes staff trained specifically in reptile care. We maintain correct temperature gradients, humidity, and UV lighting for each species.'];
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'exotic', 'sort_order' => 11, 'question' => 'Can you handle live feeding for my snake or reptile?', 'answer' => 'Yes — if your pet requires live or frozen feeders, please bring sufficient supply and provide feeding instructions. We follow your routine precisely.'];
        $faqs[] = ['category' => 'boarding', 'subcategory' => 'exotic', 'sort_order' => 12, 'question' => 'What species do you NOT board?', 'answer' => 'We do not board venomous species, large constrictors over 3m, or wild/protected animals. Please contact us if you are unsure about your specific pet — we will advise honestly.'];

        // Relocation — Import specific
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'import', 'sort_order' => 10, 'question' => 'What is the Nigerian import permit process?', 'answer' => 'An import permit must be obtained from the Nigerian Agricultural Quarantine Service (NAQS) before your pet travels. This typically takes 3–6 weeks. We apply on your behalf once you engage our services.'];
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'import', 'sort_order' => 11, 'question' => 'Is quarantine required when bringing a pet to Nigeria?', 'answer' => 'Nigeria does not mandate a fixed quarantine period, but all arriving pets undergo an inspection at the airport by NAQS officials. Having correct documentation ensures this is straightforward.'];
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'import', 'sort_order' => 12, 'question' => 'Can you collect my pet directly from the airport cargo terminal?', 'answer' => 'Yes — airport collection from Nnamdi Azikiwe International Airport is included in our full import service. We handle all customs and NAQS clearance on arrival.'];

        // Relocation — Export specific
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'export', 'sort_order' => 10, 'question' => 'What health certificate do I need to export my pet from Nigeria?', 'answer' => 'An official veterinary health certificate endorsed by NAQS is required. Our on-site vet completes the examination and paperwork. The certificate is typically valid for 10 days from issue, so timing with your travel date is important.'];
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'export', 'sort_order' => 11, 'question' => 'Do entry requirements vary by destination country?', 'answer' => 'Yes — requirements vary significantly. The EU, UK, US, and other countries each have different rules on vaccinations, titre tests, and waiting periods. We research your specific destination and build a compliance plan.'];
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'export', 'sort_order' => 12, 'question' => 'Can my pet travel in the cabin with me?', 'answer' => 'This depends entirely on the airline and the size of your pet. Small pets may qualify for cabin travel on some airlines. We liaise with your airline to identify the best and safest option.'];

        // Relocation — Local specific
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'local', 'sort_order' => 10, 'question' => 'Do you help with moving pets to other Nigerian cities?', 'answer' => 'Yes — we coordinate domestic pet relocation across Nigeria including Lagos, Port Harcourt, Kano, and other major cities, by road or domestic air cargo.'];
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'local', 'sort_order' => 11, 'question' => 'What documents are needed for domestic pet travel in Nigeria?', 'answer' => 'A veterinary health certificate and up-to-date vaccination record are recommended for domestic travel, and required by most domestic airlines for cargo shipment.'];
        $faqs[] = ['category' => 'relocation', 'subcategory' => 'local', 'sort_order' => 12, 'question' => 'Can you handle door-to-door domestic relocation?', 'answer' => 'Yes — we offer end-to-end domestic relocation including collection, health certification, transport or flight coordination, and delivery to the destination address.'];

        // Training
        $faqs[] = ['category' => 'training', 'sort_order' => 1, 'question' => 'What age can I start training my puppy?', 'answer' => 'Puppies can begin gentle, positive training from 8 weeks old. Early socialisation and foundation skills are the most important investment you can make in your puppy.'];
        $faqs[] = ['category' => 'training', 'sort_order' => 2, 'question' => 'How many sessions will my dog need?', 'answer' => 'It depends on your goals and your dog\'s starting point. Most dogs see clear progress after 4–6 sessions. Behaviour modification cases may take longer — we\'ll give you an honest assessment at the start.'];
        $faqs[] = ['category' => 'training', 'sort_order' => 3, 'question' => 'Do you use punishment or aversive tools?', 'answer' => 'Never. We use exclusively reward-based, force-free methods. No shock collars, prong collars, or intimidation-based techniques.'];
        $faqs[] = ['category' => 'training', 'sort_order' => 4, 'question' => 'Can I be present during training sessions?', 'answer' => 'Yes — and for most programmes we strongly encourage owner participation. Training works best when you understand the techniques and can reinforce them consistently at home.'];

        // Transport
        $faqs[] = ['category' => 'transport', 'sort_order' => 1, 'question' => 'Which areas of Abuja do you cover?', 'answer' => 'We cover all major districts including Maitama, Wuse, Asokoro, Garki, Gwarinpa, Jabi, Life Camp, and surrounding areas. Contact us if you are unsure whether your location is covered.'];
        $faqs[] = ['category' => 'transport', 'sort_order' => 2, 'question' => 'How far in advance should I book transport?', 'answer' => 'We recommend booking at least 24 hours in advance to secure your preferred time slot. Same-day bookings are subject to availability.'];
        $faqs[] = ['category' => 'transport', 'sort_order' => 3, 'question' => 'Are the vehicles air-conditioned?', 'answer' => 'Yes — all our transport vehicles are fully air-conditioned and fitted with approved pet crates and harnesses for your pet\'s safety and comfort during transit.'];
        $faqs[] = ['category' => 'transport', 'sort_order' => 4, 'question' => 'Can I track my pet during transport?', 'answer' => 'Yes — our drivers send a WhatsApp update when they collect your pet and again on arrival at the destination. You can also call our team at any point during transit.'];

        foreach ($faqs as $data) {
            Faq::firstOrCreate(
                ['question' => $data['question']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
