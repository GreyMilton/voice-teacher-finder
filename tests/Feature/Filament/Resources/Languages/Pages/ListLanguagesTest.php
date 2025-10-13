<?php

use App\Filament\Resources\Languages\Pages\ListLanguages;
use App\Models\Language;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $languages = Language::limit(random_int(1, 10))->get();

    livewire(ListLanguages::class)
        ->assertOk()
        ->assertCanSeeTableRecords($languages);
});
