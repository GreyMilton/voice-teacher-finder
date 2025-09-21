<?php

namespace App\Filament\Resources\TuitionLocations\Pages;

use App\Filament\Resources\TuitionLocations\TuitionLocationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTuitionLocation extends ViewRecord
{
    protected static string $resource = TuitionLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
