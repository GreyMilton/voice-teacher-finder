<?php

namespace App\Filament\Resources\Instruments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InstrumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('english_name'),
                TextEntry::make('teachers_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachers')
                    ->label('Teachers'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
