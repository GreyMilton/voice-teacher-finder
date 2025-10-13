<?php

use App\Filament\Resources\Instruments\Pages\ViewInstrument;
use App\Models\Instrument;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $instrument = Instrument::inRandomOrder()->first();

    livewire(ViewInstrument::class, [
        'record' => $instrument->id,
    ])->assertOk();
});
