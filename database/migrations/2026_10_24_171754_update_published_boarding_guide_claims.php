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
            ->where('slug', 'preparing-pet-boarding')
            ->update([
                'content' => <<<'HTML'
<h2>Choosing the Right Facility</h2>
<p>Ask about the care routine, admission process, how special needs are reviewed, and what happens if a pet appears unwell. A reputable facility should explain the arrangements before you submit a booking request.</p>

<h3>Step 1: Share health information</h3>
<p>Current vaccination records must be reviewed before the stay is confirmed. If they are not ready with your request, tell the team so they can follow up through the existing channel.</p>

<h3>Step 2: Pack their essentials</h3>
<p>Bring enough of your pet's usual food for the requested stay and clear feeding instructions. Medication is reviewed case by case; if accepted, provide it in its original labelled container with written dosage and timing instructions. Ask staff whether familiar bedding or toys can be accommodated.</p>

<h3>Step 3: Provide detailed instructions</h3>
<p>Tell the care team about feeding routines, dietary restrictions, medication, behaviour, escape risk, bite history, anxiety, handling difficulties, and any other special-care needs.</p>

<h3>Step 4: Prepare your emergency contacts</h3>
<p>Provide one primary and one secondary emergency contact, along with your emergency veterinary authorization preference. Waggies may decline admission or require further review where a pet appears ill, contagious, unsafe to handle, or otherwise unsuitable for the requested arrangement.</p>

<h3>Step 5: Plan check-in and pickup</h3>
<p>Drop-off and pick-up are available from 9:00am to 5:00pm every day. Boarding is charged per pet per overnight stay. Share your preferred arrival and collection times in your request so the team can review and confirm the arrangements.</p>
HTML,
                'updated_at' => now(),
            ]);

        DB::table('guides')
            ->where('slug', 'what-to-expect-boarding')
            ->update([
                'content' => <<<'HTML'
<h2>A routine built around your pet</h2>
<p>Boarding requests are reviewed individually. Each pet has an assigned carrier for resting in an air-conditioned facility, with water, routine cleaning, basic welfare checks, and care based on the feeding instructions supplied by the owner. A Waggies staff member and veterinarian are onsite overnight.</p>

<h2>Food and daily care</h2>
<p>Owner-supplied food is the default. If food is not supplied, Waggies may provide approved food only after confirmation. Medication and other special-care needs are reviewed case by case. If medication is accepted, provide it in its original labelled container with written dosage and timing instructions.</p>

<h2>Communication</h2>
<p>Waggies confirms communication arrangements for each request during review. Photo or video updates and a set schedule are not standard promises; mention any update request so the team can confirm what can be arranged.</p>

<h2>If your pet becomes unwell</h2>
<p>Waggies may decline admission when a pet appears ill or has an active contagious condition or parasite concern. If a pet becomes unwell during a stay, the team will contact the owner or emergency contacts and coordinate veterinary assessment as appropriate. Emergency veterinary authorization is part of the boarding request.</p>
HTML,
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not restore outdated boarding claims.
    }
};
