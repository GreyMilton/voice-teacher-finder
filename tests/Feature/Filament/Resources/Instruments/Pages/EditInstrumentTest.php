<?php

use App\Filament\Resources\Instruments\Pages\EditInstrument;
use App\Models\Instrument;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $instrument = Instrument::inRandomOrder()->first();

    livewire(EditInstrument::class, [
        'record' => $instrument->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $instrument = Instrument::inRandomOrder()->first();

    $this->actingAs($admin)
        ->get(EditInstrument::getUrl([
            'record' => $instrument->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $instrument = Instrument::inRandomOrder()->first();

    $this->assertGuest()
        ->get(EditInstrument::getUrl([
            'record' => $instrument->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
