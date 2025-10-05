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
                TextEntry::make('teachers_who_teach_in_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersWhoTeachIn')
                    ->label('Teachers Who Teach In'),
                TextEntry::make('teachers_from_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersFrom')
                    ->label('Teachers From'),
                TextEntry::make('teachers_residing_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersResiding')
                    ->label('Teachers Residing'),
                TextEntry::make('geo_point'),
                TextEntry::make('iso_3_country_code'),
                TextEntry::make('french_name'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
