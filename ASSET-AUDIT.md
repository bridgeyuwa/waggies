# Waggies public image-slot audit

Audit date: 2026-09-23. This is the post-remediation ledger generated from the rendered local application and the current local dataset.

## Exact verification summary

| Measure | Result |
| --- | ---: |
| Public route patterns | 43 active canonical patterns + 3 legacy redirect patterns = 46 total |
| Concrete public URLs | 66 requested; 65 unique final URLs |
| Dynamic records inspected | 4 guides; 10 knowledge articles; 10 products; 23 gallery items; 12 testimonials |
| Pages rendered | 66 requested; 65 unique final pages |
| Image-slot occurrences | 246 semantic slots (246 local + 0 external) |
| Local media occurrences | 246 |
| External image occurrences | 0 semantic slots |
| Hidden client-local saved-list `<img>` observations | 66 external occurrences, excluded from the semantic ledger |
| Hydrated page-DOM `<img>` observations | 312 (246 page-owned local + 66 hidden client-local external) |
| Unique canonical local physical files | 237 |
| Unique final semantic assets | 237 (237 local + 0 external) |
| Placeholder collisions | 0 remaining; 14 historical cases resolved into owned paths |
| Truly shared active assets | 9 local physical URLs reused by 18 active slots; 0 external semantic assets |
| Intentional duplicate physical assets | 24 byte-identical active groups containing 229 files (205 extra copies) |
| Missing expected image slots | 0 |
| Broken image URLs | 0 |
| Canonical local files | 237; all 237 tested HTTP 200 |
| Legacy physical files | 73 retained for compatibility (70 editorial-tree files + 3 redirect-target files) |
| Legacy redirect routes | 3 HTTP 301 routes |
| Original orphan candidates | 225 = 0 CANONICAL + 0 SHARED + 3 LEGACY + 182 PLACEHOLDER-UNUSED + 40 OBSOLETE + 0 UNKNOWN |
| Orphan files removed | 222 (182 PLACEHOLDER-UNUSED + 40 OBSOLETE) |
| Unresolved orphan candidates after cleanup | 0 |

The deliberate application redirect is `/tools/cost-calculator` → `/services/pricing`; its final response is the pricing page. The three root legacy service-image URLs return HTTP 301. The 66 external DOM observations were hidden saved-list state (`images.unsplash.com`), not page-owned slots, so they do not create a semantic asset. No stale active application reference contains a deleted path or a legacy editorial directory.

## Concrete URL inventory

| # | Requested URL | Final URL |
| ---: | --- | --- |
| 1 | `/` | `/` |
| 2 | `/about` | `/about` |
| 3 | `/about/testimonials` | `/about/testimonials` |
| 4 | `/about/gallery` | `/about/gallery` |
| 5 | `/about/careers` | `/about/careers` |
| 6 | `/about/partnerships` | `/about/partnerships` |
| 7 | `/services` | `/services` |
| 8 | `/services/boarding` | `/services/boarding` |
| 9 | `/services/boarding/dogs` | `/services/boarding/dogs` |
| 10 | `/services/boarding/cats` | `/services/boarding/cats` |
| 11 | `/services/boarding/exotic` | `/services/boarding/exotic` |
| 12 | `/services/grooming` | `/services/grooming` |
| 13 | `/services/training` | `/services/training` |
| 14 | `/services/vet-care` | `/services/vet-care` |
| 15 | `/services/pricing` | `/services/pricing` |
| 16 | `/services/relocation` | `/services/relocation` |
| 17 | `/services/relocation/import` | `/services/relocation/import` |
| 18 | `/services/relocation/export` | `/services/relocation/export` |
| 19 | `/services/relocation/transport` | `/services/relocation/transport` |
| 20 | `/services/relocation/checklist` | `/services/relocation/checklist` |
| 21 | `/faq` | `/faq` |
| 22 | `/privacy-policy` | `/privacy-policy` |
| 23 | `/terms-of-service` | `/terms-of-service` |
| 24 | `/cookies-policy` | `/cookies-policy` |
| 25 | `/contact` | `/contact` |
| 26 | `/book` | `/book` |
| 27 | `/loyalty` | `/loyalty` |
| 28 | `/shop` | `/shop` |
| 29 | `/guides` | `/guides` |
| 30 | `/knowledge-base` | `/knowledge-base` |
| 31 | `/tools` | `/tools` |
| 32 | `/tools/symptom-checker` | `/tools/symptom-checker` |
| 33 | `/tools/vaccination-schedule` | `/tools/vaccination-schedule` |
| 34 | `/tools/parasite-schedule` | `/tools/parasite-schedule` |
| 35 | `/tools/emergency-guide` | `/tools/emergency-guide` |
| 36 | `/tools/medication-dosage-guide` | `/tools/medication-dosage-guide` |
| 37 | `/tools/pet-age-calculator` | `/tools/pet-age-calculator` |
| 38 | `/tools/cost-calculator` | `/services/pricing` |
| 39 | `/tools/nutrition-calculator` | `/tools/nutrition-calculator` |
| 40 | `/tools/breed-finder` | `/tools/breed-finder` |
| 41 | `/tools/behavior-tips` | `/tools/behavior-tips` |
| 42 | `/tools/new-pet-checklist` | `/tools/new-pet-checklist` |
| 43 | `/guides/what-to-expect-boarding` | `/guides/what-to-expect-boarding` |
| 44 | `/guides/pet-transport-what-to-know` | `/guides/pet-transport-what-to-know` |
| 45 | `/guides/preparing-pet-boarding` | `/guides/preparing-pet-boarding` |
| 46 | `/guides/grooming-services-explained` | `/guides/grooming-services-explained` |
| 47 | `/knowledge-base/cat-care-beginners-nigeria` | `/knowledge-base/cat-care-beginners-nigeria` |
| 48 | `/knowledge-base/pet-vaccination-schedule-guide` | `/knowledge-base/pet-vaccination-schedule-guide` |
| 49 | `/knowledge-base/basic-dog-training-guide` | `/knowledge-base/basic-dog-training-guide` |
| 50 | `/knowledge-base/understanding-common-dog-illnesses` | `/knowledge-base/understanding-common-dog-illnesses` |
| 51 | `/knowledge-base/best-nutrition-tips-nigerian-pets` | `/knowledge-base/best-nutrition-tips-nigerian-pets` |
| 52 | `/knowledge-base/separation-anxiety-guide` | `/knowledge-base/separation-anxiety-guide` |
| 53 | `/knowledge-base/grooming-routine-at-home` | `/knowledge-base/grooming-routine-at-home` |
| 54 | `/knowledge-base/new-pet-checklist` | `/knowledge-base/new-pet-checklist` |
| 55 | `/knowledge-base/when-to-take-pet-vet` | `/knowledge-base/when-to-take-pet-vet` |
| 56 | `/knowledge-base/seasonal-pet-care-nigeria` | `/knowledge-base/seasonal-pet-care-nigeria` |
| 57 | `/shop/royal-canin-puppy` | `/shop/royal-canin-puppy` |
| 58 | `/shop/whiskas-cat-food` | `/shop/whiskas-cat-food` |
| 59 | `/shop/chew-rope-toy` | `/shop/chew-rope-toy` |
| 60 | `/shop/interactive-puzzle-feeder` | `/shop/interactive-puzzle-feeder` |
| 61 | `/shop/oatmeal-shampoo` | `/shop/oatmeal-shampoo` |
| 62 | `/shop/deshedding-brush` | `/shop/deshedding-brush` |
| 63 | `/shop/tick-flea-collar` | `/shop/tick-flea-collar` |
| 64 | `/shop/pet-first-aid-kit` | `/shop/pet-first-aid-kit` |
| 65 | `/shop/padded-dog-harness` | `/shop/padded-dog-harness` |
| 66 | `/shop/raised-pet-bowl` | `/shop/raised-pet-bowl` |

## Page × section × image-slot map

Every row below is one rendered image occurrence. Repeated URLs and repeated files are intentionally retained as separate rows.

In this page map, “No raster image slot rendered” means no page-owned raster slot. Database-backed gallery records, guide/article covers and related items, and product suggestion/recent-item images are included when they render. Hidden client-local saved-list images are deliberately excluded.

### /

1. **Hero** — `https://waggies.test/media/home/hero.jpg` → `/media/home/hero.jpg` (owner: home)
2. **Home service card boarding** — `https://waggies.test/media/home/services-boarding.jpg` → `/media/home/services-boarding.jpg` (owner: home)
3. **Home service card grooming** — `https://waggies.test/media/home/services-grooming.jpg` → `/media/home/services-grooming.jpg` (owner: home)
4. **Home service card vet care** — `https://waggies.test/media/home/services-vet-care.jpg` → `/media/home/services-vet-care.jpg` (owner: home)
5. **Home service card training** — `https://waggies.test/media/home/services-training.jpg` → `/media/home/services-training.jpg` (owner: home)
6. **Home service card relocation** — `https://waggies.test/media/home/services-relocation.jpg` → `/media/home/services-relocation.jpg` (owner: home)
7. **Care standards** — `https://waggies.test/media/home/care-standards.jpg` → `/media/home/care-standards.jpg` (owner: home)

### /about

1. **Intro** — `https://waggies.test/media/about/intro.jpg` → `/media/about/intro.jpg` (owner: about)
2. **Veterinary care** — `https://waggies.test/media/about/veterinary-care.jpg` → `/media/about/veterinary-care.jpg` (owner: about)
3. **Grooming** — `https://waggies.test/media/about/grooming.jpg` → `/media/about/grooming.jpg` (owner: about)

### /about/testimonials

1. **Hero** — `https://waggies.test/media/about/testimonials/hero.jpg` → `/media/about/testimonials/hero.jpg` (owner: about)

### /about/gallery

1. **Gallery 01** — `https://waggies.test/media/about/gallery/gallery-01.jpg` → `/media/about/gallery/gallery-01.jpg` (owner: about)
2. **Gallery 02** — `https://waggies.test/media/about/gallery/gallery-02.jpg` → `/media/about/gallery/gallery-02.jpg` (owner: about)
3. **Gallery 03** — `https://waggies.test/media/about/gallery/gallery-03.jpg` → `/media/about/gallery/gallery-03.jpg` (owner: about)
4. **Gallery 04** — `https://waggies.test/media/about/gallery/gallery-04.jpg` → `/media/about/gallery/gallery-04.jpg` (owner: about)
5. **Gallery 05** — `https://waggies.test/media/about/gallery/gallery-05.jpg` → `/media/about/gallery/gallery-05.jpg` (owner: about)
6. **Gallery 06** — `https://waggies.test/media/about/gallery/gallery-06.jpg` → `/media/about/gallery/gallery-06.jpg` (owner: about)
7. **Gallery 07** — `https://waggies.test/media/about/gallery/gallery-07.jpg` → `/media/about/gallery/gallery-07.jpg` (owner: about)
8. **Gallery 08** — `https://waggies.test/media/about/gallery/gallery-08.jpg` → `/media/about/gallery/gallery-08.jpg` (owner: about)
9. **Gallery 09** — `https://waggies.test/media/about/gallery/gallery-09.jpg` → `/media/about/gallery/gallery-09.jpg` (owner: about)
10. **Gallery 10** — `https://waggies.test/media/about/gallery/gallery-10.jpg` → `/media/about/gallery/gallery-10.jpg` (owner: about)
11. **Gallery 11** — `https://waggies.test/media/about/gallery/gallery-11.jpg` → `/media/about/gallery/gallery-11.jpg` (owner: about)
12. **Gallery 12** — `https://waggies.test/media/about/gallery/gallery-12.jpg` → `/media/about/gallery/gallery-12.jpg` (owner: about)
13. **Gallery 13** — `https://waggies.test/media/about/gallery/gallery-13.jpg` → `/media/about/gallery/gallery-13.jpg` (owner: about)
14. **Gallery 14** — `https://waggies.test/media/about/gallery/gallery-14.jpg` → `/media/about/gallery/gallery-14.jpg` (owner: about)
15. **Gallery 15** — `https://waggies.test/media/about/gallery/gallery-15.jpg` → `/media/about/gallery/gallery-15.jpg` (owner: about)
16. **Gallery 16** — `https://waggies.test/media/about/gallery/gallery-16.jpg` → `/media/about/gallery/gallery-16.jpg` (owner: about)
17. **Gallery 17** — `https://waggies.test/media/about/gallery/gallery-17.jpg` → `/media/about/gallery/gallery-17.jpg` (owner: about)
18. **Gallery 18** — `https://waggies.test/media/about/gallery/gallery-18.jpg` → `/media/about/gallery/gallery-18.jpg` (owner: about)
19. **Gallery 19** — `https://waggies.test/media/about/gallery/gallery-19.jpg` → `/media/about/gallery/gallery-19.jpg` (owner: about)
20. **Gallery 20** — `https://waggies.test/media/about/gallery/gallery-20.jpg` → `/media/about/gallery/gallery-20.jpg` (owner: about)
21. **Gallery 21** — `https://waggies.test/media/about/gallery/gallery-21.jpg` → `/media/about/gallery/gallery-21.jpg` (owner: about)
22. **Gallery 22** — `https://waggies.test/media/about/gallery/gallery-22.jpg` → `/media/about/gallery/gallery-22.jpg` (owner: about)
23. **Gallery 23** — `https://waggies.test/media/about/gallery/gallery-23.jpg` → `/media/about/gallery/gallery-23.jpg` (owner: about)
24. **Shared product promo** — `https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=600&h=600&fit=crop` → external shared asset (owner: global)

### /about/careers

1. **Team** — `https://waggies.test/media/about/careers/team.jpg` → `/media/about/careers/team.jpg` (owner: about)

### /about/partnerships

No raster image slot rendered.

### /services

1. **Hero** — `https://waggies.test/media/services/hero.jpg` → `/media/services/hero.jpg` (owner: services)
2. **Listing card dogs** — `https://waggies.test/media/services/boarding/card-dogs.jpg` → `/media/services/boarding/card-dogs.jpg` (owner: services)
3. **Hero** — `https://waggies.test/media/services/grooming/hero.jpg` → `/media/services/grooming/hero.jpg` (owner: services)
4. **Hero** — `https://waggies.test/media/services/vet-care/hero.jpg` → `/media/services/vet-care/hero.jpg` (owner: services)
5. **Hero** — `https://waggies.test/media/services/training/hero.jpg` → `/media/services/training/hero.jpg` (owner: services)
6. **Hero** — `https://waggies.test/media/services/relocation/hero.jpg` → `/media/services/relocation/hero.jpg` (owner: services)

### /services/boarding

1. **Hero** — `https://waggies.test/media/services/boarding/hero.jpg` → `/media/services/boarding/hero.jpg` (owner: services)
2. **Listing card dogs** — `https://waggies.test/media/services/boarding/card-dogs.jpg` → `/media/services/boarding/card-dogs.jpg` (owner: services)
3. **Listing card cats** — `https://waggies.test/media/services/boarding/card-cats.jpg` → `/media/services/boarding/card-cats.jpg` (owner: services)
4. **Listing card exotic** — `https://waggies.test/media/services/boarding/card-exotic.jpg` → `/media/services/boarding/card-exotic.jpg` (owner: services)

### /services/boarding/dogs

1. **Hero** — `https://waggies.test/media/services/boarding/dogs/hero.jpg` → `/media/services/boarding/dogs/hero.jpg` (owner: services)
2. **Daily-life gallery 01** — `https://waggies.test/media/services/boarding/dogs/daily-01.jpg` → `/media/services/boarding/dogs/daily-01.jpg` (owner: services)
3. **Daily-life gallery 02** — `https://waggies.test/media/services/boarding/dogs/daily-02.jpg` → `/media/services/boarding/dogs/daily-02.jpg` (owner: services)
4. **Daily-life gallery 03** — `https://waggies.test/media/services/boarding/dogs/daily-03.jpg` → `/media/services/boarding/dogs/daily-03.jpg` (owner: services)
5. **Daily-life gallery 04** — `https://waggies.test/media/services/boarding/dogs/daily-04.jpg` → `/media/services/boarding/dogs/daily-04.jpg` (owner: services)
6. **Related cats** — `https://waggies.test/media/services/boarding/dogs/related-cats.jpg` → `/media/services/boarding/dogs/related-cats.jpg` (owner: services)
7. **Related exotic** — `https://waggies.test/media/services/boarding/dogs/related-exotic.jpg` → `/media/services/boarding/dogs/related-exotic.jpg` (owner: services)

### /services/boarding/cats

1. **Hero** — `https://waggies.test/media/services/boarding/cats/hero.jpg` → `/media/services/boarding/cats/hero.jpg` (owner: services)
2. **Feline feature** — `https://waggies.test/media/services/boarding/cats/feline.jpg` → `/media/services/boarding/cats/feline.jpg` (owner: services)
3. **Daily-life gallery 01** — `https://waggies.test/media/services/boarding/cats/daily-01.jpg` → `/media/services/boarding/cats/daily-01.jpg` (owner: services)
4. **Daily-life gallery 02** — `https://waggies.test/media/services/boarding/cats/daily-02.jpg` → `/media/services/boarding/cats/daily-02.jpg` (owner: services)
5. **Daily-life gallery 03** — `https://waggies.test/media/services/boarding/cats/daily-03.jpg` → `/media/services/boarding/cats/daily-03.jpg` (owner: services)
6. **Daily-life gallery 04** — `https://waggies.test/media/services/boarding/cats/daily-04.jpg` → `/media/services/boarding/cats/daily-04.jpg` (owner: services)
7. **Related dogs** — `https://waggies.test/media/services/boarding/cats/related-dogs.jpg` → `/media/services/boarding/cats/related-dogs.jpg` (owner: services)
8. **Related exotic** — `https://waggies.test/media/services/boarding/cats/related-exotic.jpg` → `/media/services/boarding/cats/related-exotic.jpg` (owner: services)

### /services/boarding/exotic

1. **Hero** — `https://waggies.test/media/services/boarding/exotic/hero.jpg` → `/media/services/boarding/exotic/hero.jpg` (owner: services)
2. **Related dogs** — `https://waggies.test/media/services/boarding/exotic/related-dogs.jpg` → `/media/services/boarding/exotic/related-dogs.jpg` (owner: services)
3. **Related cats** — `https://waggies.test/media/services/boarding/exotic/related-cats.jpg` → `/media/services/boarding/exotic/related-cats.jpg` (owner: services)

### /services/grooming

1. **Hero** — `https://waggies.test/media/services/grooming/hero.jpg` → `/media/services/grooming/hero.jpg` (owner: services)

### /services/training

1. **Hero** — `https://waggies.test/media/services/training/hero.jpg` → `/media/services/training/hero.jpg` (owner: services)

### /services/vet-care

1. **Hero** — `https://waggies.test/media/services/vet-care/hero.jpg` → `/media/services/vet-care/hero.jpg` (owner: services)

### /services/pricing

No raster image slot rendered.

### /services/relocation

1. **Hero** — `https://waggies.test/media/services/relocation/hero.jpg` → `/media/services/relocation/hero.jpg` (owner: services)
2. **Listing card import** — `https://waggies.test/media/services/relocation/card-import.jpg` → `/media/services/relocation/card-import.jpg` (owner: services)
3. **Listing card export** — `https://waggies.test/media/services/relocation/card-export.jpg` → `/media/services/relocation/card-export.jpg` (owner: services)
4. **Listing card transport** — `https://waggies.test/media/services/relocation/card-transport.jpg` → `/media/services/relocation/card-transport.jpg` (owner: services)
5. **Training dog** — `https://waggies.test/media/services/training/training-dog.jpg` → `/media/services/training/training-dog.jpg` (owner: services)

### /services/relocation/import

1. **Hero** — `https://waggies.test/media/services/relocation/import/hero.jpg` → `/media/services/relocation/import/hero.jpg` (owner: services)

### /services/relocation/export

1. **Hero** — `https://waggies.test/media/services/relocation/export/hero.jpg` → `/media/services/relocation/export/hero.jpg` (owner: services)

### /services/relocation/transport

1. **Hero** — `https://waggies.test/media/services/relocation/transport/hero.jpg` → `/media/services/relocation/transport/hero.jpg` (owner: services)

### /services/relocation/checklist

1. **Hero** — `https://waggies.test/media/services/relocation/checklist/hero.jpg` → `/media/services/relocation/checklist/hero.jpg` (owner: services)

### /faq

1. **Hero** — `https://waggies.test/media/faq/hero.jpg` → `/media/faq/hero.jpg` (owner: faq)

### /privacy-policy

No raster image slot rendered.

### /terms-of-service

No raster image slot rendered.

### /cookies-policy

No raster image slot rendered.

### /contact

No raster image slot rendered.

### /book

No raster image slot rendered.

### /loyalty

No raster image slot rendered.

### /shop

1. **Listing card royal canin puppy** — `https://waggies.test/media/shop/card-royal-canin-puppy.jpg` → `/media/shop/card-royal-canin-puppy.jpg` (owner: shop)
2. **Listing card whiskas cat food** — `https://waggies.test/media/shop/card-whiskas-cat-food.jpg` → `/media/shop/card-whiskas-cat-food.jpg` (owner: shop)
3. **Listing card chew rope toy** — `https://waggies.test/media/shop/card-chew-rope-toy.jpg` → `/media/shop/card-chew-rope-toy.jpg` (owner: shop)
4. **Listing card interactive puzzle feeder** — `https://waggies.test/media/shop/card-interactive-puzzle-feeder.jpg` → `/media/shop/card-interactive-puzzle-feeder.jpg` (owner: shop)
5. **Listing card oatmeal shampoo** — `https://waggies.test/media/shop/card-oatmeal-shampoo.jpg` → `/media/shop/card-oatmeal-shampoo.jpg` (owner: shop)
6. **Listing card deshedding brush** — `https://waggies.test/media/shop/card-deshedding-brush.jpg` → `/media/shop/card-deshedding-brush.jpg` (owner: shop)
7. **Listing card tick flea collar** — `https://waggies.test/media/shop/card-tick-flea-collar.jpg` → `/media/shop/card-tick-flea-collar.jpg` (owner: shop)
8. **Listing card pet first aid kit** — `https://waggies.test/media/shop/card-pet-first-aid-kit.jpg` → `/media/shop/card-pet-first-aid-kit.jpg` (owner: shop)
9. **Listing card padded dog harness** — `https://waggies.test/media/shop/card-padded-dog-harness.jpg` → `/media/shop/card-padded-dog-harness.jpg` (owner: shop)
10. **Listing card raised pet bowl** — `https://waggies.test/media/shop/card-raised-pet-bowl.jpg` → `/media/shop/card-raised-pet-bowl.jpg` (owner: shop)

### /guides

1. **Listing card preparing pet boarding** — `https://waggies.test/media/guides/card-preparing-pet-boarding.jpg` → `/media/guides/card-preparing-pet-boarding.jpg` (owner: guides)
2. **Listing card what to expect boarding** — `https://waggies.test/media/guides/card-what-to-expect-boarding.jpg` → `/media/guides/card-what-to-expect-boarding.jpg` (owner: guides)
3. **Listing card grooming services explained** — `https://waggies.test/media/guides/card-grooming-services-explained.jpg` → `/media/guides/card-grooming-services-explained.jpg` (owner: guides)
4. **Listing card pet transport what to know** — `https://waggies.test/media/guides/card-pet-transport-what-to-know.jpg` → `/media/guides/card-pet-transport-what-to-know.jpg` (owner: guides)
5. **Listing card preparing pet boarding** — `https://waggies.test/media/guides/card-preparing-pet-boarding.jpg` → `/media/guides/card-preparing-pet-boarding.jpg` (owner: guides)
6. **Listing card what to expect boarding** — `https://waggies.test/media/guides/card-what-to-expect-boarding.jpg` → `/media/guides/card-what-to-expect-boarding.jpg` (owner: guides)
7. **Listing card grooming services explained** — `https://waggies.test/media/guides/card-grooming-services-explained.jpg` → `/media/guides/card-grooming-services-explained.jpg` (owner: guides)
8. **Listing card pet transport what to know** — `https://waggies.test/media/guides/card-pet-transport-what-to-know.jpg` → `/media/guides/card-pet-transport-what-to-know.jpg` (owner: guides)

### /knowledge-base

1. **Listing card understanding common dog illnesses** — `https://waggies.test/media/knowledge-base/card-understanding-common-dog-illnesses.jpg` → `/media/knowledge-base/card-understanding-common-dog-illnesses.jpg` (owner: knowledge-base)
2. **Listing card best nutrition tips nigerian pets** — `https://waggies.test/media/knowledge-base/card-best-nutrition-tips-nigerian-pets.jpg` → `/media/knowledge-base/card-best-nutrition-tips-nigerian-pets.jpg` (owner: knowledge-base)
3. **Listing card separation anxiety guide** — `https://waggies.test/media/knowledge-base/card-separation-anxiety-guide.jpg` → `/media/knowledge-base/card-separation-anxiety-guide.jpg` (owner: knowledge-base)
4. **Listing card grooming routine at home** — `https://waggies.test/media/knowledge-base/card-grooming-routine-at-home.jpg` → `/media/knowledge-base/card-grooming-routine-at-home.jpg` (owner: knowledge-base)
5. **Listing card cat care beginners nigeria** — `https://waggies.test/media/knowledge-base/card-cat-care-beginners-nigeria.jpg` → `/media/knowledge-base/card-cat-care-beginners-nigeria.jpg` (owner: knowledge-base)
6. **Listing card pet vaccination schedule guide** — `https://waggies.test/media/knowledge-base/card-pet-vaccination-schedule-guide.jpg` → `/media/knowledge-base/card-pet-vaccination-schedule-guide.jpg` (owner: knowledge-base)
7. **Listing card new pet checklist** — `https://waggies.test/media/knowledge-base/card-new-pet-checklist.jpg` → `/media/knowledge-base/card-new-pet-checklist.jpg` (owner: knowledge-base)
8. **Listing card basic dog training guide** — `https://waggies.test/media/knowledge-base/card-basic-dog-training-guide.jpg` → `/media/knowledge-base/card-basic-dog-training-guide.jpg` (owner: knowledge-base)
9. **Listing card seasonal pet care nigeria** — `https://waggies.test/media/knowledge-base/card-seasonal-pet-care-nigeria.jpg` → `/media/knowledge-base/card-seasonal-pet-care-nigeria.jpg` (owner: knowledge-base)
10. **Listing card when to take pet vet** — `https://waggies.test/media/knowledge-base/card-when-to-take-pet-vet.jpg` → `/media/knowledge-base/card-when-to-take-pet-vet.jpg` (owner: knowledge-base)

### /tools

No raster image slot rendered.

### /tools/symptom-checker

No raster image slot rendered.

### /tools/vaccination-schedule

No raster image slot rendered.

### /tools/parasite-schedule

No raster image slot rendered.

### /tools/emergency-guide

No raster image slot rendered.

### /tools/medication-dosage-guide

No raster image slot rendered.

### /tools/pet-age-calculator

No raster image slot rendered.

### /tools/cost-calculator

No raster image slot rendered.

### /tools/nutrition-calculator

No raster image slot rendered.

### /tools/breed-finder

No raster image slot rendered.

### /tools/behavior-tips

No raster image slot rendered.

### /tools/new-pet-checklist

No raster image slot rendered.

### /guides/what-to-expect-boarding

1. **Cover** — `https://waggies.test/media/guides/what-to-expect-boarding/cover.jpg` → `/media/guides/what-to-expect-boarding/cover.jpg` (owner: guides)

### /guides/pet-transport-what-to-know

1. **Cover** — `https://waggies.test/media/guides/pet-transport-what-to-know/cover.jpg` → `/media/guides/pet-transport-what-to-know/cover.jpg` (owner: guides)

### /guides/preparing-pet-boarding

1. **Cover** — `https://waggies.test/media/guides/preparing-pet-boarding/cover.jpg` → `/media/guides/preparing-pet-boarding/cover.jpg` (owner: guides)

### /guides/grooming-services-explained

1. **Cover** — `https://waggies.test/media/guides/grooming-services-explained/cover.jpg` → `/media/guides/grooming-services-explained/cover.jpg` (owner: guides)

### /knowledge-base/cat-care-beginners-nigeria

1. **Cover** — `https://waggies.test/media/knowledge-base/cat-care-beginners-nigeria/cover.jpg` → `/media/knowledge-base/cat-care-beginners-nigeria/cover.jpg` (owner: knowledge-base)
2. **Related grooming routine at home** — `https://waggies.test/media/knowledge-base/cat-care-beginners-nigeria/related-grooming-routine-at-home.jpg` → `/media/knowledge-base/cat-care-beginners-nigeria/related-grooming-routine-at-home.jpg` (owner: knowledge-base)

### /knowledge-base/pet-vaccination-schedule-guide

1. **Cover** — `https://waggies.test/media/knowledge-base/pet-vaccination-schedule-guide/cover.jpg` → `/media/knowledge-base/pet-vaccination-schedule-guide/cover.jpg` (owner: knowledge-base)
2. **Related understanding common dog illnesses** — `https://waggies.test/media/knowledge-base/pet-vaccination-schedule-guide/related-understanding-common-dog-illnesses.jpg` → `/media/knowledge-base/pet-vaccination-schedule-guide/related-understanding-common-dog-illnesses.jpg` (owner: knowledge-base)
3. **Related when to take pet vet** — `https://waggies.test/media/knowledge-base/pet-vaccination-schedule-guide/related-when-to-take-pet-vet.jpg` → `/media/knowledge-base/pet-vaccination-schedule-guide/related-when-to-take-pet-vet.jpg` (owner: knowledge-base)

### /knowledge-base/basic-dog-training-guide

1. **Cover** — `https://waggies.test/media/knowledge-base/basic-dog-training-guide/cover.jpg` → `/media/knowledge-base/basic-dog-training-guide/cover.jpg` (owner: knowledge-base)

### /knowledge-base/understanding-common-dog-illnesses

1. **Cover** — `https://waggies.test/media/knowledge-base/understanding-common-dog-illnesses/cover.jpg` → `/media/knowledge-base/understanding-common-dog-illnesses/cover.jpg` (owner: knowledge-base)
2. **Related pet vaccination schedule guide** — `https://waggies.test/media/knowledge-base/understanding-common-dog-illnesses/related-pet-vaccination-schedule-guide.jpg` → `/media/knowledge-base/understanding-common-dog-illnesses/related-pet-vaccination-schedule-guide.jpg` (owner: knowledge-base)
3. **Related when to take pet vet** — `https://waggies.test/media/knowledge-base/understanding-common-dog-illnesses/related-when-to-take-pet-vet.jpg` → `/media/knowledge-base/understanding-common-dog-illnesses/related-when-to-take-pet-vet.jpg` (owner: knowledge-base)

### /knowledge-base/best-nutrition-tips-nigerian-pets

1. **Cover** — `https://waggies.test/media/knowledge-base/best-nutrition-tips-nigerian-pets/cover.jpg` → `/media/knowledge-base/best-nutrition-tips-nigerian-pets/cover.jpg` (owner: knowledge-base)

### /knowledge-base/separation-anxiety-guide

1. **Cover** — `https://waggies.test/media/knowledge-base/separation-anxiety-guide/cover.jpg` → `/media/knowledge-base/separation-anxiety-guide/cover.jpg` (owner: knowledge-base)

### /knowledge-base/grooming-routine-at-home

1. **Cover** — `https://waggies.test/media/knowledge-base/grooming-routine-at-home/cover.jpg` → `/media/knowledge-base/grooming-routine-at-home/cover.jpg` (owner: knowledge-base)
2. **Related cat care beginners nigeria** — `https://waggies.test/media/knowledge-base/grooming-routine-at-home/related-cat-care-beginners-nigeria.jpg` → `/media/knowledge-base/grooming-routine-at-home/related-cat-care-beginners-nigeria.jpg` (owner: knowledge-base)

### /knowledge-base/new-pet-checklist

1. **Cover** — `https://waggies.test/media/knowledge-base/new-pet-checklist/cover.jpg` → `/media/knowledge-base/new-pet-checklist/cover.jpg` (owner: knowledge-base)

### /knowledge-base/when-to-take-pet-vet

1. **Cover** — `https://waggies.test/media/knowledge-base/when-to-take-pet-vet/cover.jpg` → `/media/knowledge-base/when-to-take-pet-vet/cover.jpg` (owner: knowledge-base)
2. **Related understanding common dog illnesses** — `https://waggies.test/media/knowledge-base/when-to-take-pet-vet/related-understanding-common-dog-illnesses.jpg` → `/media/knowledge-base/when-to-take-pet-vet/related-understanding-common-dog-illnesses.jpg` (owner: knowledge-base)
3. **Related pet vaccination schedule guide** — `https://waggies.test/media/knowledge-base/when-to-take-pet-vet/related-pet-vaccination-schedule-guide.jpg` → `/media/knowledge-base/when-to-take-pet-vet/related-pet-vaccination-schedule-guide.jpg` (owner: knowledge-base)

### /knowledge-base/seasonal-pet-care-nigeria

1. **Cover** — `https://waggies.test/media/knowledge-base/seasonal-pet-care-nigeria/cover.jpg` → `/media/knowledge-base/seasonal-pet-care-nigeria/cover.jpg` (owner: knowledge-base)

### /shop/royal-canin-puppy

1. **Hero** — `https://waggies.test/media/shop/royal-canin-puppy/hero.jpg` → `/media/shop/royal-canin-puppy/hero.jpg` (owner: shop)
2. **Related whiskas cat food** — `https://waggies.test/media/shop/royal-canin-puppy/related-whiskas-cat-food.jpg` → `/media/shop/royal-canin-puppy/related-whiskas-cat-food.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/royal-canin-puppy/recent-royal-canin-puppy.jpg` → `/media/shop/royal-canin-puppy/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/royal-canin-puppy/recent-whiskas-cat-food.jpg` → `/media/shop/royal-canin-puppy/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/royal-canin-puppy/recent-chew-rope-toy.jpg` → `/media/shop/royal-canin-puppy/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/royal-canin-puppy/recent-interactive-puzzle-feeder.jpg` → `/media/shop/royal-canin-puppy/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/royal-canin-puppy/recent-oatmeal-shampoo.jpg` → `/media/shop/royal-canin-puppy/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/royal-canin-puppy/recent-deshedding-brush.jpg` → `/media/shop/royal-canin-puppy/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/royal-canin-puppy/recent-tick-flea-collar.jpg` → `/media/shop/royal-canin-puppy/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/royal-canin-puppy/recent-pet-first-aid-kit.jpg` → `/media/shop/royal-canin-puppy/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/royal-canin-puppy/recent-padded-dog-harness.jpg` → `/media/shop/royal-canin-puppy/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/royal-canin-puppy/recent-raised-pet-bowl.jpg` → `/media/shop/royal-canin-puppy/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/whiskas-cat-food

1. **Hero** — `https://waggies.test/media/shop/whiskas-cat-food/hero.jpg` → `/media/shop/whiskas-cat-food/hero.jpg` (owner: shop)
2. **Related royal canin puppy** — `https://waggies.test/media/shop/whiskas-cat-food/related-royal-canin-puppy.jpg` → `/media/shop/whiskas-cat-food/related-royal-canin-puppy.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/whiskas-cat-food/recent-royal-canin-puppy.jpg` → `/media/shop/whiskas-cat-food/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/whiskas-cat-food/recent-whiskas-cat-food.jpg` → `/media/shop/whiskas-cat-food/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/whiskas-cat-food/recent-chew-rope-toy.jpg` → `/media/shop/whiskas-cat-food/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/whiskas-cat-food/recent-interactive-puzzle-feeder.jpg` → `/media/shop/whiskas-cat-food/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/whiskas-cat-food/recent-oatmeal-shampoo.jpg` → `/media/shop/whiskas-cat-food/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/whiskas-cat-food/recent-deshedding-brush.jpg` → `/media/shop/whiskas-cat-food/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/whiskas-cat-food/recent-tick-flea-collar.jpg` → `/media/shop/whiskas-cat-food/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/whiskas-cat-food/recent-pet-first-aid-kit.jpg` → `/media/shop/whiskas-cat-food/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/whiskas-cat-food/recent-padded-dog-harness.jpg` → `/media/shop/whiskas-cat-food/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/whiskas-cat-food/recent-raised-pet-bowl.jpg` → `/media/shop/whiskas-cat-food/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/chew-rope-toy

1. **Hero** — `https://waggies.test/media/shop/chew-rope-toy/hero.jpg` → `/media/shop/chew-rope-toy/hero.jpg` (owner: shop)
2. **Related interactive puzzle feeder** — `https://waggies.test/media/shop/chew-rope-toy/related-interactive-puzzle-feeder.jpg` → `/media/shop/chew-rope-toy/related-interactive-puzzle-feeder.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/chew-rope-toy/recent-royal-canin-puppy.jpg` → `/media/shop/chew-rope-toy/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/chew-rope-toy/recent-whiskas-cat-food.jpg` → `/media/shop/chew-rope-toy/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/chew-rope-toy/recent-chew-rope-toy.jpg` → `/media/shop/chew-rope-toy/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/chew-rope-toy/recent-interactive-puzzle-feeder.jpg` → `/media/shop/chew-rope-toy/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/chew-rope-toy/recent-oatmeal-shampoo.jpg` → `/media/shop/chew-rope-toy/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/chew-rope-toy/recent-deshedding-brush.jpg` → `/media/shop/chew-rope-toy/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/chew-rope-toy/recent-tick-flea-collar.jpg` → `/media/shop/chew-rope-toy/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/chew-rope-toy/recent-pet-first-aid-kit.jpg` → `/media/shop/chew-rope-toy/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/chew-rope-toy/recent-padded-dog-harness.jpg` → `/media/shop/chew-rope-toy/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/chew-rope-toy/recent-raised-pet-bowl.jpg` → `/media/shop/chew-rope-toy/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/interactive-puzzle-feeder

1. **Hero** — `https://waggies.test/media/shop/interactive-puzzle-feeder/hero.jpg` → `/media/shop/interactive-puzzle-feeder/hero.jpg` (owner: shop)
2. **Related chew rope toy** — `https://waggies.test/media/shop/interactive-puzzle-feeder/related-chew-rope-toy.jpg` → `/media/shop/interactive-puzzle-feeder/related-chew-rope-toy.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-royal-canin-puppy.jpg` → `/media/shop/interactive-puzzle-feeder/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-whiskas-cat-food.jpg` → `/media/shop/interactive-puzzle-feeder/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-chew-rope-toy.jpg` → `/media/shop/interactive-puzzle-feeder/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-interactive-puzzle-feeder.jpg` → `/media/shop/interactive-puzzle-feeder/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-oatmeal-shampoo.jpg` → `/media/shop/interactive-puzzle-feeder/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-deshedding-brush.jpg` → `/media/shop/interactive-puzzle-feeder/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-tick-flea-collar.jpg` → `/media/shop/interactive-puzzle-feeder/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-pet-first-aid-kit.jpg` → `/media/shop/interactive-puzzle-feeder/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-padded-dog-harness.jpg` → `/media/shop/interactive-puzzle-feeder/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/interactive-puzzle-feeder/recent-raised-pet-bowl.jpg` → `/media/shop/interactive-puzzle-feeder/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/oatmeal-shampoo

1. **Hero** — `https://waggies.test/media/shop/oatmeal-shampoo/hero.jpg` → `/media/shop/oatmeal-shampoo/hero.jpg` (owner: shop)
2. **Related deshedding brush** — `https://waggies.test/media/shop/oatmeal-shampoo/related-deshedding-brush.jpg` → `/media/shop/oatmeal-shampoo/related-deshedding-brush.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-royal-canin-puppy.jpg` → `/media/shop/oatmeal-shampoo/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-whiskas-cat-food.jpg` → `/media/shop/oatmeal-shampoo/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-chew-rope-toy.jpg` → `/media/shop/oatmeal-shampoo/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-interactive-puzzle-feeder.jpg` → `/media/shop/oatmeal-shampoo/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-oatmeal-shampoo.jpg` → `/media/shop/oatmeal-shampoo/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-deshedding-brush.jpg` → `/media/shop/oatmeal-shampoo/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-tick-flea-collar.jpg` → `/media/shop/oatmeal-shampoo/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-pet-first-aid-kit.jpg` → `/media/shop/oatmeal-shampoo/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-padded-dog-harness.jpg` → `/media/shop/oatmeal-shampoo/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/oatmeal-shampoo/recent-raised-pet-bowl.jpg` → `/media/shop/oatmeal-shampoo/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/deshedding-brush

1. **Hero** — `https://waggies.test/media/shop/deshedding-brush/hero.jpg` → `/media/shop/deshedding-brush/hero.jpg` (owner: shop)
2. **Related oatmeal shampoo** — `https://waggies.test/media/shop/deshedding-brush/related-oatmeal-shampoo.jpg` → `/media/shop/deshedding-brush/related-oatmeal-shampoo.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/deshedding-brush/recent-royal-canin-puppy.jpg` → `/media/shop/deshedding-brush/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/deshedding-brush/recent-whiskas-cat-food.jpg` → `/media/shop/deshedding-brush/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/deshedding-brush/recent-chew-rope-toy.jpg` → `/media/shop/deshedding-brush/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/deshedding-brush/recent-interactive-puzzle-feeder.jpg` → `/media/shop/deshedding-brush/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/deshedding-brush/recent-oatmeal-shampoo.jpg` → `/media/shop/deshedding-brush/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/deshedding-brush/recent-deshedding-brush.jpg` → `/media/shop/deshedding-brush/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/deshedding-brush/recent-tick-flea-collar.jpg` → `/media/shop/deshedding-brush/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/deshedding-brush/recent-pet-first-aid-kit.jpg` → `/media/shop/deshedding-brush/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/deshedding-brush/recent-padded-dog-harness.jpg` → `/media/shop/deshedding-brush/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/deshedding-brush/recent-raised-pet-bowl.jpg` → `/media/shop/deshedding-brush/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/tick-flea-collar

1. **Hero** — `https://waggies.test/media/shop/tick-flea-collar/hero.jpg` → `/media/shop/tick-flea-collar/hero.jpg` (owner: shop)
2. **Related pet first aid kit** — `https://waggies.test/media/shop/tick-flea-collar/related-pet-first-aid-kit.jpg` → `/media/shop/tick-flea-collar/related-pet-first-aid-kit.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/tick-flea-collar/recent-royal-canin-puppy.jpg` → `/media/shop/tick-flea-collar/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/tick-flea-collar/recent-whiskas-cat-food.jpg` → `/media/shop/tick-flea-collar/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/tick-flea-collar/recent-chew-rope-toy.jpg` → `/media/shop/tick-flea-collar/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/tick-flea-collar/recent-interactive-puzzle-feeder.jpg` → `/media/shop/tick-flea-collar/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/tick-flea-collar/recent-oatmeal-shampoo.jpg` → `/media/shop/tick-flea-collar/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/tick-flea-collar/recent-deshedding-brush.jpg` → `/media/shop/tick-flea-collar/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/tick-flea-collar/recent-tick-flea-collar.jpg` → `/media/shop/tick-flea-collar/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/tick-flea-collar/recent-pet-first-aid-kit.jpg` → `/media/shop/tick-flea-collar/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/tick-flea-collar/recent-padded-dog-harness.jpg` → `/media/shop/tick-flea-collar/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/tick-flea-collar/recent-raised-pet-bowl.jpg` → `/media/shop/tick-flea-collar/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/pet-first-aid-kit

1. **Hero** — `https://waggies.test/media/shop/pet-first-aid-kit/hero.jpg` → `/media/shop/pet-first-aid-kit/hero.jpg` (owner: shop)
2. **Related tick flea collar** — `https://waggies.test/media/shop/pet-first-aid-kit/related-tick-flea-collar.jpg` → `/media/shop/pet-first-aid-kit/related-tick-flea-collar.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-royal-canin-puppy.jpg` → `/media/shop/pet-first-aid-kit/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-whiskas-cat-food.jpg` → `/media/shop/pet-first-aid-kit/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-chew-rope-toy.jpg` → `/media/shop/pet-first-aid-kit/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-interactive-puzzle-feeder.jpg` → `/media/shop/pet-first-aid-kit/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-oatmeal-shampoo.jpg` → `/media/shop/pet-first-aid-kit/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-deshedding-brush.jpg` → `/media/shop/pet-first-aid-kit/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-tick-flea-collar.jpg` → `/media/shop/pet-first-aid-kit/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-pet-first-aid-kit.jpg` → `/media/shop/pet-first-aid-kit/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-padded-dog-harness.jpg` → `/media/shop/pet-first-aid-kit/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/pet-first-aid-kit/recent-raised-pet-bowl.jpg` → `/media/shop/pet-first-aid-kit/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/padded-dog-harness

1. **Hero** — `https://waggies.test/media/shop/padded-dog-harness/hero.jpg` → `/media/shop/padded-dog-harness/hero.jpg` (owner: shop)
2. **Related raised pet bowl** — `https://waggies.test/media/shop/padded-dog-harness/related-raised-pet-bowl.jpg` → `/media/shop/padded-dog-harness/related-raised-pet-bowl.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/padded-dog-harness/recent-royal-canin-puppy.jpg` → `/media/shop/padded-dog-harness/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/padded-dog-harness/recent-whiskas-cat-food.jpg` → `/media/shop/padded-dog-harness/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/padded-dog-harness/recent-chew-rope-toy.jpg` → `/media/shop/padded-dog-harness/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/padded-dog-harness/recent-interactive-puzzle-feeder.jpg` → `/media/shop/padded-dog-harness/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/padded-dog-harness/recent-oatmeal-shampoo.jpg` → `/media/shop/padded-dog-harness/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/padded-dog-harness/recent-deshedding-brush.jpg` → `/media/shop/padded-dog-harness/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/padded-dog-harness/recent-tick-flea-collar.jpg` → `/media/shop/padded-dog-harness/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/padded-dog-harness/recent-pet-first-aid-kit.jpg` → `/media/shop/padded-dog-harness/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/padded-dog-harness/recent-padded-dog-harness.jpg` → `/media/shop/padded-dog-harness/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/padded-dog-harness/recent-raised-pet-bowl.jpg` → `/media/shop/padded-dog-harness/recent-raised-pet-bowl.jpg` (owner: shop)

### /shop/raised-pet-bowl

1. **Hero** — `https://waggies.test/media/shop/raised-pet-bowl/hero.jpg` → `/media/shop/raised-pet-bowl/hero.jpg` (owner: shop)
2. **Related padded dog harness** — `https://waggies.test/media/shop/raised-pet-bowl/related-padded-dog-harness.jpg` → `/media/shop/raised-pet-bowl/related-padded-dog-harness.jpg` (owner: shop)
3. **Recently viewed royal canin puppy** — `https://waggies.test/media/shop/raised-pet-bowl/recent-royal-canin-puppy.jpg` → `/media/shop/raised-pet-bowl/recent-royal-canin-puppy.jpg` (owner: shop)
4. **Recently viewed whiskas cat food** — `https://waggies.test/media/shop/raised-pet-bowl/recent-whiskas-cat-food.jpg` → `/media/shop/raised-pet-bowl/recent-whiskas-cat-food.jpg` (owner: shop)
5. **Recently viewed chew rope toy** — `https://waggies.test/media/shop/raised-pet-bowl/recent-chew-rope-toy.jpg` → `/media/shop/raised-pet-bowl/recent-chew-rope-toy.jpg` (owner: shop)
6. **Recently viewed interactive puzzle feeder** — `https://waggies.test/media/shop/raised-pet-bowl/recent-interactive-puzzle-feeder.jpg` → `/media/shop/raised-pet-bowl/recent-interactive-puzzle-feeder.jpg` (owner: shop)
7. **Recently viewed oatmeal shampoo** — `https://waggies.test/media/shop/raised-pet-bowl/recent-oatmeal-shampoo.jpg` → `/media/shop/raised-pet-bowl/recent-oatmeal-shampoo.jpg` (owner: shop)
8. **Recently viewed deshedding brush** — `https://waggies.test/media/shop/raised-pet-bowl/recent-deshedding-brush.jpg` → `/media/shop/raised-pet-bowl/recent-deshedding-brush.jpg` (owner: shop)
9. **Recently viewed tick flea collar** — `https://waggies.test/media/shop/raised-pet-bowl/recent-tick-flea-collar.jpg` → `/media/shop/raised-pet-bowl/recent-tick-flea-collar.jpg` (owner: shop)
10. **Recently viewed pet first aid kit** — `https://waggies.test/media/shop/raised-pet-bowl/recent-pet-first-aid-kit.jpg` → `/media/shop/raised-pet-bowl/recent-pet-first-aid-kit.jpg` (owner: shop)
11. **Recently viewed padded dog harness** — `https://waggies.test/media/shop/raised-pet-bowl/recent-padded-dog-harness.jpg` → `/media/shop/raised-pet-bowl/recent-padded-dog-harness.jpg` (owner: shop)
12. **Recently viewed raised pet bowl** — `https://waggies.test/media/shop/raised-pet-bowl/recent-raised-pet-bowl.jpg` → `/media/shop/raised-pet-bowl/recent-raised-pet-bowl.jpg` (owner: shop)

## Complete occurrence ledger

| URL | Section | Function | Current URL | Physical file | Source | Intended purpose | Owner | Proposed final path | Placeholder collision? | Intentional shared? | Duplicate required? | Action |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| / | home | Hero | https://waggies.test/media/home/hero.jpg | public\media\home\hero.jpg | Controller/config/view | Hero | home | /media/home/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| / | home | Home service card boarding | https://waggies.test/media/home/services-boarding.jpg | public\media\home\services-boarding.jpg | Controller/config/view | Home service card boarding | home | /media/home/services-boarding.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| / | home | Home service card grooming | https://waggies.test/media/home/services-grooming.jpg | public\media\home\services-grooming.jpg | Controller/config/view | Home service card grooming | home | /media/home/services-grooming.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| / | home | Home service card vet care | https://waggies.test/media/home/services-vet-care.jpg | public\media\home\services-vet-care.jpg | Controller/config/view | Home service card vet care | home | /media/home/services-vet-care.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| / | home | Home service card training | https://waggies.test/media/home/services-training.jpg | public\media\home\services-training.jpg | Controller/config/view | Home service card training | home | /media/home/services-training.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| / | home | Home service card relocation | https://waggies.test/media/home/services-relocation.jpg | public\media\home\services-relocation.jpg | Controller/config/view | Home service card relocation | home | /media/home/services-relocation.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| / | home | Care standards | https://waggies.test/media/home/care-standards.jpg | public\media\home\care-standards.jpg | Controller/config/view | Care standards | home | /media/home/care-standards.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about | about | Intro | https://waggies.test/media/about/intro.jpg | public\media\about\intro.jpg | Controller/config/view | Intro | about | /media/about/intro.jpg | No — page/domain path is explicit | No | No | Keep canonical page-owned asset |
| /about | about | Veterinary care | https://waggies.test/media/about/veterinary-care.jpg | public\media\about\veterinary-care.jpg | Controller/config/view | Veterinary care | about | /media/about/veterinary-care.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about | about | Grooming | https://waggies.test/media/about/grooming.jpg | public\media\about\grooming.jpg | Controller/config/view | Grooming | about | /media/about/grooming.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/testimonials | about | Hero | https://waggies.test/media/about/testimonials/hero.jpg | public\media\about\testimonials\hero.jpg | Controller/config/view | Hero | about | /media/about/testimonials/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/careers | about | Team | https://waggies.test/media/about/careers/team.jpg | public\media\about\careers\team.jpg | Controller/config/view | Team | about | /media/about/careers/team.jpg | No — page/domain path is explicit | No | No | Keep canonical page-owned asset |
| /services | services | Hero | https://waggies.test/media/services/hero.jpg | public\media\services\hero.jpg | Controller/config/view | Hero | services | /media/services/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services | services | Listing card dogs | https://waggies.test/media/services/boarding/card-dogs.jpg | public\media\services\boarding\card-dogs.jpg | Controller/config/view | Listing card dogs | services | /media/services/boarding/card-dogs.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services | services | Hero | https://waggies.test/media/services/grooming/hero.jpg | public\media\services\grooming\hero.jpg | Controller/config/view | Hero | services | /media/services/grooming/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services | services | Hero | https://waggies.test/media/services/vet-care/hero.jpg | public\media\services\vet-care\hero.jpg | Controller/config/view | Hero | services | /media/services/vet-care/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services | services | Hero | https://waggies.test/media/services/training/hero.jpg | public\media\services\training\hero.jpg | Controller/config/view | Hero | services | /media/services/training/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services | services | Hero | https://waggies.test/media/services/relocation/hero.jpg | public\media\services\relocation\hero.jpg | Controller/config/view | Hero | services | /media/services/relocation/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding | services | Hero | https://waggies.test/media/services/boarding/hero.jpg | public\media\services\boarding\hero.jpg | Controller/config/view | Hero | services | /media/services/boarding/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding | services | Listing card dogs | https://waggies.test/media/services/boarding/card-dogs.jpg | public\media\services\boarding\card-dogs.jpg | Controller/config/view | Listing card dogs | services | /media/services/boarding/card-dogs.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding | services | Listing card cats | https://waggies.test/media/services/boarding/card-cats.jpg | public\media\services\boarding\card-cats.jpg | Controller/config/view | Listing card cats | services | /media/services/boarding/card-cats.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding | services | Listing card exotic | https://waggies.test/media/services/boarding/card-exotic.jpg | public\media\services\boarding\card-exotic.jpg | Controller/config/view | Listing card exotic | services | /media/services/boarding/card-exotic.jpg | No — page/domain path is explicit | No | No | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Hero | https://waggies.test/media/services/boarding/dogs/hero.jpg | public\media\services\boarding\dogs\hero.jpg | Controller/config/view | Hero | services | /media/services/boarding/dogs/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Daily-life gallery 01 | https://waggies.test/media/services/boarding/dogs/daily-01.jpg | public\media\services\boarding\dogs\daily-01.jpg | Controller/config/view | Daily-life gallery 01 | services | /media/services/boarding/dogs/daily-01.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Daily-life gallery 02 | https://waggies.test/media/services/boarding/dogs/daily-02.jpg | public\media\services\boarding\dogs\daily-02.jpg | Controller/config/view | Daily-life gallery 02 | services | /media/services/boarding/dogs/daily-02.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Daily-life gallery 03 | https://waggies.test/media/services/boarding/dogs/daily-03.jpg | public\media\services\boarding\dogs\daily-03.jpg | Controller/config/view | Daily-life gallery 03 | services | /media/services/boarding/dogs/daily-03.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Daily-life gallery 04 | https://waggies.test/media/services/boarding/dogs/daily-04.jpg | public\media\services\boarding\dogs\daily-04.jpg | Controller/config/view | Daily-life gallery 04 | services | /media/services/boarding/dogs/daily-04.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Related cats | https://waggies.test/media/services/boarding/dogs/related-cats.jpg | public\media\services\boarding\dogs\related-cats.jpg | Controller/config/view | Related cats | services | /media/services/boarding/dogs/related-cats.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/dogs | services | Related exotic | https://waggies.test/media/services/boarding/dogs/related-exotic.jpg | public\media\services\boarding\dogs\related-exotic.jpg | Controller/config/view | Related exotic | services | /media/services/boarding/dogs/related-exotic.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Hero | https://waggies.test/media/services/boarding/cats/hero.jpg | public\media\services\boarding\cats\hero.jpg | Controller/config/view | Hero | services | /media/services/boarding/cats/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Feline feature | https://waggies.test/media/services/boarding/cats/feline.jpg | public\media\services\boarding\cats\feline.jpg | Controller/config/view | Feline feature | services | /media/services/boarding/cats/feline.jpg | No — page/domain path is explicit | No | No | Keep canonical page-owned asset |
| /services/boarding/cats | services | Daily-life gallery 01 | https://waggies.test/media/services/boarding/cats/daily-01.jpg | public\media\services\boarding\cats\daily-01.jpg | Controller/config/view | Daily-life gallery 01 | services | /media/services/boarding/cats/daily-01.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Daily-life gallery 02 | https://waggies.test/media/services/boarding/cats/daily-02.jpg | public\media\services\boarding\cats\daily-02.jpg | Controller/config/view | Daily-life gallery 02 | services | /media/services/boarding/cats/daily-02.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Daily-life gallery 03 | https://waggies.test/media/services/boarding/cats/daily-03.jpg | public\media\services\boarding\cats\daily-03.jpg | Controller/config/view | Daily-life gallery 03 | services | /media/services/boarding/cats/daily-03.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Daily-life gallery 04 | https://waggies.test/media/services/boarding/cats/daily-04.jpg | public\media\services\boarding\cats\daily-04.jpg | Controller/config/view | Daily-life gallery 04 | services | /media/services/boarding/cats/daily-04.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Related dogs | https://waggies.test/media/services/boarding/cats/related-dogs.jpg | public\media\services\boarding\cats\related-dogs.jpg | Controller/config/view | Related dogs | services | /media/services/boarding/cats/related-dogs.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/cats | services | Related exotic | https://waggies.test/media/services/boarding/cats/related-exotic.jpg | public\media\services\boarding\cats\related-exotic.jpg | Controller/config/view | Related exotic | services | /media/services/boarding/cats/related-exotic.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/exotic | services | Hero | https://waggies.test/media/services/boarding/exotic/hero.jpg | public\media\services\boarding\exotic\hero.jpg | Controller/config/view | Hero | services | /media/services/boarding/exotic/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/exotic | services | Related dogs | https://waggies.test/media/services/boarding/exotic/related-dogs.jpg | public\media\services\boarding\exotic\related-dogs.jpg | Controller/config/view | Related dogs | services | /media/services/boarding/exotic/related-dogs.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/boarding/exotic | services | Related cats | https://waggies.test/media/services/boarding/exotic/related-cats.jpg | public\media\services\boarding\exotic\related-cats.jpg | Controller/config/view | Related cats | services | /media/services/boarding/exotic/related-cats.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/grooming | services | Hero | https://waggies.test/media/services/grooming/hero.jpg | public\media\services\grooming\hero.jpg | Controller/config/view | Hero | services | /media/services/grooming/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/training | services | Hero | https://waggies.test/media/services/training/hero.jpg | public\media\services\training\hero.jpg | Controller/config/view | Hero | services | /media/services/training/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/vet-care | services | Hero | https://waggies.test/media/services/vet-care/hero.jpg | public\media\services\vet-care\hero.jpg | Controller/config/view | Hero | services | /media/services/vet-care/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation | services | Hero | https://waggies.test/media/services/relocation/hero.jpg | public\media\services\relocation\hero.jpg | Controller/config/view | Hero | services | /media/services/relocation/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation | services | Listing card import | https://waggies.test/media/services/relocation/card-import.jpg | public\media\services\relocation\card-import.jpg | Controller/config/view | Listing card import | services | /media/services/relocation/card-import.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation | services | Listing card export | https://waggies.test/media/services/relocation/card-export.jpg | public\media\services\relocation\card-export.jpg | Controller/config/view | Listing card export | services | /media/services/relocation/card-export.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation | services | Listing card transport | https://waggies.test/media/services/relocation/card-transport.jpg | public\media\services\relocation\card-transport.jpg | Controller/config/view | Listing card transport | services | /media/services/relocation/card-transport.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation | services | Training dog | https://waggies.test/media/services/training/training-dog.jpg | public\media\services\training\training-dog.jpg | Controller/config/view | Training dog | services | /media/services/training/training-dog.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation/import | services | Hero | https://waggies.test/media/services/relocation/import/hero.jpg | public\media\services\relocation\import\hero.jpg | Controller/config/view | Hero | services | /media/services/relocation/import/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation/export | services | Hero | https://waggies.test/media/services/relocation/export/hero.jpg | public\media\services\relocation\export\hero.jpg | Controller/config/view | Hero | services | /media/services/relocation/export/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation/transport | services | Hero | https://waggies.test/media/services/relocation/transport/hero.jpg | public\media\services\relocation\transport\hero.jpg | Controller/config/view | Hero | services | /media/services/relocation/transport/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /services/relocation/checklist | services | Hero | https://waggies.test/media/services/relocation/checklist/hero.jpg | public\media\services\relocation\checklist\hero.jpg | Controller/config/view | Hero | services | /media/services/relocation/checklist/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /faq | faq | Hero | https://waggies.test/media/faq/hero.jpg | public\media\faq\hero.jpg | Rendered shared component | Hero | faq | /media/faq/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card royal canin puppy | https://waggies.test/media/shop/card-royal-canin-puppy.jpg | public\media\shop\card-royal-canin-puppy.jpg | Controller + current database record | Listing card royal canin puppy | shop | /media/shop/card-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card whiskas cat food | https://waggies.test/media/shop/card-whiskas-cat-food.jpg | public\media\shop\card-whiskas-cat-food.jpg | Controller + current database record | Listing card whiskas cat food | shop | /media/shop/card-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card chew rope toy | https://waggies.test/media/shop/card-chew-rope-toy.jpg | public\media\shop\card-chew-rope-toy.jpg | Controller + current database record | Listing card chew rope toy | shop | /media/shop/card-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card interactive puzzle feeder | https://waggies.test/media/shop/card-interactive-puzzle-feeder.jpg | public\media\shop\card-interactive-puzzle-feeder.jpg | Controller + current database record | Listing card interactive puzzle feeder | shop | /media/shop/card-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card oatmeal shampoo | https://waggies.test/media/shop/card-oatmeal-shampoo.jpg | public\media\shop\card-oatmeal-shampoo.jpg | Controller + current database record | Listing card oatmeal shampoo | shop | /media/shop/card-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card deshedding brush | https://waggies.test/media/shop/card-deshedding-brush.jpg | public\media\shop\card-deshedding-brush.jpg | Controller + current database record | Listing card deshedding brush | shop | /media/shop/card-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card tick flea collar | https://waggies.test/media/shop/card-tick-flea-collar.jpg | public\media\shop\card-tick-flea-collar.jpg | Controller + current database record | Listing card tick flea collar | shop | /media/shop/card-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card pet first aid kit | https://waggies.test/media/shop/card-pet-first-aid-kit.jpg | public\media\shop\card-pet-first-aid-kit.jpg | Controller + current database record | Listing card pet first aid kit | shop | /media/shop/card-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card padded dog harness | https://waggies.test/media/shop/card-padded-dog-harness.jpg | public\media\shop\card-padded-dog-harness.jpg | Controller + current database record | Listing card padded dog harness | shop | /media/shop/card-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop | shop | Listing card raised pet bowl | https://waggies.test/media/shop/card-raised-pet-bowl.jpg | public\media\shop\card-raised-pet-bowl.jpg | Controller + current database record | Listing card raised pet bowl | shop | /media/shop/card-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card preparing pet boarding | https://waggies.test/media/guides/card-preparing-pet-boarding.jpg | public\media\guides\card-preparing-pet-boarding.jpg | Controller + current database record | Listing card preparing pet boarding | guides | /media/guides/card-preparing-pet-boarding.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card what to expect boarding | https://waggies.test/media/guides/card-what-to-expect-boarding.jpg | public\media\guides\card-what-to-expect-boarding.jpg | Controller + current database record | Listing card what to expect boarding | guides | /media/guides/card-what-to-expect-boarding.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card grooming services explained | https://waggies.test/media/guides/card-grooming-services-explained.jpg | public\media\guides\card-grooming-services-explained.jpg | Controller + current database record | Listing card grooming services explained | guides | /media/guides/card-grooming-services-explained.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card pet transport what to know | https://waggies.test/media/guides/card-pet-transport-what-to-know.jpg | public\media\guides\card-pet-transport-what-to-know.jpg | Controller + current database record | Listing card pet transport what to know | guides | /media/guides/card-pet-transport-what-to-know.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card preparing pet boarding | https://waggies.test/media/guides/card-preparing-pet-boarding.jpg | public\media\guides\card-preparing-pet-boarding.jpg | Controller + current database record | Listing card preparing pet boarding | guides | /media/guides/card-preparing-pet-boarding.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card what to expect boarding | https://waggies.test/media/guides/card-what-to-expect-boarding.jpg | public\media\guides\card-what-to-expect-boarding.jpg | Controller + current database record | Listing card what to expect boarding | guides | /media/guides/card-what-to-expect-boarding.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card grooming services explained | https://waggies.test/media/guides/card-grooming-services-explained.jpg | public\media\guides\card-grooming-services-explained.jpg | Controller + current database record | Listing card grooming services explained | guides | /media/guides/card-grooming-services-explained.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides | guides | Listing card pet transport what to know | https://waggies.test/media/guides/card-pet-transport-what-to-know.jpg | public\media\guides\card-pet-transport-what-to-know.jpg | Controller + current database record | Listing card pet transport what to know | guides | /media/guides/card-pet-transport-what-to-know.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card understanding common dog illnesses | https://waggies.test/media/knowledge-base/card-understanding-common-dog-illnesses.jpg | public\media\knowledge-base\card-understanding-common-dog-illnesses.jpg | Controller + current database record | Listing card understanding common dog illnesses | knowledge-base | /media/knowledge-base/card-understanding-common-dog-illnesses.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card best nutrition tips nigerian pets | https://waggies.test/media/knowledge-base/card-best-nutrition-tips-nigerian-pets.jpg | public\media\knowledge-base\card-best-nutrition-tips-nigerian-pets.jpg | Controller + current database record | Listing card best nutrition tips nigerian pets | knowledge-base | /media/knowledge-base/card-best-nutrition-tips-nigerian-pets.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card separation anxiety guide | https://waggies.test/media/knowledge-base/card-separation-anxiety-guide.jpg | public\media\knowledge-base\card-separation-anxiety-guide.jpg | Controller + current database record | Listing card separation anxiety guide | knowledge-base | /media/knowledge-base/card-separation-anxiety-guide.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card grooming routine at home | https://waggies.test/media/knowledge-base/card-grooming-routine-at-home.jpg | public\media\knowledge-base\card-grooming-routine-at-home.jpg | Controller + current database record | Listing card grooming routine at home | knowledge-base | /media/knowledge-base/card-grooming-routine-at-home.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card cat care beginners nigeria | https://waggies.test/media/knowledge-base/card-cat-care-beginners-nigeria.jpg | public\media\knowledge-base\card-cat-care-beginners-nigeria.jpg | Controller + current database record | Listing card cat care beginners nigeria | knowledge-base | /media/knowledge-base/card-cat-care-beginners-nigeria.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card pet vaccination schedule guide | https://waggies.test/media/knowledge-base/card-pet-vaccination-schedule-guide.jpg | public\media\knowledge-base\card-pet-vaccination-schedule-guide.jpg | Controller + current database record | Listing card pet vaccination schedule guide | knowledge-base | /media/knowledge-base/card-pet-vaccination-schedule-guide.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card new pet checklist | https://waggies.test/media/knowledge-base/card-new-pet-checklist.jpg | public\media\knowledge-base\card-new-pet-checklist.jpg | Controller + current database record | Listing card new pet checklist | knowledge-base | /media/knowledge-base/card-new-pet-checklist.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card basic dog training guide | https://waggies.test/media/knowledge-base/card-basic-dog-training-guide.jpg | public\media\knowledge-base\card-basic-dog-training-guide.jpg | Controller + current database record | Listing card basic dog training guide | knowledge-base | /media/knowledge-base/card-basic-dog-training-guide.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card seasonal pet care nigeria | https://waggies.test/media/knowledge-base/card-seasonal-pet-care-nigeria.jpg | public\media\knowledge-base\card-seasonal-pet-care-nigeria.jpg | Controller + current database record | Listing card seasonal pet care nigeria | knowledge-base | /media/knowledge-base/card-seasonal-pet-care-nigeria.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base | knowledge-base | Listing card when to take pet vet | https://waggies.test/media/knowledge-base/card-when-to-take-pet-vet.jpg | public\media\knowledge-base\card-when-to-take-pet-vet.jpg | Controller + current database record | Listing card when to take pet vet | knowledge-base | /media/knowledge-base/card-when-to-take-pet-vet.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides/what-to-expect-boarding | guides | Cover | https://waggies.test/media/guides/what-to-expect-boarding/cover.jpg | public\media\guides\what-to-expect-boarding\cover.jpg | Controller + current database record | Cover | guides | /media/guides/what-to-expect-boarding/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides/pet-transport-what-to-know | guides | Cover | https://waggies.test/media/guides/pet-transport-what-to-know/cover.jpg | public\media\guides\pet-transport-what-to-know\cover.jpg | Controller + current database record | Cover | guides | /media/guides/pet-transport-what-to-know/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides/preparing-pet-boarding | guides | Cover | https://waggies.test/media/guides/preparing-pet-boarding/cover.jpg | public\media\guides\preparing-pet-boarding\cover.jpg | Controller + current database record | Cover | guides | /media/guides/preparing-pet-boarding/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /guides/grooming-services-explained | guides | Cover | https://waggies.test/media/guides/grooming-services-explained/cover.jpg | public\media\guides\grooming-services-explained\cover.jpg | Controller + current database record | Cover | guides | /media/guides/grooming-services-explained/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/cat-care-beginners-nigeria | knowledge-base | Cover | https://waggies.test/media/knowledge-base/cat-care-beginners-nigeria/cover.jpg | public\media\knowledge-base\cat-care-beginners-nigeria\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/cat-care-beginners-nigeria/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/cat-care-beginners-nigeria | knowledge-base | Related grooming routine at home | https://waggies.test/media/knowledge-base/cat-care-beginners-nigeria/related-grooming-routine-at-home.jpg | public\media\knowledge-base\cat-care-beginners-nigeria\related-grooming-routine-at-home.jpg | Controller + current database record | Related grooming routine at home | knowledge-base | /media/knowledge-base/cat-care-beginners-nigeria/related-grooming-routine-at-home.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/pet-vaccination-schedule-guide | knowledge-base | Cover | https://waggies.test/media/knowledge-base/pet-vaccination-schedule-guide/cover.jpg | public\media\knowledge-base\pet-vaccination-schedule-guide\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/pet-vaccination-schedule-guide/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/pet-vaccination-schedule-guide | knowledge-base | Related understanding common dog illnesses | https://waggies.test/media/knowledge-base/pet-vaccination-schedule-guide/related-understanding-common-dog-illnesses.jpg | public\media\knowledge-base\pet-vaccination-schedule-guide\related-understanding-common-dog-illnesses.jpg | Controller + current database record | Related understanding common dog illnesses | knowledge-base | /media/knowledge-base/pet-vaccination-schedule-guide/related-understanding-common-dog-illnesses.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/pet-vaccination-schedule-guide | knowledge-base | Related when to take pet vet | https://waggies.test/media/knowledge-base/pet-vaccination-schedule-guide/related-when-to-take-pet-vet.jpg | public\media\knowledge-base\pet-vaccination-schedule-guide\related-when-to-take-pet-vet.jpg | Controller + current database record | Related when to take pet vet | knowledge-base | /media/knowledge-base/pet-vaccination-schedule-guide/related-when-to-take-pet-vet.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/basic-dog-training-guide | knowledge-base | Cover | https://waggies.test/media/knowledge-base/basic-dog-training-guide/cover.jpg | public\media\knowledge-base\basic-dog-training-guide\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/basic-dog-training-guide/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/understanding-common-dog-illnesses | knowledge-base | Cover | https://waggies.test/media/knowledge-base/understanding-common-dog-illnesses/cover.jpg | public\media\knowledge-base\understanding-common-dog-illnesses\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/understanding-common-dog-illnesses/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/understanding-common-dog-illnesses | knowledge-base | Related pet vaccination schedule guide | https://waggies.test/media/knowledge-base/understanding-common-dog-illnesses/related-pet-vaccination-schedule-guide.jpg | public\media\knowledge-base\understanding-common-dog-illnesses\related-pet-vaccination-schedule-guide.jpg | Controller + current database record | Related pet vaccination schedule guide | knowledge-base | /media/knowledge-base/understanding-common-dog-illnesses/related-pet-vaccination-schedule-guide.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/understanding-common-dog-illnesses | knowledge-base | Related when to take pet vet | https://waggies.test/media/knowledge-base/understanding-common-dog-illnesses/related-when-to-take-pet-vet.jpg | public\media\knowledge-base\understanding-common-dog-illnesses\related-when-to-take-pet-vet.jpg | Controller + current database record | Related when to take pet vet | knowledge-base | /media/knowledge-base/understanding-common-dog-illnesses/related-when-to-take-pet-vet.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/best-nutrition-tips-nigerian-pets | knowledge-base | Cover | https://waggies.test/media/knowledge-base/best-nutrition-tips-nigerian-pets/cover.jpg | public\media\knowledge-base\best-nutrition-tips-nigerian-pets\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/best-nutrition-tips-nigerian-pets/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/separation-anxiety-guide | knowledge-base | Cover | https://waggies.test/media/knowledge-base/separation-anxiety-guide/cover.jpg | public\media\knowledge-base\separation-anxiety-guide\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/separation-anxiety-guide/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/grooming-routine-at-home | knowledge-base | Cover | https://waggies.test/media/knowledge-base/grooming-routine-at-home/cover.jpg | public\media\knowledge-base\grooming-routine-at-home\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/grooming-routine-at-home/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/grooming-routine-at-home | knowledge-base | Related cat care beginners nigeria | https://waggies.test/media/knowledge-base/grooming-routine-at-home/related-cat-care-beginners-nigeria.jpg | public\media\knowledge-base\grooming-routine-at-home\related-cat-care-beginners-nigeria.jpg | Controller + current database record | Related cat care beginners nigeria | knowledge-base | /media/knowledge-base/grooming-routine-at-home/related-cat-care-beginners-nigeria.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/new-pet-checklist | knowledge-base | Cover | https://waggies.test/media/knowledge-base/new-pet-checklist/cover.jpg | public\media\knowledge-base\new-pet-checklist\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/new-pet-checklist/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/when-to-take-pet-vet | knowledge-base | Cover | https://waggies.test/media/knowledge-base/when-to-take-pet-vet/cover.jpg | public\media\knowledge-base\when-to-take-pet-vet\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/when-to-take-pet-vet/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/when-to-take-pet-vet | knowledge-base | Related understanding common dog illnesses | https://waggies.test/media/knowledge-base/when-to-take-pet-vet/related-understanding-common-dog-illnesses.jpg | public\media\knowledge-base\when-to-take-pet-vet\related-understanding-common-dog-illnesses.jpg | Controller + current database record | Related understanding common dog illnesses | knowledge-base | /media/knowledge-base/when-to-take-pet-vet/related-understanding-common-dog-illnesses.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/when-to-take-pet-vet | knowledge-base | Related pet vaccination schedule guide | https://waggies.test/media/knowledge-base/when-to-take-pet-vet/related-pet-vaccination-schedule-guide.jpg | public\media\knowledge-base\when-to-take-pet-vet\related-pet-vaccination-schedule-guide.jpg | Controller + current database record | Related pet vaccination schedule guide | knowledge-base | /media/knowledge-base/when-to-take-pet-vet/related-pet-vaccination-schedule-guide.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /knowledge-base/seasonal-pet-care-nigeria | knowledge-base | Cover | https://waggies.test/media/knowledge-base/seasonal-pet-care-nigeria/cover.jpg | public\media\knowledge-base\seasonal-pet-care-nigeria\cover.jpg | Controller + current database record | Cover | knowledge-base | /media/knowledge-base/seasonal-pet-care-nigeria/cover.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Hero | https://waggies.test/media/shop/royal-canin-puppy/hero.jpg | public\media\shop\royal-canin-puppy\hero.jpg | Controller + current database record | Hero | shop | /media/shop/royal-canin-puppy/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Related whiskas cat food | https://waggies.test/media/shop/royal-canin-puppy/related-whiskas-cat-food.jpg | public\media\shop\royal-canin-puppy\related-whiskas-cat-food.jpg | Controller + current database record | Related whiskas cat food | shop | /media/shop/royal-canin-puppy/related-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/royal-canin-puppy/recent-royal-canin-puppy.jpg | public\media\shop\royal-canin-puppy\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/royal-canin-puppy/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/royal-canin-puppy/recent-whiskas-cat-food.jpg | public\media\shop\royal-canin-puppy\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/royal-canin-puppy/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/royal-canin-puppy/recent-chew-rope-toy.jpg | public\media\shop\royal-canin-puppy\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/royal-canin-puppy/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/royal-canin-puppy/recent-interactive-puzzle-feeder.jpg | public\media\shop\royal-canin-puppy\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/royal-canin-puppy/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/royal-canin-puppy/recent-oatmeal-shampoo.jpg | public\media\shop\royal-canin-puppy\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/royal-canin-puppy/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/royal-canin-puppy/recent-deshedding-brush.jpg | public\media\shop\royal-canin-puppy\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/royal-canin-puppy/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/royal-canin-puppy/recent-tick-flea-collar.jpg | public\media\shop\royal-canin-puppy\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/royal-canin-puppy/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/royal-canin-puppy/recent-pet-first-aid-kit.jpg | public\media\shop\royal-canin-puppy\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/royal-canin-puppy/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/royal-canin-puppy/recent-padded-dog-harness.jpg | public\media\shop\royal-canin-puppy\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/royal-canin-puppy/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/royal-canin-puppy | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/royal-canin-puppy/recent-raised-pet-bowl.jpg | public\media\shop\royal-canin-puppy\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/royal-canin-puppy/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Hero | https://waggies.test/media/shop/whiskas-cat-food/hero.jpg | public\media\shop\whiskas-cat-food\hero.jpg | Controller + current database record | Hero | shop | /media/shop/whiskas-cat-food/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Related royal canin puppy | https://waggies.test/media/shop/whiskas-cat-food/related-royal-canin-puppy.jpg | public\media\shop\whiskas-cat-food\related-royal-canin-puppy.jpg | Controller + current database record | Related royal canin puppy | shop | /media/shop/whiskas-cat-food/related-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/whiskas-cat-food/recent-royal-canin-puppy.jpg | public\media\shop\whiskas-cat-food\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/whiskas-cat-food/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/whiskas-cat-food/recent-whiskas-cat-food.jpg | public\media\shop\whiskas-cat-food\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/whiskas-cat-food/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/whiskas-cat-food/recent-chew-rope-toy.jpg | public\media\shop\whiskas-cat-food\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/whiskas-cat-food/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/whiskas-cat-food/recent-interactive-puzzle-feeder.jpg | public\media\shop\whiskas-cat-food\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/whiskas-cat-food/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/whiskas-cat-food/recent-oatmeal-shampoo.jpg | public\media\shop\whiskas-cat-food\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/whiskas-cat-food/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/whiskas-cat-food/recent-deshedding-brush.jpg | public\media\shop\whiskas-cat-food\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/whiskas-cat-food/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/whiskas-cat-food/recent-tick-flea-collar.jpg | public\media\shop\whiskas-cat-food\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/whiskas-cat-food/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/whiskas-cat-food/recent-pet-first-aid-kit.jpg | public\media\shop\whiskas-cat-food\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/whiskas-cat-food/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/whiskas-cat-food/recent-padded-dog-harness.jpg | public\media\shop\whiskas-cat-food\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/whiskas-cat-food/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/whiskas-cat-food | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/whiskas-cat-food/recent-raised-pet-bowl.jpg | public\media\shop\whiskas-cat-food\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/whiskas-cat-food/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Hero | https://waggies.test/media/shop/chew-rope-toy/hero.jpg | public\media\shop\chew-rope-toy\hero.jpg | Controller + current database record | Hero | shop | /media/shop/chew-rope-toy/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Related interactive puzzle feeder | https://waggies.test/media/shop/chew-rope-toy/related-interactive-puzzle-feeder.jpg | public\media\shop\chew-rope-toy\related-interactive-puzzle-feeder.jpg | Controller + current database record | Related interactive puzzle feeder | shop | /media/shop/chew-rope-toy/related-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/chew-rope-toy/recent-royal-canin-puppy.jpg | public\media\shop\chew-rope-toy\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/chew-rope-toy/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/chew-rope-toy/recent-whiskas-cat-food.jpg | public\media\shop\chew-rope-toy\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/chew-rope-toy/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/chew-rope-toy/recent-chew-rope-toy.jpg | public\media\shop\chew-rope-toy\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/chew-rope-toy/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/chew-rope-toy/recent-interactive-puzzle-feeder.jpg | public\media\shop\chew-rope-toy\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/chew-rope-toy/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/chew-rope-toy/recent-oatmeal-shampoo.jpg | public\media\shop\chew-rope-toy\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/chew-rope-toy/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/chew-rope-toy/recent-deshedding-brush.jpg | public\media\shop\chew-rope-toy\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/chew-rope-toy/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/chew-rope-toy/recent-tick-flea-collar.jpg | public\media\shop\chew-rope-toy\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/chew-rope-toy/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/chew-rope-toy/recent-pet-first-aid-kit.jpg | public\media\shop\chew-rope-toy\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/chew-rope-toy/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/chew-rope-toy/recent-padded-dog-harness.jpg | public\media\shop\chew-rope-toy\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/chew-rope-toy/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/chew-rope-toy | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/chew-rope-toy/recent-raised-pet-bowl.jpg | public\media\shop\chew-rope-toy\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/chew-rope-toy/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Hero | https://waggies.test/media/shop/interactive-puzzle-feeder/hero.jpg | public\media\shop\interactive-puzzle-feeder\hero.jpg | Controller + current database record | Hero | shop | /media/shop/interactive-puzzle-feeder/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Related chew rope toy | https://waggies.test/media/shop/interactive-puzzle-feeder/related-chew-rope-toy.jpg | public\media\shop\interactive-puzzle-feeder\related-chew-rope-toy.jpg | Controller + current database record | Related chew rope toy | shop | /media/shop/interactive-puzzle-feeder/related-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-royal-canin-puppy.jpg | public\media\shop\interactive-puzzle-feeder\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/interactive-puzzle-feeder/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-whiskas-cat-food.jpg | public\media\shop\interactive-puzzle-feeder\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/interactive-puzzle-feeder/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-chew-rope-toy.jpg | public\media\shop\interactive-puzzle-feeder\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/interactive-puzzle-feeder/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-interactive-puzzle-feeder.jpg | public\media\shop\interactive-puzzle-feeder\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/interactive-puzzle-feeder/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-oatmeal-shampoo.jpg | public\media\shop\interactive-puzzle-feeder\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/interactive-puzzle-feeder/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-deshedding-brush.jpg | public\media\shop\interactive-puzzle-feeder\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/interactive-puzzle-feeder/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-tick-flea-collar.jpg | public\media\shop\interactive-puzzle-feeder\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/interactive-puzzle-feeder/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-pet-first-aid-kit.jpg | public\media\shop\interactive-puzzle-feeder\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/interactive-puzzle-feeder/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-padded-dog-harness.jpg | public\media\shop\interactive-puzzle-feeder\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/interactive-puzzle-feeder/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/interactive-puzzle-feeder | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/interactive-puzzle-feeder/recent-raised-pet-bowl.jpg | public\media\shop\interactive-puzzle-feeder\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/interactive-puzzle-feeder/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Hero | https://waggies.test/media/shop/oatmeal-shampoo/hero.jpg | public\media\shop\oatmeal-shampoo\hero.jpg | Controller + current database record | Hero | shop | /media/shop/oatmeal-shampoo/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Related deshedding brush | https://waggies.test/media/shop/oatmeal-shampoo/related-deshedding-brush.jpg | public\media\shop\oatmeal-shampoo\related-deshedding-brush.jpg | Controller + current database record | Related deshedding brush | shop | /media/shop/oatmeal-shampoo/related-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/oatmeal-shampoo/recent-royal-canin-puppy.jpg | public\media\shop\oatmeal-shampoo\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/oatmeal-shampoo/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/oatmeal-shampoo/recent-whiskas-cat-food.jpg | public\media\shop\oatmeal-shampoo\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/oatmeal-shampoo/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/oatmeal-shampoo/recent-chew-rope-toy.jpg | public\media\shop\oatmeal-shampoo\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/oatmeal-shampoo/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/oatmeal-shampoo/recent-interactive-puzzle-feeder.jpg | public\media\shop\oatmeal-shampoo\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/oatmeal-shampoo/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/oatmeal-shampoo/recent-oatmeal-shampoo.jpg | public\media\shop\oatmeal-shampoo\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/oatmeal-shampoo/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/oatmeal-shampoo/recent-deshedding-brush.jpg | public\media\shop\oatmeal-shampoo\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/oatmeal-shampoo/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/oatmeal-shampoo/recent-tick-flea-collar.jpg | public\media\shop\oatmeal-shampoo\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/oatmeal-shampoo/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/oatmeal-shampoo/recent-pet-first-aid-kit.jpg | public\media\shop\oatmeal-shampoo\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/oatmeal-shampoo/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/oatmeal-shampoo/recent-padded-dog-harness.jpg | public\media\shop\oatmeal-shampoo\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/oatmeal-shampoo/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/oatmeal-shampoo | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/oatmeal-shampoo/recent-raised-pet-bowl.jpg | public\media\shop\oatmeal-shampoo\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/oatmeal-shampoo/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Hero | https://waggies.test/media/shop/deshedding-brush/hero.jpg | public\media\shop\deshedding-brush\hero.jpg | Controller + current database record | Hero | shop | /media/shop/deshedding-brush/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Related oatmeal shampoo | https://waggies.test/media/shop/deshedding-brush/related-oatmeal-shampoo.jpg | public\media\shop\deshedding-brush\related-oatmeal-shampoo.jpg | Controller + current database record | Related oatmeal shampoo | shop | /media/shop/deshedding-brush/related-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/deshedding-brush/recent-royal-canin-puppy.jpg | public\media\shop\deshedding-brush\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/deshedding-brush/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/deshedding-brush/recent-whiskas-cat-food.jpg | public\media\shop\deshedding-brush\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/deshedding-brush/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/deshedding-brush/recent-chew-rope-toy.jpg | public\media\shop\deshedding-brush\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/deshedding-brush/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/deshedding-brush/recent-interactive-puzzle-feeder.jpg | public\media\shop\deshedding-brush\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/deshedding-brush/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/deshedding-brush/recent-oatmeal-shampoo.jpg | public\media\shop\deshedding-brush\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/deshedding-brush/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/deshedding-brush/recent-deshedding-brush.jpg | public\media\shop\deshedding-brush\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/deshedding-brush/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/deshedding-brush/recent-tick-flea-collar.jpg | public\media\shop\deshedding-brush\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/deshedding-brush/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/deshedding-brush/recent-pet-first-aid-kit.jpg | public\media\shop\deshedding-brush\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/deshedding-brush/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/deshedding-brush/recent-padded-dog-harness.jpg | public\media\shop\deshedding-brush\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/deshedding-brush/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/deshedding-brush | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/deshedding-brush/recent-raised-pet-bowl.jpg | public\media\shop\deshedding-brush\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/deshedding-brush/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Hero | https://waggies.test/media/shop/tick-flea-collar/hero.jpg | public\media\shop\tick-flea-collar\hero.jpg | Controller + current database record | Hero | shop | /media/shop/tick-flea-collar/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Related pet first aid kit | https://waggies.test/media/shop/tick-flea-collar/related-pet-first-aid-kit.jpg | public\media\shop\tick-flea-collar\related-pet-first-aid-kit.jpg | Controller + current database record | Related pet first aid kit | shop | /media/shop/tick-flea-collar/related-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/tick-flea-collar/recent-royal-canin-puppy.jpg | public\media\shop\tick-flea-collar\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/tick-flea-collar/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/tick-flea-collar/recent-whiskas-cat-food.jpg | public\media\shop\tick-flea-collar\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/tick-flea-collar/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/tick-flea-collar/recent-chew-rope-toy.jpg | public\media\shop\tick-flea-collar\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/tick-flea-collar/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/tick-flea-collar/recent-interactive-puzzle-feeder.jpg | public\media\shop\tick-flea-collar\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/tick-flea-collar/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/tick-flea-collar/recent-oatmeal-shampoo.jpg | public\media\shop\tick-flea-collar\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/tick-flea-collar/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/tick-flea-collar/recent-deshedding-brush.jpg | public\media\shop\tick-flea-collar\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/tick-flea-collar/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/tick-flea-collar/recent-tick-flea-collar.jpg | public\media\shop\tick-flea-collar\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/tick-flea-collar/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/tick-flea-collar/recent-pet-first-aid-kit.jpg | public\media\shop\tick-flea-collar\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/tick-flea-collar/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/tick-flea-collar/recent-padded-dog-harness.jpg | public\media\shop\tick-flea-collar\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/tick-flea-collar/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/tick-flea-collar | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/tick-flea-collar/recent-raised-pet-bowl.jpg | public\media\shop\tick-flea-collar\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/tick-flea-collar/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Hero | https://waggies.test/media/shop/pet-first-aid-kit/hero.jpg | public\media\shop\pet-first-aid-kit\hero.jpg | Controller + current database record | Hero | shop | /media/shop/pet-first-aid-kit/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Related tick flea collar | https://waggies.test/media/shop/pet-first-aid-kit/related-tick-flea-collar.jpg | public\media\shop\pet-first-aid-kit\related-tick-flea-collar.jpg | Controller + current database record | Related tick flea collar | shop | /media/shop/pet-first-aid-kit/related-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/pet-first-aid-kit/recent-royal-canin-puppy.jpg | public\media\shop\pet-first-aid-kit\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/pet-first-aid-kit/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/pet-first-aid-kit/recent-whiskas-cat-food.jpg | public\media\shop\pet-first-aid-kit\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/pet-first-aid-kit/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/pet-first-aid-kit/recent-chew-rope-toy.jpg | public\media\shop\pet-first-aid-kit\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/pet-first-aid-kit/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/pet-first-aid-kit/recent-interactive-puzzle-feeder.jpg | public\media\shop\pet-first-aid-kit\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/pet-first-aid-kit/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/pet-first-aid-kit/recent-oatmeal-shampoo.jpg | public\media\shop\pet-first-aid-kit\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/pet-first-aid-kit/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/pet-first-aid-kit/recent-deshedding-brush.jpg | public\media\shop\pet-first-aid-kit\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/pet-first-aid-kit/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/pet-first-aid-kit/recent-tick-flea-collar.jpg | public\media\shop\pet-first-aid-kit\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/pet-first-aid-kit/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/pet-first-aid-kit/recent-pet-first-aid-kit.jpg | public\media\shop\pet-first-aid-kit\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/pet-first-aid-kit/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/pet-first-aid-kit/recent-padded-dog-harness.jpg | public\media\shop\pet-first-aid-kit\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/pet-first-aid-kit/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/pet-first-aid-kit | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/pet-first-aid-kit/recent-raised-pet-bowl.jpg | public\media\shop\pet-first-aid-kit\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/pet-first-aid-kit/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Hero | https://waggies.test/media/shop/padded-dog-harness/hero.jpg | public\media\shop\padded-dog-harness\hero.jpg | Controller + current database record | Hero | shop | /media/shop/padded-dog-harness/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Related raised pet bowl | https://waggies.test/media/shop/padded-dog-harness/related-raised-pet-bowl.jpg | public\media\shop\padded-dog-harness\related-raised-pet-bowl.jpg | Controller + current database record | Related raised pet bowl | shop | /media/shop/padded-dog-harness/related-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/padded-dog-harness/recent-royal-canin-puppy.jpg | public\media\shop\padded-dog-harness\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/padded-dog-harness/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/padded-dog-harness/recent-whiskas-cat-food.jpg | public\media\shop\padded-dog-harness\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/padded-dog-harness/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/padded-dog-harness/recent-chew-rope-toy.jpg | public\media\shop\padded-dog-harness\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/padded-dog-harness/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/padded-dog-harness/recent-interactive-puzzle-feeder.jpg | public\media\shop\padded-dog-harness\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/padded-dog-harness/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/padded-dog-harness/recent-oatmeal-shampoo.jpg | public\media\shop\padded-dog-harness\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/padded-dog-harness/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/padded-dog-harness/recent-deshedding-brush.jpg | public\media\shop\padded-dog-harness\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/padded-dog-harness/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/padded-dog-harness/recent-tick-flea-collar.jpg | public\media\shop\padded-dog-harness\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/padded-dog-harness/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/padded-dog-harness/recent-pet-first-aid-kit.jpg | public\media\shop\padded-dog-harness\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/padded-dog-harness/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/padded-dog-harness/recent-padded-dog-harness.jpg | public\media\shop\padded-dog-harness\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/padded-dog-harness/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/padded-dog-harness | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/padded-dog-harness/recent-raised-pet-bowl.jpg | public\media\shop\padded-dog-harness\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/padded-dog-harness/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Hero | https://waggies.test/media/shop/raised-pet-bowl/hero.jpg | public\media\shop\raised-pet-bowl\hero.jpg | Controller + current database record | Hero | shop | /media/shop/raised-pet-bowl/hero.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Related padded dog harness | https://waggies.test/media/shop/raised-pet-bowl/related-padded-dog-harness.jpg | public\media\shop\raised-pet-bowl\related-padded-dog-harness.jpg | Controller + current database record | Related padded dog harness | shop | /media/shop/raised-pet-bowl/related-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed royal canin puppy | https://waggies.test/media/shop/raised-pet-bowl/recent-royal-canin-puppy.jpg | public\media\shop\raised-pet-bowl\recent-royal-canin-puppy.jpg | Controller + current database record | Recently viewed royal canin puppy | shop | /media/shop/raised-pet-bowl/recent-royal-canin-puppy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed whiskas cat food | https://waggies.test/media/shop/raised-pet-bowl/recent-whiskas-cat-food.jpg | public\media\shop\raised-pet-bowl\recent-whiskas-cat-food.jpg | Controller + current database record | Recently viewed whiskas cat food | shop | /media/shop/raised-pet-bowl/recent-whiskas-cat-food.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed chew rope toy | https://waggies.test/media/shop/raised-pet-bowl/recent-chew-rope-toy.jpg | public\media\shop\raised-pet-bowl\recent-chew-rope-toy.jpg | Controller + current database record | Recently viewed chew rope toy | shop | /media/shop/raised-pet-bowl/recent-chew-rope-toy.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed interactive puzzle feeder | https://waggies.test/media/shop/raised-pet-bowl/recent-interactive-puzzle-feeder.jpg | public\media\shop\raised-pet-bowl\recent-interactive-puzzle-feeder.jpg | Controller + current database record | Recently viewed interactive puzzle feeder | shop | /media/shop/raised-pet-bowl/recent-interactive-puzzle-feeder.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed oatmeal shampoo | https://waggies.test/media/shop/raised-pet-bowl/recent-oatmeal-shampoo.jpg | public\media\shop\raised-pet-bowl\recent-oatmeal-shampoo.jpg | Controller + current database record | Recently viewed oatmeal shampoo | shop | /media/shop/raised-pet-bowl/recent-oatmeal-shampoo.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed deshedding brush | https://waggies.test/media/shop/raised-pet-bowl/recent-deshedding-brush.jpg | public\media\shop\raised-pet-bowl\recent-deshedding-brush.jpg | Controller + current database record | Recently viewed deshedding brush | shop | /media/shop/raised-pet-bowl/recent-deshedding-brush.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed tick flea collar | https://waggies.test/media/shop/raised-pet-bowl/recent-tick-flea-collar.jpg | public\media\shop\raised-pet-bowl\recent-tick-flea-collar.jpg | Controller + current database record | Recently viewed tick flea collar | shop | /media/shop/raised-pet-bowl/recent-tick-flea-collar.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed pet first aid kit | https://waggies.test/media/shop/raised-pet-bowl/recent-pet-first-aid-kit.jpg | public\media\shop\raised-pet-bowl\recent-pet-first-aid-kit.jpg | Controller + current database record | Recently viewed pet first aid kit | shop | /media/shop/raised-pet-bowl/recent-pet-first-aid-kit.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed padded dog harness | https://waggies.test/media/shop/raised-pet-bowl/recent-padded-dog-harness.jpg | public\media\shop\raised-pet-bowl\recent-padded-dog-harness.jpg | Controller + current database record | Recently viewed padded dog harness | shop | /media/shop/raised-pet-bowl/recent-padded-dog-harness.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /shop/raised-pet-bowl | shop | Recently viewed raised pet bowl | https://waggies.test/media/shop/raised-pet-bowl/recent-raised-pet-bowl.jpg | public\media\shop\raised-pet-bowl\recent-raised-pet-bowl.jpg | Controller + current database record | Recently viewed raised pet bowl | shop | /media/shop/raised-pet-bowl/recent-raised-pet-bowl.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |

### Hydrated browser-only occurrences

The browser crawl added 23 database-backed gallery rows that are absent from the initial PHP response. The 66 external `<img>` nodes observed during that crawl were hidden client-local saved-list state, not page-owned semantic slots, so they are excluded from this ledger.


| URL | Section | Function | Current URL | Physical file | Source | Intended purpose | Owner | Proposed final path | Placeholder collision? | Intentional shared? | Duplicate required? | Action |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| /about/gallery | about/gallery | Gallery 01 | https://waggies.test/media/about/gallery/gallery-01.jpg | public/media/about/gallery/gallery-01.jpg | About configuration + gallery_items | Gallery 01 | about | /media/about/gallery/gallery-01.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 02 | https://waggies.test/media/about/gallery/gallery-02.jpg | public/media/about/gallery/gallery-02.jpg | About configuration + gallery_items | Gallery 02 | about | /media/about/gallery/gallery-02.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 03 | https://waggies.test/media/about/gallery/gallery-03.jpg | public/media/about/gallery/gallery-03.jpg | About configuration + gallery_items | Gallery 03 | about | /media/about/gallery/gallery-03.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 04 | https://waggies.test/media/about/gallery/gallery-04.jpg | public/media/about/gallery/gallery-04.jpg | About configuration + gallery_items | Gallery 04 | about | /media/about/gallery/gallery-04.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 05 | https://waggies.test/media/about/gallery/gallery-05.jpg | public/media/about/gallery/gallery-05.jpg | About configuration + gallery_items | Gallery 05 | about | /media/about/gallery/gallery-05.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 06 | https://waggies.test/media/about/gallery/gallery-06.jpg | public/media/about/gallery/gallery-06.jpg | About configuration + gallery_items | Gallery 06 | about | /media/about/gallery/gallery-06.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 07 | https://waggies.test/media/about/gallery/gallery-07.jpg | public/media/about/gallery/gallery-07.jpg | About configuration + gallery_items | Gallery 07 | about | /media/about/gallery/gallery-07.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 08 | https://waggies.test/media/about/gallery/gallery-08.jpg | public/media/about/gallery/gallery-08.jpg | About configuration + gallery_items | Gallery 08 | about | /media/about/gallery/gallery-08.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 09 | https://waggies.test/media/about/gallery/gallery-09.jpg | public/media/about/gallery/gallery-09.jpg | About configuration + gallery_items | Gallery 09 | about | /media/about/gallery/gallery-09.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 10 | https://waggies.test/media/about/gallery/gallery-10.jpg | public/media/about/gallery/gallery-10.jpg | About configuration + gallery_items | Gallery 10 | about | /media/about/gallery/gallery-10.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 11 | https://waggies.test/media/about/gallery/gallery-11.jpg | public/media/about/gallery/gallery-11.jpg | About configuration + gallery_items | Gallery 11 | about | /media/about/gallery/gallery-11.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 12 | https://waggies.test/media/about/gallery/gallery-12.jpg | public/media/about/gallery/gallery-12.jpg | About configuration + gallery_items | Gallery 12 | about | /media/about/gallery/gallery-12.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 13 | https://waggies.test/media/about/gallery/gallery-13.jpg | public/media/about/gallery/gallery-13.jpg | About configuration + gallery_items | Gallery 13 | about | /media/about/gallery/gallery-13.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 14 | https://waggies.test/media/about/gallery/gallery-14.jpg | public/media/about/gallery/gallery-14.jpg | About configuration + gallery_items | Gallery 14 | about | /media/about/gallery/gallery-14.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 15 | https://waggies.test/media/about/gallery/gallery-15.jpg | public/media/about/gallery/gallery-15.jpg | About configuration + gallery_items | Gallery 15 | about | /media/about/gallery/gallery-15.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 16 | https://waggies.test/media/about/gallery/gallery-16.jpg | public/media/about/gallery/gallery-16.jpg | About configuration + gallery_items | Gallery 16 | about | /media/about/gallery/gallery-16.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 17 | https://waggies.test/media/about/gallery/gallery-17.jpg | public/media/about/gallery/gallery-17.jpg | About configuration + gallery_items | Gallery 17 | about | /media/about/gallery/gallery-17.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 18 | https://waggies.test/media/about/gallery/gallery-18.jpg | public/media/about/gallery/gallery-18.jpg | About configuration + gallery_items | Gallery 18 | about | /media/about/gallery/gallery-18.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 19 | https://waggies.test/media/about/gallery/gallery-19.jpg | public/media/about/gallery/gallery-19.jpg | About configuration + gallery_items | Gallery 19 | about | /media/about/gallery/gallery-19.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 20 | https://waggies.test/media/about/gallery/gallery-20.jpg | public/media/about/gallery/gallery-20.jpg | About configuration + gallery_items | Gallery 20 | about | /media/about/gallery/gallery-20.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 21 | https://waggies.test/media/about/gallery/gallery-21.jpg | public/media/about/gallery/gallery-21.jpg | About configuration + gallery_items | Gallery 21 | about | /media/about/gallery/gallery-21.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 22 | https://waggies.test/media/about/gallery/gallery-22.jpg | public/media/about/gallery/gallery-22.jpg | About configuration + gallery_items | Gallery 22 | about | /media/about/gallery/gallery-22.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |
| /about/gallery | about/gallery | Gallery 23 | https://waggies.test/media/about/gallery/gallery-23.jpg | public/media/about/gallery/gallery-23.jpg | About configuration + gallery_items | Gallery 23 | about | /media/about/gallery/gallery-23.jpg | No — page/domain path is explicit | No | Yes | Keep canonical page-owned asset |

## Reconciliation of the former 237/238 discrepancy

The old `238` semantic-asset value was incorrect. The additional item was the external Unsplash URL observed in a hidden, client-local saved-list component. It was repeated once per requested navigation (66 DOM observations), was not in application source, was not a page-owned semantic slot, and had no Waggies physical file. The corrected result is **237 unique final semantic assets = 237 local + 0 external**.

## Image-slot ledger integrity

The complete occurrence ledger contains **246 rows**, exactly one row for each page-owned image-slot occurrence. The 23 database-backed gallery rows are in the hydrated supplement and are included in the 246. No row is an aggregate alias for a route family or a semicolon-packed group of slots. The 66 hidden external saved-list observations are explicitly excluded from the semantic ledger.

The former 312-row total was therefore not a semantic-slot total: it was `246 page-owned local rows + 66 hidden client-state external observations`.

## Independent page render comparison

Each requested URL was rendered after remediation. `Expected` is the ledger row count for that concrete page; `Actual` is the hydrated DOM count of page-owned local `/media/` images. Absolute same-origin URLs were normalized by pathname. Every row reconciles.

| # | Requested URL | Final URL | Expected | Actual | Missing | Extra | Broken |
| ---: | --- | --- | ---: | ---: | ---: | ---: | ---: |
| 1 |  `/`  |  `/`  |  7  |  7  |  0  |  0  |  0  |
| 2 |  `/about`  |  `/about`  |  3  |  3  |  0  |  0  |  0  |
| 3 |  `/about/testimonials`  |  `/about/testimonials`  |  1  |  1  |  0  |  0  |  0  |
| 4 |  `/about/gallery`  |  `/about/gallery`  |  23  |  23  |  0  |  0  |  0  |
| 5 |  `/about/careers`  |  `/about/careers`  |  1  |  1  |  0  |  0  |  0  |
| 6 |  `/about/partnerships`  |  `/about/partnerships`  |  0  |  0  |  0  |  0  |  0  |
| 7 |  `/services`  |  `/services`  |  6  |  6  |  0  |  0  |  0  |
| 8 |  `/services/boarding`  |  `/services/boarding`  |  4  |  4  |  0  |  0  |  0  |
| 9 |  `/services/boarding/dogs`  |  `/services/boarding/dogs`  |  7  |  7  |  0  |  0  |  0  |
| 10 |  `/services/boarding/cats`  |  `/services/boarding/cats`  |  8  |  8  |  0  |  0  |  0  |
| 11 |  `/services/boarding/exotic`  |  `/services/boarding/exotic`  |  3  |  3  |  0  |  0  |  0  |
| 12 |  `/services/grooming`  |  `/services/grooming`  |  1  |  1  |  0  |  0  |  0  |
| 13 |  `/services/training`  |  `/services/training`  |  1  |  1  |  0  |  0  |  0  |
| 14 |  `/services/vet-care`  |  `/services/vet-care`  |  1  |  1  |  0  |  0  |  0  |
| 15 |  `/services/pricing`  |  `/services/pricing`  |  0  |  0  |  0  |  0  |  0  |
| 16 |  `/services/relocation`  |  `/services/relocation`  |  5  |  5  |  0  |  0  |  0  |
| 17 |  `/services/relocation/import`  |  `/services/relocation/import`  |  1  |  1  |  0  |  0  |  0  |
| 18 |  `/services/relocation/export`  |  `/services/relocation/export`  |  1  |  1  |  0  |  0  |  0  |
| 19 |  `/services/relocation/transport`  |  `/services/relocation/transport`  |  1  |  1  |  0  |  0  |  0  |
| 20 |  `/services/relocation/checklist`  |  `/services/relocation/checklist`  |  1  |  1  |  0  |  0  |  0  |
| 21 |  `/faq`  |  `/faq`  |  1  |  1  |  0  |  0  |  0  |
| 22 |  `/privacy-policy`  |  `/privacy-policy`  |  0  |  0  |  0  |  0  |  0  |
| 23 |  `/terms-of-service`  |  `/terms-of-service`  |  0  |  0  |  0  |  0  |  0  |
| 24 |  `/cookies-policy`  |  `/cookies-policy`  |  0  |  0  |  0  |  0  |  0  |
| 25 |  `/contact`  |  `/contact`  |  0  |  0  |  0  |  0  |  0  |
| 26 |  `/book`  |  `/book`  |  0  |  0  |  0  |  0  |  0  |
| 27 |  `/loyalty`  |  `/loyalty`  |  0  |  0  |  0  |  0  |  0  |
| 28 |  `/shop`  |  `/shop`  |  10  |  10  |  0  |  0  |  0  |
| 29 |  `/guides`  |  `/guides`  |  8  |  8  |  0  |  0  |  0  |
| 30 |  `/knowledge-base`  |  `/knowledge-base`  |  10  |  10  |  0  |  0  |  0  |
| 31 |  `/tools`  |  `/tools`  |  0  |  0  |  0  |  0  |  0  |
| 32 |  `/tools/symptom-checker`  |  `/tools/symptom-checker`  |  0  |  0  |  0  |  0  |  0  |
| 33 |  `/tools/vaccination-schedule`  |  `/tools/vaccination-schedule`  |  0  |  0  |  0  |  0  |  0  |
| 34 |  `/tools/parasite-schedule`  |  `/tools/parasite-schedule`  |  0  |  0  |  0  |  0  |  0  |
| 35 |  `/tools/emergency-guide`  |  `/tools/emergency-guide`  |  0  |  0  |  0  |  0  |  0  |
| 36 |  `/tools/medication-dosage-guide`  |  `/tools/medication-dosage-guide`  |  0  |  0  |  0  |  0  |  0  |
| 37 |  `/tools/pet-age-calculator`  |  `/tools/pet-age-calculator`  |  0  |  0  |  0  |  0  |  0  |
| 38 |  `/tools/cost-calculator`  |  `/services/pricing`  |  0  |  0  |  0  |  0  |  0  |
| 39 |  `/tools/nutrition-calculator`  |  `/tools/nutrition-calculator`  |  0  |  0  |  0  |  0  |  0  |
| 40 |  `/tools/breed-finder`  |  `/tools/breed-finder`  |  0  |  0  |  0  |  0  |  0  |
| 41 |  `/tools/behavior-tips`  |  `/tools/behavior-tips`  |  0  |  0  |  0  |  0  |  0  |
| 42 |  `/tools/new-pet-checklist`  |  `/tools/new-pet-checklist`  |  0  |  0  |  0  |  0  |  0  |
| 43 |  `/guides/what-to-expect-boarding`  |  `/guides/what-to-expect-boarding`  |  1  |  1  |  0  |  0  |  0  |
| 44 |  `/guides/pet-transport-what-to-know`  |  `/guides/pet-transport-what-to-know`  |  1  |  1  |  0  |  0  |  0  |
| 45 |  `/guides/preparing-pet-boarding`  |  `/guides/preparing-pet-boarding`  |  1  |  1  |  0  |  0  |  0  |
| 46 |  `/guides/grooming-services-explained`  |  `/guides/grooming-services-explained`  |  1  |  1  |  0  |  0  |  0  |
| 47 |  `/knowledge-base/cat-care-beginners-nigeria`  |  `/knowledge-base/cat-care-beginners-nigeria`  |  2  |  2  |  0  |  0  |  0  |
| 48 |  `/knowledge-base/pet-vaccination-schedule-guide`  |  `/knowledge-base/pet-vaccination-schedule-guide`  |  3  |  3  |  0  |  0  |  0  |
| 49 |  `/knowledge-base/basic-dog-training-guide`  |  `/knowledge-base/basic-dog-training-guide`  |  1  |  1  |  0  |  0  |  0  |
| 50 |  `/knowledge-base/understanding-common-dog-illnesses`  |  `/knowledge-base/understanding-common-dog-illnesses`  |  3  |  3  |  0  |  0  |  0  |
| 51 |  `/knowledge-base/best-nutrition-tips-nigerian-pets`  |  `/knowledge-base/best-nutrition-tips-nigerian-pets`  |  1  |  1  |  0  |  0  |  0  |
| 52 |  `/knowledge-base/separation-anxiety-guide`  |  `/knowledge-base/separation-anxiety-guide`  |  1  |  1  |  0  |  0  |  0  |
| 53 |  `/knowledge-base/grooming-routine-at-home`  |  `/knowledge-base/grooming-routine-at-home`  |  2  |  2  |  0  |  0  |  0  |
| 54 |  `/knowledge-base/new-pet-checklist`  |  `/knowledge-base/new-pet-checklist`  |  1  |  1  |  0  |  0  |  0  |
| 55 |  `/knowledge-base/when-to-take-pet-vet`  |  `/knowledge-base/when-to-take-pet-vet`  |  3  |  3  |  0  |  0  |  0  |
| 56 |  `/knowledge-base/seasonal-pet-care-nigeria`  |  `/knowledge-base/seasonal-pet-care-nigeria`  |  1  |  1  |  0  |  0  |  0  |
| 57 |  `/shop/royal-canin-puppy`  |  `/shop/royal-canin-puppy`  |  12  |  12  |  0  |  0  |  0  |
| 58 |  `/shop/whiskas-cat-food`  |  `/shop/whiskas-cat-food`  |  12  |  12  |  0  |  0  |  0  |
| 59 |  `/shop/chew-rope-toy`  |  `/shop/chew-rope-toy`  |  12  |  12  |  0  |  0  |  0  |
| 60 |  `/shop/interactive-puzzle-feeder`  |  `/shop/interactive-puzzle-feeder`  |  12  |  12  |  0  |  0  |  0  |
| 61 |  `/shop/oatmeal-shampoo`  |  `/shop/oatmeal-shampoo`  |  12  |  12  |  0  |  0  |  0  |
| 62 |  `/shop/deshedding-brush`  |  `/shop/deshedding-brush`  |  12  |  12  |  0  |  0  |  0  |
| 63 |  `/shop/tick-flea-collar`  |  `/shop/tick-flea-collar`  |  12  |  12  |  0  |  0  |  0  |
| 64 |  `/shop/pet-first-aid-kit`  |  `/shop/pet-first-aid-kit`  |  12  |  12  |  0  |  0  |  0  |
| 65 |  `/shop/padded-dog-harness`  |  `/shop/padded-dog-harness`  |  12  |  12  |  0  |  0  |  0  |
| 66 |  `/shop/raised-pet-bowl`  |  `/shop/raised-pet-bowl`  |  12  |  12  |  0  |  0  |  0  |

## Parent/subpage independence

| Page | Rendered slots | Namespace rule |
| --- | ---: | --- |
| `/services/boarding` | 4 | Own parent namespace: `public/media/services/boarding/hero.jpg` and `card-*.jpg` |
| `/services/boarding/dogs` | 7 | Own child namespace: `public/media/services/boarding/dogs/` |
| `/services/boarding/cats` | 8 | Own child namespace: `public/media/services/boarding/cats/` |
| `/services/boarding/exotic` | 3 | Own child namespace: `public/media/services/boarding/exotic/`; no daily-image section is rendered for exotic |

Sibling recommendation images are page-owned child paths (`related-*.jpg`) and are explicitly documented in the ledger. A child never inherits the parent hero binary by component reuse.

## Placeholder collision matrix

The current matrix has **0 unresolved collisions**. The 14 historical placeholder families were separated by semantic role and namespace; identical bytes remain separate physical files where ownership requires it.

| Historical collision family | Current independent slots |
| --- | --- |
| Boarding parent hero | `/services/boarding` hero |
| Boarding parent card | Parent dog, cat, and exotic cards |
| Boarding CTA/promo | Parent CTA/promo role |
| Dogs hero | `/services/boarding/dogs` hero |
| Cats hero | `/services/boarding/cats` hero |
| Exotic hero | `/services/boarding/exotic` hero |
| Dogs daily set | Four dogs daily slots |
| Cats daily set | Four cats daily slots |
| Cats feline feature | Cats feline feature slot |
| Dogs sibling recommendations | Dogs related cats/exotic slots |
| Cats sibling recommendations | Cats related dogs/exotic slots |
| Exotic sibling recommendations | Exotic related dogs/cats slots |
| Product hero | One hero slot per product namespace |
| Product related/recent slots | Independent related/recent slot per product page |

## Canonical, shared, and duplicate inventory

- The canonical inventory is the 237 unique physical paths represented by the 246 local rows above. All 237 exist in `public/media/` and all returned HTTP 200.
- Truly shared active URLs are exactly 9 physical paths reused by 18 slots: the four guide cards, `services/boarding/card-dogs.jpg`, and the four service-detail hero paths for grooming, training, vet care, and relocation. This is intentional cross-page reuse, not a generic catch-all.
- There are no external semantic assets. The Unsplash URL is only hidden client-local saved-list state.
- Byte identity does not change ownership. SHA-256 grouping found 24 active duplicate groups containing 229 canonical files, or 205 extra copies beyond one representative per group. The duplicate group sizes are: 29, 3, 4, 8, 4, 3, 2, 3, 4, 6, 3, 2, 8, 2, 2, 18, 52, 5, 15, 18, 3, 2, 11, and 22.
- Across the entire final 310-file tree, including legacy compatibility files, there are 36 byte-identical groups containing all 310 files and 274 extra copies. The active-only figures above are the ones used for semantic ownership decisions.

## Legacy inventory

Legacy physical files are retained files, not active semantic assets. The editorial trees contain 70 files: 33 under `public/media/editorial/` and 37 under `public/media/shared/editorial/`; each is directly addressable and returns HTTP 200. The three redirect-target files are also retained physical compatibility files and return HTTP 200:

| Legacy file path | Legacy URL | Handling | HTTP result | Canonical replacement |
| --- | --- | --- | ---: | --- |
| `public/media/editorial/*.jpg` (33 files) | `/media/editorial/*.jpg` | Retain direct compatibility files | 200 | None; historical path |
| `public/media/shared/editorial/*.jpg` (37 files) | `/media/shared/editorial/*.jpg` | Retain direct compatibility files | 200 | None; historical path |
| `public/media/services/boarding/hero-cats.jpg` | `/media/services/boarding/hero-cats.jpg` | Retain as redirect target | 200 | `/media/services/boarding/cats/hero.jpg` |
| `public/media/services/boarding/hero-dogs.jpg` | `/media/services/boarding/hero-dogs.jpg` | Retain as redirect target | 200 | `/media/services/boarding/dogs/hero.jpg` |
| `public/media/services/boarding/hero-exotic.jpg` | `/media/services/boarding/hero-exotic.jpg` | Retain as redirect target | 200 | `/media/services/boarding/exotic/hero.jpg` |

Legacy redirect routes are a separate count from legacy files:

| Legacy URL | Handling | HTTP result | Canonical replacement |
| --- | --- | ---: | --- |
| `/service-hero-boarding-cats.jpg` | `Route::permanentRedirect` | 301 | `/media/services/boarding/hero-cats.jpg` |
| `/service-hero-boarding-dogs.jpg` | `Route::permanentRedirect` | 301 | `/media/services/boarding/hero-dogs.jpg` |
| `/service-hero-boarding-exotic.jpg` | `Route::permanentRedirect` | 301 | `/media/services/boarding/hero-exotic.jpg` |

## Orphan candidate classification and cleanup

The original candidate universe was exactly `532 total files - 237 unique canonical files - 70 editorial-tree legacy files = 225`. Primary states are mutually exclusive and were assigned before cleanup:

| Primary state | Count | Definition |
| --- | ---: | --- |
| CANONICAL | 0 | No canonical file was in the candidate set; all 237 were identified by the occurrence ledger |
| SHARED | 0 | No candidate was an active shared URL |
| LEGACY | 3 | The three physical redirect-target files listed above |
| PLACEHOLDER-UNUSED | 182 | 92 unused `related-*.jpg` files in the ten knowledge-base article namespaces plus 90 unused `related-*.jpg` files in the ten product namespaces; outside the rendered controller result sets |
| OBSOLETE | 40 | Confidently retired old page-specific/config fallback files listed below |
| UNKNOWN | 0 | No candidate remained unexplained |

The 182 `PLACEHOLDER-UNUSED` files and 40 `OBSOLETE` files were removed explicitly. No legacy file was removed. The 40 obsolete paths were:

```text
/media/about/hero-veterinary-care.jpg
/media/about/gallery/care-team.jpg
/media/about/gallery/groomed-cat.jpg
/media/about/gallery/pets-boarding-together.jpg
/media/guides/grooming-and-transport.jpg
/media/guides/preparing-pet-boarding.jpg
/media/home/boarding-puppy.jpg
/media/home/hero-dog-owner.jpg
/media/knowledge/cat-care.jpg
/media/knowledge/cat-veterinary-care.jpg
/media/knowledge/separation-anxiety.jpg
/media/services/boarding/boarding-cat.jpg
/media/services/boarding/boarding-dogs.jpg
/media/services/boarding/dogs.jpg
/media/services/boarding/exotic-pet-care.jpg
/media/services/boarding/cats/feature-01.jpg
/media/services/boarding/cats/feature-02.jpg
/media/services/boarding/cats/intro.jpg
/media/services/boarding/cats/related-cats.jpg
/media/services/boarding/dogs/feature-01.jpg
/media/services/boarding/dogs/feature-02.jpg
/media/services/boarding/dogs/gallery-01.jpg
/media/services/boarding/dogs/intro.jpg
/media/services/boarding/dogs/related-dogs.jpg
/media/services/boarding/exotic/daily-01.jpg
/media/services/boarding/exotic/daily-02.jpg
/media/services/boarding/exotic/daily-03.jpg
/media/services/boarding/exotic/daily-04.jpg
/media/services/boarding/exotic/related-exotic.jpg
/media/services/grooming/grooming-spa.jpg
/media/services/relocation/checklist.jpg
/media/services/relocation/pet-carrier.jpg
/media/services/relocation/pet-export.jpg
/media/services/relocation/pet-transport.jpg
/media/services/relocation/relocation-travel.jpg
/media/services/veterinary-care/veterinary-exam.jpg
/media/shop/products/chew-rope-toy.jpg
/media/shop/products/dog-harness.jpg
/media/shop/products/pet-supplies.jpg
/media/shop/products/puzzle-feeder.jpg
```

## Final `public/media/` tree

The final tree contains 310 JPG files: 237 active canonical files and 73 compatibility files. Counts below are file counts, not semantic-slot counts.

```text
public/media/
├── about/ (28: intro, grooming, veterinary-care, careers/team, testimonials/hero, gallery/gallery-01..23)
├── editorial/ (33 legacy files)
├── faq/ (1: hero)
├── guides/ (8: 4 index cards + 4 guide covers)
├── home/ (7: hero, care-standards, 5 service cards)
├── knowledge-base/ (28: 10 index cards + 10 covers + 8 rendered related files)
├── services/ (38: service roots, boarding parent, boarding child namespaces, grooming, relocation, training, vet-care)
│   ├── boarding/ (parent cards/hero + 3 legacy redirect targets)
│   ├── boarding/dogs/ (7 active files)
│   ├── boarding/cats/ (8 active files)
│   └── boarding/exotic/ (3 active files)
├── shared/editorial/ (37 legacy files)
└── shop/ (130: 10 catalogue cards + 10 product namespaces × 12 active files)
```

No active page-specific image remains in `knowledge/`, `shop/products/`, or a generic editorial catch-all. Every active page-specific file is traceable to the owning namespace shown in the ledger.

## Database, config, stale-reference, and URL verification

| Verification | Result |
| --- | --- |
| Database image references checked | 47 |
| Invalid database image references | 0 |
| Config image references checked | 62 unique concrete `/media/*.jpg` literals |
| Invalid config image references | 0 |
| Canonical local URLs | 237 |
| Tested | 237 |
| HTTP 200 | 237 |
| Canonical URL failures | 0 |
| Stale active references | 0 |
| Missing rendered slots | 0 |
| Unexpected rendered slots | 0 |
| Broken rendered local URLs | 0 |

The stale-reference search covered old filenames, old directories, deleted obsolete paths, legacy canonical paths, `shared/editorial`, and moved asset paths across the repository. The only remaining legacy-path matches are the intentional route declarations, redirect tests, and generated route snapshot for the three compatibility routes; they are not stale active references.

## Final application verification

These are the exact requested commands run after reconciliation:

| Command | Result |
| --- | --- |
| `php artisan db:seed --no-interaction` | PASS — `DevelopmentDatasetSeeder` completed |
| `php artisan waggies:search-rebuild --no-interaction` | PASS — synchronized 101 public search documents |
| `php artisan test` | PASS — 156 tests, 1,578 assertions, 262.66s |
| `./vendor/bin/phpstan analyse --memory-limit=512M` | PASS — 193/193, no errors |
| `./vendor/bin/pint --test` | PASS |

The final trace is deterministic: every occurrence row is `PAGE × SECTION × IMAGE SLOT → CURRENT REFERENCE → FINAL SEMANTIC ASSET → PHYSICAL FILE → FINAL PUBLIC URL`, and the URL was verified to exist and render with the expected page slot.
