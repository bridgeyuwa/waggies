<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->state(fn (Product $record): string => $record->publicImageUrl('thumb'))
                    ->alt(fn (Product $record): string => $record->image_alt ?: $record->name)
                    ->square()
                    ->size(56),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('NGN')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('availability')
                    ->label('Availability')
                    ->formatStateUsing(fn (?string $state): string => Product::availabilityOptions()[$state] ?? (string) $state)
                    ->badge()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Product::statusOptions()),
                SelectFilter::make('category')
                    ->options(fn (): array => Product::categoryOptions()),
                SelectFilter::make('availability')
                    ->options(Product::availabilityOptions()),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
