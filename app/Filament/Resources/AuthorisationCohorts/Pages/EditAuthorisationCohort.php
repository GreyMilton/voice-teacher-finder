<?php

namespace App\Filament\Resources\AuthorisationCohorts\Pages;

use App\Filament\Resources\AuthorisationCohorts\AuthorisationCohortResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorisationCohort extends EditRecord
{
    protected static string $resource = AuthorisationCohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
