<?php

use App\Filament\Resources\Languages\Pages\CreateLanguage;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateLanguage::class)
        ->assertOk();
});
