<?php

use App\Filament\Resources\Territories\Pages\ListTerritories;
use App\Models\Territory;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $territories = Territory::limit(random_int(1, 10))->get();

    livewire(ListTerritories::class)
        ->assertOk()
        ->assertCanSeeTableRecords($territories);
});

test('admin can access page', function () {
    Territory::limit(random_int(1, 10))->get();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListTerritories::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Territory::limit(random_int(1, 10))->get();

    $this->assertGuest()
        ->get(ListTerritories::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
