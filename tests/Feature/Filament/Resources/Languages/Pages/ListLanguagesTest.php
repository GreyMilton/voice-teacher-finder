<?php

use App\Filament\Resources\Languages\Pages\ListLanguages;
use App\Models\Language;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $languages = Language::limit(random_int(1, 10))->get();

    livewire(ListLanguages::class)
        ->assertOk()
        ->assertCanSeeTableRecords($languages);
});

test('admin can access page', function () {
    Language::limit(random_int(1, 10))->get();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListLanguages::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Language::limit(random_int(1, 10))->get();

    $this->assertGuest()
        ->get(ListLanguages::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
