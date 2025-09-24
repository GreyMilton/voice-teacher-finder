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
                TextEntry::make('authorisationStatus')
                    ->badge(),
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
                TextEntry::make('gender')
                    ->badge(),
                TextEntry::make('qualification_string')
                    ->placeholder('-'),
                TextEntry::make('business_email')
                    ->placeholder('-'),
                TextEntry::make('business_phone')
                    ->placeholder('-'),
                TextEntry::make('business_website')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-'),
                IconEntry::make('gives_video_lessons')
                    ->boolean(),
                ImageEntry::make('profile_image_path')
                    ->placeholder('-'),
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
