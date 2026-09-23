<?php

namespace App\Filament\Resources\ClinicalSources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClinicalSourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->wrap(),
                TextColumn::make('organization')->searchable(),
                TextColumn::make('source_type')->badge(),
                TextColumn::make('jurisdiction')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('revised_on')->date(),
            ])
            ->filters([
                SelectFilter::make('status')->options(['active' => 'Active', 'superseded' => 'Superseded', 'withdrawn' => 'Withdrawn']),
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
