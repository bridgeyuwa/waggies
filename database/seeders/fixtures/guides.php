<?php

return [
    'categories' => ['Pet Care', 'Boarding'],

    'items' => [
        [
            'id' => 2,
            'slug' => 'preparing-pet-boarding',
            'title' => 'How to Prepare Your Pet for Boarding',
            'excerpt' => 'A practical guide to preparing your dog or cat for a comfortable boarding request, from records and food to emergency contacts and collection.',
            'category' => 'Pet Care',
            'image' => '/media/guides/preparing-pet-boarding/cover.jpg',
            'imageAlt' => 'Pet relaxing comfortably at home',
            'readTime' => '6 min read',
            'content' => <<<'HTML'
<h2>Choosing the Right Facility</h2>
<p>Ask about the care routine, admission process, how special needs are reviewed, and what happens if a pet appears unwell. A reputable facility should explain the arrangements before you submit a booking request.</p>

<h3>Step 1: Share health information</h3>
<p>Provide the records and health information Waggies requests during review. Requirements can depend on the pet, route, and current operational guidance, so do not assume that one fixed list applies to every stay.</p>

<h3>Step 2: Pack their essentials</h3>
<p>Bring enough of your pet's usual food for the requested stay, clear feeding instructions, regular medication with written directions, and a familiar comfort item if helpful. Label everything with your pet's name.</p>

<h3>Step 3: Provide detailed instructions</h3>
<p>Tell the care team about feeding routines, dietary restrictions, medication, behaviour, escape risk, bite history, anxiety, handling difficulties, and any other special-care needs.</p>

<h3>Step 4: Prepare your emergency contacts</h3>
<p>Provide one primary and one secondary emergency contact, along with your emergency veterinary authorization preference. Waggies may decline admission or require further review where a pet appears ill, contagious, unsafe to handle, or otherwise unsuitable for the requested arrangement.</p>

<h3>Step 5: Plan check-in and pickup</h3>
<p>Confirm the check-in and pickup timing with Waggies. The checkout date is not another overnight stay unless the pet remains beyond the agreed cutoff. Late pickup may result in a late fee or an additional night under the confirmed policy.</p>
HTML,
        ],
        [
            'id' => 1,
            'slug' => 'what-to-expect-boarding',
            'title' => "What to Expect During Your Pet's Boarding Stay",
            'excerpt' => 'An overview of routine boarding care at Waggies, including feeding instructions, cleaning, welfare checks, and communication during review.',
            'category' => 'Boarding',
            'image' => '/media/guides/what-to-expect-boarding/cover.jpg',
            'imageAlt' => 'Dog enjoying a comfortable boarding stay',
            'readTime' => null,
            'content' => <<<'HTML'
<h2>A routine built around your pet</h2>
<p>Boarding requests are reviewed individually. Each boarding pet receives an individual enclosure, water, routine cleaning, basic welfare checks, and care based on the feeding instructions supplied by the owner.</p>

<h2>Food and daily care</h2>
<p>Owner-supplied food is the default. If food is not supplied, Waggies may provide approved food only after confirmation. Medication, special handling, intensive supervision, and other special-care needs require staff review and may carry a separate charge or quote.</p>

<h2>Communication</h2>
<p>Waggies confirms the communication arrangements for each request during review. Daily photos, scheduled updates, outdoor walks, structured play, and 24/7 supervision are not standard promises unless separately confirmed.</p>

<h2>If your pet becomes unwell</h2>
<p>Waggies may decline admission when a pet appears ill or has an active contagious condition or parasite concern. If a pet becomes unwell during a stay, the team will contact the owner or emergency contacts and coordinate veterinary assessment as appropriate.</p>
HTML,
        ],
    ],
];
