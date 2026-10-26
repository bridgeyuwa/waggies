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
        $vetCareRequestUrl = route('book', ['service' => 'vet-care', 'source' => 'knowledge-base'], false);

        DB::table('knowledge_articles')
            ->where('slug', 'best-nutrition-tips-nigerian-pets')
            ->where('title', 'Nutrition Tips for Nigerian Pet Owners')
            ->where('status', 'published')
            ->update([
                'title' => 'Dog Nutrition Tips for Nigerian Pet Owners',
                'excerpt' => 'Good feeding starts with a diet that suits your dog. Learn how to choose food, manage portions and treats, and support a healthy weight.',
                'seo_title' => 'Dog Nutrition Guide: Food and Feeding Tips | Waggies',
                'seo_description' => 'Make informed choices about your dog’s food, portions and treats, and learn when to ask a vet for feeding advice tailored to your dog.',
                'read_time' => '3 min read',
                'content' => str_replace('__VET_CARE_REQUEST_URL__', $vetCareRequestUrl, <<<'HTML'
<p>Good nutrition depends on the dog, the food and how it is fed. Age, size, activity and health all affect a dog’s needs. This guide offers general starting points; a veterinarian can help tailor food and feeding routines to your dog.</p>

<h2>Choosing a complete diet</h2>
<p>A dog’s nutritional needs vary with age, size, activity and health. Choose food intended for dogs and suitable for your dog’s life stage. Check whether the package describes it as a complete daily diet or as a complementary food or topper.</p>

<p>Use the feeding directions on the package as a starting point. If the label doesn’t make the food’s intended use clear, ask the manufacturer or your veterinarian.</p>

<h2>Understanding food labels</h2>
<p>The ingredient list alone can’t tell you whether a food is right for your dog. Check the life stage, feeding directions and nutritional information on the package. Words such as “premium” or a named meat near the top of the ingredients list don’t, on their own, prove that a food is better.</p>

<p>If you’re unsure what to choose, ask your veterinarian about your dog’s age, activity, health and body condition. They can help you decide whether a food is suitable for your dog’s needs.</p>

<h2>Home-cooked food, treats and foods to avoid</h2>
<p>Plain cooked chicken, egg or vegetables may suit some dogs as small additions, but these ingredients do not make a balanced daily diet. If you want to feed home-cooked meals regularly, ask a veterinarian to help you plan a complete diet for your dog.</p>

<p>Keep chocolate, grapes and raisins, onions, garlic, and products containing xylitol away from dogs. Avoid giving seasoned leftovers or cooked bones. If your dog eats something that may be harmful, contact a veterinarian promptly.</p>

<h2>Portions and feeding routine</h2>
<p>The amount to feed depends on the food, your dog’s life stage, activity, health and body condition. Measure the daily portion and include treats and extras when considering how much your dog eats. Puppies may need more frequent meals than adult dogs; ask your veterinarian if you’re unsure about a suitable routine.</p>

<h2>Supporting a healthy weight</h2>
<p>Check your dog’s weight and body condition regularly. If you’re unsure whether your dog is too thin or carrying extra weight, ask your veterinarian to assess them. Avoid making a large change to food portions or exercise without advice tailored to your dog.</p>

<h2>Changing and storing food</h2>
<p>Change foods gradually unless your veterinarian advises otherwise. A slow transition can help reduce the chance of stomach upset. If your dog has a medical condition or develops digestive problems during a change, check with your veterinarian.</p>

<p>Store dry food according to the package directions in a cool, dry place. Keep it in its original bag, sealed, so the product and batch details remain available. Follow the label’s instructions for opened wet food and wash food bowls and scoops regularly.</p>

<h2>When to ask a veterinarian</h2>
<p>Get veterinary advice before changing the diet of a puppy, an older dog, or a dog with a health condition. Ask for help if your dog’s weight changes unexpectedly, stops eating, or develops ongoing digestive problems.</p>

<p>If you want to discuss your dog’s diet with Waggies veterinary care in Abuja, <a href="__VET_CARE_REQUEST_URL__">contact the team</a>. They’ll review your request and confirm the next step.</p>
HTML,
                ),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('knowledge_articles')
            ->where('slug', 'best-nutrition-tips-nigerian-pets')
            ->where('title', 'Dog Nutrition Tips for Nigerian Pet Owners')
            ->where('seo_title', 'Dog Nutrition Guide: Food and Feeding Tips | Waggies')
            ->update([
                'title' => 'Nutrition Tips for Nigerian Pet Owners',
                'excerpt' => "Feeding your pet well doesn't have to be complicated. A practical guide to balanced nutrition using locally available foods and quality commercial diets.",
                'seo_title' => null,
                'seo_description' => null,
                'read_time' => '5 min read',
                'content' => <<<'HTML'
<h2>Building a Balanced Diet</h2>
<p>Good nutrition is the foundation of your pet's health. Whether you feed a commercial diet, home-cooked meals, or a combination, the goal is the same: provide the right balance of proteins, fats, carbohydrates, vitamins, and minerals your pet needs to thrive in Nigeria's climate.</p>
<h2>Understanding Pet Food Labels</h2>
<p>Not all pet foods are created equal. Look for products that list a specific animal protein (chicken, fish, or lamb) as the first ingredient. Avoid foods where "meat meal" or "by-products" appear at the top of the ingredient list. Reputable brands available in Nigeria include Royal Canin, Hill's, and Eukanuba, which formulate for different life stages and breed sizes.</p>
<h2>Local Foods Your Dog Can Safely Eat</h2>
<p>Many Nigerian households supplement commercial food with home-cooked meals. Safe options include boiled chicken (no bones or seasoning), sweet potatoes, carrots, green beans, and plain boiled rice. Eggs are an excellent protein source and can be fed cooked. Avoid onions, garlic, chocolate, grapes, and anything seasoned with salt or spices common in Nigerian cooking.</p>
<h2>Feeding Schedules</h2>
<p>Adult dogs do well with two meals per day, while puppies under six months need three to four smaller meals. Establish consistent feeding times and remove uneaten food after 20 minutes to prevent bacterial growth in Abuja's heat. Always ensure fresh, clean water is available throughout the day.</p>
<h2>Weight Management</h2>
<p>Obesity is a growing problem among urban pets in Nigeria. You should be able to feel your dog's ribs without pressing hard, and they should have a visible waist when viewed from above. If your pet is gaining weight, reduce portion sizes by 10% and increase daily exercise gradually.</p>
HTML,
                'updated_at' => now(),
            ]);
    }
};
