<?php

namespace App\Filament\Resources\ClinicalSources\Pages;

use App\Filament\Resources\ClinicalSources\ClinicalSourceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClinicalSource extends EditRecord
{
    protected static string $resource = ClinicalSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
