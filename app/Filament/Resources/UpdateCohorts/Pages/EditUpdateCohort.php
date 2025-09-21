<?php

namespace App\Filament\Resources\UpdateCohorts\Pages;

use App\Filament\Resources\UpdateCohorts\UpdateCohortResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUpdateCohort extends EditRecord
{
    protected static string $resource = UpdateCohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
