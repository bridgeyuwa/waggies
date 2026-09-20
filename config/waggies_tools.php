<?php

return [
    'catalogue' => [
        ['id' => 'symptom-checker', 'name' => 'Pet Symptom Checker', 'route' => 'tools.symptom-checker', 'icon' => 'medical', 'description' => 'Select symptoms by body area and get general guidance on next steps.', 'longDescription' => "An interactive tool to help you identify potential health concerns by selecting your pet's species, the affected body area, and specific symptoms you notice.", 'category' => 'health'],
        ['id' => 'emergency-guide', 'name' => 'Emergency & Poison Guide', 'route' => 'tools.emergency', 'icon' => 'emergency', 'description' => 'Quick-reference for common pet emergencies, poisons, and what to do.', 'longDescription' => 'A reference guide covering poisonous foods, household dangers, heatstroke, choking, seizures, and bleeding - with immediate action steps and emergency contacts.', 'category' => 'health'],
        ['id' => 'parasite-schedule', 'name' => 'Parasite & Deworming Schedule', 'route' => 'tools.parasite', 'icon' => 'parasite', 'description' => 'Educational timeline for deworming puppies, kittens, and adult pets.', 'longDescription' => 'A general educational timeline showing recommended deworming intervals for puppies, kittens, adult dogs, and adult cats, with notes on travel and veterinary confirmation.', 'category' => 'health'],
        ['id' => 'medication-dosage-guide', 'name' => 'Medication Dosage Guide', 'route' => 'tools.medication', 'icon' => 'medication', 'description' => 'Veterinary-reviewed medication reference information (coming soon).', 'longDescription' => 'A veterinary-reviewed reference for common pet medications, dosage guidelines, and safety notes. Currently being prepared by our veterinary team.', 'category' => 'health'],
        ['id' => 'pet-age-calculator', 'name' => 'Pet Age Calculator', 'route' => 'tools.pet-age', 'icon' => 'pets', 'description' => "Convert your pet's age to approximate human years.", 'longDescription' => "Convert your dog or cat's age into approximate human years using species-specific conversion formulas that account for size and life stage.", 'category' => 'calculator'],
        ['id' => 'cost-calculator', 'name' => 'Cost Calculator', 'route' => 'tools.cost', 'icon' => 'calculator', 'description' => 'Estimate costs for Waggies boarding, grooming, vet, and training services.', 'longDescription' => "Get a quick cost estimate for Waggies services based on your pet's type, size, and the service you need. Covers boarding, grooming, vet care, and training.", 'category' => 'calculator'],
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
        'high_severity_ids' => ['head-pressing', 'laboured-breathing', 'seizures', 'bloated-belly', 'cloudy-eyes', 'bleeding-gums', 'disorientation'],
        'guidance' => [
            'low' => ['title' => 'Monitor and Observe', 'description' => 'The symptom you have selected is common and may resolve on its own. However, if it persists or worsens, we recommend consulting with your veterinarian.', 'recommendations' => ['Keep an eye on your pet over the next few days', 'Note any changes in behaviour, appetite, or energy', 'Check if the symptom appears at certain times or after specific activities', 'Schedule a vet visit if the symptom lasts more than 48 hours', "Maintain your pet's regular routine for feeding and exercise"]],
            'moderate' => ['title' => 'Consider a Vet Visit', 'description' => 'Your pet is showing multiple symptoms that could be related. While not necessarily an emergency, a veterinary check-up would help identify the underlying cause.', 'recommendations' => ['Monitor symptoms over the next 24-48 hours', 'Check if symptoms are improving, staying the same, or worsening', 'Schedule a routine vet visit if symptoms persist', 'Ensure your pet has access to fresh water and food', 'Avoid giving any human medication without vet guidance']],
            'high' => ['title' => 'See a Veterinarian Soon', 'description' => 'The symptoms you have selected may indicate a condition that requires veterinary attention. We strongly recommend scheduling a visit with your vet as soon as possible.', 'recommendations' => ['Schedule a vet appointment within 24 hours', 'Monitor your pet closely for any worsening symptoms', 'Keep your pet comfortable and hydrated', 'Note when symptoms started and any changes you have observed', 'Contact your vet immediately if symptoms worsen rapidly']],
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
            ['age' => '6-8 weeks', 'vaccines' => [['name' => 'DHPP (Distemper, Hepatitis, Parainfluenza, Parvovirus)', 'type' => 'core', 'description' => 'First dose of the core combination vaccine.']]],
            ['age' => '10-12 weeks', 'vaccines' => [['name' => 'DHPP Booster', 'type' => 'core', 'description' => 'Second dose of the core combination vaccine.'], ['name' => 'Leptospirosis', 'type' => 'non-core', 'description' => 'Recommended if your dog is exposed to wildlife or standing water.']]],
            ['age' => '12-16 weeks', 'vaccines' => [['name' => 'Rabies', 'type' => 'core', 'description' => 'Required by law in most areas. First dose.'], ['name' => 'DHPP Booster', 'type' => 'core', 'description' => 'Third and final dose of the puppy series.']]],
            ['age' => '16-20 weeks', 'vaccines' => [['name' => 'Bordetella (Kennel Cough)', 'type' => 'non-core', 'description' => 'Especially important for dogs that are boarded, groomed, or socialise with other dogs.']]],
            ['age' => '12-16 months', 'vaccines' => [['name' => 'DHPP Booster', 'type' => 'core', 'description' => 'First annual booster of the core vaccine.'], ['name' => 'Rabies Booster', 'type' => 'core', 'description' => 'Annual or triennial depending on local regulations.'], ['name' => 'Leptospirosis Booster', 'type' => 'non-core', 'description' => 'Annual booster if the initial series was given.'], ['name' => 'Canine Influenza', 'type' => 'non-core', 'description' => 'Recommended for dogs that frequent dog parks or boarding facilities.']]],
            ['age' => 'Annually (adult)', 'vaccines' => [['name' => 'DHPP Booster', 'type' => 'core', 'description' => 'Annual booster. Some vets may switch to a 3-year schedule after the first few years.'], ['name' => 'Rabies Booster', 'type' => 'core', 'description' => 'Every 1-3 years depending on the vaccine used and local law.'], ['name' => 'Bordetella Booster', 'type' => 'non-core', 'description' => 'Every 6-12 months for dogs in high-contact environments.']]],
        ],
        'cat' => [
            ['age' => '6-8 weeks', 'vaccines' => [['name' => 'FVRCP (Feline Viral Rhinotracheitis, Calicivirus, Panleukopenia)', 'type' => 'core', 'description' => 'First dose of the core combination vaccine.']]],
            ['age' => '10-12 weeks', 'vaccines' => [['name' => 'FVRCP Booster', 'type' => 'core', 'description' => 'Second dose of the core combination vaccine.'], ['name' => 'FeLV (Feline Leukaemia Virus)', 'type' => 'non-core', 'description' => 'Recommended for cats that go outdoors or live with FeLV-positive cats.']]],
            ['age' => '14-16 weeks', 'vaccines' => [['name' => 'FVRCP Booster', 'type' => 'core', 'description' => 'Third and final dose of the kitten series.'], ['name' => 'Rabies', 'type' => 'core', 'description' => 'Required by law in most areas. First dose.'], ['name' => 'FeLV Booster', 'type' => 'non-core', 'description' => 'Second dose if the initial series was given.']]],
            ['age' => '12-16 months', 'vaccines' => [['name' => 'FVRCP Booster', 'type' => 'core', 'description' => 'First annual booster of the core vaccine.'], ['name' => 'Rabies Booster', 'type' => 'core', 'description' => 'Annual or triennial depending on the vaccine used.'], ['name' => 'FeLV Booster', 'type' => 'non-core', 'description' => 'Annual booster if the cat is at risk.']]],
            ['age' => 'Annually (adult)', 'vaccines' => [['name' => 'FVRCP Booster', 'type' => 'core', 'description' => "Every 1-3 years depending on the vaccine and your cat's risk level."], ['name' => 'Rabies Booster', 'type' => 'core', 'description' => 'Every 1-3 years depending on the vaccine used and local law.']]],
        ],
    ],
    'emergency' => [
        ['species' => 'both', 'category' => 'poison', 'title' => 'Unsafe foods and suspected poisoning', 'severity' => 'emergency', 'symptoms' => ['Vomiting or diarrhoea', 'Excessive drooling', 'Weakness, tremors, or seizures', 'Difficulty breathing'], 'actions' => ['Call a veterinarian or emergency clinic immediately', 'Tell them what was eaten, how much, and when', 'Keep the packaging or a sample available', "Follow the vet's instructions while travelling"], 'avoid' => ['Do not induce vomiting unless a veterinarian directs you', 'Do not give milk, salt water, or home remedies', 'Do not wait for symptoms to appear']],
        ['species' => 'dog', 'category' => 'emergency', 'title' => 'Heatstroke', 'severity' => 'emergency', 'symptoms' => ['Heavy panting or difficulty breathing', 'Drooling', 'Bright red or pale gums', 'Weakness, staggering, or collapse'], 'actions' => ['Move to shade or a cool area', 'Offer small amounts of cool water if alert', 'Use cool, damp towels while contacting a vet', 'Travel to an emergency clinic immediately'], 'avoid' => ['Do not use ice or very cold water', 'Do not force large amounts of water', 'Do not leave a pet in a hot car']],
        ['species' => 'cat', 'category' => 'emergency', 'title' => 'Choking or breathing trouble', 'severity' => 'emergency', 'symptoms' => ['Open-mouth breathing', 'Blue or pale gums or tongue', 'Pawing at the mouth', 'Collapse or severe distress'], 'actions' => ['Keep the cat as calm and still as possible', 'If an object is clearly visible and safely reachable, remove it without pushing deeper', 'Call a veterinarian while travelling for emergency help', 'Seek immediate veterinary care'], 'avoid' => ['Do not push fingers blindly into the throat', 'Do not give food or water', 'Do not delay if breathing is impaired']],
        ['species' => 'both', 'category' => 'emergency', 'title' => 'Heavy bleeding', 'severity' => 'emergency', 'symptoms' => ['Visible wound with continuous blood flow', 'Pale gums', 'Weakness or collapse', 'Rapid breathing'], 'actions' => ['Apply firm, steady pressure with clean cloth or gauze', 'Keep your pet still and calm', 'Add more cloth if the first layer soaks through', 'Go to a veterinarian urgently'], 'avoid' => ['Do not remove embedded objects', 'Do not use a tourniquet unless directed', 'Do not apply alcohol, peroxide, or human medication']],
    ],
];
