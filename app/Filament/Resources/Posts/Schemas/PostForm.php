<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options([
                        'blog' => 'Blog Post',
                        'guide' => 'Guide',
                    ])
                    ->required()
                    ->default('blog'),
                Select::make('category')
                    ->options([
                        'nutrition'  => 'Nutrition',
                        'health'     => 'Health & Wellness',
                        'training'   => 'Training & Behaviour',
                        'grooming'   => 'Grooming',
                        'boarding'   => 'Boarding & Care',
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
                Textarea::make('excerpt')
                    ->rows(3)
                    ->columnSpanFull(),
                MarkdownEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->directory('posts'),
                TextInput::make('author')
                    ->required()
                    ->default('The Waggies Team'),
                DateTimePicker::make('published_at')
                    ->label('Publish at'),
            ]);
    }
}
