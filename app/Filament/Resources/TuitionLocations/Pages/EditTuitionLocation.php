<?php

namespace App\Filament\Resources\TuitionLocations\Pages;

use App\Filament\Resources\TuitionLocations\TuitionLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTuitionLocation extends EditRecord
{
    protected static string $resource = TuitionLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
