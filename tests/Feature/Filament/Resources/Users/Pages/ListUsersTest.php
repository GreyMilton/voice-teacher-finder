<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $users = User::factory(random_int(1, 10))->create();

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});

test('admin can access page', function () {
    User::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListUsers::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    User::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListUsers::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
