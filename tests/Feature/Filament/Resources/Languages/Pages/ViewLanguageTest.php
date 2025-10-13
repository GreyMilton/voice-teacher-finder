<?php

use App\Filament\Resources\Languages\Pages\ViewLanguage;
use App\Models\Language;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $language = Language::inRandomOrder()->first();

    livewire(ViewLanguage::class, [
        'record' => $language->id,
    ])->assertOk();
});
