<?php

namespace App\Filament\Resources\ClinicalReviews\Pages;

use App\Filament\Resources\ClinicalReviews\ClinicalReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListClinicalReviews extends ListRecords
{
    protected static string $resource = ClinicalReviewResource::class;
}
