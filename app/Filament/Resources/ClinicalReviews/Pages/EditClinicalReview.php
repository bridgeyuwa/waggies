<?php

namespace App\Filament\Resources\ClinicalReviews\Pages;

use App\Filament\Resources\ClinicalReviews\ClinicalReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClinicalReview extends EditRecord
{
    protected static string $resource = ClinicalReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
