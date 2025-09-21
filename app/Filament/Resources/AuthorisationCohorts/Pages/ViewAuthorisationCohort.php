<?php

namespace App\Filament\Resources\AuthorisationCohorts\Pages;

use App\Filament\Resources\AuthorisationCohorts\AuthorisationCohortResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAuthorisationCohort extends ViewRecord
{
    protected static string $resource = AuthorisationCohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
