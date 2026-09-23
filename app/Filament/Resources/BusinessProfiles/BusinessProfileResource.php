<?php

namespace App\Filament\Resources\BusinessProfiles;

use App\Filament\Resources\BusinessProfiles\Pages\EditBusinessProfile;
use App\Filament\Resources\BusinessProfiles\Pages\ListBusinessProfiles;
use App\Filament\Resources\BusinessProfiles\Schemas\BusinessProfileForm;
use App\Filament\Resources\BusinessProfiles\Tables\BusinessProfilesTable;
use App\Models\BusinessProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusinessProfileResource extends Resource
{
    protected static ?string $model = BusinessProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Business profile';

    public static function form(Schema $schema): Schema
    {
        return BusinessProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessProfilesTable::configure($table);
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
            'index' => ListBusinessProfiles::route('/'),
            'edit' => EditBusinessProfile::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
