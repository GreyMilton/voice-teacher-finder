<?php

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $user = User::factory()->create();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->get(EditUser::getUrl([
            'record' => $user->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $user = User::factory()->create();

    $this->assertGuest()
        ->get(EditUser::getUrl([
            'record' => $user->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
