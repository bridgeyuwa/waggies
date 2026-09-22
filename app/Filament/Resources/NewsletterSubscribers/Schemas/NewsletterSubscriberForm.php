<?php

namespace App\Filament\Resources\NewsletterSubscribers\Schemas;

use App\Models\NewsletterSubscriber;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscriber')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options(NewsletterSubscriber::statusOptions())
                                ->required(),
                            DateTimePicker::make('subscribed_at')
                                ->disabled()
                                ->dehydrated(false),
                            DateTimePicker::make('unsubscribed_at')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
