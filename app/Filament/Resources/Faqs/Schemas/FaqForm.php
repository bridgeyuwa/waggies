<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Models\Faq;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQ content')
                    ->schema([
                        TextInput::make('question')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('answer')
                            ->required()
                            ->rows(8)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Placement and publication')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('category')
                                ->options(Faq::categoryOptions())
                                ->required(),
                            TextInput::make('subcategory')
                                ->label('Subcategory')
                                ->maxLength(80)
                                ->helperText('Optional placement, such as dogs, cats, import, or export.'),
                            TextInput::make('sort_order')
                                ->label('Display order')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->required()
                                ->helperText('Lower numbers appear first.'),
                            Select::make('status')
                                ->options(Faq::statusOptions())
                                ->default(Faq::STATUS_DRAFT)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
