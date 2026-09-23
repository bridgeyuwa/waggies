<?php

namespace App\Filament\Resources\ClinicalSources\Pages;

use App\Filament\Resources\ClinicalSources\ClinicalSourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClinicalSources extends ListRecords
{
    protected static string $resource = ClinicalSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
