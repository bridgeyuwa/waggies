<?php

namespace App\Filament\Resources\BusinessHours\Tables;

use App\Models\BusinessHour;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BusinessHoursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kind')->formatStateUsing(fn (?string $state): string => $state === BusinessHour::KIND_EXCEPTION ? 'Exception' : 'Weekly')->badge(),
                TextColumn::make('day_of_week')->label('Day')->formatStateUsing(fn (?int $state, BusinessHour $record): string => $record->kind === BusinessHour::KIND_EXCEPTION ? 'Date exception' : (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$state ?? 0] ?? '')),
                TextColumn::make('date')->label('Date / range')->state(fn (BusinessHour $record): string => $record->kind === BusinessHour::KIND_EXCEPTION ? $record->dateLabel() : 'Weekly'),
                TextColumn::make('recurrence')->label('Repeats')->formatStateUsing(fn (?string $state): string => $state === BusinessHour::RECURRENCE_YEARLY ? 'Every year' : 'One time')->placeholder('—'),
                TextColumn::make('displayLabel')->label('Hours')->state(fn (BusinessHour $record): string => $record->displayLabel()),
                TextColumn::make('label')->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('kind')->options([
                    BusinessHour::KIND_WEEKLY => 'Weekly schedule',
                    BusinessHour::KIND_EXCEPTION => 'Date exception',
                ]),
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
