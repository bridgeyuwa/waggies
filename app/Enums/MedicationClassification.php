<?php

namespace App\Enums;

enum MedicationClassification: string
{
    case VeterinaryPrescription = 'veterinary_prescription';
    case VeterinaryNonPrescription = 'veterinary_non_prescription';
    case HumanPrescription = 'human_prescription';
    case HumanNonPrescription = 'human_non_prescription';
    case ControlledRestricted = 'controlled_restricted';
    case Unknown = 'unknown';
    case NotAppropriate = 'not_appropriate_for_routine_pet_use';
}
