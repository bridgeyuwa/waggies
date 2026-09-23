<?php

namespace App\Filament\Resources\ClinicalToolReviews;

use App\Filament\Resources\ClinicalToolReviews\Pages\CreateClinicalToolReview;
use App\Filament\Resources\ClinicalToolReviews\Pages\EditClinicalToolReview;
use App\Filament\Resources\ClinicalToolReviews\Pages\ListClinicalToolReviews;
use App\Filament\Resources\ClinicalToolReviews\Schemas\ClinicalToolReviewForm;
use App\Filament\Resources\ClinicalToolReviews\Tables\ClinicalToolReviewsTable;
use App\Models\ClinicalToolReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClinicalToolReviewResource extends Resource
{
    protected static ?string $model = ClinicalToolReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ClinicalToolReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicalToolReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClinicalToolReviews::route('/'),
            'create' => CreateClinicalToolReview::route('/create'),
            'edit' => EditClinicalToolReview::route('/{record}/edit'),
        ];
    }
}
