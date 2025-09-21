<?php

namespace App\Filament\Resources\TuitionLocations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TuitionLocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('suburb'),
                TextEntry::make('country.id')
                    ->label('Country'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
