<?php

namespace App\Filament\Resources\GalleryItems\Tables;

use App\Models\GalleryItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GalleryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->state(fn (GalleryItem $record): string => $record->publicImageUrl('thumb'))
                    ->alt(fn (GalleryItem $record): string => $record->image_alt)
                    ->square()
                    ->size(56),
                TextColumn::make('image_alt')
                    ->label('Alt text')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(GalleryItem::statusOptions()),
                SelectFilter::make('category')
                    ->options(fn (): array => GalleryItem::query()->distinct()->orderBy('category')->pluck('category', 'category')->all()),
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
