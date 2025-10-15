<?php

use App\Filament\Resources\Cohorts\Pages\ViewCohort;
use App\Models\Cohort;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $cohort = Cohort::factory()->create();

    livewire(ViewCohort::class, [
        'record' => $cohort->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $cohort = Cohort::factory()->create();

    $this->actingAs($admin)
        ->get(ViewCohort::getUrl([
            'record' => $cohort->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $cohort = Cohort::factory()->create();

    $this->assertGuest()
        ->get(ViewCohort::getUrl([
            'record' => $cohort->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
