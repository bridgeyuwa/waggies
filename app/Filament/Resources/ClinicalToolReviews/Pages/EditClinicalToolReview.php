<?php

namespace App\Filament\Resources\ClinicalToolReviews\Pages;

use App\Filament\Resources\ClinicalToolReviews\ClinicalToolReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClinicalToolReview extends EditRecord
{
    protected static string $resource = ClinicalToolReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
