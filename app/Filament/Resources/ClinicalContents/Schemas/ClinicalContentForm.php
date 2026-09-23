<?php

namespace App\Filament\Resources\ClinicalContents\Schemas;

use App\Enums\ClinicalContentStatus;
use App\Enums\ClinicalContentType;
use App\Enums\ClinicalPublicationStatus;
use App\Enums\ClinicalRiskLevel;
use App\Enums\Jurisdiction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClinicalContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Clinical governance')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content_key')->required()->unique(ignoreRecord: true),
                        Select::make('content_type')->options(collect(ClinicalContentType::cases())->mapWithKeys(fn (ClinicalContentType $case): array => [$case->value => str($case->value)->replace('_', ' ')->title()->toString()])->all())->required(),
                        Select::make('clinical_status')->options(ClinicalContentStatus::class)->required(),
                        Select::make('publication_status')->options(ClinicalPublicationStatus::class)->required(),
                        Select::make('risk_level')->options(ClinicalRiskLevel::class)->required(),
                        Select::make('jurisdiction')->options(Jurisdiction::class)->required(),
                        TextInput::make('version')->numeric()->minValue(1)->required(),
                        DateTimePicker::make('review_due_at'),
                    ]),
                    Toggle::make('source_conflict')->label('Source conflict')->helperText('Conflicts block publication until resolved.'),
                    Textarea::make('conflict_notes')->rows(3),
                    Textarea::make('metadata')->rows(4)->helperText('Internal metadata only; do not store private patient information.'),
                ])->columnSpanFull(),
            ]);
    }
}
