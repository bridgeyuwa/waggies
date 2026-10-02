<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('public tool pages', [
    'tool index' => ['tools.index', 'Pet Care Tools'],
    'symptom checker' => ['tools.symptom-checker', 'Pet Symptom Triage'],
    'vaccination schedule' => ['tools.vaccination', 'Vaccination Planning Guide'],
    'parasite schedule' => ['tools.parasite', 'Parasite & Deworming Schedule'],
    'emergency guide' => ['tools.emergency', 'Emergency & Poison Guide'],
    'pet age calculator' => ['tools.pet-age', 'Pet Age Calculator'],
    'nutrition calculator' => ['tools.nutrition', 'Diet & Nutrition Calculator'],
    'breed finder' => ['tools.breed-finder', 'Breed Info Finder'],
    'behavior tips' => ['tools.behavior-tips', 'Behavior & Training Tips'],
    'new pet checklist' => ['tools.new-pet-checklist', 'New Pet Checklist'],
]);

it('renders every public tool page with its canonical content', function (string $routeName, string $heading): void {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($heading);
})->with('public tool pages');

it('keeps the legacy cost calculator route as a permanent pricing redirect', function (): void {
    $this->get(route('tools.cost'))
        ->assertStatus(301)
        ->assertRedirect(route('services.pricing'));
});

dataset('legal pages', [
    'boarding policy' => ['boarding-policy', 'Boarding Requirements & Admission'],
    'cancellation policy' => ['cancellation-policy', 'Cancellation, Rescheduling & Refunds'],
    'relocation policy' => ['relocation-policy', 'Relocation Policy'],
    'privacy policy' => ['privacy-policy', 'Privacy Policy'],
    'terms of service' => ['terms-of-service', 'Terms of Service'],
    'cookies policy' => ['cookies-policy', 'Cookies Policy'],
]);

it('renders every legal page with a canonical title and route', function (string $routeName, string $heading): void {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($heading)
        ->assertSee('rel="canonical"', false)
        ->assertSee(route($routeName), false);
})->with('legal pages');
