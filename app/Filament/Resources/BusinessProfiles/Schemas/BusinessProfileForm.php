<?php

namespace App\Filament\Resources\BusinessProfiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusinessProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity and contact')
                    ->schema([
                        TextInput::make('business_name')->required()->maxLength(160),
                        TextInput::make('primary_email')->email()->required()->maxLength(255),
                        TextInput::make('phone')->required()->maxLength(40),
                        TextInput::make('phone_international')->maxLength(40),
                        TextInput::make('whatsapp_url')->url()->maxLength(255),
                        TextInput::make('timezone')->required()->maxLength(80),
                    ])->columns(2)->columnSpanFull(),
                Section::make('Address and map')
                    ->schema([
                        TextInput::make('address_street')->maxLength(255),
                        TextInput::make('address_city')->maxLength(120),
                        TextInput::make('address_postal_code')->maxLength(20),
                        TextInput::make('address_state')->maxLength(120),
                        TextInput::make('address_country')->maxLength(120),
                        TextInput::make('map_url')->url()->maxLength(500),
                    ])->columns(2)->columnSpanFull(),
                Section::make('Social links')
                    ->description('Only configured links appear on the public site.')
                    ->schema([
                        TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(255),
                        TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                        TextInput::make('x_url')->label('X')->url()->maxLength(255),
                        TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(255),
                        TextInput::make('tiktok_url')->label('TikTok')->url()->maxLength(255),
                        TextInput::make('youtube_url')->label('YouTube')->url()->maxLength(255),
                    ])->columns(2)->columnSpanFull(),
            ]);
    }
}
