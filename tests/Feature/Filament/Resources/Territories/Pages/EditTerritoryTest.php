<?php

use App\Filament\Resources\Territories\Pages\EditTerritory;
use App\Models\Territory;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $territory = Territory::inRandomOrder()->first();

    livewire(EditTerritory::class, [
        'record' => $territory->id,
    ])->assertOk();
});
