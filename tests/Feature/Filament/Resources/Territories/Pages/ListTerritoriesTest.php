<?php

use App\Filament\Resources\Territories\Pages\ListTerritories;
use App\Models\Territory;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $territories = Territory::limit(random_int(1, 10))->get();

    livewire(ListTerritories::class)
        ->assertOk()
        ->assertCanSeeTableRecords($territories);
});
