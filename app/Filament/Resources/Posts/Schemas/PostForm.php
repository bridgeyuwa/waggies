<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use App\Rules\ReservedSlug;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

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
                    ->default('blog')
                    ->live(),
                Select::make('category')
                    ->options([
                        'nutrition' => 'Nutrition',
                        'health' => 'Health & Wellness',
                        'training' => 'Training & Behaviour',
                        'grooming' => 'Grooming',
                        'boarding' => 'Boarding & Care',
                        'general' => 'General',
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
                    ->rules([new ReservedSlug])
                    ->unique(
                        table: Post::class,
                        column: 'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule, Get $get) {
                            return $rule->where('type', $get('type'));
                        },
                    )
                    ->columnSpanFull(),
                Textarea::make('excerpt')
                    ->rows(3)
                    ->columnSpanFull(),
                MarkdownEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('posts')
                    ->visibility('public'),
                TextInput::make('author')
                    ->required()
                    ->default('The Waggies Team'),
                DateTimePicker::make('published_at')
                    ->label('Publish at'),
            ]);
    }
}
