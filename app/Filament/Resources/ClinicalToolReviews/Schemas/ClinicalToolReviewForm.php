<?php

namespace App\Filament\Resources\ClinicalToolReviews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClinicalToolReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tool_key')
                    ->required(),
                TextInput::make('clinical_status')
                    ->required()
                    ->default('draft'),
                TextInput::make('risk_level')
                    ->required()
                    ->default('moderate'),
                TextInput::make('jurisdiction')
                    ->required()
                    ->default('Global'),
                TextInput::make('version')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('clinical_source_id')
                    ->numeric(),
                DateTimePicker::make('reviewed_at'),
                DateTimePicker::make('review_due_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
