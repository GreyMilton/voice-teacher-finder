<?php

namespace App\Filament\Resources\AuthorisationCohorts\Pages;

use App\Filament\Resources\AuthorisationCohorts\AuthorisationCohortResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthorisationCohorts extends ListRecords
{
    protected static string $resource = AuthorisationCohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
