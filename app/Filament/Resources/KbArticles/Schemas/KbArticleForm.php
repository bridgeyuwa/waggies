<?php

namespace App\Filament\Resources\KbArticles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KbArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->options([
                        'boarding'   => 'Boarding & Stays',
                        'nutrition'  => 'Nutrition & Feeding',
                        'health'     => 'Health & Wellness',
                        'grooming'   => 'Grooming',
                        'training'   => 'Training & Behaviour',
                        'daycare'    => 'Daycare',
                        'general'    => 'General',
                    ])
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                        if (empty($get('slug'))) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                MarkdownEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('featured'),
                DateTimePicker::make('published_at')
                    ->label('Publish at'),
            ]);
    }
}
