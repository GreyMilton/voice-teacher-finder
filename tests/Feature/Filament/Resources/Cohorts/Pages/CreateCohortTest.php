<?php

use App\Filament\Resources\Cohorts\Pages\CreateCohort;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateCohort::class)
        ->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(CreateCohort::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $this->assertGuest()
        ->get(CreateCohort::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
