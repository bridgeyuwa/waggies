<?php

namespace App\Filament\Resources\ClinicalReviews;

use App\Filament\Resources\ClinicalReviews\Pages\ListClinicalReviews;
use App\Filament\Resources\ClinicalReviews\Schemas\ClinicalReviewForm;
use App\Filament\Resources\ClinicalReviews\Tables\ClinicalReviewsTable;
use App\Models\ClinicalReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClinicalReviewResource extends Resource
{
    protected static ?string $model = ClinicalReview::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ClinicalReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicalReviewsTable::configure($table);
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
            'index' => ListClinicalReviews::route('/'),
        ];
    }
}
