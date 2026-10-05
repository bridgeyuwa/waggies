<?php

namespace App\Filament\Resources\Guides\Schemas;

use App\Models\Guide;
use App\Support\CountryCatalog;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\ToolbarButtonGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        $categories = Guide::categoryOptions();
        $countryCatalog = app(CountryCatalog::class);
        $fixedCountryCode = strtoupper((string) config('waggies_booking.relocation.fixed_country_code', 'NG'));
        $fixedCountryLabel = $countryCatalog->label($fixedCountryCode) ?? $fixedCountryCode;
        $editableCountryOptions = $countryCatalog->options($fixedCountryCode);
        $fixedCountryOptions = [$fixedCountryCode => $fixedCountryLabel];

        return $schema
            ->components([
                Section::make('Core content')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state, ?Guide $record): void {
                                    if ($record === null && filled($state)) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->helperText('Generated from the title on creation. Edit only when the public URL should change.'),
                        ]),
                        Select::make('category')
                            ->options(array_combine($categories, $categories) ?: [])
                            ->live()
                            ->required(),
                        Textarea::make('excerpt')
                            ->required()
                            ->maxLength(500)
                            ->rows(4)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->required()
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                [ToolbarButtonGroup::make('Headings', ['paragraph', 'h2', 'h3'])],
                                [ToolbarButtonGroup::make('Alignment', ['alignStart', 'alignCenter', 'alignEnd'])],
                                ['blockquote', 'bulletList', 'orderedList', 'horizontalRule'],
                                ['table', 'attachFiles', 'clearFormatting'],
                                ['undo', 'redo'],
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsAcceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->preventFileAttachmentPathTampering()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Relocation metadata')
                    ->description('Use this section for route-specific relocation guides. Leave route fields empty for a general guide.')
                    ->visible(fn (Get $get): bool => $get('category') === Guide::RELOCATION_CATEGORY || filled($get('relocation_direction')))
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('relocation_direction')
                                ->label('Relocation direction')
                                ->options(Guide::relocationDirectionOptions())
                                ->helperText('Waggies currently handles Nigeria-to-country and country-to-Nigeria routes.')
                                ->live(),
                            DatePicker::make('last_reviewed_at')
                                ->label('Last reviewed')
                                ->helperText('Use the date the route requirements were last checked against official sources.'),
                            Select::make('origin_country_code')
                                ->label('Origin country')
                                ->options(fn (Get $get): array => $get('relocation_direction') === Guide::RELOCATION_DIRECTION_EXPORT ? $fixedCountryOptions : $editableCountryOptions)
                                ->searchable()
                                ->required(fn (Get $get): bool => filled($get('relocation_direction'))),
                            Select::make('destination_country_code')
                                ->label('Destination country')
                                ->options(fn (Get $get): array => $get('relocation_direction') === Guide::RELOCATION_DIRECTION_IMPORT ? $fixedCountryOptions : $editableCountryOptions)
                                ->searchable()
                                ->required(fn (Get $get): bool => filled($get('relocation_direction'))),
                        ]),
                        Repeater::make('source_links')
                            ->label('Official source links')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Source name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('url')
                                    ->label('Source URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(2048),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add source link')
                            ->helperText('Use official government, embassy, airline, or veterinary authority sources.'),
                    ])
                    ->columnSpanFull(),
                Section::make('Publication')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options(Guide::statusOptions())
                                ->default(Guide::STATUS_DRAFT)
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Set automatically when the status is Published.'),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')->label('SEO title')->maxLength(60),
                        Textarea::make('seo_description')->label('SEO description')->maxLength(160)->rows(3),
                        Grid::make(2)->schema([
                            Toggle::make('is_indexable')
                                ->label('Indexable')
                                ->default(true)
                                ->helperText('Controls search, canonical, structured data, and robots output.'),
                            Toggle::make('include_in_sitemap')
                                ->label('Include in sitemap')
                                ->default(true)
                                ->helperText('Only published, indexable Guides enter the sitemap.'),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Media')
                    ->schema([
                        Image::make(
                            fn (?Guide $record): string => $record?->publicImageUrl('detail') ?? '',
                            fn (?Guide $record): string => $record === null
                                ? 'Current guide cover'
                                : ($record->image_alt ?: $record->title),
                        )
                            ->imageWidth(320)
                            ->imageHeight(200)
                            ->visible(fn (?Guide $record): bool => $record?->getFirstMedia('cover') === null && filled($record?->image)),
                        SpatieMediaLibraryFileUpload::make('cover_image')
                            ->label('Cover image')
                            ->collection('cover')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public')
                            ->responsiveImages()
                            ->maxSize(5120)
                            ->helperText('Upload a Waggies-owned image. Existing remote legacy images remain available until replaced.'),
                        TextInput::make('image_alt')
                            ->label('Cover image alt text')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('read_time')
                            ->label('Reading time')
                            ->placeholder('6 min read')
                            ->maxLength(50),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
