<?php

namespace App\Filament\Resources\JobOpenings\Schemas;

use App\Models\JobOpening;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobOpeningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role')
                    ->schema([
                        TextInput::make('title')->required()->maxLength(160),
                        Grid::make(3)->schema([
                            TextInput::make('department')->maxLength(120),
                            TextInput::make('location')->maxLength(160),
                            TextInput::make('employment_type')->maxLength(80),
                        ]),
                        Textarea::make('summary')->maxLength(500)->columnSpanFull(),
                        Textarea::make('description')->rows(7)->columnSpanFull(),
                        Textarea::make('requirements')->rows(7)->columnSpanFull(),
                    ])->columnSpanFull(),
                Section::make('Publication')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('status')->options(JobOpening::statusOptions())->required(),
                            DatePicker::make('published_at')->label('Publish from'),
                            DatePicker::make('closing_date')->label('Closing date'),
                            TextInput::make('sort_order')->numeric()->integer()->minValue(0)->required(),
                            TextInput::make('application_email')->email()->maxLength(255),
                            TextInput::make('application_url')->url()->maxLength(500),
                        ]),
                    ])->columnSpanFull(),
            ]);
    }
}
