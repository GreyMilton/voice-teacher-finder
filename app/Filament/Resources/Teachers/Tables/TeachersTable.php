<?php

namespace App\Filament\Resources\Teachers\Tables;

use App\Models\Teacher;
use Carbon\Exceptions\InvalidFormatException;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                TextColumn::make('isNearAuthorisationExpiry')
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
                    ->state(fn (Model $model): string => match ($model->isNearAuthorisationExpiry) {
                        true => 'Near expiry',
                        false => 'No',
                    }),
                TextColumn::make('currentAuthorisationStatus.value')
                    ->badge()
                    ->label('Authorisation status')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        /** @var Builder<Teacher> $query */
                        return $query->orderByCurrentAuthorisationStatus($direction);
                    })
                    ->toggleable(),
                TextColumn::make('firstAuthorisationCohort.name')
                    ->label('First authorisation')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        try {
                            // If month can be parsed, search by month of Authorisation Cohort.
                            $month = Carbon::parse($search)->format('m');

                            return $query->whereHas('firstAuthorisationCohort',
                                fn (Builder $query): Builder => $query
                                    ->whereMonth('completion_date', $month)
                            );
                        } catch (InvalidFormatException $error) {
                            // If month cannot be parsed, do not modify query.
                            return $query;
                        }
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        /** @var Builder<Teacher> $query */
                        return $query->orderByFirstAuthorisationCohort('name', $direction);
                    })
                    ->toggleable(),
                TextColumn::make('latestUpdateCohort.name')
                    ->label('Latest update')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        try {
                            // If month can be parsed, search by month of the latest Update Cohort.
                            $month = Carbon::parse($search)->format('m');

                            return $query->whereHas('latestUpdateCohort',
                                fn (Builder $query): Builder => $query
                                    ->whereMonth('completion_date', $month)
                            );
                        } catch (InvalidFormatException $error) {
                            // If month cannot be parsed, do not modify query.
                            return $query;
                        }
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        /** @var Builder<Teacher> $query */
                        return $query->orderByLatestUpdateCohort('name', $direction);
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
                    ->limit(45)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->limit(45)
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
                'firstAuthorisationCohort.completion_date',
                'firstAuthorisationCohort.name',
                'latestUpdateCohort.completion_date',
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
            ->defaultSort('name')
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks();
    }
}
