<?php

namespace App\Filament\Resources\ClinicalSources\Pages;

use App\Filament\Resources\ClinicalSources\ClinicalSourceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClinicalSource extends CreateRecord
{
    protected static string $resource = ClinicalSourceResource::class;
}
