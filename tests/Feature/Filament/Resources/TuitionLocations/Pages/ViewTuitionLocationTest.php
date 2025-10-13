<?php

use App\Filament\Resources\TuitionLocations\Pages\ViewTuitionLocation;
use App\Models\Territory;
use App\Models\TuitionLocation;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $tuitionLocation = TuitionLocation::factory()->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    livewire(ViewTuitionLocation::class, [
        'record' => $tuitionLocation->id,
    ])->assertOk();
});
