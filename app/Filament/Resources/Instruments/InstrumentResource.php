<?php

namespace App\Filament\Resources\Instruments;

use App\Filament\Resources\Instruments\Pages\CreateInstrument;
use App\Filament\Resources\Instruments\Pages\EditInstrument;
use App\Filament\Resources\Instruments\Pages\ListInstruments;
use App\Filament\Resources\Instruments\Pages\ViewInstrument;
use App\Filament\Resources\Instruments\Schemas\InstrumentForm;
use App\Filament\Resources\Instruments\Schemas\InstrumentInfolist;
use App\Filament\Resources\Instruments\Tables\InstrumentsTable;
use App\Models\Instrument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstrumentResource extends Resource
{
    protected static ?string $model = Instrument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return InstrumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InstrumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstrumentsTable::configure($table);
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
            'index' => ListInstruments::route('/'),
            'create' => CreateInstrument::route('/create'),
            'view' => ViewInstrument::route('/{record}'),
            'edit' => EditInstrument::route('/{record}/edit'),
        ];
    }
}
