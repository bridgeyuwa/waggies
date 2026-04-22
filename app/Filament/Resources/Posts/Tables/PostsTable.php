<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'guide' => 'info',
                        default => 'primary',
                    })
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('author')
                    ->searchable(),
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
                SelectFilter::make('type')
                    ->options([
                        'blog'  => 'Blog Post',
                        'guide' => 'Guide',
                    ]),
                SelectFilter::make('category')
                    ->options([
                        'nutrition'  => 'Nutrition',
                        'health'     => 'Health & Wellness',
                        'training'   => 'Training & Behaviour',
                        'grooming'   => 'Grooming',
                        'boarding'   => 'Boarding & Care',
                        'general'    => 'General',
                    ]),
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
