<?php

return [
    'catalogue' => [
        ['id' => 'symptom-checker', 'name' => 'Pet Symptom Checker', 'route' => 'tools.symptom-checker', 'icon' => 'medical', 'description' => 'Select symptoms by body area and get general guidance on next steps.', 'longDescription' => "An interactive tool to help you identify potential health concerns by selecting your pet's species, the affected body area, and specific symptoms you notice.", 'category' => 'health'],
        ['id' => 'emergency-guide', 'name' => 'Emergency & Poison Guide', 'route' => 'tools.emergency', 'icon' => 'emergency', 'description' => 'Quick-reference for common pet emergencies, poisons, and what to do.', 'longDescription' => 'A reference guide covering poisonous foods, household dangers, heatstroke, choking, seizures, and bleeding - with immediate action steps and emergency contacts.', 'category' => 'health'],
        ['id' => 'parasite-schedule', 'name' => 'Parasite & Deworming Schedule', 'route' => 'tools.parasite', 'icon' => 'parasite', 'description' => 'Educational timeline for deworming puppies, kittens, and adult pets.', 'longDescription' => 'A general educational timeline showing recommended deworming intervals for puppies, kittens, adult dogs, and adult cats, with notes on travel and veterinary confirmation.', 'category' => 'health'],
        ['id' => 'medication-dosage-guide', 'name' => 'Medication & OTC Safety Guide', 'route' => 'tools.medication', 'icon' => 'medication', 'description' => 'Safety-first information about pet and human medications, formulations, and veterinary escalation.', 'longDescription' => 'A structured medication safety reference. Product-specific dosing is withheld unless the source, formulation, species, jurisdiction, and clinical review are complete.', 'category' => 'health'],
        ['id' => 'pet-age-calculator', 'name' => 'Pet Age Calculator', 'route' => 'tools.pet-age', 'icon' => 'pets', 'description' => "Convert your pet's age to approximate human years.", 'longDescription' => "Convert your dog or cat's age into approximate human years using species-specific conversion formulas that account for size and life stage.", 'category' => 'calculator'],
        ['id' => 'nutrition-calculator', 'name' => 'Diet & Nutrition Calculator', 'route' => 'tools.nutrition', 'icon' => 'nutrition', 'description' => "Estimate your pet's daily calorie needs based on weight, species, and activity level.", 'longDescription' => "Calculate your dog or cat's estimated daily calorie requirement using the RER formula, adjusted for life stage and activity level.", 'category' => 'calculator'],
        ['id' => 'vaccination-schedule', 'name' => 'Vaccination Schedule', 'route' => 'tools.vaccination', 'icon' => 'vaccination', 'description' => 'Track recommended vaccinations for dogs and cats by age.', 'longDescription' => 'An interactive vaccination schedule tracker showing recommended vaccines for puppies, kittens, adult dogs, and adult cats at each life stage.', 'category' => 'reference'],
        ['id' => 'breed-finder', 'name' => 'Breed Info Finder', 'route' => 'tools.breed-finder', 'icon' => 'search', 'description' => 'Browse popular dog and cat breeds with size, energy, grooming, and temperament info.', 'longDescription' => 'Search and filter popular dog and cat breeds by size, energy level, and grooming needs. General breed information including temperament and common health considerations.', 'category' => 'reference'],
        ['id' => 'behavior-tips', 'name' => 'Behavior & Training Tips', 'route' => 'tools.behavior-tips', 'icon' => 'behavior', 'description' => 'Practical advice for common pet behavior issues like barking, anxiety, and house training.', 'longDescription' => 'General, non-diagnostic advice for common behavior issues in dogs and cats, including barking, jumping, anxiety, aggression, house training, and destructive chewing.', 'category' => 'reference'],
        ['id' => 'new-pet-checklist', 'name' => 'New Pet Checklist', 'route' => 'tools.new-pet-checklist', 'icon' => 'checklist', 'description' => 'Interactive checklist of everything you need before and after bringing a pet home.', 'longDescription' => 'A comprehensive interactive checklist covering preparation, first week essentials, health and wellness, training, and supplies for new pet owners. Saves progress in your browser.', 'category' => 'utility'],
    ],
    'symptom_checker' => [
        'pet_types' => [
            ['id' => 'dog', 'label' => 'Dog', 'icon' => 'pets'],
            ['id' => 'cat', 'label' => 'Cat', 'icon' => 'cat'],
            ['id' => 'other', 'label' => 'Other', 'icon' => 'exotic-pet'],
        ],
        'body_areas' => [
            ['id' => 'head', 'label' => 'Head', 'icon' => 'behavior'],
            ['id' => 'eyes', 'label' => 'Eyes', 'icon' => 'visibility'],
            ['id' => 'ears', 'label' => 'Ears', 'icon' => 'hearing'],
            ['id' => 'mouth', 'label' => 'Mouth / Teeth', 'icon' => 'mouth'],
            ['id' => 'skin', 'label' => 'Skin / Coat', 'icon' => 'grooming'],
            ['id' => 'stomach', 'label' => 'Stomach / Digestive', 'icon' => 'nutrition'],
            ['id' => 'legs', 'label' => 'Legs / Paws', 'icon' => 'exercise'],
            ['id' => 'breathing', 'label' => 'Breathing', 'icon' => 'air'],
            ['id' => 'general', 'label' => 'General', 'icon' => 'veterinary-care'],
        ],
        'symptoms' => [
            'head' => [
                ['id' => 'head-tilt', 'label' => 'Head tilting'], ['id' => 'head-shaking', 'label' => 'Frequent head shaking'], ['id' => 'head-pressing', 'label' => 'Pressing head against surfaces'], ['id' => 'swelling-face', 'label' => 'Facial swelling'], ['id' => 'discharge-nose', 'label' => 'Nasal discharge'], ['id' => 'sneezing', 'label' => 'Sneezing'],
            ],
            'eyes' => [
                ['id' => 'red-eyes', 'label' => 'Red or bloodshot eyes'], ['id' => 'watery-eyes', 'label' => 'Excessive tearing'], ['id' => 'cloudy-eyes', 'label' => 'Cloudy or hazy eyes'], ['id' => 'squinting', 'label' => 'Squinting or keeping eyes closed'], ['id' => 'eye-discharge', 'label' => 'Eye discharge (yellow/green)'], ['id' => 'pawing-eyes', 'label' => 'Pawing at eyes'],
            ],
            'ears' => [
                ['id' => 'ear-odor', 'label' => 'Bad odour from ears'], ['id' => 'ear-scratching', 'label' => 'Frequent scratching at ears'], ['id' => 'ear-discharge', 'label' => 'Discharge from ears'], ['id' => 'ear-redness', 'label' => 'Red or swollen ears'], ['id' => 'head-shake-ear', 'label' => 'Head shaking (ear-related)'], ['id' => 'ear-pain', 'label' => 'Sensitivity when ears touched'],
            ],
            'mouth' => [
                ['id' => 'bad-breath', 'label' => 'Persistent bad breath'], ['id' => 'excessive-drool', 'label' => 'Excessive drooling'], ['id' => 'difficulty-eating', 'label' => 'Difficulty eating or chewing'], ['id' => 'bleeding-gums', 'label' => 'Bleeding or swollen gums'], ['id' => 'tooth-loss', 'label' => 'Loose or missing teeth'], ['id' => 'pawing-mouth', 'label' => 'Pawing at mouth'],
            ],
            'skin' => [
                ['id' => 'itching', 'label' => 'Excessive itching or scratching'], ['id' => 'hair-loss', 'label' => 'Hair loss or bald patches'], ['id' => 'rashes', 'label' => 'Redness, rashes, or hot spots'], ['id' => 'lumps', 'label' => 'Lumps or bumps on skin'], ['id' => 'dry-skin', 'label' => 'Dry, flaky skin'], ['id' => 'excessive-shedding', 'label' => 'Excessive shedding'], ['id' => 'fleas-ticks', 'label' => 'Visible fleas or ticks'],
            ],
            'stomach' => [
                ['id' => 'vomiting', 'label' => 'Vomiting'], ['id' => 'diarrhoea', 'label' => 'Diarrhoea'], ['id' => 'loss-appetite', 'label' => 'Loss of appetite'], ['id' => 'bloated-belly', 'label' => 'Bloated or swollen belly'], ['id' => 'constipation', 'label' => 'Constipation or straining'], ['id' => 'weight-loss', 'label' => 'Unexplained weight loss'], ['id' => 'excessive-thirst', 'label' => 'Excessive thirst or urination'],
            ],
            'legs' => [
                ['id' => 'limping', 'label' => 'Limping or favouring a leg'], ['id' => 'swollen-paw', 'label' => 'Swollen paws'], ['id' => 'licking-paw', 'label' => 'Excessive licking of paws'], ['id' => 'nail-issues', 'label' => 'Broken or overgrown nails'], ['id' => 'stiffness', 'label' => 'Stiffness or difficulty rising'], ['id' => 'joint-pain', 'label' => 'Apparent joint pain'],
            ],
            'breathing' => [
                ['id' => 'coughing', 'label' => 'Coughing'], ['id' => 'wheezing', 'label' => 'Wheezing or noisy breathing'], ['id' => 'rapid-breathing', 'label' => 'Rapid or shallow breathing'], ['id' => 'laboured-breathing', 'label' => 'Laboured breathing'], ['id' => 'sneezing-resp', 'label' => 'Frequent sneezing'], ['id' => 'exercise-intolerance', 'label' => 'Exercise intolerance'],
            ],
            'general' => [
                ['id' => 'lethargy', 'label' => 'Lethargy or unusual tiredness'], ['id' => 'fever', 'label' => 'Fever (warm ears, nose, body)'], ['id' => 'aggression', 'label' => 'Unexplained aggression'], ['id' => 'hiding', 'label' => 'Hiding or withdrawal'], ['id' => 'excessive-thirst-gen', 'label' => 'Increased thirst'], ['id' => 'seizures', 'label' => 'Seizures or tremors'], ['id' => 'disorientation', 'label' => 'Disorientation or confusion'],
            ],
        ],
        'red_flag_ids' => ['head-pressing', 'laboured-breathing', 'seizures', 'bloated-belly', 'bleeding-gums', 'disorientation', 'constipation'],
        'species_red_flag_ids' => [
            'cat' => ['constipation', 'laboured-breathing'],
            'dog' => ['bloated-belly', 'laboured-breathing'],
            'other' => [],
        ],
        'guidance' => [
            'routine' => ['title' => 'Routine veterinary advice', 'description' => 'This result does not diagnose a condition. Arrange veterinary advice if the sign persists, recurs, or your pet is otherwise unwell.', 'recommendations' => ['Record when the sign started and whether it is changing', 'Do not give human medication or leftover prescriptions', 'Arrange a veterinary consultation if it persists or worsens']],
            'prompt' => ['title' => 'Prompt veterinary consultation', 'description' => 'Several or persistent signs can have different causes and need an individual assessment.', 'recommendations' => ['Contact a veterinary practice promptly', 'Keep your pet quiet and observe breathing, alertness, eating, drinking, and urination', 'Do not give human medication or home remedies']],
            'urgent' => ['title' => 'Urgent veterinary assessment', 'description' => 'This combination of context and signs may represent a time-sensitive problem. This tool cannot diagnose the cause.', 'recommendations' => ['Contact a veterinary practice now for instructions', 'Prepare the symptom timeline, medication list, and any exposure or trauma details', 'Do not delay for the result to change']],
            'emergency' => ['title' => 'Emergency veterinary assessment', 'description' => 'A red-flag sign can indicate a life-threatening emergency. Seek immediate veterinary help; this tool cannot diagnose or treat it.', 'recommendations' => ['Call a veterinary practice or emergency service immediately', 'Transport your pet safely while following veterinary instructions', 'Do not give food, human medication, or home remedies unless a veterinarian directs you']],
        ],
    ],
    'new_pet_checklist' => [
        ['title' => 'Before You Bring Them Home', 'items' => [
            ['id' => 'prep-food', 'label' => 'Choose and buy appropriate food (age-specific)'], ['id' => 'prep-bowls', 'label' => 'Food and water bowls'], ['id' => 'prep-bed', 'label' => 'A bed or safe sleeping area'], ['id' => 'prep-crate', 'label' => 'A crate or carrier for transport and safe space'], ['id' => 'prep-gate', 'label' => 'Baby gates or barriers for restricted areas'], ['id' => 'prep-toxins', 'label' => 'Remove or secure toxic plants, chemicals, and small objects'], ['id' => 'prep-wires', 'label' => 'Tuck away electrical cords and cables'], ['id' => 'prep-vet', 'label' => 'Research and choose a veterinary clinic'],
        ]],
        ['title' => 'First Week Essentials', 'items' => [
            ['id' => 'week-vet-visit', 'label' => 'Schedule a vet check-up within the first few days'], ['id' => 'week-quiet', 'label' => 'Allow a quiet settling-in period'], ['id' => 'week-routine', 'label' => 'Establish a feeding and walking routine'], ['id' => 'week-toilet', 'label' => 'Set up a toilet area and begin house training'], ['id' => 'week-intro', 'label' => 'Introduce family members one at a time'], ['id' => 'week-other-pets', 'label' => 'Introduce existing pets gradually and supervised'], ['id' => 'week-microchip', 'label' => 'Check microchip registration is up to date'],
        ]],
        ['title' => 'Health & Wellness', 'items' => [
            ['id' => 'health-vax', 'label' => 'Discuss vaccination schedule with your vet'], ['id' => 'health-deworm', 'label' => 'Arrange deworming treatment'], ['id' => 'health-flea', 'label' => 'Start flea and tick prevention'], ['id' => 'health-neuter', 'label' => 'Discuss spaying/neutering timeline with your vet'], ['id' => 'health-insurance', 'label' => 'Consider pet insurance or a health savings plan'], ['id' => 'health-records', 'label' => 'Set up a file for vaccination and health records'],
        ]],
        ['title' => 'Training & Behaviour', 'items' => [
            ['id' => 'train-name', 'label' => 'Teach their name using positive reinforcement'], ['id' => 'train-sit', 'label' => 'Start basic commands (sit, stay, come)'], ['id' => 'train-social', 'label' => 'Begin socialisation with people, places, and sounds'], ['id' => 'train-bite', 'label' => 'Address mouthing and biting with redirection'], ['id' => 'train-crate', 'label' => 'Practise crate training if using a crate'], ['id' => 'train-pro', 'label' => 'Consider enrolling in a puppy or obedience class'],
        ]],
        ['title' => 'Supplies & Equipment', 'items' => [
            ['id' => 'sup-collar', 'label' => 'Collar or harness with ID tag'], ['id' => 'sup-leash', 'label' => 'Leash (and a longer one for training)'], ['id' => 'sup-toys', 'label' => 'A few safe toys for chewing and play'], ['id' => 'sup-grooming', 'label' => 'Grooming supplies (brush, nail clippers, shampoo)'], ['id' => 'sup-poop-bags', 'label' => 'Poop bags or a litter tray (for cats)'], ['id' => 'sup-cleaning', 'label' => 'Pet-safe cleaning products for accidents'], ['id' => 'sup-first-aid', 'label' => 'Basic pet first-aid kit'],
        ]],
    ],
    'vaccination' => [
        'dog' => [
            ['age' => 'Puppy initial series', 'vaccines' => [['name' => 'Core combination vaccine', 'type' => 'core', 'description' => 'A veterinarian should select the product and timing for the puppy series based on age, health, product instructions, and local disease risk.'], ['name' => 'Rabies', 'type' => 'risk-based', 'description' => 'Rabies is an important public-health concern. Any legal or travel requirement must be checked against the applicable Nigerian authority and current product rules.']]],
            ['age' => 'Lifestyle and local-risk review', 'vaccines' => [['name' => 'Leptospirosis', 'type' => 'risk-based', 'description' => 'Discuss local exposure, relevant serogroups, and availability of a suitable product with a veterinarian; this guide does not make a universal Nigerian classification.'], ['name' => 'Kennel cough and other vaccines', 'type' => 'risk-based', 'description' => 'Consider only when the pet’s contact, boarding, travel, and product context support it.']]],
            ['age' => 'Adult or unknown history', 'vaccines' => [['name' => 'Catch-up assessment', 'type' => 'clinical review', 'description' => 'Bring any records to a veterinarian. Unknown history should not be treated as proof of protection or solved by a fixed online calendar.']]],
        ],
        'cat' => [
            ['age' => 'Kitten initial series', 'vaccines' => [['name' => 'Core combination vaccine', 'type' => 'core', 'description' => 'A veterinarian should select the product and timing for the kitten series based on age, health, product instructions, and local disease risk.'], ['name' => 'Rabies', 'type' => 'risk-based', 'description' => 'Rabies is an important public-health concern. Any legal or travel requirement must be checked against the applicable Nigerian authority and current product rules.']]],
            ['age' => 'Lifestyle and local-risk review', 'vaccines' => [['name' => 'FeLV and other vaccines', 'type' => 'risk-based', 'description' => 'Discuss outdoor access, household exposure, travel, local risk, and product availability with a veterinarian.']]],
            ['age' => 'Adult or unknown history', 'vaccines' => [['name' => 'Catch-up assessment', 'type' => 'clinical review', 'description' => 'Bring any records to a veterinarian. Unknown history should not be treated as proof of protection or solved by a fixed online calendar.']]],
        ],
    ],
    'emergency' => [
        ['species' => 'both', 'category' => 'poison', 'title' => 'Unsafe foods and suspected poisoning', 'severity' => 'emergency', 'symptoms' => ['Vomiting or diarrhoea', 'Excessive drooling', 'Weakness, tremors, or seizures', 'Difficulty breathing'], 'actions' => ['Call a veterinarian or emergency clinic immediately', 'Tell them what was eaten, how much, and when', 'Keep the packaging or a sample available', "Follow the vet's instructions while travelling"], 'avoid' => ['Do not induce vomiting unless a veterinarian directs you', 'Do not give milk, salt water, or home remedies', 'Do not wait for symptoms to appear']],
        ['species' => 'dog', 'category' => 'emergency', 'title' => 'Heatstroke', 'severity' => 'emergency', 'symptoms' => ['Heavy panting or difficulty breathing', 'Drooling', 'Bright red or pale gums', 'Weakness, staggering, or collapse'], 'actions' => ['Move to shade or a cool area', 'Offer small amounts of cool water if alert', 'Use cool, damp towels while contacting a vet', 'Travel to an emergency clinic immediately'], 'avoid' => ['Do not use ice or very cold water', 'Do not force large amounts of water', 'Do not leave a pet in a hot car']],
        ['species' => 'cat', 'category' => 'emergency', 'title' => 'Choking or breathing trouble', 'severity' => 'emergency', 'symptoms' => ['Open-mouth breathing', 'Blue or pale gums or tongue', 'Pawing at the mouth', 'Collapse or severe distress'], 'actions' => ['Keep the cat as calm and still as possible', 'If an object is clearly visible and safely reachable, remove it without pushing deeper', 'Call a veterinarian while travelling for emergency help', 'Seek immediate veterinary care'], 'avoid' => ['Do not push fingers blindly into the throat', 'Do not give food or water', 'Do not delay if breathing is impaired']],
        ['species' => 'both', 'category' => 'emergency', 'title' => 'Heavy bleeding', 'severity' => 'emergency', 'symptoms' => ['Visible wound with continuous blood flow', 'Pale gums', 'Weakness or collapse', 'Rapid breathing'], 'actions' => ['Apply firm, steady pressure with clean cloth or gauze', 'Keep your pet still and calm', 'Add more cloth if the first layer soaks through', 'Go to a veterinarian urgently'], 'avoid' => ['Do not remove embedded objects', 'Do not use a tourniquet unless directed', 'Do not apply alcohol, peroxide, or human medication']],
    ],
];
