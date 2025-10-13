<?php

use App\Filament\Resources\TuitionLocations\Pages\CreateTuitionLocation;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateTuitionLocation::class)
        ->assertOk();
});
