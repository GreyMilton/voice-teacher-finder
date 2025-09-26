<?php

namespace App\Filament\Resources\Territories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TerritoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('english_name'),
                TextEntry::make('region.english_name')
                    ->label('Region'),
                TextEntry::make('region.continent.english_name')
                    ->label('Continent'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
