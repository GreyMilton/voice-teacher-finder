<?php

namespace App\Filament\Resources\Cohorts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CohortsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('completion_date')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('cohort_type')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('teachers_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachers')
                    ->label('Teachers')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('authorisationExpirationDate')
                    ->date()
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
            ->defaultSort('completion_date')
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks();
    }
}
