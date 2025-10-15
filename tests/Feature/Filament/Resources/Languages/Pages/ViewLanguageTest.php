<?php

use App\Filament\Resources\Languages\Pages\ViewLanguage;
use App\Models\Language;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $language = Language::inRandomOrder()->first();

    livewire(ViewLanguage::class, [
        'record' => $language->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $language = Language::inRandomOrder()->first();

    $this->actingAs($admin)
        ->get(ViewLanguage::getUrl([
            'record' => $language->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $language = Language::inRandomOrder()->first();

    $this->assertGuest()
        ->get(ViewLanguage::getUrl([
            'record' => $language->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
