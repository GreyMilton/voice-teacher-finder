<?php

use App\Filament\Resources\Cohorts\Pages\ListCohorts;
use App\Models\Cohort;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $cohorts = Cohort::factory(random_int(1, 10))->create();

    livewire(ListCohorts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($cohorts);
});

test('admin can access page', function () {
    Cohort::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListCohorts::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Cohort::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListCohorts::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
