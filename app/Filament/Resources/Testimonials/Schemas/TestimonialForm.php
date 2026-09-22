<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Models\Testimonial;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service')
                                ->options(Testimonial::serviceOptions())
                                ->required(),
                            Select::make('rating')
                                ->options([1 => '1 star', 2 => '2 stars', 3 => '3 stars', 4 => '4 stars', 5 => '5 stars'])
                                ->required(),
                        ]),
                        TextInput::make('title')->maxLength(120),
                        Textarea::make('story')
                            ->required()
                            ->rows(8)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Attribution and moderation')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('author_name')->required()->maxLength(120),
                            TextInput::make('author_location')->maxLength(120),
                            TextInput::make('pet_name')->maxLength(60),
                            TextInput::make('pet_type')->maxLength(40),
                            Select::make('status')
                                ->options(Testimonial::statusOptions())
                                ->required(),
                            TextInput::make('sort_order')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->required(),
                            DateTimePicker::make('consented_at')->disabled()->dehydrated(false),
                            DateTimePicker::make('published_at')->disabled()->dehydrated(false),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Photo')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('photo')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(4096),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
