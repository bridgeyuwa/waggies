<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

final class ToolsController extends Controller
{
    public function index(): View
    {
        return view('pages.tools.index', $this->meta('Pet Care Tools - Waggies', 'Free pet care tools from Waggies: symptom checker, pet age calculator, nutrition calculator, emergency guide, breed finder, and more for pet owners in Abuja.', 'Pet Care Tools - Waggies', 'Free pet care tools: symptom checker, age calculator, nutrition calculator, emergency guide, and breed info finder.'));
    }

    public function symptomChecker(): View
    {
        return view('pages.tools.symptom-checker', $this->meta('Pet Symptom Checker - Waggies', "Check your pet's symptoms and get guidance on next steps. Our free symptom checker helps you understand what might be going on with your dog, cat, or other pet.", 'Pet Symptom Checker - Waggies', 'Check your pet\'s symptoms and get guidance on next steps.') + [
            'symptomChecker' => config('waggies_tools.symptom_checker'),
        ]);
    }

    public function vaccination(): View
    {
        return view('pages.tools.vaccination-schedule', $this->meta('Vaccination Schedule - Waggies', 'Track recommended vaccinations for your dog or cat. Our vaccination schedule shows core and non-core vaccines by age.', 'Vaccination Schedule - Waggies', 'Track recommended vaccinations for your dog or cat.') + [
            'schedules' => config('waggies_tools.vaccination'),
        ]);
    }

    public function parasite(): View
    {
        return view('pages.tools.parasite-schedule', $this->meta('Parasite & Deworming Schedule - Waggies - Waggies', 'Deworming schedule reference for puppies, kittens, and adult pets. Educational timeline with general parasite prevention guidance.', 'Parasite & Deworming Schedule - Waggies', 'Pet deworming and parasite prevention schedule reference.'));
    }

    public function emergency(): View
    {
        return view('pages.tools.emergency-guide', $this->meta('Emergency & Poison Guide - Waggies - Waggies', 'Pet emergency and poison reference guide for dogs and cats, with symptoms, immediate steps, and clear veterinary escalation.', 'Emergency & Poison Guide - Waggies', 'Quick-reference guide for common pet emergencies and poisons.'));
    }

    public function medication(): View
    {
        return view('pages.tools.medication-dosage-guide', $this->meta('Medication Dosage Guide - Waggies - Waggies', 'Veterinary-reviewed medication reference guide for common pet medications, dosage guidelines, and safety notes. Coming soon from Waggies.', 'Medication Dosage Guide - Waggies', 'Veterinary-reviewed pet medication reference. Dosage guidelines and safety notes.'));
    }

    public function petAge(): View
    {
        return view('pages.tools.pet-age-calculator', $this->meta('Pet Age Calculator', "Convert your dog or cat's age to human years. Our pet age calculator uses standard conversion tables based on your pet's size.", 'Pet Age Calculator - Waggies', "Convert your pet's age to human years with our free calculator."));
    }

    public function cost(): View
    {
        return view('pages.tools.cost-calculator', $this->meta('Cost Calculator', "Estimate costs for Waggies pet services including boarding, grooming, vet care, and training. Get a quick price range for your pet's needs.", 'Cost Calculator - Waggies', 'Estimate costs for Waggies pet services.'));
    }

    public function nutrition(): View
    {
        return view('pages.tools.nutrition-calculator', $this->meta('Diet & Nutrition Calculator - Waggies', "Calculate your pet's daily calorie needs using the RER formula. Free nutrition calculator for dogs and cats by weight, life stage, and activity level.", 'Diet & Nutrition Calculator - Waggies', "Estimate your pet's daily calorie needs with our RER-based nutrition calculator."));
    }

    public function breedFinder(): View
    {
        return view('pages.tools.breed-finder', $this->meta('Breed Info Finder - Waggies', 'Browse popular dog and cat breeds. Filter by size, energy, and grooming needs. General breed information and health considerations.', 'Breed Info Finder - Waggies', 'Search and filter popular dog and cat breeds with general breed information.') + [
            'breeds' => config('waggies_tool_data.reference.breeds', []),
        ]);
    }

    public function behaviorTips(): View
    {
        return view('pages.tools.behavior-tips', $this->meta('Behavior & Training Tips - Waggies', 'Practical pet behavior and training tips for dogs and cats. Educational guidance with clear escalation points.', 'Behavior & Training Tips - Waggies', 'General, non-diagnostic advice for common pet behavior issues.') + [
            'behaviorTips' => config('waggies_tool_data.reference.behavior', []),
        ]);
    }

    public function newPetChecklist(): View
    {
        return view('pages.tools.new-pet-checklist', $this->meta('New Pet Checklist - Waggies', 'Interactive new pet checklist. Everything you need before and after bringing a dog or cat home. Tracks progress in your browser.', 'New Pet Checklist - Waggies', 'Comprehensive interactive checklist for new pet owners. Covers preparation, first week, health, training, and supplies.') + [
            'checklist' => config('waggies_tools.new_pet_checklist'),
        ]);
    }

    private function meta(string $title, string $description, string $ogTitle, ?string $ogDescription = null): array
    {
        $metadata = [
            'navSection' => 'tools',
            'title' => $title,
            'description' => $description,
            'canonical' => route((string) request()->route()?->getName()),
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription ?? $description,
        ];

        $this->setPageHead($metadata);

        return $metadata;
    }
}
