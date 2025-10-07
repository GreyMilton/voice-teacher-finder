<?php

namespace App\Filament\Resources\TuitionLocations\Pages;

use App\Filament\Resources\TuitionLocations\TuitionLocationResource;
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
            'all' => Tab::make(),
            'all in use' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachers')
                ),
        ];
    }
}
