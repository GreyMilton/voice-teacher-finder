<?php

namespace App\Filament\Resources\Instruments\Pages;

use App\Filament\Resources\Instruments\InstrumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListInstruments extends ListRecords
{
    protected static string $resource = InstrumentResource::class;

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
                    ->has('teachers')
                ),
        ];
    }
}
