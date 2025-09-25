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
                    ->searchable()
                    ->sortable(),
                IconColumn::make('isAuthorised')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('authorisationStatus')
                    ->badge()
                    ->toggleable(),
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
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('latestUpdateCohort.name')
                    ->label('Latest update')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        try {
                            // If month can be parsed, search by month of the latest Update Cohort.
                            $month = Carbon::parse($search)->format('m');

                            return $query->whereHas('latestUpdateCohort',
                                fn (Builder $query): Builder => $query
                                    ->whereMonth('course_date', $month)
                            );
                        } catch (InvalidFormatException $error) {
                            // If month cannot be parsed, do not modify query.
                            return $query;
                        }
                    })
                    ->toggleable(),
                IconColumn::make('teaches_at_cvi')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('territoryOfResidence.english_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('territoryOfOrigin.english_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('gender')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('qualification_string')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('business_email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('business_phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('business_website')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('gives_video_lessons')
                    ->boolean()
                    ->sortable()
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
            ->searchable([
                'authorisationCohort.authorisation_date',
                'authorisationCohort.name',
                'latestUpdateCohort.course_date',
                'latestUpdateCohort.name',
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
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks();
    }
}
