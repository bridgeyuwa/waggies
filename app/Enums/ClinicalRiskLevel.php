<?php

namespace App\Enums;

enum ClinicalRiskLevel: string
{
    case Low = 'low';
    case Moderate = 'moderate';
    case High = 'high';
    case Critical = 'critical';
}
