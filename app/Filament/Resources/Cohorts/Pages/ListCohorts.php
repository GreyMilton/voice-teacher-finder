<?php

namespace App\Filament\Resources\Cohorts\Pages;

use App\Enums\CohortType;
use App\Filament\Resources\Cohorts\CohortResource;
use App\Models\Cohort;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCohorts extends ListRecords
{
    protected static string $resource = CohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Initial Authorisation' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Cohort> $query */
                    $query->whereCohortType(CohortType::InitialAuthorisation);
                }),
            'Update' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Cohort> $query */
                    $query->whereCohortType(CohortType::Update);
                }),
        ];
    }
}
