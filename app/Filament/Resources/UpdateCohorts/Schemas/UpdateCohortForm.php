<?php

namespace App\Filament\Resources\UpdateCohorts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UpdateCohortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->placeholder(now()->format('M, y'))
                    ->required(),
                DatePicker::make('cohort_date')
                    ->required(),
            ]);
    }
}
