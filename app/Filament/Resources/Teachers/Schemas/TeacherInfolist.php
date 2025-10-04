<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class TeacherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('isNearAuthorisationExpiry')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Near expiry' => 'danger',
                        'No' => 'gray',
                    })
                    ->icon(fn (string $state): Heroicon => match ($state) {
                        'Near expiry' => Heroicon::ExclamationTriangle,
                        'No' => Heroicon::OutlinedHandThumbUp,
                    })
                    ->label('Near expiry')
                    ->state(fn (Model $record): string => match ($record->isNearAuthorisationExpiry) {
                        true => 'Near expiry',
                        false => 'No',
                    }),
                TextEntry::make('currentAuthorisationStatus.value')
                    ->label('Authorisation status')
                    ->badge(),
                TextEntry::make('firstAuthorisationCohort.name')
                    ->label('First authorisation')
                    ->placeholder('-'),
                TextEntry::make('latestUpdateCohort.name')
                    ->label('Latest update')
                    ->placeholder('-'),
                IconEntry::make('teaches_at_cvi')
                    ->boolean(),
                TextEntry::make('territoryOfResidence.english_name')
                    ->label('Territory of residence')
                    ->placeholder('-'),
                TextEntry::make('territoryOfOrigin.english_name')
                    ->label('Territory of origin')
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
