<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use App\Models\Language;
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
            'all' => Tab::make()
                ->badge(Language::count()),
            'all in use' => Tab::make()
                ->badge(
                    Language::where(fn (Builder $query) => $query
                        ->has('teachersWhoTeachIn')
                        ->orHas('teachersWhoSing')
                    )->count()
                )
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where(fn (Builder $query) => $query
                        ->has('teachersWhoTeachIn')
                        ->orHas('teachersWhoSing')
                    )
                ),
            'tuition languages' => Tab::make()
                ->badge(Language::has('teachersWhoTeachIn')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersWhoTeachIn')
                ),
            'sung languages' => Tab::make()
                ->badge(Language::has('teachersWhoSing')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->has('teachersWhoSing')
                ),
        ];
    }
}
