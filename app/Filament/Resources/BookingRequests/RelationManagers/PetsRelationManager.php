<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use App\Models\BookingRequestPet;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PetsRelationManager extends RelationManager
{
    protected static string $relationship = 'pets';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('details.size')
                ->label('Dog size')
                ->options([
                    'small' => 'Small — up to 10kg',
                    'medium' => 'Medium — over 10kg through 25kg',
                    'large' => 'Large — over 25kg through 40kg',
                    'manual-review' => 'Above 40kg or unusual size — manual review',
                ])
                ->helperText('Customer guidance only. Confirm any revised price manually before final confirmation.')
                ->visible(fn (?BookingRequestPet $record): bool => $record?->species === 'dog'),
        ]);
    }

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
                TextColumn::make('details.size')
                    ->label('Dog size')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'small' => 'Small',
                        'medium' => 'Medium',
                        'large' => 'Large',
                        'manual-review' => 'Manual review',
                        default => 'Not specified',
                    }),
                TextColumn::make('breed')
                    ->label('Breed')
                    ->placeholder('Not specified'),
                TextColumn::make('age')
                    ->label('Age')
                    ->placeholder('Not specified'),
                TextColumn::make('sex')
                    ->label('Sex')
                    ->placeholder('Not specified'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
