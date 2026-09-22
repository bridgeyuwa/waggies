<?php

namespace App\Filament\Resources\ContactEnquiries\Schemas;

use App\Models\ContactEnquiry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactEnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enquiry')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')->disabled()->dehydrated(false),
                            TextInput::make('phone')->label('WhatsApp / phone')->disabled()->dehydrated(false),
                            TextInput::make('email')->email()->disabled()->dehydrated(false),
                            TextInput::make('subject')->disabled()->dehydrated(false),
                        ]),
                        Textarea::make('message')
                            ->rows(12)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Workflow')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options(ContactEnquiry::statusOptions())
                                ->required(),
                            DateTimePicker::make('received_at')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
