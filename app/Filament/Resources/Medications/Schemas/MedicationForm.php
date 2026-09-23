<?php

namespace App\Filament\Resources\Medications\Schemas;

use App\Enums\MedicationClassification;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MedicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('generic_name')->required()->maxLength(255),
                TextInput::make('brand_name')->maxLength(255),
                TextInput::make('manufacturer')->maxLength(255),
                Select::make('classification')->options(MedicationClassification::class)->required(),
                KeyValue::make('active_ingredients')->keyLabel('Ingredient')->valueLabel('Details'),
                KeyValue::make('species'),
                KeyValue::make('indications'),
                KeyValue::make('routes'),
                KeyValue::make('warnings'),
                KeyValue::make('contraindications'),
                KeyValue::make('interactions'),
                TextInput::make('clinical_content_id')->numeric()->helperText('Only link to clinical content that has passed the publication gate.'),
            ]);
    }
}
