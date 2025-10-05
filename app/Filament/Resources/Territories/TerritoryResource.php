<?php

namespace App\Filament\Resources\Territories;

use App\Filament\Resources\Languages\RelationManagers\TeachersWhoTeachInRelationManager;
use App\Filament\Resources\Territories\Pages\CreateTerritory;
use App\Filament\Resources\Territories\Pages\EditTerritory;
use App\Filament\Resources\Territories\Pages\ListTerritories;
use App\Filament\Resources\Territories\Pages\ViewTerritory;
use App\Filament\Resources\Territories\RelationManagers\TeachersFromRelationManager;
use App\Filament\Resources\Territories\RelationManagers\TeachersResidingRelationManager;
use App\Filament\Resources\Territories\RelationManagers\TuitionLocationsRelationManager;
use App\Filament\Resources\Territories\Schemas\TerritoryForm;
use App\Filament\Resources\Territories\Schemas\TerritoryInfolist;
use App\Filament\Resources\Territories\Tables\TerritoriesTable;
use App\Models\Territory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TerritoryResource extends Resource
{
    protected static ?string $model = Territory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $recordTitleAttribute = 'english_name';

    public static function form(Schema $schema): Schema
    {
        return TerritoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TerritoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TerritoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TuitionLocationsRelationManager::class,
            TeachersWhoTeachInRelationManager::class,
            TeachersFromRelationManager::class,
            TeachersResidingRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTerritories::route('/'),
            'create' => CreateTerritory::route('/create'),
            'view' => ViewTerritory::route('/{record}'),
            'edit' => EditTerritory::route('/{record}/edit'),
        ];
    }
}
