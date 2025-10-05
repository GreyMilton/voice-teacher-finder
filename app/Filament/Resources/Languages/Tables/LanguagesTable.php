<?php

namespace App\Filament\Resources\Languages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LanguagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('english_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teachers_who_sing_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersWhoSing')
                    ->label('Teachers Who Sing')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('teachers_who_teach_in_count')
                    ->badge()
                    ->color(function (int $state) {
                        return $state > 0 ? 'info' : 'gray';
                    })
                    ->counts('teachersWhoTeachIn')
                    ->label('Teachers Who Teach In')
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
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks();
    }
}
