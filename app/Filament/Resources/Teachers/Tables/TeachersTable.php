<?php

namespace App\Filament\Resources\Teachers\Tables;

use App\Models\Teacher;
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

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('isExpiringToday')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Expiring today'
                        ? 'danger'
                        : 'gray',
                    )
                    ->icon(fn (string $state): Heroicon => $state === 'Expiring today'
                        ? Heroicon::ExclamationTriangle
                        : Heroicon::OutlinedHandThumbUp,
                    )
                    ->label('Expiring today')
                    ->state(function (Model $record): string {
                        /** @var Teacher $record */
                        return $record->isExpiringToday
                            ? 'Expiring today'
                            : 'No';
                    })
                    ->toggleable(),
                TextColumn::make('isNearAuthorisationExpiry')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Near expiry'
                        ? 'danger'
                        : 'gray',
                    )
                    ->icon(fn (string $state): Heroicon => $state === 'Near expiry'
                        ? Heroicon::ExclamationTriangle
                        : Heroicon::OutlinedHandThumbUp,
                    )
                    ->label('Near expiry')
                    ->state(function (Model $record): string {
                        /** @var Teacher $record */
                        return $record->isNearAuthorisationExpiry
                            ? 'Near expiry'
                            : 'No';
                    })
                    ->toggleable(),
                TextColumn::make('currentAuthorisationStatus.value')
                    ->badge()
                    ->label('Authorisation status')
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        /** @var Builder<Teacher> $query */
                        return $query->orderByCurrentAuthorisationStatus($direction);
                    })
                    ->toggleable(),
                TextColumn::make('firstAuthorisationCohort.name')
                    ->label('First authorisation')
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        /** @var Builder<Teacher> $query */
                        return $query->orderByFirstAuthorisationCohort('name', $direction);
                    })
                    ->toggleable(),
                TextColumn::make('latestUpdateCohort.name')
                    ->label('Latest update')
                    ->searchable()
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
                    ->sortable()
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
