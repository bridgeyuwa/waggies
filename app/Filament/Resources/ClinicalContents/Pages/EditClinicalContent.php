<?php

namespace App\Filament\Resources\ClinicalContents\Pages;

use App\Filament\Resources\ClinicalContents\ClinicalContentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClinicalContent extends EditRecord
{
    protected static string $resource = ClinicalContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
