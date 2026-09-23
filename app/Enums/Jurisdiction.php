<?php

namespace App\Enums;

enum Jurisdiction: string
{
    case Nigeria = 'Nigeria';
    case UnitedStates = 'United States';
    case Global = 'Global';
    case Other = 'Other';
}
