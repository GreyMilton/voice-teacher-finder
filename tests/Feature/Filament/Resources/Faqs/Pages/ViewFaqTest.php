<?php

use App\Filament\Resources\Faqs\Pages\ViewFaq;
use App\Models\Faq;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $faq = Faq::factory()->create();

    livewire(ViewFaq::class, [
        'record' => $faq->id,
    ])->assertOk();
});
