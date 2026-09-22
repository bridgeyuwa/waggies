<?php

namespace App\Filament\Resources\KnowledgeArticles\Tables;

use App\Models\KnowledgeArticle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KnowledgeArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Cover')
                    ->state(fn (KnowledgeArticle $record): string => $record->publicImageUrl('thumb'))
                    ->alt(fn (KnowledgeArticle $record): string => $record->image_alt ?: $record->title)
                    ->square()
                    ->size(56),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (KnowledgeArticle $record): string => $record->slug),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(KnowledgeArticle::statusOptions()),
                SelectFilter::make('category')
                    ->options(array_combine(KnowledgeArticle::categoryOptions(), KnowledgeArticle::categoryOptions())),
            ])
            ->defaultSort('sort_order')
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
