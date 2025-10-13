<?php

use App\Filament\Resources\Teachers\Pages\CreateTeacher;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateTeacher::class)
        ->assertOk();
});
