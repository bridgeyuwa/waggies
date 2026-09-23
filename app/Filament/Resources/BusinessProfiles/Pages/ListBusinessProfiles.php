<?php

namespace App\Filament\Resources\BusinessProfiles\Pages;

use App\Filament\Resources\BusinessProfiles\BusinessProfileResource;
use Filament\Resources\Pages\ListRecords;

class ListBusinessProfiles extends ListRecords
{
    protected static string $resource = BusinessProfileResource::class;
}
