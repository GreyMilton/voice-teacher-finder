<?php

namespace App\Filament\Resources\Teachers\Schemas;

use App\Enums\Gender;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('authorisation_cohort_id')
                    ->relationship('authorisationCohort', 'id'),
                TextInput::make('business_email')
                    ->email()
                    ->required(),
                TextInput::make('business_phone')
                    ->tel()
                    ->required(),
                TextInput::make('business_website')
                    ->url()
                    ->required(),
                Select::make('country_of_origin_id')
                    ->relationship('countryOfOrigin', 'id'),
                Select::make('country_of_residence_id')
                    ->relationship('countryOfResidence', 'id'),
                TextInput::make('description')
                    ->required(),
                Select::make('gender')
                    ->options(Gender::class)
                    ->required(),
                Toggle::make('gives_video_lessons')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                FileUpload::make('profile_image_path')
                    ->image()
                    ->required(),
                TextInput::make('qualification_string')
                    ->required(),
                Toggle::make('teaches_at_cvi')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
            ]);
    }
}
