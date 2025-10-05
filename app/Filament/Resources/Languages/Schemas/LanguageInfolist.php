<?php

namespace App\Filament\Resources\Languages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LanguageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('english_name'),
                TextEntry::make('teachers_who_sing_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersWhoSing')
                    ->label('Teachers Who Sing'),
                TextEntry::make('teachers_who_teach_in_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersWhoTeachIn')
                    ->label('Teachers Who Teach In'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
