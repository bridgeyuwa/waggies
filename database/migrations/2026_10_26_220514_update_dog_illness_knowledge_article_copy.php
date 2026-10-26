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
        DB::table('knowledge_articles')
            ->where('slug', 'understanding-common-dog-illnesses')
            ->where('title', 'Understanding Common Dog Illnesses in Nigeria')
            ->where('status', 'published')
            ->update([
                'title' => 'Common Dog Illnesses: Signs and When to See a Vet',
                'excerpt' => 'Illness can show up as changes in appetite, energy, breathing or behaviour. Learn about tick-borne infections, parvovirus and heat illness, and when to contact a vet.',
                'seo_title' => 'Common Dog Illnesses: Signs and When to See a Vet',
                'seo_description' => 'Learn to spot signs of dog illness, understand tick-borne infections, parvovirus and heat illness, and know when your dog needs veterinary care.',
                'content' => <<<'HTML'
<h2>Recognising signs of illness</h2>
<p>Dogs can become ill for many reasons, and early signs can be easy to miss. A change in appetite or energy, vomiting, diarrhoea, coughing, unusual thirst, or a change in behaviour can signal a health problem. These signs can have several causes, so this guide can help you spot concerns—but only a veterinarian can diagnose your dog.</p>

<h2>Tick-borne infections</h2>
<p>Ticks can spread infections such as babesiosis and ehrlichiosis. Signs may include fever, weakness, reduced appetite, pale gums, or unusual bruising or bleeding. These signs can also occur with other illnesses, so a veterinarian may need to examine and test your dog.</p>

<p>Ask your veterinarian which tick-prevention option is suitable for your dog’s age, health and environment. Check your dog’s coat regularly, especially after time in areas where ticks are present.</p>

<h2>Heat-related illness</h2>
<p>Heavy panting, drooling, seeking shade or reluctance to move can be early signs that a dog is overheating. Move your dog to a cool, shaded place and offer water. If the panting continues, or your dog has difficulty breathing, becomes weak or confused, vomits, collapses or has a seizure, contact a veterinarian immediately. Heatstroke is an emergency.</p>

<p>Provide shade and water, plan walks for cooler parts of the day, and never leave a dog in a parked vehicle.</p>

<h2>Parvovirus</h2>
<p>Canine parvovirus is highly contagious and can cause severe illness, especially in puppies and dogs that are not fully vaccinated. It spreads through infected faeces and contaminated surfaces. Signs can include low energy, loss of appetite, vomiting and diarrhoea, which may become bloody.</p>

<p>If a puppy or unvaccinated dog develops vomiting or diarrhoea, contact a veterinarian promptly. Vaccination is an important protection. Ask your veterinarian to set the right schedule for your dog, including a puppy vaccination series and follow-up based on age and risk.</p>

<h2>Other changes to watch for</h2>
<p>Changes in drinking or urination, persistent itching, ear or eye discharge, coughing, limping, unexplained weight change, or unusual behaviour can also be signs of illness. A single sign may have several causes. Note when it began and whether it is getting worse, then contact your veterinarian for advice.</p>

<h2>When to contact a veterinarian</h2>
<p>Seek urgent veterinary care for difficulty breathing, collapse, seizures, heavy bleeding, suspected poisoning, severe pain, or repeated vomiting or diarrhoea—especially in a puppy, older dog, or dog with an existing health condition.</p>

<p>For other new symptoms, contact your veterinarian if they persist, recur or worsen. Don’t rely on a fixed waiting period if your dog seems seriously unwell.</p>

<p>If you need veterinary care in Abuja, contact Waggies to discuss your dog’s symptoms and the next step.</p>
HTML,
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('knowledge_articles')
            ->where('slug', 'understanding-common-dog-illnesses')
            ->where('title', 'Common Dog Illnesses: Signs and When to See a Vet')
            ->where('seo_title', 'Common Dog Illnesses: Signs and When to See a Vet')
            ->update([
                'title' => 'Understanding Common Dog Illnesses in Nigeria',
                'excerpt' => 'From tick-borne diseases to heat exhaustion, Nigerian pet owners face unique health challenges. Learn to recognise early warning signs and when to seek veterinary care.',
                'seo_title' => null,
                'seo_description' => null,
                'content' => <<<'HTML'
<h2>Why Awareness Matters</h2>
<p>Nigeria's tropical climate creates conditions that favour certain illnesses in dogs, from tick-borne infections like ehrlichiosis to heat-related conditions that can escalate quickly. As a responsible pet owner in Abuja, recognising the early signs of illness can make the difference between a quick recovery and a serious health crisis.</p>
<h2>Tick-Borne Diseases</h2>
<p>Ehrlichiosis and babesiosis are two of the most common tick-borne diseases affecting dogs in Nigeria. Symptoms include fever, lethargy, loss of appetite, pale gums, and in severe cases, nosebleeds. Ticks thrive in Abuja's warm, humid environment, making year-round prevention essential. Use vet-approved tick and flea prevention products consistently, and check your dog's coat after walks in grassy areas.</p>
<h2>Heat Exhaustion and Dehydration</h2>
<p>Abuja's dry season temperatures can exceed 38°C. Dogs cool themselves primarily through panting, which is far less efficient than human sweating. Signs of heat exhaustion include excessive panting, drooling, weakness, and confusion. Always provide fresh water, avoid midday walks, and never leave your dog in a parked vehicle.</p>
<h2>Parvovirus</h2>
<p>Parvovirus remains a significant threat, especially for unvaccinated puppies. It spreads through contact with infected faeces and can survive in the environment for months. Symptoms include severe vomiting, bloody diarrhoea, and rapid dehydration. Vaccination is your strongest defence—ensure your puppy completes the full vaccination schedule by 16 weeks of age.</p>
<h2>When to See Your Vet</h2>
<p>Trust your instincts. If your dog refuses food for more than 24 hours, shows sudden lethargy, vomits repeatedly, or has difficulty breathing, contact your veterinarian immediately. Early intervention almost always leads to better outcomes and lower treatment costs.</p>
HTML,
                'updated_at' => now(),
            ]);
    }
};
