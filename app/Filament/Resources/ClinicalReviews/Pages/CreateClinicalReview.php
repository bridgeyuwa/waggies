<?php

namespace App\Filament\Resources\ClinicalReviews\Pages;

use App\Filament\Resources\ClinicalReviews\ClinicalReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClinicalReview extends CreateRecord
{
    protected static string $resource = ClinicalReviewResource::class;
}
