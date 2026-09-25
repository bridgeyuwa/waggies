<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PetsRelationManager extends RelationManager
{
    protected static string $relationship = 'pets';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('species')
                    ->label('Species')
                    ->placeholder('Not specified'),
                TextColumn::make('breed')
                    ->label('Breed')
                    ->placeholder('Not specified'),
                TextColumn::make('age')
                    ->label('Age')
                    ->placeholder('Not specified'),
                TextColumn::make('sex')
                    ->label('Sex')
                    ->placeholder('Not specified'),
            ]);
    }
}
