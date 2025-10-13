<?php

use App\Filament\Resources\Users\Pages\CreateUser;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    livewire(CreateUser::class)
        ->assertOk();
});
