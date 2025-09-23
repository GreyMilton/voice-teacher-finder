<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeacherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                IconEntry::make('isAuthorised')
                    ->boolean(),
                IconEntry::make('authorisationStatus')
                    ->boolean(),
                TextEntry::make('authorisationCohort.name')
                    ->label('Authorisation cohort')
                    ->placeholder('-'),
                TextEntry::make('latestUpdateCohort.name')
                    ->label('Latest update')
                    ->placeholder('-'),
                IconEntry::make('teaches_at_cvi')
                    ->boolean(),
                TextEntry::make('countryOfResidence.english_name')
                    ->label('Country of residence')
                    ->placeholder('-'),
                TextEntry::make('countryOfOrigin.english_name')
                    ->label('Country of origin')
                    ->placeholder('-'),
                TextEntry::make('gender'),
                TextEntry::make('qualification_string'),
                TextEntry::make('business_email'),
                TextEntry::make('business_phone'),
                TextEntry::make('business_website'),
                TextEntry::make('description'),
                IconEntry::make('gives_video_lessons')
                    ->boolean(),
                ImageEntry::make('profile_image_path'),
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
