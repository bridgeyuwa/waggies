<?php

namespace App\Filament\Resources\ClinicalSources\Schemas;

use App\Enums\ClinicalSourceType;
use App\Enums\Jurisdiction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClinicalSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('organization')->maxLength(255),
                TextInput::make('author')->maxLength(255),
                Select::make('source_type')->options(ClinicalSourceType::class)->required(),
                TextInput::make('reference')->url()->required()->columnSpanFull(),
                Select::make('jurisdiction')->options(Jurisdiction::class)->required(),
                TextInput::make('topic')->maxLength(255),
                TextInput::make('version')->maxLength(255),
                TextInput::make('identifier')->maxLength(255),
                TextInput::make('evidence_level')->maxLength(40),
                DatePicker::make('published_on'),
                DatePicker::make('revised_on'),
                DateTimePicker::make('accessed_at'),
                Select::make('status')->options(['active' => 'Active', 'superseded' => 'Superseded', 'withdrawn' => 'Withdrawn'])->required(),
                Toggle::make('has_conflict')->label('Source conflict'),
                Textarea::make('notes')->rows(4)->columnSpanFull(),
            ]);
    }
}
