<?php

use App\Filament\Resources\Cohorts\Pages\ListCohorts;
use App\Models\Cohort;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $cohorts = Cohort::factory(random_int(1, 10))->create();

    livewire(ListCohorts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($cohorts);
});
