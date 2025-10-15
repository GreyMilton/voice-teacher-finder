<?php

use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $faqs = Faq::factory(random_int(1, 10))->create();

    livewire(ListFaqs::class)
        ->assertOk()
        ->assertCanSeeTableRecords($faqs);
});

test('admin can access page', function () {
    Faq::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListFaqs::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Faq::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListFaqs::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
