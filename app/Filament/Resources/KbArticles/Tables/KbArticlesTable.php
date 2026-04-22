<?php

namespace App\Filament\Resources\KbArticles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KbArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->badge()
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(60),
                IconColumn::make('featured')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'boarding'  => 'Boarding & Stays',
                        'nutrition' => 'Nutrition & Feeding',
                        'health'    => 'Health & Wellness',
                        'grooming'  => 'Grooming',
                        'training'  => 'Training & Behaviour',
                        'daycare'   => 'Daycare',
                        'general'   => 'General',
                    ]),
                Filter::make('featured')
                    ->query(fn (Builder $query) => $query->where('featured', true)),
                Filter::make('published')
                    ->label('Published only')
                    ->query(fn (Builder $query) => $query->whereNotNull('published_at')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }
}
