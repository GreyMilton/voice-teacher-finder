<?php

namespace App\Filament\Resources\Teachers\RelationManagers;

use App\Filament\Resources\Instruments\InstrumentResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class InstrumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'instruments';

    protected static ?string $relatedResource = InstrumentResource::class;

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
