<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Models\Testimonial;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author_name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Testimonial::serviceOptions()[$state] ?? $state ?? '')
                    ->sortable(),
                TextColumn::make('story')
                    ->label('Testimonial')
                    ->limit(90)
                    ->wrap(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Testimonial::statusOptions()),
                SelectFilter::make('service')
                    ->options(Testimonial::serviceOptions()),
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
