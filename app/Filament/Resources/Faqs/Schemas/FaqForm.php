<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class FaqForm
{
    private const SUBCATEGORIES = [
        'boarding'   => ['dogs' => 'Dogs', 'cats' => 'Cats', 'exotic' => 'Exotic'],
        'relocation' => ['import' => 'Import', 'export' => 'Export', 'local' => 'Local'],
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->options([
                        'boarding'   => 'Boarding',
                        'grooming'   => 'Grooming',
                        'relocation' => 'Relocation',
                        'vet-care'   => 'Vet Care',
                        'training'   => 'Training',
                        'transport'  => 'Transport',
                        'general'    => 'General',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('subcategory', null)),

                Select::make('subcategory')
                    ->options(fn (Get $get): array => self::SUBCATEGORIES[$get('category')] ?? [])
                    ->placeholder('All (applies to entire category)')
                    ->visible(fn (Get $get): bool => isset(self::SUBCATEGORIES[$get('category')])),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('question')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('answer')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
