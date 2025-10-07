<?php

namespace App\Filament\Resources\Territories\Pages;

use App\Filament\Resources\Territories\TerritoryResource;
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
            'all' => Tab::make(),
            'all in use' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where(fn (Builder $query) => $query
                        ->has('teachersFrom')
                        ->orHas('teachersResiding')
                        ->orHas('teachersWhoTeachIn')
                        ->orHas('tuitionLocations')
                    )
                ),
            'have tuition locations' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('tuitionLocations')
                ),
            'have teachers teaching in' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersWhoTeachIn')
                ),
            'have teachers from' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersFrom')
                ),
            'have teachers residing' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersFrom')
                ),
        ];
    }
}
