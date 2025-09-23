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
                TextEntry::make('authorisationCohort.id')
                    ->label('Authorisation cohort')
                    ->placeholder('-'),
                IconEntry::make('isAuthorised')
                    ->boolean(),
                IconEntry::make('authorisationStatus')
                    ->boolean(),
                TextEntry::make('latestUpdateCohort.name')
                    ->label('Latest update')
                    ->placeholder('-'),
                TextEntry::make('business_email'),
                TextEntry::make('business_phone'),
                TextEntry::make('business_website'),
                TextEntry::make('countryOfOrigin.id')
                    ->label('Country of origin')
                    ->placeholder('-'),
                TextEntry::make('countryOfResidence.id')
                    ->label('Country of residence')
                    ->placeholder('-'),
                TextEntry::make('description'),
                TextEntry::make('gender'),
                IconEntry::make('gives_video_lessons')
                    ->boolean(),
                TextEntry::make('name'),
                ImageEntry::make('profile_image_path'),
                TextEntry::make('qualification_string'),
                IconEntry::make('teaches_at_cvi')
                    ->boolean(),
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
