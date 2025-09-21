<?php

namespace App\Filament\Resources\AuthorisationCohorts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AuthorisationCohortInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('authorisation_date')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
