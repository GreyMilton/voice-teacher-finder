<?php

namespace App\Filament\Resources\Teachers\Schemas;

use App\Enums\Gender;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('authorisation_cohort_id')
                    ->relationship('authorisationCohort', 'name'),
                Toggle::make('teaches_at_cvi')
                    ->required(),
                Select::make('country_of_residence_id')
                    ->relationship('countryOfResidence', 'english_name'),
                Select::make('country_of_origin_id')
                    ->relationship('countryOfOrigin', 'english_name'),
                Select::make('gender')
                    ->options(Gender::class)
                    ->required(),
                TextInput::make('qualification_string')
                    ->required(),
                TextInput::make('business_email')
                    ->email()
                    ->required(),
                TextInput::make('business_phone')
                    ->tel()
                    ->required(),
                TextInput::make('business_website')
                    ->url()
                    ->required(),
                TextInput::make('description')
                    ->required(),
                Toggle::make('gives_video_lessons')
                    ->required(),
                FileUpload::make('profile_image_path')
                    ->image()
                    ->required(),
                Select::make('user_id')
                    ->relationship(
                        'user',
                        'name',
                        fn (Builder $query) => $query->doesntHave('teacher'),
                    ),
            ]);
    }
}
