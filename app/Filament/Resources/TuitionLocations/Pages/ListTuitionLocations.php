<?php

namespace App\Filament\Resources\TuitionLocations\Pages;

use App\Filament\Resources\TuitionLocations\TuitionLocationResource;
use App\Models\TuitionLocation;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTuitionLocations extends ListRecords
{
    protected static string $resource = TuitionLocationResource::class;

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
                ->badge(TuitionLocation::count()),
            'all in use' => Tab::make()
                ->badge(TuitionLocation::has('teachers')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachers')
                ),
        ];
    }
}
