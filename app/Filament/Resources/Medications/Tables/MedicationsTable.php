<?php

namespace App\Filament\Resources\Medications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MedicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('generic_name')->searchable()->sortable(),
                TextColumn::make('brand_name')->searchable(),
                TextColumn::make('classification')->badge(),
                TextColumn::make('clinicalContent.clinical_status')->label('Clinical status')->badge(),
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
