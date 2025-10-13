<?php

use App\Filament\Resources\Territories\Pages\CreateTerritory;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateTerritory::class)
        ->assertOk();
});
