<?php

namespace App\Filament\Resources\UpdateCohorts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class UpdateCohortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('course_date')
                    ->required(),
            ]);
    }
}
