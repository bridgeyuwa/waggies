<?php

namespace App\Filament\Resources\ClinicalSources;

use App\Filament\Resources\ClinicalSources\Pages\CreateClinicalSource;
use App\Filament\Resources\ClinicalSources\Pages\EditClinicalSource;
use App\Filament\Resources\ClinicalSources\Pages\ListClinicalSources;
use App\Filament\Resources\ClinicalSources\Schemas\ClinicalSourceForm;
use App\Filament\Resources\ClinicalSources\Tables\ClinicalSourcesTable;
use App\Models\ClinicalSource;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClinicalSourceResource extends Resource
{
    protected static ?string $model = ClinicalSource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ClinicalSourceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicalSourcesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClinicalSources::route('/'),
            'create' => CreateClinicalSource::route('/create'),
            'edit' => EditClinicalSource::route('/{record}/edit'),
        ];
    }
}
