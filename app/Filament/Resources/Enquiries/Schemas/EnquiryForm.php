<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled(),
                TextInput::make('email')
                    ->label('Email address')
                    ->disabled(),
                TextInput::make('service')
                    ->disabled(),
                TextInput::make('variant')
                    ->disabled(),
                TextInput::make('tier')
                    ->label('Package')
                    ->disabled(),
                TextInput::make('intent')
                    ->disabled(),
                TextInput::make('estimate_summary')
                    ->label('Estimate')
                    ->disabled()
                    ->columnSpanFull(),
                Textarea::make('message')
                    ->disabled()
                    ->columnSpanFull(),
                DateTimePicker::make('read_at')
                    ->disabled(),
            ]);
    }
}
