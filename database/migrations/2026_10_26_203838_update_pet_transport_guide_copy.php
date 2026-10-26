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
        DB::table('guides')
            ->where('slug', 'pet-transport-what-to-know')
            ->where('title', 'Pet Travel: Arranging a Route-Specific Journey')
            ->where('status', 'published')
            ->update([
                'title' => 'Pet Transport: How to Plan Your Pet’s Journey',
                'excerpt' => 'Plan pet transport by road or air with practical steps for checking requirements, preparing your pet, choosing a provider, and organizing each handoff.',
                'seo_title' => 'Pet Transport Guide: Plan Your Pet’s Journey | Waggies',
                'seo_description' => 'Planning pet transport? Compare road and air options, prepare your pet, and plan the route, documents, carrier, and handoffs before you travel.',
                'content' => <<<'HTML'
<p>Pet transport can mean a short drive, a long-distance journey, a flight with your pet, or a move across borders. The right preparation depends on the route, travel method, transport provider, and your pet’s needs.</p>

<p>Use this guide to plan the journey and identify what to confirm before you travel. Requirements differ between providers and destinations, so check the current rules for your specific trip.</p>

<h2>1. Map the whole journey</h2>
<p>Write down where the journey begins, where your pet must arrive, the travel dates, and any stops or transfers. Include the parts before and after the main trip. A journey by air, for example, may also involve transport to the airport, a transfer, and collection after arrival.</p>

<p>Then confirm how your pet will travel: with you in a vehicle, with you on a flight, or separately through a transport provider. Each option has its own rules and arrangements. Do not assume a passenger booking includes a place for your pet.</p>

<h2>2. Start planning early</h2>
<p>Check requirements as soon as you know travel may be needed. Some steps must happen in a specific order, and certain documents, tests, or approvals may take time. The timeline depends on the route, travel method, provider, and your pet.</p>

<p>Before setting a travel date, ask what must be completed first, how long each step may take, and when the arrangements can be confirmed. Leave time to respond if a document needs correction or a travel plan changes.</p>

<h2>3. Check the rules and documents</h2>
<p>Ask the airline or transport provider what it requires. For international travel, also check the current requirements set by the relevant authorities for the places your pet will leave, pass through, and enter.</p>

<p>Depending on the trip, requirements may involve identification, health records, permits, veterinary documents, tests, inspections, or quarantine. Not every pet or route requires the same documents. Confirm who must issue each document, when it must be completed, and whether an original must travel with your pet.</p>

<p>Keep a written checklist. Store copies of important records and confirmations where you can access them during the journey.</p>

<h2>4. Discuss your pet’s travel needs with a veterinarian</h2>
<p>Ask your veterinarian whether your pet is ready for the planned journey and what preparation they recommend. Discuss health conditions, age, previous travel difficulties, medication, and any care your pet may need during the trip.</p>

<p>Ask for specific advice about food, water, exercise, and medication before and during travel. Do not give your pet a sedative or other travel medication unless your veterinarian advises it and the carrier or provider permits it.</p>

<h2>5. Choose and prepare a carrier</h2>
<p>Check the airline’s or provider’s carrier requirements before buying or preparing one. Size, construction, ventilation, security, and labels may differ by travel method and provider.</p>

<p>Give your pet time to become familiar with the carrier before the trip. Start with short, calm sessions at home. For road travel, practise with short journeys where appropriate. Add identification and contact details as instructed. Follow the provider’s directions about bedding, food, and water containers.</p>

<h2>6. Confirm the provider’s role</h2>
<p>If another person or company will transport your pet, confirm who handles each part of the journey. Ask which tasks they perform themselves and which they arrange through other providers.</p>

<p>Before you agree to the plan, ask:</p>
<ul>
<li>Which parts of the route are included?</li>
<li>Who handles check-in, transfers, and collection?</li>
<li>Where and when will your pet be handed over?</li>
<li>What documents and carrier specifications are required?</li>
<li>What is included in the quote, and what may cost extra?</li>
<li>How will you be told about delays or changes?</li>
<li>What happens if a connection is missed or the travel date changes?</li>
<li>Who should you contact if there is an urgent concern?</li>
</ul>

<p>Ask for the route, timing, handoffs, included services, and costs in writing.</p>

<h2>7. Plan ground travel carefully</h2>
<p>Use a secure carrier or restraint that suits your pet and vehicle. Keep your pet from moving freely around the driver. For a long journey, plan suitable stops and follow your veterinarian’s advice about your pet’s needs. Keep your pet under control during stops to reduce the risk of escape.</p>

<p>Never leave your pet alone in a parked vehicle. Plan the journey so your pet is not left unattended while you make other arrangements.</p>

<h2>8. Prepare for travel day and arrival</h2>
<p>Before departure, check the itinerary, handover location, collection arrangements, required documents, and provider contact details. Follow the instructions you received about food, water, medication, and exercise.</p>

<p>At the destination, confirm who is authorised to collect your pet and whether any release or inspection steps remain. Once reunited, give your pet time to settle. Contact a veterinarian if you are concerned about your pet’s health or behaviour.</p>

<h2>Planning an international move with Waggies</h2>
<p>Waggies reviews international relocation requests for journeys through Nnamdi Azikiwe International Airport in Abuja. A route-specific quote confirms the available arrangements, providers, service scope, and costs. Sending a request does not book the move instantly. Waggies does not offer door-to-door delivery or standalone domestic city-to-city relocation.</p>
<p><a href="/book?service=relocation">Request a relocation quote</a></p>
HTML,
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('guides')
            ->where('slug', 'pet-transport-what-to-know')
            ->where('title', 'Pet Transport: How to Plan Your Pet’s Journey')
            ->where('seo_title', 'Pet Transport Guide: Plan Your Pet’s Journey | Waggies')
            ->update([
                'title' => 'Pet Travel: Arranging a Route-Specific Journey',
                'excerpt' => 'Learn what to confirm before pet travel, including carrier requirements, provider arrangements, and route-specific rules for your destination.',
                'seo_title' => null,
                'seo_description' => null,
                'content' => <<<'HTML'
<h2>How Pet Transport Works</h2>
<p>Waggies provides professional pet transport services across Abuja and surrounding areas. Our transport team uses climate-controlled vehicles equipped with secure, size-appropriate carriers. Whether you need a one-way trip to our facility or door-to-door service between locations, we ensure your pet travels safely and comfortably.</p>

<h2>Before the Journey</h2>
<p>Feed your pet a light meal two to three hours before transport—avoid feeding right before travel to prevent motion sickness. Ensure your pet has had an opportunity to relieve themselves. For dogs, a short walk before pickup helps them settle. Bring any medications your pet needs during transit, clearly labelled with dosage instructions.</p>

<h2>During Transport</h2>
<p>Pets travel in secure carriers within our vehicles. Our drivers are trained to drive smoothly and avoid sudden stops. For anxious travellers, we can provide a familiar-smelling item (like a worn t-shirt) in the carrier. The vehicle is climate-controlled to maintain a comfortable temperature regardless of Abuja’s weather conditions.</p>

<h2>Special Requirements</h2>
<p>Brachycephalic breeds (such as Bulldogs and Pugs) require extra care during transport due to their breathing difficulties. Puppies and kittens under four months, and very elderly pets, may need additional monitoring. Inform us of any health conditions, anxiety issues, or special needs when booking so we can make appropriate arrangements.</p>

<h2>Booking Transport</h2>
<p>Book pet transport at least 24 to 48 hours in advance. Provide your pickup and drop-off addresses, your pet’s breed and size, and any special requirements. We will confirm the time window and send you a driver’s contact information on the day of transport. You will receive updates when your pet is picked up and when they arrive safely at their destination.</p>
HTML,
                'updated_at' => now(),
            ]);
    }
};
