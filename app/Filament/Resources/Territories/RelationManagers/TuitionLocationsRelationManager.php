<?php

namespace App\Filament\Resources\Territories\RelationManagers;

use App\Filament\Resources\TuitionLocations\TuitionLocationResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TuitionLocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'tuitionLocations';

    protected static ?string $relatedResource = TuitionLocationResource::class;

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
