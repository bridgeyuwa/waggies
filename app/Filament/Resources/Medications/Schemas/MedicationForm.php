<?php

namespace App\Filament\Resources\Medications\Schemas;

use App\Enums\MedicationClassification;
use App\Models\ClinicalContent;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class MedicationForm
{
    public static function configure(Schema $schema): Schema
    {
        /**
         * @param  Builder<ClinicalContent>  $query
         * @return Builder<ClinicalContent>
         */
        $publicClinicalContentQuery = static function (Builder $query): Builder {
            return ClinicalContent::applyPublicScope($query);
        };

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
                Select::make('clinical_content_id')
                    ->label('Governed clinical content')
                    ->relationship(
                        name: 'clinicalContent',
                        titleAttribute: 'content_key',
                        modifyQueryUsing: $publicClinicalContentQuery,
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Optional. Choose approved, published clinical content; do not enter a database ID.'),
            ]);
    }
}
