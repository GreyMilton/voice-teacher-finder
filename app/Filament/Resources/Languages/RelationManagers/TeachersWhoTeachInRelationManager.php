<?php

namespace App\Filament\Resources\Languages\RelationManagers;

use App\Filament\Resources\Teachers\TeacherResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TeachersWhoTeachInRelationManager extends RelationManager
{
    protected static string $relationship = 'teachersWhoTeachIn';

    protected static ?string $relatedResource = TeacherResource::class;

    protected static ?string $title = 'Teachers who teach in';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                AttachAction::make(),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
