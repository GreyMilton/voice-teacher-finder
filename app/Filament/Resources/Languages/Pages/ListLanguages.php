<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

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
                        ->has('teachersWhoTeachIn')
                        ->orHas('teachersWhoSing')
                    )
                ),
            'tuition languages' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersWhoTeachIn')
                ),
            'sung languages' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersWhoSing')
                ),
        ];
    }
}
