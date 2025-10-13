<?php

use App\Filament\Resources\Languages\Pages\EditLanguage;
use App\Models\Language;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $language = Language::inRandomOrder()->first();

    livewire(EditLanguage::class, [
        'record' => $language->id,
    ])->assertOk();
});
