<?php

namespace App\Filament\Resources\UpdateCohorts\Pages;

use App\Filament\Resources\UpdateCohorts\UpdateCohortResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUpdateCohorts extends ListRecords
{
    protected static string $resource = UpdateCohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
