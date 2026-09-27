<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use App\Support\BookingPricingCatalog;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PetsRelationManager extends RelationManager
{
    protected static string $relationship = 'pets';

    public function form(Schema $schema): Schema
    {
        $sizeOptions = app(BookingPricingCatalog::class)->sizeOptions('boarding', 'dogs');

        return $schema
            ->components([
                TextInput::make('name')->label('Name')->disabled()->dehydrated(false),
                TextInput::make('species')->label('Species')->disabled()->dehydrated(false),
                Select::make('details.size')
                    ->label('Boarding size guidance')
                    ->options(collect($sizeOptions)->mapWithKeys(static fn (array $option, string $key): array => [$key => $option['label']])->all())
                    ->helperText('Staff may correct the customer-selected size during review. Any revised price is confirmed manually.'),
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
                TextColumn::make('breed')
                    ->label('Breed')
                    ->placeholder('Not specified'),
                TextColumn::make('age')
                    ->label('Age')
                    ->placeholder('Not specified'),
                TextColumn::make('sex')
                    ->label('Sex')
                    ->placeholder('Not specified'),
                TextColumn::make('details.size')
                    ->label('Boarding size')
                    ->placeholder('Not selected'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
