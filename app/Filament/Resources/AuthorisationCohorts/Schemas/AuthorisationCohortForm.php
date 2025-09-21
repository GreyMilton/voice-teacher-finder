<?php

namespace App\Filament\Resources\AuthorisationCohorts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class AuthorisationCohortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('authorisation_date')
                    ->required(),
            ]);
    }
}
