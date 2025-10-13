<?php

use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $faqs = Faq::factory(random_int(1, 10))->create();

    livewire(ListFaqs::class)
        ->assertOk()
        ->assertCanSeeTableRecords($faqs);
});
