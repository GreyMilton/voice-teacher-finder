<?php

use App\Filament\Resources\Instruments\Pages\ListInstruments;
use App\Models\Instrument;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $instruments = Instrument::limit(random_int(1, 10))->get();

    livewire(ListInstruments::class)
        ->assertOk()
        ->assertCanSeeTableRecords($instruments);
});

test('admin can access page', function () {
    Instrument::limit(random_int(1, 10))->get();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListInstruments::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Instrument::limit(random_int(1, 10))->get();

    $this->assertGuest()
        ->get(ListInstruments::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
