<?php

use App\Filament\Resources\Teachers\Pages\EditTeacher;
use App\Models\Teacher;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $teacher = Teacher::factory()->create();

    livewire(EditTeacher::class, [
        'record' => $teacher->id,
    ])->assertOk();
});
