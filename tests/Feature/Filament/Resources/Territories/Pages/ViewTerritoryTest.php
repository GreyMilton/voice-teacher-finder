<?php

use App\Filament\Resources\Territories\Pages\ViewTerritory;
use App\Models\Territory;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $territory = Territory::inRandomOrder()->first();

    livewire(ViewTerritory::class, [
        'record' => $territory->id,
    ])->assertOk();
});
