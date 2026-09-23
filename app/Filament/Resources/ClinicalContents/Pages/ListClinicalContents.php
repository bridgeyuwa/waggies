<?php

namespace App\Filament\Resources\ClinicalContents\Pages;

use App\Filament\Resources\ClinicalContents\ClinicalContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClinicalContents extends ListRecords
{
    protected static string $resource = ClinicalContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
