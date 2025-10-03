<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\Teacher;
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
            'unauthorised' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->unauthorised();
                }),
            'authorised' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->authorised();
                }),
            'near expiry' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->nearAuthorisationExpiry();
                }),
            'expired' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->authorisationExpired();
                }),
        ];
    }
}
