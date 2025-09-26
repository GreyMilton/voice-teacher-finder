<?php

namespace App\Filament\Resources\TuitionLocations;

use App\Filament\Resources\TuitionLocations\Pages\CreateTuitionLocation;
use App\Filament\Resources\TuitionLocations\Pages\EditTuitionLocation;
use App\Filament\Resources\TuitionLocations\Pages\ListTuitionLocations;
use App\Filament\Resources\TuitionLocations\Pages\ViewTuitionLocation;
use App\Filament\Resources\TuitionLocations\Schemas\TuitionLocationForm;
use App\Filament\Resources\TuitionLocations\Schemas\TuitionLocationInfolist;
use App\Filament\Resources\TuitionLocations\Tables\TuitionLocationsTable;
use App\Models\TuitionLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TuitionLocationResource extends Resource
{
    protected static ?string $model = TuitionLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['suburb', 'territory.english_name'];
    }

    public static function form(Schema $schema): Schema
    {
        return TuitionLocationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TuitionLocationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TuitionLocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTuitionLocations::route('/'),
            'create' => CreateTuitionLocation::route('/create'),
            'view' => ViewTuitionLocation::route('/{record}'),
            'edit' => EditTuitionLocation::route('/{record}/edit'),
        ];
    }
}
