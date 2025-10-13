<?php

use App\Filament\Resources\Cohorts\Pages\CreateCohort;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateCohort::class)
        ->assertOk();
});
