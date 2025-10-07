<?php

namespace App\Filament\Resources\Territories\Pages;

use App\Filament\Resources\Territories\TerritoryResource;
use App\Models\Territory;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTerritories extends ListRecords
{
    protected static string $resource = TerritoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Territory::count()),
            'all in use' => Tab::make()
                ->badge(
                    Territory::where(fn (Builder $query) => $query
                        ->has('teachersFrom')
                        ->orHas('teachersResiding')
                        ->orHas('teachersWhoTeachIn')
                        ->orHas('tuitionLocations')
                    )->count()
                )
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where(fn (Builder $query) => $query
                        ->has('teachersFrom')
                        ->orHas('teachersResiding')
                        ->orHas('teachersWhoTeachIn')
                        ->orHas('tuitionLocations')
                    )
                ),
            'have tuition locations' => Tab::make()
                ->badge(Territory::has('tuitionLocations')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('tuitionLocations')
                ),
            'have teachers teaching in' => Tab::make()
                ->badge(Territory::has('teachersWhoTeachIn')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersWhoTeachIn')
                ),
            'have teachers from' => Tab::make()
                ->badge(Territory::has('teachersFrom')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersFrom')
                ),
            'have teachers residing' => Tab::make()
                ->badge(Territory::has('teachersResiding')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersResiding')
                ),
        ];
    }
}
