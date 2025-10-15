<?php

use App\Filament\Resources\TuitionLocations\Pages\CreateTuitionLocation;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateTuitionLocation::class)
        ->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(CreateTuitionLocation::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $this->assertGuest()
        ->get(CreateTuitionLocation::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
