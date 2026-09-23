<?php

use App\AI\WaggiesAssistant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('triages red flags without diagnosing or using symptom counts', function (): void {
    $this->get(route('tools.symptom-checker'))
        ->assertOk()
        ->assertSee('Emergency veterinary assessment', false)
        ->assertDontSee('Your pet has', false)
        ->assertDontSee('High Concern', false);
});

it('does not expose internal content labels or unsupported legal vaccine copy', function (): void {
    $this->get(route('tools.emergency'))
        ->assertOk()
        ->assertDontSee('SEED_CONTENT')
        ->assertDontSee('REQUIRES_CLINICAL_REVIEW');

    $this->get(route('tools.vaccination'))
        ->assertOk()
        ->assertDontSee('Required by law')
        ->assertDontSee('required by law');
});

it('fails safely for medication ingestion and dose questions before the model is called', function (): void {
    config()->set('services.waggies_ai.enabled', true);
    config()->set('services.waggies_ai.provider', 'openai');
    config()->set('ai.providers.openai.key', 'test-key');
    WaggiesAssistant::fake(['unsafe model output'])->preventStrayPrompts();

    $this->postJson(route('assistant.store'), ['message' => 'What dose of ibuprofen should I give my dog?'])
        ->assertOk()
        ->assertJsonPath('sources', [])
        ->assertJsonMissing(['message' => 'unsafe model output']);

    $this->postJson(route('assistant.store'), ['message' => 'My dog is having a seizure. What should I do?'])
        ->assertOk()
        ->assertJsonPath('sources', [])
        ->assertJsonMissing(['message' => 'unsafe model output']);
});

it('renders medication safety boundaries without a generic dose list', function (): void {
    $this->get(route('tools.medication'))
        ->assertOk()
        ->assertSee('Do not give human pain medicine without veterinary instruction')
        ->assertSee('Dose display gate')
        ->assertDontSee('Content coming soon');
});

it('labels nutrition output as an estimate rather than a prescription', function (): void {
    $this->get(route('tools.nutrition'))
        ->assertOk()
        ->assertSee('Estimated starting energy requirement', false)
        ->assertSee('not an exact prescription', false);
});
