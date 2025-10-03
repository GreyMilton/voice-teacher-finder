<?php

namespace App\Filament\Resources\Cohorts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CohortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->placeholder(now()->format('M, y'))
                    ->required(),
                DatePicker::make('completion_date')
                    ->required(),
            ]);
    }
}
