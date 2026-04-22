<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->badge()
                    ->searchable(),
                TextColumn::make('subcategory')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('question')
                    ->searchable()
                    ->limit(70),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'boarding'   => 'Boarding',
                        'grooming'   => 'Grooming',
                        'relocation' => 'Relocation',
                        'vet-care'   => 'Vet Care',
                        'training'   => 'Training',
                        'transport'  => 'Transport',
                        'general'    => 'General',
                    ]),
                Filter::make('active')
                    ->label('Active only')
                    ->query(fn (Builder $query) => $query->where('is_active', true))
                    ->default(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
