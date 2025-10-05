<?php

namespace App\Filament\Resources\Teachers;

use App\Filament\Resources\Teachers\Pages\CreateTeacher;
use App\Filament\Resources\Teachers\Pages\EditTeacher;
use App\Filament\Resources\Teachers\Pages\ListTeachers;
use App\Filament\Resources\Teachers\Pages\ViewTeacher;
use App\Filament\Resources\Teachers\RelationManagers\AuthorisationStatusesRelationManager;
use App\Filament\Resources\Teachers\RelationManagers\CohortsRelationManager;
use App\Filament\Resources\Teachers\RelationManagers\InstrumentsRelationManager;
use App\Filament\Resources\Teachers\RelationManagers\LanguagesSungRelationManager;
use App\Filament\Resources\Teachers\RelationManagers\LanguagesTeachesInRelationManager;
use App\Filament\Resources\Teachers\RelationManagers\TuitionLocationsRelationManager;
use App\Filament\Resources\Teachers\Schemas\TeacherForm;
use App\Filament\Resources\Teachers\Schemas\TeacherInfolist;
use App\Filament\Resources\Teachers\Tables\TeachersTable;
use App\Models\Teacher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TeacherForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeacherInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeachersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AuthorisationStatusesRelationManager::class,
            CohortsRelationManager::class,
            InstrumentsRelationManager::class,
            LanguagesTeachesInRelationManager::class,
            LanguagesSungRelationManager::class,
            TuitionLocationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeachers::route('/'),
            'create' => CreateTeacher::route('/create'),
            'view' => ViewTeacher::route('/{record}'),
            'edit' => EditTeacher::route('/{record}/edit'),
        ];
    }
}
