<?php

use App\Filament\Resources\TuitionLocations\Pages\EditTuitionLocation;
use App\Models\Territory;
use App\Models\TuitionLocation;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $tuitionLocation = TuitionLocation::factory()->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    livewire(EditTuitionLocation::class, [
        'record' => $tuitionLocation->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $tuitionLocation = TuitionLocation::factory()->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    $this->actingAs($admin)
        ->get(EditTuitionLocation::getUrl([
            'record' => $tuitionLocation->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $tuitionLocation = TuitionLocation::factory()->create([
        'territory_id' => Territory::inRandomOrder()->first()->id,
    ]);

    $this->assertGuest()
        ->get(EditTuitionLocation::getUrl([
            'record' => $tuitionLocation->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
