<?php

use App\Filament\Resources\TuitionLocations\Pages\ListTuitionLocations;
use App\Models\Territory;
use App\Models\TuitionLocation;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $tuitionLocations = TuitionLocation::factory(random_int(1, 10))->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    livewire(ListTuitionLocations::class)
        ->assertOk()
        ->assertCanSeeTableRecords($tuitionLocations);
});
