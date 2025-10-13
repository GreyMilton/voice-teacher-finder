<?php

use App\Filament\Resources\Cohorts\Pages\ViewCohort;
use App\Models\Cohort;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $cohort = Cohort::factory()->create();

    livewire(ViewCohort::class, [
        'record' => $cohort->id,
    ])->assertOk();
});
