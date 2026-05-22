<?php

namespace App\Filament\Resources\Enquiries\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('service')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('intent')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('message')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('read_at')
                    ->label('Read')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Unread'),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('unread')
                    ->label('Unread only')
                    ->query(fn (Builder $query) => $query->whereNull('read_at'))
                    ->default(),
            ])
            ->recordActions([
                Action::make('mark_as_read')
                    ->label('Mark as Read')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->hidden(fn ($record) => $record->read_at !== null)
                    ->action(fn ($record) => $record->markRead()),
                Action::make('mark_as_unread')
                    ->label('Mark as Unread')
                    ->icon(Heroicon::OutlinedArrowUturnLeft)
                    ->color('gray')
                    ->hidden(fn ($record) => $record->read_at === null)
                    ->action(fn ($record) => $record->update(['read_at' => null])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
