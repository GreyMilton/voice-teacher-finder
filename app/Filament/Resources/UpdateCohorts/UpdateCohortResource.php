<?php

namespace App\Filament\Resources\UpdateCohorts;

use App\Filament\Resources\UpdateCohorts\Pages\CreateUpdateCohort;
use App\Filament\Resources\UpdateCohorts\Pages\EditUpdateCohort;
use App\Filament\Resources\UpdateCohorts\Pages\ListUpdateCohorts;
use App\Filament\Resources\UpdateCohorts\Pages\ViewUpdateCohort;
use App\Filament\Resources\UpdateCohorts\Schemas\UpdateCohortForm;
use App\Filament\Resources\UpdateCohorts\Schemas\UpdateCohortInfolist;
use App\Filament\Resources\UpdateCohorts\Tables\UpdateCohortsTable;
use App\Models\UpdateCohort;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UpdateCohortResource extends Resource
{
    protected static ?string $model = UpdateCohort::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static ?string $recordTitleAttribute = 'course_date';

    public static function form(Schema $schema): Schema
    {
        return UpdateCohortForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UpdateCohortInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UpdateCohortsTable::configure($table);
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
            'index' => ListUpdateCohorts::route('/'),
            'create' => CreateUpdateCohort::route('/create'),
            'view' => ViewUpdateCohort::route('/{record}'),
            'edit' => EditUpdateCohort::route('/{record}/edit'),
        ];
    }
}
