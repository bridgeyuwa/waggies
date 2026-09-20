<?php

return [
    'reference' => json_decode(<<<'JSON'
{
  "breeds": [
    {
      "id": "affenpinscher",
      "species": "dog",
      "name": "Affenpinscher",
      "size": "small",
      "temperament": [
        "Curious",
        "Confident",
        "Comical",
        "Stubborn"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small terrier-like toy breed nicknamed the \"monkey dog\" for its shaggy, expressive face. Originally bred in Germany to control rodents in stables and kitchens.",
      "generalHealthConsiderations": "Generally hardy; may be prone to patellar luxation and eye conditions. Brachycephalic features can cause breathing sensitivity in heat.",
      "weightRange": "3-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "Germany",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "afghan-hound",
      "species": "dog",
      "name": "Afghan Hound",
      "size": "large",
      "temperament": [
        "Aloof",
        "Independent",
        "Elegant",
        "Athletic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active household"
      ],
      "overview": "A tall sighthound with a long, flowing coat, originally bred in the mountainous regions of Afghanistan to course swift game over rough terrain.",
      "generalHealthConsiderations": "Generally healthy; may be prone to cataracts and hip dysplasia. Coat requires extensive professional grooming.",
      "weightRange": "23-27 kg",
      "lifespan": "12-18 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Afghanistan",
      "coatType": "Long",
      "trainability": "low"
    },
    {
      "id": "airedale-terrier",
      "species": "dog",
      "name": "Airedale Terrier",
      "size": "large",
      "temperament": [
        "Confident",
        "Energetic",
        "Intelligent",
        "Courageous"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "The largest of the terrier breeds, developed in Yorkshire's Aire Valley to hunt rats and retrieve waterfowl. Confident, versatile, and loyal.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, skin allergies, and certain eye conditions; regular grooming and hand-stripping maintain coat texture.",
      "weightRange": "18-29 kg",
      "lifespan": "10-13 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "akita",
      "species": "dog",
      "name": "Akita",
      "size": "large",
      "temperament": [
        "Loyal",
        "Dignified",
        "Courageous",
        "Reserved"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A large Spitz-type breed from northern Japan, originally used to hunt bear and guard property. Deeply loyal to family and reserved with strangers.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, hypothyroidism, and immune-mediated conditions; requires early socialization and consistent handling.",
      "weightRange": "32-45 kg",
      "lifespan": "10-14 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Japan",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "alaskan-malamute",
      "species": "dog",
      "name": "Alaskan Malamute",
      "size": "large",
      "temperament": [
        "Affectionate",
        "Energetic",
        "Loyal",
        "Strong-willed"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Cool climate"
      ],
      "overview": "A powerful Arctic sled dog bred by the Mahlemut people of Alaska for hauling heavy loads over long distances. Friendly, pack-oriented, and enduring.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, cataracts, and heat sensitivity; coat requires regular brushing, especially during seasonal shedding.",
      "weightRange": "34-45 kg",
      "lifespan": "10-14 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "United States",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "american-eskimo-dog",
      "species": "dog",
      "name": "American Eskimo Dog",
      "size": "small",
      "temperament": [
        "Intelligent",
        "Alert",
        "Friendly",
        "Energetic"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A bright, white-coated Spitz-type companion descended from European circus and farm dogs. Alert, trainable, and family-oriented despite its name.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and progressive retinal atrophy; regular dental care is recommended.",
      "weightRange": "2.5-9 kg (Toy to Standard)",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "United States",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "american-foxhound",
      "species": "dog",
      "name": "American Foxhound",
      "size": "large",
      "temperament": [
        "Gentle",
        "Energetic",
        "Independent",
        "Friendly"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A scent hound developed in the United States for hunting foxes in packs. Lean, athletic, and gentle-mannered but with strong scent-tracking instinct.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and ear infections due to pendulous ears.",
      "weightRange": "20-30 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "american-staffordshire-terrier",
      "species": "dog",
      "name": "American Staffordshire Terrier",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Confident",
        "Courageous",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A stocky, muscular companion originally developed in the United States from bulldog-and-terrier crosses. Loyal, people-oriented, and tenacious.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, skin allergies, and heart conditions; benefits from early socialization and consistent training.",
      "weightRange": "23-36 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "anatolian-shepherd",
      "species": "dog",
      "name": "Anatolian Shepherd",
      "size": "large",
      "temperament": [
        "Loyal",
        "Independent",
        "Protective",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large livestock guardian developed in Turkey to protect sheep from predators. Independent, calm, and fiercely protective of its flock.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, and eyelid conditions; requires space and experienced handling.",
      "weightRange": "36-68 kg",
      "lifespan": "11-13 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Turkey",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "australian-cattle-dog",
      "species": "dog",
      "name": "Australian Cattle Dog",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Hardworking"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active household"
      ],
      "overview": "A compact herder developed in Australia to drive cattle over long distances. Tireless, intelligent, and devoted to its work and family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, deafness, and progressive retinal atrophy; needs substantial daily exercise and mental engagement.",
      "weightRange": "14-22 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Herding",
      "origin": "Australia",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "australian-kelpie",
      "species": "dog",
      "name": "Australian Kelpie",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Work-oriented"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A tireless Australian herding dog capable of working sheep and cattle across vast distances. Highly intelligent and intensely driven.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and eye conditions. Best suited to active working or rural homes.",
      "weightRange": "11-20 kg",
      "lifespan": "10-14 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Australia",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "australian-shepherd",
      "species": "dog",
      "name": "Australian Shepherd",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Work-oriented"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural or active household"
      ],
      "overview": "Despite its name, the breed was developed in the western United States as a herding dog. Highly intelligent and thrives on activity and mental engagement.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and the MDR1 gene mutation affecting drug sensitivity.",
      "weightRange": "16-32 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Herding",
      "origin": "United States",
      "coatType": "Double",
      "trainability": "very-high"
    },
    {
      "id": "australian-terrier",
      "species": "dog",
      "name": "Australian Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Courageous",
        "Affectionate",
        "Spirited"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small sturdy terrier developed in Australia from British terrier stock to control rodents and guard homesteads. Spirited and affectionate with family.",
      "generalHealthConsiderations": "May be prone to patellar luxation, diabetes, and skin allergies; regular dental care is recommended.",
      "weightRange": "5-7 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "Australia",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "basenji",
      "species": "dog",
      "name": "Basenji",
      "size": "small",
      "temperament": [
        "Independent",
        "Curious",
        "Alert",
        "Cat-like"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Quiet household"
      ],
      "overview": "A Central African hunting breed known for its unusually low vocalizations and clean, cat-like grooming habits. Independent and curious.",
      "generalHealthConsiderations": "May be prone to progressive retinal atrophy, kidney disease (Fanconi syndrome), and hypothyroidism; routine screening is recommended.",
      "weightRange": "9-11 kg",
      "lifespan": "13-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Central Africa",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "basset-fauve-de-bretagne",
      "species": "dog",
      "name": "Basset Fauve de Bretagne",
      "size": "small",
      "temperament": [
        "Friendly",
        "Curious",
        "Energetic",
        "Outgoing"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A short-legged French scent hound with a wiry fawn coat. Friendly, outgoing, and bred to hunt small game in dense cover.",
      "generalHealthConsiderations": "Generally hardy; may be prone to ear infections and skin sensitivities. Ears require routine cleaning.",
      "weightRange": "15-19 kg",
      "lifespan": "11-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "France",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "basset-hound",
      "species": "dog",
      "name": "Basset Hound",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Patient",
        "Stubborn",
        "Loyal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A short-legged scent hound developed in France and Belgium to trail rabbits and deer. Gentle, patient, and stubbornly focused when on a scent.",
      "generalHealthConsiderations": "May be prone to ear infections, eye conditions, and back problems due to long back; weight management is important.",
      "weightRange": "18-29 kg",
      "lifespan": "10-12 years",
      "popularity": "Common",
      "breedGroup": "Hound",
      "origin": "France",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "beagle",
      "species": "dog",
      "name": "Beagle",
      "size": "small",
      "temperament": [
        "Curious",
        "Merry",
        "Friendly",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "House with yard"
      ],
      "overview": "A small scent hound originally bred for tracking small game. Curious, merry, and friendly with people and other dogs.",
      "generalHealthConsiderations": "May be prone to obesity, ear infections, and hip dysplasia; keep on a lead outdoors due to strong scent-tracking instinct.",
      "weightRange": "9-11 kg",
      "lifespan": "10-15 years",
      "popularity": "Common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "bearded-collie",
      "species": "dog",
      "name": "Bearded Collie",
      "size": "medium",
      "temperament": [
        "Bouncy",
        "Friendly",
        "Intelligent",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A long-coated Scottish herding dog known for its bouncy, cheerful demeanor. Intelligent and energetic, originally bred to drive sheep in rough terrain.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and skin sensitivities; coat requires regular brushing to prevent matting.",
      "weightRange": "18-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "bedlington-terrier",
      "species": "dog",
      "name": "Bedlington Terrier",
      "size": "small",
      "temperament": [
        "Gentle",
        "Spirited",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A terrier with a distinctive lamb-like curly coat, developed in northern England. Gentle at home yet spirited when working.",
      "generalHealthConsiderations": "May be prone to copper toxicosis (liver disease) and eye conditions; routine liver screening is recommended.",
      "weightRange": "7-10 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Curly",
      "trainability": "moderate"
    },
    {
      "id": "belgian-malinois",
      "species": "dog",
      "name": "Belgian Malinois",
      "size": "medium",
      "temperament": [
        "Confident",
        "Intelligent",
        "Energetic",
        "Loyal"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A versatile Belgian herding dog widely used for police, military, and service work. Highly trainable and intensely focused.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, eye conditions, and skin sensitivities; requires extensive physical and mental work.",
      "weightRange": "18-36 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Herding",
      "origin": "Belgium",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "belgian-sheepdog",
      "species": "dog",
      "name": "Belgian Sheepdog",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Loyal",
        "Energetic",
        "Protective"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active household"
      ],
      "overview": "A long-coated Belgian herder also known as the Groenendael. Intelligent and loyal, with strong working drives and a striking black coat.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and hypothyroidism; benefits from routine screening and consistent activity.",
      "weightRange": "18-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Belgium",
      "coatType": "Long",
      "trainability": "very-high"
    },
    {
      "id": "belgian-tervuren",
      "species": "dog",
      "name": "Belgian Tervuren",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Alert",
        "Energetic",
        "Loyal"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A long-coated fawn Belgian herder with a black mask. Intelligent and watchful, excelling in obedience, agility, and protection work.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and hypothyroidism; requires consistent training and exercise.",
      "weightRange": "18-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Belgium",
      "coatType": "Long",
      "trainability": "very-high"
    },
    {
      "id": "bergamasco",
      "species": "dog",
      "name": "Bergamasco",
      "size": "medium",
      "temperament": [
        "Patient",
        "Loyal",
        "Intelligent",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "An Italian herding breed distinguished by its flocked, felted coat. Patient and intelligent, originally bred to tend sheep in the Alps.",
      "generalHealthConsiderations": "Generally hardy; coat forms natural flocks that should not be brushed out once matured.",
      "weightRange": "26-38 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Italy",
      "coatType": "Corded",
      "trainability": "moderate"
    },
    {
      "id": "bernese-mountain-dog",
      "species": "dog",
      "name": "Bernese Mountain Dog",
      "size": "large",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Calm",
        "Loyal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Cool climate"
      ],
      "overview": "A large Swiss working dog with a striking tri-color coat. Calm, affectionate, and historically used as a farm dog.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, certain cancers, and a relatively short lifespan; routine veterinary monitoring is recommended.",
      "weightRange": "32-52 kg",
      "lifespan": "7-10 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Switzerland",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "bichon-frise",
      "species": "dog",
      "name": "Bichon Frise",
      "size": "small",
      "temperament": [
        "Cheerful",
        "Playful",
        "Affectionate",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small, curly-coated companion of Mediterranean origin, historically popular in European courts. Cheerful, affectionate, and low-shedding.",
      "generalHealthConsiderations": "May be prone to allergies, dental disease, and patellar luxation; regular grooming prevents matting.",
      "weightRange": "5-8 kg",
      "lifespan": "14-15 years",
      "popularity": "Common",
      "breedGroup": "Non-Sporting",
      "origin": "France/Spain",
      "coatType": "Curly",
      "trainability": "high"
    },
    {
      "id": "black-russian-terrier",
      "species": "dog",
      "name": "Black Russian Terrier",
      "size": "large",
      "temperament": [
        "Confident",
        "Intelligent",
        "Loyal",
        "Calm"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A large working breed developed in the Soviet Union from multiple guardian and herding lines. Confident, loyal, and protective.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, eye conditions, and heart issues; regular screening is recommended.",
      "weightRange": "36-68 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Russia",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "black-and-tan-coonhound",
      "species": "dog",
      "name": "Black and Tan Coonhound",
      "size": "large",
      "temperament": [
        "Friendly",
        "Independent",
        "Loyal",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large American scent hound developed to trail and tree raccoons. Friendly, even-tempered, and tenacious on a trail.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and ear infections due to pendulous ears.",
      "weightRange": "23-34 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "bloodhound",
      "species": "dog",
      "name": "Bloodhound",
      "size": "large",
      "temperament": [
        "Gentle",
        "Patient",
        "Determined",
        "Friendly"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A large scent hound renowned for tracking ability, historically used in Europe for hunting deer and boar and today in search-and-rescue work.",
      "generalHealthConsiderations": "May be prone to ear infections, eye conditions, and bloat; deep facial folds require routine cleaning.",
      "weightRange": "36-50 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "France/Belgium",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "bluetick-coonhound",
      "species": "dog",
      "name": "Bluetick Coonhound",
      "size": "large",
      "temperament": [
        "Friendly",
        "Energetic",
        "Loyal",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "An American coonhound distinguished by its heavily mottled blue coat. Friendly, determined, and bred to trail and tree game.",
      "generalHealthConsiderations": "Generally hardy; may be prone to ear infections and hip dysplasia.",
      "weightRange": "20-36 kg",
      "lifespan": "11-12 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "border-collie",
      "species": "dog",
      "name": "Border Collie",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Trainable",
        "Driven"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural or active household"
      ],
      "overview": "Widely regarded as the most intelligent dog breed. Originally developed as a herding dog on the Anglo-Scottish border.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, collie eye anomaly (CEA), and epilepsy; needs extensive mental and physical stimulation.",
      "weightRange": "14-22 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Herding",
      "origin": "United Kingdom",
      "coatType": "Double",
      "trainability": "very-high"
    },
    {
      "id": "border-terrier",
      "species": "dog",
      "name": "Border Terrier",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Energetic",
        "Good-natured",
        "Alert"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A small working terrier from the Anglo-Scottish border, bred to run with foxhounds. Good-natured, hardy, and eager to please.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, heart conditions, and eye issues; coat benefits from regular hand-stripping.",
      "weightRange": "5-7 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "borzoi",
      "species": "dog",
      "name": "Borzoi",
      "size": "large",
      "temperament": [
        "Gentle",
        "Independent",
        "Elegant",
        "Quiet"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Quiet household"
      ],
      "overview": "A tall Russian sighthound developed to course wolves and hares. Elegant, quiet, and gentle-mannered at home.",
      "generalHealthConsiderations": "May be prone to bloat, heart conditions, and eye issues; sensitive to anesthesia in some lines.",
      "weightRange": "25-48 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Russia",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "boston-terrier",
      "species": "dog",
      "name": "Boston Terrier",
      "size": "small",
      "temperament": [
        "Friendly",
        "Lively",
        "Intelligent",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A compact American companion breed developed in Boston from bulldog-and-terrier crosses. Friendly, lively, and well-mannered indoors.",
      "generalHealthConsiderations": "Brachycephalic — sensitive to heat and may have breathing difficulty; prominent eyes are prone to irritation and injury.",
      "weightRange": "5-11 kg",
      "lifespan": "11-13 years",
      "popularity": "Common",
      "breedGroup": "Non-Sporting",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "bouvier-des-flandres",
      "species": "dog",
      "name": "Bouvier des Flandres",
      "size": "large",
      "temperament": [
        "Loyal",
        "Intelligent",
        "Energetic",
        "Protective"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A rugged Belgian herding and farm dog with a tousled coat. Loyal, intelligent, and historically used to drive cattle and pull carts.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and bloat; coat requires regular professional grooming.",
      "weightRange": "27-40 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Belgium/France",
      "coatType": "Rough",
      "trainability": "high"
    },
    {
      "id": "boxer",
      "species": "dog",
      "name": "Boxer",
      "size": "large",
      "temperament": [
        "Playful",
        "Energetic",
        "Loyal",
        "Bright"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A muscular, high-energy breed known for its playful, patient nature with families. Originally developed in Germany as a hunting and guard dog.",
      "generalHealthConsiderations": "Brachycephalic — sensitive to heat; may be prone to certain cancers, heart conditions, and hip dysplasia.",
      "weightRange": "25-32 kg",
      "lifespan": "10-12 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "boykin-spaniel",
      "species": "dog",
      "name": "Boykin Spaniel",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Energetic",
        "Eager",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium flushing and retrieving spaniel developed in South Carolina for waterfowl and turkey hunting. Friendly, eager, and versatile.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and exercise-induced collapse; ears require routine cleaning.",
      "weightRange": "11-18 kg",
      "lifespan": "14-16 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United States",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "bracco-italiano",
      "species": "dog",
      "name": "Bracco Italiano",
      "size": "large",
      "temperament": [
        "Affectionate",
        "Energetic",
        "Intelligent",
        "Gentle"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "An ancient Italian pointing breed with a distinctive expressive face. Affectionate at home and tireless in the field.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, eye conditions, and ear infections.",
      "weightRange": "25-40 kg",
      "lifespan": "12-13 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Italy",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "briard",
      "species": "dog",
      "name": "Briard",
      "size": "large",
      "temperament": [
        "Loyal",
        "Intelligent",
        "Protective",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large French herding and guard dog with a long, dry coat. Loyal, intelligent, and historically used to tend flocks and protect property.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and bloat; coat requires regular brushing and grooming.",
      "weightRange": "25-40 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "France",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "brittany",
      "species": "dog",
      "name": "Brittany",
      "size": "medium",
      "temperament": [
        "Energetic",
        "Bright",
        "Eager",
        "Affectionate"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A compact French pointing and retrieving gun dog. Energetic, eager, and quick in the field; affectionate at home.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and epilepsy; needs ample daily exercise.",
      "weightRange": "14-20 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "France",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "brussels-griffon",
      "species": "dog",
      "name": "Brussels Griffon",
      "size": "small",
      "temperament": [
        "Alert",
        "Affectionate",
        "Curious",
        "Sensitive"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small Belgian toy breed with an expressive, almost human-like face. Alert, affectionate, and well-suited to indoor life.",
      "generalHealthConsiderations": "Brachycephalic features may cause breathing sensitivity; may be prone to patellar luxation and eye conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "Belgium",
      "coatType": "Rough",
      "trainability": "moderate"
    },
    {
      "id": "bull-terrier",
      "species": "dog",
      "name": "Bull Terrier",
      "size": "medium",
      "temperament": [
        "Playful",
        "Stubborn",
        "Courageous",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A distinctive British breed with an egg-shaped head and muscular build. Playful, courageous, and devoted to family.",
      "generalHealthConsiderations": "May be prone to kidney disease, heart conditions, deafness (in white individuals), and skin sensitivities.",
      "weightRange": "20-36 kg",
      "lifespan": "12-13 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "bulldog",
      "species": "dog",
      "name": "Bulldog",
      "size": "medium",
      "temperament": [
        "Calm",
        "Courageous",
        "Friendly",
        "Dignified"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A stocky, muscular companion with a distinctive pushed-in face. Calm and courageous, well-suited to indoor living.",
      "generalHealthConsiderations": "Brachycephalic breed — may experience breathing difficulties and heat sensitivity; skin folds require routine cleaning.",
      "weightRange": "18-25 kg",
      "lifespan": "8-10 years",
      "popularity": "Common",
      "breedGroup": "Non-Sporting",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "bullmastiff",
      "species": "dog",
      "name": "Bullmastiff",
      "size": "large",
      "temperament": [
        "Loyal",
        "Protective",
        "Calm",
        "Courageous"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A large British guard dog developed by crossing Bulldogs and Mastiffs to deter poachers. Loyal, calm, and quietly protective.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, bloat, and certain cancers; weight management and portion control are important.",
      "weightRange": "45-59 kg",
      "lifespan": "8-10 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "cairn-terrier",
      "species": "dog",
      "name": "Cairn Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Cheerful",
        "Courageous",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A small Scottish terrier bred to bolt foxes and vermin from cairns (rock piles). Cheerful, hardy, and spirited.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, patellar luxation, and certain eye conditions; coat benefits from regular hand-stripping.",
      "weightRange": "6-7 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "canaan-dog",
      "species": "dog",
      "name": "Canaan Dog",
      "size": "medium",
      "temperament": [
        "Alert",
        "Intelligent",
        "Loyal",
        "Reserved"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Quiet household"
      ],
      "overview": "A natural pariah-type breed from the Middle East, historically used to guard camps and flocks. Alert, intelligent, and independent.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and eye conditions.",
      "weightRange": "16-25 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Israel",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "cane-corso",
      "species": "dog",
      "name": "Cane Corso",
      "size": "large",
      "temperament": [
        "Loyal",
        "Confident",
        "Protective",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A large Italian mastiff breed traditionally used as a guard, hunting, and working dog. Loyal, powerful, and protective.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, bloat, and eyelid abnormalities; requires experienced handling and early socialization.",
      "weightRange": "36-50 kg",
      "lifespan": "9-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Italy",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "cardigan-welsh-corgi",
      "species": "dog",
      "name": "Cardigan Welsh Corgi",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Intelligent",
        "Loyal",
        "Alert"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A long-backed, low-set Welsh herding breed with a long history as a farm dog. Intelligent, loyal, and alert to strangers.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, degenerative myelopathy, and eye conditions; avoid excessive stair use and jumping.",
      "weightRange": "11-17 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "United Kingdom",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "cavalier-king-charles-spaniel",
      "species": "dog",
      "name": "Cavalier King Charles Spaniel",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Graceful",
        "Sociable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A toy spaniel breed with a silky coat and gentle, affectionate disposition. Originally a companion for European nobility.",
      "generalHealthConsiderations": "May be prone to mitral valve disease, syringomyelia, and ear infections; cardiac screening is particularly important.",
      "weightRange": "5-8 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Toy",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "chesapeake-bay-retriever",
      "species": "dog",
      "name": "Chesapeake Bay Retriever",
      "size": "large",
      "temperament": [
        "Loyal",
        "Energetic",
        "Intelligent",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large American retriever developed in Maryland for cold-water duck hunting. Powerful, determined, and devoted to its work.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and skin sensitivities; coat is naturally oily and should not be over-bathed.",
      "weightRange": "25-36 kg",
      "lifespan": "10-13 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United States",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "chihuahua",
      "species": "dog",
      "name": "Chihuahua",
      "size": "small",
      "temperament": [
        "Alert",
        "Devoted",
        "Bold",
        "Lively"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "The smallest recognized dog breed, originating from Mexico. Alert, devoted, and well-suited to apartment life.",
      "generalHealthConsiderations": "May be prone to dental disease, patellar luxation, and heart conditions; sensitive to cold due to small size.",
      "weightRange": "1.5-3 kg",
      "lifespan": "14-16 years",
      "popularity": "Popular",
      "breedGroup": "Toy",
      "origin": "Mexico",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "chinese-crested",
      "species": "dog",
      "name": "Chinese Crested",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Playful",
        "Alert",
        "Lively"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small companion breed that comes in hairless and powderpuff (fully coated) varieties. Affectionate, lively, and people-oriented.",
      "generalHealthConsiderations": "Hairless variety is prone to sunburn, skin irritation, and dental issues; skin requires routine cleansing and protection.",
      "weightRange": "2-5 kg",
      "lifespan": "13-18 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "China",
      "coatType": "Hairless",
      "trainability": "moderate"
    },
    {
      "id": "chinese-shar-pei",
      "species": "dog",
      "name": "Chinese Shar-Pei",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Independent",
        "Calm",
        "Reserved"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Quiet household"
      ],
      "overview": "A medium Chinese breed known for its deep skin folds and blue-black tongue. Loyal, calm, and reserved with strangers.",
      "generalHealthConsiderations": "Skin folds require routine cleaning to prevent infection; may be prone to eye conditions (entropion) and hip dysplasia.",
      "weightRange": "18-25 kg",
      "lifespan": "8-12 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "China",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "chinook",
      "species": "dog",
      "name": "Chinook",
      "size": "large",
      "temperament": [
        "Friendly",
        "Intelligent",
        "Calm",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A rare American sled dog developed in New Hampshire. Friendly, calm, and tireless in harness.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and skin sensitivities; generally healthy with routine care.",
      "weightRange": "25-40 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "United States",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "chow-chow",
      "species": "dog",
      "name": "Chow Chow",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Independent",
        "Aloof",
        "Dignified"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Quiet household"
      ],
      "overview": "An ancient Chinese spitz-type breed with a lion-like ruff and blue-black tongue. Loyal to family, aloof with strangers.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, eye conditions (entropion), and thyroid issues; sensitive to heat.",
      "weightRange": "20-32 kg",
      "lifespan": "8-12 years",
      "popularity": "Common",
      "breedGroup": "Non-Sporting",
      "origin": "China",
      "coatType": "Double",
      "trainability": "low"
    },
    {
      "id": "coton-de-tulear",
      "species": "dog",
      "name": "Coton de Tulear",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Cheerful",
        "Gentle",
        "Playful"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small companion breed from Madagascar named for its soft, cotton-like coat. Cheerful, affectionate, and well-suited to family life.",
      "generalHealthConsiderations": "Generally healthy; may be prone to patellar luxation, eye conditions, and dental issues.",
      "weightRange": "4-6 kg",
      "lifespan": "14-16 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Madagascar",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "curly-coated-retriever",
      "species": "dog",
      "name": "Curly-Coated Retriever",
      "size": "large",
      "temperament": [
        "Loyal",
        "Intelligent",
        "Independent",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "One of the oldest retriever breeds, distinguished by a tight curly coat. Loyal, intelligent, and tireless in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain skin issues; coat is low-maintenance but sheds seasonally.",
      "weightRange": "23-36 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Curly",
      "trainability": "high"
    },
    {
      "id": "dachshund",
      "species": "dog",
      "name": "Dachshund",
      "size": "small",
      "temperament": [
        "Curious",
        "Bold",
        "Lively",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "Originally bred to hunt badgers, the Dachshund has a distinctive long-backed body and comes in three coat varieties and two sizes.",
      "generalHealthConsiderations": "Long-backed structure makes them susceptible to spinal injuries, particularly intervertebral disc disease; avoid jumping and excessive stair use.",
      "weightRange": "7-14 kg (standard); under 5 kg (miniature)",
      "lifespan": "12-16 years",
      "popularity": "Common",
      "breedGroup": "Hound",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "dalmatian",
      "species": "dog",
      "name": "Dalmatian",
      "size": "large",
      "temperament": [
        "Energetic",
        "Outgoing",
        "Playful",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "An instantly recognizable breed with a spotted coat. Energetic and outgoing, originally bred as a coaching and fire-house dog.",
      "generalHealthConsiderations": "May be prone to deafness and urinary stones; requires consistent exercise and a controlled diet.",
      "weightRange": "20-32 kg",
      "lifespan": "11-13 years",
      "popularity": "Common",
      "breedGroup": "Non-Sporting",
      "origin": "Croatia (historical)",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "dandie-dinmont-terrier",
      "species": "dog",
      "name": "Dandie Dinmont Terrier",
      "size": "small",
      "temperament": [
        "Independent",
        "Affectionate",
        "Determined",
        "Intelligent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A distinctive Scottish terrier with a topknot of silky hair and a long, low body. Affectionate, determined, and quietly dignified.",
      "generalHealthConsiderations": "May be prone to intervertebral disc disease, eye conditions, and hypothyroidism; weight management is important.",
      "weightRange": "8-11 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "doberman-pinscher",
      "species": "dog",
      "name": "Doberman Pinscher",
      "size": "large",
      "temperament": [
        "Loyal",
        "Alert",
        "Energetic",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active household"
      ],
      "overview": "A sleek, muscular working dog developed in Germany. Known for loyalty, intelligence, and protective instincts.",
      "generalHealthConsiderations": "May be prone to dilated cardiomyopathy, von Willebrand's disease, and hip dysplasia; regular cardiac screening is recommended.",
      "weightRange": "32-45 kg",
      "lifespan": "10-12 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "dogo-argentino",
      "species": "dog",
      "name": "Dogo Argentino",
      "size": "large",
      "temperament": [
        "Loyal",
        "Courageous",
        "Energetic",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A large Argentine hunting and guardian breed developed to pursue big game. Loyal, athletic, and powerful.",
      "generalHealthConsiderations": "May be prone to deafness and hip dysplasia; requires experienced handling and early socialization.",
      "weightRange": "36-45 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Argentina",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "english-cocker-spaniel",
      "species": "dog",
      "name": "English Cocker Spaniel",
      "size": "medium",
      "temperament": [
        "Merry",
        "Affectionate",
        "Gentle",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A medium flushing spaniel developed in England for woodcock hunting. Merry, affectionate, and eager in the field.",
      "generalHealthConsiderations": "May be prone to ear infections, eye conditions, and hip dysplasia; ears require routine cleaning.",
      "weightRange": "12-16 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "english-foxhound",
      "species": "dog",
      "name": "English Foxhound",
      "size": "large",
      "temperament": [
        "Gentle",
        "Energetic",
        "Sociable",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A traditional English pack hound bred to trail foxes over long distances. Gentle, sociable, and tireless.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and ear infections.",
      "weightRange": "25-34 kg",
      "lifespan": "10-13 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "english-setter",
      "species": "dog",
      "name": "English Setter",
      "size": "large",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Energetic",
        "Friendly"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A long-coated pointing breed developed in England for upland bird hunting. Gentle, friendly, and elegant in motion.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, elbow dysplasia, and deafness; coat requires regular brushing.",
      "weightRange": "20-36 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "english-springer-spaniel",
      "species": "dog",
      "name": "English Springer Spaniel",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Energetic",
        "Obedient",
        "Eager"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium flushing spaniel developed to spring game for the hunter's net or gun. Friendly, eager, and tireless in the field.",
      "generalHealthConsiderations": "May be prone to ear infections, hip dysplasia, and certain skin conditions; ears require routine care.",
      "weightRange": "18-23 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "english-toy-spaniel",
      "species": "dog",
      "name": "English Toy Spaniel",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Quiet",
        "Reserved"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small toy breed with a domed head and silky coat, historically favored by British royalty. Quiet, gentle, and affectionate.",
      "generalHealthConsiderations": "Brachycephalic — may have breathing sensitivity; may be prone to heart conditions and eye issues.",
      "weightRange": "3-6 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "estrela-mountain-dog",
      "species": "dog",
      "name": "Estrela Mountain Dog",
      "size": "large",
      "temperament": [
        "Loyal",
        "Protective",
        "Independent",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large livestock guardian breed from the Estrela Mountains of Portugal. Loyal, calm, and protective of its flock and family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and bloat; requires space and consistent socialization.",
      "weightRange": "30-50 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Portugal",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "field-spaniel",
      "species": "dog",
      "name": "Field Spaniel",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Energetic",
        "Sensitive"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A medium flushing spaniel developed in England for game hunting. Affectionate, sensitive, and steady in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, ear infections, and eye conditions; ears require routine cleaning.",
      "weightRange": "16-23 kg",
      "lifespan": "12-13 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "finnish-lapphund",
      "species": "dog",
      "name": "Finnish Lapphund",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Intelligent",
        "Energetic",
        "Friendly"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Cool climate"
      ],
      "overview": "A medium Spitz-type herder developed by the Sami people of northern Scandinavia to tend reindeer. Gentle, intelligent, and hardy.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and eye conditions; coat requires regular brushing, especially during shedding seasons.",
      "weightRange": "15-24 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Finland",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "finnish-spitz",
      "species": "dog",
      "name": "Finnish Spitz",
      "size": "small",
      "temperament": [
        "Alert",
        "Energetic",
        "Loyal",
        "Vocal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A small Nordic spitz-type hunting dog that barks to point game in trees. Alert, energetic, and distinctive in voice.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and eye conditions; generally hardy with routine care.",
      "weightRange": "9-13 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Finland",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "flat-coated-retriever",
      "species": "dog",
      "name": "Flat-Coated Retriever",
      "size": "large",
      "temperament": [
        "Cheerful",
        "Energetic",
        "Friendly",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large retriever developed in England with a flat, lustrous coat. Cheerful, energetic, and slow to mature.",
      "generalHealthConsiderations": "May be prone to certain cancers and hip dysplasia; regular veterinary monitoring is recommended.",
      "weightRange": "25-36 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "french-bulldog",
      "species": "dog",
      "name": "French Bulldog",
      "size": "small",
      "temperament": [
        "Playful",
        "Adaptable",
        "Affectionate",
        "Sociable"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A compact companion breed with distinctive bat ears. Playful, adaptable, and well-suited to city living.",
      "generalHealthConsiderations": "Brachycephalic — sensitive to heat and may experience breathing difficulties; skin folds need routine cleaning.",
      "weightRange": "8-14 kg",
      "lifespan": "10-12 years",
      "popularity": "Popular",
      "breedGroup": "Non-Sporting",
      "origin": "France",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "german-pinscher",
      "species": "dog",
      "name": "German Pinscher",
      "size": "medium",
      "temperament": [
        "Alert",
        "Energetic",
        "Intelligent",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium working terrier-type breed from Germany, ancestor of the Doberman and Miniature Pinscher. Alert, intelligent, and spirited.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; generally healthy with routine care.",
      "weightRange": "11-16 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "german-shepherd",
      "species": "dog",
      "name": "German Shepherd",
      "size": "large",
      "temperament": [
        "Loyal",
        "Confident",
        "Intelligent",
        "Courageous"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active household"
      ],
      "overview": "A versatile working dog used worldwide for police, service, and herding roles. Highly trainable and deeply loyal to family.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, degenerative myelopathy, and digestive sensitivities.",
      "weightRange": "22-40 kg",
      "lifespan": "9-13 years",
      "popularity": "Popular",
      "breedGroup": "Herding",
      "origin": "Germany",
      "coatType": "Double",
      "trainability": "very-high"
    },
    {
      "id": "german-shorthaired-pointer",
      "species": "dog",
      "name": "German Shorthaired Pointer",
      "size": "large",
      "temperament": [
        "Energetic",
        "Intelligent",
        "Friendly",
        "Cooperative"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A versatile German gun dog bred to point, retrieve, and trail on land and water. Energetic, intelligent, and eager to work.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain cancers; requires substantial daily exercise.",
      "weightRange": "20-32 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "german-wirehaired-pointer",
      "species": "dog",
      "name": "German Wirehaired Pointer",
      "size": "large",
      "temperament": [
        "Energetic",
        "Intelligent",
        "Loyal",
        "Determined"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A versatile German gun dog with a wiry, weather-resistant coat. Energetic, determined, and capable across varied terrain.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and ear infections; coat requires periodic hand-stripping.",
      "weightRange": "25-32 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Germany",
      "coatType": "Wiry",
      "trainability": "very-high"
    },
    {
      "id": "giant-schnauzer",
      "species": "dog",
      "name": "Giant Schnauzer",
      "size": "large",
      "temperament": [
        "Loyal",
        "Intelligent",
        "Energetic",
        "Alert"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A large German working breed originally used to drive cattle and guard property. Loyal, alert, and highly trainable.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and bloat; coat requires regular professional grooming.",
      "weightRange": "25-36 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "glen-of-imaal-terrier",
      "species": "dog",
      "name": "Glen of Imaal Terrier",
      "size": "small",
      "temperament": [
        "Gentle",
        "Courageous",
        "Loyal",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A sturdy Irish terrier from the Glen of Imaal, bred to hunt badgers and foxes. Gentle with family yet courageous in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and eye conditions; coat benefits from regular hand-stripping.",
      "weightRange": "15-16 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "Ireland",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "golden-retriever",
      "species": "dog",
      "name": "Golden Retriever",
      "size": "large",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Intelligent",
        "Devoted"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A friendly, tolerant breed renowned as a family companion and working dog. Originally bred in Scotland to retrieve shot waterfowl.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain cancers, and heart conditions; routine veterinary screening is recommended.",
      "weightRange": "25-34 kg",
      "lifespan": "10-12 years",
      "popularity": "Popular",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "very-high"
    },
    {
      "id": "gordon-setter",
      "species": "dog",
      "name": "Gordon Setter",
      "size": "large",
      "temperament": [
        "Loyal",
        "Energetic",
        "Intelligent",
        "Confident"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A black-and-tan pointing setter developed in Scotland. Loyal, intelligent, and steady in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and bloat; coat requires regular brushing.",
      "weightRange": "20-36 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "great-dane",
      "species": "dog",
      "name": "Great Dane",
      "size": "giant",
      "temperament": [
        "Gentle",
        "Friendly",
        "Patient",
        "Dependable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Spacious home"
      ],
      "overview": "One of the tallest breeds, the Great Dane combines imposing size with a gentle, affectionate nature. Often called the \"Apollo of Dogs\".",
      "generalHealthConsiderations": "May be prone to gastric dilatation-volvulus (bloat), heart conditions, and hip dysplasia; smaller, more frequent meals can help manage bloat risk.",
      "weightRange": "45-79 kg",
      "lifespan": "7-10 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "great-pyrenees",
      "species": "dog",
      "name": "Great Pyrenees",
      "size": "large",
      "temperament": [
        "Patient",
        "Calm",
        "Loyal",
        "Protective"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large French livestock guardian with a thick white coat. Patient, calm, and devoted to protecting its flock and family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, and heart conditions; coat requires regular brushing.",
      "weightRange": "36-54 kg",
      "lifespan": "10-12 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "France",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "greater-swiss-mountain-dog",
      "species": "dog",
      "name": "Greater Swiss Mountain Dog",
      "size": "large",
      "temperament": [
        "Loyal",
        "Calm",
        "Energetic",
        "Friendly"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A large Swiss draft and farm dog, the largest of the Swiss mountain breeds. Loyal, calm, and powerful.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, bloat, and certain eye conditions; weight management is important.",
      "weightRange": "36-50 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Switzerland",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "greyhound",
      "species": "dog",
      "name": "Greyhound",
      "size": "large",
      "temperament": [
        "Gentle",
        "Quiet",
        "Athletic",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Quiet household"
      ],
      "overview": "An ancient sighthound bred to course game by speed. Despite its racing reputation, the Greyhound is quiet and gentle indoors.",
      "generalHealthConsiderations": "Generally healthy; sensitive to certain anesthetics due to low body fat. May be prone to dental disease.",
      "weightRange": "25-40 kg",
      "lifespan": "10-13 years",
      "popularity": "Common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "harrier",
      "species": "dog",
      "name": "Harrier",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Energetic",
        "Outgoing",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A medium English scent hound bred to hunt hares in packs. Friendly, outgoing, and tireless on a trail.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and ear infections.",
      "weightRange": "18-27 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "havanese",
      "species": "dog",
      "name": "Havanese",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Cheerful",
        "Intelligent",
        "Sociable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small companion breed developed in Cuba from Bichon-type ancestors. Cheerful, sociable, and devoted to its people.",
      "generalHealthConsiderations": "May be prone to patellar luxation, eye conditions, and dental disease; coat requires regular grooming.",
      "weightRange": "3-6 kg",
      "lifespan": "14-16 years",
      "popularity": "Common",
      "breedGroup": "Toy",
      "origin": "Cuba",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "ibizan-hound",
      "species": "dog",
      "name": "Ibizan Hound",
      "size": "large",
      "temperament": [
        "Gentle",
        "Independent",
        "Athletic",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A tall sighthound from the Balearic Islands, traditionally used to hunt rabbits. Gentle, athletic, and quietly loyal.",
      "generalHealthConsiderations": "Generally hardy; sensitive to certain anesthetics. May be prone to ear infections and dental issues.",
      "weightRange": "19-25 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Spain",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "icelandic-sheepdog",
      "species": "dog",
      "name": "Icelandic Sheepdog",
      "size": "small",
      "temperament": [
        "Friendly",
        "Energetic",
        "Intelligent",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "Iceland's only native dog breed, a small Spitz-type herder. Friendly, energetic, and historically kept to tend sheep.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and eye conditions; coat requires regular brushing.",
      "weightRange": "9-14 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Iceland",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "irish-red-and-white-setter",
      "species": "dog",
      "name": "Irish Red and White Setter",
      "size": "large",
      "temperament": [
        "Energetic",
        "Gentle",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A pointing setter from Ireland with a distinctive red-and-white coat. Energetic, gentle, and steady in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and immune-mediated conditions; routine screening is recommended.",
      "weightRange": "23-32 kg",
      "lifespan": "11-15 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Ireland",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "irish-setter",
      "species": "dog",
      "name": "Irish Setter",
      "size": "large",
      "temperament": [
        "Energetic",
        "Friendly",
        "Gentle",
        "Outgoing"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A long-coated pointing breed with a striking mahogany coat. Energetic, outgoing, and friendly with family and strangers alike.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, eye conditions, and certain skin issues; regular veterinary monitoring is recommended.",
      "weightRange": "25-34 kg",
      "lifespan": "11-15 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "Ireland",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "irish-terrier",
      "species": "dog",
      "name": "Irish Terrier",
      "size": "medium",
      "temperament": [
        "Bold",
        "Loyal",
        "Energetic",
        "Courageous"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium Irish terrier with a wiry red coat. Bold, loyal, and courageous, traditionally used for vermin and guarding.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; coat benefits from regular hand-stripping.",
      "weightRange": "11-12 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "Ireland",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "irish-water-spaniel",
      "species": "dog",
      "name": "Irish Water Spaniel",
      "size": "large",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Playful"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A tall spaniel with a distinctive curly liver coat and rat-like tail. Intelligent, playful, and bred for water retrieving.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and ear infections; coat requires regular grooming.",
      "weightRange": "20-30 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Ireland",
      "coatType": "Curly",
      "trainability": "high"
    },
    {
      "id": "irish-wolfhound",
      "species": "dog",
      "name": "Irish Wolfhound",
      "size": "giant",
      "temperament": [
        "Gentle",
        "Loyal",
        "Patient",
        "Dignified"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Spacious home"
      ],
      "overview": "One of the tallest dog breeds, historically used in Ireland to course wolves and elk. Gentle, dignified, and quiet indoors.",
      "generalHealthConsiderations": "May be prone to bloat, heart conditions, hip dysplasia, and a relatively short lifespan; routine veterinary monitoring is important.",
      "weightRange": "47-80 kg",
      "lifespan": "6-10 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Ireland",
      "coatType": "Rough",
      "trainability": "moderate"
    },
    {
      "id": "italian-greyhound",
      "species": "dog",
      "name": "Italian Greyhound",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Athletic",
        "Sensitive"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small toy sighthound popular in Renaissance European courts. Affectionate, gentle, and surprisingly fast outdoors.",
      "generalHealthConsiderations": "May be prone to dental disease, patellar luxation, and leg fractures; sensitive to cold due to slender build.",
      "weightRange": "3-6 kg",
      "lifespan": "14-15 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "Italy",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "japanese-chin",
      "species": "dog",
      "name": "Japanese Chin",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Playful",
        "Quiet"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small toy breed with a distinctive flat face and silky coat. Quiet, gentle, and cat-like in its movements.",
      "generalHealthConsiderations": "Brachycephalic — may have breathing sensitivity; may be prone to eye conditions and dental issues.",
      "weightRange": "2-5 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "Japan",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "japanese-spitz",
      "species": "dog",
      "name": "Japanese Spitz",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Lively",
        "Alert",
        "Intelligent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small white Spitz-type companion developed in Japan. Lively, intelligent, and devoted to family.",
      "generalHealthConsiderations": "Generally healthy; may be prone to patellar luxation and eye conditions.",
      "weightRange": "5-10 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Japan",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "keeshond",
      "species": "dog",
      "name": "Keeshond",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Outgoing",
        "Alert",
        "Loyal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium Dutch Spitz-type companion with a thick silver-grey coat. Friendly, alert, and historically kept as a barge watchdog.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and skin sensitivities; coat requires regular brushing.",
      "weightRange": "14-18 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Netherlands",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "kerry-blue-terrier",
      "species": "dog",
      "name": "Kerry Blue Terrier",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Intelligent",
        "Energetic",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium Irish terrier with a soft, blue-grey curly coat. Loyal, intelligent, and historically used for vermin and small game.",
      "generalHealthConsiderations": "May be prone to skin growths, eye conditions, and hip dysplasia; coat requires regular professional grooming.",
      "weightRange": "14-18 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "Ireland",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "komondor",
      "species": "dog",
      "name": "Komondor",
      "size": "large",
      "temperament": [
        "Loyal",
        "Independent",
        "Protective",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large Hungarian livestock guardian with a distinctive corded coat. Loyal, independent, and protective of its flock.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, and eye conditions; coat requires careful maintenance once corded.",
      "weightRange": "36-50 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Hungary",
      "coatType": "Corded",
      "trainability": "moderate"
    },
    {
      "id": "kuvasz",
      "species": "dog",
      "name": "Kuvasz",
      "size": "large",
      "temperament": [
        "Loyal",
        "Independent",
        "Protective",
        "Energetic"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large white Hungarian livestock guardian. Loyal, independent, and devoted to its family and flock.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; requires space and consistent handling.",
      "weightRange": "32-52 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Hungary",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "labrador-retriever",
      "species": "dog",
      "name": "Labrador Retriever",
      "size": "large",
      "temperament": [
        "Friendly",
        "Outgoing",
        "Active",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "One of the most popular family dogs worldwide, prized for its friendly, even temperament and willingness to please. Originally bred as a retrieving gun dog.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, ear infections, and weight gain; regular exercise and portion control are important.",
      "weightRange": "25-36 kg",
      "lifespan": "10-12 years",
      "popularity": "Popular",
      "breedGroup": "Sporting",
      "origin": "Canada/United Kingdom",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "lakeland-terrier",
      "species": "dog",
      "name": "Lakeland Terrier",
      "size": "small",
      "temperament": [
        "Bold",
        "Friendly",
        "Energetic",
        "Independent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A small terrier from England's Lake District, bred to bolt foxes. Bold, friendly, and lively.",
      "generalHealthConsiderations": "May be prone to eye conditions and patellar luxation; coat benefits from regular hand-stripping.",
      "weightRange": "7-8 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "leonberger",
      "species": "dog",
      "name": "Leonberger",
      "size": "large",
      "temperament": [
        "Gentle",
        "Loyal",
        "Calm",
        "Playful"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A large German breed with a thick golden coat and black mask. Gentle, calm, and historically kept as a multi-purpose farm dog.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, heart conditions, and certain eye issues; routine screening is recommended.",
      "weightRange": "36-68 kg",
      "lifespan": "9-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "lhasa-apso",
      "species": "dog",
      "name": "Lhasa Apso",
      "size": "small",
      "temperament": [
        "Alert",
        "Loyal",
        "Independent",
        "Reserved"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small Tibetan breed with a long flowing coat, historically kept as a sentinel in monasteries. Alert, independent, and loyal to family.",
      "generalHealthConsiderations": "May be prone to eye conditions, hip dysplasia, and dental disease; coat requires regular grooming.",
      "weightRange": "5-7 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Non-Sporting",
      "origin": "Tibet",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "lowchen",
      "species": "dog",
      "name": "Löwchen",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Lively",
        "Outgoing",
        "Playful"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small companion breed with a traditional lion-trim coat. Affectionate, lively, and historically popular among European nobility.",
      "generalHealthConsiderations": "Generally healthy; may be prone to patellar luxation and eye conditions.",
      "weightRange": "4-8 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "France/Germany",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "maltese",
      "species": "dog",
      "name": "Maltese",
      "size": "small",
      "temperament": [
        "Gentle",
        "Playful",
        "Affectionate",
        "Alert"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "An ancient toy breed from the Mediterranean with a long, white silky coat. Gentle, playful, and devoted to its people.",
      "generalHealthConsiderations": "May be prone to dental disease, patellar luxation, and eye irritation; coat requires daily grooming.",
      "weightRange": "1.5-3 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Toy",
      "origin": "Mediterranean",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "manchester-terrier",
      "species": "dog",
      "name": "Manchester Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Intelligent",
        "Loyal",
        "Spirited"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A sleek English terrier developed for ratting. Alert, intelligent, and lively in two size varieties.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye and heart conditions.",
      "weightRange": "2.5-10 kg (Toy to Standard)",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "mastiff",
      "species": "dog",
      "name": "Mastiff",
      "size": "giant",
      "temperament": [
        "Loyal",
        "Calm",
        "Dignified",
        "Protective"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Spacious home"
      ],
      "overview": "An ancient British breed of massive proportions. Loyal, calm, and dignified, devoted to its family.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, bloat, and heart conditions; weight management is important.",
      "weightRange": "54-100 kg",
      "lifespan": "8-10 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "miniature-american-shepherd",
      "species": "dog",
      "name": "Miniature American Shepherd",
      "size": "small",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Devoted"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Active family"
      ],
      "overview": "A small herding breed developed in the United States as a compact version of the Australian Shepherd. Intelligent, energetic, and trainable.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and the MDR1 gene mutation affecting drug sensitivity.",
      "weightRange": "7-18 kg",
      "lifespan": "12-13 years",
      "popularity": "Common",
      "breedGroup": "Herding",
      "origin": "United States",
      "coatType": "Double",
      "trainability": "very-high"
    },
    {
      "id": "miniature-bull-terrier",
      "species": "dog",
      "name": "Miniature Bull Terrier",
      "size": "small",
      "temperament": [
        "Playful",
        "Stubborn",
        "Courageous",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A compact version of the Bull Terrier with the same egg-shaped head and playful temperament.",
      "generalHealthConsiderations": "May be prone to kidney disease, heart conditions, deafness (in white individuals), and patellar luxation.",
      "weightRange": "9-15 kg",
      "lifespan": "11-14 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "miniature-pinscher",
      "species": "dog",
      "name": "Miniature Pinscher",
      "size": "small",
      "temperament": [
        "Alert",
        "Energetic",
        "Bold",
        "Lively"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small German toy breed with a high-stepping gait, despite its name not a scaled-down Doberman. Alert, bold, and lively.",
      "generalHealthConsiderations": "May be prone to patellar luxation, heart conditions, and certain eye issues.",
      "weightRange": "3-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Common",
      "breedGroup": "Toy",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "miniature-schnauzer",
      "species": "dog",
      "name": "Miniature Schnauzer",
      "size": "small",
      "temperament": [
        "Friendly",
        "Intelligent",
        "Alert",
        "Obedient"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small German terrier with a distinctive wiry coat and beard. Friendly, alert, and intelligent.",
      "generalHealthConsiderations": "May be prone to pancreatitis, bladder stones, and certain eye conditions; coat requires regular hand-stripping or clipping.",
      "weightRange": "5-8 kg",
      "lifespan": "12-15 years",
      "popularity": "Popular",
      "breedGroup": "Terrier",
      "origin": "Germany",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "neapolitan-mastiff",
      "species": "dog",
      "name": "Neapolitan Mastiff",
      "size": "giant",
      "temperament": [
        "Loyal",
        "Protective",
        "Calm",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A massive Italian guardian breed with loose, deeply wrinkled skin. Loyal, calm, and quietly protective.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, bloat, heart conditions, and skin-fold infections; routine cleaning of wrinkles is important.",
      "weightRange": "50-70 kg",
      "lifespan": "8-10 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Italy",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "newfoundland",
      "species": "dog",
      "name": "Newfoundland",
      "size": "giant",
      "temperament": [
        "Gentle",
        "Loyal",
        "Patient",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Cool climate"
      ],
      "overview": "A massive Canadian working dog bred for water rescue and draft work. Gentle, patient, and renowned for its swimming strength.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, heart conditions, and bloat; coat requires regular brushing.",
      "weightRange": "45-68 kg",
      "lifespan": "9-10 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Canada",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "norfolk-terrier",
      "species": "dog",
      "name": "Norfolk Terrier",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Alert",
        "Energetic",
        "Fearless"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small British terrier with dropped ears, bred to bolt vermin. Affectionate, alert, and fearless.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; coat benefits from regular hand-stripping.",
      "weightRange": "5-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "norwich-terrier",
      "species": "dog",
      "name": "Norwich Terrier",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Alert",
        "Energetic",
        "Fearless"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small British terrier with prick ears, closely related to the Norfolk. Affectionate, alert, and spirited.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain respiratory issues; coat benefits from regular hand-stripping.",
      "weightRange": "5-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "nova-scotia-duck-tolling-retriever",
      "species": "dog",
      "name": "Nova Scotia Duck Tolling Retriever",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Outgoing",
        "Eager"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium Canadian retriever developed to lure and retrieve waterfowl. Intelligent, energetic, and distinctive in its red coat.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain autoimmune conditions; routine screening is recommended.",
      "weightRange": "16-23 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Canada",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "old-english-sheepdog",
      "species": "dog",
      "name": "Old English Sheepdog",
      "size": "large",
      "temperament": [
        "Gentle",
        "Intelligent",
        "Adaptable",
        "Sociable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A large shaggy-coated British herding breed. Gentle, adaptable, and historically used to drive cattle and sheep.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and deafness; coat requires extensive regular grooming.",
      "weightRange": "25-45 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "otterhound",
      "species": "dog",
      "name": "Otterhound",
      "size": "large",
      "temperament": [
        "Friendly",
        "Energetic",
        "Independent",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large British scent hound historically bred to hunt otters. Friendly, energetic, and distinctive for its rough, water-resistant coat.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, and certain eye conditions; coat requires regular brushing.",
      "weightRange": "29-54 kg",
      "lifespan": "10-13 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Rough",
      "trainability": "moderate"
    },
    {
      "id": "papillon",
      "species": "dog",
      "name": "Papillon",
      "size": "small",
      "temperament": [
        "Alert",
        "Intelligent",
        "Friendly",
        "Energetic"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small toy spaniel with distinctive fringed, butterfly-like ears. Alert, intelligent, and surprisingly athletic for its size.",
      "generalHealthConsiderations": "May be prone to dental disease, patellar luxation, and certain eye conditions; regular dental care is important.",
      "weightRange": "2-5 kg",
      "lifespan": "14-16 years",
      "popularity": "Common",
      "breedGroup": "Toy",
      "origin": "France/Belgium",
      "coatType": "Long",
      "trainability": "very-high"
    },
    {
      "id": "parson-russell-terrier",
      "species": "dog",
      "name": "Parson Russell Terrier",
      "size": "small",
      "temperament": [
        "Bold",
        "Energetic",
        "Intelligent",
        "Friendly"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A small British terrier developed by Reverend John Russell for fox hunting. Bold, energetic, and built for ground work.",
      "generalHealthConsiderations": "May be prone to patellar luxation, eye conditions, and deafness; requires ample exercise and stimulation.",
      "weightRange": "6-8 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "pekingese",
      "species": "dog",
      "name": "Pekingese",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Loyal",
        "Independent",
        "Regal"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small toy breed with a heavy coat and rolling gait, historically favored by Chinese emperors. Regal, loyal, and independent.",
      "generalHealthConsiderations": "Brachycephalic — sensitive to heat and prone to breathing difficulty; prominent eyes are susceptible to injury and irritation.",
      "weightRange": "3-6 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "China",
      "coatType": "Long",
      "trainability": "low"
    },
    {
      "id": "pembroke-welsh-corgi",
      "species": "dog",
      "name": "Pembroke Welsh Corgi",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Intelligent",
        "Alert",
        "Energetic"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A low-set Welsh herding breed popularized by the British royal family. Intelligent, alert, and eager to participate in family activities.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, degenerative myelopathy, and eye conditions; weight management is important.",
      "weightRange": "10-14 kg",
      "lifespan": "12-14 years",
      "popularity": "Popular",
      "breedGroup": "Herding",
      "origin": "United Kingdom",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "petit-basset-griffon-vendéen",
      "species": "dog",
      "name": "Petit Basset Griffon Vendéen",
      "size": "small",
      "temperament": [
        "Friendly",
        "Energetic",
        "Outgoing",
        "Independent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A small rough-coated French scent hound bred to hunt rabbits in packs. Friendly, outgoing, and tireless in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, ear infections, and certain eye conditions; ears require routine cleaning.",
      "weightRange": "14-18 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "France",
      "coatType": "Rough",
      "trainability": "moderate"
    },
    {
      "id": "pharaoh-hound",
      "species": "dog",
      "name": "Pharaoh Hound",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Independent",
        "Athletic",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium sighthound from Malta with a sleek tan coat and large ears. Gentle, athletic, and traditionally used to hunt rabbits.",
      "generalHealthConsiderations": "Generally healthy; sensitive to certain medications and stress. May be prone to ear and dental issues.",
      "weightRange": "18-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Malta",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "plott",
      "species": "dog",
      "name": "Plott",
      "size": "large",
      "temperament": [
        "Loyal",
        "Energetic",
        "Courageous",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large American coonhound descended from German boar-hunting stock. Loyal, courageous, and tenacious on a trail.",
      "generalHealthConsiderations": "Generally hardy; may be prone to ear infections and hip dysplasia.",
      "weightRange": "18-25 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "pointer",
      "species": "dog",
      "name": "Pointer",
      "size": "large",
      "temperament": [
        "Energetic",
        "Friendly",
        "Intelligent",
        "Athletic"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large pointing gun dog developed in England for upland bird hunting. Energetic, athletic, and tireless in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain skin issues; requires substantial daily exercise.",
      "weightRange": "20-34 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "polish-lowland-sheepdog",
      "species": "dog",
      "name": "Polish Lowland Sheepdog",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Loyal",
        "Energetic",
        "Alert"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium shaggy-coated Polish herding breed. Intelligent, loyal, and historically used to tend sheep and cattle.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; coat requires regular brushing to prevent matting.",
      "weightRange": "14-19 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Poland",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "pomeranian",
      "species": "dog",
      "name": "Pomeranian",
      "size": "small",
      "temperament": [
        "Lively",
        "Bold",
        "Inquisitive",
        "Friendly"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small Spitz-type breed with a thick double coat and lively, extroverted personality. Named for the Pomerania region of Central Europe.",
      "generalHealthConsiderations": "May be prone to tracheal collapse, dental disease, and patellar luxation; coat requires regular brushing.",
      "weightRange": "1.5-3 kg",
      "lifespan": "12-16 years",
      "popularity": "Common",
      "breedGroup": "Toy",
      "origin": "Germany/Poland",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "poodle-miniature",
      "species": "dog",
      "name": "Poodle (Miniature)",
      "size": "small",
      "temperament": [
        "Intelligent",
        "Active",
        "Elegant",
        "Trainable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "The miniature variety of the Poodle — a highly intelligent, low-shedding companion renowned for trainability.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and skin sensitivities; regular professional grooming is essential.",
      "weightRange": "4-7 kg",
      "lifespan": "12-15 years",
      "popularity": "Popular",
      "breedGroup": "Toy",
      "origin": "Germany/France",
      "coatType": "Curly",
      "trainability": "very-high"
    },
    {
      "id": "poodle-standard",
      "species": "dog",
      "name": "Poodle (Standard)",
      "size": "large",
      "temperament": [
        "Intelligent",
        "Active",
        "Elegant",
        "Trainable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "The largest variety of the Poodle — originally a German water retriever, now prized as a highly intelligent, low-shedding companion.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain cancers; regular professional grooming is essential.",
      "weightRange": "20-32 kg",
      "lifespan": "12-15 years",
      "popularity": "Popular",
      "breedGroup": "Non-Sporting",
      "origin": "Germany/France",
      "coatType": "Curly",
      "trainability": "very-high"
    },
    {
      "id": "poodle-toy",
      "species": "dog",
      "name": "Poodle (Toy)",
      "size": "small",
      "temperament": [
        "Intelligent",
        "Active",
        "Elegant",
        "Trainable"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "The smallest variety of the Poodle — a highly intelligent, low-shedding companion well-suited to indoor life.",
      "generalHealthConsiderations": "May be prone to dental disease, patellar luxation, and eye conditions; regular professional grooming is essential.",
      "weightRange": "2-3 kg",
      "lifespan": "12-15 years",
      "popularity": "Popular",
      "breedGroup": "Toy",
      "origin": "Germany/France",
      "coatType": "Curly",
      "trainability": "very-high"
    },
    {
      "id": "portuguese-podengo",
      "species": "dog",
      "name": "Portuguese Podengo",
      "size": "small",
      "temperament": [
        "Alert",
        "Energetic",
        "Intelligent",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A primitive sighthound-type breed from Portugal, traditionally used to hunt rabbits. Alert, energetic, and intelligent.",
      "generalHealthConsiderations": "Generally hardy; may be prone to dental and ear issues. Coat is low-maintenance.",
      "weightRange": "4-25 kg (three size varieties)",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Portugal",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "portuguese-water-dog",
      "species": "dog",
      "name": "Portuguese Water Dog",
      "size": "large",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium-large water dog from Portugal, historically used to herd fish into nets and retrieve lost tackle. Energetic, intelligent, and low-shedding.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain storage-disease disorders; coat requires regular grooming.",
      "weightRange": "16-27 kg",
      "lifespan": "11-13 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Portugal",
      "coatType": "Curly",
      "trainability": "very-high"
    },
    {
      "id": "pug",
      "species": "dog",
      "name": "Pug",
      "size": "small",
      "temperament": [
        "Charming",
        "Mischievous",
        "Loving",
        "Quiet"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "An ancient Chinese toy breed with a wrinkled face and curled tail. Charming, loving, and well-suited to indoor life.",
      "generalHealthConsiderations": "Brachycephalic — sensitive to heat and prone to breathing difficulty; prominent eyes are susceptible to injury and skin folds need routine cleaning.",
      "weightRange": "6-9 kg",
      "lifespan": "12-15 years",
      "popularity": "Popular",
      "breedGroup": "Toy",
      "origin": "China",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "puli",
      "species": "dog",
      "name": "Puli",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Loyal",
        "Energetic",
        "Alert"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium Hungarian herding breed distinguished by its corded coat. Intelligent, agile, and historically used to tend sheep.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and eye conditions; corded coat requires careful maintenance.",
      "weightRange": "10-13 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Hungary",
      "coatType": "Corded",
      "trainability": "high"
    },
    {
      "id": "pyrenean-shepherd",
      "species": "dog",
      "name": "Pyrenean Shepherd",
      "size": "small",
      "temperament": [
        "Energetic",
        "Intelligent",
        "Loyal",
        "Alert"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A small French herding breed traditionally worked alongside the Great Pyrenees. Energetic, intelligent, and intensely devoted to its work.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and eye conditions; coat requires regular care.",
      "weightRange": "7-15 kg",
      "lifespan": "12-17 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "France",
      "coatType": "Rough",
      "trainability": "high"
    },
    {
      "id": "rat-terrier",
      "species": "dog",
      "name": "Rat Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Intelligent",
        "Lively",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A small American farm terrier developed for vermin control. Alert, lively, and affectionate with family.",
      "generalHealthConsiderations": "Generally healthy; may be prone to patellar luxation, certain eye conditions, and dental issues.",
      "weightRange": "3-12 kg (two size varieties)",
      "lifespan": "12-18 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "redbone-coonhound",
      "species": "dog",
      "name": "Redbone Coonhound",
      "size": "large",
      "temperament": [
        "Friendly",
        "Energetic",
        "Loyal",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large American coonhound with a striking red coat. Friendly, energetic, and tenacious on a trail.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and ear infections.",
      "weightRange": "20-32 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "rhodesian-ridgeback",
      "species": "dog",
      "name": "Rhodesian Ridgeback",
      "size": "large",
      "temperament": [
        "Loyal",
        "Independent",
        "Dignified",
        "Athletic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large African hound developed to track and bay lions. Loyal, athletic, and distinguished by the ridge of hair along its back.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, dermoid sinus, and certain eye conditions; routine screening is recommended.",
      "weightRange": "32-39 kg",
      "lifespan": "10-12 years",
      "popularity": "Common",
      "breedGroup": "Hound",
      "origin": "Southern Africa",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "rottweiler",
      "species": "dog",
      "name": "Rottweiler",
      "size": "large",
      "temperament": [
        "Loyal",
        "Confident",
        "Protective",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A robust working dog descended from Roman mastiffs, the Rottweiler is loyal, confident, and protective of family.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, heart conditions, and certain cancers; early socialization and consistent training are important.",
      "weightRange": "36-60 kg",
      "lifespan": "9-10 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "russell-terrier",
      "species": "dog",
      "name": "Russell Terrier",
      "size": "small",
      "temperament": [
        "Bold",
        "Energetic",
        "Intelligent",
        "Stubborn"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A small British terrier developed from the same John Russell stock as the Parson Russell. Bold, energetic, and longer-bodied.",
      "generalHealthConsiderations": "May be prone to patellar luxation, eye conditions, and deafness; requires ample exercise and stimulation.",
      "weightRange": "4-7 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "saint-bernard",
      "species": "dog",
      "name": "Saint Bernard",
      "size": "giant",
      "temperament": [
        "Gentle",
        "Loyal",
        "Calm",
        "Watchful"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Spacious home"
      ],
      "overview": "A massive Swiss breed historically used for alpine rescue at the Great St Bernard Pass. Gentle, calm, and loyal to family.",
      "generalHealthConsiderations": "May be prone to hip and elbow dysplasia, heart conditions, bloat, and eye issues; weight management is important.",
      "weightRange": "54-82 kg",
      "lifespan": "8-10 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Switzerland",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "saluki",
      "species": "dog",
      "name": "Saluki",
      "size": "large",
      "temperament": [
        "Gentle",
        "Independent",
        "Athletic",
        "Quiet"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Quiet household"
      ],
      "overview": "An ancient sighthound from the Middle East, traditionally used to course gazelles. Gentle, quiet, and surprisingly fast.",
      "generalHealthConsiderations": "Generally healthy; sensitive to certain anesthetics. May be prone to heart and eye conditions.",
      "weightRange": "16-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Middle East",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "samoyed",
      "species": "dog",
      "name": "Samoyed",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Gentle",
        "Energetic",
        "Sociable"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with yard",
        "Cool climate"
      ],
      "overview": "A medium white-coated Spitz-type breed from Siberia, historically used for herding, sled work, and companionship. Friendly, gentle, and known for its \"Sammy smile\".",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and certain heart issues; coat requires regular brushing.",
      "weightRange": "16-23 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Russia",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "schipperke",
      "species": "dog",
      "name": "Schipperke",
      "size": "small",
      "temperament": [
        "Alert",
        "Intelligent",
        "Energetic",
        "Curious"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small black Belgian breed historically used as a barge watchdog. Alert, curious, and surprisingly energetic.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and a specific neurological condition (Schipperke myelopathy); routine screening is recommended.",
      "weightRange": "3-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Belgium",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "scottish-deerhound",
      "species": "dog",
      "name": "Scottish Deerhound",
      "size": "large",
      "temperament": [
        "Gentle",
        "Dignified",
        "Loyal",
        "Athletic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large rough-coated Scottish sighthound historically bred to course deer. Gentle, dignified, and quietly athletic.",
      "generalHealthConsiderations": "May be prone to bloat, heart conditions, and certain bone issues; sensitive to anesthesia in some lines.",
      "weightRange": "34-50 kg",
      "lifespan": "8-11 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Rough",
      "trainability": "moderate"
    },
    {
      "id": "scottish-terrier",
      "species": "dog",
      "name": "Scottish Terrier",
      "size": "small",
      "temperament": [
        "Independent",
        "Loyal",
        "Dignified",
        "Alert"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A short-legged terrier from Scotland with a wiry coat and distinctive beard. Independent, dignified, and loyal to family.",
      "generalHealthConsiderations": "May be prone to certain cancers (lymphoma, hemangiosarcoma), hip dysplasia, and eye conditions; routine screening is recommended.",
      "weightRange": "8-10 kg",
      "lifespan": "11-13 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "sealyham-terrier",
      "species": "dog",
      "name": "Sealyham Terrier",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Alert",
        "Independent",
        "Courageous"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A small Welsh terrier developed for vermin and badger work. Affectionate, alert, and courageous.",
      "generalHealthConsiderations": "May be prone to certain eye conditions and deafness; coat benefits from regular hand-stripping.",
      "weightRange": "8-9 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "shetland-sheepdog",
      "species": "dog",
      "name": "Shetland Sheepdog",
      "size": "small",
      "temperament": [
        "Intelligent",
        "Loyal",
        "Affectionate",
        "Alert"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small herding breed from the Shetland Islands, resembling a miniature Rough Collie. Intelligent, loyal, and devoted to family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions (CEA), and the MDR1 gene mutation affecting drug sensitivity.",
      "weightRange": "5-11 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Herding",
      "origin": "United Kingdom",
      "coatType": "Double",
      "trainability": "very-high"
    },
    {
      "id": "shiba-inu",
      "species": "dog",
      "name": "Shiba Inu",
      "size": "small",
      "temperament": [
        "Alert",
        "Independent",
        "Loyal",
        "Curious"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "The smallest native Japanese spitz-type breed, originally used for small game hunting. Alert, independent, and cat-like in temperament.",
      "generalHealthConsiderations": "May be prone to allergies, hip dysplasia, patellar luxation, and certain eye conditions; routine screening is recommended.",
      "weightRange": "8-11 kg",
      "lifespan": "13-16 years",
      "popularity": "Popular",
      "breedGroup": "Non-Sporting",
      "origin": "Japan",
      "coatType": "Double",
      "trainability": "low"
    },
    {
      "id": "shih-tzu",
      "species": "dog",
      "name": "Shih Tzu",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Outgoing",
        "Playful",
        "Friendly"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A toy breed developed as a companion for Chinese royalty. Friendly, outgoing, and well-suited to indoor living.",
      "generalHealthConsiderations": "Brachycephalic — may experience breathing difficulties and heat sensitivity; eyes are prominent and prone to irritation.",
      "weightRange": "4-7 kg",
      "lifespan": "10-18 years",
      "popularity": "Popular",
      "breedGroup": "Toy",
      "origin": "China",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "siberian-husky",
      "species": "dog",
      "name": "Siberian Husky",
      "size": "medium",
      "temperament": [
        "Energetic",
        "Mischievous",
        "Friendly",
        "Outgoing"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Cool climate"
      ],
      "overview": "A medium-sized working sled dog with a thick double coat and striking appearance. Friendly, energetic, and pack-oriented.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and eye conditions such as cataracts. Requires significant daily exercise.",
      "weightRange": "16-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Working",
      "origin": "Russia",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "silky-terrier",
      "species": "dog",
      "name": "Silky Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Affectionate",
        "Energetic",
        "Inquisitive"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small Australian toy breed with a long, silky blue-and-tan coat. Alert, inquisitive, and lively.",
      "generalHealthConsiderations": "May be prone to patellar luxation, certain eye conditions, and dental disease; coat requires regular grooming.",
      "weightRange": "3.5-4.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "Australia",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "skye-terrier",
      "species": "dog",
      "name": "Skye Terrier",
      "size": "small",
      "temperament": [
        "Loyal",
        "Independent",
        "Courageous",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A long-backed, low-set terrier from the Isle of Skye with a profuse coat covering its face. Loyal, independent, and courageous.",
      "generalHealthConsiderations": "May be prone to back issues, hip dysplasia, and certain eye conditions; weight management is important.",
      "weightRange": "14-18 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "sloughi",
      "species": "dog",
      "name": "Sloughi",
      "size": "large",
      "temperament": [
        "Gentle",
        "Independent",
        "Loyal",
        "Athletic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Quiet household"
      ],
      "overview": "A North African sighthound traditionally used to course game across desert terrain. Gentle, athletic, and quietly loyal.",
      "generalHealthConsiderations": "Generally healthy; sensitive to certain anesthetics. May be prone to progressive retinal atrophy.",
      "weightRange": "18-29 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "North Africa",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "smooth-fox-terrier",
      "species": "dog",
      "name": "Smooth Fox Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Energetic",
        "Friendly",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A smooth-coated British terrier historically bred to bolt foxes during hunts. Alert, energetic, and quick in motion.",
      "generalHealthConsiderations": "May be prone to certain eye and heart conditions; generally healthy with routine care.",
      "weightRange": "6-8 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "soft-coated-wheaten-terrier",
      "species": "dog",
      "name": "Soft Coated Wheaten Terrier",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Energetic",
        "Loyal",
        "Gentle"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium Irish terrier with a soft, wheaten-colored coat. Friendly, energetic, and historically used as an all-purpose farm dog.",
      "generalHealthConsiderations": "May be prone to protein-losing kidney disease, protein-losing enteropathy, and certain eye conditions; routine screening is recommended.",
      "weightRange": "14-20 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "Ireland",
      "coatType": "Wavy",
      "trainability": "high"
    },
    {
      "id": "spanish-mastiff",
      "species": "dog",
      "name": "Spanish Mastiff",
      "size": "giant",
      "temperament": [
        "Loyal",
        "Calm",
        "Protective",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A massive Spanish livestock guardian with a thick coat. Loyal, calm, and protective of its flock and family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, and certain heart conditions; weight management is important.",
      "weightRange": "40-70 kg",
      "lifespan": "10-12 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Spain",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "spinone-italiano",
      "species": "dog",
      "name": "Spinone Italiano",
      "size": "large",
      "temperament": [
        "Gentle",
        "Patient",
        "Loyal",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large Italian pointing breed with a wiry coat and distinctive whiskered face. Gentle, patient, and tireless in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain eye conditions, and ear infections; coat requires periodic hand-stripping.",
      "weightRange": "29-39 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Italy",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "staffordshire-bull-terrier",
      "species": "dog",
      "name": "Staffordshire Bull Terrier",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Courageous",
        "Affectionate",
        "Energetic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A muscular British terrier developed from bulldog-and-terrier crosses. Loyal, courageous, and affectionate, particularly with family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain eye conditions, and skin sensitivities; routine screening is recommended.",
      "weightRange": "11-17 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "standard-schnauzer",
      "species": "dog",
      "name": "Standard Schnauzer",
      "size": "medium",
      "temperament": [
        "Intelligent",
        "Loyal",
        "Energetic",
        "Alert"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "The medium-sized original Schnauzer from Germany, historically used for ratting and guarding. Intelligent, alert, and versatile.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; coat requires regular hand-stripping or clipping.",
      "weightRange": "14-20 kg",
      "lifespan": "13-16 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Germany",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "sussex-spaniel",
      "species": "dog",
      "name": "Sussex Spaniel",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Calm",
        "Loyal",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Family home"
      ],
      "overview": "A long, low-bodied spaniel from Sussex, England, bred to flush game in dense cover. Gentle, calm, and distinctive in voice.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, ear infections, and certain heart conditions; ears require routine cleaning.",
      "weightRange": "16-20 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Wavy",
      "trainability": "moderate"
    },
    {
      "id": "swedish-vallhund",
      "species": "dog",
      "name": "Swedish Vallhund",
      "size": "small",
      "temperament": [
        "Intelligent",
        "Energetic",
        "Loyal",
        "Alert"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A small Swedish herding breed with a long back and short legs. Intelligent, energetic, and historically used to drive cattle.",
      "generalHealthConsiderations": "May be prone to hip dysplasia and certain eye conditions; routine screening is recommended.",
      "weightRange": "9-14 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Herding",
      "origin": "Sweden",
      "coatType": "Double",
      "trainability": "high"
    },
    {
      "id": "thai-ridgeback",
      "species": "dog",
      "name": "Thai Ridgeback",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Independent",
        "Alert",
        "Athletic"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A medium Thai breed distinguished by the ridge of hair along its back. Loyal, independent, and traditionally used as a hunting and guard dog.",
      "generalHealthConsiderations": "May be prone to dermoid sinus and certain eye conditions; generally hardy with routine care.",
      "weightRange": "16-25 kg",
      "lifespan": "12-13 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "Thailand",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "tibetan-mastiff",
      "species": "dog",
      "name": "Tibetan Mastiff",
      "size": "large",
      "temperament": [
        "Loyal",
        "Independent",
        "Protective",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Experienced owner"
      ],
      "overview": "A large Tibetan livestock guardian with a thick double coat. Loyal, independent, and protective of its family and flock.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain thyroid conditions, and eye issues; coat requires regular brushing.",
      "weightRange": "34-72 kg",
      "lifespan": "10-14 years",
      "popularity": "Less common",
      "breedGroup": "Working",
      "origin": "Tibet",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "tibetan-spaniel",
      "species": "dog",
      "name": "Tibetan Spaniel",
      "size": "small",
      "temperament": [
        "Alert",
        "Intelligent",
        "Independent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small companion breed from Tibetan monasteries with a lion-like mane. Alert, intelligent, and cat-like in temperament.",
      "generalHealthConsiderations": "May be prone to certain eye conditions and patellar luxation; generally healthy with routine care.",
      "weightRange": "4-7 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Tibet",
      "coatType": "Double",
      "trainability": "moderate"
    },
    {
      "id": "tibetan-terrier",
      "species": "dog",
      "name": "Tibetan Terrier",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Sensitive",
        "Loyal",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium Tibetan companion breed with a long, flowing coat. Affectionate, sensitive, and historically kept as a lucky charm in monasteries.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain eye conditions (lens luxation), and patellar luxation; routine screening is recommended.",
      "weightRange": "8-14 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common",
      "breedGroup": "Non-Sporting",
      "origin": "Tibet",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "toy-fox-terrier",
      "species": "dog",
      "name": "Toy Fox Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Intelligent",
        "Loyal",
        "Energetic"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small American toy terrier with a sleek coat and lively demeanor. Alert, intelligent, and affectionate with family.",
      "generalHealthConsiderations": "May be prone to patellar luxation, certain eye conditions, and dental disease; generally healthy with routine care.",
      "weightRange": "1.5-3 kg",
      "lifespan": "13-14 years",
      "popularity": "Less common",
      "breedGroup": "Toy",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "high"
    },
    {
      "id": "treeing-walker-coonhound",
      "species": "dog",
      "name": "Treeing Walker Coonhound",
      "size": "large",
      "temperament": [
        "Friendly",
        "Energetic",
        "Loyal",
        "Determined"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Rural household"
      ],
      "overview": "A large American coonhound descended from English Foxhounds. Friendly, energetic, and tenacious when trailing game.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hip dysplasia and ear infections.",
      "weightRange": "20-32 kg",
      "lifespan": "12-13 years",
      "popularity": "Less common",
      "breedGroup": "Hound",
      "origin": "United States",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "vizsla",
      "species": "dog",
      "name": "Vizsla",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Energetic",
        "Loyal",
        "Gentle"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium Hungarian pointing breed with a distinctive rusty-gold coat. Energetic, loyal, and renowned for its close bond with family.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain eye conditions, and epilepsy; requires substantial daily exercise.",
      "weightRange": "18-29 kg",
      "lifespan": "12-14 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "Hungary",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "weimaraner",
      "species": "dog",
      "name": "Weimaraner",
      "size": "large",
      "temperament": [
        "Energetic",
        "Loyal",
        "Intelligent",
        "Athletic"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A large German gun dog with a striking silver-grey coat. Energetic, intelligent, and historically bred for large-game hunting.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, bloat, certain eye conditions, and spinal issues; routine screening is recommended.",
      "weightRange": "25-36 kg",
      "lifespan": "11-13 years",
      "popularity": "Common",
      "breedGroup": "Sporting",
      "origin": "Germany",
      "coatType": "Smooth",
      "trainability": "very-high"
    },
    {
      "id": "welsh-springer-spaniel",
      "species": "dog",
      "name": "Welsh Springer Spaniel",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Energetic",
        "Loyal",
        "Gentle"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium flushing spaniel from Wales with a distinctive red-and-white coat. Affectionate, loyal, and steady in the field.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, eye conditions, and ear infections; ears require routine cleaning.",
      "weightRange": "16-20 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "high"
    },
    {
      "id": "welsh-terrier",
      "species": "dog",
      "name": "Welsh Terrier",
      "size": "small",
      "temperament": [
        "Friendly",
        "Energetic",
        "Alert",
        "Independent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A wiry-coated British terrier from Wales, closely resembling a small Airedale. Friendly, alert, and spirited.",
      "generalHealthConsiderations": "May be prone to certain eye conditions and skin sensitivities; coat benefits from regular hand-stripping.",
      "weightRange": "9-10 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "west-highland-white-terrier",
      "species": "dog",
      "name": "West Highland White Terrier",
      "size": "small",
      "temperament": [
        "Friendly",
        "Energetic",
        "Alert",
        "Courageous"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A small white-coated Scottish terrier bred for vermin control. Friendly, alert, and courageous.",
      "generalHealthConsiderations": "May be prone to skin allergies, certain eye conditions, and patellar luxation; routine dental care is recommended.",
      "weightRange": "6-8 kg",
      "lifespan": "13-15 years",
      "popularity": "Common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "moderate"
    },
    {
      "id": "whippet",
      "species": "dog",
      "name": "Whippet",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Athletic",
        "Quiet"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium sighthound developed in England for racing and rabbit coursing. Quiet indoors and capable of bursts of speed outdoors.",
      "generalHealthConsiderations": "Generally healthy; sensitive to certain anesthetics. May be prone to eye conditions and skin sensitivity.",
      "weightRange": "9-19 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "breedGroup": "Hound",
      "origin": "United Kingdom",
      "coatType": "Smooth",
      "trainability": "moderate"
    },
    {
      "id": "wire-fox-terrier",
      "species": "dog",
      "name": "Wire Fox Terrier",
      "size": "small",
      "temperament": [
        "Alert",
        "Energetic",
        "Friendly",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A wiry-coated British terrier historically bred to bolt foxes during hunts. Alert, energetic, and quick in motion.",
      "generalHealthConsiderations": "May be prone to certain eye and heart conditions; coat benefits from regular hand-stripping.",
      "weightRange": "6-8 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common",
      "breedGroup": "Terrier",
      "origin": "United Kingdom",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "wirehaired-pointing-griffon",
      "species": "dog",
      "name": "Wirehaired Pointing Griffon",
      "size": "medium",
      "temperament": [
        "Energetic",
        "Intelligent",
        "Loyal",
        "Gentle"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A medium versatile gun dog with a wiry, weather-resistant coat. Energetic, intelligent, and capable across land and water.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain eye conditions, and ear infections; coat requires periodic hand-stripping.",
      "weightRange": "20-27 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "France/Netherlands",
      "coatType": "Wiry",
      "trainability": "high"
    },
    {
      "id": "wirehaired-vizsla",
      "species": "dog",
      "name": "Wirehaired Vizsla",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Energetic",
        "Loyal",
        "Intelligent"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with yard",
        "Active family"
      ],
      "overview": "A Hungarian pointing breed with a dense, wiry coat. Energetic, loyal, and closely related to the smooth-coated Vizsla.",
      "generalHealthConsiderations": "May be prone to hip dysplasia, certain eye conditions, and ear infections; routine screening is recommended.",
      "weightRange": "20-30 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common",
      "breedGroup": "Sporting",
      "origin": "Hungary",
      "coatType": "Wiry",
      "trainability": "very-high"
    },
    {
      "id": "yorkshire-terrier",
      "species": "dog",
      "name": "Yorkshire Terrier",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Bold",
        "Confident",
        "Feisty"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A toy breed with a long, silky coat and a feisty terrier temperament. Well-suited to apartment living.",
      "generalHealthConsiderations": "May be prone to dental disease, patellar luxation, and tracheal collapse; dental care is especially important.",
      "weightRange": "2-3 kg",
      "lifespan": "11-15 years",
      "popularity": "Popular",
      "breedGroup": "Toy",
      "origin": "United Kingdom",
      "coatType": "Long",
      "trainability": "moderate"
    },
    {
      "id": "abyssinian",
      "species": "cat",
      "name": "Abyssinian",
      "size": "medium",
      "temperament": [
        "Active",
        "Intelligent",
        "Curious",
        "Playful"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Active household"
      ],
      "overview": "One of the oldest known cat breeds, distinguished by its ticked tabby coat. Active, intelligent, and curious.",
      "generalHealthConsiderations": "Generally healthy; may be prone to gingivitis and pyruvate kinase deficiency. Needs plenty of mental and physical stimulation.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Common",
      "origin": "Ethiopia/Egypt (historic)"
    },
    {
      "id": "american-bobtail",
      "species": "cat",
      "name": "American Bobtail",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Intelligent",
        "Affectionate",
        "Playful"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium cat with a naturally short bobbed tail, developed in the United States. Friendly, intelligent, and dog-like in devotion.",
      "generalHealthConsiderations": "Generally healthy; some lines may carry hip dysplasia. Coat requires regular brushing.",
      "weightRange": "3-7 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common"
    },
    {
      "id": "american-curl",
      "species": "cat",
      "name": "American Curl",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Curious",
        "Gentle",
        "Playful"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "An American breed distinguished by its distinctive curled-back ears. Affectionate, gentle, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; ears require gentle cleaning and care to avoid damage.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "american-shorthair",
      "species": "cat",
      "name": "American Shorthair",
      "size": "medium",
      "temperament": [
        "Easygoing",
        "Affectionate",
        "Active",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A natural North American breed descended from working cats. Easygoing, hardy, and well-suited to families.",
      "generalHealthConsiderations": "Generally healthy; may be prone to hypertrophic cardiomyopathy and weight gain; portion control is important.",
      "weightRange": "3-6 kg (female); 5-7 kg (male)",
      "lifespan": "15-20 years",
      "popularity": "Common"
    },
    {
      "id": "american-wirehair",
      "species": "cat",
      "name": "American Wirehair",
      "size": "medium",
      "temperament": [
        "Easygoing",
        "Affectionate",
        "Playful",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "An American breed with a distinctive wiry, springy coat. Easygoing, affectionate, and similar in temperament to the American Shorthair.",
      "generalHealthConsiderations": "Generally healthy; coat is delicate and should not be over-groomed.",
      "weightRange": "3.5-5.5 kg",
      "lifespan": "14-18 years",
      "popularity": "Less common"
    },
    {
      "id": "asian",
      "species": "cat",
      "name": "Asian",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Intelligent",
        "Curious",
        "Vocal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A medium shorthair breed group developed in Britain from Burmilla ancestry. Affectionate, intelligent, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to dental issues and certain eye conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "balinese",
      "species": "cat",
      "name": "Balinese",
      "size": "medium",
      "temperament": [
        "Vocal",
        "Social",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A longhaired version of the Siamese, developed naturally from Siamese lines. Vocal, social, and people-oriented.",
      "generalHealthConsiderations": "May be prone to dental issues, respiratory infections, and certain eye conditions.",
      "weightRange": "2.5-5 kg",
      "lifespan": "15-20 years",
      "popularity": "Less common"
    },
    {
      "id": "bengal",
      "species": "cat",
      "name": "Bengal",
      "size": "medium",
      "temperament": [
        "Active",
        "Athletic",
        "Curious",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Active household"
      ],
      "overview": "A distinctive breed developed by crossing the Asian Leopard Cat with domestic cats, known for its spotted or marbled coat. Highly energetic and intelligent.",
      "generalHealthConsiderations": "Generally healthy; may be prone to progressive retinal atrophy and hypertrophic cardiomyopathy in some lines. Requires significant stimulation.",
      "weightRange": "4-7 kg",
      "lifespan": "12-16 years",
      "popularity": "Common"
    },
    {
      "id": "birman",
      "species": "cat",
      "name": "Birman",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Quiet",
        "Sociable"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium-longhaired color-point breed from Burma, traditionally associated with temple cats. Gentle, affectionate, and quiet.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye conditions and kidney disease in some lines.",
      "weightRange": "3-5 kg",
      "lifespan": "13-15 years",
      "popularity": "Common"
    },
    {
      "id": "bombay",
      "species": "cat",
      "name": "Bombay",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Outgoing",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium shorthair breed developed to resemble a miniature black panther. Affectionate, outgoing, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain craniofacial defects (in Burmese ancestry) and hypertrophic cardiomyopathy.",
      "weightRange": "3-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "british-longhair",
      "species": "cat",
      "name": "British Longhair",
      "size": "medium",
      "temperament": [
        "Calm",
        "Affectionate",
        "Independent",
        "Easygoing"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A longhaired variant of the British Shorthair with a plush, dense coat. Calm, easygoing, and quiet.",
      "generalHealthConsiderations": "Generally healthy; may be prone to hypertrophic cardiomyopathy and polycystic kidney disease in some lines.",
      "weightRange": "4-7 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "british-shorthair",
      "species": "cat",
      "name": "British Shorthair",
      "size": "medium",
      "temperament": [
        "Easygoing",
        "Calm",
        "Independent",
        "Loyal"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A sturdy breed with a dense, plush coat and round face. Calm, easygoing, and undemanding of attention.",
      "generalHealthConsiderations": "May be prone to hypertrophic cardiomyopathy and obesity; portion control and routine veterinary monitoring are important.",
      "weightRange": "4-8 kg",
      "lifespan": "12-20 years",
      "popularity": "Common"
    },
    {
      "id": "burmese",
      "species": "cat",
      "name": "Burmese",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Social",
        "Playful",
        "Vocal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium shorthair breed developed from cats imported from Burma. Affectionate, social, and people-oriented.",
      "generalHealthConsiderations": "May be prone to certain craniofacial defects and diabetes; routine veterinary monitoring is recommended.",
      "weightRange": "3-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Common"
    },
    {
      "id": "burmilla",
      "species": "cat",
      "name": "Burmilla",
      "size": "medium",
      "temperament": [
        "Sweet",
        "Playful",
        "Affectionate",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium shorthair breed developed from Burmese and Chinchilla Persian crosses. Sweet, playful, and gentle.",
      "generalHealthConsiderations": "Generally healthy; may be prone to polycystic kidney disease (in Persian ancestry) and certain eye conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common"
    },
    {
      "id": "chartreux",
      "species": "cat",
      "name": "Chartreux",
      "size": "medium",
      "temperament": [
        "Quiet",
        "Gentle",
        "Loyal",
        "Independent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A medium French breed with a dense blue-grey coat and copper eyes. Quiet, gentle, and historically associated with French monasteries.",
      "generalHealthConsiderations": "Generally healthy; may be prone to patellar luxation and certain eye conditions.",
      "weightRange": "3-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "chausie",
      "species": "cat",
      "name": "Chausie",
      "size": "large",
      "temperament": [
        "Active",
        "Intelligent",
        "Athletic",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Experienced owner"
      ],
      "overview": "A large breed developed by crossing domestic cats with the Jungle Cat. Active, athletic, and intelligent.",
      "generalHealthConsiderations": "Generally healthy; some individuals may have digestive sensitivities to certain grains.",
      "weightRange": "4-9 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common"
    },
    {
      "id": "colorpoint-shorthair",
      "species": "cat",
      "name": "Colorpoint Shorthair",
      "size": "medium",
      "temperament": [
        "Vocal",
        "Social",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A Siamese-type breed developed in additional color points beyond the traditional four. Vocal, social, and people-oriented.",
      "generalHealthConsiderations": "May be prone to dental issues, respiratory infections, and certain eye conditions.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "cornish-rex",
      "species": "cat",
      "name": "Cornish Rex",
      "size": "medium",
      "temperament": [
        "Active",
        "Playful",
        "Affectionate",
        "Curious"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A breed with a distinctive wavy coat and slender build. Active, playful, and people-oriented.",
      "generalHealthConsiderations": "Coat is fine and offers little insulation — sensitive to cold and sunburn. May be prone to certain heart conditions.",
      "weightRange": "2.5-4.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "cymric",
      "species": "cat",
      "name": "Cymric",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Playful",
        "Loyal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A longhaired variant of the Manx, distinguished by its tailless or short-tailed appearance. Gentle, affectionate, and playful.",
      "generalHealthConsiderations": "May be prone to Manx syndrome (spinal and neurological issues related to the tailless gene); routine veterinary monitoring is recommended.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "devon-rex",
      "species": "cat",
      "name": "Devon Rex",
      "size": "small",
      "temperament": [
        "Playful",
        "Mischievous",
        "Affectionate",
        "Active"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A small breed with a soft, wavy coat and oversized ears. Playful, mischievous, and people-oriented.",
      "generalHealthConsiderations": "Coat is delicate; may be prone to hereditary baldness, certain heart conditions, and patellar luxation.",
      "weightRange": "2.5-4 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "donskoy",
      "species": "cat",
      "name": "Donskoy",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Loyal",
        "Social",
        "Curious"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Indoor",
        "Warm home"
      ],
      "overview": "A hairless breed developed in Russia from a natural mutation. Affectionate, loyal, and people-oriented.",
      "generalHealthConsiderations": "Hairless skin is prone to sunburn, oil buildup, and cold sensitivity; regular bathing and skin care are required.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "egyptian-mau",
      "species": "cat",
      "name": "Egyptian Mau",
      "size": "medium",
      "temperament": [
        "Active",
        "Loyal",
        "Intelligent",
        "Athletic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Family home"
      ],
      "overview": "A medium spotted breed claimed to descend from cats depicted in ancient Egyptian art. Active, athletic, and naturally fast.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain heart conditions and allergies in some lines.",
      "weightRange": "3-5 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common"
    },
    {
      "id": "european-shorthair",
      "species": "cat",
      "name": "European Shorthair",
      "size": "medium",
      "temperament": [
        "Hardy",
        "Affectionate",
        "Independent",
        "Active"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Family home"
      ],
      "overview": "A natural European breed developed from working farm cats. Hardy, affectionate, and independent.",
      "generalHealthConsiderations": "Generally healthy; may be prone to dental and weight issues.",
      "weightRange": "3-6 kg",
      "lifespan": "14-20 years",
      "popularity": "Less common"
    },
    {
      "id": "exotic-shorthair",
      "species": "cat",
      "name": "Exotic Shorthair",
      "size": "medium",
      "temperament": [
        "Quiet",
        "Affectionate",
        "Easygoing",
        "Gentle"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A shorthaired Persian-type breed with a flat face and plush coat. Quiet, gentle, and affectionate.",
      "generalHealthConsiderations": "Brachycephalic features may cause breathing and eye drainage issues; may be prone to polycystic kidney disease.",
      "weightRange": "3-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Common"
    },
    {
      "id": "foldex",
      "species": "cat",
      "name": "Foldex",
      "size": "medium",
      "temperament": [
        "Sweet",
        "Affectionate",
        "Quiet",
        "Calm"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A Canadian breed developed from Exotic Shorthair and Scottish Fold crosses. Sweet, calm, and distinctive for its folded ears.",
      "generalHealthConsiderations": "Folded-ear gene is linked to osteochondrodysplasia; routine joint monitoring is recommended.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "german-rex",
      "species": "cat",
      "name": "German Rex",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Intelligent",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium shorthair breed with a soft, wavy coat, developed in Germany. Affectionate, playful, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "havana-brown",
      "species": "cat",
      "name": "Havana Brown",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Intelligent",
        "Vocal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium brown breed developed in Britain from Siamese ancestry. Affectionate, playful, and distinctive for its muzzle shape.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye and respiratory conditions.",
      "weightRange": "2.5-4.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "highland-fold",
      "species": "cat",
      "name": "Highland Fold",
      "size": "medium",
      "temperament": [
        "Sweet",
        "Quiet",
        "Affectionate",
        "Calm"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A longhaired variant of the Scottish Fold with folded ears. Sweet, calm, and quietly affectionate.",
      "generalHealthConsiderations": "Folded-ear gene is linked to osteochondrodysplasia; routine joint monitoring is recommended.",
      "weightRange": "4-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "himalayan",
      "species": "cat",
      "name": "Himalayan",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Quiet",
        "Affectionate",
        "Calm"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A color-point Persian-type breed developed by crossing Persians with Siamese. Gentle, quiet, and people-oriented.",
      "generalHealthConsiderations": "Brachycephalic features may cause breathing and eye drainage issues; may be prone to polycystic kidney disease. Daily grooming is essential.",
      "weightRange": "3-5.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Common"
    },
    {
      "id": "japanese-bobtail",
      "species": "cat",
      "name": "Japanese Bobtail",
      "size": "medium",
      "temperament": [
        "Active",
        "Intelligent",
        "Affectionate",
        "Vocal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A natural Japanese breed distinguished by its pom-pom-like bobbed tail. Active, intelligent, and traditionally depicted in Japanese art.",
      "generalHealthConsiderations": "Generally healthy; bobtail gene is not associated with spinal issues. May be prone to certain eye conditions.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "khao-manee",
      "species": "cat",
      "name": "Khao Manee",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Social",
        "Curious"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A natural Thai breed with a short white coat, often with odd-colored eyes. Affectionate, social, and historically prized in Thai culture.",
      "generalHealthConsiderations": "Some lines may carry a deafness gene associated with white coat and blue eyes.",
      "weightRange": "2.5-4.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "korat",
      "species": "cat",
      "name": "Korat",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Loyal",
        "Quiet",
        "Intelligent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A natural Thai breed with a silver-blue coat and distinctive heart-shaped face. Gentle, loyal, and quietly devoted to family.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain neurodegenerative conditions in some lines.",
      "weightRange": "2.5-4.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "laperm",
      "species": "cat",
      "name": "LaPerm",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Curious",
        "Active"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A breed developed in the United States from a natural mutation, distinguished by its soft, curly coat. Affectionate, gentle, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; coat is low-shedding but requires occasional grooming.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "lykoi",
      "species": "cat",
      "name": "Lykoi",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Curious",
        "Active",
        "Loyal"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A breed developed from a natural mutation producing a partially hairless, werewolf-like appearance. Affectionate, curious, and active.",
      "generalHealthConsiderations": "Coat is sparse and may molt seasonally; skin requires routine care and protection from cold and sun.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "maine-coon",
      "species": "cat",
      "name": "Maine Coon",
      "size": "large",
      "temperament": [
        "Gentle",
        "Friendly",
        "Playful",
        "Intelligent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with space",
        "Family home"
      ],
      "overview": "One of the largest domesticated cat breeds, native to the state of Maine. Known as \"gentle giants\" for their friendly, easygoing nature.",
      "generalHealthConsiderations": "May be prone to hypertrophic cardiomyopathy, hip dysplasia, and spinal muscular atrophy; regular grooming helps manage the long coat.",
      "weightRange": "4-8 kg (female); 6-11 kg (male)",
      "lifespan": "12-15 years",
      "popularity": "Popular"
    },
    {
      "id": "manx",
      "species": "cat",
      "name": "Manx",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Affectionate",
        "Playful",
        "Loyal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A breed from the Isle of Man distinguished by its naturally tailless or short-tailed appearance. Gentle, playful, and loyal.",
      "generalHealthConsiderations": "May be prone to Manx syndrome (spinal and neurological issues related to the tailless gene); routine veterinary monitoring is recommended.",
      "weightRange": "3-5 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common"
    },
    {
      "id": "minskin",
      "species": "cat",
      "name": "Minskin",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Playful",
        "Social",
        "Curious"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Indoor",
        "Warm home"
      ],
      "overview": "A small breed developed from Munchkin, Sphynx, and other crosses, with short legs and sparse fur. Affectionate, social, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; sparse coat requires skin care similar to other hairless breeds.",
      "weightRange": "1.5-3 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "munchkin",
      "species": "cat",
      "name": "Munchkin",
      "size": "small",
      "temperament": [
        "Playful",
        "Affectionate",
        "Active",
        "Outgoing"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A breed distinguished by short legs resulting from a natural mutation. Playful, outgoing, and surprisingly agile.",
      "generalHealthConsiderations": "Generally healthy; short-legged structure may predispose some individuals to spinal issues, though debate continues among veterinarians and breeders.",
      "weightRange": "2-4 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "nebelung",
      "species": "cat",
      "name": "Nebelung",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Quiet",
        "Loyal",
        "Reserved"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Quiet household"
      ],
      "overview": "A longhaired breed with a silvery-blue coat and vivid green eyes. Quiet, gentle, and reserved with strangers but devoted to family.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye and dental conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "14-16 years",
      "popularity": "Less common"
    },
    {
      "id": "norwegian-forest-cat",
      "species": "cat",
      "name": "Norwegian Forest Cat",
      "size": "large",
      "temperament": [
        "Gentle",
        "Friendly",
        "Independent",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with space",
        "Cool climate"
      ],
      "overview": "A large, natural breed from Norway with a thick, water-resistant double coat adapted to cold climates. Calm, friendly, and naturally athletic.",
      "generalHealthConsiderations": "Generally hardy; may be prone to hypertrophic cardiomyopathy and glycogen storage disease type IV in some lines. Regular brushing prevents matting.",
      "weightRange": "4-6 kg (female); 5-9 kg (male)",
      "lifespan": "14-16 years",
      "popularity": "Less common"
    },
    {
      "id": "ocicat",
      "species": "cat",
      "name": "Ocicat",
      "size": "medium",
      "temperament": [
        "Active",
        "Social",
        "Playful",
        "Intelligent"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A spotted breed developed from Abyssinian, Siamese, and American Shorthair crosses. Active, social, and dog-like in devotion.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain dental and eye conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "oriental-longhair",
      "species": "cat",
      "name": "Oriental Longhair",
      "size": "medium",
      "temperament": [
        "Vocal",
        "Social",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A longhaired Siamese-type breed developed in many coat colors. Vocal, social, and people-oriented.",
      "generalHealthConsiderations": "May be prone to dental issues, respiratory infections, and certain eye conditions.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "oriental-shorthair",
      "species": "cat",
      "name": "Oriental Shorthair",
      "size": "medium",
      "temperament": [
        "Vocal",
        "Social",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A Siamese-type breed developed in many coat colors and patterns. Vocal, social, and intensely people-oriented.",
      "generalHealthConsiderations": "May be prone to dental issues, respiratory infections, and certain eye conditions.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Common"
    },
    {
      "id": "persian",
      "species": "cat",
      "name": "Persian",
      "size": "medium",
      "temperament": [
        "Quiet",
        "Gentle",
        "Affectionate",
        "Calm"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "One of the oldest known cat breeds, recognized for its long, luxurious coat and calm, docile temperament. Best suited to a quiet indoor environment.",
      "generalHealthConsiderations": "Flat-faced (brachycephalic) structure may cause breathing and eye drainage issues; prone to polycystic kidney disease. Daily grooming is essential to prevent matting.",
      "weightRange": "3-5.5 kg",
      "lifespan": "12-17 years",
      "popularity": "Popular"
    },
    {
      "id": "peterbald",
      "species": "cat",
      "name": "Peterbald",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Social",
        "Intelligent",
        "Loyal"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Warm home"
      ],
      "overview": "A Russian breed developed from Oriental Shorthair and Donskoy crosses, varying from hairless to fully coated. Affectionate, social, and people-oriented.",
      "generalHealthConsiderations": "Hairless individuals require routine skin care; may be prone to dental and skin sensitivities.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "pixiebob",
      "species": "cat",
      "name": "Pixiebob",
      "size": "medium",
      "temperament": [
        "Loyal",
        "Affectionate",
        "Intelligent",
        "Social"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Family home"
      ],
      "overview": "A medium breed developed to resemble the North American bobcat, with a naturally short tail. Loyal, social, and dog-like in temperament.",
      "generalHealthConsiderations": "Generally healthy; some lines may carry certain reproductive quirks; routine veterinary monitoring is recommended.",
      "weightRange": "3-6 kg",
      "lifespan": "13-15 years",
      "popularity": "Less common"
    },
    {
      "id": "ragamuffin",
      "species": "cat",
      "name": "Ragamuffin",
      "size": "large",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Calm",
        "Sociable"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A large, semi-longhaired breed closely related to the Ragdoll. Affectionate, gentle, and devoted to family.",
      "generalHealthConsiderations": "Generally healthy; may be prone to hypertrophic cardiomyopathy and polycystic kidney disease in some lines.",
      "weightRange": "4-9 kg",
      "lifespan": "14-17 years",
      "popularity": "Less common"
    },
    {
      "id": "ragdoll",
      "species": "cat",
      "name": "Ragdoll",
      "size": "large",
      "temperament": [
        "Docile",
        "Affectionate",
        "Relaxed",
        "Gentle"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A large, semi-longhaired breed known for going limp when picked up. Docile and people-oriented, well-suited to indoor life.",
      "generalHealthConsiderations": "May be prone to hypertrophic cardiomyopathy and bladder stones; recommended as an indoor-only cat.",
      "weightRange": "4-9 kg",
      "lifespan": "12-17 years",
      "popularity": "Popular"
    },
    {
      "id": "russian-blue",
      "species": "cat",
      "name": "Russian Blue",
      "size": "medium",
      "temperament": [
        "Gentle",
        "Reserved",
        "Loyal",
        "Quiet"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "A natural breed with a short, dense, blue-grey coat and vivid green eyes. Quiet, reserved, and forms strong bonds with family.",
      "generalHealthConsiderations": "Generally healthy; may be prone to bladder stones. Routine dental care is recommended.",
      "weightRange": "3-6 kg",
      "lifespan": "15-20 years",
      "popularity": "Less common"
    },
    {
      "id": "savannah",
      "species": "cat",
      "name": "Savannah",
      "size": "large",
      "temperament": [
        "Active",
        "Intelligent",
        "Athletic",
        "Loyal"
      ],
      "exerciseNeeds": "very-high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Experienced owner"
      ],
      "overview": "A large breed developed by crossing domestic cats with the African Serval. Active, athletic, and intelligent, with early-generation individuals requiring experienced handling.",
      "generalHealthConsiderations": "Generally healthy; some individuals may be sensitive to certain anesthetics. Requires substantial stimulation and space.",
      "weightRange": "4-11 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "scottish-fold",
      "species": "cat",
      "name": "Scottish Fold",
      "size": "medium",
      "temperament": [
        "Sweet",
        "Quiet",
        "Loyal",
        "Calm"
      ],
      "exerciseNeeds": "low",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "Recognizable by its characteristic folded ears, the Scottish Fold is sweet-tempered and quietly affectionate.",
      "generalHealthConsiderations": "The folded-ear gene is linked to osteochondrodysplasia, a cartilage and bone condition; routine joint monitoring is recommended.",
      "weightRange": "4-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Common"
    },
    {
      "id": "selkirk-rex",
      "species": "cat",
      "name": "Selkirk Rex",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Playful",
        "Calm"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A breed with a distinctive curly coat, developed from natural mutations in the United States. Affectionate, gentle, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; coat requires regular grooming to prevent matting.",
      "weightRange": "3-6 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "serengeti",
      "species": "cat",
      "name": "Serengeti",
      "size": "medium",
      "temperament": [
        "Active",
        "Confident",
        "Social",
        "Athletic"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Active household"
      ],
      "overview": "A medium breed developed from Oriental Shorthair and Bengal crosses, resembling a serval. Active, confident, and athletic.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye conditions and progressive retinal atrophy in some lines.",
      "weightRange": "3-6 kg",
      "lifespan": "12-14 years",
      "popularity": "Less common"
    },
    {
      "id": "siamese",
      "species": "cat",
      "name": "Siamese",
      "size": "medium",
      "temperament": [
        "Vocal",
        "Social",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Indoor"
      ],
      "overview": "One of the oldest and most recognizable Asian breeds, distinguished by its striking color-point coat and vivid blue eyes. Highly vocal and people-oriented.",
      "generalHealthConsiderations": "May be prone to dental issues, respiratory infections, and certain eye conditions; benefits from regular interaction and mental stimulation.",
      "weightRange": "3-5 kg",
      "lifespan": "12-20 years",
      "popularity": "Popular"
    },
    {
      "id": "siberian",
      "species": "cat",
      "name": "Siberian",
      "size": "large",
      "temperament": [
        "Affectionate",
        "Gentle",
        "Playful",
        "Hardy"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "House with space",
        "Cool climate"
      ],
      "overview": "A large natural breed from Russia with a thick, water-resistant triple coat. Affectionate, hardy, and traditionally kept as a farm and household cat.",
      "generalHealthConsiderations": "Generally healthy; may be prone to hypertrophic cardiomyopathy in some lines. Regular brushing prevents matting.",
      "weightRange": "4-7 kg (female); 5-9 kg (male)",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "singapura",
      "species": "cat",
      "name": "Singapura",
      "size": "small",
      "temperament": [
        "Affectionate",
        "Curious",
        "Playful",
        "Gentle"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "One of the smallest cat breeds, developed from cats imported from Singapore. Affectionate, curious, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain uterine conditions in some lines. Routine veterinary monitoring is recommended.",
      "weightRange": "2-3 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "snowshoe",
      "species": "cat",
      "name": "Snowshoe",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Social",
        "Intelligent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium breed developed from Siamese and American Shorthair crosses, distinguished by its white \"snowshoe\" paws. Affectionate, social, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye conditions and dental issues.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "sokoke",
      "species": "cat",
      "name": "Sokoke",
      "size": "medium",
      "temperament": [
        "Active",
        "Intelligent",
        "Independent",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "House with space",
        "Active household"
      ],
      "overview": "A natural breed from the Kenyan coastal forest, distinguished by its modified tabby coat. Active, intelligent, and independent.",
      "generalHealthConsiderations": "Generally healthy; small gene pool warrants responsible breeding practices.",
      "weightRange": "2.5-4.5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "somali",
      "species": "cat",
      "name": "Somali",
      "size": "medium",
      "temperament": [
        "Active",
        "Playful",
        "Intelligent",
        "Curious"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with space",
        "Active household"
      ],
      "overview": "A longhaired version of the Abyssinian, with a bushy tail and ticked coat. Active, playful, and intelligent.",
      "generalHealthConsiderations": "Generally healthy; may be prone to pyruvate kinase deficiency and certain dental conditions.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "sphynx",
      "species": "cat",
      "name": "Sphynx",
      "size": "medium",
      "temperament": [
        "Friendly",
        "Outgoing",
        "Curious",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "high",
      "livingEnvironment": [
        "Indoor",
        "Warm home"
      ],
      "overview": "A hairless breed resulting from a natural genetic mutation. Friendly, extroverted, and warm to the touch.",
      "generalHealthConsiderations": "Lacks a coat — prone to sunburn, cold sensitivity, and skin oil buildup; regular bathing is required. May be prone to hypertrophic cardiomyopathy.",
      "weightRange": "3-5 kg",
      "lifespan": "9-15 years",
      "popularity": "Less common"
    },
    {
      "id": "thai",
      "species": "cat",
      "name": "Thai",
      "size": "medium",
      "temperament": [
        "Vocal",
        "Social",
        "Intelligent",
        "Affectionate"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A natural breed preserving the traditional, rounder-headed Siamese type. Vocal, social, and people-oriented.",
      "generalHealthConsiderations": "May be prone to dental issues, respiratory infections, and certain eye conditions.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "tonkinese",
      "species": "cat",
      "name": "Tonkinese",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Social",
        "Intelligent"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium breed developed from Burmese and Siamese crosses. Affectionate, playful, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain eye conditions and dental issues.",
      "weightRange": "3-5 kg",
      "lifespan": "12-16 years",
      "popularity": "Less common"
    },
    {
      "id": "toyger",
      "species": "cat",
      "name": "Toyger",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Intelligent",
        "Active"
      ],
      "exerciseNeeds": "moderate",
      "groomingNeeds": "low",
      "livingEnvironment": [
        "Apartment-friendly",
        "Family home"
      ],
      "overview": "A medium breed developed to resemble a toy tiger, with bold vertical stripes on an orange background. Affectionate, playful, and people-oriented.",
      "generalHealthConsiderations": "Generally healthy; may be prone to certain heart conditions in some lines.",
      "weightRange": "3-5 kg",
      "lifespan": "12-15 years",
      "popularity": "Less common"
    },
    {
      "id": "turkish-angora",
      "species": "cat",
      "name": "Turkish Angora",
      "size": "medium",
      "temperament": [
        "Affectionate",
        "Playful",
        "Intelligent",
        "Active"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with space",
        "Family home"
      ],
      "overview": "A natural breed from Turkey with a silky semi-long coat. Affectionate, intelligent, and historically prized for its white coat and often odd-colored eyes.",
      "generalHealthConsiderations": "Generally healthy; some lines may carry a deafness gene associated with white coat and blue eyes.",
      "weightRange": "2.5-5 kg",
      "lifespan": "12-18 years",
      "popularity": "Less common"
    },
    {
      "id": "turkish-van",
      "species": "cat",
      "name": "Turkish Van",
      "size": "large",
      "temperament": [
        "Active",
        "Intelligent",
        "Independent",
        "Affectionate"
      ],
      "exerciseNeeds": "high",
      "groomingNeeds": "moderate",
      "livingEnvironment": [
        "House with space",
        "Family home"
      ],
      "overview": "A large natural breed from the Lake Van region of Turkey, distinguished by its all-white body with colored markings on head and tail. Active, intelligent, and historically fond of water.",
      "generalHealthConsiderations": "Generally healthy; some lines may carry a deafness gene associated with white coat and blue eyes.",
      "weightRange": "3-6 kg (female); 5-8 kg (male)",
      "lifespan": "12-17 years",
      "popularity": "Less common"
    }
  ],
  "behavior": [
    {
      "stableId": "behavior-dog-barking",
      "status": "SEED_CONTENT",
      "review": "REQUIRES_CLINICAL_REVIEW",
      "cmsReplaceable": true,
      "lastReviewed": null,
      "species": "dog",
      "category": "training",
      "title": "Excessive barking",
      "description": "Barking is natural communication, but a sudden or persistent increase can point to boredom, anxiety, discomfort, or a change in the environment.",
      "practicalAdvice": [
        "Identify the trigger and note when it happens",
        "Add age-appropriate exercise and mental stimulation",
        "Reward quiet pauses rather than shouting over the barking",
        "Use puzzle feeders and safe enrichment when your dog is alone"
      ],
      "whenToSeekHelp": "If barking is sudden, severe, or continues despite consistent training, speak with your vet and a qualified behaviourist.",
      "escalate": true
    },
    {
      "stableId": "behavior-dog-separation",
      "status": "SEED_CONTENT",
      "review": "REQUIRES_CLINICAL_REVIEW",
      "cmsReplaceable": true,
      "lastReviewed": null,
      "species": "dog",
      "category": "anxiety",
      "title": "Separation distress",
      "description": "Some dogs struggle when left alone. Destruction, vocalisation, toileting, pacing, or self-injury can be signs of distress rather than stubbornness.",
      "practicalAdvice": [
        "Start with short, calm absences and build gradually",
        "Keep departures and arrivals low-key",
        "Offer safe enrichment that only appears during alone time",
        "Keep a simple log of duration and behaviour"
      ],
      "whenToSeekHelp": "Self-injury, panic, or persistent destruction needs veterinary guidance and a qualified behaviour plan.",
      "escalate": true
    },
    {
      "stableId": "behavior-dog-house-training",
      "status": "SEED_CONTENT",
      "review": "REQUIRES_CLINICAL_REVIEW",
      "cmsReplaceable": true,
      "lastReviewed": null,
      "species": "dog",
      "category": "everyday",
      "title": "House training",
      "description": "A predictable routine, close supervision, and immediate reward make toileting habits easier to build.",
      "practicalAdvice": [
        "Take puppies out after waking, eating, drinking, and playing",
        "Reward immediately in the correct place",
        "Clean accidents with an enzymatic cleaner",
        "Do not punish accidents"
      ],
      "whenToSeekHelp": "If an adult dog that was previously house-trained starts having accidents, arrange a veterinary check to rule out medical causes.",
      "escalate": true
    },
    {
      "stableId": "behavior-cat-scratching",
      "status": "SEED_CONTENT",
      "review": "REQUIRES_CLINICAL_REVIEW",
      "cmsReplaceable": true,
      "lastReviewed": null,
      "species": "cat",
      "category": "everyday",
      "title": "Scratching furniture",
      "description": "Scratching is normal cat behaviour used for stretching, claw care, scent marking, and emotional regulation.",
      "practicalAdvice": [
        "Place sturdy scratchers beside the areas your cat already uses",
        "Try both vertical and horizontal surfaces",
        "Reward use with play or treats",
        "Protect furniture temporarily while the new habit forms"
      ],
      "whenToSeekHelp": "Sudden destructive behaviour, hiding, or changes in appetite can signal stress or illness and should be discussed with a vet.",
      "escalate": true
    },
    {
      "stableId": "behavior-cat-litter",
      "status": "SEED_CONTENT",
      "review": "REQUIRES_CLINICAL_REVIEW",
      "cmsReplaceable": true,
      "lastReviewed": null,
      "species": "cat",
      "category": "safety",
      "title": "Litter box changes",
      "description": "A cat that stops using the litter box may be communicating discomfort, stress, or a problem with the box or litter.",
      "practicalAdvice": [
        "Keep boxes clean and place them in quiet, accessible locations",
        "Offer one box per cat plus one extra where possible",
        "Avoid sudden litter or location changes",
        "Use an enzymatic cleaner for accidents"
      ],
      "whenToSeekHelp": "Straining, frequent attempts, blood, crying, or no urine is urgent. Contact a veterinarian immediately.",
      "escalate": true
    },
    {
      "stableId": "behavior-cat-introduction",
      "status": "SEED_CONTENT",
      "review": "REQUIRES_CLINICAL_REVIEW",
      "cmsReplaceable": true,
      "lastReviewed": null,
      "species": "cat",
      "category": "anxiety",
      "title": "Introducing cats slowly",
      "description": "Gradual introductions help cats build familiarity without forcing direct contact before they are ready.",
      "practicalAdvice": [
        "Start with separate spaces and separate resources",
        "Swap bedding or scent items before visual introductions",
        "Pair calm behaviour with food or play",
        "Increase shared time only when both cats remain relaxed"
      ],
      "whenToSeekHelp": "Persistent fighting, injury, appetite changes, or hiding warrants veterinary and behaviour support.",
      "escalate": true
    }
  ]
}
JSON
    , true, 512, JSON_THROW_ON_ERROR),
];
