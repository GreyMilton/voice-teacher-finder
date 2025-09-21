<?php

namespace App\Filament\Resources\UpdateCohorts\Pages;

use App\Filament\Resources\UpdateCohorts\UpdateCohortResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUpdateCohort extends ViewRecord
{
    protected static string $resource = UpdateCohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
