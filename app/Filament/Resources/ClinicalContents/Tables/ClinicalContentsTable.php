<?php

namespace App\Filament\Resources\ClinicalContents\Tables;

use App\Enums\ClinicalContentStatus;
use App\Enums\ClinicalRiskLevel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClinicalContentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content_key')->searchable()->sortable(),
                TextColumn::make('clinical_status')->badge()->sortable(),
                TextColumn::make('publication_status')->badge()->sortable(),
                TextColumn::make('risk_level')->badge()->sortable(),
                TextColumn::make('jurisdiction')->sortable(),
                TextColumn::make('review_due_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('clinical_status')->options(collect(ClinicalContentStatus::cases())->mapWithKeys(fn ($case): array => [$case->value => $case->value])->all()),
                SelectFilter::make('risk_level')->options(collect(ClinicalRiskLevel::cases())->mapWithKeys(fn ($case): array => [$case->value => $case->value])->all()),
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
