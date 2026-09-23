<?php

namespace App\Filament\Resources\ClinicalReviews\Schemas;

use App\Enums\ClinicalReviewDecision;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClinicalReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('clinical_content_id')
                    ->required()
                    ->numeric(),
                TextInput::make('clinical_source_id')
                    ->numeric(),
                Select::make('reviewer_id')
                    ->relationship('reviewer', 'name'),
                TextInput::make('reviewer_name'),
                TextInput::make('reviewer_credential'),
                TextInput::make('organization'),
                TextInput::make('review_reference'),
                Select::make('decision')
                    ->options(ClinicalReviewDecision::class)
                    ->required(),
                Textarea::make('review_notes')
                    ->columnSpanFull(),
                DateTimePicker::make('reviewed_at'),
                DateTimePicker::make('next_review_at'),
                TextInput::make('content_version')
                    ->required()
                    ->numeric(),
                TextInput::make('source_version'),
            ]);
    }
}
