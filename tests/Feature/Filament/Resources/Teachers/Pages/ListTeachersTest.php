<?php

use App\Filament\Resources\Teachers\Pages\ListTeachers;
use App\Models\Teacher;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $teachers = Teacher::factory(random_int(1, 10))->create();

    livewire(ListTeachers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($teachers);
});
