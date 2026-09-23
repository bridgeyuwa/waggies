<?php

namespace App\Filament\Resources\BusinessProfiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BusinessProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business_name')->label('Business')->searchable(),
                TextColumn::make('primary_email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('address_city')->label('City'),
                TextColumn::make('timezone')->label('Timezone'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([])]);
    }
}
