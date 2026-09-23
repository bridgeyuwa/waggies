<?php

namespace App\Filament\Resources\ClinicalContents\Pages;

use App\Filament\Resources\ClinicalContents\ClinicalContentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClinicalContent extends CreateRecord
{
    protected static string $resource = ClinicalContentResource::class;
}
