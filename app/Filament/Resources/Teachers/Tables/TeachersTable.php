<?php

namespace App\Filament\Resources\Teachers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('authorisationCohort.id')
                    ->searchable(),
                TextColumn::make('business_email')
                    ->searchable(),
                TextColumn::make('business_phone')
                    ->searchable(),
                TextColumn::make('business_website')
                    ->searchable(),
                TextColumn::make('countryOfOrigin.id')
                    ->searchable(),
                TextColumn::make('countryOfResidence.id')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('gender')
                    ->searchable(),
                IconColumn::make('gives_video_lessons')
                    ->boolean(),
                TextColumn::make('name')
                    ->searchable(),
                ImageColumn::make('profile_image_path'),
                TextColumn::make('qualification_string')
                    ->searchable(),
                IconColumn::make('teaches_at_cvi')
                    ->boolean(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
