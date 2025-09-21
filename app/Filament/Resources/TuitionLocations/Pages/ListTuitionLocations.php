<?php

namespace App\Filament\Resources\TuitionLocations\Pages;

use App\Filament\Resources\TuitionLocations\TuitionLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTuitionLocations extends ListRecords
{
    protected static string $resource = TuitionLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
