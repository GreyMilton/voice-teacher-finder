<?php

namespace App\Filament\Resources\AuthorisationCohorts;

use App\Filament\Resources\AuthorisationCohorts\Pages\CreateAuthorisationCohort;
use App\Filament\Resources\AuthorisationCohorts\Pages\EditAuthorisationCohort;
use App\Filament\Resources\AuthorisationCohorts\Pages\ListAuthorisationCohorts;
use App\Filament\Resources\AuthorisationCohorts\Pages\ViewAuthorisationCohort;
use App\Filament\Resources\AuthorisationCohorts\Schemas\AuthorisationCohortForm;
use App\Filament\Resources\AuthorisationCohorts\Schemas\AuthorisationCohortInfolist;
use App\Filament\Resources\AuthorisationCohorts\Tables\AuthorisationCohortsTable;
use App\Models\AuthorisationCohort;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuthorisationCohortResource extends Resource
{
    protected static ?string $model = AuthorisationCohort::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return AuthorisationCohortForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuthorisationCohortInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorisationCohortsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuthorisationCohorts::route('/'),
            'create' => CreateAuthorisationCohort::route('/create'),
            'view' => ViewAuthorisationCohort::route('/{record}'),
            'edit' => EditAuthorisationCohort::route('/{record}/edit'),
        ];
    }
}
