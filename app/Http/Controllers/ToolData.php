<?php

namespace App\Http\Controllers;

final class ToolData
{
    /**
     * @return list<array{id: string, name: string, route: string, icon: string, description: string, longDescription: string, category: string}>
     */
    public static function catalogue(): array
    {
        return [
            ['id' => 'symptom-checker', 'name' => 'Pet Symptom Checker', 'route' => 'tools.symptom-checker', 'icon' => 'medical', 'description' => 'Select symptoms by body area and get general guidance on next steps.', 'longDescription' => "An interactive tool to help you identify potential health concerns by selecting your pet's species, the affected body area, and specific symptoms you notice.", 'category' => 'health'],
            ['id' => 'emergency-guide', 'name' => 'Emergency & Poison Guide', 'route' => 'tools.emergency', 'icon' => 'emergency', 'description' => 'Quick-reference for common pet emergencies, poisons, and what to do.', 'longDescription' => 'A reference guide covering poisonous foods, household dangers, heatstroke, choking, seizures, and bleeding - with immediate action steps and emergency contacts.', 'category' => 'health'],
            ['id' => 'parasite-schedule', 'name' => 'Parasite & Deworming Schedule', 'route' => 'tools.parasite', 'icon' => 'parasite', 'description' => 'Educational timeline for deworming puppies, kittens, and adult pets.', 'longDescription' => 'A general educational timeline showing recommended deworming intervals for puppies, kittens, adult dogs, and adult cats, with notes on travel and veterinary confirmation.', 'category' => 'health'],
            ['id' => 'pet-age-calculator', 'name' => 'Pet Age Calculator', 'route' => 'tools.pet-age', 'icon' => 'pets', 'description' => "Convert your pet's age to approximate human years.", 'longDescription' => "Convert your dog or cat's age into approximate human years using species-specific conversion formulas that account for size and life stage.", 'category' => 'calculator'],
            ['id' => 'nutrition-calculator', 'name' => 'Diet & Nutrition Calculator', 'route' => 'tools.nutrition', 'icon' => 'nutrition', 'description' => "Estimate your pet's daily calorie needs based on weight, species, and activity level.", 'longDescription' => "Calculate your dog or cat's estimated daily calorie requirement using the RER formula, adjusted for life stage and activity level.", 'category' => 'calculator'],
            ['id' => 'vaccination-schedule', 'name' => 'Vaccination Schedule', 'route' => 'tools.vaccination', 'icon' => 'vaccination', 'description' => 'Track recommended vaccinations for dogs and cats by age.', 'longDescription' => 'An interactive vaccination schedule tracker showing recommended vaccines for puppies, kittens, adult dogs, and adult cats at each life stage.', 'category' => 'reference'],
            ['id' => 'breed-finder', 'name' => 'Breed Info Finder', 'route' => 'tools.breed-finder', 'icon' => 'search', 'description' => 'Browse popular dog and cat breeds with size, energy, grooming, and temperament info.', 'longDescription' => 'Search and filter popular dog and cat breeds by size, energy level, and grooming needs. General breed information including temperament and common health considerations.', 'category' => 'reference'],
            ['id' => 'behavior-tips', 'name' => 'Behavior & Training Tips', 'route' => 'tools.behavior-tips', 'icon' => 'behavior', 'description' => 'Practical advice for common pet behavior issues like barking, anxiety, and house training.', 'longDescription' => 'General, non-diagnostic advice for common behavior issues in dogs and cats, including barking, jumping, anxiety, aggression, house training, and destructive chewing.', 'category' => 'reference'],
            ['id' => 'new-pet-checklist', 'name' => 'New Pet Checklist', 'route' => 'tools.new-pet-checklist', 'icon' => 'checklist', 'description' => 'Interactive checklist of everything you need before and after bringing a pet home.', 'longDescription' => 'A comprehensive interactive checklist covering preparation, first week essentials, health and wellness, training, and supplies for new pet owners. Saves progress in your browser.', 'category' => 'utility'],
        ];
    }
}
