<?php

use App\Filament\Resources\Instruments\Pages\CreateInstrument;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateInstrument::class)
        ->assertOk();
});
