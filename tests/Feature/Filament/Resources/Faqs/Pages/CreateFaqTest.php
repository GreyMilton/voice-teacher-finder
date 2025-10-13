<?php

use App\Filament\Resources\Faqs\Pages\CreateFaq;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateFaq::class)
        ->assertOk();
});
