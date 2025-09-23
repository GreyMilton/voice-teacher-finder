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
                TextInput::make('suburb')
                    ->required(),
                Select::make('country_id')
                    ->relationship('country', 'english_name')
                    ->required(),
            ]);
    }
}
