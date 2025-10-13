<?php

use App\Filament\Resources\Teachers\Pages\ViewTeacher;
use App\Models\Teacher;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $teacher = Teacher::factory()->create();

    livewire(ViewTeacher::class, [
        'record' => $teacher->id,
    ])->assertOk();
});
