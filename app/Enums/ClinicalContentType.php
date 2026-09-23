<?php

namespace App\Enums;

enum ClinicalContentType: string
{
    case Clinical = 'clinical';
    case NonClinical = 'non_clinical';
    case Mixed = 'mixed';
}
