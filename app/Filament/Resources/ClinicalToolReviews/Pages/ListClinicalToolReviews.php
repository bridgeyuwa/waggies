<?php

namespace App\Filament\Resources\ClinicalToolReviews\Pages;

use App\Filament\Resources\ClinicalToolReviews\ClinicalToolReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClinicalToolReviews extends ListRecords
{
    protected static string $resource = ClinicalToolReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
