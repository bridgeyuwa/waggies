<?php

namespace App\Filament\Resources\KnowledgeArticles\Schemas;

use App\Models\KnowledgeArticle;
use Filament\Forms\Components\DateTimePicker;
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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KnowledgeArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        $categories = KnowledgeArticle::categoryOptions();

        return $schema
            ->components([
                Section::make('Core content')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state, ?KnowledgeArticle $record): void {
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
                        Textarea::make('excerpt')->required()->maxLength(500)->rows(4)->columnSpanFull(),
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
                Section::make('Support taxonomy')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('category')
                                ->options(array_combine($categories, $categories) ?: [])
                                ->required(),
                            TextInput::make('author')->maxLength(255),
                        ]),
                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->required()
                            ->helperText('Lower numbers appear first in the public Knowledge Base.'),
                    ])
                    ->columnSpanFull(),
                Section::make('Publication')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options(KnowledgeArticle::statusOptions())
                                ->default(KnowledgeArticle::STATUS_DRAFT)
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
                                ->helperText('Only published, indexable articles enter the sitemap.'),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Media')
                    ->schema([
                        Image::make(
                            fn (?KnowledgeArticle $record): string => $record?->publicImageUrl('detail') ?? '',
                            fn (?KnowledgeArticle $record): string => $record === null
                                ? 'Current knowledge article cover'
                                : ($record->image_alt ?: $record->title),
                        )
                            ->imageWidth(320)
                            ->imageHeight(200)
                            ->visible(fn (?KnowledgeArticle $record): bool => $record?->getFirstMedia('cover') === null && filled($record?->image)),
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
                        TextInput::make('read_time')->label('Reading time')->placeholder('5 min read')->maxLength(50),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
