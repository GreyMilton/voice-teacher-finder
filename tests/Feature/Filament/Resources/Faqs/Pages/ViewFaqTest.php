<?php

use App\Filament\Resources\Faqs\Pages\ViewFaq;
use App\Models\Faq;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $faq = Faq::factory()->create();

    livewire(ViewFaq::class, [
        'record' => $faq->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $faq = Faq::factory()->create();

    $this->actingAs($admin)
        ->get(ViewFaq::getUrl([
            'record' => $faq->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $faq = Faq::factory()->create();

    $this->assertGuest()
        ->get(ViewFaq::getUrl([
            'record' => $faq->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
