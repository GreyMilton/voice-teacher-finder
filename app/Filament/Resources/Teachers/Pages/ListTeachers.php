<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

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
            'visible' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->visible()),
            'hidden' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereNot(fn (Builder $query) => $query->visible())
                ),
        ];
    }
}
