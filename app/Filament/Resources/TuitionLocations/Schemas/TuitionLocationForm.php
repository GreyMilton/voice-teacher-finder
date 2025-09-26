<?php

namespace App\Filament\Resources\TuitionLocations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TuitionLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('area')
                    ->required(),
                Select::make('territory_id')
                    ->relationship('territory', 'english_name')
                    ->required(),
            ]);
    }
}
