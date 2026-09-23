<?php

namespace App\Filament\Resources\ClinicalContents;

use App\Filament\Resources\ClinicalContents\Pages\EditClinicalContent;
use App\Filament\Resources\ClinicalContents\Pages\ListClinicalContents;
use App\Filament\Resources\ClinicalContents\Schemas\ClinicalContentForm;
use App\Filament\Resources\ClinicalContents\Tables\ClinicalContentsTable;
use App\Models\ClinicalContent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClinicalContentResource extends Resource
{
    protected static ?string $model = ClinicalContent::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ClinicalContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicalContentsTable::configure($table);
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
            'index' => ListClinicalContents::route('/'),
            'edit' => EditClinicalContent::route('/{record}/edit'),
        ];
    }
}
