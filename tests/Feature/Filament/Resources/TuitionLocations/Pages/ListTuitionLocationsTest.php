<?php

use App\Filament\Resources\TuitionLocations\Pages\ListTuitionLocations;
use App\Models\Territory;
use App\Models\TuitionLocation;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $tuitionLocations = TuitionLocation::factory(random_int(1, 10))->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    livewire(ListTuitionLocations::class)
        ->assertOk()
        ->assertCanSeeTableRecords($tuitionLocations);
});

test('admin can access page', function () {
    TuitionLocation::factory(random_int(1, 10))->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListTuitionLocations::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    TuitionLocation::factory(random_int(1, 10))->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    $this->assertGuest()
        ->get(ListTuitionLocations::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
