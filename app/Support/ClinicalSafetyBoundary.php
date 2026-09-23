<?php

namespace App\Support;

use Illuminate\Support\Str;

final class ClinicalSafetyBoundary
{
    public function responseFor(string $message): ?string
    {
        $message = Str::lower($message);

        if (Str::contains($message, ['seizure', 'difficulty breathing', 'cannot breathe', 'collapse', 'severe bleeding', 'unable to urinate', 'urinary obstruction', 'major trauma'])) {
            return 'This may be an emergency. Contact a veterinarian or emergency veterinary service immediately and follow their instructions while transporting your pet. Do not wait for an online answer to replace urgent assessment.';
        }

        if (Str::contains($message, ['xylitol', 'chocolate', 'poison', 'poisoning', 'ate ibuprofen', 'ate naproxen', 'ate paracetamol', 'ate acetaminophen'])) {
            return 'Treat suspected poisoning or dangerous medication ingestion as urgent. Contact a veterinarian immediately, keep the packaging or a photo, and note the substance, strength, amount, time, route, species, weight, and current signs if known. Do not induce vomiting or give food, milk, oil, charcoal, antidotes, or human medicine unless a veterinarian directs you.';
        }

        if (Str::contains($message, ['what dose', 'dosage', 'how much medicine', 'give my cat human pain', 'give my dog human pain', 'human pain medicine'])) {
            return 'I cannot calculate or recommend a pet medication dose from a chat message. Formulation, concentration, species, indication, weight, health history, jurisdiction, and veterinary review all matter. Do not give human pain medicine unless a veterinarian specifically instructs you; contact a veterinarian for patient-specific advice.';
        }

        if (Str::contains($message, ['diagnose', 'does my dog have', 'does my cat have'])) {
            return 'I cannot diagnose a pet from a chat. Arrange veterinary assessment, especially if the signs are severe, worsening, or affecting breathing, consciousness, eating, drinking, or urination.';
        }

        return null;
    }
}
