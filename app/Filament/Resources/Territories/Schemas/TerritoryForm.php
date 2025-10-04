<?php

namespace App\Filament\Resources\Territories\Schemas;

use App\Models\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TerritoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('english_name')
                    ->required(),
                Select::make('region_id')
                    ->getOptionLabelFromRecordUsing(function (Region $region) {
                        return "{$region->english_name}, {$region->continent->english_name}";
                    })
                    ->relationship('region', 'english_name')
                    ->required(),
                TextInput::make('geo_point')
                    ->required(),
                TextInput::make('iso_3_country_code')
                    ->required(),
                TextInput::make('french_name')
                    ->required(),
            ]);
    }
}
