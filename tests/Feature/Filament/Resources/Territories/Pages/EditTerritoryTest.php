<?php

use App\Filament\Resources\Territories\Pages\EditTerritory;
use App\Models\Territory;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $territory = Territory::inRandomOrder()->first();

    livewire(EditTerritory::class, [
        'record' => $territory->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $territory = Territory::inRandomOrder()->first();

    $this->actingAs($admin)
        ->get(EditTerritory::getUrl([
            'record' => $territory->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $territory = Territory::inRandomOrder()->first();

    $this->assertGuest()
        ->get(EditTerritory::getUrl([
            'record' => $territory->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
