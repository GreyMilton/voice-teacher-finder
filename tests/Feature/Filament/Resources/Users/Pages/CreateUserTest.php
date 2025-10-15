<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateUser::class)
        ->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(CreateUser::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $this->assertGuest()
        ->get(CreateUser::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
