<?php

namespace App\Filament\Resources\Cohorts;

use App\Filament\Resources\Cohorts\Pages\CreateCohort;
use App\Filament\Resources\Cohorts\Pages\EditCohort;
use App\Filament\Resources\Cohorts\Pages\ListCohorts;
use App\Filament\Resources\Cohorts\Pages\ViewCohort;
use App\Filament\Resources\Cohorts\RelationManagers\TeachersRelationManager;
use App\Filament\Resources\Cohorts\Schemas\CohortForm;
use App\Filament\Resources\Cohorts\Schemas\CohortInfolist;
use App\Filament\Resources\Cohorts\Tables\CohortsTable;
use App\Models\Cohort;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CohortResource extends Resource
{
    protected static ?string $model = Cohort::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CohortForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CohortInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CohortsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TeachersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCohorts::route('/'),
            'create' => CreateCohort::route('/create'),
            'view' => ViewCohort::route('/{record}'),
            'edit' => EditCohort::route('/{record}/edit'),
        ];
    }
}
