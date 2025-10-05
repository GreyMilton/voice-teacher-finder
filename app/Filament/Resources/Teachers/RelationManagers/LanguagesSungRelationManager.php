<?php

namespace App\Filament\Resources\Teachers\RelationManagers;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LanguagesSungRelationManager extends RelationManager
{
    protected static string $relationship = 'languagesSung';

    protected static ?string $relatedResource = LanguageResource::class;

    protected static ?string $title = 'Languages sung';

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
