<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $users = User::factory(random_int(1, 10))->create();

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});
