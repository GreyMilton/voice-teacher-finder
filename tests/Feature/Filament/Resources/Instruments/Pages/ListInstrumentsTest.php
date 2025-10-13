<?php

use App\Filament\Resources\Instruments\Pages\ListInstruments;
use App\Models\Instrument;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $instruments = Instrument::limit(random_int(1, 10))->get();

    livewire(ListInstruments::class)
        ->assertOk()
        ->assertCanSeeTableRecords($instruments);
});
