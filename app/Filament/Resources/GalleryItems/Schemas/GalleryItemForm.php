<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use App\Models\GalleryItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gallery image')
                    ->schema([
                        Image::make(
                            fn (?GalleryItem $record): string => $record?->publicImageUrl('detail') ?? '',
                            fn (?GalleryItem $record): string => $record === null || blank($record->image_alt)
                                ? 'Current gallery image'
                                : $record->image_alt,
                        )
                            ->imageWidth(320)
                            ->imageHeight(200)
                            ->visible(fn (?GalleryItem $record): bool => $record?->getFirstMedia('image') === null && filled($record?->image)),
                        SpatieMediaLibraryFileUpload::make('gallery_image')
                            ->label('Image')
                            ->collection('image')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public')
                            ->responsiveImages()
                            ->maxSize(10240)
                            ->required(fn (?GalleryItem $record): bool => $record === null)
                            ->helperText('Upload a Waggies-owned image. JPG, PNG, and WebP files are accepted.'),
                        TextInput::make('image_alt')
                            ->label('Alt text')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Describe the image for visitors using assistive technology.'),
                        Textarea::make('caption')
                            ->rows(3)
                            ->maxLength(255)
                            ->helperText('Optional caption shown in the lightbox.'),
                    ])
                    ->columnSpanFull(),
                Section::make('Placement and publication')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('category')
                                ->required()
                                ->maxLength(80)
                                ->helperText('Examples: Boarding, Grooming, Veterinary, Training.'),
                            TextInput::make('sort_order')
                                ->label('Display order')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->required()
                                ->helperText('Lower numbers appear first.'),
                            Select::make('status')
                                ->options(GalleryItem::statusOptions())
                                ->default(GalleryItem::STATUS_DRAFT)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
