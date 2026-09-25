<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(160),
                        Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            Select::make('category')
                                ->options(fn (): array => Product::categoryOptions())
                                ->searchable()
                                ->required(),
                            Select::make('badge')
                                ->options(['Bestseller' => 'Bestseller', 'New' => 'New'])
                                ->placeholder('No badge'),
                        ]),
                        Repeater::make('features')
                            ->simple(TextInput::make('feature')->required()->maxLength(160))
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->helperText('Optional points shown on the public product page.'),
                    ])
                    ->columnSpanFull(),
                Section::make('Price and publication')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('price')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->prefix('₦')
                                ->required(),
                            Select::make('currency')
                                ->options(['NGN' => 'Naira (NGN)'])
                                ->default('NGN')
                                ->required(),
                            TextInput::make('sort_order')
                                ->label('Display order')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->required(),
                            Select::make('status')
                                ->options(Product::statusOptions())
                                ->default(Product::STATUS_DRAFT)
                                ->required(),
                            Select::make('availability')
                                ->options(Product::availabilityOptions())
                                ->default(Product::AVAILABILITY_AVAILABLE)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Product image')
                    ->schema([
                        Image::make(
                            fn (?Product $record): string => $record?->publicImageUrl('detail') ?? '',
                            fn (?Product $record): string => $record === null
                                ? 'Current product image'
                                : ($record->image_alt ?: $record->name),
                        )
                            ->imageWidth(320)
                            ->imageHeight(200)
                            ->visible(fn (?Product $record): bool => $record?->getFirstMedia('image') === null && filled($record?->image)),
                        SpatieMediaLibraryFileUpload::make('product_image')
                            ->label('Image')
                            ->collection('image')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public')
                            ->responsiveImages()
                            ->maxSize(10240)
                            ->helperText('Upload a Waggies-owned product image. Existing catalogue images remain available until replaced.'),
                        SpatieMediaLibraryFileUpload::make('product_gallery')
                            ->label('Additional product images')
                            ->collection('images')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->responsiveImages()
                            ->maxFiles(Product::MAX_GALLERY_IMAGES)
                            ->appendFiles()
                            ->maxSize(10240)
                            ->helperText('Up to 8 additional images. Drag to reorder them; the first image is used as the primary gallery image.'),
                        TextInput::make('image_alt')
                            ->label('Alt text')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO title')
                            ->maxLength(160),
                        Textarea::make('seo_description')
                            ->label('SEO description')
                            ->maxLength(255)
                            ->rows(3),
                        Grid::make(2)->schema([
                            Toggle::make('is_indexable')
                                ->label('Indexable')
                                ->default(true)
                                ->helperText('Controls public search, canonical, structured data, and robots output.'),
                            Toggle::make('include_in_sitemap')
                                ->label('Include in sitemap')
                                ->default(true)
                                ->helperText('Only published, indexable products enter the sitemap.'),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
