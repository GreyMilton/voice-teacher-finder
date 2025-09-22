<?php

namespace App\Filament\Resources\Teachers\Tables;

use Carbon\Exceptions\InvalidFormatException;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('authorisationCohort.name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        try {
                            // If month can be parsed, search by month of Authorisation Cohort.
                            $month = Carbon::parse($search)->format('m');

                            return $query->whereHas('authorisationCohort',
                                fn (Builder $query): Builder => $query
                                    ->whereMonth('authorisation_date', $month)
                            );
                        } catch (InvalidFormatException $error) {
                            // If month cannot be parsed, do not modify query.
                            return $query;
                        }
                    })
                    ->toggleable(),
                IconColumn::make('teaches_at_cvi')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('countryOfResidence.english_name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('countryOfOrigin.english_name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('gender')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('qualification_string')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('business_email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('business_phone')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('business_website')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('gives_video_lessons')
                    ->boolean()
                    ->toggleable(),
                ImageColumn::make('profile_image_path')
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchable(['authorisationCohort.authorisation_date', 'authorisationCohort.name'])
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
