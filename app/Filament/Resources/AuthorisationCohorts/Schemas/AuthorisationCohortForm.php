<?php

namespace App\Filament\Resources\AuthorisationCohorts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AuthorisationCohortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->placeholder(now()->format('M, y'))
                    ->required(),
                DatePicker::make('authorisation_date')
                    ->required(),
            ]);
    }
}
