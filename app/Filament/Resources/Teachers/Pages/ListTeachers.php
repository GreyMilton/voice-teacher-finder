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
            'all' => Tab::make()
                ->badge(Teacher::count()),
            'unauthorised' => Tab::make()
                ->badge(Teacher::unauthorised()->count())
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->unauthorised();
                }),
            'authorised' => Tab::make()
                ->badge(Teacher::authorised()->count())
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->authorised();
                }),
            'near expiry' => Tab::make()
                ->badge(Teacher::nearAuthorisationExpiry()->count())
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->nearAuthorisationExpiry();
                }),
            'expiring today' => Tab::make()
                ->badge(Teacher::expiringToday()->count())
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->expiringToday();
                }),
            'expired' => Tab::make()
                ->badge(Teacher::authorisationExpired()->count())
                ->modifyQueryUsing(function (Builder $query) {
                    /** @var Builder<Teacher> $query */
                    $query->authorisationExpired();
                }),
        ];
    }
}
