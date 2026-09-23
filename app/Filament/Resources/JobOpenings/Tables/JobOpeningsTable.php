<?php

namespace App\Filament\Resources\JobOpenings\Tables;

use App\Models\JobOpening;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JobOpeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('department')->searchable()->sortable(),
                TextColumn::make('employment_type')->label('Type')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('closing_date')->date()->sortable(),
                TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(JobOpening::statusOptions()),
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
