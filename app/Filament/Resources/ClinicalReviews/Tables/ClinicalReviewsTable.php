<?php

namespace App\Filament\Resources\ClinicalReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClinicalReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content.content_key')
                    ->label('Clinical content')
                    ->sortable(),
                TextColumn::make('source.title')
                    ->label('Clinical source')
                    ->sortable(),
                TextColumn::make('reviewer.name')
                    ->searchable(),
                TextColumn::make('reviewer_name')
                    ->searchable(),
                TextColumn::make('reviewer_credential')
                    ->searchable(),
                TextColumn::make('organization')
                    ->searchable(),
                TextColumn::make('review_reference')
                    ->searchable(),
                TextColumn::make('decision')
                    ->badge()
                    ->searchable(),
                TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('next_review_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('content_version')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('source_version')
                    ->searchable(),
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
                //
            ])
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
